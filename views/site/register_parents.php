<?php
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Create Parent Account';
?>

<div class="auth-wrapper">
    <div class="auth-container fade-in-up">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="bi bi-person-plus"></i>
                </div>
                <h1 class="auth-title">Join E-KAFA PIUMS</h1>
                <p class="auth-subtitle">Create your parent account to get started</p>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'register-form',
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'fieldConfig' => [
                    'template' => '{label}{input}{error}',
                    'labelOptions' => ['class' => 'form-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <!-- Email Field -->
            <div class="form-group">
                <?= $form->field($model, 'email', [
                    'template' => '{label}<div class="input-with-icon">{input}<i class="bi bi-envelope input-icon"></i>{error}</div>',
                ])->textInput([
                    'placeholder' => 'your.email@example.com',
                    'autofocus' => true,
                ])->hint('We\'ll use this email for login and notifications') ?>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <?= $form->field($model, 'password', [
                    'template' => '{label}<div class="input-with-icon">{input}<i class="bi bi-lock input-icon"></i><button type="button" class="password-toggle" data-target="register-password"><i class="bi bi-eye"></i></button>{error}</div>',
                ])->passwordInput([
                    'placeholder' => 'Create a strong password',
                    'id' => 'register-password',
                ])->hint('8-16 characters, must include a number and special character') ?>
            </div>

            <!-- Confirm Password Field -->
            <div class="form-group">
                <?= $form->field($model, 'confirm_password', [
                    'template' => '{label}<div class="input-with-icon">{input}<i class="bi bi-lock-fill input-icon"></i><button type="button" class="password-toggle" data-target="confirm-password"><i class="bi bi-eye"></i></button>{error}</div>',
                ])->passwordInput([
                    'placeholder' => 'Re-enter your password',
                    'id' => 'confirm-password',
                ]) ?>
            </div>

            <!-- Password Strength Indicator -->
            <div class="password-strength mb-3" id="password-strength" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-muted">Password Strength:</small>
                    <small class="strength-text fw-bold"></small>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms" style="font-size: 0.875rem;">
                    I agree to the <a href="#" class="text-decoration-none">Terms & Conditions</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-modern btn-primary-modern w-100" id="register-btn">
                <i class="bi bi-person-check"></i>
                Create Account
            </button>

            <?php ActiveForm::end(); ?>

            <!-- Login Link -->
            <div class="text-center mt-4">
                <p class="text-muted mb-0">Already have an account?</p>
                <?= Html::a('Login as Parent', ['site/login', 'role' => 'parent'], [
                    'class' => 'btn-modern btn-secondary-modern w-100 mt-2'
                ]) ?>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-3">
                <?= Html::a('← Back to Home', ['site/index'], [
                    'class' => 'text-decoration-none',
                    'style' => 'color: var(--ekafa-gray-600);'
                ]) ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
// Password visibility toggles
document.querySelectorAll('.password-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.dataset.target;
        const input = document.getElementById(targetId);
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

// Password strength checker
const passwordInput = document.getElementById('register-password');
const strengthDiv = document.getElementById('password-strength');
const strengthBar = strengthDiv.querySelector('.progress-bar');
const strengthText = strengthDiv.querySelector('.strength-text');

passwordInput.addEventListener('input', function() {
    const password = this.value;
    
    if (password.length === 0) {
        strengthDiv.style.display = 'none';
        return;
    }
    
    strengthDiv.style.display = 'block';
    
    let strength = 0;
    let text = '';
    let color = '';
    
    // Length check
    if (password.length >= 8) strength += 25;
    if (password.length >= 12) strength += 15;
    
    // Complexity checks
    if (/[a-z]/.test(password)) strength += 15;
    if (/[A-Z]/.test(password)) strength += 15;
    if (/[0-9]/.test(password)) strength += 15;
    if (/[\W_]/.test(password)) strength += 15;
    
    // Determine strength level
    if (strength < 40) {
        text = 'Weak';
        color = '#ef4444';
    } else if (strength < 70) {
        text = 'Fair';
        color = '#f59e0b';
    } else if (strength < 90) {
        text = 'Good';
        color = '#3b82f6';
    } else {
        text = 'Strong';
        color = '#10b981';
    }
    
    strengthBar.style.width = strength + '%';
    strengthBar.style.backgroundColor = color;
    strengthText.textContent = text;
    strengthText.style.color = color;
});

// Loading state on submit
document.getElementById('register-form').addEventListener('submit', function(e) {
    const termsCheckbox = document.getElementById('terms');
    
    if (!termsCheckbox.checked) {
        e.preventDefault();
        alert('Please accept the Terms & Conditions to continue.');
        return;
    }
    
    const btn = document.getElementById('register-btn');
    btn.classList.add('btn-loading');
    btn.disabled = true;
});

// Real-time password match validation
const confirmPasswordInput = document.getElementById('confirm-password');
confirmPasswordInput.addEventListener('input', function() {
    const password = passwordInput.value;
    const confirmPassword = this.value;
    
    if (confirmPassword.length > 0) {
        if (password !== confirmPassword) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    }
});
JS);

$this->registerCss(<<<CSS
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear,
input[type="password"]::-webkit-credentials-auto-fill-button {
    display: none !important;
}

.is-valid {
    border-color: #10b981 !important;
}

.is-invalid {
    border-color: #ef4444 !important;
}

.form-text {
    font-size: 0.8125rem;
    color: var(--ekafa-gray-500);
    margin-top: 0.25rem;
}
CSS);
?>