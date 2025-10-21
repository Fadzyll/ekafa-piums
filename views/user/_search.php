<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<style>
.search-form-modern .form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.search-form-modern .form-group {
    margin-bottom: 0;
}

.search-form-modern label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.search-form-modern label i {
    margin-right: 0.5rem;
    color: #667eea;
}

.search-form-modern .form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.6rem 1rem;
    transition: all 0.3s ease;
}

.search-form-modern .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

.search-buttons {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
    margin-top: 1rem;
}

.btn-search-modern {
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-search-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-search-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-search-secondary {
    background: #e9ecef;
    color: #495057;
}

.btn-search-secondary:hover {
    background: #dee2e6;
    transform: translateY(-2px);
}
</style>

<div class="users-search search-form-modern">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'id' => 'user-search-form'
        ],
    ]); ?>

    <div class="form-row">
        <div class="form-group">
            <?= $form->field($model, 'username')->textInput([
                'placeholder' => 'Search by username...',
                'id' => 'usersearch-username'
            ])->label('<i class="fas fa-user"></i> Username') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'email')->textInput([
                'placeholder' => 'Search by email...',
                'id' => 'usersearch-email'
            ])->label('<i class="fas fa-envelope"></i> Email') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'role')->dropDownList([
                '' => 'All Roles',
                'Admin' => 'Admin',
                'Teacher' => 'Teacher',
                'Parent' => 'Parent',
            ], [
                'id' => 'usersearch-role'
            ])->label('<i class="fas fa-user-tag"></i> Role') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'date_registered')->textInput([
                'type' => 'date',
                'id' => 'usersearch-date_registered'
            ])->label('<i class="far fa-calendar"></i> Registration Date') ?>
        </div>
    </div>

    <div class="search-buttons">
        <?= Html::submitButton('<i class="fas fa-search"></i> Search', [
            'class' => 'btn-search-modern btn-search-primary',
            'id' => 'search-submit-btn'
        ]) ?>
        <?= Html::a('<i class="fas fa-redo"></i> Reset', ['index'], [
            'class' => 'btn-search-modern btn-search-secondary'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>