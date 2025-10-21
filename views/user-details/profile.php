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

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #004135, #11684d);
    --success-gradient: linear-gradient(135deg, #10b981, #00c77f);
    --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    --card-hover-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
    --input-focus-glow: 0 0 0 4px rgba(0, 168, 107, 0.15);
}

body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    background-attachment: fixed !important;
    min-height: 100vh;
}

.profile-edit-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
    animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

.edit-header {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px) saturate(180%);
    border-radius: 24px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.6);
    position: relative;
    overflow: hidden;
}

.edit-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: var(--primary-gradient);
}

.edit-header::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(0, 168, 107, 0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}

.back-button-wrapper {
    flex-shrink: 0;
}

.btn-back {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: white;
    border: 2px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #004135;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.btn-back:hover {
    transform: translateX(-4px) scale(1.05);
    background: var(--primary-gradient);
    color: white;
    border-color: transparent;
    box-shadow: 0 8px 24px rgba(0, 65, 53, 0.3);
}

.header-text h1 {
    font-size: 2.25rem;
    font-weight: 800;
    color: #111827;
    margin: 0 0 0.5rem 0;
    letter-spacing: -0.02em;
}

.header-subtitle {
    color: #6b7280;
    font-size: 1.0625rem;
    margin: 0;
    font-weight: 500;
}

.completion-badge {
    margin-left: auto;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #ecfdf5, #d1fae5);
    border-radius: 50px;
    border: 2px solid #10b981;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 700;
    color: #065f46;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.completion-badge i {
    font-size: 1.5rem;
}

.edit-grid {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 2rem;
    align-items: start;
}

@media (max-width: 1200px) {
    .edit-grid {
        grid-template-columns: 1fr;
    }
}

.picture-section {
    position: sticky;
    top: 2rem;
}

.picture-card {
    background: white;
    border-radius: 24px;
    padding: 2rem;
    box-shadow: var(--card-shadow);
    border: 1px solid #f3f4f6;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.picture-card:hover {
    box-shadow: var(--card-hover-shadow);
    transform: translateY(-4px);
}

.picture-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.picture-header i {
    font-size: 2rem;
    color: white;
    background: var(--primary-gradient);
    padding: 0.75rem;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(0, 65, 53, 0.3);
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
                var(--primary-gradient) border-box;
    box-shadow: 0 15px 40px rgba(0, 65, 53, 0.2);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.picture-preview:hover {
    transform: scale(1.05) rotate(3deg);
    box-shadow: 0 25px 60px rgba(0, 65, 53, 0.35);
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
    background: linear-gradient(135deg, rgba(0, 65, 53, 0.95), rgba(0, 168, 107, 0.9));
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

.picture-upload {
    text-align: center;
}

.file-input-hidden {
    display: none;
}

.upload-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    background: var(--primary-gradient);
    color: white;
    border-radius: 16px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    border: none;
    box-shadow: 0 8px 24px rgba(0, 65, 53, 0.3);
    position: relative;
    overflow: hidden;
}

.upload-button::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.upload-button:hover::before {
    width: 300px;
    height: 300px;
}

.upload-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(0, 65, 53, 0.4);
}

.upload-button i {
    font-size: 1.25rem;
    position: relative;
    z-index: 1;
}

.upload-button span {
    position: relative;
    z-index: 1;
}

.upload-hint {
    margin-top: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-radius: 12px;
    color: #065f46;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border: 1px solid #86efac;
}

.upload-hint i {
    font-size: 1.125rem;
}

.stats-mini {
    margin-top: 1.5rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.stat-box {
    background: linear-gradient(135deg, #f9fafb, #ffffff);
    padding: 1.25rem;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    text-align: center;
    transition: all 0.3s ease;
}

.stat-box:hover {
    background: linear-gradient(135deg, #ecfdf5, #d1fae5);
    border-color: #86efac;
    transform: translateY(-2px);
}

.stat-icon {
    font-size: 2rem;
    color: #004135;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 0.9375rem;
    color: #111827;
    font-weight: 600;
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-card {
    background: white;
    border-radius: 24px;
    padding: 2rem;
    box-shadow: var(--card-shadow);
    border: 1px solid #f3f4f6;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    animation: slideInRight 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    animation-fill-mode: both;
}

.form-card:nth-child(1) { animation-delay: 0.1s; }
.form-card:nth-child(2) { animation-delay: 0.2s; }
.form-card:nth-child(3) { animation-delay: 0.3s; }

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(40px); }
    to { opacity: 1; transform: translateX(0); }
}

.form-card:hover {
    box-shadow: var(--card-hover-shadow);
    border-color: #e5e7eb;
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
    background: var(--primary-gradient);
    padding: 0.875rem;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(0, 65, 53, 0.25);
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

.form-group {
    margin-bottom: 0;
}

.form-label-modern {
    display: block;
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.75rem;
    font-size: 0.9375rem;
    letter-spacing: 0.01em;
}

.required-star {
    color: #ef4444;
    margin-left: 0.25rem;
    font-size: 1.125rem;
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
    font-weight: 500;
}

.form-control-modern::placeholder {
    color: #9ca3af;
    font-weight: 400;
}

.form-control-modern:hover {
    border-color: #d1d5db;
    background-color: white;
}

.form-control-modern:focus {
    outline: none;
    border-color: #00a86b;
    background-color: white;
    box-shadow: var(--input-focus-glow);
    transform: translateY(-1px);
}

.form-control-modern:focus::placeholder {
    color: #d1d5db;
}

.textarea-modern {
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
    line-height: 1.6;
}

.select-modern {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%23004135' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1.25rem center;
    background-size: 16px;
    padding-right: 3.5rem;
    appearance: none;
    cursor: pointer;
}

.select-modern:hover {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%2300a86b' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
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
    margin-top: 1rem;
}

.actions-left,
.actions-right {
    display: flex;
    gap: 1rem;
}

.btn-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border-radius: 14px;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    text-decoration: none;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.btn-modern::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-modern:active::before {
    width: 400px;
    height: 400px;
}

.btn-primary-modern {
    background: var(--success-gradient);
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
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.btn-secondary-modern:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    transform: translateY(-2px);
}

.btn-outline-modern {
    background: transparent;
    color: #6b7280;
    border: 2px solid #d1d5db;
    box-shadow: none;
}

.btn-outline-modern:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #374151;
}

.btn-modern i {
    font-size: 1.125rem;
    position: relative;
    z-index: 1;
}

.btn-modern span {
    position: relative;
    z-index: 1;
}

@media (max-width: 768px) {
    .edit-header {
        padding: 1.5rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-text h1 {
        font-size: 1.75rem;
    }
    
    .completion-badge {
        align-self: stretch;
        justify-content: center;
    }
    
    .picture-preview {
        width: 200px;
        height: 200px;
    }
    
    .form-card {
        padding: 1.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 1rem;
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

<div class="profile-edit-container">
    <div class="edit-header">
        <div class="header-content">
            <div class="back-button-wrapper">
                <?= Html::a('<i class="bi bi-arrow-left"></i>', ['view'], [
                    'class' => 'btn-back',
                    'title' => 'Back to Profile'
                ]) ?>
            </div>
            <div class="header-text">
                <h1><?= Html::encode($this->title) ?></h1>
                <p class="header-subtitle">Update your personal information and keep your profile up to date</p>
            </div>
            <div class="completion-badge">
                <i class="bi bi-pencil-square"></i>
                <span>Editing Mode</span>
            </div>
        </div>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'profile-form',
        'options' => [
            'enctype' => 'multipart/form-data', 
            'class' => 'modern-form'
        ],
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'validateOnBlur' => false,
        'validateOnChange' => false,
        'validateOnSubmit' => true,
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'form-label-modern'],
            'inputOptions' => ['class' => 'form-control-modern'],
            'errorOptions' => ['class' => 'error-message'],
        ],
    ]); ?>

    <div class="edit-grid">
        <div class="picture-section">
            <div class="picture-card">
                <div class="picture-header">
                    <i class="bi bi-camera-fill"></i>
                    <h3>Profile Picture</h3>
                </div>
                
                <div class="picture-preview-wrapper">
                    <div class="picture-preview" onclick="document.getElementById('imageFile').click();">
                        <img id="preview" src="<?= Html::encode($imageUrl) ?>" alt="Profile Picture">
                        <div class="picture-overlay">
                            <i class="bi bi-cloud-upload"></i>
                            <span>Upload New</span>
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
                        <i class="bi bi-info-circle-fill"></i>
                        <span>JPG, PNG • Max 2MB • 100×100 to 1000×1000px</span>
                    </div>
                </div>

                <div class="stats-mini">
                    <div class="stat-box">
                        <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
                        <div class="stat-label">Member Since</div>
                        <div class="stat-value"><?= Yii::$app->formatter->asDate($model->created_at ?? time(), 'MMM yyyy') ?></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
                        <div class="stat-label">Last Update</div>
                        <div class="stat-value"><?= Yii::$app->formatter->asRelativeTime($model->updated_at ?? time()) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-person-circle"></i>
                    <h3>Personal Information</h3>
                    <span class="required-badge">
                        <i class="bi bi-asterisk"></i> Required Fields
                    </span>
                </div>

                <div class="form-row">
                    <div>
                        <?= $form->field($model, 'full_name')->textInput([
                            'placeholder' => 'Enter your full name',
                            'maxlength' => true
                        ])->label('Full Name <span class="required-star">*</span>') ?>
                    </div>
                    <div>
                        <?= $form->field($model, 'ic_number')->textInput([
                            'placeholder' => 'e.g., 950815105234',
                            'maxlength' => true
                        ])->label('IC Number <span class="required-star">*</span>') ?>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <?= $form->field($model, 'age')->textInput([
                            'type' => 'number',
                            'min' => 1,
                            'max' => 120,
                            'placeholder' => 'Your age'
                        ]) ?>
                    </div>
                    <div>
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
                    <div>
                        <?= $form->field($model, 'race')->textInput([
                            'placeholder' => 'e.g., Malay, Chinese, Indian',
                            'maxlength' => true
                        ]) ?>
                    </div>
                    <div>
                        <?= $form->field($model, 'citizenship')->textInput([
                            'placeholder' => 'e.g., Malaysian',
                            'maxlength' => true
                        ]) ?>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <?= $form->field($model, 'phone_number')->textInput([
                            'placeholder' => 'e.g., +60123456789',
                            'maxlength' => true
                        ])->label('Phone Number <span class="required-star">*</span>') ?>
                    </div>
                    <div>
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

            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-geo-alt-fill"></i>
                    <h3>Address Information</h3>
                </div>

                <div class="form-row">
                    <div class="form-col-full">
                        <?= $form->field($model, 'address')->textarea([
                            'rows' => 3,
                            'placeholder' => 'Enter your complete address',
                            'class' => 'form-control-modern textarea-modern'
                        ]) ?>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <?= $form->field($model, 'city')->textInput([
                            'placeholder' => 'e.g., Kota Kinabalu',
                            'maxlength' => true
                        ]) ?>
                    </div>
                    <div>
                        <?= $form->field($model, 'postcode')->textInput([
                            'placeholder' => 'e.g., 88400',
                            'maxlength' => true
                        ]) ?>
                    </div>
                </div>

                <div class="form-row">
                    <div>
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

            <div class="form-actions">
                <div class="actions-left">
                    <?= Html::a('<i class="bi bi-x-circle"></i><span>Cancel</span>', ['view'], [
                        'class' => 'btn-modern btn-secondary-modern',
                        'data' => [
                            'confirm' => 'Discard changes and go back?',
                        ],
                    ]) ?>
                </div>
                <div class="actions-right">
                    <button type="button" class="btn-modern btn-outline-modern" onclick="resetForm()">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span>Reset</span>
                    </button>
                    <?= Html::submitButton('<i class="bi bi-check-circle-fill"></i><span>Save Changes</span>', [
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
            alert('⚠️ Please select an image file (JPG, PNG)');
            event.target.value = '';
            return;
        }
        
        preview.style.transition = 'opacity 0.3s ease';
        preview.style.opacity = '0.5';
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            setTimeout(() => preview.style.opacity = '1', 150);
        };
        reader.readAsDataURL(file);
    }
}

function resetForm() {
    if (confirm('🔄 Reset all changes?')) {
        document.querySelector('form').reset();
        const preview = document.getElementById('preview');
        preview.src = '<?= Html::encode($imageUrl) ?>';
    }
}
</script>