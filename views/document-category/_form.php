<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\DocumentCategory;

/** @var yii\web\View $this */
/** @var app\models\DocumentCategory $model */
?>

<style>
.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px 20px 0 0;
    padding: 2.5rem;
    color: white;
    margin-bottom: 0;
}

.form-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.form-subtitle {
    opacity: 0.9;
    font-size: 0.95rem;
    margin: 0;
}

.form-container {
    padding: 2.5rem;
}

.form-section {
    background: #f9fafb;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.form-section:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
}

.section-title {
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

.section-title i {
    color: #667eea;
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
    color: #667eea;
}

.form-control, .form-select {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 0.875rem 1.25rem;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    outline: none;
}

.form-control::placeholder {
    color: #9ca3af;
    font-style: italic;
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
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
    color: #667eea;
}

.checkbox-container {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.checkbox-container:hover {
    border-color: #667eea;
    background: #f9fafb;
}

.checkbox-container input[type="checkbox"] {
    width: 24px;
    height: 24px;
    cursor: pointer;
    accent-color: #667eea;
}

.checkbox-label {
    font-weight: 600;
    color: #111827;
    margin: 0;
    cursor: pointer;
    flex-grow: 1;
}

.checkbox-description {
    color: #6b7280;
    font-size: 0.875rem;
    margin-top: 0.25rem;
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

.role-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.role-option {
    position: relative;
}

.role-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.role-option label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 1rem;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    position: relative;
}

.role-option label i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: #6b7280;
    transition: all 0.3s ease;
}

.role-option label span {
    font-weight: 600;
    color: #374151;
    font-size: 0.9375rem;
}

/* SELECTED STATE FOR ROLE */
.role-option.selected label {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border-color: #667eea !important;
    color: white !important;
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
}

.role-option.selected label i,
.role-option.selected label span {
    color: white !important;
}

/* Check indicator badge */
.check-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: white;
    color: #667eea;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    z-index: 10;
}

.status-toggle {
    display: flex;
    gap: 1rem;
}

.status-option {
    flex: 1;
    position: relative;
}

.status-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.status-option label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    position: relative;
    min-height: 56px;
}

/* SELECTED STATE FOR STATUS - ACTIVE */
.status-option.selected label {
    border-color: #10b981 !important;
    background: #d1fae5 !important;
    color: #065f46 !important;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

/* SELECTED STATE FOR STATUS - INACTIVE */
.status-option.inactive.selected label {
    border-color: #6b7280 !important;
    background: #f3f4f6 !important;
    color: #374151 !important;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
}

/* Status check badge */
.status-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #10b981;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    z-index: 10;
}

.status-option.inactive .status-badge {
    background: #6b7280;
}

.invalid-feedback {
    display: block;
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 600;
}

.has-error .form-control,
.has-error .form-select {
    border-color: #ef4444;
}

.has-error .form-label {
    color: #ef4444;
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

.form-section {
    animation: slideInUp 0.5s ease;
}

.form-section:nth-child(1) { animation-delay: 0.1s; }
.form-section:nth-child(2) { animation-delay: 0.2s; }
.form-section:nth-child(3) { animation-delay: 0.3s; }

@media (max-width: 768px) {
    .button-group {
        grid-template-columns: 1fr;
    }
    
    .role-selector {
        grid-template-columns: 1fr;
    }
    
    .form-container {
        padding: 1.5rem;
    }
}
</style>

<div class="card shadow-lg border-0">
    <div class="form-header">
        <div class="form-title">
            <i class="bi bi-<?= $model->isNewRecord ? 'plus-circle-fill' : 'pencil-square' ?>"></i>
            <?= Html::encode($this->title) ?>
        </div>
        <p class="form-subtitle">
            <?= $model->isNewRecord 
                ? 'Create a new document category for your institution' 
                : 'Update the information for this document category' ?>
        </p>
    </div>

    <div class="form-container">
        <?php $form = ActiveForm::begin([
            'id' => 'document-category-form',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnSubmit' => true,
            'options' => ['class' => 'document-category-form'],
        ]); ?>

        <!-- Basic Information Section -->
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-info-circle-fill"></i>
                Basic Information
            </div>

            <div class="mb-4">
                <?= $form->field($model, 'category_name')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'e.g., Birth Certificate, Teaching License, Medical Records',
                    'class' => 'form-control',
                ])->label('<i class="bi bi-tag"></i> Category Name', ['class' => 'form-label']) ?>
                <div class="input-helper">
                    <i class="bi bi-lightbulb"></i>
                    <span>Choose a clear, descriptive name that users will easily understand</span>
                </div>
            </div>

            <div class="mb-4">
                <?= $form->field($model, 'description')->textarea([
                    'rows' => 4,
                    'placeholder' => 'Provide a detailed description of what this document is for and why it\'s needed...',
                    'class' => 'form-control',
                ])->label('<i class="bi bi-card-text"></i> Description', ['class' => 'form-label']) ?>
                <div class="input-helper">
                    <i class="bi bi-lightbulb"></i>
                    <span>Help users understand the purpose and requirements of this document</span>
                </div>
            </div>
        </div>

        <!-- Role & Requirements Section -->
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-people-fill"></i>
                Role & Requirements
            </div>

            <div class="mb-4">
                <label class="form-label">
                    <i class="bi bi-person-badge"></i>
                    Required For Role
                </label>
                <div class="role-selector">
                    <div class="role-option" id="role-option-teacher">
                        <?= Html::activeRadio($model, 'required_for_role', [
                            'value' => DocumentCategory::ROLE_TEACHER,
                            'uncheck' => null,
                            'id' => 'role-teacher'
                        ]) ?>
                        <label for="role-teacher">
                            <i class="bi bi-person-workspace"></i>
                            <span>Teacher Only</span>
                        </label>
                    </div>
                    <div class="role-option" id="role-option-parent">
                        <?= Html::activeRadio($model, 'required_for_role', [
                            'value' => DocumentCategory::ROLE_PARENT,
                            'uncheck' => null,
                            'id' => 'role-parent'
                        ]) ?>
                        <label for="role-parent">
                            <i class="bi bi-people"></i>
                            <span>Parent Only</span>
                        </label>
                    </div>
                    <div class="role-option" id="role-option-both">
                        <?= Html::activeRadio($model, 'required_for_role', [
                            'value' => DocumentCategory::ROLE_BOTH,
                            'uncheck' => null,
                            'id' => 'role-both'
                        ]) ?>
                        <label for="role-both">
                            <i class="bi bi-people-fill"></i>
                            <span>Both Roles</span>
                        </label>
                    </div>
                </div>
                <?= Html::error($model, 'required_for_role', ['class' => 'invalid-feedback']) ?>
            </div>

            <div class="mb-4">
                <label class="form-label mb-3">
                    <i class="bi bi-exclamation-circle"></i>
                    Document Requirement
                </label>
                <div class="checkbox-container">
                    <?= Html::activeCheckbox($model, 'is_required', [
                        'label' => false,
                        'class' => 'form-check-input'
                    ]) ?>
                    <div>
                        <div class="checkbox-label">Mark this document as mandatory</div>
                        <div class="checkbox-description">
                            Users will be required to upload this document to complete their profile
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Section -->
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-toggle-on"></i>
                Status
            </div>

            <div class="mb-0">
                <label class="form-label mb-3">
                    <i class="bi bi-power"></i>
                    Category Status
                </label>
                <div class="status-toggle">
                    <div class="status-option" id="status-option-active">
                        <?= Html::activeRadio($model, 'status', [
                            'value' => DocumentCategory::STATUS_ACTIVE,
                            'uncheck' => null,
                            'id' => 'status-active'
                        ]) ?>
                        <label for="status-active">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Active</span>
                        </label>
                    </div>
                    <div class="status-option inactive" id="status-option-inactive">
                        <?= Html::activeRadio($model, 'status', [
                            'value' => DocumentCategory::STATUS_INACTIVE,
                            'uncheck' => null,
                            'id' => 'status-inactive'
                        ]) ?>
                        <label for="status-inactive">
                            <i class="bi bi-x-circle-fill"></i>
                            <span>Inactive</span>
                        </label>
                    </div>
                </div>
                <div class="input-helper mt-3">
                    <i class="bi bi-info-circle"></i>
                    <span>Inactive categories won't be visible to users for document upload</span>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="button-group">
            <?= Html::a('<i class="bi bi-x-circle"></i> Cancel', ['index'], [
                'class' => 'btn btn-form btn-cancel'
            ]) ?>
            <?= Html::submitButton(
                $model->isNewRecord 
                    ? '<i class="bi bi-check-circle"></i> Create Category' 
                    : '<i class="bi bi-save"></i> Save Changes',
                ['class' => 'btn btn-form btn-save']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
// Pure JavaScript - no jQuery dependency
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing indicators...');
    
    // Function to update role indicators
    function updateRoleIndicators() {
        console.log('Updating role indicators...');
        
        // Remove all selected classes and badges
        document.querySelectorAll('.role-option').forEach(function(option) {
            option.classList.remove('selected');
            var existingBadge = option.querySelector('.check-badge');
            if (existingBadge) {
                existingBadge.remove();
            }
        });
        
        // Add selected class and badge to checked option
        document.querySelectorAll('.role-option input[type="radio"]:checked').forEach(function(input) {
            console.log('Found checked role radio:', input.id);
            var parentOption = input.closest('.role-option');
            var label = parentOption.querySelector('label');
            
            parentOption.classList.add('selected');
            console.log('Added selected class to', parentOption.id);
            
            // Create and add badge
            var badge = document.createElement('div');
            badge.className = 'check-badge';
            badge.innerHTML = '✓';
            label.appendChild(badge);
            console.log('Added badge to role option');
        });
    }
    
    // Function to update status indicators
    function updateStatusIndicators() {
        console.log('Updating status indicators...');
        
        // Remove all selected classes and badges
        document.querySelectorAll('.status-option').forEach(function(option) {
            option.classList.remove('selected');
            var existingBadge = option.querySelector('.status-badge');
            if (existingBadge) {
                existingBadge.remove();
            }
        });
        
        // Add selected class and badge to checked option
        document.querySelectorAll('.status-option input[type="radio"]:checked').forEach(function(input) {
            console.log('Found checked status radio:', input.id);
            var parentOption = input.closest('.status-option');
            var label = parentOption.querySelector('label');
            
            parentOption.classList.add('selected');
            console.log('Added selected class to', parentOption.id);
            
            // Create and add badge
            var badge = document.createElement('div');
            badge.className = 'status-badge';
            badge.innerHTML = '✓';
            label.appendChild(badge);
            console.log('Added badge to status option');
        });
    }
    
    // Initialize on page load
    setTimeout(function() {
        updateRoleIndicators();
        updateStatusIndicators();
    }, 100);
    
    // Add event listeners to role options
    document.querySelectorAll('.role-option input[type="radio"]').forEach(function(input) {
        input.addEventListener('change', function() {
            console.log('Role radio changed:', this.id);
            updateRoleIndicators();
        });
    });
    
    // Add event listeners to status options
    document.querySelectorAll('.status-option input[type="radio"]').forEach(function(input) {
        input.addEventListener('change', function() {
            console.log('Status radio changed:', this.id);
            updateStatusIndicators();
        });
    });
    
    console.log('Selection indicators initialized successfully!');
});
</script>

<?php
$script = <<< JS
// Form validation feedback
$('#document-category-form').on('beforeValidate', function() {
    $('.has-error').removeClass('has-error');
});

$('#document-category-form').on('afterValidate', function(event, messages, errorAttributes) {
    $.each(errorAttributes, function(index, attribute) {
        $('#' + attribute.id).closest('.mb-4').addClass('has-error');
    });
});

// Character counter for description
$('#documentcategory-description').on('input', function() {
    var length = $(this).val().length;
    var maxLength = 500;
    var remaining = maxLength - length;
    
    if (!$('.char-counter').length) {
        $(this).after('<div class="input-helper char-counter"><i class="bi bi-fonts"></i><span></span></div>');
    }
    
    $('.char-counter span').text(remaining + ' characters remaining');
    
    if (remaining < 50) {
        $('.char-counter').css('color', '#ef4444');
    } else {
        $('.char-counter').css('color', '#6b7280');
    }
});

// Auto-save draft (localStorage)
const formData = {};
$('#document-category-form input, #document-category-form textarea, #document-category-form select').on('change', function() {
    const fieldName = $(this).attr('name');
    const fieldValue = $(this).val();
    formData[fieldName] = fieldValue;
    localStorage.setItem('categoryFormDraft', JSON.stringify(formData));
});

// Smooth scroll to first error
$('#document-category-form').on('afterValidate', function(event, messages, errorAttributes) {
    if (errorAttributes.length > 0) {
        $('html, body').animate({
            scrollTop: $('#' + errorAttributes[0].id).offset().top - 100
        }, 300);
    }
});
JS;
$this->registerJs($script);
?>