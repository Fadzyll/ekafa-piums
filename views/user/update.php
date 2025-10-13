<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = 'Update User: ' . Html::encode($model->username);
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($model->username), 'url' => ['view', 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';

// Optional: disable the default layout title
$this->params['hideTitle'] = true;
?>

<div class="users-update">
    <?= $this->render('_form', [
        'model' => $model,
        'isRestricted' => $isRestricted,
    ]) ?>
</div>