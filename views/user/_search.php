<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="users-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'username') ?> <!-- New: searchable by username -->

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'role')->dropDownList([
        '' => 'All Roles',
        'Admin' => 'Admin',
        'Teacher' => 'Teacher',
        'Parent' => 'Parent',
    ]) ?>

    <?= $form->field($model, 'date_registered') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>