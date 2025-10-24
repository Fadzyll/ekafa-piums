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
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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
    border-color: #3b82f6;
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: #3b82f6;
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
}

.search-bar {
    position: relative;
    margin-bottom: 1rem;
}

.search-bar input {
    padding-left: 3rem;
    border-radius: 50px;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.search-bar input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.search-bar i {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 1.25rem;
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
    border-color: #3b82f6;
    color: #3b82f6;
}

.filter-pill.active {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-color: #3b82f6;
    color: white;
}

.action-bar {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
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
    color: #3b82f6;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    border: 2px solid #3b82f6;
    transition: all 0.3s ease;
}

.btn-secondary-action:hover {
    background: #3b82f6;
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
    transform: scale(1.005);
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
                <?= Html::a('<i class="bi bi-plus-circle"></i> Upload Document', ['create'], ['class' => 'btn btn-primary-action']) ?>
                <?= Html::a('<i class="bi bi-download"></i> Export', ['export'], ['class' => 'btn btn-secondary-action']) ?>
                <?= Html::a('<i class="bi bi-funnel"></i> Advanced Filter', '#', ['class' => 'btn btn-secondary-action', 'id' => 'toggleFilter']) ?>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" 
                           class="form-control" 
                           id="searchDocuments" 
                           placeholder="Search by user ID, document name, or status...">
                </div>

                <div class="filter-pills">
                    <button class="filter-pill active" data-status="">All Documents</button>
                    <button class="filter-pill" data-status="approved">Approved</button>
                    <button class="filter-pill" data-status="pending">Pending Review</button>
                    <button class="filter-pill" data-status="rejected">Rejected</button>
                    <button class="filter-pill" data-status="expired">Expired</button>
                </div>
            </div>

            <!-- Data Table -->
            <?php Pjax::begin(['id' => 'documents-pjax']); ?>
            
            <div class="documents-table-card">
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
                            'filter' => UserDocuments::optsStatus(),
                            'headerOptions' => ['style' => 'width: 150px;'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                        ],
                        [
                            'attribute' => 'file_url',
                            'label' => 'File',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a(
                                    '<i class="bi bi-file-earmark-pdf"></i> View', 
                                    \yii\helpers\Url::to(['download', 'document_id' => $model->document_id, 'inline' => 1]),
                                    [
                                        'target' => '_blank',
                                        'class' => 'file-link',
                                        'data-pjax' => '0', // ✅ Prevent PJAX from interfering
                                    ]
                                ); '<span class="no-file">No file</span>';
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
// Search functionality
$('#searchDocuments').on('keyup', function() {
    var value = $(this).val().toLowerCase();
    $('.table tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
});

// Filter pills
$('.filter-pill').on('click', function() {
    $('.filter-pill').removeClass('active');
    $(this).addClass('active');
    
    var status = $(this).data('status').toLowerCase();
    
    if (status === '') {
        $('.table tbody tr').show();
    } else {
        $('.table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(status) > -1);
        });
    }
});

// Smooth animations
$('.table tbody tr').each(function(index) {
    $(this).css('animation', 'fadeInUp 0.3s ease ' + (index * 0.05) + 's forwards');
    $(this).css('opacity', '0');
});

// Pagination smooth scroll
$('.pagination a').on('click', function() {
    $('html, body').animate({
        scrollTop: $('.user-documents-index').offset().top - 20
    }, 300);
});
JS;
$this->registerJs($script);
?>