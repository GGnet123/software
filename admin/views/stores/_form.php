<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\StoresModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stores-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true])->hint('пример: Алматы, улица Кунаева, 77') ?>

    <?= '<h2>Upload images</h2>' . \dosamigos\fileupload\FileUploadUI::widget([
        'id'=>'images_input',
        'model'=>$model,
        'attribute' => 'image',
        'fieldOptions' => [
            'accept' => 'image/*'
        ],
        'url' => 'upload',
        'gallery' => false,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
