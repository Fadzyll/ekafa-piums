<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */

$this->title = 'Create Classroom Model';
$this->params['breadcrumbs'][] = ['label' => 'Classroom Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classroom-model-create">

    <?= $this->render('_form', [
        'model' => $model,
        'teachers' => $teachers, // Ensure this variable is passed to the view
    ]) ?>

</div>