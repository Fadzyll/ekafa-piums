<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\UserDocuments;
use app\models\DocumentCategory;
use app\models\Users;

/** @var yii\web\View $this */
/** @var app\models\UserDocumentsSearch $model */
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
    color: #3b82f6;
}

.search-form-modern .form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.6rem 1rem;
    transition: all 0.3s ease;
}

.search-form-modern .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
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
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.btn-search-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
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
    color: #3b82f6;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
</style>

<div class="user-documents-search search-form-modern">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'id' => 'document-search-form'
        ],
    ]); ?>

    <div class="form-row">
        <div class="form-group">
            <?= $form->field($model, 'document_name')->textInput([
                'placeholder' => 'Search by document name...',
                'id' => 'documentsearch-document_name'
            ])->label('<i class="bi bi-file-earmark-text"></i> Document Name') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'document_type')->textInput([
                'placeholder' => 'Search by document type...',
                'id' => 'documentsearch-document_type'
            ])->label('<i class="bi bi-tags"></i> Document Type') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'status')->dropDownList(
                ['' => 'All Statuses'] + UserDocuments::optsStatus(),
                ['id' => 'documentsearch-status']
            )->label('<i class="bi bi-check-circle"></i> Status') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'category_id')->dropDownList(
                ['' => 'All Categories'] + \yii\helpers\ArrayHelper::map(
                    DocumentCategory::find()->where(['status' => DocumentCategory::STATUS_ACTIVE])->all(),
                    'category_id',
                    'category_name'
                ),
                ['id' => 'documentsearch-category_id']
            )->label('<i class="bi bi-folder"></i> Category') ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <?= $form->field($model, 'user_id')->textInput([
                'placeholder' => 'Enter user ID...',
                'type' => 'number',
                'id' => 'documentsearch-user_id'
            ])->label('<i class="bi bi-person"></i> User ID') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'owner_type')->dropDownList(
                ['' => 'All Owner Types'] + UserDocuments::optsOwnerType(),
                ['id' => 'documentsearch-owner_type']
            )->label('<i class="bi bi-person-badge"></i> Owner Type') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'upload_date')->textInput([
                'type' => 'date',
                'id' => 'documentsearch-upload_date'
            ])->label('<i class="bi bi-calendar-upload"></i> Upload Date') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'expiry_date')->textInput([
                'type' => 'date',
                'id' => 'documentsearch-expiry_date'
            ])->label('<i class="bi bi-calendar-x"></i> Expiry Date') ?>
        </div>
    </div>

    <!-- Advanced Search Section -->
    <div class="advanced-section" style="display: none;" id="advancedSearch">
        <h6><i class="bi bi-funnel-fill"></i> Advanced Filters</h6>
        
        <div class="form-row">
            <div class="form-group">
                <?= $form->field($model, 'uploaded_by')->textInput([
                    'placeholder' => 'Uploaded by user ID...',
                    'type' => 'number',
                    'id' => 'documentsearch-uploaded_by'
                ])->label('<i class="bi bi-cloud-upload"></i> Uploaded By') ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'verified_by')->textInput([
                    'placeholder' => 'Verified by admin ID...',
                    'type' => 'number',
                    'id' => 'documentsearch-verified_by'
                ])->label('<i class="bi bi-check2-square"></i> Verified By') ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'version')->textInput([
                    'placeholder' => 'Version number...',
                    'type' => 'number',
                    'id' => 'documentsearch-version'
                ])->label('<i class="bi bi-clock-history"></i> Version') ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'is_latest_version')->dropDownList(
                    ['' => 'All Versions', 1 => 'Latest Only', 0 => 'Old Versions'],
                    ['id' => 'documentsearch-is_latest_version']
                )->label('<i class="bi bi-bookmark-star"></i> Latest Version') ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $form->field($model, 'verified_at')->textInput([
                    'type' => 'date',
                    'id' => 'documentsearch-verified_at'
                ])->label('<i class="bi bi-calendar-check"></i> Verified Date') ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'original_filename')->textInput([
                    'placeholder' => 'Search by original filename...',
                    'id' => 'documentsearch-original_filename'
                ])->label('<i class="bi bi-file-earmark"></i> Original Filename') ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'mime_type')->textInput([
                    'placeholder' => 'e.g., application/pdf',
                    'id' => 'documentsearch-mime_type'
                ])->label('<i class="bi bi-file-binary"></i> MIME Type') ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'owner_id')->textInput([
                    'placeholder' => 'Enter owner ID...',
                    'type' => 'number',
                    'id' => 'documentsearch-owner_id'
                ])->label('<i class="bi bi-hash"></i> Owner ID') ?>
            </div>
        </div>
    </div>

    <div class="search-buttons">
        <button type="button" 
                class="btn-search-modern btn-search-secondary" 
                id="toggleAdvanced">
            <i class="bi bi-sliders"></i> Advanced Search
        </button>
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
// Toggle advanced search section
$('#toggleAdvanced').on('click', function() {
    $('#advancedSearch').slideToggle(300);
    $(this).find('i').toggleClass('bi-sliders bi-x-circle');
    var text = $(this).find('i').hasClass('bi-x-circle') ? ' Hide Advanced' : ' Advanced Search';
    $(this).html('<i class="bi bi-' + ($(this).find('i').hasClass('bi-x-circle') ? 'x-circle' : 'sliders') + '"></i>' + text);
});

// Auto-submit on field change (optional - comment out if not needed)
/*
$('#document-search-form select, #document-search-form input[type="date"]').on('change', function() {
    $('#document-search-form').submit();
});
*/
JS;
$this->registerJs($script);
?>