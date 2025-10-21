<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserJob $model */

$this->title = 'Employment Information';
$this->params['breadcrumbs'][] = ['label' => 'My Profile', 'url' => ['user-details/view']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
/* Reuse enhanced styles from profile edit */
:root {
    --primary-gradient: linear-gradient(135deg, #004135, #11684d);
    --success-gradient: linear-gradient(135deg, #10b981, #00c77f);
    --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    --card-hover-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
    --input-focus-glow: 0 0 0 4px rgba(0, 168, 107, 0.15);
}

body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    background-attachment: fixed !important;
    min-height: 100vh;
}

.job-edit-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem 1rem;
    animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Briefcase Icon Header */
.job-header {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    color: white;
    border-radius: 24px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 60px rgba(30, 64, 175, 0.3);
    position: relative;
    overflow: hidden;
}

.job-header::before {
    content: '💼';
    position: absolute;
    font-size: 15rem;
    opacity: 0.1;
    top: -3rem;
    right: -2rem;
    transform: rotate(-15deg);
}

.job-header-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}

.job-header-text {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Keep job icon visually balanced on right */
.job-icon-large {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

.job-header h1 {
    font-size: 2.25rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
}

.job-header p {
    opacity: 0.95;
    font-size: 1.0625rem;
    margin: 0;
}

/* Salary Input with Currency Symbol */
.currency-input-group {
    position: relative;
}

.currency-symbol {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 700;
    color: #10b981;
    font-size: 1.125rem;
    pointer-events: none;
    z-index: 1;
}

.currency-input {
    padding-left: 3.5rem !important;
    font-weight: 600;
    font-size: 1.125rem;
    color: #059669;
}

.salary-hint {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    background: linear-gradient(135deg, #ecfdf5, #d1fae5);
    border-radius: 14px;
    margin-top: 1.5rem;
    border: 1px solid #86efac;
}

.salary-hint i {
    font-size: 1.5rem;
    color: #059669;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.salary-hint-content {
    font-size: 0.9375rem;
    color: #065f46;
    line-height: 1.6;
}

.salary-hint-content strong {
    display: block;
    margin-bottom: 0.25rem;
}

/* Copy same enhanced styles from profile edit */
.form-card {
    background: white;
    border-radius: 24px;
    padding: 2rem;
    box-shadow: var(--card-shadow);
    border: 1px solid #f3f4f6;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    margin-bottom: 1.5rem;
}

.form-card:hover {
    box-shadow: var(--card-hover-shadow);
}

.form-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.form-card-header i {
    font-size: 2rem;
    color: white;
    background: var(--primary-gradient);
    padding: 0.875rem;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(0, 65, 53, 0.25);
}

.form-card-header h3 {
    font-size: 1.375rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
    flex: 1;
}

.required-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.875rem;
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border-radius: 50px;
    color: #991b1b;
    font-size: 0.8125rem;
    font-weight: 700;
    border: 1px solid #fca5a5;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 1rem;
}

.form-col-full {
    grid-column: 1 / -1;
}

.form-label-modern {
    display: block;
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.75rem;
    font-size: 0.9375rem;
}

.required-star {
    color: #ef4444;
    margin-left: 0.25rem;
}

.form-control-modern {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e5e7eb;
    border-radius: 14px;
    font-size: 1rem;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    background-color: #fafafa;
    color: #111827;
}

.form-control-modern:focus {
    outline: none;
    border-color: #00a86b;
    background-color: white;
    box-shadow: var(--input-focus-glow);
    transform: translateY(-1px);
}

.textarea-modern {
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
}

.error-message {
    display: none; /* Hidden by default */
    align-items: center;
    gap: 0.5rem;
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 600;
    padding: 0.5rem 0.75rem;
    background: #fef2f2;
    border-radius: 8px;
    border-left: 3px solid #dc2626;
}

.error-message:not(:empty) {
    display: flex; /* Show when it has content */
}

.error-message::before {
    content: '⚠';
    font-size: 1.125rem;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem;
    background: linear-gradient(135deg, #f9fafb, #ffffff);
    border-radius: 24px;
    gap: 1rem;
    border: 2px dashed #e5e7eb;
}

.actions-left,
.actions-right {
    display: flex;
    gap: 1rem;
}

.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border-radius: 14px;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.btn-primary-modern {
    background: var(--success-gradient);
    color: white;
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(16, 185, 129, 0.5);
}

.btn-secondary-modern {
    background: white;
    color: #374151;
    border: 2px solid #e5e7eb;
}

.btn-secondary-modern:hover {
    background: #f9fafb;
    transform: translateY(-2px);
}

.btn-outline-modern {
    background: transparent;
    color: #6b7280;
    border: 2px solid #d1d5db;
}

.btn-outline-modern:hover {
    background: #f3f4f6;
    color: #374151;
}

.btn-back {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.25);
    border: 2px solid rgba(255, 255, 255, 0.3);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    transition: all 0.3s ease;
    backdrop-filter: blur(8px);
    margin: 0;
}

.btn-back:hover {
    background: white;
    color: #1e40af;
    transform: translateX(-4px);
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .actions-left,
    .actions-right {
        width: 100%;
        flex-direction: column;
    }
    
    .btn-modern {
        width: 100%;
    }
}
</style>

<div class="job-edit-container">
    <!-- Job Header -->
    <div class="job-header">
        <div class="job-header-content">
            <!-- Back Button -->
            <?= Html::a('<i class="bi bi-arrow-left"></i>', ['user-details/view'], [
                'class' => 'btn-back',
                'title' => 'Back to Profile'
            ]) ?>
            <div class="job-header-text">
                <h1><?= Html::encode($this->title) ?></h1>
                <p>Add or update your employment details for verification purposes</p>
            </div>
             <div class="job-icon-large">
                <i class="bi bi-briefcase-fill"></i>
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

    <!-- Employment Details Card -->
    <div class="form-card">
        <div class="form-card-header">
            <i class="bi bi-building"></i>
            <h3>Employment Details</h3>
            <span class="required-badge">
                <i class="bi bi-asterisk"></i> All Required
            </span>
        </div>

        <div class="form-row">
            <div>
                <?= $form->field($model, 'job')->textInput([
                    'placeholder' => 'e.g., Teacher, Engineer, Manager',
                    'maxlength' => true
                ])->label('Job Title <span class="required-star">*</span>') ?>
            </div>
            <div>
                <?= $form->field($model, 'employer')->textInput([
                    'placeholder' => 'e.g., Universiti Malaysia Sabah',
                    'maxlength' => true
                ])->label('Employer Name <span class="required-star">*</span>') ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col-full">
                <?= $form->field($model, 'employer_address')->textarea([
                    'rows' => 3,
                    'placeholder' => 'Enter complete employer address',
                    'class' => 'form-control-modern textarea-modern'
                ])->label('Employer Address <span class="required-star">*</span>') ?>
            </div>
        </div>

        <div class="form-row">
            <div>
                <?= $form->field($model, 'employer_phone_number')->textInput([
                    'placeholder' => 'e.g., +60123456789',
                    'maxlength' => true
                ])->label('Employer Phone') ?>
            </div>
        </div>
    </div>

    <!-- Salary Information Card -->
    <div class="form-card">
        <div class="form-card-header">
            <i class="bi bi-cash-stack"></i>
            <h3>Salary Information</h3>
        </div>

        <div class="form-row">
            <div>
                <label class="form-label-modern">Gross Salary (RM) <span class="required-star">*</span></label>
                <div class="currency-input-group">
                    <span class="currency-symbol">RM</span>
                    <?= Html::activeTextInput($model, 'gross_salary', [
                        'class' => 'form-control-modern currency-input',
                        'type' => 'number',
                        'min' => 0,
                        'step' => '0.01',
                        'placeholder' => '0.00',
                        'id' => 'gross-salary'
                    ]) ?>
                </div>
                <?= Html::error($model, 'gross_salary', ['class' => 'error-message']) ?>
            </div>
            <div>
                <label class="form-label-modern">Net Salary (RM) <span class="required-star">*</span></label>
                <div class="currency-input-group">
                    <span class="currency-symbol">RM</span>
                    <?= Html::activeTextInput($model, 'net_salary', [
                        'class' => 'form-control-modern currency-input',
                        'type' => 'number',
                        'min' => 0,
                        'step' => '0.01',
                        'placeholder' => '0.00',
                        'id' => 'net-salary'
                    ]) ?>
                </div>
                <?= Html::error($model, 'net_salary', ['class' => 'error-message']) ?>
            </div>
        </div>

        <div class="salary-hint">
            <i class="bi bi-lightbulb-fill"></i>
            <div class="salary-hint-content">
                <strong>💡 Salary Guidelines:</strong>
                <div><strong>Gross Salary:</strong> Total salary before deductions (EPF, SOCSO, tax)</div>
                <div><strong>Net Salary:</strong> Take-home salary after all deductions (typically 80-85% of gross)</div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="form-actions">
        <div class="actions-left">
            <?= Html::a('<i class="bi bi-x-circle"></i><span>Cancel</span>', ['user-details/view'], [
                'class' => 'btn-modern btn-secondary-modern',
                'data' => ['confirm' => 'Discard changes?'],
            ]) ?>
        </div>
        <div class="actions-right">
            <button type="button" class="btn-modern btn-outline-modern" onclick="resetForm()">
                <i class="bi bi-arrow-clockwise"></i>
                <span>Reset</span>
            </button>
            <?= Html::submitButton('<i class="bi bi-check-circle-fill"></i><span>Save Employment Info</span>', [
                'class' => 'btn-modern btn-primary-modern',
                'id' => 'submit-btn'
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
function resetForm() {
    if (confirm('Are you sure you want to reset all fields?')) {
        document.querySelector('.modern-form').reset();
    }
}
</script>