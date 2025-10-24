<?php

/* @var $this yii\web\View */
/* @var $user app\models\Users */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->username ?>,

We received a request to reset your password for your E-KAFA PIUMS account.

To reset your password, please click on the following link or copy and paste it into your browser:

<?= $resetLink ?>

⚠️ IMPORTANT: This link will expire in 1 hour for security reasons.

If you didn't request a password reset, please ignore this email or contact support if you have concerns.

Best regards,
E-KAFA PIUMS Team

---
This is an automated email. Please do not reply to this message.
© <?= date('Y') ?> E-KAFA PIUMS. All rights reserved.