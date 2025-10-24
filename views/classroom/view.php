<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */

$this->title = $model->class_name;
$this->params['breadcrumbs'][] = ['label' => 'Classrooms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
\yii\web\YiiAsset::register($this);
?>

<style>
.view-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px 20px 0 0;
    padding: 2.5rem;
    color: white;
    margin-bottom: 0;
}

.view-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.view-subtitle {
    opacity: 0.9;
    font-size: 1rem;
    margin: 0;
}

.action-bar {
    background: white;
    padding: 1.5rem;
    border-bottom: 2px solid #f3f4f6;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-action-bar {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-back {
    background: #6b7280;
    color: white;
}

.btn-back:hover {
    background: #4b5563;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    color: white;
}

.btn-edit-action {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.btn-edit-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    color: white;
}

.btn-delete-action {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-delete-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    color: white;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 2rem;
}

.info-card {
    background: #f9fafb;
    border-radius: 16px;
    padding: 1.5rem;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.info-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.info-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6b7280;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
}

.stats-section {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin: 0 2rem 2rem 2rem;
    border: 2px solid #e5e7eb;
}

.stats-section h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.stat-item {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 1.5rem;
    color: white;
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
    font-weight: 600;
}

.enrollment-progress {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin: 0 2rem 2rem 2rem;
    border: 2px solid #e5e7eb;
}

.progress-bar-custom {
    height: 40px;
    border-radius: 20px;
    background-color: #e5e7eb;
    overflow: hidden;
    position: relative;
}

.progress-bar-fill {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: white;
    transition: width 0.6s ease;
}

.progress-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.progress-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.progress-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.875rem;
}

.status-open {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.status-closed {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.status-full {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.status-progress {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.status-draft {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
}

.session-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.875rem;
}

.session-morning {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
}

.session-afternoon {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.info-card, .stats-section, .enrollment-progress {
    animation: slideInUp 0.5s ease;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }

    .action-bar {
        flex-direction: column;
    }

    .btn-action-bar {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="classroom-view">
    <div class="card shadow-lg border-0">
        <!-- Header -->
        <div class="view-header">
            <div class="view-title">
                <i class="bi bi-door-open-fill"></i>
                <?= Html::encode($this->title) ?>
            </div>
            <p class="view-subtitle">
                Complete details and information about this classroom
            </p>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <?= Html::a('<i class="bi bi-arrow-left"></i> Back to List', ['index'], ['class' => 'btn btn-action-bar btn-back']) ?>
            <?= Html::a('<i class="bi bi-pencil-square"></i> Edit Classroom', ['update', 'class_id' => $model->class_id], ['class' => 'btn btn-action-bar btn-edit-action']) ?>
            <?= Html::a('<i class="bi bi-trash"></i> Delete', ['delete', 'class_id' => $model->class_id], [
                'class' => 'btn btn-action-bar btn-delete-action',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this classroom? This action cannot be undone.',
                    'method' => 'post',
                ],
            ]) ?>
        </div>

        <!-- Enrollment Progress -->
        <div class="enrollment-progress">
            <h4>
                <i class="bi bi-graph-up"></i>
                Enrollment Progress
            </h4>
            <?php
            $percentage = $model->getEnrollmentPercentage();
            $progressClass = $percentage >= 90 ? 'progress-danger' : ($percentage >= 70 ? 'progress-warning' : 'progress-success');
            ?>
            <div class="progress-bar-custom">
                <div class="progress-bar-fill <?= $progressClass ?>" style="width: <?= $percentage ?>%">
                    <?= $model->current_enrollment ?> / <?= $model->quota ?> Students (<?= $percentage ?>%)
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-between">
                <span><i class="bi bi-people"></i> <strong>Available Slots:</strong> <?= $model->getAvailableSlots() ?></span>
                <span>
                    <?php if ($model->isFull()): ?>
                        <span class="badge bg-danger">CLASS FULL</span>
                    <?php else: ?>
                        <span class="badge bg-success">ACCEPTING STUDENTS</span>
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <!-- Information Grid -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-hash"></i> Class ID
                </div>
                <div class="info-value"><?= Html::encode($model->class_id) ?></div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-door-open"></i> Class Name
                </div>
                <div class="info-value"><?= Html::encode($model->class_name) ?></div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-calendar"></i> Year
                </div>
                <div class="info-value"><?= Html::encode($model->year) ?></div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-mortarboard"></i> Grade Level
                </div>
                <div class="info-value">
                    <?= $model->grade_level ? Html::encode($model->grade_level) : '<em class="text-muted">Not specified</em>' ?>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-clock"></i> Session Type
                </div>
                <div class="info-value">
                    <?php
                    $isMorning = strtolower($model->session_type) === 'morning';
                    $badgeClass = $isMorning ? 'session-morning' : 'session-afternoon';
                    $icon = $isMorning ? 'bi-sun-fill' : 'bi-moon-fill';
                    ?>
                    <span class="session-badge <?= $badgeClass ?>">
                        <i class="bi <?= $icon ?>"></i>
                        <?= Html::encode($model->session_type) ?>
                    </span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-toggle-on"></i> Status
                </div>
                <div class="info-value">
                    <?php
                    $statusMap = [
                        'Open' => ['status-open', 'bi-door-open'],
                        'Closed' => ['status-closed', 'bi-door-closed'],
                        'Full' => ['status-full', 'bi-people-fill'],
                        'In Progress' => ['status-progress', 'bi-hourglass-split'],
                        'Draft' => ['status-draft', 'bi-file-earmark'],
                    ];
                    $statusInfo = $statusMap[$model->status] ?? ['status-draft', 'bi-circle'];
                    ?>
                    <span class="status-badge <?= $statusInfo[0] ?>">
                        <i class="bi <?= $statusInfo[1] ?>"></i>
                        <?= Html::encode($model->status) ?>
                    </span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-person-fill"></i> Assigned Teacher
                </div>
                <div class="info-value">
                    <?= $model->user ? Html::encode($model->user->username) : '<em class="text-muted">Not assigned</em>' ?>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-geo-alt"></i> Classroom Location
                </div>
                <div class="info-value">
                    <?= $model->classroom_location ? Html::encode($model->classroom_location) : '<em class="text-muted">Not specified</em>' ?>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-calendar-check"></i> Start Date
                </div>
                <div class="info-value">
                    <?= $model->class_start_date ? Yii::$app->formatter->asDate($model->class_start_date, 'long') : '<em class="text-muted">Not set</em>' ?>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-calendar-x"></i> End Date
                </div>
                <div class="info-value">
                    <?= $model->class_end_date ? Yii::$app->formatter->asDate($model->class_end_date, 'long') : '<em class="text-muted">Not set</em>' ?>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-calendar-plus"></i> Created At
                </div>
                <div class="info-value">
                    <?= $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : '<em class="text-muted">Unknown</em>' ?>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-clock-history"></i> Last Updated
                </div>
                <div class="info-value">
                    <?= $model->updated_at ? Yii::$app->formatter->asDatetime($model->updated_at) : '<em class="text-muted">Unknown</em>' ?>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <h4>
                <i class="bi bi-bar-chart-fill"></i>
                Classroom Statistics
            </h4>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">
                        <?= $model->quota ?>
                    </div>
                    <div class="stat-label">Student Quota</div>
                </div>
                <div class="stat-item" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="stat-number">
                        <?= $model->current_enrollment ?>
                    </div>
                    <div class="stat-label">Currently Enrolled</div>
                </div>
                <div class="stat-item" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="stat-number">
                        <?= $model->getAvailableSlots() ?>
                    </div>
                    <div class="stat-label">Available Slots</div>
                </div>
                <div class="stat-item" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <div class="stat-number">
                        <?= number_format($model->getEnrollmentPercentage(), 1) ?>%
                    </div>
                    <div class="stat-label">Enrollment Rate</div>
                </div>
            </div>
        </div>
    </div>
</div>
