<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$role = $role ?? null;
$roleLabel = $role ? ucfirst($role) : '';
?>

<div class="auth-wrapper">
    <div class="auth-container fade-in-up">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h1 class="auth-title">Welcome Back!</h1>
                <p class="auth-subtitle">
                    <?= $role ? "Login as <strong>$roleLabel</strong>" : "Login to E-KAFA PIUMS" ?>
                </p>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'fieldConfig' => [
                    'template' => '{label}{input}{error}',
                    'labelOptions' => ['class' => 'form-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <?= Html::activeHiddenInput($model, 'role') ?>

            <!-- Email Field -->
            <div class="form-group">
                <?= $form->field($model, 'email', [
                    'template' => '{label}<div class="input-with-icon">{input}<i class="bi bi-envelope input-icon"></i>{error}</div>',
                ])->textInput([
                    'placeholder' => 'Enter your email',
                    'autofocus' => true,
                ]) ?>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <?= $form->field($model, 'password', [
                    'template' => '{label}<div class="input-with-icon">{input}<i class="bi bi-lock input-icon"></i><button type="button" class="password-toggle"><i class="bi bi-eye"></i></button>{error}</div>',
                ])->passwordInput([
                    'placeholder' => 'Enter your password',
                    'id' => 'login-password',
                ]) ?>
            </div>

            <!-- Remember Me -->
            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => '<div class="form-check">{input}{label}</div>',
                'labelOptions' => ['class' => 'form-check-label'],
                'inputOptions' => ['class' => 'form-check-input'],
            ]) ?>

            <!-- Submit Button -->
            <button type="submit" class="btn-modern btn-primary-modern w-100 mt-4" id="login-btn">
                <i class="bi bi-box-arrow-in-right"></i>
                Login
            </button>

            <?php ActiveForm::end(); ?>

            <!-- Register Link (for Parents only) -->
            <?php if (strtolower((string)$role) === 'parent'): ?>
                <div class="text-center mt-4">
                    <p class="text-muted mb-0">Don't have an account?</p>
                    <?= Html::a('Create Parent Account', ['site/register-parent'], [
                        'class' => 'btn-modern btn-secondary-modern w-100 mt-2'
                    ]) ?>
                </div>
            <?php endif; ?>

            <!-- Back to Role Selection -->
            <div class="text-center mt-3">
                <?= Html::a('← Back to Role Selection', ['site/select-role'], [
                    'class' => 'text-decoration-none',
                    'style' => 'color: var(--ekafa-gray-600);'
                ]) ?>
            </div>
        </div>
    </div>
</div>

<?php
// Password Toggle Script
$this->registerJs(<<<JS
// Password visibility toggle
document.querySelector('.password-toggle').addEventListener('click', function() {
    const input = document.getElementById('login-password');
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

// Loading state on submit
document.getElementById('login-form').addEventListener('submit', function() {
    const btn = document.getElementById('login-btn');
    btn.classList.add('btn-loading');
    btn.disabled = true;
});
JS);

// Remove browser autofill icons
$this->registerCss(<<<CSS
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear,
input[type="password"]::-webkit-credentials-auto-fill-button {
    display: none !important;
}
CSS);
?>