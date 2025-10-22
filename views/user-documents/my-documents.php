<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Documents';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.document-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.document-card:hover {
    border-color: #004135;
    box-shadow: 0 4px 12px rgba(0, 65, 53, 0.1);
    transform: translateY(-2px);
}

.document-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
}

.document-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
}

.document-description {
    color: #6b7280;
    font-size: 0.9375rem;
    margin-bottom: 1rem;
}

.upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    background: #f9fafb;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #004135;
    background: #f3f4f6;
}

.upload-area input[type="file"] {
    display: none;
}

.btn-upload {
    background: linear-gradient(135deg, #004135, #11684d);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-upload:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 65, 53, 0.3);
}

.document-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-pending { background: #fef3c7; color: #92400e; }
.status-completed { background: #d1fae5; color: #065f46; }
.status-rejected { background: #fee2e2; color: #991b1b; }
</style>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><i class="bi bi-files"></i> <?= Html::encode($this->title) ?></h3>
    </div>

    <div class="card-body">
        <?php if (empty($categories)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No document categories available at the moment.
            </div>
        <?php else: ?>
            <?php foreach ($categories as $category): ?>
                <?php $uploaded = $uploadedDocuments[$category->category_id] ?? null; ?>
                
                <div class="document-card">
                    <div class="document-header">
                        <div>
                            <div class="document-title">
                                <?= Html::encode($category->category_name) ?>
                                <?php if ($category->is_required): ?>
                                    <span class="badge bg-danger ms-2">Required</span>
                                <?php endif; ?>
                            </div>
                            <div class="document-description">
                                <?= Html::encode($category->description) ?>
                            </div>
                        </div>
                        
                        <?php if ($uploaded): ?>
                            <?php
                            $statusClass = [
                                'Pending Review' => 'status-pending',
                                'Completed' => 'status-completed',
                                'Rejected' => 'status-rejected',
                            ];
                            ?>
                            <span class="document-status <?= $statusClass[$uploaded->status] ?? '' ?>">
                                <i class="bi bi-<?= $uploaded->status === 'Completed' ? 'check-circle' : 'clock' ?>"></i>
                                <?= Html::encode($uploaded->status) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($uploaded): ?>
                        <div class="d-flex gap-2 align-items-center">
                            <i class="bi bi-file-earmark-pdf" style="font-size: 2rem; color: #004135;"></i>
                            <div class="flex-grow-1">
                                <div class="fw-bold"><?= basename($uploaded->file_url) ?></div>
                                <div class="text-muted small">Uploaded: <?= Yii::$app->formatter->asDatetime($uploaded->upload_date) ?></div>
                                <?php if ($uploaded->admin_notes): ?>
                                    <div class="alert alert-warning mt-2 mb-0 py-2">
                                        <strong>Admin Notes:</strong> <?= Html::encode($uploaded->admin_notes) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex gap-2">
                                <?= Html::a('<i class="bi bi-eye"></i>', Yii::getAlias('@web/' . $uploaded->file_url), [
                                    'class' => 'btn btn-sm btn-info',
                                    'target' => '_blank'
                                ]) ?>
                                <button class="btn btn-sm btn-primary" onclick="document.getElementById('file-<?= $category->category_id ?>').click()">
                                    <i class="bi bi-arrow-repeat"></i> Replace
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="upload-area" onclick="document.getElementById('file-<?= $category->category_id ?>').click()">
                            <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #6b7280;"></i>
                            <div class="mt-2">
                                <button type="button" class="btn-upload">
                                    <i class="bi bi-upload"></i> Choose File
                                </button>
                            </div>
                            <div class="text-muted mt-2">PDF, JPG, PNG • Max 5MB</div>
                        </div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data" id="form-<?= $category->category_id ?>">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        <?= Html::hiddenInput('category_id', $category->category_id) ?>
                        <input type="file" 
                               id="file-<?= $category->category_id ?>" 
                               name="file" 
                               accept=".pdf,.jpg,.jpeg,.png"
                               onchange="document.getElementById('form-<?= $category->category_id ?>').submit()">
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>