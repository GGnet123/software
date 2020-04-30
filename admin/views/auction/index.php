<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel admin\models\AuctionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auctions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auctions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Auctions', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'price',
            [
                'attribute' => 'winner_id',
                'value' => function($data){
                    $winner = \admin\models\User::findOne(['id'=>$data->winner_id]);
                    return $winner->username;
                }
            ],
            [
                'attribute' => 'winner_phone',
                'value' => function($data){
                    $winner = \admin\models\User::findOne(['id'=>$data->winner_id]);
                    return $winner->phone_number;
                }
            ],
            [
                'class' => '\pheme\grid\ToggleColumn',
                'attribute' => 'is_active',
                'filter' => [
                    1 => 'Да',
                    2 => 'Нет'
                ]
            ],
            //'participants_ids',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
