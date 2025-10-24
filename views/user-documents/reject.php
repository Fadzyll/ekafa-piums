<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserDocuments $model */

$this->title = 'Reject Document: ' . $model->document_name;
$this->params['breadcrumbs'][] = ['label' => 'User Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->document_name, 'url' => ['view', 'document_id' => $model->document_id]];
$this->params['breadcrumbs'][] = 'Reject';
?>

<style>
.reject-header {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-radius: 20px 20px 0 0;
    padding: 2.5rem;
    color: white;
    margin-bottom: 0;
}

.reject-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.reject-subtitle {
    opacity: 0.9;
    font-size: 1rem;
    margin: 0;
}

.reject-body {
    padding: 2.5rem;
}

.warning-box {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-left: 4px solid #ef4444;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.warning-box h4 {
    color: #991b1b;
    font-weight: 700;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.warning-box p {
    color: #7f1d1d;
    margin: 0;
}

.document-info-box {
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #6b7280;
}

.info-value {
    font-weight: 700;
    color: #111827;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.75rem;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-label i {
    color: #ef4444;
}

.form-control {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 1rem;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    outline: none;
}

.button-group {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 2rem;
}

.btn-action {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    cursor: pointer;
}

.btn-cancel {
    background: #e5e7eb;
    color: #374151;
    text-decoration: none;
}

.btn-cancel:hover {
    background: #d1d5db;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    color: #374151;
}

.btn-reject {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
}

.char-counter {
    text-align: right;
    color: #6b7280;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.required-note {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .button-group {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="user-documents-reject">
    <div class="card shadow-lg border-0">
        <!-- Header -->
        <div class="reject-header">
            <div class="reject-title">
                <i class="bi bi-x-circle-fill"></i>
                Reject Document
            </div>
            <p class="reject-subtitle">
                Provide a reason for rejecting this document
            </p>
        </div>

        <!-- Body -->
        <div class="reject-body">
            <!-- Warning Box -->
            <div class="warning-box">
                <h4>
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Important Notice
                </h4>
                <p>
                    Rejecting this document will notify the user. Please provide a clear and specific reason 
                    so they can understand what needs to be corrected and resubmit the document.
                </p>
            </div>

            <!-- Document Information -->
            <div class="document-info-box">
                <h5 style="font-weight: 700; margin-bottom: 1rem; color: #111827;">
                    <i class="bi bi-file-earmark-text"></i>
                    Document Information
                </h5>
                <div class="info-row">
                    <span class="info-label">Document Name</span>
                    <span class="info-value"><?= Html::encode($model->document_name) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Document Type</span>
                    <span class="info-value"><?= Html::encode($model->document_type) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">User</span>
                    <span class="info-value">
                        <?= $model->user ? Html::encode($model->user->username ?? 'User #' . $model->user_id) : 'User #' . $model->user_id ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Upload Date</span>
                    <span class="info-value">
                        <?= Yii::$app->formatter->asDatetime($model->upload_date, 'php:M d, Y h:i A') ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Current Status</span>
                    <span class="info-value">
                        <span class="badge bg-warning"><?= Html::encode($model->displayStatus()) ?></span>
                    </span>
                </div>
            </div>

            <!-- Rejection Form -->
            <?php $form = ActiveForm::begin([
                'id' => 'reject-form',
                'options' => ['class' => 'rejection-form'],
            ]); ?>

            <div class="form-group">
                <label class="form-label" for="rejection_reason">
                    <i class="bi bi-chat-left-text-fill"></i>
                    Rejection Reason
                </label>
                <textarea 
                    class="form-control" 
                    id="rejection_reason" 
                    name="rejection_reason" 
                    rows="6" 
                    placeholder="Please provide a detailed reason for rejecting this document. Be specific about what needs to be corrected..."
                    required
                    maxlength="1000"></textarea>
                <div class="char-counter">
                    <span id="charCount">0</span> / 1000 characters
                </div>
                <div class="required-note">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>A rejection reason is required and must be at least 10 characters long.</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="button-group">
                <?= Html::a(
                    '<i class="bi bi-arrow-left"></i> Cancel', 
                    ['view', 'document_id' => $model->document_id], 
                    ['class' => 'btn-action btn-cancel']
                ) ?>
                <?= Html::submitButton(
                    '<i class="bi bi-x-circle-fill"></i> Reject Document',
                    [
                        'class' => 'btn-action btn-reject',
                        'id' => 'rejectBtn',
                    ]
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$script = <<< JS
// Character counter
const textarea = document.getElementById('rejection_reason');
const charCount = document.getElementById('charCount');
const rejectBtn = document.getElementById('rejectBtn');

textarea.addEventListener('input', function() {
    const count = this.value.length;
    charCount.textContent = count;
    
    // Update button state
    if (count < 10) {
        rejectBtn.disabled = true;
        rejectBtn.style.opacity = '0.5';
        rejectBtn.style.cursor = 'not-allowed';
    } else {
        rejectBtn.disabled = false;
        rejectBtn.style.opacity = '1';
        rejectBtn.style.cursor = 'pointer';
    }
});

// Initial check
textarea.dispatchEvent(new Event('input'));

// Form validation
document.getElementById('reject-form').addEventListener('submit', function(e) {
    const reason = textarea.value.trim();
    
    if (reason.length < 10) {
        e.preventDefault();
        alert('Please provide a more detailed rejection reason (at least 10 characters).');
        textarea.focus();
        return false;
    }
    
    // Confirm rejection
    if (!confirm('Are you sure you want to reject this document? The user will be notified.')) {
        e.preventDefault();
        return false;
    }
    
    return true;
});
JS;
$this->registerJs($script);
?>