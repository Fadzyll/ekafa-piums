<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;

class RegisterAccForm extends Model
{
    public $email;
    public $password;
    public $confirm_password;

    public function rules()
    {
        return [
            [['email', 'password', 'confirm_password'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => Users::class, 'message' => 'This email is already registered.'],

            // Password must be 8–16 characters, with number and special character
            ['password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[\W_]).{8,16}$/',
                'message' => 'Password must be 8–16 characters long, include at least one number and one special character.'
            ],

            // Confirm password must match password
            ['confirm_password', 'compare', 'compareAttribute' => 'password',
                'message' => 'Passwords do not match.'
            ],
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new Users();
        $user->email = $this->email;
        $user->role = 'Parent';
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $user->date_registered = date('Y-m-d H:i:s');

        return $user->save(false); // Save without validation, since already validated
    }
}