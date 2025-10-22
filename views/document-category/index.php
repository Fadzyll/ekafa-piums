<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\DocumentCategory;

$this->title = 'Document Categories';
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
?>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><i class="bi bi-folder-fill"></i> <?= Html::encode($this->title) ?></h3>
    </div>

    <div class="card-body">
        <p>
            <?= Html::a('<i class="bi bi-plus-circle"></i> Create New Category', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-striped table-hover'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'category_name',
                [
                    'attribute' => 'description',
                    'format' => 'ntext',
                    'contentOptions' => ['style' => 'max-width: 300px;'],
                ],
                [
                    'attribute' => 'required_for_role',
                    'format' => 'raw',
                    'value' => function($model) {
                        $badges = [
                            'Teacher' => '<span class="badge bg-info">Teacher</span>',
                            'Parent' => '<span class="badge bg-warning">Parent</span>',
                            'Both' => '<span class="badge bg-success">Both</span>',
                        ];
                        return $badges[$model->required_for_role] ?? $model->required_for_role;
                    },
                ],
                [
                    'attribute' => 'is_required',
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->is_required 
                            ? '<span class="badge bg-danger">Mandatory</span>' 
                            : '<span class="badge bg-secondary">Optional</span>';
                    },
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->status === 'Active' 
                            ? '<span class="badge bg-success">Active</span>' 
                            : '<span class="badge bg-secondary">Inactive</span>';
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        return [$action, 'id' => $model->category_id];
                    }
                ],
            ],
        ]); ?>
    </div>
</div>