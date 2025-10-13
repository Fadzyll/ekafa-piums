<?php

use app\models\PartnerDetails;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\PartnerDetailsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Partner Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-details-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Partner Details', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'partner_id',
            'partner_name',
            'partner_ic_number',
            'partner_phone_number',
            'partner_citizenship',
            //'partner_marital_status',
            //'partner_address:ntext',
            //'partner_city',
            //'partner_postcode',
            //'partner_state',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PartnerDetails $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'partner_id' => $model->partner_id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
