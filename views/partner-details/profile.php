<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartnerJob $model */

$this->title = 'Partner Job Information';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-4">
    <div class="partner-job-update">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
            </div>

            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'partner_job')->textInput(['maxlength' => true])->label('Occupation') ?>
                        <?= $form->field($model, 'partner_employer')->textInput(['maxlength' => true])->label('Employer Name') ?>
                        <?= $form->field($model, 'partner_employer_phone_number')->textInput(['maxlength' => true])->label('Employer Phone Number') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'partner_employer_address')->textarea(['rows' => 3])->label('Employer Address') ?>
                        <?= $form->field($model, 'partner_gross_salary')->textInput(['type' => 'number', 'step' => '0.01'])->label('Gross Salary (RM)') ?>
                        <?= $form->field($model, 'partner_net_salary')->textInput(['type' => 'number', 'step' => '0.01'])->label('Net Salary (RM)') ?>
                    </div>
                </div>

                <div class="form-group mt-4 text-end">
                    <?= Html::submitButton('Save Job Information', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>