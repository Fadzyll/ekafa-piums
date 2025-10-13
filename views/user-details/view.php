<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UserDetails $model */

$this->title = 'View Profile';
$this->params['breadcrumbs'][] = $this->title;

$defaultImage = Yii::getAlias('@web/images/default_profile.png');
$relativePath = ltrim($model->profile_picture_url, '/');
$uploadedPath = Yii::getAlias('@webroot/' . $relativePath);
$imageUrl = (isset($model->profile_picture_url) && file_exists($uploadedPath))
    ? Yii::getAlias('@web/' . $relativePath)
    : $defaultImage;
?>

<div class="container mt-4">
    <!-- Profile Card -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
            <?= Html::a('Update Profile', ['profile'], ['class' => 'btn btn-light']) ?>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Profile Picture -->
                <div class="col-md-4 text-center mb-3">
                    <img src="<?= Html::encode($imageUrl) ?>"
                         class="img-thumbnail rounded-circle mb-3"
                         style="width: 180px; height: 180px; object-fit: cover;">
                </div>

                <!-- User Info -->
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
        </div>
    </div>

    <!-- Job Information Card -->
    <div class="card shadow">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Job Information</h4>
            <?= Html::a(
                $model->userJob ? 'Update Job' : 'Add Job',
                ['user-job/profile'],
                ['class' => 'btn btn-light']
            ) ?>
        </div>

        <div class="card-body">
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
                <p class="text-muted mb-0 fst-italic">
                    No job information available. Click "Add Job" to complete your profile.
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>