<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\DocumentCategory;

/** @var yii\web\View $this */
/** @var app\models\DocumentCategory $model */
?>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
    </div>

    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'form-label fw-bold'],
                'inputOptions' => ['class' => 'form-control'],
                'errorOptions' => ['class' => 'invalid-feedback d-block'],
            ],
        ]); ?>

        <?= $form->field($model, 'category_name')->textInput([
            'maxlength' => true,
            'placeholder' => 'e.g., Birth Certificate, Teaching License'
        ]) ?>

        <?= $form->field($model, 'description')->textarea([
            'rows' => 3,
            'placeholder' => 'Describe what this document is for...'
        ]) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'required_for_role')->dropDownList([
                    DocumentCategory::ROLE_TEACHER => 'Teacher Only',
                    DocumentCategory::ROLE_PARENT => 'Parent Only',
                    DocumentCategory::ROLE_BOTH => 'Both Teacher & Parent',
                ], ['prompt' => 'Select Role']) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'status')->dropDownList([
                    DocumentCategory::STATUS_ACTIVE => 'Active',
                    DocumentCategory::STATUS_INACTIVE => 'Inactive',
                ]) ?>
            </div>
        </div>

        <div class="form-check mb-3">
            <?= Html::activeCheckbox($model, 'is_required', [
                'class' => 'form-check-input',
                'label' => 'This document is mandatory',
                'template' => '{input} {label}',
            ]) ?>
        </div>

        <div class="form-group mt-4">
            <div class="row g-2">
                <div class="col-6">
                    <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary w-100']) ?>
                </div>
                <div class="col-6">
                    <?= Html::submitButton('<i class="bi bi-check-circle"></i> Save', ['class' => 'btn btn-success w-100']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>