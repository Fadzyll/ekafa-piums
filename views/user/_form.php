<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var bool $isRestricted */

?>

<style>
.form-modern-wrapper {
    max-width: 700px;
    margin: 0 auto;
}

.form-modern-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
    border: none;
}

.form-modern-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2.5rem;
    text-align: center;
    position: relative;
}

.form-modern-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
    opacity: 0.3;
}

.form-modern-header h3 {
    color: white;
    margin: 0;
    font-weight: 600;
    font-size: 2rem;
    position: relative;
    z-index: 1;
}

.form-modern-header .icon-circle {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    position: relative;
    z-index: 1;
}

.form-modern-header .icon-circle i {
    font-size: 2.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.form-modern-body {
    padding: 2.5rem;
}

.form-group-modern {
    margin-bottom: 1.8rem;
    position: relative;
}

.form-group-modern label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.form-group-modern label i {
    margin-right: 0.5rem;
    color: #667eea;
}

.form-control-modern {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 0.8rem 1rem;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.form-control-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    outline: none;
}

.form-control-modern:disabled,
.form-control-modern[readonly] {
    background: #f5f5f5;
    cursor: not-allowed;
}

.input-group-modern {
    position: relative;
}

.input-group-modern .toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #667eea;
    cursor: pointer;
    z-index: 10;
    padding: 0.5rem;
    transition: all 0.3s ease;
}

.input-group-modern .toggle-password:hover {
    color: #764ba2;
    transform: translateY(-50%) scale(1.1);
}

.validation-feedback {
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.validation-feedback.success {
    color: #28a745;
}

.validation-feedback.error {
    color: #dc3545;
}

.validation-feedback.checking {
    color: #6c757d;
}

.restriction-notice {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
    border-left: 4px solid #ffc107;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.restriction-notice i {
    font-size: 1.5rem;
    margin-right: 1rem;
    color: #856404;
}

.restriction-notice .text-muted {
    margin: 0;
    color: #856404 !important;
}

.password-disabled-field {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 0.8rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.password-disabled-field input {
    border: none;
    background: transparent;
    flex: 1;
}

.password-disabled-field button {
    background: #e9ecef;
    border: none;
    padding: 0.5rem 0.8rem;
    border-radius: 8px;
    color: #6c757d;
}

.btn-modern-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-modern {
    padding: 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-modern i {
    margin-right: 0.5rem;
}

.btn-back {
    background: #e9ecef;
    color: #495057;
}

.btn-back:hover {
    background: #dee2e6;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}

.password-strength {
    height: 4px;
    background: #e0e0e0;
    border-radius: 2px;
    margin-top: 0.5rem;
    overflow: hidden;
}

.password-strength-bar {
    height: 100%;
    width: 0%;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.password-requirements {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 0.5rem;
    padding-left: 1.5rem;
}

.password-requirements li {
    margin-bottom: 0.3rem;
}

.password-requirements .met {
    color: #28a745;
}

.password-requirements .met i {
    color: #28a745;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-modern-card {
    animation: slideInUp 0.5s ease;
}
</style>

<div class="form-modern-wrapper">
    <div class="form-modern-card">
        <div class="form-modern-header">
            <div class="icon-circle">
                <i class="fas fa-user-circle"></i>
            </div>
            <h3><?= Html::encode($this->title) ?></h3>
        </div>

        <div class="form-modern-body">
            <?php if ($isRestricted): ?>
                <div class="restriction-notice">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Restricted Mode:</strong>
                        <p class="text-muted">Some fields are restricted for <strong>Teacher</strong> and <strong>Parent</strong> roles.</p>
                    </div>
                </div>
            <?php endif; ?>

            <?php $form = ActiveForm::begin([
                'id' => 'user-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => ''],
                    'inputOptions' => ['class' => 'form-control form-control-modern'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <!-- Username Field with Real-time Validation -->
            <div class="form-group-modern">
                <?= $form->field($model, 'username')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Enter username',
                    'id' => 'username-field'
                ])->label('<i class="fas fa-user"></i> Username') ?>
                <div class="validation-feedback" id="username-check" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span></span>
                </div>
            </div>

            <!-- Email Field -->
            <div class="form-group-modern">
                <?php if ($isRestricted): ?>
                    <?= $form->field($model, 'email')->textInput([
                        'readonly' => true,
                        'class' => 'form-control form-control-modern'
                    ])->label('<i class="fas fa-envelope"></i> Email') ?>
                <?php else: ?>
                    <?= $form->field($model, 'email')->textInput([
                        'maxlength' => true,
                        'type' => 'email',
                        'placeholder' => 'Enter email address',
                        'id' => 'email-field'
                    ])->label('<i class="fas fa-envelope"></i> Email') ?>
                    <div class="validation-feedback" id="email-check" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Password Field -->
            <div class="form-group-modern">
                <?php if (!$isRestricted): ?>
                    <label><i class="fas fa-lock"></i> Password</label>
                    <div class="input-group-modern">
                        <?= Html::activePasswordInput($model, 'password', [
                            'class' => 'form-control form-control-modern',
                            'id' => 'password',
                            'placeholder' => $model->isNewRecord ? 'Enter password' : 'Leave blank to keep current password',
                            'style' => 'padding-right: 45px;'
                        ]) ?>
                        <button class="toggle-password" type="button" data-target="password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <?php if ($model->hasErrors('password')): ?>
                        <div class="invalid-feedback d-block"><?= $model->getFirstError('password') ?></div>
                    <?php endif; ?>
                    <div class="password-strength" id="password-strength">
                        <div class="password-strength-bar"></div>
                    </div>
                    <ul class="password-requirements" id="password-requirements">
                        <li><i class="far fa-circle"></i> <span>8-16 characters</span></li>
                        <li><i class="far fa-circle"></i> <span>At least one number</span></li>
                        <li><i class="far fa-circle"></i> <span>At least one special character</span></li>
                    </ul>
                <?php else: ?>
                    <label><i class="fas fa-lock"></i> Password</label>
                    <div class="password-disabled-field">
                        <input type="password" value="********" disabled>
                        <button type="button" disabled>
                            <i class="bi bi-lock-fill"></i>
                        </button>
                    </div>
                    <div class="form-text text-muted mt-2">
                        <i class="fas fa-exclamation-triangle"></i> Password changes are disabled for <strong>Teacher</strong> and <strong>Parent</strong> roles.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Password Confirmation (Only for new records) -->
            <?php if (!$isRestricted && $model->isNewRecord): ?>
                <div class="form-group-modern">
                    <label><i class="fas fa-lock"></i> Confirm Password</label>
                    <div class="input-group-modern">
                        <?= Html::passwordInput('password_confirm', '', [
                            'class' => 'form-control form-control-modern',
                            'id' => 'password-confirm',
                            'placeholder' => 'Re-enter password',
                            'style' => 'padding-right: 45px;'
                        ]) ?>
                        <button class="toggle-password" type="button" data-target="password-confirm">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <small class="form-text text-muted">Must match the password above</small>
                    <div class="validation-feedback" id="password-match" style="display: none;"></div>
                </div>
            <?php endif; ?>

            <!-- Role Field -->
            <div class="form-group-modern">
                <?php if ($isRestricted): ?>
                    <?= $form->field($model, 'role')->dropDownList([
                        $model->role => $model->role,
                    ], [
                        'disabled' => true,
                        'class' => 'form-control form-control-modern'
                    ])->label('<i class="fas fa-user-tag"></i> Role') ?>
                <?php else: ?>
                    <?= $form->field($model, 'role')->dropDownList([
                        'Admin' => '🛡️ Admin',
                        'Teacher' => '👨‍🏫 Teacher',
                        'Parent' => '👨‍👩‍👧 Parent',
                    ], [
                        'prompt' => 'Select Role',
                        'class' => 'form-control form-control-modern'
                    ])->label('<i class="fas fa-user-tag"></i> Role') ?>
                <?php endif; ?>
            </div>

            <div class="btn-modern-group">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-back btn-modern']) ?>
                <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-save btn-modern', 'id' => 'submit-btn']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$checkUsernameUrl = Url::to(['user/check-username']);
$checkEmailUrl = Url::to(['user/check-email']);
$userId = $model->user_id ?? '';

$this->registerJs(<<<JS
    // Password toggle functionality
    $('.toggle-password').on('click', function () {
        const targetId = $(this).data('target');
        const input = $('#' + targetId);
        const icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });
    
    // Real-time username availability check
    let usernameTimeout;
    $('#username-field').on('input', function() {
        clearTimeout(usernameTimeout);
        const username = $(this).val();
        const checkDiv = $('#username-check');
        
        if (username.length < 3) {
            checkDiv.hide();
            return;
        }
        
        // Show checking state
        checkDiv.removeClass('success error').addClass('checking').show();
        checkDiv.find('i').removeClass('fa-check-circle fa-times-circle').addClass('fa-spinner fa-spin');
        checkDiv.find('span').text('Checking availability...');
        
        usernameTimeout = setTimeout(function() {
            $.ajax({
                url: '$checkUsernameUrl',
                data: { username: username, id: '$userId' },
                success: function(data) {
                    checkDiv.removeClass('checking');
                    checkDiv.find('i').removeClass('fa-spinner fa-spin');
                    
                    if (data.available) {
                        checkDiv.addClass('success');
                        checkDiv.find('i').addClass('fa-check-circle');
                        checkDiv.find('span').text('Username available');
                    } else {
                        checkDiv.addClass('error');
                        checkDiv.find('i').addClass('fa-times-circle');
                        checkDiv.find('span').text('Username already taken');
                    }
                },
                error: function() {
                    checkDiv.hide();
                }
            });
        }, 500);
    });
    
    // Real-time email availability check
    let emailTimeout;
    $('#email-field').on('input', function() {
        clearTimeout(emailTimeout);
        const email = $(this).val();
        const checkDiv = $('#email-check');
        
        // Basic email format check
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            checkDiv.hide();
            return;
        }
        
        // Show checking state
        checkDiv.removeClass('success error').addClass('checking').show();
        checkDiv.find('i').removeClass('fa-check-circle fa-times-circle').addClass('fa-spinner fa-spin');
        checkDiv.find('span').text('Checking availability...');
        
        emailTimeout = setTimeout(function() {
            $.ajax({
                url: '$checkEmailUrl',
                data: { email: email, id: '$userId' },
                success: function(data) {
                    checkDiv.removeClass('checking');
                    checkDiv.find('i').removeClass('fa-spinner fa-spin');
                    
                    if (data.available) {
                        checkDiv.addClass('success');
                        checkDiv.find('i').addClass('fa-check-circle');
                        checkDiv.find('span').text('Email available');
                    } else {
                        checkDiv.addClass('error');
                        checkDiv.find('i').addClass('fa-times-circle');
                        checkDiv.find('span').text('Email already in use');
                    }
                },
                error: function() {
                    checkDiv.hide();
                }
            });
        }, 500);
    });
    
    // Password strength checker
    $('#password').on('input', function() {
        const password = $(this).val();
        let strength = 0;
        const requirements = $('#password-requirements li');
        
        if (password.length === 0) {
            $('.password-strength-bar').css('width', '0%');
            requirements.removeClass('met').find('i').removeClass('fas fa-check-circle').addClass('far fa-circle');
            return;
        }
        
        // Check length
        if (password.length >= 8 && password.length <= 16) {
            strength += 33;
            requirements.eq(0).addClass('met').find('i').removeClass('far fa-circle').addClass('fas fa-check-circle');
        } else {
            requirements.eq(0).removeClass('met').find('i').removeClass('fas fa-check-circle').addClass('far fa-circle');
        }
        
        // Check for number
        if (/\d/.test(password)) {
            strength += 33;
            requirements.eq(1).addClass('met').find('i').removeClass('far fa-circle').addClass('fas fa-check-circle');
        } else {
            requirements.eq(1).removeClass('met').find('i').removeClass('fas fa-check-circle').addClass('far fa-circle');
        }
        
        // Check for special character
        if (/[\W_]/.test(password)) {
            strength += 34;
            requirements.eq(2).addClass('met').find('i').removeClass('far fa-circle').addClass('fas fa-check-circle');
        } else {
            requirements.eq(2).removeClass('met').find('i').removeClass('fas fa-check-circle').addClass('far fa-circle');
        }
        
        // Update strength bar
        const strengthBar = $('.password-strength-bar');
        strengthBar.css('width', strength + '%');
        
        if (strength < 50) {
            strengthBar.css('background', 'linear-gradient(90deg, #f5576c 0%, #f093fb 100%)');
        } else if (strength < 100) {
            strengthBar.css('background', 'linear-gradient(90deg, #ffc107 0%, #ff9800 100%)');
        } else {
            strengthBar.css('background', 'linear-gradient(90deg, #43e97b 0%, #38f9d7 100%)');
        }
    });
    
    // Password confirmation match check
    $('#password-confirm').on('input', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();
        const matchDiv = $('#password-match');
        
        if (confirmPassword.length === 0) {
            matchDiv.hide();
            return;
        }
        
        matchDiv.show();
        
        if (password === confirmPassword) {
            matchDiv.removeClass('error').addClass('success');
            matchDiv.html('<i class="fas fa-check-circle"></i><span>Passwords match</span>');
        } else {
            matchDiv.removeClass('success').addClass('error');
            matchDiv.html('<i class="fas fa-times-circle"></i><span>Passwords do not match</span>');
        }
    });
    
    // Form submission validation
    $('#user-form').on('submit', function(e) {
        const password = $('#password').val();
        const passwordConfirm = $('#password-confirm').val();
        
        // Check if new record and passwords don't match
        if ($('#password-confirm').length && password !== passwordConfirm) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }
        
        // Disable submit button to prevent double submission
        $('#submit-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    });
JS);

// CSS to hide native browser password icons
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