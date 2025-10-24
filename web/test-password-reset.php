<?php
/**
 * Password Reset Debug Test Script
 * 
 * Place this file in: web/test-password-reset.php
 * Access via: http://your-site.test/test-password-reset.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
(new yii\web\Application($config));

// Set the email you want to test
$testEmail = 'fadzzf8@gmail.com'; // ← CHANGE THIS TO YOUR TEST EMAIL

?>
<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Debug</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
        h3 { color: #667eea; margin-top: 30px; }
        .success { color: #10b981; font-weight: bold; }
        .error { color: #ef4444; font-weight: bold; }
        .info { background: #f0f9ff; padding: 15px; border-left: 4px solid #3b82f6; margin: 10px 0; }
        .warning { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .test-box { background: #f8f9fa; padding: 15px; margin: 15px 0; border-radius: 5px; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔍 Password Reset Debug Test</h1>
    <div class="info">
        <strong>Testing Email:</strong> <?= htmlspecialchars($testEmail) ?>
    </div>

    <?php
    // TEST 1: Check if user exists
    echo "<h3>Test 1: Check if user exists in database</h3>";
    echo "<div class='test-box'>";
    
    $user = app\models\Users::findOne(['email' => $testEmail]);
    if ($user) {
        echo "<span class='success'>✅ User found!</span><br>";
        echo "<strong>Username:</strong> " . htmlspecialchars($user->username) . "<br>";
        echo "<strong>User ID:</strong> " . $user->user_id . "<br>";
        echo "<strong>Email:</strong> " . htmlspecialchars($user->email) . "<br>";
        echo "<strong>Status:</strong> " . $user->status . " (should be 10 for active)<br>";
        echo "<strong>Role:</strong> " . htmlspecialchars($user->role) . "<br>";
    } else {
        echo "<span class='error'>❌ User NOT found</span><br>";
        echo "<div class='warning'>";
        echo "<strong>Solution:</strong> The email doesn't exist in the database.<br>";
        echo "1. Register this email first, OR<br>";
        echo "2. Use an email that exists in your database<br>";
        echo "</div>";
    }
    echo "</div>";

    // TEST 2: Check user status
    if ($user) {
        echo "<h3>Test 2: Check if user is active</h3>";
        echo "<div class='test-box'>";
        if ($user->status == app\models\Users::STATUS_ACTIVE) {
            echo "<span class='success'>✅ User is ACTIVE (status = 10)</span><br>";
        } else {
            echo "<span class='error'>❌ User is NOT active</span><br>";
            echo "Current status: " . $user->status . "<br>";
            echo "<div class='warning'>";
            echo "<strong>Solution:</strong> Run this SQL query:<br>";
            echo "<pre>UPDATE users SET status = 10 WHERE email = '$testEmail';</pre>";
            echo "</div>";
        }
        echo "</div>";
    }

    // TEST 3: Check configuration
    echo "<h3>Test 3: Check configuration parameters</h3>";
    echo "<div class='test-box'>";
    
    $hasSupport = isset(Yii::$app->params['supportEmail']);
    $hasExpire = isset(Yii::$app->params['user.passwordResetTokenExpire']);
    
    echo "<strong>supportEmail:</strong> ";
    if ($hasSupport) {
        echo "<span class='success'>✅ " . htmlspecialchars(Yii::$app->params['supportEmail']) . "</span><br>";
    } else {
        echo "<span class='error'>❌ NOT SET</span><br>";
    }
    
    echo "<strong>user.passwordResetTokenExpire:</strong> ";
    if ($hasExpire) {
        echo "<span class='success'>✅ " . Yii::$app->params['user.passwordResetTokenExpire'] . " seconds</span><br>";
    } else {
        echo "<span class='error'>❌ NOT SET</span><br>";
    }
    
    if (!$hasSupport || !$hasExpire) {
        echo "<div class='warning'>";
        echo "<strong>Solution:</strong> Update your config/params.php to include:<br>";
        echo "<pre>";
        echo "return [\n";
        echo "    'adminEmail' => 'admin@piums.ums.edu.my',\n";
        echo "    'senderEmail' => 'noreply@piums.ums.edu.my',\n";
        echo "    'senderName' => 'E-KAFA PIUMS',\n";
        echo "    'supportEmail' => 'support@piums.ums.edu.my', // ← Add this\n";
        echo "    'user.passwordResetTokenExpire' => 3600, // ← Add this\n";
        echo "];\n";
        echo "</pre>";
        echo "</div>";
    }
    echo "</div>";

    // TEST 4: Test token generation
    if ($user) {
        echo "<h3>Test 4: Test password reset token generation</h3>";
        echo "<div class='test-box'>";
        
        $oldToken = $user->password_reset_token;
        $user->generatePasswordResetToken();
        
        if ($user->save(false)) {
            echo "<span class='success'>✅ Token generated successfully!</span><br>";
            echo "<strong>Token:</strong> " . substr($user->password_reset_token, 0, 30) . "...<br>";
            echo "<strong>Old token:</strong> " . ($oldToken ? substr($oldToken, 0, 30) . "..." : "none") . "<br>";
        } else {
            echo "<span class='error'>❌ Failed to save token</span><br>";
            echo "<pre>" . print_r($user->errors, true) . "</pre>";
        }
        echo "</div>";
    }

    // TEST 5: Test mailer configuration
    echo "<h3>Test 5: Check mailer configuration</h3>";
    echo "<div class='test-box'>";
    
    $mailer = Yii::$app->mailer;
    echo "<strong>Mailer class:</strong> " . get_class($mailer) . "<br>";
    echo "<strong>Use file transport:</strong> " . ($mailer->useFileTransport ? '<span class="success">Yes (emails saved to files)</span>' : '<span class="error">No (real emails)</span>') . "<br>";
    echo "<strong>View path:</strong> " . $mailer->viewPath . "<br>";
    
    if ($mailer->useFileTransport) {
        echo "<div class='info'>";
        echo "<strong>Note:</strong> Emails will be saved to <code>runtime/mail/</code> folder instead of being sent.<br>";
        echo "This is good for testing!";
        echo "</div>";
    }
    echo "</div>";

    // TEST 6: Check if email template files exist
    echo "<h3>Test 6: Check if email template files exist</h3>";
    echo "<div class='test-box'>";
    
    $htmlTemplate = Yii::getAlias('@app/views/mail/passwordResetToken-html.php');
    $textTemplate = Yii::getAlias('@app/views/mail/passwordResetToken-text.php');
    
    echo "<strong>HTML template:</strong> ";
    if (file_exists($htmlTemplate)) {
        echo "<span class='success'>✅ Found</span><br>";
    } else {
        echo "<span class='error'>❌ Not found</span> - Expected at: $htmlTemplate<br>";
    }
    
    echo "<strong>Text template:</strong> ";
    if (file_exists($textTemplate)) {
        echo "<span class='success'>✅ Found</span><br>";
    } else {
        echo "<span class='error'>❌ Not found</span> - Expected at: $textTemplate<br>";
    }
    echo "</div>";

    // TEST 7: Try to send a test email
    if ($user && $hasSupport && $hasExpire) {
        echo "<h3>Test 7: Attempt to send password reset email</h3>";
        echo "<div class='test-box'>";
        
        try {
            $result = Yii::$app->mailer
                ->compose(
                    [
                        'html' => '@app/views/mail/passwordResetToken-html',
                        'text' => '@app/views/mail/passwordResetToken-text'
                    ],
                    ['user' => $user]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' Support'])
                ->setTo($user->email)
                ->setSubject('Password reset for ' . Yii::$app->name)
                ->send();
            
            if ($result) {
                echo "<span class='success'>✅ Email sent successfully!</span><br>";
                if ($mailer->useFileTransport) {
                    echo "<div class='info'>";
                    echo "Check the <code>runtime/mail/</code> folder for a .eml file<br>";
                    echo "You can open it with a text editor or email client to see the email content.";
                    echo "</div>";
                } else {
                    echo "<div class='info'>";
                    echo "Check your email inbox at: " . htmlspecialchars($user->email);
                    echo "</div>";
                }
            } else {
                echo "<span class='error'>❌ Email sending returned false</span><br>";
                echo "<div class='warning'>";
                echo "Check <code>runtime/logs/app.log</code> for error details.";
                echo "</div>";
            }
        } catch (Exception $e) {
            echo "<span class='error'>❌ Exception occurred!</span><br>";
            echo "<strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        }
        echo "</div>";
    }

    // SUMMARY
    echo "<h3>📋 Summary</h3>";
    echo "<div class='test-box'>";
    
    $allPassed = true;
    
    if (!$user) {
        echo "<span class='error'>❌ User doesn't exist - PASSWORD RESET WILL FAIL</span><br>";
        $allPassed = false;
    }
    
    if ($user && $user->status != 10) {
        echo "<span class='error'>❌ User is not active - PASSWORD RESET WILL FAIL</span><br>";
        $allPassed = false;
    }
    
    if (!$hasSupport || !$hasExpire) {
        echo "<span class='error'>❌ Configuration incomplete - PASSWORD RESET WILL FAIL</span><br>";
        $allPassed = false;
    }
    
    if ($allPassed && $user) {
        echo "<span class='success'>✅ All tests passed! Password reset should work.</span><br>";
        echo "<div class='info'>";
        echo "<strong>Next step:</strong> Try the password reset form with email: " . htmlspecialchars($testEmail);
        echo "</div>";
    }
    
    echo "</div>";
    ?>

    <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
        <strong>📝 Logs Location:</strong><br>
        Check <code>runtime/logs/app.log</code> for detailed error messages<br><br>
        
        <strong>📧 Email Files Location (if using file transport):</strong><br>
        Check <code>runtime/mail/</code> folder for .eml files
    </div>
</div>
</body>
</html>