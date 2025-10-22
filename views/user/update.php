<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var bool $isRestricted */

$this->title = 'Update User: ' . Html::encode($model->username);
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($model->username), 'url' => ['view', 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
$this->params['hideTitle'] = true;

?>

<style>
.update-user-wrapper {
    max-width: 900px;
    margin: 0 auto;
}

.update-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
}

.user-info-sidebar {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    height: fit-content;
    position: sticky;
    top: 20px;
}

.sidebar-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin: 0 auto 1.5rem;
    position: relative;
    overflow: hidden;
    border: 4px solid #f0f0f0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.sidebar-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.sidebar-avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
    font-weight: bold;
}

.sidebar-name {
    text-align: center;
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.sidebar-role {
    text-align: center;
    margin-bottom: 1.5rem;
}

.sidebar-role-badge {
    display: inline-block;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.sidebar-role-badge.admin {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.sidebar-role-badge.teacher {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.sidebar-role-badge.parent {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.sidebar-divider {
    height: 2px;
    background: linear-gradient(90deg, transparent, #e9ecef, transparent);
    margin: 1.5rem 0;
}

.sidebar-info-item {
    margin-bottom: 1rem;
}

.sidebar-info-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 0.3rem;
    display: flex;
    align-items: center;
}

.sidebar-info-label i {
    margin-right: 0.4rem;
    color: #667eea;
}

.sidebar-info-value {
    font-size: 0.9rem;
    color: #333;
    font-weight: 500;
    word-break: break-word;
}

.quick-actions {
    margin-top: 1.5rem;
}

.quick-action-btn {
    width: 100%;
    padding: 0.7rem;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
}

.quick-action-btn.view {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.quick-action-btn.delete {
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    color: white;
}

.quick-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    color: white;
}

.update-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.update-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem;
    color: white;
    position: relative;
}

.update-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
}

.update-header h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.8rem;
    font-weight: 600;
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.update-header p {
    margin: 0;
    opacity: 0.9;
    position: relative;
    z-index: 1;
}

.update-body {
    padding: 2rem;
}

.restriction-alert {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
    border: 2px solid #ffc107;
    border-radius: 15px;
    padding: 1.2rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: start;
    gap: 1rem;
    animation: shake 0.5s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.restriction-alert-icon {
    width: 45px;
    height: 45px;
    background: #ffc107;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
    font-size: 1.3rem;
}

.restriction-alert-content {
    flex: 1;
}

.restriction-alert-content h5 {
    margin: 0 0 0.5rem 0;
    color: #856404;
    font-weight: 600;
    font-size: 1.1rem;
}

.restriction-alert-content p {
    margin: 0;
    color: #856404;
    line-height: 1.5;
}

.restriction-list {
    margin: 0.8rem 0 0 0;
    padding-left: 1.5rem;
    color: #856404;
}

.restriction-list li {
    margin-bottom: 0.3rem;
}

.changes-indicator {
    background: linear-gradient(135deg, #e8f4ff 0%, #d4e8ff 100%);
    border-left: 4px solid #667eea;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.changes-indicator i {
    color: #667eea;
    font-size: 1.5rem;
}

.changes-indicator span {
    color: #333;
    font-weight: 500;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.user-info-sidebar {
    animation: slideInLeft 0.5s ease;
}

.update-card {
    animation: slideInRight 0.5s ease;
}

@media (max-width: 992px) {
    .update-layout {
        grid-template-columns: 1fr;
    }
    
    .user-info-sidebar {
        position: static;
    }
    
    .update-user-wrapper {
        padding: 0 1rem;
    }
}
</style>

<div class="update-user-wrapper">
    <div class="update-layout">
        <!-- Sidebar with User Info -->
        <div class="user-info-sidebar">
            <div class="sidebar-avatar">
                <?php 
                // Load userDetails relation if not loaded
                if (!$model->isRelationPopulated('userDetails')) {
                    $model->refresh();
                }
                ?>
                <?php if ($model->userDetails && $model->userDetails->profile_picture_url): ?>
                    <img src="<?= Yii::getAlias('@web/' . $model->userDetails->profile_picture_url) ?>" alt="<?= Html::encode($model->username) ?>">
                <?php else: ?>
                    <div class="sidebar-avatar-placeholder">
                        <?= strtoupper(substr($model->username, 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="sidebar-name"><?= Html::encode($model->username) ?></div>
            
            <div class="sidebar-role">
                <?php
                $roleClass = [
                    'Admin' => 'admin',
                    'Teacher' => 'teacher',
                    'Parent' => 'parent',
                ];
                ?>
                <span class="sidebar-role-badge <?= $roleClass[$model->role] ?? '' ?>">
                    <?= Html::encode($model->role) ?>
                </span>
            </div>
            
            <div class="sidebar-divider"></div>
            
            <div class="sidebar-info-item">
                <div class="sidebar-info-label">
                    <i class="fas fa-id-badge"></i>
                    User ID
                </div>
                <div class="sidebar-info-value">#<?= Html::encode($model->user_id) ?></div>
            </div>
            
            <div class="sidebar-info-item">
                <div class="sidebar-info-label">
                    <i class="fas fa-envelope"></i>
                    Email
                </div>
                <div class="sidebar-info-value"><?= Html::encode($model->email) ?></div>
            </div>
            
            <div class="sidebar-info-item">
                <div class="sidebar-info-label">
                    <i class="fas fa-info-circle"></i>
                    Account Status
                </div>
                <div class="sidebar-info-value">
                    <?php
                    $statusLabels = [
                        10 => '<span class="badge bg-success">Active</span>',
                        9 => '<span class="badge bg-warning text-dark">Inactive</span>',
                        0 => '<span class="badge bg-danger">Deleted</span>',
                    ];
                    echo $statusLabels[$model->status] ?? '<span class="badge bg-secondary">Unknown</span>';
                    ?>
                </div>
            </div>
            
            <div class="sidebar-info-item">
                <div class="sidebar-info-label">
                    <i class="fas fa-calendar-plus"></i>
                    Joined
                </div>
                <div class="sidebar-info-value">
                    <?= $model->date_registered ? Yii::$app->formatter->asDate($model->date_registered, 'php:M d, Y') : 'N/A' ?>
                </div>
            </div>
            
            <div class="sidebar-info-item">
                <div class="sidebar-info-label">
                    <i class="fas fa-sign-in-alt"></i>
                    Last Login
                </div>
                <div class="sidebar-info-value">
                    <?= $model->last_login ? Yii::$app->formatter->asRelativeTime($model->last_login) : 'Never' ?>
                </div>
            </div>
            
            <div class="sidebar-info-item">
                <div class="sidebar-info-label">
                    <i class="fas fa-edit"></i>
                    Last Updated
                </div>
                <div class="sidebar-info-value">
                    <?= $model->updated_at ? Yii::$app->formatter->asRelativeTime($model->updated_at) : 'N/A' ?>
                </div>
            </div>
            
            <div class="sidebar-divider"></div>
            
            <div class="quick-actions">
                <?= Html::a('<i class="fas fa-eye"></i> View Profile', ['view', 'user_id' => $model->user_id], ['class' => 'quick-action-btn view']) ?>
                <?= Html::a('<i class="fas fa-trash"></i> Delete User', ['delete', 'user_id' => $model->user_id], [
                    'class' => 'quick-action-btn delete',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this user?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- Main Update Form -->
        <div class="update-card">
            <div class="update-header">
                <h3>
                    <i class="fas fa-user-edit"></i>
                    Update User Information
                </h3>
                <p>Modify the user details below and save your changes</p>
            </div>

            <div class="update-body">
                <?php if ($isRestricted): ?>
                    <div class="restriction-alert">
                        <div class="restriction-alert-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="restriction-alert-content">
                            <h5><i class="fas fa-exclamation-triangle"></i> Restricted Account</h5>
                            <p>This account has restricted editing permissions because the user role is <strong><?= Html::encode($model->role) ?></strong>.</p>
                            <ul class="restriction-list">
                                <li><i class="fas fa-times-circle"></i> Email address cannot be changed</li>
                                <li><i class="fas fa-times-circle"></i> Password cannot be modified</li>
                                <li><i class="fas fa-times-circle"></i> Role cannot be changed</li>
                                <li><i class="fas fa-check-circle" style="color: #28a745;"></i> Username can still be updated</li>
                            </ul>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="changes-indicator">
                        <i class="fas fa-info-circle"></i>
                        <span>You can modify all fields for this user. Leave password blank to keep the current password.</span>
                    </div>
                <?php endif; ?>

                <?= $this->render('_form', [
                    'model' => $model,
                    'isRestricted' => $isRestricted,
                ]) ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
    // Track form changes
    let formChanged = false;
    
    $('#user-form').on('change input', 'input, select', function() {
        if (!formChanged) {
            formChanged = true;
            $('.changes-indicator').html('<i class="fas fa-exclamation-circle" style="color: #ffc107;"></i><span style="color: #856404;">You have unsaved changes!</span>');
            $('.changes-indicator').css({
                'background': 'linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%)',
                'border-left-color': '#ffc107'
            });
        }
    });
    
    // Warn before leaving if there are unsaved changes
    $(window).on('beforeunload', function() {
        if (formChanged) {
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });
    
    // Remove warning on form submit
    $('#user-form').on('submit', function() {
        $(window).off('beforeunload');
    });
JS);
?>