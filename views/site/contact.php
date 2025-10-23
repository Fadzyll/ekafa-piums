<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Contact Us';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    /* ==========================
       Modern Contact Page Styles
       ========================== */
    
    :root {
        --ekafa-primary: #003829;
        --ekafa-primary-light: #00563d;
        --ekafa-secondary: #00b377;
        --ekafa-gradient: linear-gradient(135deg, #003829 0%, #00563d 100%);
        --text-dark: #111827;
        --text-light: #6b7280;
        --white: #ffffff;
    }

    .contact-page {
        background: white;
        padding-bottom: 60px;
    }

    /* Hero Section */
    .contact-hero {
        background: var(--ekafa-gradient);
        padding: 80px 20px 60px;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .contact-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    .contact-hero-content {
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .contact-badge {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .contact-hero h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.2;
        color: var(--text-white);
    }

    .contact-hero p {
        font-size: 1.25rem;
        opacity: 0.95;
        line-height: 1.8;
        color: var(--text-white);
    }

    /* Main Content */
    .contact-content {
        max-width: 1200px;
        margin: -40px auto 60px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    /* Contact Info Card */
    .contact-info-card {
        background: white;
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .contact-info-card h2 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
    }

    .contact-info-card p {
        font-size: 1rem;
        color: var(--text-light);
        line-height: 1.8;
        margin-bottom: 2rem;
    }

    /* Contact Item */
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-radius: 16px;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        border-left: 4px solid var(--ekafa-secondary);
    }

    .contact-item:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .contact-item-icon {
        width: 56px;
        height: 56px;
        background: white;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ekafa-primary);
        font-size: 1.75rem;
        flex-shrink: 0;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .contact-item-content {
        flex: 1;
    }

    .contact-item-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .contact-item-text {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
        word-break: break-word;
    }

    .contact-item-text a {
        color: var(--text-dark);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .contact-item-text a:hover {
        color: var(--ekafa-secondary);
    }

    /* Map Card */
    .map-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .map-card h3 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
    }

    .map-container {
        width: 100%;
        height: 450px;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    /* Office Hours */
    .office-hours {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-top: 2rem;
        border-left: 4px solid var(--ekafa-secondary);
    }

    .office-hours h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ekafa-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .office-hours-grid {
        display: grid;
        gap: 0.75rem;
    }

    .office-hours-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 56, 41, 0.1);
    }

    .office-hours-item:last-child {
        border-bottom: none;
    }

    .office-hours-day {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 1rem;
    }

    .office-hours-time {
        color: var(--text-light);
        font-weight: 500;
        font-size: 1rem;
    }

    /* Social Media Section */
    .social-section {
        background: linear-gradient(180deg, white 0%, #f9fafb 100%);
        padding: 60px 20px;
    }

    .social-container {
        max-width: 1200px;
        margin: 0 auto;
        text-align: center;
    }

    .social-container h3 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .social-container p {
        font-size: 1.125rem;
        color: var(--text-light);
        margin-bottom: 2.5rem;
    }

    .social-links {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .social-link {
        width: 64px;
        height: 64px;
        background: white;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ekafa-primary);
        font-size: 1.75rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        border: 2px solid transparent;
    }

    .social-link:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        border-color: var(--ekafa-secondary);
        color: var(--ekafa-secondary);
    }

    /* Animations */
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .contact-hero h1 {
            font-size: 2rem;
        }

        .contact-grid {
            grid-template-columns: 1fr;
        }

        .contact-info-card,
        .map-card {
            padding: 2rem;
        }

        .map-container {
            height: 300px;
        }

        .contact-item {
            padding: 1.25rem;
        }

        .contact-item-icon {
            width: 48px;
            height: 48px;
            font-size: 1.5rem;
        }
    }
</style>

<div class="contact-page">
    <!-- Hero Section -->
    <div class="contact-hero">
        <div class="contact-hero-content">
            <span class="contact-badge">📞 Get In Touch</span>
            <h1><?= Html::encode($this->title) ?></h1>
            <p>
                We're here to help! Reach out to the E-KAFA PIUMS administration 
                team for any inquiries or support.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="contact-content">
        <div class="contact-grid">
            <!-- Contact Information -->
            <div class="contact-info-card">
                <h2>Contact Information</h2>
                <p>
                    Feel free to reach out to us through any of the following channels. 
                    Our team is ready to assist you.
                </p>

                <!-- Phone -->
                <div class="contact-item">
                    <div class="contact-item-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div class="contact-item-content">
                        <div class="contact-item-label">Phone</div>
                        <p class="contact-item-text">
                            <a href="tel:+60388203000">+60 88-820 3000</a>
                        </p>
                    </div>
                </div>

                <!-- Email -->
                <div class="contact-item">
                    <div class="contact-item-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div class="contact-item-content">
                        <div class="contact-item-label">Email</div>
                        <p class="contact-item-text">
                            <a href="mailto:piums@ums.edu.my">piums@ums.edu.my</a>
                        </p>
                    </div>
                </div>

                <!-- Address -->
                <div class="contact-item">
                    <div class="contact-item-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div class="contact-item-content">
                        <div class="contact-item-label">Address</div>
                        <p class="contact-item-text">
                            Pusat Islam Universiti Malaysia Sabah,<br>
                            Jalan UMS, 88400 Kota Kinabalu,<br>
                            Sabah, Malaysia
                        </p>
                    </div>
                </div>

                <!-- Office Hours -->
                <div class="office-hours">
                    <h4>
                        <i class="bi bi-clock"></i>
                        Office Hours
                    </h4>
                    <div class="office-hours-grid">
                        <div class="office-hours-item">
                            <span class="office-hours-day">Monday - Thursday</span>
                            <span class="office-hours-time">8:00 AM - 5:00 PM</span>
                        </div>
                        <div class="office-hours-item">
                            <span class="office-hours-day">Friday</span>
                            <span class="office-hours-time">8:00 AM - 12:00 PM<br>2:30 PM - 5:00 PM</span>
                        </div>
                        <div class="office-hours-item">
                            <span class="office-hours-day">Weekend & Public Holidays</span>
                            <span class="office-hours-time">Closed</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="map-card">
                <h3>Find Us</h3>
                <div class="map-container">
                    <!-- KAFA Pusat Islam UMS Map Embed -->
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.084887458668!2d116.12267607584208!3d6.0378098269869815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x323b6b0eeabc2ef5%3A0x3030fd6209b8c611!2sKAFA%20Pusat%20Islam%20UMS!5e0!3m2!1sen!2smy!4v1730000000000!5m2!1sen!2smy"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Media Section -->
    <div class="social-section">
        <div class="social-container">
            <h3>Connect With Us</h3>
            <p>Follow us on social media for updates and announcements</p>
            
            <div class="social-links">
                <a href="https://www.facebook.com/pusatislamums/?locale=ms_MY" target="_blank" class="social-link" title="Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://www.tiktok.com/@pusatislamums" target="_blank" class="social-link" title="TikTok">
                    <i class="bi bi-tiktok"></i>
                </a>
                <a href="https://ums.edu.my/piumsv2/index.php" target="_blank" class="social-link" title="Website">
                    <i class="bi bi-globe"></i>
                </a>
                <a href="mailto:pusatislam[a]ums.edu.my" class="social-link" title="Email">
                    <i class="bi bi-envelope"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Scroll animations
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

    // Observe contact items
    document.querySelectorAll('.contact-item, .map-card').forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(item);
    });
});
</script>