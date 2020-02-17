<?php

use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin([
            'id'=>'ordersForm'
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'short_description')->widget(CKEditor::className(),[
        'options' => ['rows' => 5],
        'preset'=>'basic',
    ]); ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'barcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'popular')->checkbox() ?>

    <?php $categories = \yii\helpers\ArrayHelper::map(\admin\models\Categories::find()->all(),'id','title') ?>
    <?= $form->field($model, 'category_id')->dropDownList($categories) ?>

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
