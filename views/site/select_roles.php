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
                    'class' => 'text-decoration-none back-link'
                ]) ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Ensure CSS variables are available */
:root {
    --ekafa-primary: #004135;
    --ekafa-primary-light: #11684d;
    --ekafa-gray-900: #111827;
    --ekafa-gray-600: #4b5563;
    --ekafa-gray-200: #e5e7eb;
    --ekafa-gray-100: #f3f4f6;
    --radius-xl: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-md: 1rem;
    --transition-base: 250ms ease-in-out;
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
}

/* Role Grid Specific Styles */
.role-title {
    font-weight: 700;
    font-size: 1.125rem;
    margin-top: 0.5rem;
    color: var(--ekafa-gray-900);
    transition: color var(--transition-base);
}

.role-description {
    font-size: 0.875rem;
    color: var(--ekafa-gray-600);
    margin-top: 0.25rem;
}

.role-card-modern:hover .role-title {
    color: var(--ekafa-primary);
}

.back-link {
    color: var(--ekafa-gray-600);
    font-size: 0.9375rem;
    font-weight: 500;
    transition: color var(--transition-base);
}

.back-link:hover {
    color: var(--ekafa-primary);
}
</style>