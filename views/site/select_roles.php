<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Select Role';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-5">
    <div class="role-selection-wrapper p-4 mx-auto">
        <h2 class="text-center mb-5">Please Choose Access Type</h2>

        <div class="row justify-content-center g-4">
            <!-- Admin Card -->
            <div class="col-10 col-sm-6 col-md-4 col-lg-3">
                <a href="<?= Url::to(['site/login', 'role' => 'Admin']) ?>" class="text-decoration-none">
                    <div class="card role-card">
                        <i class="bi bi-shield-lock-fill role-icon"></i>
                        <span>Admin</span>
                    </div>
                </a>
            </div>

            <!-- Teacher Card -->
            <div class="col-10 col-sm-6 col-md-4 col-lg-3">
                <a href="<?= Url::to(['site/login', 'role' => 'Teacher']) ?>" class="text-decoration-none">
                    <div class="card role-card">
                        <i class="bi bi-person-badge-fill role-icon"></i>
                        <span>Teacher</span>
                    </div>
                </a>
            </div>

            <!-- Parent Card -->
            <div class="col-10 col-sm-6 col-md-4 col-lg-3">
                <a href="<?= Url::to(['site/login', 'role' => 'Parent']) ?>" class="text-decoration-none">
                    <div class="card role-card">
                        <i class="bi bi-people-fill role-icon"></i>
                        <span>Parent</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Outer container */
.role-selection-wrapper {
    background-color: rgba(255, 255, 255, 0.95);
    border-radius: 16px;
    padding: 40px 20px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    max-width: 1000px;
    max-height: 1000px;
    margin: auto;
    min-height: 50vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Role Cards */
.role-card {
    background-color: #014421;
    color: #ffffff;
    width: 100%;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border-radius: 12px;
    font-size: 1.25rem;
    transition: background-color 0.3s, transform 0.2s;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.role-card:hover {
    background-color: #02663c;
    transform: translateY(-4px);
}

.role-icon {
    font-size: 2.75rem;
    margin-bottom: 12px;
}
</style>