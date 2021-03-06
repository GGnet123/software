<?php


namespace admin\models;


use yii\db\ActiveRecord;

class Orders extends ActiveRecord
{
    public static $statuses = [
        1 => 'В ожидании',
        2 => 'Принято',
        3 => 'Доставлено',
        4 => 'Отменено'
    ];

    public static function tableName()
    {
        return 'orders'; // TODO: Change the autogenerated stub
    }
    public function rules()
    {
        return [
            [
                ['client_name','client_address','is_card','client_phone','taken','delivery_price'],'required'
            ],
            [
                ['id','total','status','status_id','is_card','date','delivery_type','items','runner'],'safe'
            ]
        ]; // TODO: Change the autogenerated stub
    }
    public function beforeSave($insert)
    {
        if ($this->isNewRecord){
            $this->date = date('d/m/Y');
        }
        return true; // TODO: Change the autogenerated stub
    }
}