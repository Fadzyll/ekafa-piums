<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */

$this->title = 'Classroom: ' . $model->class_name;
$this->params['breadcrumbs'][] = ['label' => 'Classroom Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->class_name;

\yii\web\YiiAsset::register($this);
?>

<div class="card shadow classroom-model-view mb-4">
    <div class="card-header bg-info text-white">
        <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
    </div>

    <div class="card-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'class_id',
                'class_name',
                'year',
                'session_type',
                [
                    'label' => 'Teacher',
                    'value' => $model->user->username ?? '(not set)',
                ],
                [
                    'label' => 'Quota',
                    'value' => $model->quota,
                ],
                [
                    'label' => 'Current Enrollment',
                    'value' => $model->current_enrollment,
                ],
                'status',
            ],
            'options' => ['class' => 'table table-bordered table-striped'],
        ]) ?>

        <div class="mt-4 d-flex flex-wrap gap-2">
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('Update', ['update', 'class_id' => $model->class_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'class_id' => $model->class_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this classroom?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
</div>
    </div>
</div>