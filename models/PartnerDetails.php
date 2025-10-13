<?php

namespace app\models;

use Yii;

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
 *
 * @property PartnerJob $partnerJob
 * @property UserDetails $userDetails
 */
class PartnerDetails extends \yii\db\ActiveRecord
{
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
            [['partner_id'], 'exist', 'skipOnError' => true,
                'targetClass' => UserDetails::class,
                'targetAttribute' => ['partner_id' => 'user_details_id']],
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
        ];
    }

    public function getPartnerJob()
    {
        return $this->hasOne(PartnerJob::class, ['partner_id' => 'partner_id']);
    }

    public function getUserDetails()
    {
        return $this->hasOne(UserDetails::class, ['user_details_id' => 'partner_id']);
    }

    public static function find()
    {
        return new PartnerDetailsQuery(get_called_class());
    }
}