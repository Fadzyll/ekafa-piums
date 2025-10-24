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
            return false;
        }

        if (!Users::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save(false)) {
                return false;
            }
        }

        // Try to send email with explicit view path
        try {
            return Yii::$app
                ->mailer
                ->compose(
                    [
                        'html' => '@app/views/mail/passwordResetToken-html',  // Explicit path
                        'text' => '@app/views/mail/passwordResetToken-text'   // Explicit path
                    ],
                    ['user' => $user]
                )
                ->setFrom([Yii::$app->params['supportEmail'] ?? 'noreply@ekafa-piums.test' => Yii::$app->name . ' Support'])
                ->setTo($this->email)
                ->setSubject('Password reset for ' . Yii::$app->name)
                ->send();
        } catch (\Exception $e) {
            Yii::error('Failed to send password reset email: ' . $e->getMessage());
            return false;
        }
    }
}