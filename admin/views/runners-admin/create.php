<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model admin\models\Runners */

$this->title = 'Create Runners';
$this->params['breadcrumbs'][] = ['label' => 'Runners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="runners-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
