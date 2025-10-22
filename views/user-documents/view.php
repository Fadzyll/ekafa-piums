<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\UserDocuments $model */

$this->title = 'Document #' . $model->document_id;
$this->params['breadcrumbs'][] = ['label' => 'User Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
\yii\web\YiiAsset::register($this);
?>

<style>
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

.status-completed {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.status-incomplete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.status-pending {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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

.info-card, .file-preview-card, .timeline-card {
    animation: slideInUp 0.5s ease;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .action-bar {
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
                Complete document details and file information
            </p>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <?= Html::a('<i class="bi bi-arrow-left"></i> Back to List', ['index'], ['class' => 'btn btn-action btn-back']) ?>
            <?= Html::a('<i class="bi bi-pencil-square"></i> Edit', ['update', 'document_id' => $model->document_id], ['class' => 'btn btn-action btn-edit']) ?>
            <?php if ($model->file_url): ?>
                <?= Html::a('<i class="bi bi-download"></i> Download', Yii::getAlias('@web/' . $model->file_url), [
                    'class' => 'btn btn-action btn-download',
                    'target' => '_blank',
                    'download' => basename($model->file_url)
                ]) ?>
            <?php endif; ?>
            <?= Html::a('<i class="bi bi-trash"></i> Delete', ['delete', 'document_id' => $model->document_id], [
                'class' => 'btn btn-action btn-delete',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this document? This action cannot be undone.',
                    'method' => 'post',
                ],
            ]) ?>
        </div>

        <!-- Content -->
        <div class="document-content">
            <!-- Information Grid -->
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-hash"></i>
                        Document ID
                    </div>
                    <div class="info-value"><?= Html::encode($model->document_id) ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-person"></i>
                        User ID
                    </div>
                    <div class="info-value">
                        <span style="background: #dbeafe; color: #1e40af; padding: 0.5rem 1rem; border-radius: 8px;">
                            User #<?= Html::encode($model->user_id) ?>
                        </span>
                    </div>
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
                        <i class="bi bi-flag"></i>
                        Status
                    </div>
                    <div class="info-value">
                        <?php
                        $statusClass = [
                            'Completed' => 'status-completed',
                            'Incomplete' => 'status-incomplete',
                            'Pending Review' => 'status-pending',
                        ];
                        $statusIcon = [
                            'Completed' => 'check-circle-fill',
                            'Incomplete' => 'x-circle-fill',
                            'Pending Review' => 'clock-fill',
                        ];
                        ?>
                        <span class="status-badge <?= $statusClass[$model->status] ?? '' ?>">
                            <i class="bi bi-<?= $statusIcon[$model->status] ?? 'question-circle' ?>"></i>
                            <?= Html::encode($model->status) ?>
                        </span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-calendar-plus"></i>
                        Upload Date
                    </div>
                    <div class="info-value">
                        <?= $model->upload_date ? Yii::$app->formatter->asDatetime($model->upload_date, 'php:M d, Y h:i A') : 'N/A' ?>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">
                        <i class="bi bi-folder"></i>
                        Category
                    </div>
                    <div class="info-value">
                        <?= $model->category ? Html::encode($model->category->category_name) : 'N/A' ?>
                    </div>
                </div>
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
                            <?php
                            $ext = pathinfo($model->file_url, PATHINFO_EXTENSION);
                            $icon = 'file-earmark';
                            if ($ext === 'pdf') $icon = 'file-earmark-pdf';
                            elseif (in_array($ext, ['jpg', 'jpeg', 'png'])) $icon = 'file-earmark-image';
                            ?>
                            <i class="bi bi-<?= $icon ?>"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name-large"><?= basename($model->file_url) ?></div>
                            <div class="file-meta">
                                <?php
                                $filePath = Yii::getAlias('@webroot/' . $model->file_url);
                                if (file_exists($filePath)) {
                                    $fileSize = filesize($filePath);
                                    $sizeInMB = $fileSize / (1024 * 1024);
                                    echo 'Size: ' . ($sizeInMB > 1 ? number_format($sizeInMB, 2) . ' MB' : number_format($fileSize / 1024, 2) . ' KB');
                                    echo ' • Type: ' . strtoupper($ext);
                                }
                                ?>
                            </div>
                        </div>
                        <div class="file-actions">
                            <?= Html::a('<i class="bi bi-eye"></i> View', Yii::getAlias('@web/' . $model->file_url), [
                                'class' => 'btn btn-file-action btn-view-file',
                                'target' => '_blank'
                            ]) ?>
                            <?= Html::a('<i class="bi bi-download"></i> Download', Yii::getAlias('@web/' . $model->file_url), [
                                'class' => 'btn btn-file-action btn-download-file',
                                'target' => '_blank',
                                'download' => basename($model->file_url)
                            ]) ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-file-message">
                        <i class="bi bi-inbox"></i>
                        <div>No file uploaded for this document</div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Timeline -->
            <div class="timeline-card">
                <h4>
                    <i class="bi bi-clock-history"></i>
                    Document Timeline
                </h4>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">Document Created</div>
                        <div class="timeline-time">
                            <?= Yii::$app->formatter->asDatetime($model->upload_date, 'php:M d, Y h:i A') ?>
                        </div>
                    </div>
                </div>

                <?php if ($model->status === 'Completed'): ?>
                    <div class="timeline-item">
                        <div class="timeline-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Document Approved</div>
                            <div class="timeline-time">Status changed to Completed</div>
                        </div>
                    </div>
                <?php elseif ($model->status === 'Pending Review'): ?>
                    <div class="timeline-item">
                        <div class="timeline-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Awaiting Review</div>
                            <div class="timeline-time">Document is pending review</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>