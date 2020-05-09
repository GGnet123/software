<?php


namespace admin\controllers;


use admin\models\Auctions;
use admin\models\Categories;
use admin\models\OrderAmounts;
use admin\models\Orders;
use admin\models\Products;
use admin\models\StoreProductsModel;
use admin\models\StoresModel;
use admin\models\User;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Controller;
use function MongoDB\BSON\toJSON;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public static function allowedDomains()
    {
        return [
             '*'
        ];
    }

    public function behaviors()
    {
        return [
            'corsFilter'  => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    'Origin'                           => static::allowedDomains(),
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    /*'Access-Control-Allow-Credentials' => true,*/
                    'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
                ],
            ],
            'basicAuth' => [
                'class' => \yii\filters\auth\HttpBasicAuth::className(),
                'except' => [
                    'get-products',
                    'get-store-products',
                    'get-categories',
                    'get-popular-categories',
                    'get-popular-products',
                    'stores',
                    'get-fresh-products',
                    'login',
                    'registration',
                    'options'
                ],
                'auth' => function ($username, $password) {
                    $user = \common\models\User::find()->where(['username' => $username])->one();
                    if ($user->validatePassword($password)) {
                        return $user;
                    }
                    return null;
                },
            ],
        ];
    }
    public function actionMakeOrder(){
        $products = \Yii::$app->request->post('products');
        $total = \Yii::$app->request->post('total');
        $is_card = \Yii::$app->request->post('isCard');
        $address = \Yii::$app->request->post('address');
        $phone = \Yii::$app->request->post('phone');
        $taken = \Yii::$app->request->post('taken');
        $kv = \Yii::$app->request->post('kv');
        $dom = \Yii::$app->request->post('dom');
        $client_name = \Yii::$app->request->post('name');
        $client_surname = \Yii::$app->request->post('surname');
        $client = User::findOne(['id'=>\Yii::$app->user->id]);
        $client->bonuses += $total * 0.01;
        $client->save();
        $coords = self::getLatLonByAddress($address);
        $query = \Yii::$app->db->createCommand('SELECT *, getDistance(lat, lng, :lat, :lng) as distance FROM stores order by distance', [
            ':lat' => $coords[0],
            ':lng' => $coords[1]
        ]);
        $store = json_decode(json_encode($query->queryAll()));

        $order = new Orders();
        $order->client_name = $client_name . ' ' . $client_surname;
        $order->client_address = str_replace('Казахстан, Алматы,','', $address) . ', Подъезд ' . $dom . ', кв ' . $kv;
        $order->store_id = $store[0]->id;
        $order->delivery_price = $store[0]->distance * 2000;
        $order->total = $total;
        $order->is_card = $is_card;
        $order->taken = $is_card == true ? 0 : $taken;
        $order->client_phone = $phone;
        $order->lat = $coords[0];
        $order->lng = $coords[1];
        $str = '';
        $str2 = '';

        foreach ($products as $product){
            $str .= $product['id'] . ';';
            $str2 .= $product['amount'] . ';';
        }
        $order->items = $str;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!$order->save()) {
            var_dump($order->errors);
            return 'false';
        }
        $amount = new OrderAmounts();
        $amount->order_id = $order->id;
        $amount->amount = $str2;
        if ($amount->save()) {
            return $total * 0.01;
        };
    }
    public function actionRegistration(){
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');
        $email = \Yii::$app->request->post('email');
        $phone_number = \Yii::$app->request->post('phone');
        $user = new User();
        $user->username = $login;
        $user->email = $email;
        $user->role = 'user';
        $user->password_hash = $password;
        $user->phone_number = $phone_number;
        try{
            if ($user->save()){
                return 'true';
            } else{
                return 'false';
            };
        } catch (\Exception $exception){
            return $exception->getCode();
        }
    }
    public function actionLogin(){
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');
        $address = \Yii::$app->request->post('address');
        if ($address){
            $coords = self::getLatLonByAddress($address);
            $lat = $coords[0];
            $lng = $coords[1];
        }
        $user = User::findOne(['username'=>$login]);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($user){
            if (\Yii::$app->security->validatePassword($password, $user->password_hash)){
                if ($lat && $lng){
                    $user->lat = $lat;
                    $user->lng = $lng;
                }
                return [
                    'success'=>true,
                    'user'=>$user
                ];
            } else{
                return [
                    'success'=>false
                ];
            }
        }
        return [
            'success'=>false
        ];
    }
    public function actionParticipate(){
        $id = \Yii::$app->request->post('id');
        $auction = Auctions::findOne(['id'=>$id]);
        $user = User::findOne(['id'=>\Yii::$app->user->id]);
        if ($user->bonuses >= $auction->price){
            $auction->participants_ids .= $user->id . ',';
            $user->bonuses -= $auction->price;
            $user->save();
            $auction->save();
        } else{
            throw new Exception('Не достаточно бонусов для участия');
        }
    }
    public function actionGetWinner($id){
        $auction = Auctions::findOne(['id'=>$id]);
        $participants = explode(',', $auction->participants_ids);
        $random = rand(0, sizeof($participants));
        $winner = User::findOne(['id'=>$participants[$random]]);
        $auction->is_active = false;
        $auction->winner_id = $winner->id;
        $auction->save();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $winner;
    }
    public static function getLatLonByAddress($address)
    {

        $params = array(
            'apikey' => '14407baf-56ac-4e53-95fb-0e22a170991c',
            'geocode' => $address,
            'format'  => 'json',
            'results' => 1,
        );
        try {
            $response = json_decode(file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params)));

            if ($response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0) {
                return array_reverse(explode(' ', $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos));
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return false;
    }
    public function actionGetProducts($slug = null){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($slug){
            $category = Categories::findOne(['slug'=>$slug]);
            return Products::findAll(['category_id'=>$category->id, 'is_active'=>1]);
        }
        return Products::find()->where(['is_active'=>1])->all();
    }
    public function actionGetStoreProducts($address, $slug = null){
        $coords = self::getLatLonByAddress($address);
        $query = \Yii::$app->db->createCommand('SELECT *, getDistance(lat, lng, :lat, :lng) as distance FROM stores order by distance', [
            ':lat' => $coords[0],
            ':lng' => $coords[1]
        ]);
        $store = json_decode(json_encode($query->queryAll()));

        $products = Products::find()->rightJoin('store_products', 'products.id = store_products.product_id')
            ->select([
                'products.id',
                'products.title',
                'store_products.product_id',
                'products.short_description',
                'store_products.price',
                'store_products.amount',
                'store_products.grams',
                'products.image',
                'products.popular',
            ])
            ->where(['store_products.store_id'=>$store[0]->id,'products.is_active'=>1]);

        if ($slug){
            $category = Categories::findOne(['slug'=>$slug]);
            $products->andWhere(['category_id'=>$category->id]);
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $products->all();
    }
    public function actionGetCategories(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Categories::find()->all();
    }
    public function actionGetPopularCategories(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $category = Categories::find()->where(['popular'=>1])->all();
        return $category;
    }
    public function actionGetPopularProducts($address){
        $coords = self::getLatLonByAddress($address);
        $query = \Yii::$app->db->createCommand('SELECT *, getDistance(lat, lng, :lat, :lng) as distance FROM stores order by distance', [
            ':lat' => $coords[0],
            ':lng' => $coords[1]
        ]);
        $store = json_decode(json_encode($query->queryAll()));

        $products = Products::find()->rightJoin('store_products', 'products.id = store_products.product_id')
            ->select([
                'products.id',
                'products.title',
                'products.short_description',
                'store_products.price',
                'store_products.amount',
                'store_products.grams',
                'products.image',
                'products.popular',
            ])
            ->where(['store_products.store_id'=>$store[0]->id,'products.is_active'=>1,'products.popular'=>1]);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $products->all();
    }
    public function actionStores(){
        $stores = StoresModel::find()->all();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $stores;
    }
    public function actionGetFreshProducts(){
        $products = Products::find()->where(['category_id' => 4])->orWhere(['category_id'=>8])->all();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $products;
    }
}