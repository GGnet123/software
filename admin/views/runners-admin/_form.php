<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\Runners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="runners-form">

    <?php $form = ActiveForm::begin(); ?>

<!--    --><?//= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'auth_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'rating')->textInput() ?>

<!--    --><?//= $form->field($model, 'status_id')->textInput() ?>

<!--    --><?//= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'money')->textInput() ?>

<!--    --><?//= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'busy')->textInput() ?>

<!--    --><?//= $form->field($model, 'order_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
