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
 * @property string $auth_key
 * @property string|null $password_reset_token
 * @property string|null $verification_token
 * @property string $role
 * @property int $status
 * @property string|null $date_registered
 * @property string|null $last_login
 * @property int $created_at
 * @property int $updated_at
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    const ROLE_ADMIN = 'Admin';
    const ROLE_TEACHER = 'Teacher';
    const ROLE_PARENT = 'Parent';

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public $password;
    public $password_confirm;

    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [['email', 'username', 'role'], 'required'],
            [['role'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['date_registered', 'last_login'], 'safe'],
            [['email', 'password_hash', 'password_reset_token', 'verification_token'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 100],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['verification_token'], 'unique'],

            // Default values
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['created_at', 'updated_at'], 'default', 'value' => function() {
                return time();
            }],

            // Password is required only on insert
            ['password', 'required', 'on' => 'insert'],

            // Password length and complexity when provided
            ['password', 'string', 'min' => 8, 'max' => 16, 'skipOnEmpty' => true],
            ['password', 'match',
                'pattern' => '/^(?=.*\d)(?=.*[\W_]).{8,16}$/',
                'message' => 'Password must be 8–16 characters long, include at least one number and one special character.',
                'skipOnEmpty' => true
            ],

            // Password confirmation (only on insert)
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 
                'message' => 'Passwords do not match.', 'on' => 'insert'],

            // Role options
            ['role', 'in', 'range' => array_keys(self::optsRole())],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
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
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'verification_token' => 'Verification Token',
            'role' => 'Role',
            'status' => 'Status',
            'date_registered' => 'Date Registered',
            'last_login' => 'Last Login',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Only hash password if it's provided and not empty
            if (!empty($this->password)) {
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            }

            if ($insert) {
                if (empty($this->auth_key)) {
                    $this->auth_key = Yii::$app->security->generateRandomString(32);
                }
                if (empty($this->date_registered)) {
                    $this->date_registered = date('Y-m-d H:i:s');
                }
                $this->created_at = time();
            }

            $this->updated_at = time();

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

    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne(['user_id' => $id, 'status' => self::STATUS_ACTIVE]);
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
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Finds user by password reset token
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'] ?? 3600;
        return $timestamp + $expire >= time();
    }

    /**
     * Generates email verification token
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Relation to UserDetails (user_profiles table)
     */
    public function getUserDetails()
    {
        return $this->hasOne(UserDetails::class, ['user_id' => 'user_id']);
    }

    /**
     * Relation to UserJobDetails
     */
    public function getUserJobDetails()
    {
        return $this->hasOne(UserJobDetails::class, ['user_id' => 'user_id']);
    }

    /**
     * Relation to PartnerDetails (for Parents)
     */
    public function getPartnerDetails()
    {
        return $this->hasOne(PartnerDetails::class, ['partner_id' => 'user_id']);
    }
}