<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Documents';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
/* Modern List Container */
.documents-list-container {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-top: 1.5rem;
}

/* Document List Item */
.document-list-item {
    background: white;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid transparent;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.document-list-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, #004135, #11684d);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.document-list-item:hover::before {
    transform: scaleY(1);
}

.document-list-item:hover {
    box-shadow: 0 8px 24px rgba(0, 65, 53, 0.12);
    transform: translateX(4px);
}

.document-list-item.has-upload {
    border-left-color: #10b981;
}

.document-list-item.is-required:not(.has-upload) {
    border-left-color: #ef4444;
}

/* List Item Layout */
.doc-list-row {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}

.doc-number {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #004135, #11684d);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.125rem;
    box-shadow: 0 4px 12px rgba(0, 65, 53, 0.2);
}

.doc-number.completed {
    background: linear-gradient(135deg, #10b981, #059669);
}

.doc-number.pending {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.doc-number.rejected {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.doc-info {
    flex: 1;
    min-width: 0;
}

.doc-title-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.25rem;
    flex-wrap: wrap;
}

.doc-title {
    font-size: 1.0625rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.doc-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.badge-required {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
}

.badge-optional {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
}

.doc-description {
    color: #64748b;
    font-size: 0.9375rem;
    line-height: 1.5;
    margin: 0;
}

.doc-actions {
    flex-shrink: 0;
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

/* Status Badge */
.doc-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    white-space: nowrap;
    animation: fadeInScale 0.3s ease;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.status-pending {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
}

.status-completed {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
}

.status-rejected {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
}

/* Upload Area - Compact Style */
.upload-area-compact {
    margin-top: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-area-compact:hover {
    border-color: #004135;
    background: linear-gradient(135deg, #ecfdf5, #d1fae5);
    transform: scale(1.01);
}

.upload-area-compact input[type="file"] {
    display: none;
}

.btn-upload-compact {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #004135, #11684d);
    color: white;
    padding: 0.625rem 1.25rem;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 65, 53, 0.2);
}

.btn-upload-compact:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 65, 53, 0.3);
}

.upload-hint {
    color: #64748b;
    font-size: 0.8125rem;
    margin-top: 0.5rem;
}

/* Uploaded File Display */
.uploaded-file-display {
    margin-top: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-radius: 12px;
    border: 1px solid #bbf7d0;
}

.file-info-row {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.file-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.file-details {
    flex: 1;
    min-width: 0;
}

.file-name {
    font-weight: 600;
    color: #065f46;
    margin: 0 0 0.25rem 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.file-meta {
    color: #059669;
    font-size: 0.8125rem;
}

.file-actions {
    flex-shrink: 0;
    display: flex;
    gap: 0.5rem;
}

/* Admin Notes */
.admin-notes-alert {
    margin-top: 0.75rem;
    padding: 0.875rem 1rem;
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border-left: 4px solid #f59e0b;
    border-radius: 8px;
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

.admin-notes-alert strong {
    color: #92400e;
    display: block;
    margin-bottom: 0.25rem;
}

.admin-notes-alert p {
    color: #78350f;
    margin: 0;
    font-size: 0.9375rem;
}

/* Buttons */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-view {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.btn-view:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    color: white;
}

.btn-replace {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
}

.btn-replace:hover {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
}

/* Progress Indicator */
.progress-indicator {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.progress-stats {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.stat-item {
    flex: 1;
    min-width: 150px;
    text-align: center;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    background: linear-gradient(135deg, #004135, #11684d);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-label {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-top: 0.25rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .doc-list-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .doc-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .file-info-row {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .file-actions {
        width: 100%;
    }
    
    .progress-stats {
        flex-direction: column;
        gap: 1rem;
    }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.empty-state-icon {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}

.empty-state-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 0.5rem;
}
</style>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><i class="bi bi-files"></i> <?= Html::encode($this->title) ?></h3>
    </div>

    <div class="card-body">
        <?php if (empty($categories)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <div class="empty-state-title">No Documents Available</div>
                <p>No document categories are available at the moment.</p>
            </div>
        <?php else: ?>
            <?php
            // Calculate progress statistics
            $totalDocs = count($categories);
            $uploadedCount = count($uploadedDocuments);
            $completedCount = 0;
            $pendingCount = 0;
            $requiredCount = 0;
            
            foreach ($categories as $category) {
                if ($category->is_required) $requiredCount++;
                if (isset($uploadedDocuments[$category->category_id])) {
                    $status = $uploadedDocuments[$category->category_id]->status;
                    if ($status === 'Completed') $completedCount++;
                    if ($status === 'Pending Review') $pendingCount++;
                }
            }
            ?>
            
            <!-- Progress Indicator -->
            <div class="progress-indicator">
                <div class="progress-stats">
                    <div class="stat-item">
                        <div class="stat-value"><?= $uploadedCount ?>/<?= $totalDocs ?></div>
                        <div class="stat-label">Uploaded</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= $completedCount ?></div>
                        <div class="stat-label">Completed</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= $pendingCount ?></div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= $requiredCount ?></div>
                        <div class="stat-label">Required</div>
                    </div>
                </div>
            </div>

            <!-- Documents List -->
            <div class="documents-list-container">
                <?php foreach ($categories as $index => $category): ?>
                    <?php 
                    $uploaded = $uploadedDocuments[$category->category_id] ?? null;
                    $hasUpload = !empty($uploaded);
                    $isRequired = $category->is_required;
                    
                    // Determine status class for numbering
                    $numberClass = '';
                    if ($uploaded) {
                        if ($uploaded->status === 'Completed') $numberClass = 'completed';
                        elseif ($uploaded->status === 'Pending Review') $numberClass = 'pending';
                        elseif ($uploaded->status === 'Rejected') $numberClass = 'rejected';
                    }
                    ?>
                    
                    <div class="document-list-item <?= $hasUpload ? 'has-upload' : '' ?> <?= $isRequired ? 'is-required' : '' ?>">
                        <div class="doc-list-row">
                            <!-- Document Number -->
                            <div class="doc-number <?= $numberClass ?>">
                                <?php if ($uploaded && $uploaded->status === 'Completed'): ?>
                                    <i class="bi bi-check-lg"></i>
                                <?php else: ?>
                                    <?= $index + 1 ?>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Document Info -->
                            <div class="doc-info">
                                <div class="doc-title-row">
                                    <h4 class="doc-title"><?= Html::encode($category->category_name) ?></h4>
                                    <span class="doc-badge <?= $isRequired ? 'badge-required' : 'badge-optional' ?>">
                                        <i class="bi bi-<?= $isRequired ? 'exclamation-circle' : 'info-circle' ?>"></i>
                                        <?= $isRequired ? 'Required' : 'Optional' ?>
                                    </span>
                                </div>
                                <p class="doc-description"><?= Html::encode($category->description) ?></p>
                            </div>
                            
                            <!-- Status Badge -->
                            <?php if ($uploaded): ?>
                                <div class="doc-actions">
                                    <?php
                                    $statusClass = [
                                        'Pending Review' => 'status-pending',
                                        'Completed' => 'status-completed',
                                        'Rejected' => 'status-rejected',
                                    ];
                                    $statusIcon = [
                                        'Pending Review' => 'clock-history',
                                        'Completed' => 'check-circle-fill',
                                        'Rejected' => 'x-circle-fill',
                                    ];
                                    ?>
                                    <span class="doc-status-badge <?= $statusClass[$uploaded->status] ?? '' ?>">
                                        <i class="bi bi-<?= $statusIcon[$uploaded->status] ?? 'clock' ?>"></i>
                                        <?= Html::encode($uploaded->status) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Uploaded File Display -->
                        <?php if ($uploaded): ?>
                            <div class="uploaded-file-display">
                                <div class="file-info-row">
                                    <div class="file-icon">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                    </div>
                                    <div class="file-details">
                                        <div class="file-name"><?= basename($uploaded->file_url) ?></div>
                                        <div class="file-meta">
                                            <i class="bi bi-calendar-check"></i>
                                            Uploaded: <?= Yii::$app->formatter->asDatetime($uploaded->upload_date) ?>
                                        </div>
                                    </div>
                                    <div class="file-actions">
                                        <?= Html::a(
                                            '<i class="bi bi-eye"></i> View',
                                            ['download', 'document_id' => $uploaded->document_id, 'inline' => 1],  // ✅ NEW,
                                            ['class' => 'btn-modern btn-view', 'target' => '_blank']
                                        ) ?>
                                        <button class="btn-modern btn-replace" 
                                                onclick="document.getElementById('file-<?= $category->category_id ?>').click()">
                                            <i class="bi bi-arrow-repeat"></i> Replace
                                        </button>
                                    </div>
                                </div>
                                
                                <?php if ($uploaded->admin_notes): ?>
                                    <div class="admin-notes-alert">
                                        <strong><i class="bi bi-info-circle-fill"></i> Admin Notes</strong>
                                        <p><?= Html::encode($uploaded->admin_notes) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <!-- Upload Area -->
                            <div class="upload-area-compact" 
                                 onclick="document.getElementById('file-<?= $category->category_id ?>').click()">
                                <button type="button" class="btn-upload-compact">
                                    <i class="bi bi-cloud-upload"></i>
                                    Click to Upload Document
                                </button>
                                <div class="upload-hint">
                                    <i class="bi bi-info-circle"></i>
                                    Supported: PDF, JPG, PNG • Maximum size: 5MB
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Hidden Form -->
                        <form method="post" enctype="multipart/form-data" id="form-<?= $category->category_id ?>" style="display: none;">
                            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                            <?= Html::hiddenInput('category_id', $category->category_id) ?>
                            <input type="file" 
                                   id="file-<?= $category->category_id ?>" 
                                   name="file" 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   onchange="this.form.submit()">
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Add smooth animations on page load
document.addEventListener('DOMContentLoaded', function() {
    const listItems = document.querySelectorAll('.document-list-item');
    listItems.forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            setTimeout(() => {
                item.style.transition = 'all 0.5s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});
</script>