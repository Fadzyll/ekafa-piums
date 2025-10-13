<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Register as Parent';
$this->params['breadcrumbs'][] = ['label' => 'Login', 'url' => ['site/select-role']];
$this->params['breadcrumbs'][] = 'Register';
?>

<div class="site-register container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

            <p class="text-center mb-4">Fill in the form below to create your parent account:</p>

            <?php $form = ActiveForm::begin([
                'id' => 'register-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'form-label text-start d-block'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password', [
                'template' => '{label}
                <div class="input-group">
                    {input}
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                {error}',
            ])->passwordInput(['class' => 'form-control', 'id' => 'password']) ?>

            <?= $form->field($model, 'confirm_password', [
                'template' => '{label}
                <div class="input-group">
                    {input}
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                {error}',
            ])->passwordInput(['class' => 'form-control', 'id' => 'confirm-password']) ?>

            <div class="form-group mt-4 text-center">
                <?= Html::submitButton('Register', ['class' => 'btn btn-success w-100']) ?>
            </div>

            <div class="form-group mt-2 text-center">
                <?= Html::a('Already have an account? Back to Login', ['site/login', 'role' => 'parent']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<?php
// JavaScript to toggle visibility
$this->registerJs(<<<JS
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const input = this.closest('.input-group').querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
JS);
?>

<?php
$this->registerCss(<<<CSS
/* Chrome, Edge, Safari – remove built-in eye icon */
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear {
    display: none;
}

input[type="password"]::-webkit-credentials-auto-fill-button {
    visibility: hidden;
    display: none !important;
    pointer-events: none;
    position: absolute;
    right: 0;
}

input[type="password"]::-webkit-inner-spin-button,
input[type="password"]::-webkit-outer-spin-button,
input[type="password"]::-webkit-clear-button {
    display: none;
}
CSS);
?>