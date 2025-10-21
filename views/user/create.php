<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = 'Create New User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;

?>

<style>
.create-user-wrapper {
    max-width: 800px;
    margin: 0 auto;
}

.progress-steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 3px;
    background: #e9ecef;
    z-index: 0;
}

.progress-line {
    position: absolute;
    top: 20px;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    transition: width 0.3s ease;
    z-index: 1;
}

.step {
    flex: 1;
    text-align: center;
    position: relative;
    z-index: 2;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-weight: bold;
    transition: all 0.3s ease;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.step.active .step-circle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: scale(1.1);
}

.step.completed .step-circle {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.step-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 600;
}

.step.active .step-label {
    color: #667eea;
}

.step.completed .step-label {
    color: #43e97b;
}

.create-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
}

.create-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2.5rem;
    text-align: center;
    position: relative;
}

.create-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
}

.create-header-icon {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    position: relative;
    z-index: 1;
}

.create-header-icon i {
    font-size: 2.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.create-header h3 {
    color: white;
    margin: 0 0 0.5rem 0;
    font-weight: 600;
    font-size: 2rem;
    position: relative;
    z-index: 1;
}

.create-header p {
    color: rgba(255,255,255,0.9);
    margin: 0;
    position: relative;
    z-index: 1;
}

.create-body {
    padding: 2.5rem;
}

.info-banner {
    background: linear-gradient(135deg, #e8f4ff 0%, #d4e8ff 100%);
    border-left: 4px solid #667eea;
    padding: 1.2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    display: flex;
    align-items: start;
    gap: 1rem;
}

.info-banner-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.info-banner-content h5 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-weight: 600;
}

.info-banner-content ul {
    margin: 0;
    padding-left: 1.2rem;
    color: #555;
}

.info-banner-content ul li {
    margin-bottom: 0.3rem;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.progress-steps {
    animation: slideInDown 0.5s ease;
}

.create-card {
    animation: fadeIn 0.6s ease;
}

@media (max-width: 768px) {
    .create-user-wrapper {
        padding: 0 1rem;
    }
    
    .progress-steps {
        flex-wrap: wrap;
    }
    
    .step {
        margin-bottom: 1rem;
    }
    
    .create-header {
        padding: 2rem 1.5rem;
    }
    
    .create-body {
        padding: 1.5rem;
    }
}
</style>

<div class="create-user-wrapper">
    <!-- Progress Steps -->
    <div class="progress-steps">
        <div class="progress-line" style="width: 0%"></div>
        <div class="step active" data-step="1">
            <div class="step-circle">
                <i class="fas fa-user"></i>
            </div>
            <div class="step-label">Basic Info</div>
        </div>
        <div class="step" data-step="2">
            <div class="step-circle">
                <i class="fas fa-lock"></i>
            </div>
            <div class="step-label">Security</div>
        </div>
        <div class="step" data-step="3">
            <div class="step-circle">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Complete</div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="create-card">
        <div class="create-header">
            <div class="create-header-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h3><?= Html::encode($this->title) ?></h3>
            <p>Fill in the details below to create a new user account</p>
        </div>

        <div class="create-body">
            <!-- Information Banner -->
            <div class="info-banner">
                <div class="info-banner-icon">
                    <i class="fas fa-info"></i>
                </div>
                <div class="info-banner-content">
                    <h5>Before you start:</h5>
                    <ul>
                        <li>Ensure all required fields are filled correctly</li>
                        <li>Password must be 8-16 characters with numbers and special characters</li>
                        <li>Email addresses must be unique in the system</li>
                        <li>Choose the appropriate role for the user</li>
                    </ul>
                </div>
            </div>

            <?= $this->render('_form', [
                'model' => $model,
                'isRestricted' => false,
            ]) ?>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
    // Track form field completion for progress
    function updateProgress() {
        const username = $('#users-username').val();
        const email = $('#users-email').val();
        const password = $('#password').val();
        const role = $('#users-role').val();
        
        let progress = 0;
        const steps = $('.step');
        const progressLine = $('.progress-line');
        
        // Step 1: Basic Info (username, email)
        if (username && email) {
            progress = 33;
            steps.eq(0).addClass('completed');
            steps.eq(1).addClass('active');
        } else {
            steps.eq(0).removeClass('completed');
            steps.eq(1).removeClass('active completed');
            steps.eq(2).removeClass('active completed');
        }
        
        // Step 2: Security (password, role)
        if (username && email && password && role) {
            progress = 66;
            steps.eq(1).addClass('completed');
            steps.eq(2).addClass('active');
        }
        
        // Step 3: Complete (all filled)
        if (username && email && password && role && password.length >= 8) {
            progress = 100;
            steps.eq(2).addClass('completed');
        }
        
        progressLine.css('width', progress + '%');
    }
    
    // Monitor form inputs
    $('#users-username, #users-email, #password, #users-role').on('input change', function() {
        updateProgress();
    });
    
    // Initial check
    updateProgress();
JS);
?>