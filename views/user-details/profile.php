<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserDetails $model */

$this->title = 'Edit Profile';
$this->params['breadcrumbs'][] = ['label' => 'My Profile', 'url' => ['view']];
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
                    <?= Html::a('<i class="bi bi-arrow-left"></i>', ['view'], [
                        'class' => 'btn-modern btn-secondary-modern btn-icon',
                        'title' => 'Back to Profile'
                    ]) ?>
                </div>
                <div>
                    <h1 class="edit-title"><?= Html::encode($this->title) ?></h1>
                    <p class="edit-subtitle">Update your personal information</p>
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
                        <h3>Profile Picture</h3>
                    </div>
                    
                    <div class="picture-preview-wrapper">
                        <div class="picture-preview">
                            <img id="preview" src="<?= Html::encode($imageUrl) ?>" 
                                 alt="Profile Picture">
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

                <!-- Quick Stats Card -->
                <div class="stats-card">
                    <div class="stat-item">
                        <i class="bi bi-calendar-check"></i>
                        <div>
                            <div class="stat-label">Member Since</div>
                            <div class="stat-value">
                                <?= Yii::$app->formatter->asDate($model->created_at ?? time(), 'long') ?>
                            </div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="bi bi-clock-history"></i>
                        <div>
                            <div class="stat-label">Last Updated</div>
                            <div class="stat-value">
                                <?= Yii::$app->formatter->asRelativeTime($model->updated_at ?? time()) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Form Fields -->
            <div class="form-fields-section">
                
                <!-- Personal Information Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-person-circle"></i>
                        <h3>Personal Information</h3>
                        <span class="required-badge">
                            <i class="bi bi-asterisk"></i> Required
                        </span>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <?= $form->field($model, 'full_name')->textInput([
                                'placeholder' => 'Enter your full name',
                                'maxlength' => true
                            ])->label('Full Name <span class="required-star">*</span>') ?>
                        </div>
                        <div class="form-col">
                            <?= $form->field($model, 'ic_number')->textInput([
                                'placeholder' => 'e.g., 950815105234',
                                'maxlength' => true
                            ])->label('IC Number <span class="required-star">*</span>') ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <?= $form->field($model, 'age')->textInput([
                                'type' => 'number',
                                'min' => 1,
                                'max' => 120,
                                'placeholder' => 'Age'
                            ]) ?>
                        </div>
                        <div class="form-col">
                            <?= $form->field($model, 'gender')->dropDownList([
                                'Male' => 'Male',
                                'Female' => 'Female'
                            ], [
                                'prompt' => 'Select Gender',
                                'class' => 'form-control-modern select-modern'
                            ]) ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <?= $form->field($model, 'race')->textInput([
                                'placeholder' => 'e.g., Malay, Chinese, Indian',
                                'maxlength' => true
                            ]) ?>
                        </div>
                        <div class="form-col">
                            <?= $form->field($model, 'citizenship')->textInput([
                                'placeholder' => 'e.g., Malaysian',
                                'maxlength' => true
                            ]) ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <?= $form->field($model, 'phone_number')->textInput([
                                'placeholder' => 'e.g., +60123456789',
                                'maxlength' => true
                            ])->label('Phone Number <span class="required-star">*</span>') ?>
                        </div>
                        <div class="form-col">
                            <?= $form->field($model, 'marital_status')->dropDownList([
                                'Single' => 'Single',
                                'Married' => 'Married',
                                'Divorced' => 'Divorced',
                                'Widowed' => 'Widowed'
                            ], [
                                'prompt' => 'Select Status',
                                'class' => 'form-control-modern select-modern'
                            ]) ?>
                        </div>
                    </div>
                </div>

                <!-- Address Information Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-geo-alt-fill"></i>
                        <h3>Address Information</h3>
                    </div>

                    <div class="form-row">
                        <div class="form-col full-width">
                            <?= $form->field($model, 'address')->textarea([
                                'rows' => 3,
                                'placeholder' => 'Enter your complete address',
                                'class' => 'form-control-modern textarea-modern'
                            ]) ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <?= $form->field($model, 'city')->textInput([
                                'placeholder' => 'e.g., Kota Kinabalu',
                                'maxlength' => true
                            ]) ?>
                        </div>
                        <div class="form-col">
                            <?= $form->field($model, 'postcode')->textInput([
                                'placeholder' => 'e.g., 88400',
                                'maxlength' => true
                            ]) ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <?= $form->field($model, 'state')->dropDownList([
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
                            ]) ?>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <div class="actions-left">
                        <?= Html::a('<i class="bi bi-x-circle"></i> Cancel', ['view'], [
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
                        <?= Html::submitButton('<i class="bi bi-check-circle"></i> Save Changes', [
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
/* ============================================================================
   Profile Edit Page - Modern Design
   ============================================================================ */

.edit-profile-wrapper {
    max-width: 1400px;
    margin: 0 auto;
}

.edit-header {
    background: linear-gradient(135deg, var(--ekafa-primary), var(--ekafa-primary-light));
    color: white;
    padding: var(--spacing-xl);
    border-radius: var(--radius-2xl);
    margin-bottom: var(--spacing-xl);
    box-shadow: var(--shadow-lg);
}

.back-button {
    display: inline-block;
}

.btn-icon {
    width: 48px;
    height: 48px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.edit-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
}

.edit-subtitle {
    margin: 0;
    opacity: 0.9;
    font-size: 0.9375rem;
}

/* Grid Layout */
.edit-grid {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: var(--spacing-xl);
}

@media (max-width: 1200px) {
    .edit-grid {
        grid-template-columns: 1fr;
    }
}

/* Profile Picture Section */
.profile-picture-section {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.picture-card {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-md);
}

.picture-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-lg);
    color: var(--ekafa-primary);
}

.picture-header i {
    font-size: 1.5rem;
}

.picture-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 700;
}

.picture-preview-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: var(--spacing-lg);
}

.picture-preview {
    position: relative;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
    border: 4px solid var(--ekafa-gray-200);
    transition: all var(--transition-base);
    box-shadow: var(--shadow-md);
}

.picture-preview:hover {
    border-color: var(--ekafa-primary);
    box-shadow: var(--shadow-lg);
}

.picture-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-base);
}

.picture-preview:hover img {
    transform: scale(1.05);
}

.picture-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 65, 53, 0.85);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transition: opacity var(--transition-base);
    gap: var(--spacing-sm);
}

.picture-preview:hover .picture-overlay {
    opacity: 1;
}

.picture-overlay i {
    font-size: 2rem;
}

.picture-overlay span {
    font-size: 0.875rem;
    font-weight: 600;
}

/* Drag over state */
.picture-preview.drag-over {
    border-color: var(--ekafa-primary);
    border-style: dashed;
    background-color: var(--ekafa-gray-50);
}

.picture-upload {
    text-align: center;
}

.file-input-hidden {
    display: none;
}

.upload-button {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: 0.75rem 1.5rem;
    background: var(--ekafa-primary);
    color: white;
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-base);
    border: none;
    box-shadow: var(--shadow-sm);
}

.upload-button:hover {
    background: var(--ekafa-primary-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.upload-hint {
    margin-top: var(--spacing-md);
    font-size: 0.8125rem;
    color: var(--ekafa-gray-500);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
}

/* Stats Card */
.stats-card {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-md);
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.stat-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    background: var(--ekafa-gray-50);
    border-radius: var(--radius-lg);
}

.stat-item i {
    font-size: 1.5rem;
    color: var(--ekafa-primary);
}

.stat-label {
    font-size: 0.8125rem;
    color: var(--ekafa-gray-500);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-value {
    font-size: 0.9375rem;
    color: var(--ekafa-gray-900);
    font-weight: 600;
}

/* Form Fields Section */
.form-fields-section {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.form-card {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl);
    box-shadow: var(--shadow-md);
    transition: box-shadow var(--transition-base);
}

.form-card:hover {
    box-shadow: var(--shadow-lg);
}

.form-card-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 2px solid var(--ekafa-gray-100);
}

.form-card-header i {
    font-size: 1.5rem;
    color: var(--ekafa-primary);
}

.form-card-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--ekafa-gray-900);
    flex: 1;
}

.required-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    background: #fef2f2;
    color: #991b1b;
    border-radius: var(--radius-md);
    font-size: 0.8125rem;
    font-weight: 600;
}

.required-star {
    color: #ef4444;
    margin-left: 0.25rem;
}

/* Form Row Layout */
.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-md);
}

.form-col.full-width {
    grid-column: 1 / -1;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

/* Modern Form Controls */
.form-label-modern {
    display: block;
    font-weight: 600;
    color: var(--ekafa-gray-700);
    margin-bottom: var(--spacing-sm);
    font-size: 0.875rem;
}

.form-control-modern {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--ekafa-gray-200);
    border-radius: var(--radius-lg);
    font-size: 0.9375rem;
    transition: all var(--transition-base);
    background-color: white;
}

.form-control-modern:focus {
    outline: none;
    border-color: var(--ekafa-primary);
    box-shadow: 0 0 0 4px rgba(0, 65, 53, 0.1);
}

.form-control-modern:hover:not(:focus) {
    border-color: var(--ekafa-gray-300);
}

.textarea-modern {
    resize: vertical;
    min-height: 100px;
}

.select-modern {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234b5563' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 3rem;
    appearance: none;
}

.error-message {
    display: block;
    color: #ef4444;
    font-size: 0.8125rem;
    margin-top: var(--spacing-xs);
    animation: slideDown 0.2s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-lg);
    background: var(--ekafa-gray-50);
    border-radius: var(--radius-xl);
    gap: var(--spacing-md);
}

.actions-left,
.actions-right {
    display: flex;
    gap: var(--spacing-md);
}

.btn-outline-modern {
    background: white;
    color: var(--ekafa-gray-700);
    border: 2px solid var(--ekafa-gray-300);
}

.btn-outline-modern:hover {
    border-color: var(--ekafa-gray-400);
    background: var(--ekafa-gray-50);
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
    
    .actions-left,
    .actions-right {
        width: 100%;
    }
    
    .actions-right {
        flex-direction: column;
    }
    
    .btn-modern {
        width: 100%;
    }
}
</style>