<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModelSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="classroom-model-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'class_id') ?>

    <?= $form->field($model, 'class_name') ?>

    <?= $form->field($model, 'year') ?>

    <?= $form->field($model, 'session_type') ?>

    <?= $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'quota') ?>

    <?php // echo $form->field($model, 'current_enrollment') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
