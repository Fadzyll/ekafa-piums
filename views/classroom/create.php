<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */
/** @var array $teachers */

$this->title = 'Create New Classroom';
$this->params['breadcrumbs'][] = ['label' => 'Classroom Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="classroom-model-create">
    <?= $this->render('_form', [
        'model' => $model,
        'teachers' => $teachers,
    ]) ?>
</div>