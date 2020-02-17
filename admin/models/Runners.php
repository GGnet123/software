<?php


namespace admin\models;


use yii\db\ActiveRecord;

class Runners extends ActiveRecord
{
    public static function tableName()
    {
        return 'runners'; // TODO: Change the autogenerated stub
    }
    public function rules()
    {
        return [
            [['username'],'unique'],
            [['username','password_hash'], 'required'],
            [['id','auth_token','rating','status_id','status','money','latitude','longitude','busy','order_id'], 'safe']
        ]; // TODO: Change the autogenerated stub
    }
    public function beforeSave($insert)
    {
        if ($this->isNewRecord){
            $this->auth_token = \Yii::$app->security->generateRandomString();
            $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password_hash);
        } else {
            if (!Runners::findOne(['password_hash' => $this->password_hash])) {
                $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password_hash);
            }
        }
        return true; // TODO: Change the autogenerated stub
    }
}