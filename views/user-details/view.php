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
$isTeacher = $userRole === 'Teacher';
$isParent = $userRole === 'Parent';
$isAdmin = $userRole === 'Admin';

// Calculate profile completion percentage based on role
$totalFields = 0;
$completedFields = 0;

// Base fields for all roles
$baseFields = ['full_name', 'ic_number', 'age', 'gender', 'race', 'phone_number', 'citizenship', 'marital_status', 'address', 'city', 'postcode', 'state'];
$totalFields += count($baseFields);
foreach ($baseFields as $field) {
    if (!empty($model->$field)) $completedFields++;
}

// Role-specific fields
if ($isParent) {
    if ($model->userJobDetails) {
        $completedFields += 1;
        $totalFields += 1;
    } else {
        $totalFields += 1;
    }
    if ($model->partnerDetails) {
        $completedFields += 1;
        $totalFields += 1;
    } else {
        $totalFields += 1;
    }
}

if ($isTeacher) {
    $teacherEducations = \app\models\TeachersEducation::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->count();
    if ($teacherEducations > 0) {
        $completedFields += 1;
        $totalFields += 1;
    } else {
        $totalFields += 1;
    }
}

$completionPercentage = $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;

// Get teacher education records if user is a teacher
$teacherEducations = [];
if ($isTeacher) {
    $teacherEducations = \app\models\TeachersEducation::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->orderBy(['graduation_date' => SORT_DESC])
        ->all();
}
?>

<style>
/* ============================================================================
   Enhanced Profile Page Styles
   ============================================================================ */

.profile-container {
    max-width: 1400px;
    margin: 0 auto;
    animation: fadeIn 0.5s ease-out;
}

/* Modern Profile Header with Gradient */
.profile-header {
    background: linear-gradient(135deg, #004135 0%, #11684d 50%, #00a86b 100%);
    color: white;
    border-radius: 24px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0, 65, 53, 0.3);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    gap: 2rem;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.profile-header::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -5%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
    border-radius: 50%;
}

/* Profile Avatar with Ring Effect */
.profile-avatar-large {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    border: 5px solid rgba(255, 255, 255, 0.3);
    object-fit: cover;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    position: relative;
    z-index: 1;
    transition: transform 0.3s ease;
}

.profile-avatar-large:hover {
    transform: scale(1.05) rotate(2deg);
}

.profile-info {
    flex: 1;
    position: relative;
    z-index: 1;
}

.profile-info h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    letter-spacing: -0.02em;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.25);
    border-radius: 50px;
    font-size: 0.9375rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Profile Completion Progress */
.profile-completion {
    margin-top: 1.5rem;
    position: relative;
    z-index: 1;
}

.completion-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
}

.completion-percentage {
    font-size: 1.25rem;
    font-weight: 700;
}

.progress-bar-wrapper {
    height: 12px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #00c77f);
    border-radius: 50px;
    transition: width 1s ease-out;
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
    animation: progressGlow 2s ease-in-out infinite;
}

@keyframes progressGlow {
    0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.5); }
    50% { box-shadow: 0 0 30px rgba(16, 185, 129, 0.8); }
}

/* Modern Tab Navigation */
.nav-tabs-modern {
    border-bottom: 3px solid #e5e7eb;
    margin-bottom: 2rem;
    display: flex;
    gap: 0.5rem;
    background: white;
    padding: 0.5rem;
    border-radius: 16px 16px 0 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.nav-tabs-modern .nav-link {
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 12px;
    background: transparent;
    color: #6b7280;
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
}

.nav-tabs-modern .nav-link i {
    font-size: 1.25rem;
}

.nav-tabs-modern .nav-link:hover {
    color: #004135;
    background: #f3f4f6;
    transform: translateY(-2px);
}

.nav-tabs-modern .nav-link.active {
    color: white;
    background: linear-gradient(135deg, #004135, #11684d);
    box-shadow: 0 8px 20px rgba(0, 65, 53, 0.3);
    transform: translateY(-2px);
}

/* Enhanced Info Cards */
.info-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
    border: 1px solid #f3f4f6;
}

.info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
    border-color: #e5e7eb;
}

.info-card-title {
    font-size: 1.375rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.info-card-title i {
    font-size: 1.75rem;
    color: #004135;
    background: linear-gradient(135deg, #ecfdf5, #d1fae5);
    padding: 0.75rem;
    border-radius: 12px;
}

/* Info Grid with Better Spacing */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.info-item {
    padding: 1rem;
    background: #f9fafb;
    border-radius: 12px;
    border: 1px solid #f3f4f6;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #f3f4f6;
    border-color: #e5e7eb;
    transform: translateX(4px);
}

.info-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.info-label::before {
    content: '';
    width: 4px;
    height: 4px;
    background: #004135;
    border-radius: 50%;
}

.info-value {
    font-size: 1.0625rem;
    color: #111827;
    font-weight: 600;
}

/* Enhanced Empty State */
.empty-state-card {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
    border-radius: 20px;
    border: 2px dashed #d1d5db;
}

.empty-state-icon {
    font-size: 4rem;
    color: #9ca3af;
    margin-bottom: 1.5rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.empty-state-card h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.75rem;
}

.empty-state-card p {
    color: #6b7280;
    font-size: 1rem;
    margin-bottom: 2rem;
}

/* Partner Info Card Special Styling */
.partner-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-radius: 16px;
}

.partner-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.partner-info h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 0.25rem;
}

.partner-info p {
    color: #78350f;
    font-size: 0.9375rem;
    margin: 0;
}

/* Education Record Card */
.education-record {
    background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
    border: 2px solid #e9d5ff;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.education-record::before {
    content: '🎓';
    position: absolute;
    top: -10px;
    right: -10px;
    font-size: 6rem;
    opacity: 0.1;
    transform: rotate(-15deg);
}

.education-record:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(124, 58, 237, 0.2);
    border-color: #c4b5fd;
}

.education-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

.education-institution {
    font-size: 1.25rem;
    font-weight: 700;
    color: #5b21b6;
    margin-bottom: 0.25rem;
}

.education-degree {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.375rem 0.875rem;
    background: white;
    border: 2px solid #c4b5fd;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 700;
    color: #6d28d9;
}

.education-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
    position: relative;
    z-index: 1;
}

.education-detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b21a8;
    font-size: 0.9375rem;
}

.education-detail-item i {
    color: #7c3aed;
}

.education-actions {
    display: flex;
    gap: 0.5rem;
}

/* Action Buttons Enhancement */
.btn-modern {
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.btn-primary-modern {
    background: linear-gradient(135deg, #004135, #11684d);
}

.btn-secondary-modern {
    background: white;
    color: #004135;
    border: 2px solid #e5e7eb;
}

.btn-secondary-modern:hover {
    border-color: #004135;
    background: #f9fafb;
}

/* Responsive Design */
@media (max-width: 992px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
        padding: 2rem;
    }
    
    .profile-info h1 {
        font-size: 2rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .nav-tabs-modern {
        overflow-x: auto;
        flex-wrap: nowrap;
    }
}

@media (max-width: 768px) {
    .profile-avatar-large {
        width: 100px;
        height: 100px;
    }
    
    .profile-info h1 {
        font-size: 1.75rem;
    }
    
    .info-card {
        padding: 1.5rem;
    }
    
    .education-details {
        grid-template-columns: 1fr;
    }
}

/* Animation Classes */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.fade-in {
    animation: fadeIn 0.6s ease-out;
}
</style>

<div class="profile-container fade-in">
    
    <!-- Enhanced Profile Header -->
    <div class="profile-header">
        <img src="<?= Html::encode($imageUrl) ?>" alt="Profile Picture" class="profile-avatar-large">
        <div class="profile-info">
            <h1><?= Html::encode($model->full_name ?: 'Complete Your Profile') ?></h1>
            <span class="role-badge">
                <i class="bi bi-person-badge-fill"></i>
                <?= Html::encode($userRole) ?>
            </span>
            <p class="mt-2 mb-0" style="opacity: 0.95; font-size: 1rem;">
                <i class="bi bi-envelope-fill"></i>
                <?= Html::encode(Yii::$app->user->identity->email) ?>
            </p>
            
            <!-- Profile Completion Progress -->
            <div class="profile-completion">
                <div class="completion-label">
                    <span>Profile Completion</span>
                    <span class="completion-percentage"><?= $completionPercentage ?>%</span>
                </div>
                <div class="progress-bar-wrapper">
                    <div class="progress-bar-fill" style="width: <?= $completionPercentage ?>%;"></div>
                </div>
            </div>
        </div>
        <div class="ms-auto" style="position: relative; z-index: 1;">
            <?= Html::a('<i class="bi bi-pencil-square"></i> Edit Profile', ['profile'], [
                'class' => 'btn-modern btn-secondary-modern'
            ]) ?>
        </div>
    </div>

    <!-- Enhanced Tab Navigation - UPDATED WITH ROLE-BASED ACCESS -->
    <ul class="nav nav-tabs-modern" id="profileTabs" role="tablist">
        <!-- Personal Info - Available to ALL roles (Admin, Teacher, Parent) -->
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button">
                <i class="bi bi-person-fill"></i> Personal Info
            </button>
        </li>
        
        <!-- Employment - Only for Parents -->
        <?php if ($isParent): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="job-tab" data-bs-toggle="tab" data-bs-target="#job" type="button">
                <i class="bi bi-briefcase-fill"></i> Employment
            </button>
        </li>
        <?php endif; ?>
        
        <!-- Education - Only for Teachers -->
        <?php if ($isTeacher): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education" type="button">
                <i class="bi bi-mortarboard-fill"></i> Education
            </button>
        </li>
        <?php endif; ?>
        
        <!-- Partner Info - Only for Parents -->
        <?php if ($isParent): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="partner-tab" data-bs-toggle="tab" data-bs-target="#partner" type="button">
                <i class="bi bi-people-fill"></i> Partner Info
            </button>
        </li>
        <?php endif; ?>
        
        <!-- Documents - Only for Teachers and Parents -->
        <?php if ($isTeacher || $isParent): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button">
                <i class="bi bi-file-earmark-text-fill"></i> My Documents
            </button>
        </li>
        <?php endif; ?>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="profileTabsContent">
        
        <!-- Personal Information Tab - AVAILABLE TO ALL ROLES -->
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

        <!-- Employment Tab - ONLY FOR PARENTS -->
        <?php if ($isParent): ?>
        <div class="tab-pane fade" id="job" role="tabpanel">
            <?php if ($model->userJobDetails): ?>
                <div class="info-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="info-card-title mb-0">
                            <i class="bi bi-briefcase-fill"></i>
                            Employment Details
                        </h3>
                        <?= Html::a('<i class="bi bi-pencil"></i> Edit', ['user-job-details/profile'], [
                            'class' => 'btn-modern btn-secondary-modern'
                        ]) ?>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Job/Occupation</div>
                            <div class="info-value"><?= Html::encode($model->userJobDetails->job ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Job Title</div>
                            <div class="info-value"><?= Html::encode($model->userJobDetails->job_title ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Department</div>
                            <div class="info-value"><?= Html::encode($model->userJobDetails->department ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Employer</div>
                            <div class="info-value"><?= Html::encode($model->userJobDetails->employer ?: '-') ?></div>
                        </div>
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">Employer Address</div>
                            <div class="info-value"><?= Html::encode($model->userJobDetails->employer_address ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Employer Phone</div>
                            <div class="info-value"><?= Html::encode($model->userJobDetails->employer_phone_number ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Employment Type</div>
                            <div class="info-value"><?= Html::encode($model->userJobDetails->employment_type ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Employment Status</div>
                            <div class="info-value"><?= Html::encode($model->userJobDetails->employment_status ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Gross Salary</div>
                            <div class="info-value">RM <?= $model->userJobDetails->gross_salary ? number_format($model->userJobDetails->gross_salary, 2) : '-' ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Net Salary</div>
                            <div class="info-value">RM <?= $model->userJobDetails->net_salary ? number_format($model->userJobDetails->net_salary, 2) : '-' ?></div>
                        </div>
                        <?php if ($model->userJobDetails->other_income): ?>
                        <div class="info-item">
                            <div class="info-label">Other Income</div>
                            <div class="info-value">RM <?= number_format($model->userJobDetails->other_income, 2) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Other Income Source</div>
                            <div class="info-value"><?= Html::encode($model->userJobDetails->other_income_source ?: '-') ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state-card">
                    <i class="bi bi-briefcase empty-state-icon"></i>
                    <h4>No Employment Information</h4>
                    <p>Add your employment details to complete your profile.</p>
                    <?= Html::a('<i class="bi bi-plus-circle"></i> Add Employment Info', ['user-job-details/profile'], [
                        'class' => 'btn-modern btn-primary-modern'
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Teacher Education Tab - ONLY FOR TEACHERS -->
        <?php if ($isTeacher): ?>
        <div class="tab-pane fade" id="education" role="tabpanel">
            <div class="info-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="info-card-title mb-0">
                        <i class="bi bi-mortarboard-fill"></i>
                        Educational Qualifications
                    </h3>
                    <?= Html::a('<i class="bi bi-plus-circle"></i> Add Education', ['teachers-education/create'], [
                        'class' => 'btn-modern btn-primary-modern'
                    ]) ?>
                </div>

                <?php if (!empty($teacherEducations)): ?>
                    <?php foreach ($teacherEducations as $education): ?>
                        <div class="education-record">
                            <div class="education-header">
                                <div>
                                    <div class="education-institution">
                                        <?= Html::encode($education->institution_name) ?>
                                    </div>
                                    <span class="education-degree">
                                        <i class="bi bi-award-fill"></i>
                                        <?= Html::encode($education->displayDegreeLevel()) ?>
                                    </span>
                                </div>
                                <div class="education-actions">
                                    <?= Html::a('<i class="bi bi-pencil"></i>', ['teachers-education/update', 'id' => $education->education_id], [
                                        'class' => 'btn btn-sm btn-outline-primary',
                                        'title' => 'Edit'
                                    ]) ?>
                                    <?= Html::a('<i class="bi bi-trash"></i>', ['teachers-education/delete', 'id' => $education->education_id], [
                                        'class' => 'btn btn-sm btn-outline-danger',
                                        'title' => 'Delete',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this education record?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </div>
                            </div>
                            
                            <div class="education-details">
                                <div class="education-detail-item">
                                    <i class="bi bi-book-fill"></i>
                                    <span><strong>Field:</strong> <?= Html::encode($education->field_of_study) ?></span>
                                </div>
                                <?php if ($education->graduation_date): ?>
                                <div class="education-detail-item">
                                    <i class="bi bi-calendar-check-fill"></i>
                                    <span><strong>Graduated:</strong> <?= Yii::$app->formatter->asDate($education->graduation_date, 'php:F Y') ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state-card">
                        <i class="bi bi-mortarboard empty-state-icon"></i>
                        <h4>No Education Records</h4>
                        <p>Add your educational qualifications to verify your teaching credentials.</p>
                        <?= Html::a('<i class="bi bi-plus-circle"></i> Add Education', ['teachers-education/create'], [
                            'class' => 'btn-modern btn-primary-modern'
                        ]) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Partner Tab - ONLY FOR PARENTS -->
        <?php if ($isParent): ?>
        <div class="tab-pane fade" id="partner" role="tabpanel">
            <?php if ($model->partnerDetails): ?>
                <div class="info-card">
                    <div class="partner-header">
                        <img src="<?= Html::encode($partnerImageUrl) ?>" alt="Partner" class="partner-avatar">
                        <div class="partner-info flex-grow-1">
                            <h3><?= Html::encode($model->partnerDetails->partner_name) ?></h3>
                            <p><i class="bi bi-person-badge"></i> <?= Html::encode($model->partnerDetails->partner_ic_number) ?></p>
                        </div>
                        <?= Html::a('<i class="bi bi-pencil"></i> Edit', ['partner-details/profile'], [
                            'class' => 'btn-modern btn-secondary-modern'
                        ]) ?>
                    </div>
                    
                    <h3 class="info-card-title">
                        <i class="bi bi-info-circle"></i>
                        Partner Details
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_phone_number ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Citizenship</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_citizenship ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Marital Status</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_marital_status ?: '-') ?></div>
                        </div>
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">Address</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_address ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">City</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_city ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Postcode</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_postcode ?: '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">State</div>
                            <div class="info-value"><?= Html::encode($model->partnerDetails->partner_state ?: '-') ?></div>
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
                                <div class="info-value"><?= Html::encode($model->partnerDetails->partnerJob->partner_job ?: '-') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Employer</div>
                                <div class="info-value"><?= Html::encode($model->partnerDetails->partnerJob->partner_employer ?: '-') ?></div>
                            </div>
                            <div class="info-item" style="grid-column: 1 / -1;">
                                <div class="info-label">Employer Address</div>
                                <div class="info-value"><?= Html::encode($model->partnerDetails->partnerJob->partner_employer_address ?: '-') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Employer Phone</div>
                                <div class="info-value"><?= Html::encode($model->partnerDetails->partnerJob->partner_employer_phone_number ?: '-') ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Gross Salary</div>
                                <div class="info-value">RM <?= $model->partnerDetails->partnerJob->partner_gross_salary ? number_format($model->partnerDetails->partnerJob->partner_gross_salary, 2) : '-' ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Net Salary</div>
                                <div class="info-value">RM <?= $model->partnerDetails->partnerJob->partner_net_salary ? number_format($model->partnerDetails->partnerJob->partner_net_salary, 2) : '-' ?></div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state-card">
                        <i class="bi bi-briefcase empty-state-icon"></i>
                        <h4>No Partner Employment Info</h4>
                        <p>Add your partner's employment details for complete family records.</p>
                        <?= Html::a('<i class="bi bi-plus-circle"></i> Add Partner Job', ['partner-job/profile'], [
                            'class' => 'btn-modern btn-primary-modern'
                        ]) ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="empty-state-card">
                    <i class="bi bi-people empty-state-icon"></i>
                    <h4>No Partner Information</h4>
                    <p>Add your partner's details to complete your family profile.</p>
                    <?= Html::a('<i class="bi bi-plus-circle"></i> Add Partner Info', ['partner-details/profile'], [
                        'class' => 'btn-modern btn-primary-modern'
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Documents Tab - ONLY FOR TEACHERS AND PARENTS -->
        <?php if ($isTeacher || $isParent): ?>
        <div class="tab-pane fade" id="documents" role="tabpanel">
            <?php
            $userId = Yii::$app->user->id;
            $categories = \app\models\DocumentCategory::getActiveCategories($userRole);
            $uploadedDocuments = \app\models\UserDocuments::find()
                ->where(['user_id' => $userId])
                ->with('category')
                ->all();
            ?>
            <?php if ($uploadedDocuments): ?>
                <div class="info-card">
                    <h3 class="info-card-title">
                        <i class="bi bi-files"></i>
                        Uploaded Documents
                    </h3>
                    <div class="info-grid">
                        <?php foreach ($uploadedDocuments as $doc): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <?= Html::encode($doc->category->category_name ?? 'Unknown Category') ?>
                                </div>
                                <div class="info-value d-flex justify-content-between align-items-center">
                                    <span><?= basename($doc->file_url) ?></span>
                                    <div>
                                        <?= Html::a('<i class="bi bi-eye"></i>', Yii::getAlias('@web/' . $doc->file_url), [
                                            'class' => 'btn btn-sm btn-info',
                                            'target' => '_blank',
                                            'title' => 'View'
                                        ]) ?>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <?php
                                    $statusBadges = [
                                        'Completed' => '<span class="badge bg-success">Approved</span>',
                                        'Pending Review' => '<span class="badge bg-warning">Pending</span>',
                                        'Rejected' => '<span class="badge bg-danger">Rejected</span>',
                                    ];
                                    echo $statusBadges[$doc->status] ?? '';
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-3 text-center">
                        <?= Html::a('<i class="bi bi-plus-circle"></i> Manage Documents', ['/user-documents/my-documents'], [
                            'class' => 'btn-modern btn-primary-modern'
                        ]) ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state-card">
                    <i class="bi bi-file-earmark-x empty-state-icon"></i>
                    <h4>No Documents Uploaded</h4>
                    <p>Upload your required documents to complete your profile.</p>
                    <?= Html::a('<i class="bi bi-upload"></i> Upload Documents', ['/user-documents/my-documents'], [
                        'class' => 'btn-modern btn-primary-modern'
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
// Smooth scroll to active tab content
document.querySelectorAll('.nav-tabs-modern .nav-link').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function (event) {
        event.target.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
});

// Add entrance animation to info cards when tab is shown
document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function (event) {
        const target = document.querySelector(event.target.getAttribute('data-bs-target'));
        const cards = target.querySelectorAll('.info-card, .empty-state-card, .education-record');
        
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.4s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
});

// Animate progress bar on page load
window.addEventListener('load', function() {
    const progressBar = document.querySelector('.progress-bar-fill');
    if (progressBar) {
        const targetWidth = progressBar.style.width;
        progressBar.style.width = '0%';
        setTimeout(() => {
            progressBar.style.width = targetWidth;
        }, 300);
    }
});
</script>