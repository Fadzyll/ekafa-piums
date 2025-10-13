<?php

use app\models\PartnerJob;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\PartnerJobSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Partner Jobs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-job-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Partner Job', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'partner_id',
            'partner_job',
            'partner_employer',
            'partner_employer_address:ntext',
            'partner_employer_phone_number',
            //'partner_gross_salary',
            //'partner_net_salary',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PartnerJob $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'partner_id' => $model->partner_id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
