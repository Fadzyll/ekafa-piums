<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "partner_job".
 *
 * @property int $partner_id
 * @property string $partner_job
 * @property string $partner_employer
 * @property string $partner_employer_address
 * @property string $partner_employer_phone_number
 * @property float $partner_gross_salary
 * @property float $partner_net_salary
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
            // ✅ REQUIRED FIELDS
            [
                [
                    'partner_id',
                    'partner_job',
                    'partner_employer',
                    'partner_employer_address',
                    'partner_employer_phone_number',
                    'partner_gross_salary',
                    'partner_net_salary'
                ],
                'required'
            ],

            [['partner_id'], 'integer'],
            [['partner_employer_address'], 'string'],

            // ✅ Salary validation
            [['partner_gross_salary', 'partner_net_salary'], 'number', 'min' => 0],
            
            // ✅ Net salary validation
            ['partner_net_salary', 'compare', 'compareAttribute' => 'partner_gross_salary', 'operator' => '<=', 'message' => 'Net salary cannot be greater than gross salary.'],

            [['partner_job'], 'string', 'max' => 100],
            [['partner_employer'], 'string', 'max' => 255],
            [['partner_employer_phone_number'], 'string', 'max' => 20],
            
            // ✅ Phone validation
            [['partner_employer_phone_number'], 'match', 'pattern' => '/^(\+?6?01)[0-9]{8,9}$/', 'message' => 'Please enter a valid Malaysian phone number.'],

            [['partner_id'], 'unique'],
            [
                ['partner_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PartnerDetails::class,
                'targetAttribute' => ['partner_id' => 'partner_id']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'partner_id' => 'Partner ID',
            'partner_job' => 'Occupation',
            'partner_employer' => 'Employer Name',
            'partner_employer_address' => 'Employer Address',
            'partner_employer_phone_number' => 'Employer Phone Number',
            'partner_gross_salary' => 'Gross Salary (RM)',
            'partner_net_salary' => 'Net Salary (RM)',
        ];
    }

    /**
     * Relation to PartnerDetails
     */
    public function getPartnerDetails()
    {
        return $this->hasOne(PartnerDetails::class, ['partner_id' => 'partner_id']);
    }

    public static function find()
    {
        return new PartnerJobQuery(get_called_class());
    }
}