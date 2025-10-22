<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user_profiles".
 *
 * @property int $user_details_id
 * @property int|null $user_id
 * @property string|null $full_name
 * @property string|null $ic_number
 * @property int|null $age
 * @property string|null $date_of_birth
 * @property string|null $gender
 * @property string|null $race
 * @property string|null $phone_number
 * @property string|null $emergency_phone
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_relationship
 * @property string|null $citizenship
 * @property string|null $marital_status
 * @property string|null $occupation
 * @property string|null $address
 * @property string|null $city
 * @property string|null $postcode
 * @property string|null $state
 * @property string|null $country
 * @property string|null $profile_picture_url
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $blood_type
 *
 * @property Users $user
 * @property UserJobDetails $userJobDetails
 * @property PartnerDetails $partnerDetails
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
        return 'user_profiles';
    }

    public function rules()
    {
        return [
            // ✅ REQUIRED FIELDS
            [['full_name', 'ic_number', 'phone_number'], 'required'],
            
            // Integer fields
            [['user_id', 'age'], 'integer'],
            
            // String fields with length limits
            [['full_name', 'profile_picture_url', 'emergency_contact_name', 'occupation'], 'string', 'max' => 255],
            [['ic_number', 'phone_number', 'emergency_phone'], 'string', 'max' => 20],
            [['race', 'marital_status'], 'string', 'max' => 50],
            [['citizenship', 'city', 'state', 'country', 'emergency_contact_relationship'], 'string', 'max' => 100],
            [['postcode'], 'string', 'max' => 10],
            
            // Text fields
            [['gender', 'address', 'blood_type'], 'string'],
            
            // Date fields
            [['date_of_birth', 'created_at', 'updated_at'], 'safe'],
            
            // Default value
            [['country'], 'default', 'value' => 'Malaysia'],
            
            // ✅ IC Number validation
            [['ic_number'], 'unique'],
            [['ic_number'], 'match', 'pattern' => '/^\d{12}$/', 'message' => 'IC number must be exactly 12 digits.'],
            
            // ✅ Phone number validation
            [['phone_number', 'emergency_phone'], 'match', 'pattern' => '/^(\+?6?01)[0-9]{8,9}$/', 'message' => 'Please enter a valid Malaysian phone number.'],
            
            // ✅ Age validation
            [['age'], 'integer', 'min' => 18, 'max' => 100],
            
            // Gender validation
            ['gender', 'in', 'range' => array_keys(self::optsGender())],
            
            // Blood type validation
            ['blood_type', 'in', 'range' => array_keys(self::optsBloodType())],
            
            // Foreign key validation
            [['user_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Users::class,
                'targetAttribute' => ['user_id' => 'user_id']
            ],

            // ✅ File validation
            [['imageFile'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['png', 'jpg', 'jpeg'],
                'mimeTypes' => ['image/jpeg', 'image/png'],
                'maxSize' => 2 * 1024 * 1024,
                'tooBig' => 'The file is too large. Maximum size is 2MB.',
                'checkExtensionByMimeType' => true,
            ],

            // ✅ Image validation
            [['imageFile'], 'image',
                'skipOnEmpty' => true,
                'minWidth' => 100,
                'minHeight' => 100,
                'maxWidth' => 1000,
                'maxHeight' => 1000,
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
            'date_of_birth' => 'Date of Birth',
            'gender' => 'Gender',
            'race' => 'Race',
            'phone_number' => 'Phone Number',
            'emergency_phone' => 'Emergency Phone',
            'emergency_contact_name' => 'Emergency Contact Name',
            'emergency_contact_relationship' => 'Emergency Contact Relationship',
            'citizenship' => 'Citizenship',
            'marital_status' => 'Marital Status',
            'occupation' => 'Occupation',
            'address' => 'Address',
            'city' => 'City',
            'postcode' => 'Postcode',
            'state' => 'State',
            'country' => 'Country',
            'profile_picture_url' => 'Profile Picture',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'blood_type' => 'Blood Type',
            'imageFile' => 'Upload Profile Picture',
        ];
    }

    /**
     * Relation to Users
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
    }

    /**
     * Relation to UserJobDetails
     */
    public function getUserJobDetails()
    {
        return $this->hasOne(UserJobDetails::class, ['user_id' => 'user_id']);
    }

    /**
     * Relation to PartnerDetails
     */
    public function getPartnerDetails()
    {
        return $this->hasOne(PartnerDetails::class, ['partner_id' => 'user_id']);
    }

    /**
     * Gender options
     */
    public static function optsGender()
    {
        return [
            self::GENDER_MALE => 'Male',
            self::GENDER_FEMALE => 'Female',
        ];
    }

    /**
     * Blood type options
     */
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

    /**
     * ✅ Auto timestamp handling
     */
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

    /**
     * ✅ Auto-extract age and gender from Malaysian IC
     * Format: YYMMDD-PB-###G (12 digits without dash)
     */
    public function beforeValidate()
    {
        if ($this->ic_number && strlen($this->ic_number) == 12) {
            // Extract birth year
            $year = substr($this->ic_number, 0, 2);
            $month = substr($this->ic_number, 2, 2);
            $day = substr($this->ic_number, 4, 2);
            
            $currentYear = date('Y');
            $currentCentury = floor($currentYear / 100) * 100;
            $previousCentury = $currentCentury - 100;
            
            // Determine century
            $birthYear = ($year > date('y')) ? $previousCentury + $year : $currentCentury + $year;
            
            // Set date_of_birth
            $this->date_of_birth = sprintf('%04d-%02d-%02d', $birthYear, $month, $day);
            
            // Calculate age
            $this->age = $currentYear - $birthYear;
            
            // Extract gender (last digit: odd = male, even = female)
            $lastDigit = (int) substr($this->ic_number, -1);
            $this->gender = ($lastDigit % 2 == 0) ? self::GENDER_FEMALE : self::GENDER_MALE;
        }
        
        return parent::beforeValidate();
    }

    public static function find()
    {
        return new UserDetailsQuery(get_called_class());
    }
}