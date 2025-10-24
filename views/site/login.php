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
                <?= $form->field($model, 'email')->label('Email')->begin() ?>
                    <div class="input-with-icon">
                        <?= Html::activeTextInput($model, 'email', [
                            'class' => 'form-control',
                            'placeholder' => 'Enter your email',
                            'autofocus' => true,
                        ]) ?>
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                    <?= Html::error($model, 'email', ['class' => 'invalid-feedback d-block']) ?>
                <?= $form->field($model, 'email')->end() ?>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <?= $form->field($model, 'password')->label('Password')->begin() ?>
                    <div class="input-with-icon">
                        <?= Html::activePasswordInput($model, 'password', [
                            'class' => 'form-control',
                            'placeholder' => 'Enter your password',
                            'id' => 'login-password',
                        ]) ?>
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="password-toggle">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <?= Html::error($model, 'password', ['class' => 'invalid-feedback d-block']) ?>
                <?= $form->field($model, 'password')->end() ?>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => '<div class="form-check">{input}{label}</div>',
                    'labelOptions' => ['class' => 'form-check-label'],
                    'inputOptions' => ['class' => 'form-check-input'],
                ]) ?>
                <?= Html::a('Forgot Password?', ['site/request-password-reset'], [
                    'class' => 'text-decoration-none',
                    'style' => 'color: var(--ekafa-primary, #667eea); font-size: 0.9rem;'
                ]) ?>
            </div>

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

// CSS for proper icon positioning
$this->registerCss(<<<CSS
/* Remove browser autofill icons */
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear,
input[type="password"]::-webkit-credentials-auto-fill-button,
input[type="email"]::-webkit-credentials-auto-fill-button {
    display: none !important;
}

/* Input with icon wrapper */
.input-with-icon {
    position: relative;
    width: 100%;
}

.input-with-icon .form-control {
    padding-right: 45px;
    width: 100%;
}

.input-with-icon .input-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1.1rem;
    pointer-events: none;
    z-index: 2;
}

.input-with-icon .password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    color: #94a3b8;
    font-size: 1.1rem;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3;
    transition: color 0.2s;
}

.input-with-icon .password-toggle:hover {
    color: #64748b;
}

/* Adjust padding when both icon and toggle button present */
.input-with-icon:has(.password-toggle) .form-control {
    padding-right: 45px;
}

/* Error message styling */
.invalid-feedback {
    display: block !important;
    margin-top: 0.25rem;
    font-size: 0.875rem;
}

/* Fix field spacing */
.field-loginform-email,
.field-loginform-password {
    margin-bottom: 0 !important;
}

.form-group {
    margin-bottom: 1.25rem;
}

/* Ensure label is above input */
.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}
CSS);
?>