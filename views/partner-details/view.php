<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PartnerDetails $model */

$this->title = $model->partner_id;
$this->params['breadcrumbs'][] = ['label' => 'Partner Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="partner-details-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'partner_id' => $model->partner_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'partner_id' => $model->partner_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'partner_id',
            'partner_name',
            'partner_ic_number',
            'partner_phone_number',
            'partner_citizenship',
            'partner_marital_status',
            'partner_address:ntext',
            'partner_city',
            'partner_postcode',
            'partner_state',
        ],
    ]) ?>

</div>
