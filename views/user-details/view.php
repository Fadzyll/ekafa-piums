<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UserDetails $model */

$this->title = 'View Profile';
$this->params['breadcrumbs'][] = $this->title;

// Default profile image path
$defaultImage = Yii::getAlias('@web/images/default_profile.png');

// Safe image URL helper
function safeImageUrl($relativePath, $defaultImage) {
    if (!$relativePath) return $defaultImage;
    $relative = ltrim($relativePath, '/');
    $filePath = Yii::getAlias('@webroot/' . $relative);
    return file_exists($filePath)
        ? Yii::getAlias('@web/' . $relative)
        : $defaultImage;
}

// User & Partner Images
$imageUrl = safeImageUrl($model->profile_picture_url ?? null, $defaultImage);
$partnerImageUrl = $model->partnerDetails && $model->partnerDetails->profile_picture_url
    ? safeImageUrl($model->partnerDetails->profile_picture_url, $defaultImage)
    : $defaultImage;
?>

<div class="container mt-4">

    <!-- USER INFORMATION CARD -->
    <div class="card shadow mb-5">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">User Information</h3>
            <div class="d-flex gap-2">
                <?= Html::a('Update Profile', ['profile'], ['class' => 'btn btn-light']) ?>
                <?= Html::a(
                    $model->userJob ? 'Update Job' : 'Add Job',
                    ['user-job/profile'],
                    ['class' => 'btn btn-light']
                ) ?>
            </div>
        </div>

        <div class="card-body">
            <div class="row align-items-center">
                <!-- Profile Picture -->
                <div class="col-md-4 text-center mb-3">
                    <img src="<?= Html::encode($imageUrl) ?>"
                         class="img-thumbnail rounded-circle mb-3 shadow-sm"
                         style="width: 150px; height: 150px; object-fit: cover;">
                </div>

                <!-- User Information -->
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Full Name:</strong> <?= Html::encode($model->full_name) ?></p>
                            <p><strong>IC Number:</strong> <?= Html::encode($model->ic_number) ?></p>
                            <p><strong>Age:</strong> <?= Html::encode($model->age) ?></p>
                            <p><strong>Gender:</strong> <?= Html::encode($model->gender) ?></p>
                            <p><strong>Race:</strong> <?= Html::encode($model->race) ?></p>
                            <p><strong>Phone Number:</strong> <?= Html::encode($model->phone_number) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Citizenship:</strong> <?= Html::encode($model->citizenship) ?></p>
                            <p><strong>Marital Status:</strong> <?= Html::encode($model->marital_status) ?></p>
                            <p><strong>Address:</strong> <?= Html::encode($model->address) ?></p>
                            <p><strong>City:</strong> <?= Html::encode($model->city) ?></p>
                            <p><strong>Postcode:</strong> <?= Html::encode($model->postcode) ?></p>
                            <p><strong>State:</strong> <?= Html::encode($model->state) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- USER JOB INFORMATION -->
            <hr class="my-4">
            <h5 class="text-dark mb-3">Job Information</h5>

            <?php if ($model->userJob): ?>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Job Title:</strong> <?= Html::encode($model->userJob->job) ?></p>
                        <p><strong>Employer:</strong> <?= Html::encode($model->userJob->employer) ?></p>
                        <p><strong>Employer Address:</strong> <?= Html::encode($model->userJob->employer_address) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Employer Phone:</strong> <?= Html::encode($model->userJob->employer_phone_number) ?></p>
                        <p><strong>Gross Salary:</strong> RM <?= Html::encode($model->userJob->gross_salary) ?></p>
                        <p><strong>Net Salary:</strong> RM <?= Html::encode($model->userJob->net_salary) ?></p>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-muted fst-italic">
                    No job information available. Click "Add Job" to complete your profile.
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- PARTNER INFORMATION CARD -->
    <div class="card shadow mb-5">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Partner Information</h4>
            <div class="d-flex gap-2">
                <?= Html::a(
                    $model->partnerDetails ? 'Update Partner' : 'Add Partner',
                    ['partner-details/profile'],
                    ['class' => 'btn btn-light']
                ) ?>

                <?php if ($model->partnerDetails): ?>
                    <?= Html::a(
                        $model->partnerDetails->partnerJob ? 'Update Partner Job' : 'Add Partner Job',
                        ['partner-job/profile'],
                        ['class' => 'btn btn-light']
                    ) ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body">
            <?php if ($model->partnerDetails): ?>
                <div class="row align-items-center">
                    <div class="col-md-4 text-center mb-3">
                        <img src="<?= Html::encode($partnerImageUrl) ?>"
                             class="img-thumbnail rounded-circle mb-3 shadow-sm"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> <?= Html::encode($model->partnerDetails->partner_name) ?></p>
                                <p><strong>IC Number:</strong> <?= Html::encode($model->partnerDetails->partner_ic_number) ?></p>
                                <p><strong>Phone:</strong> <?= Html::encode($model->partnerDetails->partner_phone_number) ?></p>
                                <p><strong>Citizenship:</strong> <?= Html::encode($model->partnerDetails->partner_citizenship) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Marital Status:</strong> <?= Html::encode($model->partnerDetails->partner_marital_status) ?></p>
                                <p><strong>Address:</strong> <?= Html::encode($model->partnerDetails->partner_address) ?></p>
                                <p><strong>City:</strong> <?= Html::encode($model->partnerDetails->partner_city) ?></p>
                                <p><strong>Postcode:</strong> <?= Html::encode($model->partnerDetails->partner_postcode) ?></p>
                                <p><strong>State:</strong> <?= Html::encode($model->partnerDetails->partner_state) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Partner Job Info -->
                <hr class="my-4">
                <h5 class="text-dark mb-3">Partner Job Information</h5>

                <?php if ($model->partnerDetails->partnerJob): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Occupation:</strong> <?= Html::encode($model->partnerDetails->partnerJob->partner_job) ?></p>
                            <p><strong>Employer:</strong> <?= Html::encode($model->partnerDetails->partnerJob->partner_employer) ?></p>
                            <p><strong>Employer Address:</strong> <?= Html::encode($model->partnerDetails->partnerJob->partner_employer_address) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Employer Phone:</strong> <?= Html::encode($model->partnerDetails->partnerJob->partner_employer_phone_number) ?></p>
                            <p><strong>Gross Salary:</strong> RM <?= Html::encode($model->partnerDetails->partnerJob->partner_gross_salary) ?></p>
                            <p><strong>Net Salary:</strong> RM <?= Html::encode($model->partnerDetails->partnerJob->partner_net_salary) ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted fst-italic">
                        No partner job information available. Click "Add Partner Job" to complete partner details.
                    </p>
                <?php endif; ?>

            <?php else: ?>
                <p class="text-muted fst-italic">
                    No partner information available. Click "Add Partner" to complete your profile.
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>