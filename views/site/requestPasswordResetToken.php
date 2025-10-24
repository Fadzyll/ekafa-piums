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
                    <i class="bi bi-key"></i>
                </div>
                <h1 class="auth-title">Reset Your Password</h1>
                <p class="auth-subtitle">Enter your email address and we'll send you a reset link</p>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'request-password-reset-form',
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
                <?= $form->field($model, 'email')->label('Email')->begin() ?>
                    <div class="input-with-icon">
                        <?= Html::activeTextInput($model, 'email', [
                            'class' => 'form-control',
                            'placeholder' => 'Enter your registered email',
                            'autofocus' => true,
                        ]) ?>
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                    <?= Html::error($model, 'email', ['class' => 'invalid-feedback d-block']) ?>
                <?= $form->field($model, 'email')->end() ?>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-modern btn-primary-modern w-100 mt-4" id="reset-btn">
                <i class="bi bi-send"></i>
                Send Reset Link
            </button>

            <?php ActiveForm::end(); ?>

            <!-- Back to Login -->
            <div class="text-center mt-4">
                <p class="text-muted mb-0">Remember your password?</p>
                <?= Html::a('Back to Login', ['site/select-role'], [
                    'class' => 'btn-modern btn-secondary-modern w-100 mt-2'
                ]) ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
// Loading state on submit
document.getElementById('request-password-reset-form').addEventListener('submit', function() {
    const btn = document.getElementById('reset-btn');
    btn.classList.add('btn-loading');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';
});
JS);

$this->registerCss(<<<CSS
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

/* Error message styling */
.invalid-feedback {
    display: block !important;
    margin-top: 0.25rem;
    font-size: 0.875rem;
}

/* Fix field spacing */
.field-passwordresetrequestform-email {
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