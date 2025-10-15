<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

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
            [['partner_id', 'partner_name', 'partner_ic_number'], 'required'],
            [['partner_id'], 'integer'],
            [['partner_address'], 'string'],
            [['partner_name'], 'string', 'max' => 255],
            [['partner_ic_number', 'partner_phone_number'], 'string', 'max' => 20],
            [['partner_citizenship', 'partner_city', 'partner_state'], 'string', 'max' => 100],
            [['partner_marital_status'], 'string', 'max' => 50],
            [['partner_postcode'], 'string', 'max' => 10],
            [['partner_id'], 'unique'],
            [['profile_picture_url'], 'string', 'max' => 255],

            // ✅ Image file validation
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],

            // ✅ Fix relation validation — use user_id (not user_details_id)
            [
                ['partner_id'], 'exist',
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
     * Relation to PartnerJob table
     */
    public function getPartnerJob()
    {
        return $this->hasOne(PartnerJob::class, ['partner_id' => 'partner_id']);
    }

    /**
     * Relation back to UserDetails
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