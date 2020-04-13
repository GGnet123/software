<?php


namespace admin\controllers;


use common\models\MobileUsers;
use yii\web\Controller;

class MobileController extends Controller
{
    public function actionLogin(){
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');
        $user = MobileUsers::findOne(['login'=>$login, 'password'=>$password]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($user){
            return [
                'success' => true,
                'user' => $user
            ];
        }
        return [
            'success' => false
        ];
    }
    public function actionRegister(){
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');

        $user = new MobileUsers(['login'=>$login, 'password'=>$password]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($user->save()){
            return [
                'success' => true
            ];
        }
        return [
            'success' => false
        ];
    }
    public  function actionGetCatalog(){


    }

    public function actionEditProfile(){
        $name = \Yii::$app->request->post("name");
        $surname = \Yii::$app->request->post("surname");
        $age = \Yii::$app->request->post("age");
        $description = \Yii::$app->request->post("description");
        $user_id = \Yii::$app->request->getHeaders()['token'];

        $user = MobileUsers::findOne(['id'=>$user_id]);
        $user->name = $name;
        $user->surname = $surname;
        $user->description = $description;
        $user->age = $age;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if($user->save())
            return [
                'success' => true
            ];
    }
}