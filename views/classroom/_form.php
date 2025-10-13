<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var array $teachers */
/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><?= Html::encode($this->title ?? 'Classroom Form') ?></h3>
    </div>

    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'class_name')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'year')->textInput() ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'session_type')->dropDownList([
                    'Morning' => 'Morning',
                    'Evening' => 'Evening',
                ], ['prompt' => 'Select Session']) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'user_id')->dropDownList($teachers, ['prompt' => 'Select Teacher']) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'quota')->textInput() ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'current_enrollment')->textInput() ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'status')->dropDownList([
                    'Open' => 'Open',
                    'Closed' => 'Closed',
                    'Full' => 'Full',
                ], ['prompt' => 'Select Status']) ?>
            </div>
        </div>

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