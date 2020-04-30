<?php


namespace admin\controllers;


use admin\models\Auctions;
use admin\models\Categories;
use admin\models\Products;
use admin\models\StoreProductsModel;
use admin\models\StoresModel;
use admin\models\User;
use yii\db\Exception;
use yii\web\Controller;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'basicAuth' => [
                'class' => \yii\filters\auth\HttpBasicAuth::className(),
                'except' => [
                    'test',
                    'get-products',
                    'get-store-products',
                    'get-categories',
                    'get-popular-categories',
                    'get-popular-products',
                    'stores',
                    'get-fresh-products',
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
    public function actionRegistration(){
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');
        $email = \Yii::$app->request->post('email');
        $phone_number = \Yii::$app->request->post('phone');
        $user = new User();
        $user->username = $login;
        $user->email = $email;
        $user->password_hash = \Yii::$app->security->generatePasswordHash($password);
        $user->phone_number = $phone_number;
        $user->save();
    }
    public function actionLogin(){
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');
        $address = \Yii::$app->request->post('address');
        $coords = self::getLatLonByAddress($address);
        $lat = $coords[0];
        $lng = $coords[1];
        $user = User::findOne(['username'=>$login]);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
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
                'store_products.id',
                'products.title',
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
                'store_products.id',
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
    public  function actionStores(){
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