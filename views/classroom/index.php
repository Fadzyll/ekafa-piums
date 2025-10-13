<?php

use app\models\ClassroomModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModelSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Classroom Management';
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
?>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
    </div>

    <div class="card-body">
        <p>
            <?= Html::a('Create Classroom', ['create'], ['class' => 'btn btn-success']) ?>
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
                'class_name',
                'year',
                'session_type',
                [
                    'attribute' => 'user_id',
                    'label' => 'Teacher Name',
                    'value' => function ($model) {
                        return $model->user ? $model->user->username : 'N/A';
                    }
                ],
                [
                    'attribute' => 'current_enrollment',
                    'label' => 'Current Enrollment',
                ],
                [
                    'class' => ActionColumn::className(),
                    'header' => 'Actions',
                    'urlCreator' => function ($action, ClassroomModel $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'class_id' => $model->class_id]);
                    }
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>
    </div>
</div>