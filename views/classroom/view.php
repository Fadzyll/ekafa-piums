<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */

$this->title = $model->class_name;
$this->params['breadcrumbs'][] = ['label' => 'Classroom Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);

// Calculate statistics
$enrollmentPercentage = $model->quota > 0 ? round(($model->current_enrollment / $model->quota) * 100, 2) : 0;
$availableSlots = max(0, $model->quota - $model->current_enrollment);

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
                    <i class="fas fa-graduation-cap me-2"></i>
                    <?= Html::encode($model->grade_level ?? 'Not specified') ?> • 
                    <?= Html::encode($model->session_type) ?> Session • 
                    Year <?= Html::encode($model->year) ?>
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
                $progressColor = $enrollmentPercentage >= 90 ? 'danger' : ($enrollmentPercentage >= 70 ? 'warning' : 'success');
                ?>
                <div class="progress-bar bg-<?= $progressColor ?>" style="width: <?= $enrollmentPercentage ?>%">
                    <?= $enrollmentPercentage ?>%
                </div>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-box-icon text-success">
                <i class="fas fa-chair"></i>
            </div>
            <div class="stat-box-value text-success">
                <?= $availableSlots ?>
            </div>
            <p class="stat-box-label">Available Slots</p>
        </div>

        <div class="stat-box">
            <div class="stat-box-icon text-info">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-box-value text-info">
                RM <?= $model->monthly_fee ? number_format($model->monthly_fee, 2) : '0.00' ?>
            </div>
            <p class="stat-box-label">Monthly Fee</p>
        </div>

        <div class="stat-box">
            <div class="stat-box-icon text-warning">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-box-value text-warning">
                <?= $model->minimum_enrollment ?? 0 ?>
            </div>
            <p class="stat-box-label">Minimum Required</p>
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
                <?= Html::a('<i class="fas fa-copy me-2"></i>Duplicate', ['create', 'copy_from' => $model->class_id], [
                    'class' => 'btn btn-info btn-action text-white'
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
                            <?= Html::encode($model->class_id) ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-book text-primary"></i>
                            Class Name
                        </div>
                        <div class="detail-value">
                            <?= Html::encode($model->class_name) ?>
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
                            <i class="fas fa-align-left text-primary"></i>
                            Description
                        </div>
                        <div class="detail-value">
                            <?= $model->description ? nl2br(Html::encode($model->description)) : '<span class="empty-value">No description provided</span>' ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-bullseye text-primary"></i>
                            Objectives
                        </div>
                        <div class="detail-value">
                            <?= $model->objectives ? nl2br(Html::encode($model->objectives)) : '<span class="empty-value">No objectives specified</span>' ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-tasks text-primary"></i>
                            Prerequisites
                        </div>
                        <div class="detail-value">
                            <?= $model->prerequisites ? nl2br(Html::encode($model->prerequisites)) : '<span class="empty-value">No prerequisites</span>' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Card -->
            <div class="card info-card">
                <div class="card-header-custom">
                    <i class="fas fa-calendar-alt"></i>Schedule & Timing
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-sun text-warning"></i>
                            Session Type
                        </div>
                        <div class="detail-value">
                            <span class="badge bg-<?= $model->isSessionTypeMorning() ? 'warning' : 'info' ?>">
                                <i class="fas fa-<?= $model->isSessionTypeMorning() ? 'sun' : 'moon' ?> me-1"></i>
                                <?= Html::encode($model->session_type) ?>
                            </span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-clock text-success"></i>
                            Class Time
                        </div>
                        <div class="detail-value">
                            <?php if ($model->start_time && $model->end_time): ?>
                                <?= date('g:i A', strtotime($model->start_time)) ?> - <?= date('g:i A', strtotime($model->end_time)) ?>
                            <?php else: ?>
                                <span class="empty-value">Not specified</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-calendar-week text-info"></i>
                            Days of Week
                        </div>
                        <div class="detail-value">
                            <?= $model->days_of_week ? Html::encode($model->days_of_week) : '<span class="empty-value">Not specified</span>' ?>
                        </div>
                    </div>
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
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-6">
            <!-- Staff Card -->
            <div class="card info-card">
                <div class="card-header-custom">
                    <i class="fas fa-chalkboard-teacher"></i>Teaching Staff
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-user-tie text-primary"></i>
                            Primary Teacher
                        </div>
                        <div class="detail-value">
                            <?= $model->user ? '<strong>' . Html::encode($model->user->username) . '</strong>' : '<span class="empty-value">Not assigned</span>' ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-user text-secondary"></i>
                            Assistant Teacher
                        </div>
                        <div class="detail-value">
                            <?= $model->assistantTeacher ? '<strong>' . Html::encode($model->assistantTeacher->username) . '</strong>' : '<span class="empty-value">Not assigned</span>' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Card -->
            <div class="card info-card">
                <div class="card-header-custom">
                    <i class="fas fa-map-marker-alt"></i>Location Details
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-building text-primary"></i>
                            Building
                        </div>
                        <div class="detail-value">
                            <?= $model->building ? Html::encode($model->building) : '<span class="empty-value">Not specified</span>' ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-layer-group text-info"></i>
                            Floor
                        </div>
                        <div class="detail-value">
                            <?= $model->floor !== null ? Html::encode($model->floor) : '<span class="empty-value">Not specified</span>' ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-door-open text-success"></i>
                            Classroom Location
                        </div>
                        <div class="detail-value">
                            <?= $model->classroom_location ? Html::encode($model->classroom_location) : '<span class="empty-value">Not specified</span>' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollment Card -->
            <div class="card info-card">
                <div class="card-header-custom">
                    <i class="fas fa-user-graduate"></i>Enrollment & Age Requirements
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-child text-primary"></i>
                            Age Range
                        </div>
                        <div class="detail-value">
                            <?php if ($model->min_age !== null || $model->max_age !== null): ?>
                                <?= $model->min_age ?? 'Any' ?> - <?= $model->max_age ?? 'Any' ?> years
                            <?php else: ?>
                                <span class="empty-value">Not specified</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-calendar-day text-info"></i>
                            Enrollment Period
                        </div>
                        <div class="detail-value">
                            <?php if ($model->enrollment_start_date && $model->enrollment_end_date): ?>
                                <?= Yii::$app->formatter->asDate($model->enrollment_start_date, 'medium') ?> to 
                                <?= Yii::$app->formatter->asDate($model->enrollment_end_date, 'medium') ?>
                            <?php else: ?>
                                <span class="empty-value">Not set</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-list-ol text-warning"></i>
                            Waiting List
                        </div>
                        <div class="detail-value">
                            <?= $model->waiting_list_count ?? 0 ?> student(s)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fees Card -->
            <div class="card info-card">
                <div class="card-header-custom">
                    <i class="fas fa-dollar-sign"></i>Fee Structure
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-money-bill-wave text-success"></i>
                            Monthly Fee
                        </div>
                        <div class="detail-value">
                            <strong>RM <?= $model->monthly_fee ? number_format($model->monthly_fee, 2) : '0.00' ?></strong>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-file-invoice-dollar text-info"></i>
                            Registration Fee
                        </div>
                        <div class="detail-value">
                            <strong>RM <?= $model->registration_fee ? number_format($model->registration_fee, 2) : '0.00' ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Administrative Information -->
    <?php if ($model->admin_notes || $model->created_by || $model->created_at): ?>
    <div class="card info-card">
        <div class="card-header-custom">
            <i class="fas fa-cog"></i>Administrative Information
        </div>
        <div class="card-body p-0">
            <?php if ($model->admin_notes): ?>
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-sticky-note text-warning"></i>
                    Admin Notes
                </div>
                <div class="detail-value">
                    <?= nl2br(Html::encode($model->admin_notes)) ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-user-shield text-primary"></i>
                    Created By
                </div>
                <div class="detail-value">
                    <?= $model->createdBy ? Html::encode($model->createdBy->username) : '<span class="empty-value">Unknown</span>' ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-clock text-info"></i>
                    Created At
                </div>
                <div class="detail-value">
                    <?= $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at, 'long') : '<span class="empty-value">Unknown</span>' ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-sync text-success"></i>
                    Last Updated
                </div>
                <div class="detail-value">
                    <?= $model->updated_at ? Yii::$app->formatter->asDatetime($model->updated_at, 'long') : '<span class="empty-value">Unknown</span>' ?>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">
                    <i class="fas fa-eye text-secondary"></i>
                    Visibility
                </div>
                <div class="detail-value">
                    <span class="badge bg-<?= $model->is_visible ? 'success' : 'secondary' ?>">
                        <?= $model->is_visible ? 'Visible to Students' : 'Hidden' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>