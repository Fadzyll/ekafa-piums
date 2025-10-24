<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UserDocuments $model */

$this->title = $model->document_name ?: ('Document #' . $model->document_id);
$this->params['breadcrumbs'][] = ['label' => 'User Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
\yii\web\YiiAsset::register($this);

$isAdmin = Yii::$app->user->identity->role === 'Admin';
$isTeacherOrParent = in_array(Yii::$app->user->identity->role, ['Teacher', 'Parent']);
?>

<style>
.admin-action-bar {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-left: 4px solid #f59e0b;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
}

.admin-action-bar .label {
    font-weight: 700;
    color: #92400e;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.admin-action-bar .actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    flex: 1;
}

.btn-admin-action {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 700;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-approve-action {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-approve-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    color: white;
}

.btn-reject-action {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-reject-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    color: white;
}

/* Rest of the existing styles from the original view.php file... */
.document-view-header {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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

.btn-action {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
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

.btn-edit {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    color: white;
}

.btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    color: white;
}

.btn-download {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
}

.btn-download:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
    color: white;
}

.document-content {
    padding: 2rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-card {
    background: #f9fafb;
    border-radius: 16px;
    padding: 1.5rem;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.info-card:hover {
    border-color: #3b82f6;
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

.info-label i {
    color: #3b82f6;
}

.info-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    word-break: break-word;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.875rem;
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

.file-preview-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.file-preview-card h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.file-preview-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    background: #f9fafb;
    padding: 2rem;
    border-radius: 12px;
}

.file-icon-large {
    font-size: 5rem;
    color: #3b82f6;
}

.file-info {
    flex-grow: 1;
}

.file-name-large {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.5rem;
    word-break: break-all;
}

.file-meta {
    color: #6b7280;
    font-size: 0.9375rem;
}

.file-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.btn-file-action {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
    white-space: nowrap;
}

.btn-view-file {
    background: #3b82f6;
    color: white;
}

.btn-view-file:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    color: white;
}

.btn-download-file {
    background: white;
    color: #3b82f6;
    border: 2px solid #3b82f6;
}

.btn-download-file:hover {
    background: #3b82f6;
    color: white;
}

.no-file-message {
    text-align: center;
    padding: 3rem;
    color: #9ca3af;
}

.no-file-message i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.notes-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.notes-card.rejection {
    border-color: #ef4444;
    background: #fef2f2;
}

.notes-card h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.notes-content {
    color: #4b5563;
    line-height: 1.75;
    padding: 1rem;
    background: white;
    border-radius: 8px;
}

.timeline-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 2rem;
}

.timeline-card h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.timeline-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 12px;
    margin-bottom: 1rem;
}

.timeline-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.timeline-content {
    flex-grow: 1;
}

.timeline-title {
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.25rem;
}

.timeline-time {
    color: #6b7280;
    font-size: 0.875rem;
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

.info-card, .file-preview-card, .timeline-card, .notes-card {
    animation: slideInUp 0.5s ease;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .action-bar, .admin-action-bar {
        flex-direction: column;
    }
    
    .file-preview-content {
        flex-direction: column;
        text-align: center;
    }
    
    .file-actions {
        width: 100%;
    }
}
</style>

<div class="user-documents-view">
    <div class="card shadow-lg border-0">
        <!-- Header -->
        <div class="document-view-header">
            <div class="view-title">
                <i class="bi bi-file-earmark-text"></i>
                <?= Html::encode($this->title) ?>
            </div>
            <p class="view-subtitle">
                <?= $isAdmin ? 'Review and manage document status' : 'Complete document details and file information' ?>
            </p>
        </div>

        <!-- Admin Action Bar -->
        <?php if ($isAdmin && $model->status === \app\models\UserDocuments::STATUS_PENDING_REVIEW): ?>
        <div class="card-body pt-3 pb-0">
            <div class="admin-action-bar">
                <div class="label">
                    <i class="bi bi-shield-check"></i>
                    Quick Review Actions:
                </div>
                <div class="actions">
                    <?= Html::a(
                        '<i class="bi bi-check-circle-fill"></i> Approve Document',
                        ['approve', 'document_id' => $model->document_id],
                        [
                            'class' => 'btn-admin-action btn-approve-action',
                            'data' => [
                                'confirm' => 'Are you sure you want to approve this document?',
                                'method' => 'post',
                            ],
                        ]
                    ) ?>
                    <?= Html::a(
                        '<i class="bi bi-x-circle-fill"></i> Reject Document',
                        ['reject', 'document_id' => $model->document_id],
                        ['class' => 'btn-admin-action btn-reject-action']
                    ) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Action Bar -->
        <div class="action-bar">
            <?= Html::a('<i class="bi bi-arrow-left"></i> Back to List', ['index'], ['class' => 'btn btn-action btn-back']) ?>
            
            <?php if ($isAdmin): ?>
                <?= Html::a('<i class="bi bi-pencil-square"></i> Update Status', ['update', 'document_id' => $model->document_id], ['class' => 'btn btn-action btn-edit']) ?>
            <?php elseif ($isTeacherOrParent): ?>
                <?= Html::a('<i class="bi bi-pencil-square"></i> Edit', ['update', 'document_id' => $model->document_id], ['class' => 'btn btn-action btn-edit']) ?>
                <?= Html::a('<i class="bi bi-trash"></i> Delete', ['delete', 'document_id' => $model->document_id], [
                    'class' => 'btn btn-action btn-delete',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this document? This action cannot be undone.',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        </div>

        <!-- Content -->
        <div class="document-content">
            <!-- Information Grid -->
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-person"></i>
                        User ID
                    </div>
                    <div class="info-value"><?= Html::encode($model->user_id) ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-file-text"></i>
                        Document Name
                    </div>
                    <div class="info-value"><?= Html::encode($model->document_name) ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-tag"></i>
                        Document Type
                    </div>
                    <div class="info-value"><?= Html::encode($model->document_type) ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-folder"></i>
                        Category
                    </div>
                    <div class="info-value">
                        <?= $model->category ? Html::encode($model->category->category_name) : '<em class="text-muted">No category</em>' ?>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-toggle-on"></i>
                        Status
                    </div>
                    <div class="info-value">
                        <?php
                        $statusClasses = [
                            \app\models\UserDocuments::STATUS_APPROVED => 'status-approved',
                            \app\models\UserDocuments::STATUS_REJECTED => 'status-rejected',
                            \app\models\UserDocuments::STATUS_PENDING_REVIEW => 'status-pending',
                            \app\models\UserDocuments::STATUS_EXPIRED => 'status-expired',
                        ];
                        $statusIcons = [
                            \app\models\UserDocuments::STATUS_APPROVED => 'check-circle-fill',
                            \app\models\UserDocuments::STATUS_REJECTED => 'x-circle-fill',
                            \app\models\UserDocuments::STATUS_PENDING_REVIEW => 'clock-fill',
                            \app\models\UserDocuments::STATUS_EXPIRED => 'hourglass-bottom',
                        ];
                        ?>
                        <span class="status-badge <?= $statusClasses[$model->status] ?? 'status-pending' ?>">
                            <i class="bi bi-<?= $statusIcons[$model->status] ?? 'question-circle' ?>"></i>
                            <?= $model->displayStatus() ?>
                        </span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-calendar-plus"></i>
                        Upload Date
                    </div>
                    <div class="info-value">
                        <?= Yii::$app->formatter->asDatetime($model->upload_date, 'php:M d, Y h:i A') ?>
                    </div>
                </div>

                <?php if ($model->expiry_date): ?>
                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-calendar-x"></i>
                        Expiry Date
                    </div>
                    <div class="info-value">
                        <?= Yii::$app->formatter->asDate($model->expiry_date, 'php:M d, Y') ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($model->reviewed_date): ?>
                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-check-square"></i>
                        Reviewed Date
                    </div>
                    <div class="info-value">
                        <?= Yii::$app->formatter->asDatetime($model->reviewed_date, 'php:M d, Y h:i A') ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- File Preview -->
            <div class="file-preview-card">
                <h4>
                    <i class="bi bi-paperclip"></i>
                    Attached File
                </h4>
                <?php if ($model->file_url): ?>
                <div class="file-preview-content">
                    <div class="file-icon-large">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <div class="file-info">
                        <div class="file-name-large">
                            <?= basename($model->file_url) ?>
                        </div>
                        <div class="file-meta">
                            Document file • Uploaded on <?= Yii::$app->formatter->asDate($model->upload_date, 'php:M d, Y') ?>
                        </div>
                    </div>
                    <div class="file-actions">
                        <?= Html::a(
                            '<i class="bi bi-eye"></i> View File',
                            ['download', 'document_id' => $model->document_id, 'inline' => 1],
                            [
                                'class' => 'btn-file-action btn-view-file',
                                'target' => '_blank',
                                'data-pjax' => '0'
                            ]
                        ) ?>
                        <?= Html::a(
                            '<i class="bi bi-download"></i> Download',
                            ['download', 'document_id' => $model->document_id],
                            [
                                'class' => 'btn-file-action btn-download-file',
                                'data-pjax' => '0'
                            ]
                        ) ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="no-file-message">
                    <i class="bi bi-file-earmark-x"></i>
                    <p>No file attached to this document</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Rejection Notes -->
            <?php if ($model->status === \app\models\UserDocuments::STATUS_REJECTED && $model->rejection_notes): ?>
            <div class="notes-card rejection">
                <h4>
                    <i class="bi bi-exclamation-triangle-fill" style="color: #ef4444;"></i>
                    Rejection Notes
                </h4>
                <div class="notes-content">
                    <?= nl2br(Html::encode($model->rejection_notes)) ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Additional Notes -->
            <?php if ($model->notes): ?>
            <div class="notes-card">
                <h4>
                    <i class="bi bi-sticky"></i>
                    Additional Notes
                </h4>
                <div class="notes-content">
                    <?= nl2br(Html::encode($model->notes)) ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Timeline -->
            <div class="timeline-card">
                <h4>
                    <i class="bi bi-clock-history"></i>
                    Document Timeline
                </h4>
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="bi bi-upload"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">Document Uploaded</div>
                        <div class="timeline-time">
                            <?= Yii::$app->formatter->asDatetime($model->upload_date, 'php:M d, Y \a\t h:i A') ?>
                        </div>
                    </div>
                </div>

                <?php if ($model->reviewed_date): ?>
                <div class="timeline-item">
                    <div class="timeline-icon" style="background: linear-gradient(135deg, <?= $model->status === \app\models\UserDocuments::STATUS_APPROVED ? '#10b981 0%, #059669 100%' : '#ef4444 0%, #dc2626 100%' ?>);">
                        <i class="bi bi-<?= $model->status === \app\models\UserDocuments::STATUS_APPROVED ? 'check-circle-fill' : 'x-circle-fill' ?>"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">
                            Document <?= $model->status === \app\models\UserDocuments::STATUS_APPROVED ? 'Approved' : 'Rejected' ?>
                        </div>
                        <div class="timeline-time">
                            <?= Yii::$app->formatter->asDatetime($model->reviewed_date, 'php:M d, Y \a\t h:i A') ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($model->expiry_date): ?>
                <div class="timeline-item">
                    <div class="timeline-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">Expiry Date</div>
                        <div class="timeline-time">
                            <?= Yii::$app->formatter->asDate($model->expiry_date, 'php:M d, Y') ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>