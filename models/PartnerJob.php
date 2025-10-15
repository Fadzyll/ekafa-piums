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
            // ✅ Required fields
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

            // ✅ Salary must be numeric and non-negative
            [['partner_gross_salary', 'partner_net_salary'], 'number', 'min' => 0],

            // ✅ Length limits
            [['partner_job'], 'string', 'max' => 100],
            [['partner_employer'], 'string', 'max' => 255],
            [['partner_employer_phone_number'], 'string', 'max' => 20],

            // ✅ Unique and relational checks
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

    /**
     * Set default salary values on new records
     */
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->partner_gross_salary = $this->partner_gross_salary ?? 0.00;
            $this->partner_net_salary = $this->partner_net_salary ?? 0.00;
        }
        return parent::beforeValidate();
    }

    public static function find()
    {
        return new PartnerJobQuery(get_called_class());
    }
}