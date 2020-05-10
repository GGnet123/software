<?php


namespace admin\controllers;


use admin\models\OrderAmounts;
use admin\models\Orders;
use admin\models\Products;
use admin\models\Runners;
use admin\models\StoreProductsModel;
use admin\models\StoresModel;
use admin\models\User;
use ArrayObject;
use phpDocumentor\Reflection\Types\Integer;
use yii\db\Exception;
use yii\debug\panels\MailPanel;
use yii\web\Controller;
use yii\web\HttpException;
use function GuzzleHttp\Psr7\str;

class RunnersController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionLogin(){
        $login = \Yii::$app->request->getBodyParam('login');
        $password = \Yii::$app->request->getBodyParam("password");

        $runner = Runners::findOne(['username'=>$login]);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (\Yii::$app->security->validatePassword($password, $runner->password_hash)){
            $runner->status = 'online';
            $runner->save();
            return [
                'token'=>$runner->auth_token,
                'success'=>true
            ];
        }
        else{
            \Yii::$app->response->statusCode = 401;
            throw new HttpException(401);
        }
    }

    public function actionOrders($page){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $token = \Yii::$app->request->getHeaders()['x-auth-token'];
        $runner = Runners::findOne(['auth_token'=>$token]);
        $offset = ($page-1)*10;
        $query = \Yii::$app->db->createCommand("SELECT *, getDistance(lat,lng,$runner->latitude, $runner->longitude) as distance FROM orders order by distance limit 10 offset $offset");
        $orders = json_decode(json_encode($query->queryAll()));
        if ($runner){
            for ($i=0; $i<count($orders); $i++){
                $array_items = [];
                $amount = OrderAmounts::findOne(['order_id'=>$orders[$i]->id]);
                $arr_cnt = explode(';', $amount->amount);
                foreach (explode(';', $orders[$i]->items) as $key => $item){
                    $product = Products::findOne(['id'=>$item]);
                    if ($product) {
                        $product_price = StoreProductsModel::findOne(['store_id'=>$orders[$i]->store_id, 'id'=>$product->id]);
                        $array_items[] = [
                            'title'=>$product->title,
                            'price'=>$product_price->price,
                            'cnt'=> $arr_cnt[$key],
                            'grams'=>'0',
                            'barcode'=>$product->barcode
                        ];
                    }
                }
                $orders[$i]->items = $array_items;
            }

            $ords['orders'] = $orders;
            return $ords;
        }
        else{
            throw new HttpException(401);
        }
    }
    public function actionVerifyOrder(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $code = \Yii::$app->request->post("code");
        $auth_token = \Yii::$app->request->getHeaders()['x-auth-token'];
        $runner = Runners::findOne(['auth_token'=>$auth_token]);
        $model = Orders::findOne(['id'=>$runner->order_id]);
        if ($runner->code == $code){
            $runner->busy = false;
            $runner->order_id = null;
            $runner->money += (int)(($model->total + $model->delivery_price) * 0.1);
            if ($runner->rating+5<100){
                $runner->rating += 5;
            } else{
                $runner->rating = 100;
            }
            $model->status_id = 3;
            $model->status = Orders::$statuses[3];
            $model->save();
            $runner->code = null;
            $runner->save();
            return [
                'success' => true
            ];
        }
        return [
            'success' => false
        ];
    }
    public function actionShowCode(){
        $user = Runners::findOne(['id'=>1]);
        return 'Code: ' . $user->code;
    }
    public function actionOrderChangeStatus(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $auth_token = \Yii::$app->request->getHeaders()['x-auth-token'];
        $runner = Runners::findOne(['auth_token'=>$auth_token]);
        if ($runner){
            $id = \Yii::$app->request->getBodyParam('id');
            $status_id = \Yii::$app->request->getBodyParam('status_id');
            $model = Orders::findOne($id);
            if (!$model){
                throw new Exception("Invalid order id");
                die();
            }
            if ($status_id == 4){
                $runner->busy = false;
                $runner->money -= 200;
                $runner->order_id = null;
                if ($runner->rating-10>0){
                    $runner->rating -= 5;
                } else{
                    $runner->rating = 0;
                }
            }
            if ($status_id == 3){
                $basic  = new \Nexmo\Client\Credentials\Basic('bbfa49d1', 'lJ6Am96jovnHir8I');
                $client = new \Nexmo\Client($basic);
                $order_client = Orders::findOne(['id'=>$runner->order_id]);
                $code = rand(1000,9999);
                $runner->code = $code;
                $runner->save();
                $phone = $order_client->client_phone;
                $text = 'EzShop';
                /*$message = $client->message()->sendText(
                    $phone,
                    $text,
                    $code
                );
                var_dump($message->getDeliveryError());*/
                return [
                    'success'=>true
                ];
            }
            if ($status_id == 2 && !$runner->busy){
                $runner->busy = true;
                $runner->order_id = $id;
            }
            $model->status_id = $status_id;
            $model->status = Orders::$statuses[$status_id];
            $model->runner = $runner->username;
            $runner->save();
            $model->save();

            return [
              'success'=>true
            ];
        }
        else{
            \Yii::$app->response->setStatusCode(401, ['unauthorized request']);
            throw new HttpException(401);
        }
    }
    public function actionDeviceToken(){
        $token = \Yii::$app->request->post('token');
        $auth_key =  \Yii::$app->request->getHeaders()['x-auth-token'];
        $model = Runners::findOne(['auth_token'=>$auth_key]);
        if ($model->device_id!=$token){
            $model->device_id = $token;
            $model->save();
        }
    }
    public function actionStores() {
        $stores = StoresModel::find()->all();
        return $this->render('stores', ['stores'=>$stores]);
    }

    public function actionRunnerProfile(){
        $auth_token =  \Yii::$app->request->getHeaders()['x-auth-token'];
        $runner = Runners::findOne(['auth_token'=>$auth_token]);
        if ($runner->order_id){
            $order = Orders::findOne(['id'=>$runner->order_id]);
            foreach (explode(' ', $order->items) as $item){
                $product = Products::findOne($item);
                $array_items[] = [
                    'title'=>$product->title,
                    'price'=>$product->price,
                    'cnt'=>'1',
                    'grams'=>'0',
                    'barcode'=>$product->barcode
                ];
            }
            $order->items = $array_items;
            $profile = [
                'id'=>$runner->id,
                'rating'=>$runner->rating,
                'money'=>$runner->money,
                'username'=>$runner->username,
                'order'=>$order
            ];
        }
        else{
            $profile = [
                'id'=>$runner->id,
                'rating'=>$runner->rating,
                'money'=>$runner->money,
                'username'=>$runner->username,
                'order'=>null
            ];
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $profile;
    }
    public function actionIsBusy(){
        $auth_token =  \Yii::$app->request->getHeaders()['x-auth-token'];
        $model = Runners::findOne(['auth_token'=>$auth_token]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'busy'=>$model->busy,
            'username'=>$model->username
        ];
    }
    public function actionLocation(){
        $lat = \Yii::$app->request->post('lat');
        $lng = \Yii::$app->request->post('lng');
        $auth_token =  \Yii::$app->request->getHeaders()['x-auth-token'];
        $model = Runners::findOne(['auth_token'=>$auth_token]);
        $model->latitude = $lat;
        $model->longitude = $lng;
        $model->save();
    }
}