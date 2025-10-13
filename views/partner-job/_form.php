<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartnerJob $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="partner-job-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'partner_id')->textInput() ?>

    <?= $form->field($model, 'partner_job')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_employer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_employer_address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'partner_employer_phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_gross_salary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_net_salary')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
