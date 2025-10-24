<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\Users */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
            color: #333;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Password Reset Request</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong><?= Html::encode($user->username) ?></strong>,</p>
            
            <p>We received a request to reset your password for your E-KAFA PIUMS account. Click the button below to create a new password:</p>
            
            <div style="text-align: center;">
                <a href="<?= Html::encode($resetLink) ?>" class="button">Reset My Password</a>
            </div>
            
            <p style="font-size: 14px; color: #6c757d;">Or copy and paste this link into your browser:</p>
            <p style="word-break: break-all; font-size: 12px; background: #f8f9fa; padding: 10px; border-radius: 4px;">
                <?= Html::encode($resetLink) ?>
            </p>
            
            <div class="warning">
                <strong>⚠️ Important:</strong> This link will expire in 1 hour for security reasons.
            </div>
            
            <p style="margin-top: 20px;">If you didn't request a password reset, please ignore this email or contact support if you have concerns.</p>
            
            <p style="margin-top: 30px;">
                Best regards,<br>
                <strong>E-KAFA PIUMS Team</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; <?= date('Y') ?> E-KAFA PIUMS. All rights reserved.</p>
        </div>
    </div>
</body>
</html>