<?php
/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'E-KAFA PIUMS - Islamic Education Management System';
?>

<style>
    /* ==========================
       Modern Landing Page Styles
       ========================== */
    
    :root {
        --ekafa-primary: #004135;
        --ekafa-primary-light: #11684d;
        --ekafa-secondary: #00a86b;
        --ekafa-gradient: linear-gradient(135deg, #004135 0%, #11684d 100%);
        --text-dark: #1f2937;
        --text-light: #6b7280;
        --white: #ffffff;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }

    /* Reset & Base */
    .landing-page {
        background: transparent;
        padding: 0;
        margin: 0;
        overflow-x: hidden;
    }

    /* Hero Section */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 80px 20px 60px;
        background: linear-gradient(135deg, rgba(0, 65, 53, 0.95), rgba(17, 104, 77, 0.9));
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
        color: white;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #fff 0%, #a7f3d0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: fadeInUp 1s ease-out 0.2s both;
    }

    .hero-subtitle {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        opacity: 0.95;
        animation: fadeInUp 1s ease-out 0.4s both;
    }

    .hero-description {
        font-size: 1.125rem;
        line-height: 1.8;
        opacity: 0.9;
        margin-bottom: 2rem;
        animation: fadeInUp 1s ease-out 0.6s both;
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        animation: fadeInUp 1s ease-out 0.8s both;
    }

    .btn-hero {
        padding: 1rem 2rem;
        font-size: 1.125rem;
        font-weight: 600;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-lg);
    }

    .btn-hero-primary {
        background: white;
        color: var(--ekafa-primary);
    }

    .btn-hero-primary:hover {
        background: #f0fdf4;
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
    }

    .btn-hero-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }

    .btn-hero-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
    }

    /* Hero Image/Illustration */
    .hero-visual {
        position: relative;
        animation: fadeInUp 1s ease-out 0.4s both;
    }

    .hero-image-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: var(--shadow-xl);
        position: relative;
        overflow: hidden;
    }

    .hero-image-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(0, 168, 107, 0.1), transparent);
        animation: shimmer 3s infinite;
    }

    .hero-image-card img {
        width: 100%;
        height: auto;
        border-radius: 16px;
        display: block;
    }

    /* Stats Section */
    .stats-section {
        background: white;
        padding: 60px 20px;
        margin-top: -40px;
        position: relative;
        z-index: 10;
    }

    .stats-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
    }

    .stat-card {
        text-align: center;
        padding: 2rem 1rem;
        border-radius: 16px;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        background: var(--ekafa-gradient);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--ekafa-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 1rem;
        color: var(--text-light);
        font-weight: 500;
    }

    /* Features Section */
    .features-section {
        padding: 100px 20px;
        background: linear-gradient(180deg, white 0%, #f9fafb 100%);
    }

    .section-header {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 60px;
    }

    .section-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #dcfce7 0%, #a7f3d0 100%);
        color: var(--ekafa-primary);
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .section-description {
        font-size: 1.125rem;
        color: var(--text-light);
        line-height: 1.8;
    }

    .features-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--ekafa-gradient);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-xl);
    }

    .feature-card:hover::before {
        transform: scaleX(1);
    }

    .feature-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #dcfce7 0%, #a7f3d0 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: var(--ekafa-primary);
        font-size: 2rem;
    }

    .feature-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .feature-description {
        font-size: 1rem;
        color: var(--text-light);
        line-height: 1.7;
    }

    /* CTA Section */
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
        color: white;
        position: relative;
        z-index: 2;
    }

    .cta-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
    }

    .cta-description {
        font-size: 1.25rem;
        margin-bottom: 2.5rem;
        opacity: 0.95;
    }

    .cta-button {
        background: white;
        color: var(--ekafa-primary);
        padding: 1.25rem 2.5rem;
        font-size: 1.25rem;
        font-weight: 700;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: var(--shadow-xl);
        transition: all 0.3s ease;
    }

    .cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
        background: #f0fdf4;
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
            font-size: 1.25rem;
        }

        .hero-description {
            font-size: 1rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .cta-title {
            font-size: 2rem;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .hero-visual {
            order: -1;
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