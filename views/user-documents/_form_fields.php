<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\models\UserDocuments;

/** @var yii\web\View $this */
/** @var app\models\UserDocuments $model */
/** @var yii\widgets\ActiveForm $form */
/** @var bool $isAdmin */

$isAdmin = Yii::$app->user->identity->role === 'Admin';
?>

<style>
.admin-review-panel {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-left: 4px solid #f59e0b;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.admin-review-panel h4 {
    color: #92400e;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.admin-review-panel p {
    color: #78350f;
    margin: 0;
}

.quick-action-buttons {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 1.5rem;
}

.btn-quick-action {
    padding: 1rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-quick-approve {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-quick-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
}

.btn-quick-reject {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-quick-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
}
</style>

<?php if ($isAdmin): ?>
    <!-- Admin Review Panel -->
    <div class="admin-review-panel">
        <h4>
            <i class="bi bi-shield-check"></i>
            Admin Review Mode
        </h4>
        <p>As an admin, you can only update the document status, add admin notes, and provide rejection reasons. You cannot upload or replace files.</p>
        
        <!-- Quick Action Buttons -->
        <div class="quick-action-buttons">
            <?= Html::a(
                '<i class="bi bi-check-circle-fill"></i> Quick Approve',
                ['approve', 'document_id' => $model->document_id],
                [
                    'class' => 'btn btn-quick-action btn-quick-approve',
                    'data' => [
                        'confirm' => 'Are you sure you want to approve this document?',
                        'method' => 'post',
                    ],
                ]
            ) ?>
            <?= Html::a(
                '<i class="bi bi-x-circle-fill"></i> Quick Reject',
                ['reject', 'document_id' => $model->document_id],
                [
                    'class' => 'btn btn-quick-action btn-quick-reject',
                ]
            ) ?>
        </div>
    </div>

    <!-- Admin-Only Fields -->
    <div class="upload-section">
        <div class="section-header">
            <i class="bi bi-check2-square"></i>
            Review & Update Status
        </div>

        <div class="mb-4">
            <label class="form-label">
                <i class="bi bi-flag"></i>
                Document Status
            </label>
            <div class="status-selector">
                <?php foreach (UserDocuments::optsStatus() as $value => $label): ?>
                    <div class="status-option">
                        <?= Html::activeRadio($model, 'status', [
                            'value' => $value,
                            'uncheck' => null,
                            'id' => 'status-' . strtolower(str_replace(' ', '-', $value))
                        ]) ?>
                        <label for="status-<?= strtolower(str_replace(' ', '-', $value)) ?>">
                            <i class="bi bi-<?= $value === UserDocuments::STATUS_APPROVED ? 'check-circle-fill' : ($value === UserDocuments::STATUS_REJECTED ? 'x-circle-fill' : 'clock-fill') ?>"></i>
                            <span><?= $label ?></span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mb-4">
            <?= $form->field($model, 'admin_notes')->textarea([
                'rows' => 4,
                'placeholder' => 'Add any administrative notes about this document...'
            ])->label('<i class="bi bi-sticky"></i> Admin Notes', ['class' => 'form-label']) ?>
        </div>

        <div class="mb-0">
            <?= $form->field($model, 'rejection_reason')->textarea([
                'rows' => 3,
                'placeholder' => 'Required if rejecting the document...'
            ])->label('<i class="bi bi-exclamation-triangle"></i> Rejection Reason', ['class' => 'form-label']) ?>
            <div class="input-helper">
                <i class="bi bi-lightbulb"></i>
                <span>This field is required when rejecting a document</span>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Original Full Form for Teachers/Parents -->
    <!-- User & Document Info -->
    <div class="upload-section">
        <div class="section-header">
            <i class="bi bi-info-circle-fill"></i>
            Document Information
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <?= $form->field($model, 'user_id')->textInput([
                    'type' => 'number',
                    'min' => 1,
                    'placeholder' => 'Enter user ID',
                    'readonly' => !$model->isNewRecord
                ])->label('<i class="bi bi-person"></i> User ID', ['class' => 'form-label']) ?>
                <div class="input-helper">
                    <i class="bi bi-lightbulb"></i>
                    <span>Enter the ID of the user this document belongs to</span>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <?= $form->field($model, 'category_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map(
                        \app\models\DocumentCategory::find()->where(['status' => \app\models\DocumentCategory::STATUS_ACTIVE])->all(),
                        'category_id',
                        'category_name'
                    ),
                    ['prompt' => 'Select Document Category']
                )->label('<i class="bi bi-folder"></i> Document Category', ['class' => 'form-label']) ?>
                <div class="input-helper">
                    <i class="bi bi-lightbulb"></i>
                    <span>Choose the appropriate category for this document</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <?= $form->field($model, 'document_type')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'e.g., Birth Certificate, Teaching License'
                ])->label('<i class="bi bi-tag"></i> Document Type', ['class' => 'form-label']) ?>
                <div class="input-helper">
                    <i class="bi bi-lightbulb"></i>
                    <span>Specify the type of document</span>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <?= $form->field($model, 'document_name')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'e.g., John Doe Birth Certificate'
                ])->label('<i class="bi bi-file-text"></i> Document Name', ['class' => 'form-label']) ?>
                <div class="input-helper">
                    <i class="bi bi-lightbulb"></i>
                    <span>Provide a descriptive name for this document</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <?= $form->field($model, 'owner_type')->dropDownList(
                    UserDocuments::optsOwnerType(),
                    ['prompt' => 'Select Owner Type']
                )->label('<i class="bi bi-person-badge"></i> Owner Type', ['class' => 'form-label']) ?>
            </div>

            <div class="col-md-6 mb-0">
                <?= $form->field($model, 'owner_id')->textInput([
                    'type' => 'number',
                    'min' => 0,
                    'placeholder' => 'Enter owner ID'
                ])->label('<i class="bi bi-hash"></i> Owner ID', ['class' => 'form-label']) ?>
            </div>
        </div>
    </div>

    <!-- File Upload -->
    <div class="upload-section">
        <div class="section-header">
            <i class="bi bi-cloud-upload-fill"></i>
            Upload File
        </div>

        <div class="file-upload-zone" id="fileUploadZone">
            <div class="file-upload-icon">
                <i class="bi bi-cloud-arrow-up"></i>
            </div>
            <div class="file-upload-text">
                Drag & Drop your file here
            </div>
            <div class="file-upload-hint">
                or click to browse • Supports PDF, JPG, PNG, JPEG • Max 5MB
            </div>
            <?= $form->field($model, 'file')->fileInput([
                'id' => 'fileInput',
                'accept' => '.pdf,.jpg,.jpeg,.png'
            ])->label(false) ?>
        </div>

        <div class="file-preview" id="filePreview">
            <div class="file-preview-content">
                <div class="file-icon">
                    <i class="bi bi-file-earmark-pdf" id="fileIcon"></i>
                </div>
                <div class="file-details">
                    <div class="file-name" id="fileName">document.pdf</div>
                    <div class="file-size" id="fileSize">0 KB</div>
                </div>
                <button type="button" class="remove-file" id="removeFile">
                    <i class="bi bi-x-lg"></i> Remove
                </button>
            </div>
        </div>

        <?php if (!$model->isNewRecord && $model->file_url): ?>
            <div class="current-file-info">
                <strong><i class="bi bi-paperclip"></i> Current File:</strong>
                <?= Html::a(
                    $model->original_filename ?: basename($model->file_url), 
                    Yii::getAlias('@web/' . $model->file_url), 
                    ['target' => '_blank', 'class' => 'text-primary fw-bold']
                ) ?>
                <?php if ($model->file_size): ?>
                    <span class="text-muted ms-2">
                        (<?= Yii::$app->formatter->asShortSize($model->file_size) ?>)
                    </span>
                <?php endif; ?>
                <div class="mt-2 text-muted">
                    <small>Upload a new file to replace the current one</small>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Status & Additional Info -->
    <div class="upload-section">
        <div class="section-header">
            <i class="bi bi-check2-square"></i>
            Document Status & Details
        </div>

        <div class="mb-4">
            <label class="form-label">
                <i class="bi bi-flag"></i>
                Document Status
            </label>
            <div class="status-selector">
                <?php foreach (UserDocuments::optsStatus() as $value => $label): ?>
                    <div class="status-option">
                        <?= Html::activeRadio($model, 'status', [
                            'value' => $value,
                            'uncheck' => null,
                            'id' => 'status-' . strtolower(str_replace(' ', '-', $value))
                        ]) ?>
                        <label for="status-<?= strtolower(str_replace(' ', '-', $value)) ?>">
                            <i class="bi bi-<?= $value === UserDocuments::STATUS_APPROVED ? 'check-circle-fill' : ($value === UserDocuments::STATUS_REJECTED ? 'x-circle-fill' : 'clock-fill') ?>"></i>
                            <span><?= $label ?></span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <?= $form->field($model, 'expiry_date')->textInput([
                    'type' => 'date',
                    'placeholder' => 'Select expiry date'
                ])->label('<i class="bi bi-calendar-x"></i> Expiry Date (Optional)', ['class' => 'form-label']) ?>
                <div class="input-helper">
                    <i class="bi bi-lightbulb"></i>
                    <span>Set an expiry date if the document is time-sensitive</span>
                </div>
            </div>

            <div class="col-md-6 mb-0">
                <?= $form->field($model, 'uploaded_by')->textInput([
                    'type' => 'number',
                    'placeholder' => 'Auto-filled on save',
                    'readonly' => true
                ])->label('<i class="bi bi-person-up"></i> Uploaded By', ['class' => 'form-label']) ?>
            </div>
        </div>

        <div class="mb-4">
            <?= $form->field($model, 'admin_notes')->textarea([
                'rows' => 3,
                'placeholder' => 'Add any administrative notes about this document...',
                'readonly' => true
            ])->label('<i class="bi bi-sticky"></i> Admin Notes (Read-only)', ['class' => 'form-label']) ?>
        </div>

        <?php if (!$model->isNewRecord && $model->status === UserDocuments::STATUS_REJECTED): ?>
            <div class="mb-0">
                <?= $form->field($model, 'rejection_reason')->textarea([
                    'rows' => 3,
                    'placeholder' => 'Rejection reason from admin...',
                    'readonly' => true
                ])->label('<i class="bi bi-exclamation-triangle"></i> Rejection Reason (Read-only)', ['class' => 'form-label']) ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>