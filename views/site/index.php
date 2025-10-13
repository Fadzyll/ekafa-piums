<?php
/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'E-KAFA PIUMS';
?>

<style>
    .landing-wrapper {
        background-color: #ffffff; /* solid white */
        padding: 60px 30px;
        border-radius: 16px;
        color: #004135; /* matching system theme color */
        max-width: 700px;
        margin: 100px auto;
        text-align: center;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }

    .landing-wrapper h1 {
        font-size: 42px;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .landing-wrapper p.lead {
        font-size: 20px;
        margin-bottom: 30px;
    }

    body {
        background-image: url('<?= Url::to('@web/images/Masjid_UMS.jpg') ?>');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
    }
</style>

<div class="landing-wrapper">
    <h1>Welcome to E-KAFA PIUMS</h1>
    <p class="lead">Pusat Islam UMS | KAFA Education System</p>
</div>