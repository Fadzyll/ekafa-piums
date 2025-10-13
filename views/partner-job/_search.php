<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartnerJobSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="partner-job-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'partner_id') ?>

    <?= $form->field($model, 'partner_job') ?>

    <?= $form->field($model, 'partner_employer') ?>

    <?= $form->field($model, 'partner_employer_address') ?>

    <?= $form->field($model, 'partner_employer_phone_number') ?>

    <?php // echo $form->field($model, 'partner_gross_salary') ?>

    <?php // echo $form->field($model, 'partner_net_salary') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
