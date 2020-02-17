<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

<!--    --><?//= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'client_name')->textInput(['maxlength' => true,'id'=>'name']) ?>

    <?= $form->field($model, 'client_address')->textInput(['maxlength' => true,'id'=>'address']) ?>

<!--    --><?//= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_card')->checkbox(['id'=>'card']) ?>

<!--    --><?//= $form->field($model, 'total')->textInput() ?>

<!--    --><?//= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'status_id')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'delivery_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'delivery_price')->textInput(['id'=>'delivery']) ?>

    <?= $form->field($model, 'client_phone')->textInput(['maxlength' => true,'id'=>'phone']) ?>

    <?php $products = \yii\helpers\ArrayHelper::map(\admin\models\Products::find()->all(),'id','title') ?>
    <?= $form->field($model, 'items')->dropDownList($products, ['prompt'=>'Products','class'=>'productsSelect form-control'])->label('Choose products') ?>
    <div class="selected-products"></div>
<!--    --><?//= $form->field($model, 'items')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'taken')->textInput(['maxlength' => true,'id'=>'taken']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success','id'=>'submitBtn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
