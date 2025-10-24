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

    public function attributeLabels()
    {
        return [
            'email' => 'Email Address',
            'password' => 'Password',
            'confirm_password' => 'Confirm Password',
        ];
    }

    /**
     * Registers a new parent user
     * @return bool
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new Users();
        $user->scenario = 'insert'; // Use insert scenario for password validation
        $user->email = $this->email;
        
        // Generate username from email (before @ symbol)
        $emailParts = explode('@', $this->email);
        $baseUsername = $emailParts[0];
        
        // Ensure username is unique
        $username = $baseUsername;
        $counter = 1;
        while (Users::find()->where(['username' => $username])->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        $user->username = $username;
        $user->role = 'Parent';
        $user->status = Users::STATUS_ACTIVE;
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $user->auth_key = Yii::$app->security->generateRandomString(32);
        $user->date_registered = date('Y-m-d H:i:s');
        $user->created_at = time();
        $user->updated_at = time();

        // Optional: Generate email verification token
        // $user->generateEmailVerificationToken();

        return $user->save(false); // Save without validation, since already validated
    }
}