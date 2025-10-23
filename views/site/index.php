<?php
/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'E-KAFA PIUMS - Islamic Education Management System';
?>

<style>
    /* ==========================
       IMPROVED READABILITY - Modern Landing Page Styles
       ========================== */
    
    :root {
        /* High Contrast Color System */
        --ekafa-primary: #003829;
        --ekafa-primary-light: #00563d;
        --ekafa-primary-dark: #001f15;
        --ekafa-secondary: #00b377;
        --ekafa-accent: #10d890;
        --ekafa-gradient: linear-gradient(135deg, #003829 0%, #00563d 100%);
        
        /* Text Colors - High Contrast */
        --text-primary: #111827;
        --text-secondary: #374151;
        --text-tertiary: #4b5563;
        --text-white: #ffffff;
        --text-white-soft: #f9fafb;
        
        /* Background Colors */
        --bg-white: #ffffff;
        --bg-light: #f3f4f6;
        --bg-lighter: #f9fafb;
        
        /* Shadows */
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.15);
        --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
    }

    /* Reset & Base */
    .landing-page {
        background: transparent;
        padding: 0;
        margin: 0;
        overflow-x: hidden;
        font-size: 16px;
    }

    /* Hero Section - Improved Contrast */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 100px 20px 80px;
        background: linear-gradient(135deg, rgba(0, 56, 41, 0.95), rgba(0, 31, 21, 0.97));
    }

    .hero-content {
        max-width: 1200px;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
        animation: fadeInUp 1s ease-out;
    }

    .hero-text {
        color: var(--text-white);
    }

    .hero-title {
        font-size: 3.75rem;
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 1.5rem;
        color: var(--text-white);
        text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 1s ease-out 0.2s both;
    }

    .hero-subtitle {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        color: var(--text-white);
        text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.2);
        animation: fadeInUp 1s ease-out 0.4s both;
    }

    .hero-description {
        font-size: 1.25rem;
        line-height: 1.8;
        color: var(--text-white-soft);
        margin-bottom: 2.5rem;
        font-weight: 400;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        animation: fadeInUp 1s ease-out 0.6s both;
    }

    .hero-buttons {
        display: flex;
        gap: 1.25rem;
        flex-wrap: wrap;
        animation: fadeInUp 1s ease-out 0.8s both;
    }

    .btn-hero {
        padding: 1.125rem 2.25rem;
        font-size: 1.125rem;
        font-weight: 700;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-lg);
        border: 2px solid transparent;
    }

    .btn-hero-primary {
        background: var(--text-white);
        color: var(--ekafa-primary);
        border-color: var(--text-white);
    }

    .btn-hero-primary:hover {
        background: var(--text-white-soft);
        transform: translateY(-3px);
        box-shadow: var(--shadow-2xl);
        color: var(--ekafa-primary);
    }

    .btn-hero-secondary {
        background: transparent;
        color: var(--text-white);
        border: 2px solid var(--text-white);
        backdrop-filter: blur(10px);
    }

    .btn-hero-secondary:hover {
        background: var(--text-white);
        color: var(--ekafa-primary);
        transform: translateY(-3px);
        box-shadow: var(--shadow-2xl);
    }

    /* Hero Image/Illustration */
    .hero-visual {
        position: relative;
        animation: fadeInUp 1s ease-out 0.4s both;
    }

    .hero-image-card {
        background: var(--bg-white);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: var(--shadow-2xl);
        position: relative;
        overflow: hidden;
        border: 2px solid rgba(0, 179, 119, 0.2);
    }

    .hero-image-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(0, 179, 119, 0.1), transparent);
        animation: shimmer 3s infinite;
    }

    .hero-image-card img {
        width: 100%;
        height: auto;
        border-radius: 16px;
        display: block;
    }

    /* Stats Section - Better Contrast */
    .stats-section {
        background: var(--bg-white);
        padding: 70px 20px;
        margin-top: -50px;
        position: relative;
        z-index: 10;
        box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.05);
    }

    .stats-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 2rem;
    }

    .stat-card {
        text-align: center;
        padding: 2.5rem 1.5rem;
        border-radius: 16px;
        background: var(--bg-white);
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-md);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--ekafa-secondary);
    }

    .stat-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        background: var(--ekafa-gradient);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-white);
        font-size: 2.25rem;
        box-shadow: var(--shadow-md);
    }

    .stat-number {
        font-size: 2.75rem;
        font-weight: 900;
        color: var(--ekafa-primary);
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .stat-label {
        font-size: 1.125rem;
        color: var(--text-secondary);
        font-weight: 600;
    }

    /* Features Section - Enhanced Readability */
    .features-section {
        padding: 100px 20px;
        background: var(--bg-lighter);
    }

    .section-header {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 70px;
    }

    .section-badge {
        display: inline-block;
        padding: 0.625rem 1.25rem;
        background: var(--ekafa-primary);
        color: var(--text-white);
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.9375rem;
        margin-bottom: 1.25rem;
        box-shadow: var(--shadow-md);
        border: 2px solid var(--ekafa-primary-light);
    }

    .section-title {
        font-size: 2.75rem;
        font-weight: 900;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
        line-height: 1.2;
    }

    .section-description {
        font-size: 1.25rem;
        color: var(--text-secondary);
        line-height: 1.8;
        font-weight: 500;
    }

    .features-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2.5rem;
    }

    .feature-card {
        background: var(--bg-white);
        padding: 2.75rem;
        border-radius: 20px;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 2px solid #e5e7eb;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: var(--ekafa-gradient);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-2xl);
        border-color: var(--ekafa-secondary);
    }

    .feature-card:hover::before {
        transform: scaleX(1);
    }

    .feature-icon {
        width: 72px;
        height: 72px;
        background: var(--ekafa-gradient);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.75rem;
        color: var(--text-white);
        font-size: 2.25rem;
        box-shadow: var(--shadow-md);
    }

    .feature-title {
        font-size: 1.625rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .feature-description {
        font-size: 1.0625rem;
        color: var(--text-secondary);
        line-height: 1.75;
        font-weight: 400;
    }

    /* CTA Section - Maximum Contrast */
    .cta-section {
        padding: 100px 20px;
        background: var(--ekafa-gradient);
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -25%;
        width: 50%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    .cta-content {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
        color: var(--text-white);
        position: relative;
        z-index: 2;
    }

    .cta-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 1.75rem;
        line-height: 1.2;
        color: var(--text-white);
        text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .cta-description {
        font-size: 1.375rem;
        margin-bottom: 3rem;
        font-weight: 500;
        line-height: 1.6;
        color: var(--text-white);
        text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.15);
    }

    .cta-button {
        background: var(--text-white);
        color: var(--ekafa-primary);
        padding: 1.375rem 3rem;
        font-size: 1.375rem;
        font-weight: 800;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.875rem;
        box-shadow: var(--shadow-2xl);
        transition: all 0.3s ease;
        border: 2px solid var(--text-white);
    }

    .cta-button:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px -15px rgb(0 0 0 / 0.3);
        background: var(--text-white-soft);
        color: var(--ekafa-primary);
    }

    /* Animations */
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

    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-content {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.375rem;
        }

        .hero-description {
            font-size: 1.125rem;
        }

        .section-title {
            font-size: 2.25rem;
        }

        .cta-title {
            font-size: 2.25rem;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .hero-visual {
            order: -1;
        }

        .stat-card {
            padding: 2rem 1.25rem;
        }

        .feature-card {
            padding: 2.25rem;
        }
    }
</style>

<div class="landing-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">E-KAFA PIUMS</h1>
                <p class="hero-subtitle">Pusat Islam Universiti Malaysia Sabah</p>
                <p class="hero-description">
                    A comprehensive digital platform revolutionizing KAFA education management. 
                    Streamline registrations, track attendance, manage payments, and enhance 
                    communication between administrators, teachers, and parents.
                </p>
                <div class="hero-buttons">
                    <?= Html::a(
                        '<i class="bi bi-box-arrow-in-right"></i> Get Started',
                        ['site/select-role'],
                        ['class' => 'btn-hero btn-hero-primary']
                    ) ?>
                    <?= Html::a(
                        '<i class="bi bi-info-circle"></i> Learn More',
                        ['site/about'],
                        ['class' => 'btn-hero btn-hero-secondary']
                    ) ?>
                </div>
            </div>

            <div class="hero-visual">
                <div class="hero-image-card">
                    <img src="<?= Url::to('@web/images/ekafa_picture.jpg') ?>" alt="E-KAFA PIUMS">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-number">3+</div>
                <div class="stat-label">User Roles</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-number">100%</div>
                <div class="stat-label">Digital Tracking</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-lightning-charge"></i>
                </div>
                <div class="stat-number">24/7</div>
                <div class="stat-label">Access</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="stat-number">Secure</div>
                <div class="stat-label">Platform</div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="section-header">
            <span class="section-badge">✨ Key Features</span>
            <h2 class="section-title">Everything You Need in One Place</h2>
            <p class="section-description">
                Powerful features designed to make KAFA management effortless and efficient
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <h3 class="feature-title">Smart Registration</h3>
                <p class="feature-description">
                    Online registration system with document uploads, automatic approval 
                    workflows, and real-time status tracking for seamless onboarding.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h3 class="feature-title">Attendance Management</h3>
                <p class="feature-description">
                    Digital attendance tracking with absence forms, automatic notifications, 
                    and detailed reporting for teachers and parents.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-book"></i>
                </div>
                <h3 class="feature-title">Learning Portal</h3>
                <p class="feature-description">
                    Comprehensive learning management with materials sharing, grade tracking, 
                    and exam result publication for enhanced education.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <h3 class="feature-title">Payment System</h3>
                <p class="feature-description">
                    Integrated payment ledger with receipt uploads, automatic verification, 
                    and detailed billing history for transparent financial management.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-door-open"></i>
                </div>
                <h3 class="feature-title">Class Management</h3>
                <p class="feature-description">
                    Organize classes efficiently with teacher assignments, student rosters, 
                    and classroom scheduling tools.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h3 class="feature-title">Inventory Control</h3>
                <p class="feature-description">
                    Track educational materials, supplies, and resources with real-time 
                    stock monitoring and automated alerts.
                </p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2 class="cta-title">Ready to Transform Your KAFA Management?</h2>
            <p class="cta-description">
                Join us in modernizing Islamic education with digital efficiency and transparency
            </p>
            <?= Html::a(
                'Start Your Journey <i class="bi bi-arrow-right"></i>',
                ['site/select-role'],
                ['class' => 'cta-button']
            ) ?>
        </div>
    </section>
</div>

<script>
// Scroll animations
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe feature cards
    document.querySelectorAll('.feature-card, .stat-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>