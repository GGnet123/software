<?php


namespace common\models;


use yii\db\ActiveRecord;

class MobileUsers extends ActiveRecord
{
    public static function tableName()
    {
        return 'mobile.user'; // TODO: Change the autogenerated stub
    }
}