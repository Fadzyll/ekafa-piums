<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DocumentCategory $model */

$this->title = 'Create Document Category';
$this->params['breadcrumbs'][] = ['label' => 'Document Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
?>

<div class="document-category-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>