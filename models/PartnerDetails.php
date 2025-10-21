<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "partner_details".
 *
 * @property int $partner_id
 * @property string $partner_name
 * @property string $partner_ic_number
 * @property string|null $partner_phone_number
 * @property string|null $partner_citizenship
 * @property string|null $partner_marital_status
 * @property string|null $partner_address
 * @property string|null $partner_city
 * @property string|null $partner_postcode
 * @property string|null $partner_state
 * @property string|null $profile_picture_url
 *
 * @property UserDetails $userDetails
 * @property PartnerJob $partnerJob
 */
class PartnerDetails extends \yii\db\ActiveRecord
{
    /** @var UploadedFile */
    public $imageFile;

    public static function tableName()
    {
        return 'partner_details';
    }

    public function rules()
    {
        return [
            // ✅ REQUIRED FIELDS
            [['partner_id', 'partner_name', 'partner_ic_number'], 'required'],
            
            [['partner_id'], 'integer'],
            [['partner_address'], 'string'],
            [['partner_name'], 'string', 'max' => 255],
            [['partner_ic_number', 'partner_phone_number'], 'string', 'max' => 20],
            [['partner_citizenship', 'partner_city', 'partner_state'], 'string', 'max' => 100],
            [['partner_marital_status'], 'string', 'max' => 50],
            [['partner_postcode'], 'string', 'max' => 10],
            [['profile_picture_url'], 'string', 'max' => 255],
            
            // ✅ IC validation
            [['partner_ic_number'], 'match', 'pattern' => '/^\d{12}$/', 'message' => 'IC number must be exactly 12 digits.'],
            
            // ✅ Phone validation
            [['partner_phone_number'], 'match', 'pattern' => '/^(\+?6?01)[0-9]{8,9}$/', 'message' => 'Please enter a valid Malaysian phone number.'],
            
            [['partner_id'], 'unique'],

            // ✅ File validation
            [['imageFile'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['png', 'jpg', 'jpeg'],
                'mimeTypes' => ['image/jpeg', 'image/png'],
                'maxSize' => 2 * 1024 * 1024,
                'tooBig' => 'The file is too large. Maximum size is 2MB.',
                'checkExtensionByMimeType' => true,
            ],

            // ✅ FIXED: Foreign key
            [['partner_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => UserDetails::class,
                'targetAttribute' => ['partner_id' => 'user_id']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'partner_id' => 'Partner ID',
            'partner_name' => 'Partner Name',
            'partner_ic_number' => 'Partner IC Number',
            'partner_phone_number' => 'Partner Phone Number',
            'partner_citizenship' => 'Partner Citizenship',
            'partner_marital_status' => 'Partner Marital Status',
            'partner_address' => 'Partner Address',
            'partner_city' => 'Partner City',
            'partner_postcode' => 'Partner Postcode',
            'partner_state' => 'Partner State',
            'profile_picture_url' => 'Profile Picture',
            'imageFile' => 'Upload New Picture',
        ];
    }

    /**
     * Relation to PartnerJob
     */
    public function getPartnerJob()
    {
        return $this->hasOne(PartnerJob::class, ['partner_id' => 'partner_id']);
    }

    /**
     * ✅ FIXED: Relation to UserDetails
     */
    public function getUserDetails()
    {
        return $this->hasOne(UserDetails::class, ['user_id' => 'partner_id']);
    }

    public static function find()
    {
        return new PartnerDetailsQuery(get_called_class());
    }
}