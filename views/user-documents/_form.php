<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserDocuments $model */
/** @var yii\widgets\ActiveForm $form */

$isAdmin = Yii::$app->user->identity->role === 'Admin';
?>

<style>
.upload-form-header {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 20px 20px 0 0;
    padding: 2.5rem;
    color: white;
    margin-bottom: 0;
}

.upload-form-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.upload-form-subtitle {
    opacity: 0.9;
    font-size: 0.95rem;
    margin: 0;
}

.upload-form-body {
    padding: 2.5rem;
}

.upload-section {
    background: #f9fafb;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.upload-section:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
}

.section-header {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e5e7eb;
}

.section-header i {
    color: #3b82f6;
    font-size: 1.25rem;
}

.form-label {
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.9375rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-label i {
    color: #3b82f6;
}

.form-control, .form-select {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 0.875rem 1.25rem;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    outline: none;
}

.file-upload-zone {
    border: 3px dashed #d1d5db;
    border-radius: 16px;
    padding: 3rem 2rem;
    text-align: center;
    background: white;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.file-upload-zone:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.file-upload-zone.dragover {
    border-color: #10b981;
    background: #d1fae5;
}

.file-upload-icon {
    font-size: 4rem;
    color: #9ca3af;
    margin-bottom: 1rem;
}

.file-upload-zone:hover .file-upload-icon {
    color: #3b82f6;
    transform: scale(1.1);
}

.file-upload-text {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.file-upload-hint {
    color: #6b7280;
    font-size: 0.875rem;
}

.file-upload-zone input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.file-preview {
    display: none;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.file-preview.active {
    display: block;
    animation: slideInUp 0.3s ease;
}

.file-preview-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.file-icon {
    font-size: 3rem;
    color: #3b82f6;
}

.file-details {
    flex-grow: 1;
}

.file-name {
    font-weight: 700;
    color: #111827;
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.file-size {
    color: #6b7280;
    font-size: 0.875rem;
}

.remove-file {
    background: #fee2e2;
    color: #dc2626;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.remove-file:hover {
    background: #dc2626;
    color: white;
}

.current-file-info {
    background: #dbeafe;
    border: 2px solid #3b82f6;
    border-radius: 12px;
    padding: 1.25rem;
    margin-top: 1rem;
}

.current-file-info strong {
    color: #1e40af;
}

.status-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.status-option {
    position: relative;
}

.status-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.status-option label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.25rem;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
}

.status-option label i {
    font-size: 1.5rem;
    color: #6b7280;
}

.status-option input:checked + label {
    border-color: #10b981;
    background: #d1fae5;
    color: #065f46;
}

.status-option input:checked + label i {
    color: inherit;
}

.button-group {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 2rem;
}

.btn-form {
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
}

.btn-cancel {
    background: #e5e7eb;
    color: #374151;
}

.btn-cancel:hover {
    background: #d1d5db;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn-save {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
}

.input-helper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    color: #6b7280;
    font-size: 0.8125rem;
}

.input-helper i {
    color: #3b82f6;
}

@keyframes slideInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.upload-section {
    animation: slideInUp 0.5s ease;
}

.upload-section:nth-child(1) { animation-delay: 0.1s; }
.upload-section:nth-child(2) { animation-delay: 0.2s; }
.upload-section:nth-child(3) { animation-delay: 0.3s; }

@media (max-width: 768px) {
    .button-group {
        grid-template-columns: 1fr;
    }
    
    .status-selector {
        grid-template-columns: 1fr;
    }
    
    .upload-form-body {
        padding: 1.5rem;
    }
}
</style>

<div class="card shadow-lg border-0">
    <div class="upload-form-header">
        <div class="upload-form-title">
            <i class="bi bi-<?= $isAdmin ? 'shield-check' : ($model->isNewRecord ? 'cloud-upload-fill' : 'pencil-square') ?>"></i>
            <?= Html::encode($this->title ?? ($isAdmin ? 'Review Document' : 'Manage User Document')) ?>
        </div>
        <p class="upload-form-subtitle">
            <?php if ($isAdmin): ?>
                Review document and update status as needed
            <?php else: ?>
                <?= $model->isNewRecord 
                    ? 'Upload a new document to the system' 
                    : 'Update document information and file' ?>
            <?php endif; ?>
        </p>
    </div>

    <div class="upload-form-body">
        <?php $form = ActiveForm::begin([
            'id' => 'user-documents-form',
            'options' => ['enctype' => 'multipart/form-data'],
            'enableClientValidation' => true,
        ]); ?>

        <?= $this->render('_form_fields', [
            'model' => $model,
            'form' => $form,
        ]) ?>

        <!-- Form Actions -->
        <div class="button-group">
            <?= Html::a('<i class="bi bi-x-circle"></i> Cancel', ['index'], [
                'class' => 'btn btn-form btn-cancel'
            ]) ?>
            <?= Html::submitButton(
                $isAdmin 
                    ? '<i class="bi bi-check-circle"></i> Update Status'
                    : ($model->isNewRecord 
                        ? '<i class="bi bi-cloud-upload"></i> Upload Document' 
                        : '<i class="bi bi-save"></i> Save Changes'),
                ['class' => 'btn btn-form btn-save']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php if (!$isAdmin): ?>
<?php
$script = <<< JS
// File upload handling
const fileInput = document.getElementById('fileInput');
const fileUploadZone = document.getElementById('fileUploadZone');
const filePreview = document.getElementById('filePreview');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');
const fileIcon = document.getElementById('fileIcon');
const removeFile = document.getElementById('removeFile');

function handleFile(file) {
    if (file) {
        // Show preview
        filePreview.classList.add('active');
        fileName.textContent = file.name;
        
        // Calculate file size
        const sizeInKB = (file.size / 1024).toFixed(2);
        const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
        fileSize.textContent = sizeInMB > 1 ? sizeInMB + ' MB' : sizeInKB + ' KB';
        
        // Set icon based on file type
        const ext = file.name.split('.').pop().toLowerCase();
        if (ext === 'pdf') {
            fileIcon.className = 'bi bi-file-earmark-pdf';
        } else if (['jpg', 'jpeg', 'png'].includes(ext)) {
            fileIcon.className = 'bi bi-file-earmark-image';
        } else {
            fileIcon.className = 'bi bi-file-earmark';
        }
    }
}

fileInput.addEventListener('change', function(e) {
    handleFile(e.target.files[0]);
});

// Drag and drop
fileUploadZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    fileUploadZone.classList.add('dragover');
});

fileUploadZone.addEventListener('dragleave', function(e) {
    fileUploadZone.classList.remove('dragover');
});

fileUploadZone.addEventListener('drop', function(e) {
    e.preventDefault();
    fileUploadZone.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(files[0]);
        fileInput.files = dataTransfer.files;
        handleFile(files[0]);
    }
});

// Remove file
removeFile.addEventListener('click', function() {
    fileInput.value = '';
    filePreview.classList.remove('active');
});

// Form validation
$('#user-documents-form').on('beforeSubmit', function(e) {
    var form = $(this);
    var hasFile = $('#fileInput')[0].files.length > 0;
    var isUpdate = <?= $model->isNewRecord ? 'false' : 'true' ?>;
    
    if (!isUpdate && !hasFile) {
        alert('Please select a file to upload');
        return false;
    }
    
    return true;
});
JS;
$this->registerJs($script);
?>
<?php endif; ?>