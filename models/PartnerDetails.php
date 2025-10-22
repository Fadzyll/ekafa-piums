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
 * @property string|null $ic_copy_url
 * @property string|null $date_of_birth
 * @property string|null $gender
 * @property string|null $blood_type
 * @property int|null $has_health_conditions
 * @property string|null $health_conditions_details
 * @property string|null $race
 * @property string|null $religion
 * @property string|null $education_level
 * @property string|null $partner_phone_number
 * @property string|null $email
 * @property string|null $alternative_phone
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property string|null $emergency_contact_relationship
 * @property string|null $partner_citizenship
 * @property string|null $partner_marital_status
 * @property string|null $relationship_to_user
 * @property string|null $marriage_date
 * @property string|null $marriage_certificate_no
 * @property string|null $divorce_date
 * @property string|null $divorce_certificate_no
 * @property string|null $partner_address
 * @property string|null $partner_city
 * @property string|null $partner_postcode
 * @property string|null $partner_state
 * @property string|null $country
 * @property string|null $profile_picture_url
 * @property string|null $status
 * @property int|null $is_verified
 * @property int|null $verified_by
 * @property string|null $verified_at
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property UserDetails $userDetails
 * @property PartnerJob $partnerJob
 * @property Users $verifiedBy
 */
class PartnerDetails extends \yii\db\ActiveRecord
{
    /** @var UploadedFile */
    public $imageFile;
    /** @var UploadedFile */
    public $icFile;

    const GENDER_MALE = 'Male';
    const GENDER_FEMALE = 'Female';

    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';
    const STATUS_DIVORCED = 'Divorced';
    const STATUS_DECEASED = 'Deceased';

    const RELATIONSHIP_SPOUSE = 'Spouse';
    const RELATIONSHIP_EX_SPOUSE = 'Ex-Spouse';
    const RELATIONSHIP_PARTNER = 'Partner';
    const RELATIONSHIP_OTHER = 'Other';

    public static function tableName()
    {
        return 'partner_details';
    }

    public function rules()
    {
        return [
            [['partner_id', 'partner_name', 'partner_ic_number'], 'required'],
            
            [['partner_id', 'has_health_conditions', 'is_verified', 'verified_by'], 'integer'],
            [['date_of_birth', 'marriage_date', 'divorce_date', 'verified_at', 'created_at', 'updated_at'], 'safe'],
            [['health_conditions_details', 'partner_address'], 'string'],
            [['gender', 'blood_type', 'education_level', 'relationship_to_user', 'status'], 'string'],
            
            [['partner_name', 'ic_copy_url', 'email', 'profile_picture_url'], 'string', 'max' => 255],
            [['partner_ic_number', 'partner_phone_number', 'alternative_phone', 'emergency_contact_phone'], 'string', 'max' => 20],
            [['race', 'religion', 'emergency_contact_relationship', 'partner_marital_status'], 'string', 'max' => 50],
            [['partner_citizenship', 'partner_city', 'partner_state', 'country', 'marriage_certificate_no', 'divorce_certificate_no'], 'string', 'max' => 100],
            [['partner_postcode'], 'string', 'max' => 10],
            [['emergency_contact_name'], 'string', 'max' => 255],
            
            // Default values
            [['blood_type'], 'default', 'value' => 'Unknown'],
            [['has_health_conditions'], 'default', 'value' => 0],
            [['country'], 'default', 'value' => 'Malaysia'],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['relationship_to_user'], 'default', 'value' => self::RELATIONSHIP_SPOUSE],
            [['is_verified'], 'default', 'value' => 0],
            
            // IC validation
            [['partner_ic_number'], 'match', 'pattern' => '/^\d{12}$/', 'message' => 'IC number must be exactly 12 digits.'],
            
            // Phone validation
            [['partner_phone_number', 'alternative_phone', 'emergency_contact_phone'], 'match', 'pattern' => '/^(\+?6?01)[0-9]{8,9}$/', 'message' => 'Please enter a valid Malaysian phone number.', 'skipOnEmpty' => true],
            
            // Email validation
            [['email'], 'email'],
            
            // Enum validation
            ['gender', 'in', 'range' => array_keys(self::optsGender())],
            ['blood_type', 'in', 'range' => array_keys(self::optsBloodType())],
            ['education_level', 'in', 'range' => array_keys(self::optsEducationLevel())],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            ['relationship_to_user', 'in', 'range' => array_keys(self::optsRelationship())],
            
            [['partner_id'], 'unique'],

            // File validation
            [['imageFile', 'icFile'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['png', 'jpg', 'jpeg'],
                'mimeTypes' => ['image/jpeg', 'image/png'],
                'maxSize' => 2 * 1024 * 1024,
                'tooBig' => 'The file is too large. Maximum size is 2MB.',
                'checkExtensionByMimeType' => true,
            ],

            // Foreign keys
            [['partner_id'], 'exist', 'skipOnError' => true,
                'targetClass' => UserDetails::class,
                'targetAttribute' => ['partner_id' => 'user_id']
            ],
            [['verified_by'], 'exist', 'skipOnError' => true,
                'targetClass' => Users::class,
                'targetAttribute' => ['verified_by' => 'user_id']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'partner_id' => 'Partner ID',
            'partner_name' => 'Partner Name',
            'partner_ic_number' => 'Partner IC Number',
            'ic_copy_url' => 'IC Copy',
            'date_of_birth' => 'Date of Birth',
            'gender' => 'Gender',
            'blood_type' => 'Blood Type',
            'has_health_conditions' => 'Has Health Conditions',
            'health_conditions_details' => 'Health Conditions Details',
            'race' => 'Race',
            'religion' => 'Religion',
            'education_level' => 'Education Level',
            'partner_phone_number' => 'Partner Phone Number',
            'email' => 'Email',
            'alternative_phone' => 'Alternative Phone',
            'emergency_contact_name' => 'Emergency Contact Name',
            'emergency_contact_phone' => 'Emergency Contact Phone',
            'emergency_contact_relationship' => 'Emergency Contact Relationship',
            'partner_citizenship' => 'Partner Citizenship',
            'partner_marital_status' => 'Partner Marital Status',
            'relationship_to_user' => 'Relationship to User',
            'marriage_date' => 'Marriage Date',
            'marriage_certificate_no' => 'Marriage Certificate No',
            'divorce_date' => 'Divorce Date',
            'divorce_certificate_no' => 'Divorce Certificate No',
            'partner_address' => 'Partner Address',
            'partner_city' => 'Partner City',
            'partner_postcode' => 'Partner Postcode',
            'partner_state' => 'Partner State',
            'country' => 'Country',
            'profile_picture_url' => 'Profile Picture',
            'status' => 'Status',
            'is_verified' => 'Is Verified',
            'verified_by' => 'Verified By',
            'verified_at' => 'Verified At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'imageFile' => 'Upload Profile Picture',
            'icFile' => 'Upload IC Copy',
        ];
    }

    public function getPartnerJob()
    {
        return $this->hasOne(PartnerJob::class, ['partner_id' => 'partner_id']);
    }

    public function getUserDetails()
    {
        return $this->hasOne(UserDetails::class, ['user_id' => 'partner_id']);
    }

    public function getVerifiedBy()
    {
        return $this->hasOne(Users::class, ['user_id' => 'verified_by']);
    }

    public static function optsGender()
    {
        return [
            self::GENDER_MALE => 'Male',
            self::GENDER_FEMALE => 'Female',
        ];
    }

    public static function optsBloodType()
    {
        return [
            'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
            'O+' => 'O+',
            'O-' => 'O-',
            'Unknown' => 'Unknown',
        ];
    }

    public static function optsEducationLevel()
    {
        return [
            'No Formal Education' => 'No Formal Education',
            'Primary' => 'Primary',
            'Secondary' => 'Secondary',
            'Diploma' => 'Diploma',
            'Bachelor' => 'Bachelor',
            'Master' => 'Master',
            'PhD' => 'PhD',
            'Other' => 'Other',
        ];
    }

    public static function optsStatus()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_DIVORCED => 'Divorced',
            self::STATUS_DECEASED => 'Deceased',
        ];
    }

    public static function optsRelationship()
    {
        return [
            self::RELATIONSHIP_SPOUSE => 'Spouse',
            self::RELATIONSHIP_EX_SPOUSE => 'Ex-Spouse',
            self::RELATIONSHIP_PARTNER => 'Partner',
            self::RELATIONSHIP_OTHER => 'Other',
        ];
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

    public static function find()
    {
        return new PartnerDetailsQuery(get_called_class());
    }
}