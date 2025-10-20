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
        /* ============================================================================
           Modern Layout Styles - Matching ekafa-custom.css Design System
           ============================================================================ */
        
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            padding-top: 70px;
            font-family: 'Inter', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
        }

        /* Modern Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, #004135, #11684d) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            height: 70px;
        }

        .navbar-custom .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.3s ease;
        }

        .navbar-custom .navbar-brand:hover {
            transform: translateY(-2px);
        }

        .navbar-custom .navbar-brand img {
            height: 35px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-custom .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .navbar-custom .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: white;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar-custom .nav-link:hover::after {
            width: 80%;
        }

        .user-info-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2rem;
            color: white;
            font-size: 0.875rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-info-badge i {
            font-size: 1.25rem;
        }

        /* Modern Sidebar */
        .sidebar {
            width: 260px;
            height: calc(100vh - 70px);
            position: fixed;
            top: 70px;
            left: 0;
            background: linear-gradient(180deg, #004135 0%, #002820 100%);
            padding: 1.5rem 0;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar .nav-item {
            margin-bottom: 0.25rem;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.875rem 1.5rem;
            border-radius: 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9375rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            position: relative;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: rgba(255, 255, 255, 0.1);
            transition: width 0.3s ease;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
            border-left-color: #00a86b;
        }

        .sidebar .nav-link:hover::before {
            width: 100%;
        }

        .sidebar .nav-link.active {
            color: white;
            background: rgba(0, 168, 107, 0.2);
            border-left-color: #00a86b;
            font-weight: 600;
        }

        /* Collapsible Menu */
        .sidebar .collapse .nav-link {
            padding-left: 3rem;
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .sidebar .collapse .nav-link::before {
            content: '→';
            position: absolute;
            left: 2rem;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .sidebar .collapse .nav-link:hover::before {
            opacity: 1;
            left: 2.25rem;
        }

        /* Section Divider */
        .sidebar-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 1rem 1.5rem;
        }

        /* Content Wrapper */
        .content-wrapper {
            margin-left: 260px;
            padding: 2rem;
            min-height: calc(100vh - 70px);
            box-sizing: border-box;
        }

        /* Guest Center Layout */
        .guest-center {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 70px);
            padding: 2rem;
        }

        /* Modern Footer */
        footer#footer {
            background: linear-gradient(135deg, #004135, #002820);
            color: white;
            padding: 1.5rem 0;
            margin-top: auto;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        footer .container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            font-size: 0.875rem;
            opacity: 0.9;
        }

        footer .container p {
            margin: 0;
            color: white;
        }

        .footer-logged-in {
            margin-left: 260px;
        }

        /* Breadcrumbs */
        .breadcrumbs-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            margin-bottom: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .breadcrumb {
            background: transparent;
            margin-bottom: 0;
            font-size: 0.875rem;
        }

        .breadcrumb-item a {
            color: #004135;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #00a86b;
        }

        .breadcrumb-item.active {
            color: #6b7280;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
            }

            .content-wrapper {
                margin-left: 0;
                padding: 1rem;
            }

            .footer-logged-in {
                margin-left: 0;
            }

            .user-info-badge {
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
            }
        }

        /* Page Wrapper */
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Alert Animations */
        .alert {
            animation: slideInDown 0.3s ease-out;
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading State */
        .page-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #00a86b, #004135);
            z-index: 9999;
            animation: loading 1.5s ease-in-out infinite;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>
</head>
<body>
<div class="page-wrapper">
<?php $this->beginBody(); ?>

<!-- Modern Navbar -->
<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/logo_putih.png', ['alt' => 'E-KAFA Logo']) . ' E-KAFA PIUMS',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar navbar-expand-md navbar-dark navbar-custom fixed-top']
    ]);

    if (Yii::$app->user->isGuest) {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'],
            'items' => [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Login', 'url' => ['/site/select-role']],
            ],
        ]);
    } else {
        echo Html::tag('div',
            '<i class="bi bi-bell-fill"></i>' . Html::encode(Yii::$app->user->identity->username ?? Yii::$app->user->identity->email),
            ['class' => 'ms-auto user-info-badge']
        );
    }

    NavBar::end();
    ?>
</header>

<?php if (!Yii::$app->user->isGuest): ?>
    <?php
    $role = Yii::$app->user->identity->role;
    $currentRoute = Yii::$app->controller->route;
    ?>

    <!-- Modern Sidebar -->
    <div class="sidebar">
        <ul class="nav flex-column">

            <!-- Dashboard -->
            <li class="nav-item">
                <?= Html::a(
                    '<i class="bi bi-speedometer2"></i> Dashboard',
                    ['/site/dashboard'],
                    ['class' => 'nav-link' . ($currentRoute === 'site/dashboard' ? ' active' : '')]
                ) ?>
            </li>

            <div class="sidebar-divider"></div>

            <?php if ($role === 'Admin'): ?>

                <!-- Admin: User Management -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#adminUser" role="button" aria-expanded="false">
                        <i class="bi bi-people-fill"></i> User Management
                    </a>
                    <div class="collapse" id="adminUser">
                        <?= Html::a('Users Account', ['/user/index'], ['class' => 'nav-link']) ?>
                        <?= Html::a('Admin Profile', ['/user-details/view'], ['class' => 'nav-link']) ?>
                    </div>
                </li>
                
                <!-- Admin: Student Registration -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#adminRegistration" role="button">
                        <i class="bi bi-clipboard-check"></i> Registration
                    </a>
                    <div class="collapse" id="adminRegistration">
                        <?= Html::a('New Applications', ['/registration/new'], ['class' => 'nav-link']) ?>
                        <?= Html::a('Registration History', ['/registration/history'], ['class' => 'nav-link']) ?>
                    </div>
                </li>

                <!-- Admin: Class Management -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#adminClass" role="button">
                        <i class="bi bi-door-open"></i> Class Management
                    </a>
                    <div class="collapse" id="adminClass">
                        <?= Html::a('Manage Classes', ['/classroom/index'], ['class' => 'nav-link']) ?>
                        <?= Html::a('Assign Teachers', ['/classroom/assign'], ['class' => 'nav-link']) ?>
                    </div>
                </li>

                <!-- Admin: Payment -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#adminPayment" role="button">
                        <i class="bi bi-wallet2"></i> Bill & Payment
                    </a>
                    <div class="collapse" id="adminPayment">
                        <?= Html::a('Payment Approval', ['/payment/approval'], ['class' => 'nav-link']) ?>
                        <?= Html::a('Payment Categories', ['/payment/categories'], ['class' => 'nav-link']) ?>
                    </div>
                </li>

                <!-- Admin: Inventory -->
                <li class="nav-item">
                    <?= Html::a(
                        '<i class="bi bi-box-seam"></i> Stock Management',
                        ['/inventory/index'],
                        ['class' => 'nav-link']
                    ) ?>
                </li>

            <?php elseif ($role === 'Teacher'): ?>

                <!-- Teacher: Profile -->
                <li class="nav-item">
                    <?= Html::a(
                        '<i class="bi bi-person-circle"></i> My Profile',
                        ['/user-details/view'],
                        ['class' => 'nav-link']
                    ) ?>
                </li>

                <div class="sidebar-divider"></div>

                <!-- Teacher: Learning -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#teacherLearning" role="button">
                        <i class="bi bi-book"></i> Learning Section
                    </a>
                    <div class="collapse" id="teacherLearning">
                        <?= Html::a('Learning Materials', ['/learning/materials'], ['class' => 'nav-link']) ?>
                        <?= Html::a('Student Grades', ['/learning/grades'], ['class' => 'nav-link']) ?>
                    </div>
                </li>

                <!-- Teacher: Attendance -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#teacherAttendance" role="button">
                        <i class="bi bi-calendar-check"></i> Attendance
                    </a>
                    <div class="collapse" id="teacherAttendance">
                        <?= Html::a('Mark Attendance', ['/attendance/mark'], ['class' => 'nav-link']) ?>
                        <?= Html::a('Absence Forms', ['/attendance/absent'], ['class' => 'nav-link']) ?>
                    </div>
                </li>

                <!-- Teacher: Payment -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#teacherPayment" role="button">
                        <i class="bi bi-cash-coin"></i> Payment
                    </a>
                    <div class="collapse" id="teacherPayment">
                        <?= Html::a('Payment Details', ['/payment/details'], ['class' => 'nav-link']) ?>
                        <?= Html::a('Verify Receipts', ['/payment/verify'], ['class' => 'nav-link']) ?>
                    </div>
                </li>

                <!-- Teacher: Activities -->
                <li class="nav-item">
                    <?= Html::a(
                        '<i class="bi bi-calendar-event"></i> Activities',
                        ['/activity/index'],
                        ['class' => 'nav-link']
                    ) ?>
                </li>

            <?php elseif ($role === 'Parent'): ?>

                <!-- Parent: Profile -->
                <li class="nav-item">
                    <?= Html::a(
                        '<i class="bi bi-person-circle"></i> My Profile',
                        ['/user-details/view'],
                        ['class' => 'nav-link']
                    ) ?>
                </li>

                <div class="sidebar-divider"></div>

                <!-- Parent: My Children -->
                <li class="nav-item">
                    <?= Html::a(
                        '<i class="bi bi-people"></i> My Children',
                        ['/child/index'],
                        ['class' => 'nav-link']
                    ) ?>
                </li>

                <!-- Parent: Registration -->
                <li class="nav-item">
                    <?= Html::a(
                        '<i class="bi bi-clipboard-plus"></i> Student Registration',
                        ['/registration/create'],
                        ['class' => 'nav-link']
                    ) ?>
                </li>

                <div class="sidebar-divider"></div>

                <!-- Parent: Learning -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#parentLearning" role="button">
                        <i class="bi bi-book"></i> Learning
                    </a>
                    <div class="collapse" id="parentLearning">
                        <?= Html::a('Learning Materials', ['/learning/view'], ['class' => 'nav-link']) ?>
                        <?= Html::a('Exam Results', ['/learning/results'], ['class' => 'nav-link']) ?>
                    </div>
                </li>

                <!-- Parent: Attendance -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#parentAttendance" role="button">
                        <i class="bi bi-calendar-check"></i> Attendance
                    </a>
                    <div class="collapse" id="parentAttendance">
                        <?= Html::a('View Attendance', ['/attendance/view'], ['class' => 'nav-link']) ?>
                        <?= Html::a('Submit Absence Form', ['/attendance/absence-form'], ['class' => 'nav-link']) ?>
                    </div>
                </li>

                <!-- Parent: Payment -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#parentPayment" role="button">
                        <i class="bi bi-wallet2"></i> Payment
                    </a>
                    <div class="collapse" id="parentPayment">
                        <?= Html::a('Payment Ledger', ['/payment/ledger'], ['class' => 'nav-link']) ?>
                        <?= Html::a('Upload Receipt', ['/payment/upload'], ['class' => 'nav-link']) ?>
                    </div>
                </li>

                <!-- Parent: Activities -->
                <li class="nav-item">
                    <?= Html::a(
                        '<i class="bi bi-calendar-event"></i> Activities',
                        ['/activity/view'],
                        ['class' => 'nav-link']
                    ) ?>
                </li>

            <?php endif; ?>

            <div class="sidebar-divider"></div>

            <!-- Common: Logout -->
            <li class="nav-item">
                <?= Html::a(
                    '<i class="bi bi-box-arrow-right"></i> Log out',
                    ['/site/logout'],
                    [
                        'data-method' => 'post',
                        'class' => 'nav-link',
                        'style' => 'color: rgba(255, 107, 107, 0.9);'
                    ]
                ) ?>
            </li>
        </ul>
    </div>
<?php endif; ?>

<!-- Main Content -->
<main id="main" class="<?= Yii::$app->user->isGuest ? 'guest-center' : 'content-wrapper' ?>" role="main">
    <?php if (!Yii::$app->user->isGuest && !empty($this->params['breadcrumbs'])): ?>
        <div class="breadcrumbs-wrapper">
            <div class="container-fluid">
                <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'],
                    'homeLink' => ['label' => 'Home', 'url' => ['/site/dashboard']],
                ]) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="container-fluid">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<!-- Modern Footer -->
<footer id="footer" class="<?= Yii::$app->user->isGuest ? '' : 'footer-logged-in' ?>">
    <div class="container">
        <p class="mb-0">
            &copy; <?= date('Y') ?> Pusat Islam Universiti Malaysia Sabah. All rights reserved.
        </p>
    </div>
</footer>

<?php $this->endBody(); ?>

<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Active menu highlighting
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            // Open parent collapse if exists
            const collapse = link.closest('.collapse');
            if (collapse) {
                collapse.classList.add('show');
            }
        }
    });
});
</script>

</div>
</body>
</html>
<?php $this->endPage(); ?>