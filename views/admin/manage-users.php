<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Manage Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="admin-manage-users">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'full_name',
            'ic_number',
            'age',
            'gender',
            'race',
            'phone_number',
            'state',
            // Add more columns as needed
        ],
    ]) ?>
</div>