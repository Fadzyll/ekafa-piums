<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\ClassroomModel;

$this->title = 'Classroom Management';
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
?>

<style>
.classroom-header {
    background: linear-gradient(135deg, #004135 0%, #11684d 50%, #00a86b 100%);
    border-radius: 20px 20px 0 0;
    padding: 2rem;
    color: white;
    margin-bottom: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.classroom-header h3 {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: white;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.filter-toggle-btn,
.btn-create-classroom {
    background: white;
    color: #003829;
    border: none;
    border-radius: 10px;
    padding: 0.6rem 1.2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-toggle-btn:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.filter-toggle-btn i {
    font-size: 1.1rem;
}

.btn-create-classroom {
    background: white;
    color: #003829;
}

.btn-create-classroom:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    color: #003829;
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    border: 2px solid #f3f4f6;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    border-color: #00b377;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #00b377;
    margin: 0;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Search Section Styles */
.search-filter-container {
    background: white;
    border-radius: 16px;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: all 0.3s ease;
}

.search-filter-container.collapsed {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.search-content {
    padding: 1.5rem;
    display: none;
}

.search-content.show {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.search-form-modern .form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.search-form-modern .form-group {
    margin-bottom: 0;
}

.search-form-modern label {
    font-weight: 600;
    color: #495057;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.search-form-modern label i {
    margin-right: 0.5rem;
    color: #00b377;
    font-size: 0.95rem;
}

.search-form-modern .form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.6rem 0.9rem;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.search-form-modern .form-control:focus {
    border-color: #00b377;
    box-shadow: 0 0 0 0.2rem rgba(0, 179, 119, 0.15);
}

.search-buttons {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.btn-search-modern {
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.btn-search-primary {
    background: linear-gradient(135deg, #003829 0%, #00563d 100%);
    color: white;
}

.btn-search-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 179, 119, 0.4);
}

.btn-search-secondary {
    background: #f3f4f6;
    color: #6b7280;
}

.btn-search-secondary:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
}

.classroom-table-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: #f9fafb;
    color: #374151;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding: 1rem;
    border-bottom: 2px solid #e5e7eb;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background: #f9fafb;
    transform: scale(1.01);
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.025em;
}

.badge-status-open {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.badge-status-closed {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.badge-status-full {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.badge-status-progress {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.badge-status-draft {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
}

.badge-status-completed {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
}

.badge-status-cancelled {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
}

.badge-session-morning {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
}

.badge-session-afternoon {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.action-buttons-cell {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-action {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.2s ease;
    border: none;
}

.btn-view {
    background: #3b82f6;
    color: white;
}

.btn-view:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.btn-edit {
    background: #f59e0b;
    color: white;
}

.btn-edit:hover {
    background: #d97706;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state i {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.empty-state h4 {
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #9ca3af;
}

.classroom-name-cell {
    font-weight: 600;
    color: #111827;
}

.teacher-name-cell {
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.enrollment-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.enrollment-text {
    font-weight: 600;
    color: #374151;
    min-width: 50px;
}

.progress-bar-wrapper {
    flex: 1;
    min-width: 80px;
}

.progress-thin {
    height: 8px;
    border-radius: 10px;
    background-color: #e5e7eb;
    overflow: hidden;
}

.progress-thin .progress-bar {
    height: 100%;
    border-radius: 10px;
    transition: width 0.3s ease;
}

.progress-bar-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.progress-bar-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.progress-bar-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
    }

    .classroom-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
        justify-content: space-between;
    }
}
</style>

<div class="classroom-index">
    <div class="card shadow-lg" style="border: none; overflow: hidden;">
        <div class="classroom-header">
            <h3>
                <i class="bi bi-door-open-fill"></i>
                Classroom Management
            </h3>
            <div class="header-actions">
                <button class="filter-toggle-btn" id="searchToggle">
                    <i class="bi bi-funnel"></i>
                    <span>Search & Filter</span>
                </button>
                <?= Html::a('<i class="bi bi-plus-circle"></i> Create New Classroom', ['create'], ['class' => 'btn-create-classroom']) ?>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Statistics Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-label">Total Classrooms</div>
                    <div class="stat-value"><?= $dataProvider->getTotalCount() ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Open</div>
                    <div class="stat-value" style="color: #10b981;">
                        <?= ClassroomModel::find()->where(['status' => ClassroomModel::STATUS_OPEN])->count() ?>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Full Classes</div>
                    <div class="stat-value" style="color: #f59e0b;">
                        <?= ClassroomModel::find()->where(['status' => ClassroomModel::STATUS_FULL])->count() ?>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">In Progress</div>
                    <div class="stat-value" style="color: #3b82f6;">
                        <?= ClassroomModel::find()->where(['status' => ClassroomModel::STATUS_IN_PROGRESS])->count() ?>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Section (Collapsible) -->
            <div class="search-filter-container collapsed" id="searchContainer">
                <div class="search-content" id="searchContent">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>

            <!-- Data Table -->
            <?php Pjax::begin(['id' => 'classroom-grid-pjax']); ?>

            <div class="classroom-table-card">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => null, // Disable inline filters since we have collapsible search
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'header' => 'No.',
                            'headerOptions' => ['style' => 'width: 60px; text-align: center;'],
                            'contentOptions' => ['style' => 'text-align: center; font-weight: 600;'],
                        ],
                        [
                            'attribute' => 'class_name',
                            'label' => 'Class Name',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<div class="classroom-name-cell">' . Html::encode($model->class_name) . '</div>' .
                                       '<small class="text-muted">' . Html::encode($model->grade_level ?? 'N/A') . '</small>';
                            },
                        ],
                        [
                            'attribute' => 'year',
                            'label' => 'Year',
                            'headerOptions' => ['style' => 'width: 100px;'],
                            'contentOptions' => ['style' => 'text-align: center; font-weight: 600;'],
                        ],
                        [
                            'attribute' => 'session_type',
                            'label' => 'Session',
                            'format' => 'raw',
                            'value' => function($model) {
                                $isMorning = strtolower($model->session_type) === 'morning';
                                $badgeClass = $isMorning ? 'badge-session-morning' : 'badge-session-afternoon';
                                $icon = $isMorning ? 'bi-sun-fill' : 'bi-moon-fill';
                                return '<span class="badge ' . $badgeClass . '"><i class="bi ' . $icon . '"></i> ' . Html::encode($model->session_type) . '</span>';
                            },
                            'headerOptions' => ['style' => 'width: 150px;'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                        ],
                        [
                            'attribute' => 'user_id',
                            'label' => 'Teacher',
                            'format' => 'raw',
                            'value' => function($model) {
                                if ($model->user) {
                                    return '<div class="teacher-name-cell"><i class="bi bi-person-fill text-primary"></i>' .
                                           Html::encode($model->user->username) . '</div>';
                                }
                                return '<span class="text-muted">Not Assigned</span>';
                            },
                        ],
                        [
                            'attribute' => 'current_enrollment',
                            'label' => 'Enrollment',
                            'format' => 'raw',
                            'value' => function($model) {
                                $percentage = $model->quota > 0 ? ($model->current_enrollment / $model->quota) * 100 : 0;
                                $progressClass = $percentage >= 90 ? 'progress-bar-danger' : ($percentage >= 70 ? 'progress-bar-warning' : 'progress-bar-success');

                                return '<div class="enrollment-info">' .
                                       '<span class="enrollment-text">' . $model->current_enrollment . '/' . $model->quota . '</span>' .
                                       '<div class="progress-bar-wrapper">' .
                                       '<div class="progress-thin">' .
                                       '<div class="progress-bar ' . $progressClass . '" style="width: ' . $percentage . '%"></div>' .
                                       '</div>' .
                                       '</div>' .
                                       '</div>';
                            },
                            'headerOptions' => ['style' => 'width: 200px;'],
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function($model) {
                                $statusMap = [
                                    'Open' => ['badge-status-open', 'bi-door-open'],
                                    'Closed' => ['badge-status-closed', 'bi-door-closed'],
                                    'Full' => ['badge-status-full', 'bi-people-fill'],
                                    'In Progress' => ['badge-status-progress', 'bi-hourglass-split'],
                                    'Draft' => ['badge-status-draft', 'bi-file-earmark'],
                                    'Completed' => ['badge-status-completed', 'bi-check-circle-fill'],
                                    'Cancelled' => ['badge-status-cancelled', 'bi-x-circle-fill'],
                                ];
                                $badge = $statusMap[$model->status] ?? ['badge-status-draft', 'bi-circle'];
                                return '<span class="badge ' . $badge[0] . '"><i class="bi ' . $badge[1] . '"></i> ' . Html::encode($model->status) . '</span>';
                            },
                            'headerOptions' => ['style' => 'width: 150px;'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Actions',
                            'template' => '{view} {update} {delete}',
                            'headerOptions' => ['style' => 'width: 180px; text-align: center;'],
                            'contentOptions' => ['class' => 'action-buttons-cell'],
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<i class="bi bi-eye"></i>', $url, [
                                        'class' => 'btn btn-action btn-view btn-sm',
                                        'title' => 'View Details',
                                    ]);
                                },
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="bi bi-pencil"></i>', $url, [
                                        'class' => 'btn btn-action btn-edit btn-sm',
                                        'title' => 'Edit Classroom',
                                    ]);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<i class="bi bi-trash"></i>', $url, [
                                        'class' => 'btn btn-action btn-delete btn-sm',
                                        'title' => 'Delete Classroom',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete "' . $model->class_name . '"?',
                                            'method' => 'post',
                                        ],
                                    ]);
                                },
                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                return [$action, 'class_id' => $model->class_id];
                            }
                        ],
                    ],
                    'emptyText' => '<div class="empty-state">
                        <i class="bi bi-door-closed"></i>
                        <h4>No Classrooms Found</h4>
                        <p>Start by creating your first classroom</p>
                    </div>',
                ]); ?>
            </div>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$script = <<< JS
// Toggle search section
$('#searchToggle').on('click', function() {
    var container = $('#searchContainer');
    var content = $('#searchContent');
    var icon = $(this).find('i');
    var text = $(this).find('span');

    if (content.hasClass('show')) {
        content.removeClass('show').slideUp(300);
        container.addClass('collapsed');
        icon.removeClass('bi-x-lg').addClass('bi-funnel');
        text.text('Search & Filter');
    } else {
        content.addClass('show').slideDown(300);
        container.removeClass('collapsed');
        icon.removeClass('bi-funnel').addClass('bi-x-lg');
        text.text('Hide Search');
    }
});

// Smooth scroll on pagination
$('.pagination a').on('click', function() {
    $('html, body').animate({
        scrollTop: $('.classroom-index').offset().top - 20
    }, 300);
});

// Handle PJAX updates
$(document).on('pjax:success', function() {
    // Re-attach pagination event after PJAX update
    $('.pagination a').on('click', function() {
        $('html, body').animate({
            scrollTop: $('.classroom-index').offset().top - 20
        }, 300);
    });
});
JS;
$this->registerJs($script);
?>
