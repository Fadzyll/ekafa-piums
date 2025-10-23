<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */

$this->title = $model->class_name;
$this->params['breadcrumbs'][] = ['label' => 'Classroom Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);

// Register CSS
$this->registerCss("
    .classroom-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px 15px 0 0;
        margin-bottom: 2rem;
    }
    .classroom-header h1 {
        margin: 0;
        font-weight: 700;
    }
    .classroom-header .subtitle {
        opacity: 0.9;
        margin-top: 0.5rem;
    }
    .info-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    }
    .card-header-custom {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 3px solid #667eea;
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: #495057;
    }
    .card-header-custom i {
        margin-right: 0.5rem;
        color: #667eea;
    }
    .detail-row {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-weight: 600;
        color: #6c757d;
        min-width: 200px;
        display: flex;
        align-items: center;
    }
    .detail-label i {
        margin-right: 0.5rem;
        width: 20px;
        text-align: center;
    }
    .detail-value {
        color: #495057;
        flex: 1;
    }
    .status-badge-large {
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-block;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .stat-box {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        text-align: center;
    }
    .stat-box-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    .stat-box-value {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }
    .stat-box-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin: 0;
    }
    .action-bar {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    .progress-enrollment {
        height: 30px;
        border-radius: 15px;
        background-color: #e9ecef;
    }
    .progress-enrollment .progress-bar {
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    .btn-action {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 0.25rem;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .empty-value {
        color: #adb5bd;
        font-style: italic;
    }
");
?>

<div class="classroom-view-container">
    <!-- Header Section -->
    <div class="classroom-header shadow">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1>
                    <i class="fas fa-school me-2"></i>
                    <?= Html::encode($model->class_name) ?>
                </h1>
                <div class="subtitle">
                    <?php if ($model->grade_level): ?>
                        <i class="fas fa-graduation-cap me-2"></i>
                        <?= Html::encode($model->grade_level) ?> • 
                    <?php endif; ?>
                    <i class="fas fa-calendar me-2"></i>
                    Year <?= Html::encode($model->year) ?> • 
                    <i class="fas fa-<?= $model->isSessionTypeMorning() ? 'sun' : 'moon' ?> me-2"></i>
                    <?= Html::encode($model->session_type) ?> Session
                </div>
            </div>
            <div>
                <?php
                $statusColors = [
                    'Draft' => 'secondary',
                    'Open' => 'success',
                    'Closed' => 'danger',
                    'Full' => 'warning',
                    'In Progress' => 'info',
                    'Completed' => 'primary',
                    'Cancelled' => 'dark',
                ];
                $color = $statusColors[$model->status] ?? 'secondary';
                ?>
                <span class="status-badge-large bg-<?= $color ?> text-white">
                    <?= Html::encode($model->status) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-box-icon text-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-box-value text-primary">
                <?= $model->current_enrollment ?>/<?= $model->quota ?>
            </div>
            <p class="stat-box-label">Current Enrollment</p>
            <div class="progress progress-enrollment mt-2">
                <?php
                $percentage = $model->getEnrollmentPercentage();
                $progressColor = $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success');
                ?>
                <div class="progress-bar bg-<?= $progressColor ?>" style="width: <?= $percentage ?>%">
                    <?= $percentage ?>%
                </div>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-box-icon text-success">
                <i class="fas fa-chair"></i>
            </div>
            <div class="stat-box-value text-success">
                <?= $model->getAvailableSlots() ?>
            </div>
            <p class="stat-box-label">Available Slots</p>
        </div>

        <div class="stat-box">
            <div class="stat-box-icon text-<?= $model->isFull() ? 'danger' : 'info' ?>">
                <i class="fas fa-<?= $model->isFull() ? 'lock' : 'unlock' ?>"></i>
            </div>
            <div class="stat-box-value text-<?= $model->isFull() ? 'danger' : 'info' ?>">
                <?= $model->isFull() ? 'FULL' : 'OPEN' ?>
            </div>
            <p class="stat-box-label">Enrollment Status</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <div>
                <?= Html::a('<i class="fas fa-arrow-left me-2"></i>Back to List', ['index'], [
                    'class' => 'btn btn-outline-secondary btn-action'
                ]) ?>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <?= Html::a('<i class="fas fa-edit me-2"></i>Edit', ['update', 'class_id' => $model->class_id], [
                    'class' => 'btn btn-primary btn-action'
                ]) ?>
                <?= Html::a('<i class="fas fa-trash me-2"></i>Delete', ['delete', 'class_id' => $model->class_id], [
                    'class' => 'btn btn-danger btn-action',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this classroom? This action cannot be undone.',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-6">
            <!-- Basic Information Card -->
            <div class="card info-card">
                <div class="card-header-custom">
                    <i class="fas fa-info-circle"></i>Basic Information
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-hashtag text-primary"></i>
                            Class ID
                        </div>
                        <div class="detail-value">
                            <strong><?= Html::encode($model->class_id) ?></strong>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-book text-primary"></i>
                            Class Name
                        </div>
                        <div class="detail-value">
                            <strong><?= Html::encode($model->class_name) ?></strong>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-calendar text-primary"></i>
                            Year
                        </div>
                        <div class="detail-value">
                            <strong><?= Html::encode($model->year) ?></strong>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-graduation-cap text-primary"></i>
                            Grade Level
                        </div>
                        <div class="detail-value">
                            <?= $model->grade_level ? Html::encode($model->grade_level) : '<span class="empty-value">Not specified</span>' ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-<?= $model->isSessionTypeMorning() ? 'sun' : 'moon' ?> text-warning"></i>
                            Session Type
                        </div>
                        <div class="detail-value">
                            <span class="badge bg-<?= $model->isSessionTypeMorning() ? 'warning' : 'info' ?>">
                                <?= Html::encode($model->session_type) ?>
                            </span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-toggle-<?= $model->status === 'Draft' ? 'off' : 'on' ?> text-info"></i>
                            Status
                        </div>
                        <div class="detail-value">
                            <span class="badge bg-<?= $color ?>">
                                <?= Html::encode($model->status) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Card -->
            <div class="card info-card">
                <div class="card-header-custom">
                    <i class="fas fa-calendar-alt"></i>Class Schedule
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-play-circle text-success"></i>
                            Class Start Date
                        </div>
                        <div class="detail-value">
                            <?= $model->class_start_date ? Yii::$app->formatter->asDate($model->class_start_date, 'long') : '<span class="empty-value">Not set</span>' ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-stop-circle text-danger"></i>
                            Class End Date
                        </div>
                        <div class="detail-value">
                            <?= $model->class_end_date ? Yii::$app->formatter->asDate($model->class_end_date, 'long') : '<span class="empty-value">Not set</span>' ?>
                        </div>
                    </div>
                    <?php if ($model->class_start_date && $model->class_end_date): ?>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-clock text-info"></i>
                            Duration
                        </div>
                        <div class="detail-value">
                            <?php
                            $start = new DateTime($model->class_start_date);
                            $end = new DateTime($model->class_end_date);
                            $interval = $start->diff($end);
                            $days = $interval->days;
                            $weeks = floor($days / 7);
                            $months = floor($days / 30);
                            
                            if ($months > 0) {
                                echo $months . ' month' . ($months > 1 ? 's' : '');
                            } elseif ($weeks > 0) {
                                echo $weeks . ' week' . ($weeks > 1 ? 's' : '');
                            } else {
                                echo $days . ' day' . ($days > 1 ? 's' : '');
                            }
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-6">
            <!-- Teacher Card -->
            <div class="card info-card">
                <div class="card-header-custom">
                    <i class="fas fa-chalkboard-teacher"></i>Teacher Information
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-user-tie text-primary"></i>
                            Teacher Name
                        </div>
                        <div class="detail-value">
                            <?= $model->user ? '<strong>' . Html::encode($model->user->username) . '</strong>' : '<span class="empty-value">Not assigned</span>' ?>
                        </div>
                    </div>
                    <?php if ($model->user && $model->user->email): ?>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-envelope text-info"></i>
                            Email
                        </div>
                        <div class="detail-value">
                            <a href="mailto:<?= Html::encode($model->user->email) ?>">
                                <?= Html::encode($model->user->email) ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Location Card -->
            <div class="card info-card">
                <div class="card-header-custom">
                    <i class="fas fa-map-marker-alt"></i>Location
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-door-open text-primary"></i>
                            Classroom Location
                        </div>
                        <div class="detail-value">
                            <?= $model->classroom_location ? '<strong>' . Html::encode($model->classroom_location) . '</strong>' : '<span class="empty-value">Not specified</span>' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollment Card -->
            <div class="card info-card">
                <div class="card-header-custom">
                    <i class="fas fa-user-graduate"></i>Enrollment Details
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-users text-primary"></i>
                            Maximum Capacity
                        </div>
                        <div class="detail-value">
                            <strong><?= $model->quota ?> students</strong>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-user-check text-success"></i>
                            Currently Enrolled
                        </div>
                        <div class="detail-value">
                            <strong><?= $model->current_enrollment ?> students</strong>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-chair text-info"></i>
                            Available Slots
                        </div>
                        <div class="detail-value">
                            <strong class="text-<?= $model->getAvailableSlots() > 0 ? 'success' : 'danger' ?>">
                                <?= $model->getAvailableSlots() ?> slots
                            </strong>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-percentage text-warning"></i>
                            Enrollment Rate
                        </div>
                        <div class="detail-value">
                            <strong><?= $model->getEnrollmentPercentage() ?>%</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timestamps Card -->
    <div class="card info-card">
        <div class="card-header-custom">
            <i class="fas fa-clock"></i>Record Information
        </div>
        <div class="card-body p-0">
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-plus-circle text-success"></i>
                    Created At
                </div>
                <div class="detail-value">
                    <?= $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at, 'long') : '<span class="empty-value">Unknown</span>' ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-sync text-info"></i>
                    Last Updated
                </div>
                <div class="detail-value">
                    <?= $model->updated_at ? Yii::$app->formatter->asDatetime($model->updated_at, 'long') : '<span class="empty-value">Unknown</span>' ?>
                </div>
            </div>
        </div>
    </div>
</div>