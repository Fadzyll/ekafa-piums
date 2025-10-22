<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\DocumentCategory;

$this->title = 'Document Categories';
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
?>

<style>
.category-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px 20px 0 0;
    padding: 2rem;
    color: white;
    margin-bottom: 0;
}

.category-header h3 {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
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
    border-color: #667eea;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #667eea;
    margin: 0;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.search-filter-section {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.filter-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.filter-tab {
    padding: 0.625rem 1.25rem;
    border-radius: 50px;
    border: 2px solid #e5e7eb;
    background: white;
    color: #6b7280;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-tab:hover {
    border-color: #667eea;
    color: #667eea;
}

.filter-tab.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}

.search-box {
    position: relative;
}

.search-box input {
    padding-left: 3rem;
    border-radius: 50px;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.search-box input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.search-box i {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 1.25rem;
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.btn-create {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    color: white;
}

.btn-export {
    background: white;
    color: #667eea;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    border: 2px solid #667eea;
    transition: all 0.3s ease;
}

.btn-export:hover {
    background: #667eea;
    color: white;
}

.category-table-card {
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

.badge-role-teacher {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.badge-role-parent {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.badge-role-both {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
}

.badge-required {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.badge-optional {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
}

.badge-active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.badge-inactive {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
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

.category-name-cell {
    font-weight: 600;
    color: #111827;
}

.description-cell {
    color: #6b7280;
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.category-table-card {
    animation: fadeIn 0.5s ease;
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .filter-tabs {
        flex-direction: column;
    }
}
</style>

<div class="document-category-index">
    <div class="card shadow-lg border-0">
        <div class="category-header">
            <h3>
                <i class="bi bi-folder-fill"></i>
                Document Categories Management
            </h3>
            <p class="mb-0 mt-2" style="opacity: 0.9; font-size: 0.95rem;">
                Manage and organize document categories for your institution
            </p>
        </div>

        <div class="card-body p-4">
            <!-- Statistics Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-label">Total Categories</div>
                    <div class="stat-value"><?= $dataProvider->getTotalCount() ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Active</div>
                    <div class="stat-value" style="color: #10b981;">
                        <?= DocumentCategory::find()->where(['status' => DocumentCategory::STATUS_ACTIVE])->count() ?>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Required Docs</div>
                    <div class="stat-value" style="color: #ef4444;">
                        <?= DocumentCategory::find()->where(['is_required' => 1])->count() ?>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">For Teachers</div>
                    <div class="stat-value" style="color: #3b82f6;">
                        <?= DocumentCategory::find()->where(['required_for_role' => DocumentCategory::ROLE_TEACHER])->count() ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <?= Html::a('<i class="bi bi-plus-circle"></i> Create New Category', ['create'], ['class' => 'btn btn-create']) ?>
                <?= Html::a('<i class="bi bi-download"></i> Export', ['export'], ['class' => 'btn btn-export']) ?>
            </div>

            <!-- Search and Filter Section -->
            <div class="search-filter-section">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" 
                                   class="form-control" 
                                   id="searchInput" 
                                   placeholder="Search categories by name or description...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="statusFilter" style="border-radius: 50px; border: 2px solid #e5e7eb;">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="filter-tabs">
                    <button class="filter-tab active" data-filter="all">All Categories</button>
                    <button class="filter-tab" data-filter="teacher">Teacher Only</button>
                    <button class="filter-tab" data-filter="parent">Parent Only</button>
                    <button class="filter-tab" data-filter="both">Both Roles</button>
                    <button class="filter-tab" data-filter="required">Required</button>
                    <button class="filter-tab" data-filter="optional">Optional</button>
                </div>
            </div>

            <!-- Data Table -->
            <?php Pjax::begin(['id' => 'category-grid-pjax']); ?>
            
            <div class="category-table-card">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'header' => 'No.',
                            'headerOptions' => ['style' => 'width: 60px; text-align: center;'],
                            'contentOptions' => ['style' => 'text-align: center; font-weight: 600;'],
                        ],
                        [
                            'attribute' => 'category_name',
                            'label' => 'Category Name',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<div class="category-name-cell">' . Html::encode($model->category_name) . '</div>';
                            },
                        ],
                        [
                            'attribute' => 'description',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<div class="description-cell" title="' . Html::encode($model->description) . '">' 
                                    . Html::encode($model->description) . '</div>';
                            },
                            'headerOptions' => ['style' => 'width: 300px;'],
                        ],
                        [
                            'attribute' => 'required_for_role',
                            'label' => 'Role',
                            'format' => 'raw',
                            'value' => function($model) {
                                $badges = [
                                    'Teacher' => '<span class="badge badge-role-teacher"><i class="bi bi-person-workspace"></i> Teacher</span>',
                                    'Parent' => '<span class="badge badge-role-parent"><i class="bi bi-people"></i> Parent</span>',
                                    'Both' => '<span class="badge badge-role-both"><i class="bi bi-people-fill"></i> Both</span>',
                                ];
                                return $badges[$model->required_for_role] ?? $model->required_for_role;
                            },
                            'filter' => [
                                'Teacher' => 'Teacher',
                                'Parent' => 'Parent',
                                'Both' => 'Both',
                            ],
                            'headerOptions' => ['style' => 'width: 150px;'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                        ],
                        [
                            'attribute' => 'is_required',
                            'label' => 'Required',
                            'format' => 'raw',
                            'value' => function($model) {
                                return $model->is_required 
                                    ? '<span class="badge badge-required"><i class="bi bi-exclamation-circle"></i> Mandatory</span>' 
                                    : '<span class="badge badge-optional"><i class="bi bi-check-circle"></i> Optional</span>';
                            },
                            'filter' => [1 => 'Required', 0 => 'Optional'],
                            'headerOptions' => ['style' => 'width: 130px;'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function($model) {
                                return $model->status === 'Active' 
                                    ? '<span class="badge badge-active"><i class="bi bi-check-circle-fill"></i> Active</span>' 
                                    : '<span class="badge badge-inactive"><i class="bi bi-x-circle-fill"></i> Inactive</span>';
                            },
                            'filter' => [
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                            ],
                            'headerOptions' => ['style' => 'width: 120px;'],
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
                                    return Html::a('<i class="bi bi-eye"></i> View', $url, [
                                        'class' => 'btn btn-action btn-view btn-sm',
                                        'title' => 'View Details',
                                    ]);
                                },
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="bi bi-pencil"></i> Edit', $url, [
                                        'class' => 'btn btn-action btn-edit btn-sm',
                                        'title' => 'Edit Category',
                                    ]);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<i class="bi bi-trash"></i>', $url, [
                                        'class' => 'btn btn-action btn-delete btn-sm',
                                        'title' => 'Delete Category',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete "' . $model->category_name . '"?',
                                            'method' => 'post',
                                        ],
                                    ]);
                                },
                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                return [$action, 'id' => $model->category_id];
                            }
                        ],
                    ],
                    'emptyText' => '<div class="empty-state">
                        <i class="bi bi-folder-x"></i>
                        <h4>No Categories Found</h4>
                        <p>Start by creating your first document category</p>
                    </div>',
                ]); ?>
            </div>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$script = <<< JS
// Search functionality
$('#searchInput').on('keyup', function() {
    var value = $(this).val().toLowerCase();
    $('.table tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
});

// Status filter
$('#statusFilter').on('change', function() {
    var status = $(this).val().toLowerCase();
    if (status === '') {
        $('.table tbody tr').show();
    } else {
        $('.table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(status) > -1);
        });
    }
});

// Filter tabs
$('.filter-tab').on('click', function() {
    $('.filter-tab').removeClass('active');
    $(this).addClass('active');
    
    var filter = $(this).data('filter');
    
    if (filter === 'all') {
        $('.table tbody tr').show();
    } else {
        var filterText = '';
        switch(filter) {
            case 'teacher': filterText = 'teacher'; break;
            case 'parent': filterText = 'parent'; break;
            case 'both': filterText = 'both'; break;
            case 'required': filterText = 'mandatory'; break;
            case 'optional': filterText = 'optional'; break;
        }
        
        $('.table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(filterText) > -1);
        });
    }
});

// Add smooth scroll to top on pagination
$('.pagination a').on('click', function() {
    $('html, body').animate({
        scrollTop: $('.document-category-index').offset().top - 20
    }, 300);
});
JS;
$this->registerJs($script);
?>