<?php

use app\models\ClassroomModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModelSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $stats */

$this->title = 'Classroom Management';
$this->params['breadcrumbs'][] = $this->title;

// Register custom CSS
$this->registerCss("
    .stats-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }
    .stats-card .card-body {
        padding: 1.5rem;
    }
    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin: 0;
        font-weight: 500;
    }
    .classroom-table {
        border-radius: 10px;
        overflow: hidden;
    }
    .table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem;
    }
    .table tbody tr {
        transition: all 0.3s ease;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }
    .progress-thin {
        height: 8px;
        border-radius: 10px;
        background-color: #e9ecef;
    }
    .progress-thin .progress-bar {
        border-radius: 10px;
    }
    .action-buttons .btn {
        padding: 0.4rem 0.8rem;
        margin: 0 0.2rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .search-panel {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .btn-create {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    .filter-toggle {
        cursor: pointer;
        color: #667eea;
        font-weight: 600;
    }
    .filter-toggle:hover {
        text-decoration: underline;
    }
    .enrollment-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .enrollment-text {
        font-weight: 600;
        min-width: 40px;
    }
");

// Register custom JS
$this->registerJs("
    // Toggle filter panel
    $('#filter-toggle').click(function() {
        $('#filter-panel').slideToggle();
        $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
    });

    // Bulk actions
    $('#select-all').change(function() {
        $('.selection-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkActions();
    });

    $(document).on('change', '.selection-checkbox', function() {
        updateBulkActions();
    });

    function updateBulkActions() {
        var checked = $('.selection-checkbox:checked').length;
        if (checked > 0) {
            $('#bulk-actions').removeClass('d-none');
            $('#selected-count').text(checked);
        } else {
            $('#bulk-actions').addClass('d-none');
        }
    }

    // Bulk delete
    $('#bulk-delete-btn').click(function() {
        if (!confirm('Are you sure you want to delete the selected classrooms?')) {
            return;
        }
        
        var ids = [];
        $('.selection-checkbox:checked').each(function() {
            ids.push($(this).val());
        });
        
        $.post('" . Url::to(['bulk-delete']) . "', {ids: ids}, function(response) {
            location.reload();
        });
    });

    // Status update
    $(document).on('change', '.status-quick-update', function() {
        var select = $(this);
        var classId = select.data('class-id');
        var status = select.val();
        
        $.post('" . Url::to(['update-status']) . "', {
            class_id: classId,
            status: status
        }, function(response) {
            if (response.success) {
                $.pjax.reload({container: '#classroom-pjax'});
            } else {
                alert('Failed to update status: ' + response.message);
            }
        });
    });

    // Export functionality
    $('#export-btn').click(function() {
        window.location.href = '" . Url::to(['export']) . "' + location.search;
    });

    // Tooltips
    $('[data-toggle=\"tooltip\"]').tooltip();
");
?>

<div class="classroom-management-index">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stats-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">Total Classrooms</p>
                            <h2 class="stat-number text-primary"><?= $stats['total'] ?></h2>
                        </div>
                        <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-school"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stats-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">Active Classes</p>
                            <h2 class="stat-number text-success"><?= $stats['active'] ?></h2>
                        </div>
                        <div class="stats-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stats-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">Full Classes</p>
                            <h2 class="stat-number text-warning"><?= $stats['full'] ?></h2>
                        </div>
                        <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stats-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">In Progress</p>
                            <h2 class="stat-number text-info"><?= $stats['inProgress'] ?></h2>
                        </div>
                        <div class="stats-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-spinner"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card classroom-table shadow">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-list me-2"></i><?= Html::encode($this->title) ?>
                    </h4>
                </div>
                <div class="col-md-6 text-end">
                    <button id="export-btn" class="btn btn-outline-primary me-2">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                    <?= Html::a('<i class="fas fa-plus me-1"></i> Create Classroom', ['create'], [
                        'class' => 'btn btn-create text-white'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Toggle -->
            <div class="mb-3">
                <span id="filter-toggle" class="filter-toggle">
                    <i class="fas fa-filter me-1"></i> 
                    <span>Show Filters</span>
                    <i class="fas fa-chevron-down ms-1"></i>
                </span>
            </div>

            <!-- Filter Panel -->
            <div id="filter-panel" class="search-panel" style="display: none;">
                <?php
                echo $this->render('_search', ['model' => $searchModel]);
                ?>
            </div>

            <!-- Bulk Actions -->
            <div id="bulk-actions" class="alert alert-info d-none" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <span id="selected-count">0</span> item(s) selected.
                <button id="bulk-delete-btn" class="btn btn-sm btn-danger ms-3">
                    <i class="fas fa-trash me-1"></i> Delete Selected
                </button>
            </div>

            <?php Pjax::begin(['id' => 'classroom-pjax']); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover align-middle'],
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'header' => 'No.',
                        'headerOptions' => ['style' => 'width: 60px'],
                    ],
                    [
                        'attribute' => 'class_name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<div class="fw-bold">' . Html::encode($model->class_name) . '</div>' .
                                   '<small class="text-muted">' . Html::encode($model->grade_level ?? 'N/A') . '</small>';
                        },
                    ],
                    [
                        'attribute' => 'year',
                        'filter' => false,
                        'contentOptions' => ['class' => 'text-center'],
                        'headerOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'session_type',
                        'format' => 'raw',
                        'filter' => Html::activeDropDownList($searchModel, 'session_type', 
                            ClassroomModel::optsSessionType(), 
                            ['class' => 'form-control', 'prompt' => 'All']
                        ),
                        'value' => function ($model) {
                            $icon = $model->isSessionTypeMorning() ? 'sun' : 'moon';
                            $color = $model->isSessionTypeMorning() ? 'warning' : 'info';
                            return '<span class="badge bg-' . $color . '"><i class="fas fa-' . $icon . ' me-1"></i>' . 
                                   Html::encode($model->session_type) . '</span>';
                        },
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'teacherName',
                        'label' => 'Teacher',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if ($model->user) {
                                return '<div><i class="fas fa-chalkboard-teacher me-1 text-primary"></i>' . 
                                       Html::encode($model->user->username) . '</div>';
                            }
                            return '<span class="text-muted">Not Assigned</span>';
                        },
                    ],
                    [
                        'attribute' => 'current_enrollment',
                        'label' => 'Enrollment',
                        'format' => 'raw',
                        'filter' => false,
                        'value' => function ($model) {
                            $percentage = $model->quota > 0 ? ($model->current_enrollment / $model->quota) * 100 : 0;
                            $colorClass = $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success');
                            
                            return '<div class="enrollment-cell">' .
                                   '<span class="enrollment-text">' . $model->current_enrollment . '/' . $model->quota . '</span>' .
                                   '<div class="flex-grow-1">' .
                                   '<div class="progress progress-thin">' .
                                   '<div class="progress-bar bg-' . $colorClass . '" style="width: ' . $percentage . '%"></div>' .
                                   '</div>' .
                                   '</div>' .
                                   '</div>';
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'filter' => Html::activeDropDownList($searchModel, 'status', 
                            ClassroomModel::optsStatus(), 
                            ['class' => 'form-control', 'prompt' => 'All']
                        ),
                        'value' => function ($model) {
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
                            return '<span class="status-badge bg-' . $color . ' text-white">' . 
                                   Html::encode($model->status) . '</span>';
                        },
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => 'Actions',
                        'template' => '{view} {update} {delete}',
                        'contentOptions' => ['class' => 'text-center action-buttons'],
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'class' => 'btn btn-sm btn-info',
                                    'title' => 'View',
                                    'data-toggle' => 'tooltip',
                                ]);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, [
                                    'class' => 'btn btn-sm btn-primary',
                                    'title' => 'Update',
                                    'data-toggle' => 'tooltip',
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'title' => 'Delete',
                                    'data-toggle' => 'tooltip',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this classroom?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                        'urlCreator' => function ($action, ClassroomModel $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'class_id' => $model->class_id]);
                        }
                    ],
                ],
            ]); ?>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>