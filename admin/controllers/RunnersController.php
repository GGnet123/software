<?php


namespace admin\controllers;


use admin\models\Orders;
use admin\models\Products;
use admin\models\Runners;
use admin\models\User;
use phpDocumentor\Reflection\Types\Integer;
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
        $token = \Yii::$app->request->getHeaders()['x-auth-token'];
        $runner = Runners::findOne(['auth_token'=>$token]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $orders = Orders::find()
            ->orderBy(['status_id' => SORT_ASC])
            ->limit(10)
            ->offset(((($page-1) * 10 )))
            ->all();
        if ($runner){
            for ($i=0; $i<count($orders); $i++){
                $array_items = [];
                foreach (explode(' ',$orders[$i]->items) as $item){
                    $product = Products::findOne($item);
                    $array_items[] = [
                        'title'=>$product->title,
                        'price'=>$product->price,
                        'cnt'=>'1',
                        'grams'=>'0',
                        'barcode'=>$product->barcode
                    ];
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

    public function actionOrderChangeStatus(){
        $auth_token = \Yii::$app->request->getHeaders()['x-auth-token'];
        $runner = Runners::findOne(['auth_token'=>$auth_token]);
        if ($runner){
            $id = \Yii::$app->request->getBodyParam('id');
            $status_id = \Yii::$app->request->getBodyParam('status_id');
            $model = Orders::findOne($id);
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
                $runner->busy = false;
                $runner->order_id = null;
                $runner->money += (int)(($model->total + $model->delivery_price) * 0.1);
                if ($runner->rating+5<100){
                    $runner->rating += 5;
                } else{
                    $runner->rating = 100;
                }
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