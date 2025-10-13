<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */

$this->title = 'Update Classroom Model: ' . $model->class_id;
$this->params['breadcrumbs'][] = ['label' => 'Classroom Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->class_id, 'url' => ['view', 'class_id' => $model->class_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="classroom-model-update">

    <?= $this->render('_form', [
        'model' => $model,
        'teachers' => $teachers,
    ]) ?>

</div>