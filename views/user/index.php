<?php

use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
?>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
    </div>

    <div class="card-body">
        <p>
            <?= Html::a('Create Users', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?php Pjax::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No.',
                ],
                'username',
                'email:email',
                'role',
                'date_registered',
                [
                    'class' => ActionColumn::className(),
                    'header' => 'Actions',
                    'urlCreator' => function ($action, Users $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'user_id' => $model->user_id]);
                    }
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>
    </div>
</div>