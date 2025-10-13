<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $user_id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $role
 * @property string|null $date_registered
 * @property string|null $last_login
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    const ROLE_ADMIN = 'Admin';
    const ROLE_TEACHER = 'Teacher';
    const ROLE_PARENT = 'Parent';

    public $password;

    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [['email', 'username', 'role'], 'required'],
            [['role'], 'string'],
            [['date_registered', 'last_login'], 'safe'],
            [['email', 'password_hash'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 100],
            [['email'], 'unique'],
            [['username'], 'unique'],

            // Password is required only on insert
            ['password', 'required', 'on' => 'insert'],

            // Password length and complexity when provided
            ['password', 'string', 'min' => 8, 'max' => 16, 'skipOnEmpty' => true],
            ['password', 'match',
                'pattern' => '/^(?=.*\d)(?=.*[\W_]).{8,16}$/',
                'message' => 'Password must be 8–16 characters long, include at least one number and one special character.',
                'skipOnEmpty' => true
            ],

            // Role options
            ['role', 'in', 'range' => array_keys(self::optsRole())],
        ];
    }



    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'Username',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'password' => 'Password',
            'role' => 'Role',
            'date_registered' => 'Date Registered',
            'last_login' => 'Last Login',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->password)) {
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            }

            if ($insert && empty($this->date_registered)) {
                $this->date_registered = date('Y-m-d H:i:s');
            }

            return true;
        }
        return false;
    }

    public static function find()
    {
        return new UsersQuery(get_called_class());
    }

    public static function optsRole()
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_TEACHER => 'Teacher',
            self::ROLE_PARENT => 'Parent',
        ];
    }

    public function displayRole()
    {
        return self::optsRole()[$this->role] ?? $this->role;
    }

    public function isRoleAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isRoleTeacher()
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function isRoleParent()
    {
        return $this->role === self::ROLE_PARENT;
    }

    // ─────────────────────────────────────
    // IdentityInterface implementations
    // ─────────────────────────────────────

    public static function findIdentity($id): IdentityInterface
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        // Only needed for API token auth (not used here)
        return null;
    }

    public function getId(): int|string
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(): ?string
    {
        return null; // optional: implement if you want "remember me"
    }

    public function validateAuthKey($authKey): bool
    {
        return true; // optional: implement if you want "remember me"
    }

    public function getUserDetails()
    {
        return $this->hasOne(UserDetails::class, ['user_id' => 'user_id']);
    }

}