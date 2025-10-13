<?php

namespace app\models;

use Yii;
use app\models\Users;
use app\models\PartnerDetails;
use app\models\UserJob;
use app\models\UserDetailsQuery;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user_details".
 *
 * @property int $user_details_id
 * @property int|null $user_id
 * @property string|null $full_name
 * @property string|null $ic_number
 * @property int|null $age
 * @property string|null $gender
 * @property string|null $race
 * @property string|null $phone_number
 * @property string|null $citizenship
 * @property string|null $marital_status
 * @property string|null $address
 * @property string|null $city
 * @property string|null $postcode
 * @property string|null $state
 * @property string|null $profile_picture_url
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Users $users
 *
 * @property UploadedFile $imageFile
 */
class UserDetails extends \yii\db\ActiveRecord
{
    /** @var UploadedFile */
    public $imageFile;

    const GENDER_MALE = 'Male';
    const GENDER_FEMALE = 'Female';

    public static function tableName()
    {
        return 'user_details';
    }

    public function rules()
    {
        return [
            [['user_id', 'age'], 'integer'],
            [['gender', 'address'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['full_name', 'profile_picture_url'], 'string', 'max' => 255],
            [['ic_number', 'phone_number'], 'string', 'max' => 20],
            [['race', 'marital_status'], 'string', 'max' => 50],
            [['citizenship', 'city', 'state'], 'string', 'max' => 100],
            [['postcode'], 'string', 'max' => 10],

            [['ic_number'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],

            ['gender', 'in', 'range' => array_keys(self::optsGender())],

            // File validation: extensions, mime types, max size (2MB), optional
            [['imageFile'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['png', 'jpg', 'jpeg'],
                'mimeTypes' => ['image/jpeg', 'image/png'],
                'maxSize' => 2 * 1024 * 1024, // 2MB
                'tooBig' => 'The file is too large. Maximum size is 2MB.',
                'checkExtensionByMimeType' => true,
            ],

            // Image validation: dimension limits, optional
            [['imageFile'], 'image',
                'skipOnEmpty' => true,
                'minWidth' => 100,
                'minHeight' => 100,
                'maxWidth' => 1000,
                'maxHeight' => 1000,
                'tooBig' => 'The image is too large. Maximum size is 2MB.',
                'underWidth' => 'The image width is too small. Minimum width is 100px.',
                'underHeight' => 'The image height is too small. Minimum height is 100px.',
                'overWidth' => 'The image width is too large. Maximum width is 1000px.',
                'overHeight' => 'The image height is too large. Maximum height is 1000px.',
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_details_id' => 'User Details ID',
            'user_id' => 'User ID',
            'full_name' => 'Full Name',
            'ic_number' => 'IC Number',
            'age' => 'Age',
            'gender' => 'Gender',
            'race' => 'Race',
            'phone_number' => 'Phone Number',
            'citizenship' => 'Citizenship',
            'marital_status' => 'Marital Status',
            'address' => 'Address',
            'city' => 'City',
            'postcode' => 'Postcode',
            'state' => 'State',
            'profile_picture_url' => 'Profile Picture',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'imageFile' => 'Upload Profile Picture',
        ];
    }

    public function getUsers()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

        public function getUserJob()
    {
        return $this->hasOne(\app\models\UserJob::class, ['user_id' => 'user_id']);
    }


    public function getPartnerDetails()
    {
        return $this->hasOne(PartnerDetails::class, ['partner_id' => 'user_details_id']);
    }

    public static function find()
    {
        return new UserDetailsQuery(get_called_class());
    }

    // GENDER ENUM
    public static function optsGender()
    {
        return [
            self::GENDER_MALE => 'Male',
            self::GENDER_FEMALE => 'Female',
        ];
    }

    public function displayGender()
    {
        return self::optsGender()[$this->gender] ?? '-';
    }

    public function isGenderMale()
    {
        return $this->gender === self::GENDER_MALE;
    }

    public function isGenderFemale()
    {
        return $this->gender === self::GENDER_FEMALE;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->updated_at = date('Y-m-d H:i:s');
            if ($insert && $this->created_at === null) {
                $this->created_at = $this->updated_at;
            }
            return true;
        }
        return false;
    }
}