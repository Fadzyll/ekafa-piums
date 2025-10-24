<?php
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Reset Password';
?>

<div class="auth-wrapper">
    <div class="auth-container fade-in-up">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h1 class="auth-title">Create New Password</h1>
                <p class="auth-subtitle">Please enter your new password below</p>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'reset-password-form',
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'fieldConfig' => [
                    'template' => '{label}{input}{error}',
                    'labelOptions' => ['class' => 'form-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <!-- Password Field -->
            <div class="form-group">
                <?= $form->field($model, 'password')->label('New Password')->begin() ?>
                    <div class="input-with-icon">
                        <?= Html::activePasswordInput($model, 'password', [
                            'class' => 'form-control',
                            'placeholder' => 'Enter new password',
                            'id' => 'reset-password',
                        ]) ?>
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="password-toggle" data-target="reset-password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <small class="form-text">8-16 characters, must include a number and special character</small>
                    <?= Html::error($model, 'password', ['class' => 'invalid-feedback d-block']) ?>
                <?= $form->field($model, 'password')->end() ?>
            </div>

            <!-- Confirm Password Field -->
            <div class="form-group">
                <?= $form->field($model, 'confirm_password')->label('Confirm New Password')->begin() ?>
                    <div class="input-with-icon">
                        <?= Html::activePasswordInput($model, 'confirm_password', [
                            'class' => 'form-control',
                            'placeholder' => 'Re-enter new password',
                            'id' => 'confirm-password',
                        ]) ?>
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="password-toggle" data-target="confirm-password">
                            <i class="bi bi-eye"></i>
                        </button>
                        <i class="bi bi-check-circle-fill validation-icon" id="match-icon" style="display: none;"></i>
                    </div>
                    <?= Html::error($model, 'confirm_password', ['class' => 'invalid-feedback d-block']) ?>
                <?= $form->field($model, 'confirm_password')->end() ?>
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

            <!-- Submit Button -->
            <button type="submit" class="btn-modern btn-primary-modern w-100 mt-4" id="submit-btn">
                <i class="bi bi-check-circle"></i>
                Reset Password
            </button>

            <?php ActiveForm::end(); ?>
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
const passwordInput = document.getElementById('reset-password');
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

// Real-time password match validation - UPDATED to show custom icon
const confirmPasswordInput = document.getElementById('confirm-password');
const matchIcon = document.getElementById('match-icon');

confirmPasswordInput.addEventListener('input', function() {
    const password = passwordInput.value;
    const confirmPassword = this.value;
    
    if (confirmPassword.length > 0) {
        if (password !== confirmPassword) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            matchIcon.style.display = 'none';
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            matchIcon.style.display = 'block';
        }
    } else {
        this.classList.remove('is-invalid', 'is-valid');
        matchIcon.style.display = 'none';
    }
});

// Loading state on submit
document.getElementById('reset-password-form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    btn.classList.add('btn-loading');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Resetting...';
});
JS);

$this->registerCss(<<<CSS
/* Remove browser autofill icons */
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear,
input[type="password"]::-webkit-credentials-auto-fill-button {
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

/* When input has validation icon, add more padding */
.input-with-icon:has(.validation-icon) .form-control {
    padding-right: 75px;
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

/* Validation checkmark icon */
.input-with-icon .validation-icon {
    position: absolute;
    right: 45px;
    top: 50%;
    transform: translateY(-50%);
    color: #10b981;
    font-size: 1.1rem;
    z-index: 2;
    pointer-events: none;
}

/* Remove default Bootstrap validation icons */
.form-control.is-valid,
.form-control.is-invalid {
    background-image: none !important;
    padding-right: 45px !important;
}

.input-with-icon:has(.validation-icon) .form-control.is-valid {
    padding-right: 75px !important;
}

/* Error message styling */
.invalid-feedback {
    display: block !important;
    margin-top: 0.25rem;
    font-size: 0.875rem;
}

/* Fix field spacing */
.field-resetpasswordform-password,
.field-resetpasswordform-confirm_password {
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

/* Form text (hints) */
.form-text {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.8125rem;
    color: var(--ekafa-gray-500, #64748b);
}

/* Validation states - remove default borders */
.is-valid {
    border-color: #10b981 !important;
}

.is-invalid {
    border-color: #ef4444 !important;
}
CSS);
?>