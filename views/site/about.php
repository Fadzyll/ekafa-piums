<?php
/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'About E-KAFA PIUMS';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .about-wrapper {
        background-color: #ffffff;
        padding: 60px 40px;
        border-radius: 16px;
        color: #004135;
        max-width: 900px;
        margin: 100px auto;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
        text-align: center;
    }

    .about-wrapper h1 {
        font-size: 42px;
        font-weight: bold;
        margin-bottom: 25px;
    }

    .about-wrapper p {
        font-size: 18px;
        line-height: 1.8;
        margin-bottom: 20px;
        text-align: justify;
    }

    .about-wrapper img {
        width: 400px; /* adjust size as needed */
        margin-bottom: 25px;
        border-radius: 12px; /* optional for smooth edges */
        box-shadow: 0 4px 12px rgba(0,0,0,0.2); /* optional for style */
    }

    body {
        background-image: url('<?= Url::to('@web/images/Masjid_UMS.jpg') ?>');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
    }
</style>

<div class="about-wrapper">
    <!-- ✅ Add your image here -->
    <img src="<?= Url::to('@web/images/ekafa_picture.jpg') ?>" alt="E-KAFA PIUMS Logo">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        The <strong>E-KAFA PIUMS</strong> (Pusat Islam Universiti Malaysia Sabah) is a 
        web-based management system developed to digitalize and streamline the administration 
        of the KAFA education program. It replaces traditional manual methods such as 
        Google Forms and physical logbooks with a centralized online platform.
    </p>
    <p>
        The system integrates essential modules for user registration, attendance tracking, 
        learning management, payment processing, activity scheduling, stock monitoring, and 
        reminder notifications. Designed using the Agile methodology, it ensures that 
        administrators, teachers, and parents can collaborate efficiently in real time.
    </p>
    <p>
        Through <strong>E-KAFA PIUMS</strong>, the management of KAFA at PIUMS becomes more 
        systematic, transparent, and accessible—reducing administrative workload and enhancing 
        the overall learning experience for students.
    </p>
</div>