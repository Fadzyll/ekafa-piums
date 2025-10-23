<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ClassroomModel;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModelSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="classroom-model-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'class' => 'needs-validation',
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-3">
            <?= $form->field($model, 'class_name')->textInput([
                'placeholder' => 'Search by class name...',
                'class' => 'form-control form-control-sm'
            ]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'grade_level')->dropDownList(
                ClassroomModel::optsGradeLevel(),
                [
                    'prompt' => 'All Grade Levels',
                    'class' => 'form-select form-select-sm'
                ]
            ) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'session_type')->dropDownList(
                ClassroomModel::optsSessionType(),
                [
                    'prompt' => 'All Sessions',
                    'class' => 'form-select form-select-sm'
                ]
            ) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'status')->dropDownList(
                ClassroomModel::optsStatus(),
                [
                    'prompt' => 'All Statuses',
                    'class' => 'form-select form-select-sm'
                ]
            ) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'teacherName')->textInput([
                'placeholder' => 'Teacher name...',
                'class' => 'form-control form-control-sm'
            ]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'classroom_location')->textInput([
                'placeholder' => 'Location...',
                'class' => 'form-control form-control-sm'
            ]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'class_start_date')->input('date', [
                'class' => 'form-control form-control-sm'
            ])->label('Start Date From') ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'class_end_date')->input('date', [
                'class' => 'form-control form-control-sm'
            ])->label('End Date To') ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'year')->textInput([
                'type' => 'number',
                'placeholder' => 'Year',
                'class' => 'form-control form-control-sm'
            ]) ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="fas fa-search me-1"></i> Search', [
            'class' => 'btn btn-primary btn-sm'
        ]) ?>
        <?= Html::a('<i class="fas fa-redo me-1"></i> Reset', ['index'], [
            'class' => 'btn btn-outline-secondary btn-sm'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>