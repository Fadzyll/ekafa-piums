<?php

use app\models\UserDocuments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\UserDocumentsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'User Documents';
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
?>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
    </div>

    <div class="card-body">
        <p>
            <?= Html::a('Create User Document', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?php Pjax::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-bordered align-middle'],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No.',
                    'headerOptions' => ['style' => 'width:60px; text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'user_id',
                    'label' => 'User ID',
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'document_type',
                    'label' => 'Document Name',
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $badgeClass = match ($model->status) {
                            'Completed' => 'bg-success',
                            'Incomplete' => 'bg-danger',
                            'Pending Review' => 'bg-warning text-dark',
                            default => 'bg-secondary',
                        };
                        return Html::tag('span', $model->status, ['class' => 'badge ' . $badgeClass]);
                    },
                    'filter' => [
                        'Completed' => 'Completed',
                        'Incomplete' => 'Incomplete',
                        'Pending Review' => 'Pending Review',
                    ],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'file_url',
                    'label' => 'File',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->file_url
                            ? Html::a('📄 View File', Yii::getAlias('@web/' . $model->file_url), [
                                'target' => '_blank',
                                'class' => 'btn btn-sm btn-outline-primary'
                            ])
                            : Html::tag('span', 'No file', ['class' => 'text-muted']);
                    },
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'upload_date',
                    'label' => 'Uploaded On',
                    'format' => ['datetime', 'php:Y-m-d H:i'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'class' => ActionColumn::className(),
                    'header' => 'Actions',
                    'headerOptions' => ['style' => 'width:160px; text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('View', $url, ['class' => 'btn btn-sm btn-primary']);
                        },
                        'update' => function ($url, $model) {
                            return Html::a('Edit', $url, ['class' => 'btn btn-sm btn-success']);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('Delete', $url, [
                                'class' => 'btn btn-sm btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this document?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, UserDocuments $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'document_id' => $model->document_id]);
                    },
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>
    </div>
</div>