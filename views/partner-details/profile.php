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

<style>
/* Reuse enhanced styles with partner-specific theme */
:root {
    --partner-gradient: linear-gradient(135deg, #dc2626, #f87171);
    --partner-light: linear-gradient(135deg, #fef2f2, #fee2e2);
    --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
}

body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    background-attachment: fixed !important;
    min-height: 100vh;
}

.partner-edit-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
    animation: fadeInUp 0.6s ease;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Partner-specific Header (Heart/Love Theme) */
.partner-header {
    background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
    color: white;
    border-radius: 24px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 60px rgba(236, 72, 153, 0.3);
    position: relative;
    overflow: hidden;
}

.partner-header::before {
    content: '❤️';
    position: absolute;
    font-size: 15rem;
    opacity: 0.1;
    top: -3rem;
    right: -2rem;
    transform: rotate(-15deg);
}

.partner-header-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: space-between; /* spreads left and right */
    gap: 1.5rem;
}

.partner-header-text {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.partner-icon-large {
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

.partner-header h1 {
    font-size: 2.25rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
}

.partner-header p {
    opacity: 0.95;
    font-size: 1.0625rem;
    margin: 0;
}

/* Edit Grid */
.edit-grid {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 2rem;
}

@media (max-width: 1200px) {
    .edit-grid {
        grid-template-columns: 1fr;
    }
}

/* Picture Section (Pink/Rose Theme) */
.picture-card {
    background: white;
    border-radius: 24px;
    padding: 2rem;
    box-shadow: var(--card-shadow);
    border: 1px solid #fce7f3;
    transition: all 0.4s ease;
    position: sticky;
    top: 2rem;
}

.picture-card:hover {
    box-shadow: 0 20px 60px rgba(236, 72, 153, 0.2);
    transform: translateY(-4px);
}

.picture-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #fce7f3;
}

.picture-header i {
    font-size: 2rem;
    color: white;
    background: linear-gradient(135deg, #ec4899, #f472b6);
    padding: 0.75rem;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(236, 72, 153, 0.3);
}

.picture-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.picture-preview-wrapper {
    margin-bottom: 2rem;
    display: flex;
    justify-content: center;
}

.picture-preview {
    position: relative;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
    border: 6px solid transparent;
    background: linear-gradient(white, white) padding-box,
                linear-gradient(135deg, #ec4899, #f472b6) border-box;
    box-shadow: 0 15px 40px rgba(236, 72, 153, 0.2);
    transition: all 0.4s ease;
}

.picture-preview:hover {
    transform: scale(1.05) rotate(3deg);
    box-shadow: 0 25px 60px rgba(236, 72, 153, 0.35);
}

.picture-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.picture-preview:hover img {
    transform: scale(1.1);
}

.picture-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.95), rgba(244, 114, 182, 0.9));
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease;
    gap: 0.75rem;
}

.picture-preview:hover .picture-overlay {
    opacity: 1;
}

.picture-overlay i {
    font-size: 3rem;
    animation: bounceUpDown 2s ease-in-out infinite;
}

@keyframes bounceUpDown {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.picture-overlay span {
    font-size: 1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

.file-input-hidden {
    display: none;
}

.upload-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #ec4899, #f472b6);
    color: white;
    border-radius: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 8px 24px rgba(236, 72, 153, 0.3);
}

.upload-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(236, 72, 153, 0.4);
}

.upload-hint {
    margin-top: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #fdf2f8, #fce7f3);
    border-radius: 12px;
    color: #9f1239;
    font-size: 0.875rem;
    text-align: center;
    border: 1px solid #fbcfe8;
}

.info-notice {
    margin-top: 1.5rem;
    padding: 1.25rem;
    background: linear-gradient(135deg, #fff7ed, #ffedd5);
    border-left: 4px solid #fb923c;
    border-radius: 14px;
    display: flex;
    gap: 1rem;
}

.info-notice i {
    font-size: 1.5rem;
    color: #ea580c;
    flex-shrink: 0;
}

.info-notice-content {
    font-size: 0.9375rem;
    color: #7c2d12;
    line-height: 1.6;
}

.info-notice-content strong {
    display: block;
    margin-bottom: 0.25rem;
    color: #9a3412;
}

/* Form Cards */
.form-card {
    background: white;
    border-radius: 24px;
    padding: 2rem;
    box-shadow: var(--card-shadow);
    border: 1px solid #f3f4f6;
    transition: all 0.4s ease;
    margin-bottom: 1.5rem;
}

.form-card:hover {
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
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
    background: linear-gradient(135deg, #ec4899, #f472b6);
    padding: 0.875rem;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(236, 72, 153, 0.25);
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
    transition: all 0.3s ease;
    background-color: #fafafa;
}

.form-control-modern:focus {
    outline: none;
    border-color: #ec4899;
    background-color: white;
    box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.15);
    transform: translateY(-1px);
}

.textarea-modern {
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
}

.select-modern {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%23ec4899' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1.25rem center;
    padding-right: 3.5rem;
    appearance: none;
    cursor: pointer;
}

.error-message {
    display: flex;
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

.error-message::before {
    content: '⚠';
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
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.btn-primary-modern {
    background: linear-gradient(135deg, #10b981, #00c77f);
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
    margin: 0; /* remove margin-bottom */
}

.btn-back:hover {
    background: white;
    color: #ec4899;
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

<div class="partner-edit-container">
    <!-- Partner Header -->
    <div class="partner-header">
        <div class="partner-header-content">
            <!-- Back Button -->
                <?= Html::a('<i class="bi bi-arrow-left"></i>', ['user-details/view'], [
                    'class' => 'btn-back',
                    'title' => 'Back to Profile'
                ]) ?>
            <div class="partner-header-text">
                <h1><?= Html::encode($this->title) ?></h1>
                <p>Add your partner's details for family records and emergency contact</p>
            </div>
            <div class="partner-icon-large">
                <i class="bi bi-person-hearts"></i>
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
        <!-- Left Column: Partner Picture -->
        <div class="picture-section">
            <div class="picture-card">
                <div class="picture-header">
                    <i class="bi bi-camera-fill"></i>
                    <h3>Partner's Photo</h3>
                </div>
                
                <div class="picture-preview-wrapper">
                    <div class="picture-preview" onclick="document.getElementById('imageFile').click();">
                        <img id="preview" src="<?= Html::encode($imageUrl) ?>" alt="Partner Picture">
                        <div class="picture-overlay">
                            <i class="bi bi-cloud-upload"></i>
                            <span>Upload Photo</span>
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
                        <i class="bi bi-upload"></i>
                        <span>Choose Photo</span>
                    </label>
                    
                    <div class="upload-hint">
                        <i class="bi bi-info-circle"></i>
                        JPG, PNG • Max 2MB • 100×100 to 1000×1000px
                    </div>
                </div>

                <div class="info-notice">
                    <i class="bi bi-shield-check"></i>
                    <div class="info-notice-content">
                        <strong>Privacy Notice</strong>
                        Partner information is securely stored and used only for family records and emergency contact purposes.
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Form Fields -->
        <div class="form-section">
            
            <!-- Personal Information Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-person-vcard"></i>
                    <h3>Partner's Personal Information</h3>
                    <span class="required-badge">
                        <i class="bi bi-asterisk"></i> Required
                    </span>
                </div>

                <div class="form-row">
                    <div>
                        <?= $form->field($model, 'partner_name')->textInput([
                            'placeholder' => "Enter partner's full name",
                            'maxlength' => true
                        ])->label('Full Name <span class="required-star">*</span>') ?>
                    </div>
                    <div>
                        <?= $form->field($model, 'partner_ic_number')->textInput([
                            'placeholder' => 'e.g., 950815105234',
                            'maxlength' => true
                        ])->label('IC Number <span class="required-star">*</span>') ?>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <?= $form->field($model, 'partner_phone_number')->textInput([
                            'placeholder' => 'e.g., +60123456789',
                            'maxlength' => true
                        ])->label('Phone Number') ?>
                    </div>
                    <div>
                        <?= $form->field($model, 'partner_citizenship')->textInput([
                            'placeholder' => 'e.g., Malaysian',
                            'maxlength' => true
                        ])->label('Citizenship') ?>
                    </div>
                </div>

                <div class="form-row">
                    <div>
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
                    <div class="form-col-full">
                        <?= $form->field($model, 'partner_address')->textarea([
                            'rows' => 3,
                            'placeholder' => "Enter partner's complete address",
                            'class' => 'form-control-modern textarea-modern'
                        ])->label('Address') ?>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <?= $form->field($model, 'partner_city')->textInput([
                            'placeholder' => 'e.g., Kota Kinabalu',
                            'maxlength' => true
                        ])->label('City') ?>
                    </div>
                    <div>
                        <?= $form->field($model, 'partner_postcode')->textInput([
                            'placeholder' => 'e.g., 88400',
                            'maxlength' => true
                        ])->label('Postcode') ?>
                    </div>
                </div>

                <div class="form-row">
                    <div>
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
                    <?= Html::submitButton('<i class="bi bi-check-circle-fill"></i><span>Save Partner Info</span>', [
                        'class' => 'btn-modern btn-primary-modern',
                        'id' => 'submit-btn'
                    ]) ?>
                </div>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
// Image preview
function loadPreview(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('⚠️ File size must be less than 2MB');
            event.target.value = '';
            return;
        }
        
        if (!file.type.match('image.*')) {
            alert('⚠️ Please select an image file');
            event.target.value = '';
            return;
        }
        
        preview.style.opacity = '0.5';
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            setTimeout(() => preview.style.opacity = '1', 150);
        };
        reader.readAsDataURL(file);
    }
}

// Form reset
function resetForm() {
    if (confirm('🔄 Reset all changes?')) {
        document.querySelector('form').reset();
        document.getElementById('preview').src = '<?= Html::encode($imageUrl) ?>';
    }
}

// Form submission
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    btn.classList.add('btn-loading');
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i><span>Saving...</span>';
});
</script>