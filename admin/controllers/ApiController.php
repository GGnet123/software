<?php


namespace admin\controllers;


use admin\models\Categories;
use admin\models\Products;
use admin\models\User;
use yii\web\Controller;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionLogin(){
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');
        $lat = \Yii::$app->request->post('lat');
        $lng = \Yii::$app->request->post('lng');
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
    public function actionGetProducts(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Products::find()->all();
    }
    public function actionGetCategories(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Categories::find()->all();
    }
    public function actionGetProductsByCategory($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $category = Categories::findOne($id)->products;
        return $category;
    }
    public function actionGetPopularCategories(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $category = Categories::find()->where(['popular'=>true])->all();
        return $category;
    }
    public function actionGetPopularProducts(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $products = Products::find()->where(['popular'=>true])->all();
        return $products;
    }
}