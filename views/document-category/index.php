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
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.category-header h3 {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.filter-toggle-btn,
.btn-create-category {
    background: white;
    color: #667eea;
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

.btn-create-category {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-create-category:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    color: white;
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
    color: #667eea;
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
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-search-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-search-secondary {
    background: #f3f4f6;
    color: #6b7280;
}

.btn-search-secondary:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
}

.quick-filters {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.quick-filter-btn {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    border: 2px solid #e5e7eb;
    background: white;
    color: #6b7280;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quick-filter-btn:hover {
    border-color: #667eea;
    color: #667eea;
}

.quick-filter-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
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

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .category-header {
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

<div class="document-category-index">
    <div class="card shadow-lg border-0">
        <div class="category-header">
            <h3>
                <i class="bi bi-folder-fill"></i>
                Document Categories Management
            </h3>
            <div class="header-actions">
                <button class="filter-toggle-btn" id="searchToggle">
                    <i class="bi bi-funnel"></i>
                    <span>Search & Filter</span>
                </button>
                <?= Html::a('<i class="bi bi-plus-circle"></i> Create New Category', ['create'], ['class' => 'btn-create-category']) ?>
            </div>
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

            <!-- Search & Filter Section (Collapsible) -->
            <div class="search-filter-container collapsed" id="searchContainer">
                <div class="search-content" id="searchContent">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>

            <!-- Data Table -->
            <?php Pjax::begin(['id' => 'category-grid-pjax']); ?>
            
            <div class="category-table-card">
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
        scrollTop: $('.document-category-index').offset().top - 20
    }, 300);
});

// Handle PJAX updates
$(document).on('pjax:success', function() {
    // Re-attach pagination event after PJAX update
    $('.pagination a').on('click', function() {
        $('html, body').animate({
            scrollTop: $('.document-category-index').offset().top - 20
        }, 300);
    });
});
JS;
$this->registerJs($script);
?>