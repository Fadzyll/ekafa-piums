<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\DocumentCategory;

/** @var yii\web\View $this */
/** @var app\models\DocumentCategorySearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<style>
.search-form-modern .form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.search-form-modern .form-group {
    margin-bottom: 0;
}

.search-form-modern label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.search-form-modern label i {
    margin-right: 0.5rem;
    color: #667eea;
}

.search-form-modern .form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.6rem 1rem;
    transition: all 0.3s ease;
}

.search-form-modern .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

.search-buttons {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
    margin-top: 1rem;
}

.btn-search-modern {
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-search-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-search-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-search-secondary {
    background: #e9ecef;
    color: #495057;
}

.btn-search-secondary:hover {
    background: #dee2e6;
    transform: translateY(-2px);
}

.advanced-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    margin-top: 1rem;
}

.advanced-section h6 {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.quick-filters {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.quick-filter-btn {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    border: 2px solid #e5e7eb;
    background: white;
    color: #6b7280;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quick-filter-btn:hover {
    border-color: #667eea;
    color: #667eea;
}

.quick-filter-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}
</style>

<div class="document-category-search search-form-modern">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'id' => 'category-search-form'
        ],
    ]); ?>

    <!-- Quick Filters -->
    <div class="quick-filters">
        <span style="font-weight: 600; color: #495057; display: flex; align-items: center; margin-right: 0.5rem;">
            <i class="bi bi-lightning-fill" style="color: #667eea; margin-right: 0.25rem;"></i> Quick Filters:
        </span>
        <button type="button" class="quick-filter-btn" data-filter="all">All</button>
        <button type="button" class="quick-filter-btn" data-filter="active">Active Only</button>
        <button type="button" class="quick-filter-btn" data-filter="inactive">Inactive Only</button>
        <button type="button" class="quick-filter-btn" data-filter="required">Required</button>
        <button type="button" class="quick-filter-btn" data-filter="optional">Optional</button>
        <button type="button" class="quick-filter-btn" data-filter="teacher">For Teachers</button>
        <button type="button" class="quick-filter-btn" data-filter="parent">For Parents</button>
        <button type="button" class="quick-filter-btn" data-filter="both">For Both</button>
    </div>

    <div class="form-row">
        <div class="form-group">
            <?= $form->field($model, 'category_name')->textInput([
                'placeholder' => 'Search by category name...',
                'id' => 'categorysearch-category_name'
            ])->label('<i class="bi bi-folder"></i> Category Name') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'description')->textInput([
                'placeholder' => 'Search in description...',
                'id' => 'categorysearch-description'
            ])->label('<i class="bi bi-text-left"></i> Description') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'required_for_role')->dropDownList([
                '' => 'All Roles',
                DocumentCategory::ROLE_TEACHER => 'Teacher',
                DocumentCategory::ROLE_PARENT => 'Parent',
                DocumentCategory::ROLE_BOTH => 'Both',
            ], [
                'id' => 'categorysearch-required_for_role'
            ])->label('<i class="bi bi-people"></i> Required For Role') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'is_required')->dropDownList([
                '' => 'All',
                1 => 'Required (Mandatory)',
                0 => 'Optional',
            ], [
                'id' => 'categorysearch-is_required'
            ])->label('<i class="bi bi-exclamation-circle"></i> Is Required') ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <?= $form->field($model, 'status')->dropDownList([
                '' => 'All Status',
                DocumentCategory::STATUS_ACTIVE => 'Active',
                DocumentCategory::STATUS_INACTIVE => 'Inactive',
            ], [
                'id' => 'categorysearch-status'
            ])->label('<i class="bi bi-toggle-on"></i> Status') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'created_at')->textInput([
                'type' => 'date',
                'id' => 'categorysearch-created_at'
            ])->label('<i class="bi bi-calendar-plus"></i> Created Date') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'updated_at')->textInput([
                'type' => 'date',
                'id' => 'categorysearch-updated_at'
            ])->label('<i class="bi bi-calendar-event"></i> Updated Date') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'category_id')->textInput([
                'placeholder' => 'Enter category ID...',
                'type' => 'number',
                'id' => 'categorysearch-category_id'
            ])->label('<i class="bi bi-hash"></i> Category ID') ?>
        </div>
    </div>

    <div class="search-buttons">
        <?= Html::submitButton('<i class="bi bi-search"></i> Search', [
            'class' => 'btn-search-modern btn-search-primary',
            'id' => 'search-submit-btn'
        ]) ?>
        <?= Html::a('<i class="bi bi-arrow-clockwise"></i> Reset', ['index'], [
            'class' => 'btn-search-modern btn-search-secondary'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<< JS
// Quick filter functionality
$('.quick-filter-btn').on('click', function() {
    $('.quick-filter-btn').removeClass('active');
    $(this).addClass('active');
    
    var filter = $(this).data('filter');
    
    // Reset all fields
    $('#categorysearch-status').val('');
    $('#categorysearch-is_required').val('');
    $('#categorysearch-required_for_role').val('');
    
    // Apply quick filter
    switch(filter) {
        case 'all':
            // Do nothing - all fields are reset
            break;
        case 'active':
            $('#categorysearch-status').val('<?= DocumentCategory::STATUS_ACTIVE ?>');
            break;
        case 'inactive':
            $('#categorysearch-status').val('<?= DocumentCategory::STATUS_INACTIVE ?>');
            break;
        case 'required':
            $('#categorysearch-is_required').val('1');
            break;
        case 'optional':
            $('#categorysearch-is_required').val('0');
            break;
        case 'teacher':
            $('#categorysearch-required_for_role').val('<?= DocumentCategory::ROLE_TEACHER ?>');
            break;
        case 'parent':
            $('#categorysearch-required_for_role').val('<?= DocumentCategory::ROLE_PARENT ?>');
            break;
        case 'both':
            $('#categorysearch-required_for_role').val('<?= DocumentCategory::ROLE_BOTH ?>');
            break;
    }
    
    // Auto-submit the form
    $('#category-search-form').submit();
});

// Set active quick filter based on current values
function setActiveQuickFilter() {
    var status = $('#categorysearch-status').val();
    var isRequired = $('#categorysearch-is_required').val();
    var role = $('#categorysearch-required_for_role').val();
    
    $('.quick-filter-btn').removeClass('active');
    
    if (!status && !isRequired && !role) {
        $('.quick-filter-btn[data-filter="all"]').addClass('active');
    } else if (status === '<?= DocumentCategory::STATUS_ACTIVE ?>' && !isRequired && !role) {
        $('.quick-filter-btn[data-filter="active"]').addClass('active');
    } else if (status === '<?= DocumentCategory::STATUS_INACTIVE ?>' && !isRequired && !role) {
        $('.quick-filter-btn[data-filter="inactive"]').addClass('active');
    } else if (isRequired === '1' && !status && !role) {
        $('.quick-filter-btn[data-filter="required"]').addClass('active');
    } else if (isRequired === '0' && !status && !role) {
        $('.quick-filter-btn[data-filter="optional"]').addClass('active');
    } else if (role === '<?= DocumentCategory::ROLE_TEACHER ?>' && !status && !isRequired) {
        $('.quick-filter-btn[data-filter="teacher"]').addClass('active');
    } else if (role === '<?= DocumentCategory::ROLE_PARENT ?>' && !status && !isRequired) {
        $('.quick-filter-btn[data-filter="parent"]').addClass('active');
    } else if (role === '<?= DocumentCategory::ROLE_BOTH ?>' && !status && !isRequired) {
        $('.quick-filter-btn[data-filter="both"]').addClass('active');
    }
}

// Set active filter on page load
$(document).ready(function() {
    setActiveQuickFilter();
});

// Auto-submit on field change (optional - comment out if not needed)
/*
$('#category-search-form select, #category-search-form input[type="date"]').on('change', function() {
    $('#category-search-form').submit();
});
*/
JS;
$this->registerJs($script);
?>