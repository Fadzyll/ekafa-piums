<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;
    public $role; // <-- New property for role

    private $_user = false;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['role', 'safe'], // Allow 'role' to be assigned without validation error
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password and role.
     */
    public function validatePassword($attribute, $params)
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, 'Incorrect email, password, or role.');
            return;
        }

        if (!Yii::$app->security->validatePassword($this->password, $user->password_hash)) {
            $this->addError($attribute, 'Incorrect email or password.');
            return;
        }

        if (strcasecmp($user->role, $this->role) !== 0) {
            $this->addError($attribute, 'Access denied: role mismatch.');
        }
    }

    /**
     * Logs in the user if credentials are valid.
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();

            // Update last login timestamp
            $user->last_login = date('Y-m-d H:i:s');
            $user->save(false); // Skip validation

            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by email and role.
     *
     * @return Users|null
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Users::find()
                ->where(['email' => $this->email])
                ->andFilterWhere(['role' => $this->role])
                ->one();
        }

        return $this->_user;
    }
}