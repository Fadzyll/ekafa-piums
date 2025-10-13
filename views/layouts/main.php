<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="utf-8">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head(); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        html, body {
            padding-top: 56px;
            height: 100%;
        }
        .sidebar {
            width: 220px;
            height: 100%;
            position: fixed;
            top: 56px;
            left: 0;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .content-wrapper {
            margin-left: 220px;
            padding: 20px;
            min-height: 100vh; /* this ensures it grows beyond screen height */
            box-sizing: border-box;
        }
        .guest-center {
            display: flex;
            min-height: auto;      /* You might still want a minimum height for the page */
            text-align: center;    /* Keep this if you want content centered horizontally */
            padding: 20px;         /* Keep or adjust this padding as desired */
        }
        .breadcrumbs-wrapper {
            background-color: #f8f9fa;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .sidebar {
            background-color: #004135;
            height: 100vh;
            padding: 1rem;
        }
        .sidebar .nav-link {
            color: white;
            margin-bottom: 0.5rem;
        }
        .sidebar .nav-link:hover {
            background-color: #11684d;
            border-radius: 0.375rem;
        }
        footer#footer {
            background-color: #004135;
            color: white;
            padding: 1rem 0;
            clear: both; /* Helps ensure it drops below floated elements */
        }
        footer .container {
            max-width: 960px; /* match your content width */
            margin: 0 auto;
            text-align: center;
        }
        .footer-logged-in {
            margin-left: 220px;
        }
    </style>
</head>
<div class="page-wrapper d-flex flex-column min-vh-100">
<?php $this->beginBody(); ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/logo_putih.png', ['alt' => 'E-KAFA Logo', 'style' => 'height: 30px; vertical-align: middle;']) . ' E-KAFA PIUMS',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar navbar-expand-md navbar-dark navbar-custom fixed-top']
    ]);

    if (Yii::$app->user->isGuest) {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'],
            'items' => [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Location', 'url' => ['/site/location']],
                ['label' => 'Login', 'url' => ['/site/select-role']],
            ],
        ]);
    } else {
        echo Html::tag('div',
            '<i class="bi bi-bell me-3"></i>' . Html::encode(Yii::$app->user->identity->email),
            ['class' => 'ms-auto d-flex align-items-center text-white']
        );
    }

    NavBar::end();
    ?>
</header>

<!-- Breadcrumbs always just below navbar -->
<?php /* if (!Yii::$app->user->isGuest): ?>
    <?php
    $route = Yii::$app->controller->getRoute();
    $isDashboard = $route === 'site/dashboard';

    if (!$isDashboard) {
        $homeUrl = ['/site/dashboard'];

        if (!isset($this->params['breadcrumbs'])) {
            $this->params['breadcrumbs'] = [];
        }

        // Prevent double "Home"
        $first = $this->params['breadcrumbs'][0]['label'] ?? null;
        if ($first !== 'Home') {
            array_unshift($this->params['breadcrumbs'], ['label' => 'Home', 'url' => $homeUrl]);
        }
    ?>
        <div class="breadcrumbs-wrapper content-wrapper">
            <div class="container">
                <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'],
                    'options' => ['class' => 'mb-0']
                ]) ?>
            </div>
        </div>
    <?php } ?>
<?php endif; */ ?> 

<?php if (!Yii::$app->user->isGuest): ?>
    <?php
    $role = Yii::$app->user->identity->role;
    ?>

    <div class="sidebar bg-dark text-white">
        <ul class="nav flex-column">

            <!-- Dashboard (common) -->
            <li class="nav-item">
                <?= Html::a('Dashboard', ['/site/dashboard'], ['class' => 'nav-link text-white']) ?>
            </li>

            <?php if ($role === 'Admin'): ?>

                <!-- Admin: User Management -->
                <li class="nav-item">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#adminUser" role="button" aria-expanded="false" aria-controls="adminUser">
                        User Management
                    </a>
                    <div class="collapse ps-3" id="adminUser">
                        <?= Html::a('Users Account', ['/user/index'], ['class' => 'nav-link text-white']) ?>
                        <?= Html::a('Admin Profile', ['/user-details/view'], ['class' => 'nav-link text-white']) ?>
                    </div>
                </li>
                
                <!-- Admin: Student Application -->
                <li class="nav-item">
                    <?= Html::a('New Registration', ['/registration/index'], ['class' => 'nav-link text-white']) ?>
                    <?= Html::a('Registration History', ['/registration/index'], ['class' => 'nav-link text-white']) ?>
                </li>

                <!-- Admin: Class Management -->
                <li class="nav-item">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#adminClass" role="button" aria-expanded="false" aria-controls="adminClass">
                        Class Management
                    </a>
                    <div class="collapse ps-3" id="adminClass">
                        <?= Html::a('Manage Classes', ['/classroom/index'], ['class' => 'nav-link text-white']) ?>
                        <!-- You can add more class-related pages here later -->
                    </div>
                </li>

                <!-- Admin: Payment -->
                <li class="nav-item">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#adminPayment" role="button">
                        Bill & Payment
                    </a>
                    <div class="collapse ps-3" id="adminPayment">
                        <?= Html::a('Payment Requests', ['/payment/requests'], ['class' => 'nav-link text-white']) ?>
                        <?= Html::a('Receipts', ['/payment/receipts'], ['class' => 'nav-link text-white']) ?>
                    </div>
                </li>

            <?php elseif ($role === 'Teacher'): ?>

                <!-- Teacher: Profile -->
                <li class="nav-item">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#teacherProfile" role="button">
                        Profile
                    </a>
                    <div class="collapse ps-3" id="teacherProfile">
                        <?= Html::a('User Profile', ['/user-details/view'], ['class' => 'nav-link text-white']) ?>
                    </div>
                </li>

                <!-- Teacher: Learning -->
                <li class="nav-item">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#teacherLearning" role="button">
                        Learning Section
                    </a>
                    <div class="collapse ps-3" id="teacherLearning">
                        <?= Html::a('Materials', ['/learning/material'], ['class' => 'nav-link text-white']) ?>
                        <?= Html::a('Grades', ['/learning/grades'], ['class' => 'nav-link text-white']) ?>
                        <?= Html::a('Student Absent List', ['/attendance/absent'], ['class' => 'nav-link text-white']) ?>
                    </div>
                </li>

                <!-- Teacher: Payment -->
                <li class="nav-item">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#teacherPayment" role="button">
                        Bill & Payment
                    </a>
                    <div class="collapse ps-3" id="teacherPayment">
                        <?= Html::a('Payment Details', ['/payment/details'], ['class' => 'nav-link text-white']) ?>
                        <?= Html::a('Receipts', ['/payment/receipts'], ['class' => 'nav-link text-white']) ?>
                    </div>
                </li>

            <?php elseif ($role === 'Parent'): ?>

                <!-- Parent: Profile -->
                <li class="nav-item">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#parentProfile" role="button">
                        Profile
                    </a>
                    <div class="collapse ps-3" id="parentProfile">
                        <?= Html::a('User Profile', ['/user-details/view'], ['class' => 'nav-link text-white']) ?>
                        <?= Html::a('My Child Info', ['/child/index'], ['class' => 'nav-link text-white']) ?>
                    </div>
                </li>

                <!-- Parents: Student Registration -->
                <li class="nav-item">
                    <?= Html::a('Student Registration', ['/registration/index'], ['class' => 'nav-link text-white']) ?>
                </li>

                <!-- Parent: Learning -->
                <li class="nav-item">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#parentLearning" role="button">
                        Learning Section
                    </a>
                    <div class="collapse ps-3" id="parentLearning">
                        <?= Html::a('Materials', ['/learning/view'], ['class' => 'nav-link text-white']) ?>
                        <?= Html::a('Exam Results', ['/learning/results'], ['class' => 'nav-link text-white']) ?>
                    </div>
                </li>

                <!-- Parent: Payment -->
                <li class="nav-item">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#parentPayment" role="button">
                        Bill & Payment
                    </a>
                    <div class="collapse ps-3" id="parentPayment">
                        <?= Html::a('My Payments', ['/payment/parent'], ['class' => 'nav-link text-white']) ?>
                        <?= Html::a('Upload Receipt', ['/payment/upload'], ['class' => 'nav-link text-white']) ?>
                    </div>
                </li>

            <?php endif; ?>

            <!-- Common Logout -->
            <li class="nav-item mt-3">
                <?= Html::a('Log out', ['/site/logout'], [
                    'data-method' => 'post',
                    'class' => 'nav-link text-white'
                ]) ?>
            </li>
        </ul>
    </div>
<?php endif; ?>


<main id="main" class="<?= Yii::$app->user->isGuest ? 'guest-center' : 'content-wrapper' ?>" role="main">
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto <?= Yii::$app->user->isGuest ? '' : 'footer-logged-in' ?>">
    <div class="container">
        &copy; Pusat Islam Universiti Malaysia Sabah <?= date('Y') ?>
    </div>
</footer>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>