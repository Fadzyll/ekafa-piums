<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "partner_job".
 *
 * @property int $partner_id
 * @property string|null $partner_job
 * @property string|null $partner_employer
 * @property string|null $partner_employer_address
 * @property string|null $partner_employer_phone_number
 * @property float|null $partner_gross_salary
 * @property float|null $partner_net_salary
 *
 * @property PartnerDetails $partnerDetails
 */
class PartnerJob extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'partner_job';
    }

    public function rules()
    {
        return [
            [['partner_id'], 'required'],
            [['partner_id'], 'integer'],
            [['partner_employer_address'], 'string'],
            [['partner_gross_salary', 'partner_net_salary'], 'number'],
            [['partner_job'], 'string', 'max' => 100],
            [['partner_employer'], 'string', 'max' => 255],
            [['partner_employer_phone_number'], 'string', 'max' => 20],
            [['partner_id'], 'unique'],
            [['partner_id'], 'exist', 'skipOnError' => true,
                'targetClass' => PartnerDetails::class,
                'targetAttribute' => ['partner_id' => 'partner_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'partner_id' => 'Partner ID',
            'partner_job' => 'Partner Job',
            'partner_employer' => 'Partner Employer',
            'partner_employer_address' => 'Partner Employer Address',
            'partner_employer_phone_number' => 'Partner Employer Phone Number',
            'partner_gross_salary' => 'Partner Gross Salary',
            'partner_net_salary' => 'Partner Net Salary',
        ];
    }

    public function getPartnerDetails()
    {
        return $this->hasOne(PartnerDetails::class, ['partner_id' => 'partner_id']);
    }

    public static function find()
    {
        return new PartnerJobQuery(get_called_class());
    }
}