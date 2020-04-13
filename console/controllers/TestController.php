<?php
namespace console\controllers;

use admin\models\Orders;

class TestController extends \yii\web\Controller
{
    public function actionTest(){
        $client_phone = Orders::findOne(['id'=>3])->client_phone;
        var_dump($client_phone);

    }

}