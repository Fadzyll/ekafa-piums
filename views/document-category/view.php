<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\DocumentCategory $model */

$this->title = $model->category_name;
$this->params['breadcrumbs'][] = ['label' => 'Document Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;
\yii\web\YiiAsset::register($this);
?>

<style>
.view-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

.btn-action-bar {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
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

.btn-edit-action {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.btn-edit-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    color: white;
}

.btn-delete-action {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-delete-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    color: white;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 2rem;
}

.info-card {
    background: #f9fafb;
    border-radius: 16px;
    padding: 1.5rem;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.info-card:hover {
    border-color: #667eea;
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
}

.info-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
}

.description-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin: 0 2rem 2rem 2rem;
    border: 2px solid #e5e7eb;
}

.description-card h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.description-text {
    color: #4b5563;
    line-height: 1.75;
    font-size: 1rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.875rem;
}

.status-active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.status-inactive {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.875rem;
}

.role-teacher {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.role-parent {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.role-both {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
}

.requirement-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.875rem;
}

.requirement-yes {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.requirement-no {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.stats-section {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin: 0 2rem 2rem 2rem;
    border: 2px solid #e5e7eb;
}

.stats-section h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.stat-item {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 1.5rem;
    color: white;
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
    font-weight: 600;
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

.info-card, .description-card, .stats-section {
    animation: slideInUp 0.5s ease;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .action-bar {
        flex-direction: column;
    }
    
    .btn-action-bar {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="document-category-view">
    <div class="card shadow-lg border-0">
        <!-- Header -->
        <div class="view-header">
            <div class="view-title">
                <i class="bi bi-folder-fill"></i>
                <?= Html::encode($this->title) ?>
            </div>
            <p class="view-subtitle">
                Complete details and information about this document category
            </p>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <?= Html::a('<i class="bi bi-arrow-left"></i> Back to List', ['index'], ['class' => 'btn btn-action-bar btn-back']) ?>
            <?= Html::a('<i class="bi bi-pencil-square"></i> Edit Category', ['update', 'id' => $model->category_id], ['class' => 'btn btn-action-bar btn-edit-action']) ?>
            <?= Html::a('<i class="bi bi-trash"></i> Delete', ['delete', 'id' => $model->category_id], [
                'class' => 'btn btn-action-bar btn-delete-action',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this category? This action cannot be undone.',
                    'method' => 'post',
                ],
            ]) ?>
        </div>

        <!-- Information Grid -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-hash"></i> Category ID
                </div>
                <div class="info-value"><?= Html::encode($model->category_id) ?></div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-folder"></i> Category Name
                </div>
                <div class="info-value"><?= Html::encode($model->category_name) ?></div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-people"></i> Required For Role
                </div>
                <div class="info-value">
                    <?php
                    $roleClass = [
                        'Teacher' => 'role-teacher',
                        'Parent' => 'role-parent',
                        'Both' => 'role-both',
                    ];
                    $roleIcon = [
                        'Teacher' => 'person-workspace',
                        'Parent' => 'people',
                        'Both' => 'people-fill',
                    ];
                    ?>
                    <span class="role-badge <?= $roleClass[$model->required_for_role] ?? '' ?>">
                        <i class="bi bi-<?= $roleIcon[$model->required_for_role] ?? 'person' ?>"></i>
                        <?= Html::encode($model->required_for_role) ?>
                    </span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-exclamation-circle"></i> Is Required?
                </div>
                <div class="info-value">
                    <span class="requirement-badge <?= $model->is_required ? 'requirement-yes' : 'requirement-no' ?>">
                        <i class="bi bi-<?= $model->is_required ? 'exclamation-triangle' : 'check-circle' ?>"></i>
                        <?= $model->is_required ? 'Mandatory' : 'Optional' ?>
                    </span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-toggle-on"></i> Status
                </div>
                <div class="info-value">
                    <span class="status-badge <?= $model->status === 'Active' ? 'status-active' : 'status-inactive' ?>">
                        <i class="bi bi-<?= $model->status === 'Active' ? 'check-circle-fill' : 'x-circle-fill' ?>"></i>
                        <?= Html::encode($model->status) ?>
                    </span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="bi bi-calendar-plus"></i> Created At
                </div>
                <div class="info-value">
                    <?= $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : 'N/A' ?>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="description-card">
            <h4>
                <i class="bi bi-card-text"></i>
                Description
            </h4>
            <div class="description-text">
                <?= $model->description ? Html::encode($model->description) : '<em class="text-muted">No description provided</em>' ?>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <h4>
                <i class="bi bi-graph-up"></i>
                Document Statistics
            </h4>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">
                        <?= \app\models\UserDocuments::find()
                            ->where(['category_id' => $model->category_id])
                            ->count() ?>
                    </div>
                    <div class="stat-label">Total Uploads</div>
                </div>
                <div class="stat-item" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="stat-number">
                        <?= \app\models\UserDocuments::find()
                            ->where(['category_id' => $model->category_id, 'status' => 'Completed'])
                            ->count() ?>
                    </div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-item" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="stat-number">
                        <?= \app\models\UserDocuments::find()
                            ->where(['category_id' => $model->category_id, 'status' => 'Pending Review'])
                            ->count() ?>
                    </div>
                    <div class="stat-label">Pending Review</div>
                </div>
                <div class="stat-item" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <div class="stat-number">
                        <?= \app\models\UserDocuments::find()
                            ->where(['category_id' => $model->category_id, 'status' => 'Incomplete'])
                            ->count() ?>
                    </div>
                    <div class="stat-label">Incomplete</div>
                </div>
            </div>
        </div>
    </div>
</div>