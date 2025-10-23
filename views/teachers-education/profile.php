<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TeachersEducation $model */

$this->title = 'Education Information';
$this->params['breadcrumbs'][] = ['label' => 'My Profile', 'url' => ['user-details/view']];
$this->params['breadcrumbs'][] = $this->title;

$isUpdate = !$model->isNewRecord;
?>

<style>
/* Reuse enhanced styles from user job profile */
:root {
    --primary-gradient: linear-gradient(135deg, #004135, #11684d);
    --success-gradient: linear-gradient(135deg, #10b981, #00c77f);
    --education-gradient: linear-gradient(135deg, #7c3aed, #a78bfa);
    --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    --card-hover-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
    --input-focus-glow: 0 0 0 4px rgba(124, 58, 237, 0.15);
}

body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    background-attachment: fixed !important;
    min-height: 100vh;
}

.education-edit-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem 1rem;
    animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Graduation Cap Icon Header */
.education-header {
    background: var(--education-gradient);
    color: white;
    border-radius: 24px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 60px rgba(124, 58, 237, 0.3);
    position: relative;
    overflow: hidden;
}

.education-header::before {
    content: '🎓';
    position: absolute;
    font-size: 15rem;
    opacity: 0.1;
    top: -3rem;
    right: -2rem;
    transform: rotate(-15deg);
}

.education-header-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}

.education-header-text {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Keep education icon visually balanced on right */
.education-icon-large {
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

.education-header h1 {
    font-size: 2.25rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
}

.education-header p {
    opacity: 0.95;
    font-size: 1.0625rem;
    margin: 0;
}

/* File Upload Areas */
.file-upload-section {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-top: 1rem;
}

.file-upload-card {
    position: relative;
    padding: 2rem;
    border: 2px dashed #d1d5db;
    border-radius: 16px;
    background: linear-gradient(135deg, #fafafa, #ffffff);
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-card:hover {
    border-color: #7c3aed;
    background: linear-gradient(135deg, #faf5ff, #f5f3ff);
    transform: translateY(-2px);
}

.file-upload-card.has-file {
    border-color: #10b981;
    background: linear-gradient(135deg, #ecfdf5, #d1fae5);
    border-style: solid;
}

.file-upload-icon {
    font-size: 3rem;
    color: #9ca3af;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.file-upload-card:hover .file-upload-icon {
    color: #7c3aed;
    transform: scale(1.1);
}

.file-upload-card.has-file .file-upload-icon {
    color: #10b981;
}

.file-upload-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.5rem;
}

.file-upload-text {
    color: #6b7280;
    font-size: 0.9375rem;
    margin-bottom: 0.5rem;
}

.file-upload-hint {
    color: #9ca3af;
    font-size: 0.8125rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.file-input-hidden {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    cursor: pointer;
}

.current-file-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    background: white;
    border: 2px solid #10b981;
    border-radius: 12px;
    margin-top: 1rem;
    font-size: 0.9375rem;
    font-weight: 600;
    color: #065f46;
}

.current-file-badge i {
    font-size: 1.25rem;
}

.current-file-name {
    flex: 1;
    text-align: left;
    word-break: break-all;
}

.btn-view-file {
    padding: 0.375rem 0.75rem;
    background: #059669;
    color: white;
    border-radius: 8px;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

.btn-view-file:hover {
    background: #047857;
    transform: scale(1.05);
    color: white;
}

/* Degree Level Badges */
.degree-preview {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: 16px;
    margin-top: 1rem;
    border: 2px solid #f59e0b;
}

.degree-preview i {
    font-size: 2rem;
    color: #92400e;
}

.degree-preview-text {
    font-size: 1.125rem;
    font-weight: 700;
    color: #92400e;
}

/* Info Boxes */
.info-box {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    border-radius: 14px;
    margin-top: 1.5rem;
    border: 1px solid #60a5fa;
}

.info-box i {
    font-size: 1.5rem;
    color: #1e40af;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.info-box-content {
    font-size: 0.9375rem;
    color: #1e3a8a;
    line-height: 1.6;
}

.info-box-content strong {
    display: block;
    margin-bottom: 0.25rem;
}

/* Copy same enhanced styles from user job profile */
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
    background: var(--education-gradient);
    padding: 0.875rem;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(124, 58, 237, 0.25);
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
    border-color: #7c3aed;
    background-color: white;
    box-shadow: var(--input-focus-glow);
    transform: translateY(-1px);
}

.select-modern {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%237c3aed' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1.25rem center;
    padding-right: 3.5rem;
    appearance: none;
    cursor: pointer;
}

.error-message {
    display: none;
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
    display: flex;
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
    text-decoration: none;
}

.btn-primary-modern {
    background: var(--success-gradient);
    color: white;
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(16, 185, 129, 0.5);
    color: white;
}

.btn-secondary-modern {
    background: white;
    color: #374151;
    border: 2px solid #e5e7eb;
}

.btn-secondary-modern:hover {
    background: #f9fafb;
    transform: translateY(-2px);
    color: #374151;
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
    text-decoration: none;
}

.btn-back:hover {
    background: white;
    color: #7c3aed;
    transform: translateX(-4px);
}

@media (max-width: 768px) {
    .form-row,
    .file-upload-section {
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

<div class="education-edit-container">
    <!-- Education Header -->
    <div class="education-header">
        <div class="education-header-content">
            <!-- Back Button -->
            <?= Html::a('<i class="bi bi-arrow-left"></i>', ['user-details/view'], [
                'class' => 'btn-back',
                'title' => 'Back to Profile'
            ]) ?>
            <div class="education-header-text">
                <h1><?= Html::encode($this->title) ?></h1>
                <p><?= $isUpdate ? 'Update your educational qualification details' : 'Add your educational qualification for teacher verification' ?></p>
            </div>
            <div class="education-icon-large">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
        </div>
    </div>

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'modern-form'
        ],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'form-label-modern'],
            'inputOptions' => ['class' => 'form-control-modern'],
            'errorOptions' => ['class' => 'error-message'],
        ],
    ]); ?>

    <!-- Education Details Card -->
    <div class="form-card">
        <div class="form-card-header">
            <i class="bi bi-bank"></i>
            <h3>Education Details</h3>
            <span class="required-badge">
                <i class="bi bi-asterisk"></i> Required Fields
            </span>
        </div>

        <div class="form-row">
            <div class="form-col-full">
                <?= $form->field($model, 'institution_name')->textInput([
                    'placeholder' => 'e.g., Universiti Malaysia Sabah',
                    'maxlength' => true,
                    'autofocus' => true
                ])->label('Institution Name <span class="required-star">*</span>') ?>
            </div>
        </div>

        <div class="form-row">
            <div>
                <?= $form->field($model, 'degree_level')->dropDownList(
                    \app\models\TeachersEducation::optsDegreeLevel(),
                    [
                        'prompt' => 'Select Degree Level',
                        'class' => 'form-control-modern select-modern',
                        'id' => 'degree-level-select'
                    ]
                )->label('Degree Level <span class="required-star">*</span>') ?>
            </div>
            <div>
                <?= $form->field($model, 'field_of_study')->textInput([
                    'placeholder' => 'e.g., Computer Science, Education',
                    'maxlength' => true
                ])->label('Field of Study <span class="required-star">*</span>') ?>
            </div>
        </div>

        <div class="form-row">
            <div>
                <?= $form->field($model, 'graduation_date')->textInput([
                    'type' => 'date',
                    'max' => date('Y-m-d')
                ])->label('Graduation Date') ?>
            </div>
            <div style="display: flex; align-items: flex-end;">
                <div id="degree-preview" class="degree-preview" style="display: none;">
                    <i class="bi bi-award-fill"></i>
                    <span class="degree-preview-text" id="degree-preview-text"></span>
                </div>
            </div>
        </div>

        <div class="info-box">
            <i class="bi bi-info-circle-fill"></i>
            <div class="info-box-content">
                <strong>💡 Important Information:</strong>
                Please ensure all education details are accurate and match your official documents. This information will be used for teacher verification and qualification assessment.
            </div>
        </div>
    </div>

    <!-- Documents Upload Card -->
    <div class="form-card">
        <div class="form-card-header">
            <i class="bi bi-cloud-upload-fill"></i>
            <h3>Upload Supporting Documents</h3>
        </div>

        <p style="color: #6b7280; margin-bottom: 1.5rem;">
            Upload your certificate and/or transcript to verify your educational qualification. Accepted formats: PDF, JPG, PNG (Max 5MB each)
        </p>

        <div class="file-upload-section">
            <!-- Certificate Upload -->
            <div class="file-upload-card <?= $model->certificate_url ? 'has-file' : '' ?>" onclick="document.getElementById('certificatefile').click()">
                <i class="bi <?= $model->certificate_url ? 'bi-file-earmark-check-fill' : 'bi-cloud-upload' ?> file-upload-icon"></i>
                <div class="file-upload-title">📜 Certificate</div>
                <div class="file-upload-text">
                    <?= $model->certificate_url ? 'File uploaded' : 'Click to upload certificate' ?>
                </div>
                <div class="file-upload-hint">
                    <i class="bi bi-info-circle"></i>
                    <span>PDF, JPG, PNG • Max 5MB</span>
                </div>
                
                <?= $form->field($model, 'certificateFile', [
                    'template' => '{input}{error}',
                    'options' => ['style' => 'display: none;']
                ])->fileInput([
                    'accept' => '.pdf,.jpg,.jpeg,.png',
                    'class' => 'file-input-hidden',
                    'id' => 'certificatefile',
                    'onchange' => 'updateFileName(this, "cert")'
                ])->label(false) ?>
                
                <?php if ($model->certificate_url): ?>
                    <div class="current-file-badge">
                        <i class="bi bi-file-earmark-check-fill"></i>
                        <span class="current-file-name"><?= basename($model->certificate_url) ?></span>
                        <?= Html::a('<i class="bi bi-eye-fill"></i> View', 
                            Yii::getAlias('@web/' . $model->certificate_url), 
                            ['class' => 'btn-view-file', 'target' => '_blank', 'onclick' => 'event.stopPropagation()']) ?>
                    </div>
                <?php endif; ?>
                
                <div id="cert-new-file" style="display: none; margin-top: 1rem;">
                    <span style="color: #059669; font-weight: 600;">
                        <i class="bi bi-check-circle-fill"></i> New file selected: <span id="cert-filename"></span>
                    </span>
                </div>
            </div>

            <!-- Transcript Upload -->
            <div class="file-upload-card <?= $model->transcript_url ? 'has-file' : '' ?>" onclick="document.getElementById('transcriptfile').click()">
                <i class="bi <?= $model->transcript_url ? 'bi-file-earmark-check-fill' : 'bi-cloud-upload' ?> file-upload-icon"></i>
                <div class="file-upload-title">📄 Transcript</div>
                <div class="file-upload-text">
                    <?= $model->transcript_url ? 'File uploaded' : 'Click to upload transcript' ?>
                </div>
                <div class="file-upload-hint">
                    <i class="bi bi-info-circle"></i>
                    <span>PDF, JPG, PNG • Max 5MB</span>
                </div>
                
                <?= $form->field($model, 'transcriptFile', [
                    'template' => '{input}{error}',
                    'options' => ['style' => 'display: none;']
                ])->fileInput([
                    'accept' => '.pdf,.jpg,.jpeg,.png',
                    'class' => 'file-input-hidden',
                    'id' => 'transcriptfile',
                    'onchange' => 'updateFileName(this, "transcript")'
                ])->label(false) ?>
                
                <?php if ($model->transcript_url): ?>
                    <div class="current-file-badge">
                        <i class="bi bi-file-earmark-check-fill"></i>
                        <span class="current-file-name"><?= basename($model->transcript_url) ?></span>
                        <?= Html::a('<i class="bi bi-eye-fill"></i> View', 
                            Yii::getAlias('@web/' . $model->transcript_url), 
                            ['class' => 'btn-view-file', 'target' => '_blank', 'onclick' => 'event.stopPropagation()']) ?>
                    </div>
                <?php endif; ?>
                
                <div id="transcript-new-file" style="display: none; margin-top: 1rem;">
                    <span style="color: #059669; font-weight: 600;">
                        <i class="bi bi-check-circle-fill"></i> New file selected: <span id="transcript-filename"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="info-box" style="margin-top: 1.5rem;">
            <i class="bi bi-shield-check"></i>
            <div class="info-box-content">
                <strong>🔒 Document Security:</strong>
                Your uploaded documents are stored securely and will only be accessible to authorized administrators for verification purposes. All files are encrypted and protected.
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="form-actions">
        <div class="actions-left">
            <?= Html::a('<i class="bi bi-x-circle"></i><span>Cancel</span>', ['user-details/view'], [
                'class' => 'btn-modern btn-secondary-modern',
                'data' => ['confirm' => 'Discard changes and go back?'],
            ]) ?>
        </div>
        <div class="actions-right">
            <button type="button" class="btn-modern btn-outline-modern" onclick="resetForm()">
                <i class="bi bi-arrow-clockwise"></i>
                <span>Reset</span>
            </button>
            <?= Html::submitButton(
                $isUpdate 
                    ? '<i class="bi bi-check-circle-fill"></i><span>Update Education</span>' 
                    : '<i class="bi bi-plus-circle-fill"></i><span>Save Education</span>', 
                ['class' => 'btn-modern btn-primary-modern', 'id' => 'submit-btn']
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
// Degree level preview
document.getElementById('degree-level-select').addEventListener('change', function() {
    const selectedText = this.options[this.selectedIndex].text;
    const preview = document.getElementById('degree-preview');
    const previewText = document.getElementById('degree-preview-text');
    
    if (this.value) {
        previewText.textContent = selectedText;
        preview.style.display = 'inline-flex';
    } else {
        preview.style.display = 'none';
    }
});

// Initialize degree preview if value exists
window.addEventListener('load', function() {
    const degreeSelect = document.getElementById('degree-level-select');
    if (degreeSelect.value) {
        const selectedText = degreeSelect.options[degreeSelect.selectedIndex].text;
        document.getElementById('degree-preview-text').textContent = selectedText;
        document.getElementById('degree-preview').style.display = 'inline-flex';
    }
});

// File name update
function updateFileName(input, type) {
    const file = input.files[0];
    if (file) {
        // Check file size
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            alert('⚠️ File size must be less than 5MB');
            input.value = '';
            return;
        }
        
        // Check file type
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('⚠️ Please upload PDF, JPG, or PNG files only');
            input.value = '';
            return;
        }
        
        // Update UI
        document.getElementById(type + '-filename').textContent = file.name;
        document.getElementById(type + '-new-file').style.display = 'block';
        
        // Update card appearance
        const card = input.closest('.file-upload-card');
        card.classList.add('has-file');
        const icon = card.querySelector('.file-upload-icon');
        icon.className = 'bi bi-file-earmark-check-fill file-upload-icon';
    }
}

function resetForm() {
    if (confirm('🔄 Reset all changes? Any unsaved data will be lost.')) {
        document.querySelector('.modern-form').reset();
        document.getElementById('degree-preview').style.display = 'none';
        document.getElementById('cert-new-file').style.display = 'none';
        document.getElementById('transcript-new-file').style.display = 'none';
    }
}

// Prevent form submission on Enter key in text inputs
document.querySelectorAll('.form-control-modern').forEach(input => {
    if (input.type !== 'textarea') {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    }
});
</script>