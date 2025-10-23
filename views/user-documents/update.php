<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UserDocuments $model */

$this->title = Yii::t('app', 'Update User Documents: {name}', [
    'name' => $model->document_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->document_id, 'url' => ['view', 'document_id' => $model->document_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-documents-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
