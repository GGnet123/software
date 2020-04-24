<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model admin\models\StoresModel */

$this->title = 'Create Stores Model';
$this->params['breadcrumbs'][] = ['label' => 'Stores Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stores-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
