<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'popular')->checkbox() ?>

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
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
