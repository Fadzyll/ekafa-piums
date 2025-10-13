<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartnerDetailsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="partner-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'partner_id') ?>

    <?= $form->field($model, 'partner_name') ?>

    <?= $form->field($model, 'partner_ic_number') ?>

    <?= $form->field($model, 'partner_phone_number') ?>

    <?= $form->field($model, 'partner_citizenship') ?>

    <?php // echo $form->field($model, 'partner_marital_status') ?>

    <?php // echo $form->field($model, 'partner_address') ?>

    <?php // echo $form->field($model, 'partner_city') ?>

    <?php // echo $form->field($model, 'partner_postcode') ?>

    <?php // echo $form->field($model, 'partner_state') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
