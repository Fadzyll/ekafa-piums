<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartnerDetails $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="partner-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'partner_id')->textInput() ?>

    <?= $form->field($model, 'partner_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_ic_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_citizenship')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_marital_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'partner_city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_postcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_state')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
