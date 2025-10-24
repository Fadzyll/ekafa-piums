<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidArgumentException;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $confirm_password;

    /**
     * @var \app\models\Users
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Password reset token cannot be blank.');
        }
        $this->_user = Users::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'confirm_password'], 'required'],
            ['password', 'string', 'min' => 8, 'max' => 16],
            ['password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[\W_]).{8,16}$/',
                'message' => 'Password must be 8–16 characters long, include at least one number and one special character.'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.'],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->_user;
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $user->removePasswordResetToken();
        $user->updated_at = time();

        return $user->save(false);
    }
}