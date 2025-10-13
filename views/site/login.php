<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */
/** @var string|null $role */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login' . ($role ? ' as ' . ucfirst($role) : '');
$this->params['breadcrumbs'][] = ['label' => 'Login', 'url' => ['site/select-role']];
if ($role) {
    $this->params['breadcrumbs'][] = ucfirst($role);
}
?>

<div class="site-login container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

            <p class="text-center mb-4">Please fill out the following fields to login:</p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'form-label text-start d-block'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <?= Html::activeHiddenInput($model, 'role') ?>

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
            ])->passwordInput(['class' => 'form-control', 'id' => 'login-password']) ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"form-check text-start\">{input} {label}</div>\n<div class=\"text-start\">{error}</div>",
                'labelOptions' => ['class' => 'form-check-label'],
                'inputOptions' => ['class' => 'form-check-input'],
            ]) ?>

            <div class="form-group mt-4 text-center">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary w-100', 'name' => 'login-button']) ?>
            </div>

            <?php if (strtolower((string)$role) === 'parent'): ?>
                <div class="form-group mt-2 text-center">
                    <?= Html::a('Don\'t have an account? Register Here', ['site/register-parent']) ?>
                </div>
            <?php endif; ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
// Password visibility toggle
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
// Remove browser autofill/show password icons
$this->registerCss(<<<CSS
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