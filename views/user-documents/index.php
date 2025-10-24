<?php

use app\models\UserDocuments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\UserDocumentsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'User Documents';
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
?>

<style>
.documents-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px 20px 0 0;
    padding: 2rem;
    color: white;
    margin-bottom: 0;
}

.documents-header h3 {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.documents-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-box {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    border: 2px solid #f3f4f6;
    transition: all 0.3s ease;
    text-align: center;
}

.stat-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    border-color: #667eea;
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: #667eea;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #6b7280;
    font-size: 0.8125rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-box.completed .stat-number { color: #10b981; }
.stat-box.pending .stat-number { color: #f59e0b; }
.stat-box.incomplete .stat-number { color: #ef4444; }

.filter-section {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: all 0.3s ease;
}

.filter-section.show {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
        padding: 0 1.5rem;
    }
    to {
        opacity: 1;
        max-height: 1000px;
        padding: 1.5rem;
    }
}

.filter-pills {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-pill {
    padding: 0.625rem 1.25rem;
    border-radius: 50px;
    border: 2px solid #e5e7eb;
    background: white;
    color: #6b7280;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-pill:hover {
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
}

.filter-pill.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}

.action-bar {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
}

.action-bar-left {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-primary-action {
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

.btn-primary-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    color: white;
}

.btn-secondary-action {
    background: white;
    color: #667eea;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    border: 2px solid #667eea;
    transition: all 0.3s ease;
}

.btn-secondary-action:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

.btn-filter-toggle {
    background: white;
    color: #667eea;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    border: 2px solid #667eea;
    transition: all 0.3s ease;
}

.btn-filter-toggle:hover {
    background: #f3f4f6;
    transform: translateY(-2px);
}

.btn-filter-toggle.active {
    background: #667eea;
    color: white;
}

.documents-table-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
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
    transform: scale(1.002);
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-approved {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.status-rejected {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.status-pending {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.status-expired {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
}

.file-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #eff6ff;
    color: #3b82f6;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    text-decoration: none;
}

.file-link:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.no-file {
    color: #9ca3af;
    font-style: italic;
}

.action-btn-group {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-table-action {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.2s ease;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}

.btn-view-action {
    background: #3b82f6;
    color: white;
}

.btn-view-action:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    color: white;
}

.btn-edit-action {
    background: #10b981;
    color: white;
}

.btn-edit-action:hover {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    color: white;
}

.btn-delete-action {
    background: #ef4444;
    color: white;
}

.btn-delete-action:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    color: white;
}

.user-id-badge {
    background: #f3f4f6;
    color: #374151;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.875rem;
    display: inline-block;
}

.document-name {
    font-weight: 600;
    color: #111827;
}

.category-badge {
    background: #e0e7ff;
    color: #3730a3;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.8125rem;
    display: inline-block;
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

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 2px solid #e5e7eb;
    padding: 0.625rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.documents-table-card {
    animation: fadeInUp 0.5s ease;
}

@media (max-width: 768px) {
    .documents-stats {
        grid-template-columns: 1fr;
    }
    
    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .action-bar-left {
        flex-direction: column;
    }
    
    .filter-pills {
        flex-direction: column;
    }
}
</style>

<div class="user-documents-index">
    <div class="card shadow-lg border-0">
        <div class="documents-header">
            <h3>
                <i class="bi bi-files"></i>
                User Documents Management
            </h3>
            <p class="mb-0 mt-2" style="opacity: 0.9; font-size: 0.95rem;">
                Track and manage all user document submissions
            </p>
        </div>

        <div class="card-body p-4">
            <!-- Statistics -->
            <div class="documents-stats">
                <div class="stat-box">
                    <div class="stat-number"><?= $dataProvider->getTotalCount() ?></div>
                    <div class="stat-label">Total Documents</div>
                </div>
                <div class="stat-box completed">
                    <div class="stat-number">
                        <?= UserDocuments::find()->where(['status' => UserDocuments::STATUS_APPROVED])->count() ?>
                    </div>
                    <div class="stat-label">Approved</div>
                </div>
                <div class="stat-box pending">
                    <div class="stat-number">
                        <?= UserDocuments::find()->where(['status' => UserDocuments::STATUS_PENDING_REVIEW])->count() ?>
                    </div>
                    <div class="stat-label">Pending Review</div>
                </div>
                <div class="stat-box incomplete">
                    <div class="stat-number">
                        <?= UserDocuments::find()->where(['status' => UserDocuments::STATUS_REJECTED])->count() ?>
                    </div>
                    <div class="stat-label">Rejected</div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-bar-left">
                    <?= Html::a('<i class="bi bi-plus-circle"></i> Upload Document', ['create'], ['class' => 'btn btn-primary-action']) ?>
                    <?= Html::a('<i class="bi bi-download"></i> Export', ['export'], ['class' => 'btn btn-secondary-action']) ?>
                </div>
                <button type="button" class="btn btn-filter-toggle" id="toggleFilter">
                    <i class="bi bi-funnel"></i> <span id="filterText">Search & Filter</span>
                </button>
            </div>

            <!-- Filter Section (Initially Hidden) -->
            <div class="filter-section" id="filterSection" style="display: none;">
                <!-- Quick Filters -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-lightning-fill me-2" style="color: #667eea;"></i>
                        <strong>Quick Filters:</strong>
                    </div>
                    <div class="filter-pills">
                        <button class="filter-pill active" data-filter="all">All</button>
                        <button class="filter-pill" data-filter="approved">Active Only</button>
                        <button class="filter-pill" data-filter="pending">Pending Only</button>
                        <button class="filter-pill" data-filter="rejected">Rejected</button>
                        <button class="filter-pill" data-filter="expired">Expired</button>
                    </div>
                </div>

                <!-- Advanced Search Form -->
                <?php $form = \yii\widgets\ActiveForm::begin([
                    'action' => ['index'],
                    'method' => 'get',
                    'options' => ['data-pjax' => 1, 'id' => 'search-form'],
                ]); ?>

                <div class="row g-3">
                    <!-- Document Name -->
                    <div class="col-md-6">
                        <label class="form-label d-flex align-items-center">
                            <i class="bi bi-folder me-2"></i>
                            Document Name
                        </label>
                        <?= Html::activeTextInput($searchModel, 'document_name', [
                            'class' => 'form-control',
                            'placeholder' => 'Search by document name...'
                        ]) ?>
                    </div>

                    <!-- Document Type -->
                    <div class="col-md-6">
                        <label class="form-label d-flex align-items-center">
                            <i class="bi bi-list-ul me-2"></i>
                            Document Type
                        </label>
                        <?= Html::activeTextInput($searchModel, 'document_type', [
                            'class' => 'form-control',
                            'placeholder' => 'Search in type...'
                        ]) ?>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6">
                        <label class="form-label d-flex align-items-center">
                            <i class="bi bi-tags me-2"></i>
                            Category
                        </label>
                        <?= Html::activeDropDownList($searchModel, 'category_id', 
                            \yii\helpers\ArrayHelper::map(
                                \app\models\DocumentCategory::find()->all(), 
                                'category_id', 
                                'category_name'
                            ),
                            [
                                'class' => 'form-select',
                                'prompt' => 'All Categories'
                            ]
                        ) ?>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label d-flex align-items-center">
                            <i class="bi bi-toggle-on me-2"></i>
                            Status
                        </label>
                        <?= Html::activeDropDownList($searchModel, 'status', 
                            UserDocuments::optsStatus(),
                            [
                                'class' => 'form-select',
                                'prompt' => 'All Status'
                            ]
                        ) ?>
                    </div>

                    <!-- User ID -->
                    <div class="col-md-6">
                        <label class="form-label d-flex align-items-center">
                            <i class="bi bi-person me-2"></i>
                            User ID
                        </label>
                        <?= Html::activeTextInput($searchModel, 'user_id', [
                            'class' => 'form-control',
                            'placeholder' => 'Enter user ID...'
                        ]) ?>
                    </div>

                    <!-- Document ID -->
                    <div class="col-md-6">
                        <label class="form-label d-flex align-items-center">
                            <i class="bi bi-hash me-2"></i>
                            Document ID
                        </label>
                        <?= Html::activeTextInput($searchModel, 'document_id', [
                            'class' => 'form-control',
                            'placeholder' => 'Enter document ID...'
                        ]) ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 mt-4">
                    <?= Html::submitButton('<i class="bi bi-search me-2"></i>Search', [
                        'class' => 'btn btn-primary px-4',
                        'style' => 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;'
                    ]) ?>
                    <?= Html::a('<i class="bi bi-arrow-clockwise me-2"></i>Reset', ['index'], [
                        'class' => 'btn btn-outline-secondary px-4'
                    ]) ?>
                </div>

                <?php \yii\widgets\ActiveForm::end(); ?>
            </div>

            <!-- Data Table -->
            <?php Pjax::begin(['id' => 'documents-pjax']); ?>
            
            <div class="documents-table-card">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => null, // We'll use custom filters
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'header' => 'No.',
                            'headerOptions' => ['style' => 'width: 60px; text-align: center;'],
                            'contentOptions' => ['style' => 'text-align: center; font-weight: 600;'],
                        ],
                        [
                            'attribute' => 'user_id',
                            'label' => 'User',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return '<span class="user-id-badge">User #' . $model->user_id . '</span>';
                            },
                            'headerOptions' => ['style' => 'width: 100px;'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                        ],
                        [
                            'attribute' => 'document_name',
                            'label' => 'Document Name',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return '<div class="document-name">' . Html::encode($model->document_name) . '</div>';
                            },
                        ],
                        [
                            'attribute' => 'document_type',
                            'label' => 'Type',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return '<div style="color: #6b7280; font-size: 0.875rem;">' . Html::encode($model->document_type) . '</div>';
                            },
                            'headerOptions' => ['style' => 'width: 150px;'],
                        ],
                        [
                            'attribute' => 'category_id',
                            'label' => 'Category',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->category 
                                    ? '<span class="category-badge">' . Html::encode($model->category->category_name) . '</span>'
                                    : '<span class="text-muted">-</span>';
                            },
                            'headerOptions' => ['style' => 'width: 140px;'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $statusClasses = [
                                    UserDocuments::STATUS_APPROVED => 'status-approved',
                                    UserDocuments::STATUS_REJECTED => 'status-rejected',
                                    UserDocuments::STATUS_PENDING_REVIEW => 'status-pending',
                                    UserDocuments::STATUS_EXPIRED => 'status-expired',
                                ];
                                $statusIcons = [
                                    UserDocuments::STATUS_APPROVED => 'check-circle-fill',
                                    UserDocuments::STATUS_REJECTED => 'x-circle-fill',
                                    UserDocuments::STATUS_PENDING_REVIEW => 'clock-fill',
                                    UserDocuments::STATUS_EXPIRED => 'hourglass-bottom',
                                ];
                                return Html::tag('span', 
                                    '<i class="bi bi-' . ($statusIcons[$model->status] ?? 'question-circle') . '"></i> ' . $model->displayStatus(), 
                                    ['class' => 'status-badge ' . ($statusClasses[$model->status] ?? 'status-pending')]
                                );
                            },
                            'headerOptions' => ['style' => 'width: 150px;'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                        ],
                        [
                            'attribute' => 'file_url',
                            'label' => 'File',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->file_url) {
                                    return Html::a(
                                        '<i class="bi bi-file-earmark-pdf"></i> View', 
                                        \yii\helpers\Url::to(['download', 'document_id' => $model->document_id, 'inline' => 1]),
                                        [
                                            'target' => '_blank',
                                            'class' => 'file-link',
                                            'data-pjax' => '0',
                                        ]
                                    );
                                }
                                return '<span class="no-file">No file</span>';
                            },
                            'headerOptions' => ['style' => 'width: 100px;'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                        ],
                        [
                            'attribute' => 'upload_date',
                            'label' => 'Uploaded',
                            'format' => ['datetime', 'php:M d, Y'],
                            'headerOptions' => ['style' => 'width: 130px;'],
                            'contentOptions' => ['style' => 'text-align: center; color: #6b7280; font-size: 0.875rem;'],
                        ],
                        [
                            'class' => ActionColumn::className(),
                            'header' => 'Actions',
                            'headerOptions' => ['style' => 'width: 200px; text-align: center;'],
                            'contentOptions' => ['class' => 'action-btn-group'],
                            'template' => '{update} {delete}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="bi bi-pencil"></i>', $url, [
                                        'class' => 'btn btn-table-action btn-edit-action',
                                        'title' => 'Edit Document',
                                    ]);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<i class="bi bi-trash"></i>', $url, [
                                        'class' => 'btn btn-table-action btn-delete-action',
                                        'title' => 'Delete Document',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this document?',
                                            'method' => 'post',
                                        ],
                                    ]);
                                },
                            ],
                            'urlCreator' => function ($action, UserDocuments $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'document_id' => $model->document_id]);
                            },
                        ],
                    ],
                    'emptyText' => '<div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4>No Documents Found</h4>
                        <p>Start by uploading your first document</p>
                    </div>',
                ]); ?>
            </div>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$script = <<< JS
// Toggle Filter Section
$('#toggleFilter').on('click', function() {
    var filterSection = $('#filterSection');
    var button = $(this);
    
    if (filterSection.is(':visible')) {
        filterSection.slideUp(300);
        button.removeClass('active');
        $('#filterText').text('Search & Filter');
    } else {
        filterSection.slideDown(300).addClass('show');
        button.addClass('active');
        $('#filterText').text('Hide Search');
    }
});

// Quick Filter Pills
$('.filter-pill').on('click', function() {
    $('.filter-pill').removeClass('active');
    $(this).addClass('active');
    
    var filter = $(this).data('filter');
    
    // Update the status dropdown in the form
    if (filter === 'all') {
        $('#userdocumentssearch-status').val('').trigger('change');
    } else if (filter === 'approved') {
        $('#userdocumentssearch-status').val('<?= UserDocuments::STATUS_APPROVED ?>').trigger('change');
    } else if (filter === 'pending') {
        $('#userdocumentssearch-status').val('<?= UserDocuments::STATUS_PENDING_REVIEW ?>').trigger('change');
    } else if (filter === 'rejected') {
        $('#userdocumentssearch-status').val('<?= UserDocuments::STATUS_REJECTED ?>').trigger('change');
    } else if (filter === 'expired') {
        $('#userdocumentssearch-status').val('<?= UserDocuments::STATUS_EXPIRED ?>').trigger('change');
    }
    
    // Auto-submit the form
    $('#search-form').submit();
});

// Smooth animations for table rows
$('.table tbody tr').each(function(index) {
    $(this).css('animation', 'fadeInUp 0.3s ease ' + (index * 0.05) + 's forwards');
    $(this).css('opacity', '0');
});

// Pagination smooth scroll
$(document).on('click', '.pagination a', function() {
    $('html, body').animate({
        scrollTop: $('.user-documents-index').offset().top - 20
    }, 300);
});

// Auto-hide filter section on mobile after search
if ($(window).width() < 768) {
    $('#search-form').on('submit', function() {
        setTimeout(function() {
            $('#filterSection').slideUp(300);
            $('#toggleFilter').removeClass('active');
            $('#filterText').text('Search & Filter');
        }, 500);
    });
}
JS;
$this->registerJs($script);
?>