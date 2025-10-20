<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PartnerDetails $model */

$this->title = 'Partner Information';
$this->params['breadcrumbs'][] = ['label' => 'My Profile', 'url' => ['user-details/view']];
$this->params['breadcrumbs'][] = $this->title;

// Fallback image
$defaultImage = Yii::getAlias('@web/images/default_profile.png');
$relativePath = ltrim($model->profile_picture_url ?? '', '/');
$uploadedPath = Yii::getAlias('@webroot/' . $relativePath);
$imageUrl = ($model->profile_picture_url && file_exists($uploadedPath))
    ? Yii::getAlias('@web/' . $relativePath)
    : $defaultImage;
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
                    <p class="edit-subtitle">Update your partner's personal information</p>
                </div>
            </div>
        </div>

        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'modern-form'],
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'form-label-modern'],
                'inputOptions' => ['class' => 'form-control-modern'],
                'errorOptions' => ['class' => 'error-message'],
            ],
        ]); ?>

        <div class="edit-grid">
            <!-- Left Column: Profile Picture -->
            <div class="profile-picture-section">
                <div class="picture-card">
                    <div class="picture-header">
                        <i class="bi bi-camera-fill"></i>
                        <h3>Partner's Photo</h3>
                    </div>
                    
                    <div class="picture-preview-wrapper">
                        <div class="picture-preview">
                            <img id="preview" src="<?= Html::encode($imageUrl) ?>" 
                                 alt="Partner Picture">
                            <div class="picture-overlay">
                                <i class="bi bi-upload"></i>
                                <span>Click to upload</span>
                            </div>
                        </div>
                    </div>

                    <div class="picture-upload">
                        <?= $form->field($model, 'imageFile', [
                            'template' => '{input}{error}',
                            'options' => ['class' => 'upload-field'],
                        ])->fileInput([
                            'accept' => 'image/*',
                            'onchange' => 'loadPreview(event)',
                            'class' => 'file-input-hidden',
                            'id' => 'imageFile'
                        ])->label(false) ?>
                        
                        <label for="imageFile" class="upload-button">
                            <i class="bi bi-cloud-upload"></i>
                            Choose Photo
                        </label>
                        
                        <p class="upload-hint">
                            <i class="bi bi-info-circle"></i>
                            JPG, PNG • Max 2MB • 100x100 to 1000x1000px
                        </p>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="info-notice">
                    <i class="bi bi-info-circle-fill"></i>
                    <div>
                        <strong>Partner Information</strong>
                        <p>This information is used for family records and emergency contact purposes.</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Form Fields -->
            <div class="form-fields-section">
                
                <!-- Personal Information Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-person-hearts"></i>
                        <h3>Partner's Personal Information</h3>
                        <span class="required-badge">
                            <i class="bi bi-asterisk"></i> Required
                        </span>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <?= $form->field($model, 'partner_name')->textInput([
                                'placeholder' => 'Enter partner\'s full name',
                                'maxlength' => true
                            ])->label('Full Name <span class="required-star">*</span>') ?>
                        </div>
                        <div class="form-col">
                            <?= $form->field($model, 'partner_ic_number')->textInput([
                                'placeholder' => 'e.g., 950815105234',
                                'maxlength' => true
                            ])->label('IC Number <span class="required-star">*</span>') ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <?= $form->field($model, 'partner_phone_number')->textInput([
                                'placeholder' => 'e.g., +60123456789',
                                'maxlength' => true
                            ])->label('Phone Number') ?>
                        </div>
                        <div class="form-col">
                            <?= $form->field($model, 'partner_citizenship')->textInput([
                                'placeholder' => 'e.g., Malaysian',
                                'maxlength' => true
                            ])->label('Citizenship') ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <?= $form->field($model, 'partner_marital_status')->dropDownList([
                                'Married' => 'Married',
                                'Divorced' => 'Divorced',
                                'Widowed' => 'Widowed'
                            ], [
                                'prompt' => 'Select Status',
                                'class' => 'form-control-modern select-modern'
                            ])->label('Marital Status') ?>
                        </div>
                    </div>
                </div>

                <!-- Address Information Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-geo-alt-fill"></i>
                        <h3>Partner's Address Information</h3>
                    </div>

                    <div class="form-row">
                        <div class="form-col full-width">
                            <?= $form->field($model, 'partner_address')->textarea([
                                'rows' => 3,
                                'placeholder' => 'Enter partner\'s complete address',
                                'class' => 'form-control-modern textarea-modern'
                            ])->label('Address') ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <?= $form->field($model, 'partner_city')->textInput([
                                'placeholder' => 'e.g., Kota Kinabalu',
                                'maxlength' => true
                            ])->label('City') ?>
                        </div>
                        <div class="form-col">
                            <?= $form->field($model, 'partner_postcode')->textInput([
                                'placeholder' => 'e.g., 88400',
                                'maxlength' => true
                            ])->label('Postcode') ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <?= $form->field($model, 'partner_state')->dropDownList([
                                'Johor' => 'Johor',
                                'Kedah' => 'Kedah',
                                'Kelantan' => 'Kelantan',
                                'Melaka' => 'Melaka',
                                'Negeri Sembilan' => 'Negeri Sembilan',
                                'Pahang' => 'Pahang',
                                'Penang' => 'Penang',
                                'Perak' => 'Perak',
                                'Perlis' => 'Perlis',
                                'Sabah' => 'Sabah',
                                'Sarawak' => 'Sarawak',
                                'Selangor' => 'Selangor',
                                'Terengganu' => 'Terengganu',
                                'W.P. Kuala Lumpur' => 'W.P. Kuala Lumpur',
                                'W.P. Labuan' => 'W.P. Labuan',
                                'W.P. Putrajaya' => 'W.P. Putrajaya',
                            ], [
                                'prompt' => 'Select State',
                                'class' => 'form-control-modern select-modern'
                            ])->label('State') ?>
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
                        <?= Html::submitButton('<i class="bi bi-check-circle"></i> Save Partner Info', [
                            'class' => 'btn-modern btn-primary-modern',
                            'id' => 'submit-btn'
                        ]) ?>
                    </div>
                </div>

            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
// JavaScript for image preview and form interactions
$this->registerJs(<<<JS
// Image preview with animation
function loadPreview(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    
    if (file) {
        // Validate file size
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            event.target.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            event.target.value = '';
            return;
        }
        
        // Fade out animation
        preview.style.opacity = '0.5';
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            // Fade in animation
            setTimeout(() => {
                preview.style.opacity = '1';
            }, 150);
        };
        reader.readAsDataURL(file);
    }
}

// Form reset function
function resetForm() {
    if (confirm('Reset all changes?')) {
        document.querySelector('form').reset();
        
        // Reset image preview to original
        const preview = document.getElementById('preview');
        preview.src = '<?= Html::encode($imageUrl) ?>';
    }
}

// Form submission loading state
document.querySelector('form').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.classList.add('btn-loading');
    submitBtn.disabled = true;
});

// Picture upload area click
document.querySelector('.picture-preview').addEventListener('click', function() {
    document.getElementById('imageFile').click();
});

// Drag and drop for image upload
const picturePreview = document.querySelector('.picture-preview');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    picturePreview.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    picturePreview.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    picturePreview.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    picturePreview.classList.add('drag-over');
}

function unhighlight(e) {
    picturePreview.classList.remove('drag-over');
}

picturePreview.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length) {
        document.getElementById('imageFile').files = files;
        loadPreview({ target: { files: files } });
    }
}
JS);
?>

<style>
/* Info Notice Card */
.info-notice {
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border-left: 4px solid var(--ekafa-info);
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    display: flex;
    gap: var(--spacing-md);
}

.info-notice i {
    font-size: 1.5rem;
    color: var(--ekafa-info);
    flex-shrink: 0;
}

.info-notice strong {
    display: block;
    color: var(--ekafa-gray-900);
    margin-bottom: 0.25rem;
    font-size: 0.9375rem;
}

.info-notice p {
    color: var(--ekafa-gray-700);
    font-size: 0.875rem;
    margin: 0;
    line-height: 1.5;
}
</style>