<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model admin\models\Runners */

$this->title = 'Update Runners: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Runners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="runners-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
