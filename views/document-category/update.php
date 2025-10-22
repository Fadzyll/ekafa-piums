<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DocumentCategory $model */

$this->title = 'Update: ' . $model->category_name;
$this->params['breadcrumbs'][] = ['label' => 'Document Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->category_name, 'url' => ['view', 'id' => $model->category_id]];
$this->params['breadcrumbs'][] = 'Update';
$this->params['hideTitle'] = true;
?>

<div class="document-category-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>