<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserDocuments $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="card shadow" style="min-height: auto;">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><?= Html::encode($this->title ?? 'Manage User Documents') ?></h3>
    </div>

    <div class="card-body py-3">
        <?php $form = ActiveForm::begin([
            'id' => 'user-documents-form',
            'options' => ['enctype' => 'multipart/form-data'], // required for file uploads
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'form-label text-start d-block'],
                'inputOptions' => ['class' => 'form-control'],
                'errorOptions' => ['class' => 'invalid-feedback d-block'],
            ],
        ]); ?>

        <?= $form->field($model, 'user_id')->textInput(['type' => 'number', 'min' => 1]) ?>

        <?= $form->field($model, 'document_type')->textInput(['maxlength' => true, 'placeholder' => 'Enter document name']) ?>

        <div class="mb-3">
            <label class="form-label">Upload File</label>
            <div class="input-group">
                <?= Html::activeFileInput($model, 'file_url', ['class' => 'form-control']) ?>
            </div>
            <?php if (!$model->isNewRecord && $model->file_url): ?>
                <div class="form-text mt-1">
                    Current file:
                    <?= Html::a(basename($model->file_url), Yii::getAlias('@web/' . $model->file_url), ['target' => '_blank']) ?>
                </div>
            <?php endif; ?>
        </div>

        <?= $form->field($model, 'status')->dropDownList([
            'Completed' => 'Completed',
            'Incomplete' => 'Incomplete',
            'Pending Review' => 'Pending Review',
        ], ['prompt' => 'Select Status']) ?>

        <?= $form->field($model, 'upload_date')->textInput(['readonly' => true, 'value' => $model->upload_date ?: date('Y-m-d H:i:s')]) ?>

        <div class="form-group mt-4">
            <div class="row g-2">
                <div class="col-6">
                    <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary w-100']) ?>
                </div>
                <div class="col-6">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>