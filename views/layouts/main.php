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
           IMPROVED CONTRAST & READABILITY - Modern Layout Styles
           ============================================================================ */
        
        /* WCAG AA Compliant Color System */
        :root {
            /* Primary Colors - Enhanced Contrast */
            --ekafa-primary: #003829;
            --ekafa-primary-light: #00563d;
            --ekafa-primary-dark: #001f15;
            --ekafa-accent: #00b377;
            
            /* Text Colors - High Contrast */
            --text-primary: #111827;
            --text-secondary: #374151;
            --text-tertiary: #6b7280;
            --text-white: #ffffff;
            --text-white-soft: #f9fafb;
            
            /* Background Colors */
            --bg-primary: #ffffff;
            --bg-secondary: #f3f4f6;
            --bg-dark: #1f2937;
            
            /* Sidebar Colors - Darker for Better Contrast */
            --sidebar-bg: #001f15;
            --sidebar-text: #e5e7eb;
            --sidebar-text-hover: #ffffff;
            --sidebar-active: #00b377;
            --sidebar-hover-bg: rgba(0, 179, 119, 0.15);
            
            /* Navbar Colors - Darker */
            --navbar-bg: #003829;
            --navbar-text: #ffffff;
            
            /* Status Colors - High Contrast */
            --success: #059669;
            --warning: #d97706;
            --danger: #dc2626;
            --info: #0284c7;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.15);
        }
        
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            padding-top: 70px;
            font-family: 'Inter', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-image: url('<?= Yii::getAlias('@web/images/Masjid_UMS.jpg') ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            position: relative;
            min-height: 100vh;
            color: var(--text-primary);
            font-size: 16px;
            line-height: 1.6;
        }

        /* Darker overlay for better content visibility */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: linear-gradient(135deg, rgba(0, 56, 41, 0.85), rgba(0, 31, 21, 0.9));
            z-index: -1;
        }

        /* Enhanced Navbar - Better Contrast */
        .navbar-custom {
            background: var(--navbar-bg) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid rgba(0, 179, 119, 0.3);
            height: 70px;
        }

        .navbar-custom .navbar-brand {
            color: var(--navbar-text) !important;
            font-weight: 700;
            font-size: 1.35rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: transform 0.3s ease;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .navbar-custom .navbar-brand:hover {
            transform: translateY(-2px);
        }

        .navbar-custom .navbar-brand img {
            height: 38px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .navbar-custom .nav-link {
            color: var(--navbar-text) !important;
            font-weight: 600;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            font-size: 1rem;
        }

        .navbar-custom .nav-link:hover {
            color: var(--navbar-text) !important;
            background: rgba(0, 179, 119, 0.2);
            transform: translateY(-2px);
        }

        .navbar-custom .nav-link::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 50%;
            width: 0;
            height: 3px;
            background: var(--ekafa-accent);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar-custom .nav-link:hover::after {
            width: 70%;
        }

        .user-info-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1.25rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 2rem;
            color: var(--text-white);
            font-size: 0.95rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.25);
        }

        .user-info-badge i {
            font-size: 1.4rem;
        }

        /* Enhanced Sidebar - Much Better Contrast */
        .sidebar {
            width: 260px;
            height: calc(100vh - 70px);
            position: fixed;
            top: 70px;
            left: 0;
            background: var(--sidebar-bg);
            padding: 1.5rem 0;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            border-right: 2px solid rgba(0, 179, 119, 0.2);
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 179, 119, 0.4);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 179, 119, 0.6);
        }

        .sidebar .nav-item {
            margin-bottom: 0.25rem;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.95rem 1.5rem;
            border-radius: 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            font-size: 1rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            position: relative;
        }

        .sidebar .nav-link i {
            font-size: 1.25rem;
            min-width: 24px;
            text-align: center;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: var(--sidebar-hover-bg);
            transition: width 0.3s ease;
        }

        .sidebar .nav-link:hover {
            color: var(--sidebar-text-hover);
            background: var(--sidebar-hover-bg);
            border-left-color: var(--sidebar-active);
        }

        .sidebar .nav-link:hover::before {
            width: 100%;
        }

        .sidebar .nav-link.active {
            color: var(--sidebar-text-hover);
            background: var(--sidebar-hover-bg);
            border-left-color: var(--sidebar-active);
            font-weight: 600;
            box-shadow: inset 0 0 0 1px rgba(0, 179, 119, 0.2);
        }

        /* Collapsible Menu - Improved Visibility */
        .sidebar .collapse .nav-link {
            padding-left: 3.5rem;
            font-size: 0.95rem;
            color: rgba(229, 231, 235, 0.85);
        }

        .sidebar .collapse .nav-link::before {
            content: '→';
            position: absolute;
            left: 2.5rem;
            opacity: 0;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .sidebar .collapse .nav-link:hover::before {
            opacity: 1;
            left: 2.75rem;
        }

        .sidebar .collapse .nav-link:hover {
            color: var(--sidebar-text-hover);
        }

        /* Section Divider */
        .sidebar-divider {
            height: 1px;
            background: rgba(0, 179, 119, 0.25);
            margin: 1rem 1.5rem;
        }

        /* Content Wrapper - Better Background */
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

        /* Enhanced Footer */
        footer#footer {
            background: var(--sidebar-bg);
            color: var(--text-white);
            padding: 1.75rem 0;
            margin-top: auto;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.2);
            border-top: 2px solid rgba(0, 179, 119, 0.2);
        }

        footer .container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            font-size: 0.95rem;
        }

        footer .container p {
            margin: 0;
            color: var(--text-white);
            font-weight: 500;
        }

        .footer-logged-in {
            margin-left: 260px;
        }

        /* Breadcrumbs - Higher Contrast */
        .breadcrumbs-wrapper {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            margin-bottom: 1.5rem;
            border-radius: 1rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .breadcrumb {
            background: transparent;
            margin-bottom: 0;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .breadcrumb-item a {
            color: var(--ekafa-primary);
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 600;
        }

        .breadcrumb-item a:hover {
            color: var(--ekafa-accent);
        }

        .breadcrumb-item.active {
            color: var(--text-secondary);
            font-weight: 600;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            body {
                background-attachment: scroll;
                font-size: 15px;
            }
            
            body::before {
                background: linear-gradient(135deg, rgba(0, 31, 21, 0.95), rgba(0, 15, 10, 0.95));
            }

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
                font-size: 0.85rem;
                padding: 0.5rem 0.85rem;
            }

            .navbar-custom .navbar-brand {
                font-size: 1.15rem;
            }
        }

        /* Page Wrapper */
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Enhanced Alert Animations */
        .alert {
            animation: slideInDown 0.3s ease-out;
            border-radius: 0.75rem;
            border: none;
            box-shadow: var(--shadow-lg);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border-left: 4px solid var(--warning);
        }

        .alert-info {
            background: #eff6ff;
            color: #1e40af;
            border-left: 4px solid var(--info);
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
            background: linear-gradient(90deg, var(--ekafa-accent), var(--ekafa-primary));
            z-index: 9999;
            animation: loading 1.5s ease-in-out infinite;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Improved Link Styles */
        a {
            color: var(--ekafa-primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: var(--ekafa-accent);
        }

        /* Better Button Contrast */
        .btn-primary {
            background: var(--ekafa-primary);
            border-color: var(--ekafa-primary);
            color: var(--text-white);
            font-weight: 600;
        }

        .btn-primary:hover {
            background: var(--ekafa-primary-dark);
            border-color: var(--ekafa-primary-dark);
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
                ['label' => 'Contact', 'url' => ['/site/contact']],
                ['label' => 'Login', 'url' => ['/site/select-role']],
            ],
        ]);
    } else {
        $user = Yii::$app->user->identity;
        $userDetails = $user->userDetails ?? null;
        $profilePic = ($userDetails && $userDetails->profile_picture_url) 
            ? Html::img('@web/' . $userDetails->profile_picture_url, [
                'style' => 'width: 34px; height: 34px; border-radius: 50%; margin-right: 8px; object-fit: cover; border: 2px solid rgba(255, 255, 255, 0.3);'
            ])
            : '<i class="bi bi-person-circle" style="font-size: 1.75rem; margin-right: 8px;"></i>';

        echo Html::tag('div',
            $profilePic . Html::encode($user->username ?? $user->email),
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
                        <?= Html::a('Document Categories', ['/document-category/index'], ['class' => 'nav-link']) ?>
                        <?= Html::a('Review Documents', ['/user-documents/index'], ['class' => 'nav-link']) ?>
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

                <!-- Teacher: User Profile -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#teacherProfile" role="button" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> User Profile
                    </a>
                    <div class="collapse" id="teacherProfile">
                        <?= Html::a('My Profile', ['/user-details/view'], ['class' => 'nav-link']) ?>
                        <?= Html::a('<i class="bi bi-files"></i> My Documents', ['/user-documents/my-documents'], ['class' => 'nav-link']) ?>
                    </div>
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

                <!-- Parent: User Profile -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#parentProfile" role="button" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> User Profile
                    </a>
                    <div class="collapse" id="parentProfile">
                        <?= Html::a('My Profile', ['/user-details/view'], ['class' => 'nav-link']) ?>
                        <?= Html::a('<i class="bi bi-files"></i> My Documents', ['/user-documents/my-documents'], ['class' => 'nav-link']) ?>
                    </div>
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
                        'style' => 'color: #fca5a5;'
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