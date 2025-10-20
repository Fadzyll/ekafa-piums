<?php
use yii\helpers\Html;

$this->title = 'My Profile';

// Helper function for safe image URLs
$defaultImage = Yii::getAlias('@web/images/default_profile.png');
function safeImageUrl($relativePath, $defaultImage) {
    if (!$relativePath) return $defaultImage;
    $relative = ltrim($relativePath, '/');
    $filePath = Yii::getAlias('@webroot/' . $relative);
    return file_exists($filePath) ? Yii::getAlias('@web/' . $relative) : $defaultImage;
}

$imageUrl = safeImageUrl($model->profile_picture_url ?? null, $defaultImage);
$partnerImageUrl = $model->partnerDetails && $model->partnerDetails->profile_picture_url
    ? safeImageUrl($model->partnerDetails->profile_picture_url, $defaultImage)
    : $defaultImage;

$userRole = Yii::$app->user->identity->role ?? 'User';
?>

<div class="profile-container fade-in">
    
    <!-- Profile Header -->
    <div class="profile-header">
        <img src="<?= Html::encode($imageUrl) ?>" alt="Profile Picture" class="profile-avatar-large">
        <div class="profile-info">
            <h1><?= Html::encode($model->full_name ?: 'Complete Your Profile') ?></h1>
            <span class="role-badge">
                <i class="bi bi-person-badge"></i>
                <?= Html::encode($userRole) ?>
            </span>
            <p class="mt-2 mb-0" style="opacity: 0.9;">
                <i class="bi bi-envelope"></i>
                <?= Html::encode(Yii::$app->user->identity->email) ?>
            </p>
        </div>
        <div class="ms-auto">
            <?= Html::a('<i class="bi bi-pencil-square"></i> Edit Profile', ['profile'], [
                'class' => 'btn-modern btn-secondary-modern'
            ]) ?>
        </div>
    </div>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs-modern" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button">
                <i class="bi bi-person"></i> Personal Info
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="job-tab" data-bs-toggle="tab" data-bs-target="#job" type="button">
                <i class="bi bi-briefcase"></i> Employment
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="partner-tab" data-bs-toggle="tab" data-bs-target="#partner" type="button">
                <i class="bi bi-people"></i> Partner Info
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content fade-in" id="profileTabsContent">
        
        <!-- Personal Information Tab -->
        <div class="tab-pane fade show active" id="personal" role="tabpanel">
            <div class="info-card">
                <h3 class="info-card-title">
                    <i class="bi bi-person-circle"></i>
                    Personal Information
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value"><?= Html::encode($model->full_name ?: '-') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">IC Number</div>
                        <div class="info-value"><?= Html::encode($model->ic_number ?: '-') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Age</div>
                        <div class="info-value"><?= Html::encode($model->age ?: '-') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Gender</div>
                        <div class="info-value"><?= Html::encode($model->gender ?: '-') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Race</div>
                        <div class="info-value"><?= Html::encode($model->race ?: '-') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Citizenship</div>
                        <div class="info-value"><?= Html::encode($model->citizenship ?: '-') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Marital Status</div>
                        <div class="info-value"><?= Html::encode($model->marital_status ?: '-') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value"><?= Html::encode($model->phone_number ?: '-') ?></div>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <h3 class="info-card-title">
                    <i class="bi bi-geo-alt"></i>
                    Address Information
                </h3>
                <div class="info-grid">
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <div class="info-label">Address</div>
                        <div class="info-value"><?= Html::encode($model->address ?: '-') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">City</div>
                        <div class="info-value"><?= Html::encode($model->city ?: '-') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Postcode</div>
                        <div class="info-value"><?= Html::encode($model->postcode ?: '-') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">State</div>
                        <div class="info-value"><?= Html::encode($model->state ?: '-') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Tab -->
        <div class="tab-pane fade" id="job" role="tabpanel">
            <?php if ($model->userJob): ?>
                <div class="info-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="info-card-title mb-0">
                            <i class="bi bi-briefcase-fill"></i>
                            Employment Details
                        </h3>
                        <?= Html::a('<i class="bi bi-pencil"></i> Edit', ['user-job/profile'], [
                            'class' => 'btn-modern btn-secondary-modern'
                        ]) ?>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Job Title</div>
                            <div class="info-value"><?= Html::encode($model->userJob->job) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Employer</div>
                            <div class="info-value"><?= Html::encode($model->userJob->employer) ?></div>
                        </div>
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">Employer Address</div>
                            <div class="info-value"><?= Html::encode($model->userJob->employer_address) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Employer Phone</div>
                            <div class="info-value"><?= Html::encode($model->userJob->employer_phone_number) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Gross Salary</div>
                            <div class="info-value">RM <?= number_format($model->userJob->gross_salary, 2) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Net Salary</div>
                            <div class="info-value">RM <?= number_format($model->userJob->net_salary, 2) ?></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="info-card text-center" style="padding: 3rem;">
                    <i class="bi bi-briefcase" style="font-size: 3rem; color: var(--ekafa-gray-300);"></i>
                    <h4 class="mt-3 mb-2">No Employment Information</h4>
                    <p class="text-muted mb-4">Add your employment details to complete your profile</p>
                    <?= Html::a('<i class="bi bi-plus-circle"></i> Add Employment Info', ['user-job/profile'], [
                        'class' => 'btn-modern btn-primary-modern'
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Partner Tab -->
        <div class="tab-pane fade" id="partner" role="tabpanel">
            <?php if ($model->partnerDetails): ?>
                <div class="info-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex gap-3 align-items-center">
                            <img src="<?= Html::encode($partnerImageUrl) ?>" alt="Partner" 
                                 style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--ekafa-gray-200);">
                            <div>
                                <h3 class="info-card-title mb-1">
                                    Partner Information
                                </h3>
                                <p class="text-muted mb-0"><?= Html::encode($model->partnerDetails->partner_name) ?></p>
                            </div>
                        </div>
                        <?= Html::a('<i class="bi bi-pencil"></i> Edit', ['partner-details/profile'], [
                            'class' => 'btn-modern btn-secondary-modern'
                        ]) ?>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">IC Number</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_ic_number) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_phone_number) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Citizenship</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_citizenship) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Marital Status</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_marital_status) ?></div>
                        </div>
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">Address</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_address) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">City</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_city) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Postcode</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_postcode) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">State</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_state) ?></div>
                        </div>
                    </div>
                </div>

                <?php if ($model->partnerDetails->partnerJob): ?>
                    <div class="info-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="info-card-title mb-0">
                                <i class="bi bi-briefcase"></i>
                                Partner Employment
                            </h3>
                            <?= Html::a('<i class="bi bi-pencil"></i> Edit', ['partner-job/profile'], [
                                'class' => 'btn-modern btn-secondary-modern'
                            ]) ?>
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Occupation</div>
                                <div class="info-value"><?= Html::encode($model->partnerDetails->partnerJob->partner_job) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Employer</div>
                                <div class="info-value"><?= Html::encode($model->partnerDetails->partnerJob->partner_employer) ?></div>
                            </div>
                            <div class="info-item" style="grid-column: 1 / -1;">
                                <div class="info-label">Employer Address</div>
                                <div class="info-value"><?= Html::encode($model->partnerDetails->partnerJob->partner_employer_address) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Employer Phone</div>
                                <div class="info-value"><?= Html::encode($model->partnerDetails->partnerJob->partner_employer_phone_number) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Gross Salary</div>
                                <div class="info-value">RM <?= number_format($model->partnerDetails->partnerJob->partner_gross_salary, 2) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Net Salary</div>
                                <div class="info-value">RM <?= number_format($model->partnerDetails->partnerJob->partner_net_salary, 2) ?></div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="info-card text-center" style="padding: 2rem;">
                        <i class="bi bi-briefcase" style="font-size: 2rem; color: var(--ekafa-gray-300);"></i>
                        <h5 class="mt-2 mb-2">No Partner Employment Info</h5>
                        <p class="text-muted mb-3">Add partner's employment details</p>
                        <?= Html::a('<i class="bi bi-plus-circle"></i> Add Partner Job', ['partner-job/profile'], [
                            'class' => 'btn-modern btn-primary-modern btn-sm'
                        ]) ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="info-card text-center" style="padding: 3rem;">
                    <i class="bi bi-people" style="font-size: 3rem; color: var(--ekafa-gray-300);"></i>
                    <h4 class="mt-3 mb-2">No Partner Information</h4>
                    <p class="text-muted mb-4">Add your partner's details to complete your profile</p>
                    <?= Html::a('<i class="bi bi-plus-circle"></i> Add Partner Info', ['partner-details/profile'], [
                        'class' => 'btn-modern btn-primary-modern'
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>