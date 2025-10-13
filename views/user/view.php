<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = Html::encode($model->username);
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;

\yii\web\YiiAsset::register($this);
?>

<div class="card shadow">
    <div class="card-header bg-info text-white">
        <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
    </div>

    <div class="card-body">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => 'Profile Picture',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->userDetails && $model->userDetails->profile_picture_url) {
                            $img = Html::img(
                                Yii::getAlias('@web/' . $model->userDetails->profile_picture_url),
                                ['style' => 'max-width: 200px; border-radius: 10px;']
                            );
                            return "<div style='text-align: center;'>$img</div>";
                        }
                        return '<div style="text-align: center;">(not set)</div>';
                    },
                ],
                'username',
                'email:email',
                'role',
                'date_registered',
                'last_login',
            ],
        ]) ?>

        <div class="mt-4 d-flex flex-wrap gap-2">
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('Update', ['update', 'user_id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'user_id' => $model->user_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
</div>