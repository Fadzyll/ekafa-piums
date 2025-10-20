<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Select Your Role';
?>

<div class="auth-wrapper">
    <div class="auth-container fade-in-up" style="max-width: 600px;">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="bi bi-key"></i>
                </div>
                <h1 class="auth-title">Choose Access Type</h1>
                <p class="auth-subtitle">Select your role to continue to E-KAFA PIUMS</p>
            </div>

            <div class="role-grid">
                <!-- Admin Card -->
                <a href="<?= Url::to(['site/login', 'role' => 'Admin']) ?>" class="role-card-modern">
                    <div class="role-icon-modern">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div class="role-title">Admin</div>
                    <div class="role-description">System Administrator</div>
                </a>

                <!-- Teacher Card -->
                <a href="<?= Url::to(['site/login', 'role' => 'Teacher']) ?>" class="role-card-modern">
                    <div class="role-icon-modern">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                    <div class="role-title">Teacher</div>
                    <div class="role-description">KAFA Educator</div>
                </a>

                <!-- Parent Card -->
                <a href="<?= Url::to(['site/login', 'role' => 'Parent']) ?>" class="role-card-modern">
                    <div class="role-icon-modern">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="role-title">Parent</div>
                    <div class="role-description">Student Guardian</div>
                </a>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-4">
                <?= Html::a('← Back to Home', ['site/index'], [
                    'class' => 'text-decoration-none',
                    'style' => 'color: var(--ekafa-gray-600);'
                ]) ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCss(<<<CSS
.role-title {
    font-weight: 700;
    font-size: 1.125rem;
    margin-top: 0.5rem;
    color: var(--ekafa-gray-900);
}

.role-description {
    font-size: 0.875rem;
    color: var(--ekafa-gray-600);
    margin-top: 0.25rem;
}

.role-card-modern:hover .role-title {
    color: var(--ekafa-primary);
}
CSS);
?>