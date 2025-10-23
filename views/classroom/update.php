<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */
/** @var array $teachers */

$this->title = 'Update Classroom: ' . $model->class_name;
$this->params['breadcrumbs'][] = ['label' => 'Classroom Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->class_name, 'url' => ['view', 'class_id' => $model->class_id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="classroom-model-update">
    <?= $this->render('_form', [
        'model' => $model,
        'teachers' => $teachers,
    ]) ?>
</div>