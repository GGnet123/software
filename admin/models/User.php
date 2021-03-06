<?php

namespace admin\models;

use \yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public static $roles = [
        'admin'=>'admin',
        'user'=>'user'
    ];
    public static function tableName()
    {
        return 'user'; // TODO: Change the autogenerated stub
    }
    public function rules()
    {
        return [
            [['username','password_hash','email','role','phone_number'],'required'],
            [['status','device_id','bonuses'],'safe'],
        ]; // TODO: Change the autogenerated stub
    }
    public function beforeSave($insert)
    {
        if($this->isNewRecord){
            $this->created_at = date("d/m/Y");
            $this->auth_key = \Yii::$app->security->generateRandomString();
            $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password_hash);
        } else{
            if (!User::findOne(['password_hash'=>$this->password_hash])){
                $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password_hash);
            }
            $this->updated_at = date("d/m/Y");
        }
        return true; // TODO: Change the autogenerated stub
    }

}