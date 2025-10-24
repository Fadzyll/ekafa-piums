<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\Users',
                'filter' => ['status' => Users::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user Users */
        $user = Users::findOne([
            'status' => Users::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            Yii::error('Password reset - User not found or inactive: ' . $this->email);
            return false;
        }

        // Generate new token if current one is invalid or doesn't exist
        if (!Users::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save(false)) {
                Yii::error('Password reset - Failed to save token for user: ' . $this->email);
                Yii::error('Password reset - User save errors: ' . print_r($user->errors, true));
                return false;
            }
        }

        // Try to send email with explicit view path
        try {
            $result = Yii::$app
                ->mailer
                ->compose(
                    [
                        'html' => '@app/views/mail/passwordResetToken-html',
                        'text' => '@app/views/mail/passwordResetToken-text'
                    ],
                    ['user' => $user]
                )
                ->setFrom([Yii::$app->params['supportEmail'] ?? 'noreply@ekafa-piums.test' => Yii::$app->name . ' Support'])
                ->setTo($this->email)
                ->setSubject('Password reset for ' . Yii::$app->name)
                ->send();
            
            if ($result) {
                Yii::info('Password reset email sent successfully to: ' . $this->email);
            } else {
                Yii::error('Password reset - Mailer send() returned false for: ' . $this->email);
            }
            
            return $result;
        } catch (\Exception $e) {
            Yii::error('Password reset - Exception sending email: ' . $e->getMessage());
            Yii::error('Password reset - Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }
}