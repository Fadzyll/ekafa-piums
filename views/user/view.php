<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = Html::encode($model->username);
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;

\yii\web\YiiAsset::register($this);
?>

<style>
.profile-view-wrapper {
    max-width: 900px;
    margin: 0 auto;
}

.profile-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
    border: none;
    margin-bottom: 2rem;
}

.profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 3rem 2rem;
    text-align: center;
    position: relative;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin: 0 auto 1rem;
    position: relative;
    z-index: 1;
    border: 5px solid white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    overflow: hidden;
    background: white;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: white;
    font-weight: bold;
}

.profile-name {
    color: white;
    margin: 0 0 0.5rem 0;
    font-weight: 600;
    font-size: 2rem;
    position: relative;
    z-index: 1;
}

.profile-role-badge {
    display: inline-block;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-weight: 500;
    font-size: 1rem;
    position: relative;
    z-index: 1;
}

.badge-admin-view {
    background: rgba(255,255,255,0.3);
    color: white;
    border: 2px solid white;
}

.badge-teacher-view {
    background: rgba(240,147,251,0.3);
    color: white;
    border: 2px solid white;
}

.badge-parent-view {
    background: rgba(79,172,254,0.3);
    color: white;
    border: 2px solid white;
}

.profile-body {
    padding: 2.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-item {
    background: #f8f9ff;
    padding: 1.5rem;
    border-radius: 15px;
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.info-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.info-label i {
    margin-right: 0.5rem;
    color: #667eea;
}

.info-value {
    font-size: 1.1rem;
    color: #333;
    font-weight: 500;
}

.action-buttons-modern {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
    padding-top: 1.5rem;
    border-top: 2px solid #f0f0f0;
}

.btn-action-modern {
    padding: 0.8rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-action-modern i {
    font-size: 1.1rem;
}

.btn-back-modern {
    background: #e9ecef;
    color: #495057;
}

.btn-back-modern:hover {
    background: #dee2e6;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    color: #495057;
}

.btn-update-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-update-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-delete-modern {
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    color: white;
}

.btn-delete-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(245, 87, 108, 0.4);
    color: white;
}

.timeline-section {
    margin-top: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8f9ff 0%, #e8eeff 100%);
    border-radius: 15px;
}

.timeline-section h4 {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.timeline-section h4 i {
    margin-right: 0.5rem;
}

.timeline-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.timeline-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: white;
    border-radius: 10px;
}

.timeline-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.timeline-content {
    flex: 1;
}

.timeline-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.2rem;
}

.timeline-date {
    font-size: 0.85rem;
    color: #6c757d;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.profile-card {
    animation: fadeInUp 0.5s ease;
}

@media (max-width: 768px) {
    .profile-header {
        padding: 2rem 1rem;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
    }
    
    .profile-name {
        font-size: 1.5rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons-modern {
        flex-direction: column;
    }
    
    .btn-action-modern {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="profile-view-wrapper">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php if ($model->userDetails && $model->userDetails->profile_picture_url): ?>
                    <img src="<?= Yii::getAlias('@web/' . $model->userDetails->profile_picture_url) ?>" alt="<?= Html::encode($model->username) ?>">
                <?php else: ?>
                    <div class="profile-avatar-placeholder">
                        <?= strtoupper(substr($model->username, 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <h3 class="profile-name"><?= Html::encode($model->username) ?></h3>
            
            <?php
            $roleClass = [
                'Admin' => 'badge-admin-view',
                'Teacher' => 'badge-teacher-view',
                'Parent' => 'badge-parent-view',
            ];
            ?>
            <span class="profile-role-badge <?= $roleClass[$model->role] ?? '' ?>">
                <i class="fas fa-<?= $model->role === 'Admin' ? 'user-shield' : ($model->role === 'Teacher' ? 'chalkboard-teacher' : 'user-friends') ?>"></i>
                <?= Html::encode($model->role) ?>
            </span>
        </div>

        <div class="profile-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </div>
                    <div class="info-value"><?= Html::encode($model->email) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-user"></i>
                        Username
                    </div>
                    <div class="info-value"><?= Html::encode($model->username) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-id-badge"></i>
                        User ID
                    </div>
                    <div class="info-value">#<?= Html::encode($model->user_id) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-user-tag"></i>
                        Role
                    </div>
                    <div class="info-value"><?= Html::encode($model->role) ?></div>
                </div>
            </div>

            <div class="timeline-section">
                <h4><i class="fas fa-history"></i> Account Timeline</h4>
                <div class="timeline-items">
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Account Created</div>
                            <div class="timeline-date">
                                <?= $model->date_registered ? Yii::$app->formatter->asDatetime($model->date_registered, 'php:F d, Y \a\t h:i A') : 'Not available' ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Last Login</div>
                            <div class="timeline-date">
                                <?= $model->last_login ? Yii::$app->formatter->asDatetime($model->last_login, 'php:F d, Y \a\t h:i A') : 'Never logged in' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-buttons-modern">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Back to List', ['index'], ['class' => 'btn-back-modern btn-action-modern']) ?>
                <?= Html::a('<i class="fas fa-edit"></i> Update Profile', ['update', 'user_id' => $model->user_id], ['class' => 'btn-update-modern btn-action-modern']) ?>
                <?= Html::a('<i class="fas fa-trash"></i> Delete User', ['delete', 'user_id' => $model->user_id], [
                    'class' => 'btn-delete-modern btn-action-modern',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this user? This action cannot be undone.',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
    // Add smooth hover effects
    $('.info-item').on('mouseenter', function() {
        $(this).css('border-left-width', '6px');
    }).on('mouseleave', function() {
        $(this).css('border-left-width', '4px');
    });
JS);
?>