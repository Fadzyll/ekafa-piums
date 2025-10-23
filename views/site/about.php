<?php
/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'About E-KAFA PIUMS';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    /* ==========================
       IMPROVED READABILITY - Modern About Page Styles
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
    }

    .about-page {
        background: var(--bg-white);
        padding-bottom: 60px;
        font-size: 16px;
    }

    /* Hero Section - Better Contrast */
    .about-hero {
        background: var(--ekafa-gradient);
        padding: 100px 20px 80px;
        text-align: center;
        color: var(--text-white);
        position: relative;
        overflow: hidden;
    }

    .about-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    .about-hero-content {
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .about-badge {
        display: inline-block;
        padding: 0.625rem 1.5rem;
        background: var(--text-white);
        color: var(--ekafa-primary);
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.9375rem;
        margin-bottom: 1.5rem;
        border: 2px solid var(--text-white);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .about-hero h1 {
        font-size: 3.25rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        color: var(--text-white);
        text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .about-hero p {
        font-size: 1.375rem;
        font-weight: 500;
        line-height: 1.8;
        color: var(--text-white);
        text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.15);
    }

    /* Image Section */
    .about-image-section {
        max-width: 1200px;
        margin: -60px auto 80px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }

    .about-image-card {
        background: var(--bg-white);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.15);
        overflow: hidden;
        border: 2px solid rgba(0, 179, 119, 0.2);
    }

    .about-image-card img {
        width: 100%;
        height: auto;
        border-radius: 16px;
        display: block;
    }

    /* Content Section - Enhanced Readability */
    .about-content {
        max-width: 1200px;
        margin: 0 auto 80px;
        padding: 0 20px;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: start;
    }

    .content-text h2 {
        font-size: 2.75rem;
        font-weight: 900;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .content-text p {
        font-size: 1.125rem;
        color: var(--text-secondary);
        line-height: 1.8;
        margin-bottom: 1.5rem;
        text-align: justify;
        font-weight: 400;
    }

    .highlight-box {
        background: var(--bg-white);
        padding: 2.5rem;
        border-radius: 16px;
        border: 2px solid var(--ekafa-secondary);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .highlight-box h3 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--ekafa-primary);
        margin-bottom: 1.5rem;
    }

    .highlight-box ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .highlight-box li {
        padding: 0.875rem 0;
        color: var(--text-primary);
        font-size: 1.0625rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-weight: 500;
        border-bottom: 1px solid #e5e7eb;
    }

    .highlight-box li:last-child {
        border-bottom: none;
    }

    .highlight-box li i {
        color: var(--ekafa-secondary);
        font-size: 1.5rem;
        font-weight: bold;
        flex-shrink: 0;
    }

    /* Mission & Vision Section - Better Contrast */
    .mission-vision-section {
        background: var(--bg-lighter);
        padding: 80px 20px;
    }

    .mission-vision-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
    }

    .mv-card {
        background: var(--bg-white);
        padding: 3rem;
        border-radius: 24px;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
        border: 2px solid #e5e7eb;
    }

    .mv-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: var(--ekafa-gradient);
    }

    .mv-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.15);
        border-color: var(--ekafa-secondary);
    }

    .mv-icon {
        width: 80px;
        height: 80px;
        background: var(--ekafa-gradient);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
        color: var(--text-white);
        font-size: 2.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .mv-card h3 {
        font-size: 2.25rem;
        font-weight: 900;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
    }

    .mv-card p {
        font-size: 1.125rem;
        color: var(--text-secondary);
        line-height: 1.8;
        text-align: justify;
        font-weight: 400;
    }

    /* Timeline Section - Enhanced Visibility */
    .timeline-section {
        padding: 80px 20px;
        background: var(--bg-white);
    }

    .timeline-header {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 60px;
    }

    .timeline-header h2 {
        font-size: 2.75rem;
        font-weight: 900;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .timeline-header p {
        font-size: 1.25rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .timeline {
        max-width: 900px;
        margin: 0 auto;
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--ekafa-gradient);
        transform: translateX(-50%);
    }

    .timeline-item {
        display: flex;
        margin-bottom: 3rem;
        position: relative;
    }

    .timeline-item:nth-child(odd) {
        flex-direction: row;
    }

    .timeline-item:nth-child(even) {
        flex-direction: row-reverse;
    }

    .timeline-content {
        width: calc(50% - 40px);
        background: var(--bg-white);
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        position: relative;
        border: 2px solid #e5e7eb;
    }

    .timeline-item:hover .timeline-content {
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.15);
        border-color: var(--ekafa-secondary);
    }

    .timeline-item:nth-child(odd) .timeline-content {
        text-align: right;
    }

    .timeline-item:nth-child(even) .timeline-content {
        text-align: left;
    }

    .timeline-marker {
        width: 28px;
        height: 28px;
        background: var(--bg-white);
        border: 5px solid var(--ekafa-primary);
        border-radius: 50%;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        box-shadow: 0 0 0 8px rgba(0, 56, 41, 0.1);
    }

    .timeline-phase {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: var(--ekafa-gradient);
        color: var(--text-white);
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.9375rem;
        margin-bottom: 1rem;
    }

    .timeline-content h4 {
        font-size: 1.625rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .timeline-content p {
        color: var(--text-secondary);
        line-height: 1.7;
        font-size: 1.0625rem;
        font-weight: 400;
    }

    /* Features Grid - Better Contrast */
    .features-overview {
        padding: 80px 20px;
        background: var(--bg-lighter);
    }

    .features-overview-header {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 60px;
    }

    .features-overview-header h2 {
        font-size: 2.75rem;
        font-weight: 900;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .features-overview-header p {
        font-size: 1.25rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .features-grid-about {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .feature-item {
        background: var(--bg-white);
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
    }

    .feature-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.15);
        border-color: var(--ekafa-secondary);
    }

    .feature-item-icon {
        width: 72px;
        height: 72px;
        background: var(--ekafa-gradient);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--text-white);
        font-size: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .feature-item h4 {
        font-size: 1.375rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .feature-item p {
        font-size: 1.0625rem;
        color: var(--text-secondary);
        line-height: 1.7;
        font-weight: 400;
    }

    /* Animations */
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .about-hero h1 {
            font-size: 2.25rem;
        }

        .about-hero p {
            font-size: 1.125rem;
        }

        .content-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .content-text h2 {
            font-size: 2.25rem;
        }

        .mission-vision-container {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .timeline::before {
            left: 20px;
        }

        .timeline-item,
        .timeline-item:nth-child(even) {
            flex-direction: row !important;
        }

        .timeline-content {
            width: calc(100% - 60px);
            margin-left: 60px;
            text-align: left !important;
        }

        .timeline-marker {
            left: 20px;
            transform: none;
        }

        .features-grid-about {
            grid-template-columns: 1fr;
        }

        .mv-card,
        .feature-item,
        .highlight-box {
            padding: 2rem;
        }
    }
</style>

<div class="about-page">
    <!-- Hero Section -->
    <div class="about-hero">
        <div class="about-hero-content">
            <span class="about-badge">🌟 About Us</span>
            <h1><?= Html::encode($this->title) ?></h1>
            <p>
                Revolutionizing KAFA education management through innovative technology 
                and seamless digital integration at Pusat Islam Universiti Malaysia Sabah
            </p>
        </div>
    </div>

    <!-- Image Section -->
    <div class="about-image-section">
        <div class="about-image-card">
            <img src="<?= Url::to('@web/images/ekafa_picture.jpg') ?>" alt="E-KAFA PIUMS">
        </div>
    </div>

    <!-- Main Content -->
    <div class="about-content">
        <div class="content-grid">
            <div class="content-text">
                <h2>Transforming Islamic Education</h2>
                <p>
                    The <strong>E-KAFA PIUMS</strong> (Pusat Islam Universiti Malaysia Sabah) is a 
                    comprehensive web-based management system designed to digitalize and streamline 
                    the administration of the KAFA education program.
                </p>
                <p>
                    Born from the need to modernize traditional manual methods such as Google Forms 
                    and physical logbooks, our platform provides a centralized online solution that 
                    connects administrators, teachers, and parents in real-time.
                </p>
                <p>
                    Through systematic planning and Agile methodology, E-KAFA PIUMS integrates essential 
                    modules for comprehensive education management—from registration to graduation.
                </p>
            </div>

            <div class="highlight-box">
                <h3>Core Capabilities</h3>
                <ul>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <span>User Registration & Management</span>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Real-time Attendance Tracking</span>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Learning Management System</span>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Integrated Payment Processing</span>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Activity Scheduling</span>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Stock & Inventory Monitoring</span>
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Automated Notifications</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="mission-vision-section">
        <div class="mission-vision-container">
            <div class="mv-card">
                <div class="mv-icon">
                    <i class="bi bi-bullseye"></i>
                </div>
                <h3>Our Mission</h3>
                <p>
                    To empower KAFA education at PIUMS through innovative digital solutions that 
                    enhance administrative efficiency, improve communication, and create a seamless 
                    learning experience for students, teachers, and parents alike.
                </p>
            </div>

            <div class="mv-card">
                <div class="mv-icon">
                    <i class="bi bi-eye"></i>
                </div>
                <h3>Our Vision</h3>
                <p>
                    To be the leading digital platform for Islamic education management in Malaysia, 
                    setting new standards for transparency, accessibility, and excellence in KAFA 
                    administration and learning outcomes.
                </p>
            </div>
        </div>
    </div>

    <!-- Development Timeline -->
    <div class="timeline-section">
        <div class="timeline-header">
            <h2>Development Journey</h2>
            <p>Built with precision using Agile methodology for optimal results</p>
        </div>

        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-content">
                    <span class="timeline-phase">Phase 1</span>
                    <h4>Planning & Analysis</h4>
                    <p>
                        Comprehensive requirement gathering, stakeholder interviews, and system 
                        architecture design. Identified key pain points in traditional KAFA management.
                    </p>
                </div>
                <div class="timeline-marker"></div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <span class="timeline-phase">Phase 2</span>
                    <h4>Core Development</h4>
                    <p>
                        Built fundamental modules including user management, registration system, 
                        and authentication. Established database architecture and security protocols.
                    </p>
                </div>
                <div class="timeline-marker"></div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <span class="timeline-phase">Phase 3</span>
                    <h4>Feature Integration</h4>
                    <p>
                        Integrated attendance tracking, learning management, payment processing, 
                        and activity scheduling modules with seamless inter-module communication.
                    </p>
                </div>
                <div class="timeline-marker"></div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <span class="timeline-phase">Phase 4</span>
                    <h4>Testing & Refinement</h4>
                    <p>
                        Rigorous testing across all modules, user feedback incorporation, 
                        performance optimization, and security hardening.
                    </p>
                </div>
                <div class="timeline-marker"></div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <span class="timeline-phase">Phase 5</span>
                    <h4>Launch & Support</h4>
                    <p>
                        System deployment, user training, documentation, and ongoing support 
                        for continuous improvement and feature enhancement.
                    </p>
                </div>
                <div class="timeline-marker"></div>
            </div>
        </div>
    </div>

    <!-- Features Overview -->
    <div class="features-overview">
        <div class="features-overview-header">
            <h2>What Makes Us Different</h2>
            <p>Comprehensive features designed specifically for KAFA management needs</p>
        </div>

        <div class="features-grid-about">
            <div class="feature-item">
                <div class="feature-item-icon">
                    <i class="bi bi-lightning-charge"></i>
                </div>
                <h4>Real-Time Updates</h4>
                <p>Instant notifications and live data synchronization across all modules</p>
            </div>

            <div class="feature-item">
                <div class="feature-item-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h4>Secure & Reliable</h4>
                <p>Enterprise-grade security with encrypted data and role-based access</p>
            </div>

            <div class="feature-item">
                <div class="feature-item-icon">
                    <i class="bi bi-phone"></i>
                </div>
                <h4>Mobile Friendly</h4>
                <p>Responsive design works seamlessly on phones, tablets, and desktops</p>
            </div>

            <div class="feature-item">
                <div class="feature-item-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
                <h4>Analytics & Reports</h4>
                <p>Comprehensive reporting tools for data-driven decision making</p>
            </div>

            <div class="feature-item">
                <div class="feature-item-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h4>Multi-Role System</h4>
                <p>Tailored interfaces for admins, teachers, and parents</p>
            </div>

            <div class="feature-item">
                <div class="feature-item-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <h4>Continuous Updates</h4>
                <p>Regular improvements based on user feedback and needs</p>
            </div>
        </div>
    </div>
</div>

<script>
// Scroll animations for timeline and features
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe timeline items
    document.querySelectorAll('.timeline-item, .feature-item, .mv-card').forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(item);
    });
});
</script>