<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartnerJob $model */

$this->title = 'Partner Job Information';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
        </div>

        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <!-- Hidden partner_id -->
            <?= $form->field($model, 'partner_id')->hiddenInput()->label(false) ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'partner_job')
                        ->textInput(['maxlength' => true, 'placeholder' => 'e.g. Teacher, Engineer, Clerk'])
                        ->label('Occupation') ?>

                    <?= $form->field($model, 'partner_employer')
                        ->textInput(['maxlength' => true, 'placeholder' => 'e.g. Universiti Malaysia Sabah'])
                        ->label('Employer Name') ?>

                    <?= $form->field($model, 'partner_employer_address')
                        ->textarea(['rows' => 3, 'placeholder' => 'Employer full address'])
                        ->label('Employer Address') ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'partner_employer_phone_number')
                        ->textInput(['maxlength' => true, 'placeholder' => 'e.g. 012-3456789'])
                        ->label('Employer Phone Number') ?>

                    <?= $form->field($model, 'partner_gross_salary')
                        ->textInput(['type' => 'number', 'step' => '0.01', 'min' => 0, 'placeholder' => '0.00'])
                        ->label('Gross Salary (RM)') ?>

                    <?= $form->field($model, 'partner_net_salary')
                        ->textInput(['type' => 'number', 'step' => '0.01', 'min' => 0, 'placeholder' => '0.00'])
                        ->label('Net Salary (RM)') ?>
                </div>
            </div>

            <div class="form-group mt-4 text-end">
                <?= Html::a('Back to Profile', ['user-details/view'], ['class' => 'btn btn-primary']) ?>
                <?= Html::submitButton('Save Partner Job Info', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>