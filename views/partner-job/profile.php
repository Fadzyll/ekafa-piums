<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartnerJob $model */

$this->title = 'Partner Employment Information';
$this->params['breadcrumbs'][] = ['label' => 'My Profile', 'url' => ['user-details/view']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-container fade-in">
    <div class="edit-profile-wrapper">
        <!-- Header -->
        <div class="edit-header">
            <div class="d-flex align-items-center gap-3">
                <div class="back-button">
                    <?= Html::a('<i class="bi bi-arrow-left"></i>', ['user-details/view'], [
                        'class' => 'btn-modern btn-secondary-modern btn-icon',
                        'title' => 'Back to Profile'
                    ]) ?>
                </div>
                <div>
                    <h1 class="edit-title"><?= Html::encode($this->title) ?></h1>
                    <p class="edit-subtitle">Update your partner's employment details</p>
                </div>
            </div>
        </div>

        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'modern-form'],
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'form-label-modern'],
                'inputOptions' => ['class' => 'form-control-modern'],
                'errorOptions' => ['class' => 'error-message'],
            ],
        ]); ?>

        <div class="single-column-form">
            <!-- Employment Information Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-briefcase-fill"></i>
                    <h3>Partner's Employment Details</h3>
                    <span class="required-badge">
                        <i class="bi bi-asterisk"></i> Required
                    </span>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <?= $form->field($model, 'partner_job')->textInput([
                            'placeholder' => 'e.g., Teacher, Engineer, Manager',
                            'maxlength' => true
                        ])->label('Job Title <span class="required-star">*</span>') ?>
                    </div>
                    <div class="form-col">
                        <?= $form->field($model, 'partner_employer')->textInput([
                            'placeholder' => 'e.g., Universiti Malaysia Sabah',
                            'maxlength' => true
                        ])->label('Employer Name <span class="required-star">*</span>') ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col full-width">
                        <?= $form->field($model, 'partner_employer_address')->textarea([
                            'rows' => 3,
                            'placeholder' => 'Enter employer complete address',
                            'class' => 'form-control-modern textarea-modern'
                        ])->label('Employer Address <span class="required-star">*</span>') ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <?= $form->field($model, 'partner_employer_phone_number')->textInput([
                            'placeholder' => 'e.g., +60123456789',
                            'maxlength' => true
                        ])->label('Employer Phone <span class="required-star">*</span>') ?>
                    </div>
                </div>
            </div>

            <!-- Salary Information Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-cash-stack"></i>
                    <h3>Partner's Salary Information</h3>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="currency-input-wrapper">
                            <?= $form->field($model, 'partner_gross_salary')->textInput([
                                'type' => 'number',
                                'min' => 0,
                                'step' => '0.01',
                                'placeholder' => '0.00',
                                'class' => 'form-control-modern currency-input'
                            ])->label('Gross Salary (RM) <span class="required-star">*</span>') ?>
                            <span class="currency-symbol">RM</span>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="currency-input-wrapper">
                            <?= $form->field($model, 'partner_net_salary')->textInput([
                                'type' => 'number',
                                'min' => 0,
                                'step' => '0.01',
                                'placeholder' => '0.00',
                                'class' => 'form-control-modern currency-input'
                            ])->label('Net Salary (RM) <span class="required-star">*</span>') ?>
                            <span class="currency-symbol">RM</span>
                        </div>
                    </div>
                </div>

                <div class="salary-hint">
                    <i class="bi bi-info-circle"></i>
                    <div>
                        <strong>Gross Salary:</strong> Total salary before deductions<br>
                        <strong>Net Salary:</strong> Take-home salary after deductions
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <div class="actions-left">
                    <?= Html::a('<i class="bi bi-x-circle"></i> Cancel', ['user-details/view'], [
                        'class' => 'btn-modern btn-secondary-modern',
                        'data' => [
                            'confirm' => 'Discard changes and go back?',
                        ],
                    ]) ?>
                </div>
                <div class="actions-right">
                    <button type="button" class="btn-modern btn-outline-modern" onclick="resetForm()">
                        <i class="bi bi-arrow-clockwise"></i>
                        Reset
                    </button>
                    <?= Html::submitButton('<i class="bi bi-check-circle"></i> Save Partner Employment', [
                        'class' => 'btn-modern btn-primary-modern',
                        'id' => 'submit-btn'
                    ]) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$this->registerJs(<<<JS
// Form reset function
function resetForm() {
    if (confirm('Reset all changes?')) {
        document.querySelector('form').reset();
    }
}

// Form submission loading state
document.querySelector('form').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.classList.add('btn-loading');
    submitBtn.disabled = true;
});

// Auto-calculate net salary hint
const grossInput = document.querySelector('input[name="PartnerJob[partner_gross_salary]"]');
const netInput = document.querySelector('input[name="PartnerJob[partner_net_salary]"]');

if (grossInput && netInput) {
    grossInput.addEventListener('input', function() {
        if (this.value && !netInput.value) {
            // Suggest 85% as typical net salary
            const suggested = (parseFloat(this.value) * 0.85).toFixed(2);
            netInput.placeholder = 'Suggested: ' + suggested;
        }
    });
}
JS);
?>