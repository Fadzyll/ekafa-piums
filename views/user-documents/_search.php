<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserDocumentsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-documents-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'document_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'document_type') ?>

    <?= $form->field($model, 'file_url') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'upload_date') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
