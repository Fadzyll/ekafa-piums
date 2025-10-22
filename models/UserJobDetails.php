<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_job_details".
 *
 * @property int $job_detail_id
 * @property int $user_id
 * @property string|null $job
 * @property string|null $job_title
 * @property string|null $department
 * @property string|null $employment_type
 * @property int|null $working_hours_per_week
 * @property string|null $employment_status
 * @property string|null $employer
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $employer_address
 * @property string|null $employment_letter_url
 * @property string|null $latest_payslip_url
 * @property string|null $employer_phone_number
 * @property float|null $gross_salary
 * @property float|null $net_salary
 * @property string|null $currency
 * @property string|null $tax_identification_number
 * @property string|null $epf_number
 * @property string|null $socso_number
 * @property int|null $is_verified
 * @property int|null $verified_by
 * @property string|null $verified_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property float|null $other_income
 * @property string|null $other_income_source
 *
 * @property UserDetails $userDetails
 * @property Users $verifiedBy
 */
class UserJobDetails extends \yii\db\ActiveRecord
{
    const EMPLOYMENT_TYPE_FULL_TIME = 'Full-Time';
    const EMPLOYMENT_TYPE_PART_TIME = 'Part-Time';
    const EMPLOYMENT_TYPE_CONTRACT = 'Contract';
    const EMPLOYMENT_TYPE_FREELANCE = 'Freelance';
    const EMPLOYMENT_TYPE_SELF_EMPLOYED = 'Self-Employed';
    const EMPLOYMENT_TYPE_UNEMPLOYED = 'Unemployed';

    const EMPLOYMENT_STATUS_ACTIVE = 'Active';
    const EMPLOYMENT_STATUS_RESIGNED = 'Resigned';
    const EMPLOYMENT_STATUS_TERMINATED = 'Terminated';
    const EMPLOYMENT_STATUS_RETIRED = 'Retired';

    public static function tableName()
    {
        return 'user_job_details';
    }

    public function rules()
    {
        return [
            // ✅ REQUIRED FIELDS
            [['user_id'], 'required'],

            [['user_id', 'working_hours_per_week', 'is_verified', 'verified_by'], 'integer'],
            [['start_date', 'end_date', 'verified_at', 'created_at', 'updated_at'], 'safe'],
            [['employer_address'], 'string'],
            
            // ✅ Salary validation
            [['gross_salary', 'net_salary', 'other_income'], 'number', 'min' => 0],
            
            // ✅ Net salary cannot exceed gross salary
            ['net_salary', 'compare', 'compareAttribute' => 'gross_salary', 'operator' => '<=', 'message' => 'Net salary cannot be greater than gross salary.'],
            
            [['job', 'job_title', 'department'], 'string', 'max' => 100],
            [['employer', 'employment_letter_url', 'latest_payslip_url', 'other_income_source'], 'string', 'max' => 255],
            [['employer_phone_number'], 'string', 'max' => 20],
            [['currency'], 'string', 'max' => 3],
            [['tax_identification_number', 'epf_number', 'socso_number'], 'string', 'max' => 50],
            [['employment_type', 'employment_status'], 'string'],
            
            // ✅ Phone validation
            [['employer_phone_number'], 'match', 'pattern' => '/^(\+?6?01)[0-9]{8,9}$/', 'message' => 'Please enter a valid Malaysian phone number.', 'skipOnEmpty' => true],

            // Default values
            [['currency'], 'default', 'value' => 'MYR'],
            [['employment_type'], 'default', 'value' => self::EMPLOYMENT_TYPE_FULL_TIME],
            [['employment_status'], 'default', 'value' => self::EMPLOYMENT_STATUS_ACTIVE],
            [['is_verified'], 'default', 'value' => 0],
            [['other_income'], 'default', 'value' => 0.00],
            [['working_hours_per_week'], 'default', 'value' => 40],

            // Enum validation
            ['employment_type', 'in', 'range' => array_keys(self::optsEmploymentType())],
            ['employment_status', 'in', 'range' => array_keys(self::optsEmploymentStatus())],

            // ✅ Foreign keys
            [['user_id'], 'exist', 'skipOnError' => true,
                'targetClass' => UserDetails::class,
                'targetAttribute' => ['user_id' => 'user_id']
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
            'job_detail_id' => 'Job Detail ID',
            'user_id' => 'User ID',
            'job' => 'Job/Occupation',
            'job_title' => 'Job Title',
            'department' => 'Department',
            'employment_type' => 'Employment Type',
            'working_hours_per_week' => 'Working Hours Per Week',
            'employment_status' => 'Employment Status',
            'employer' => 'Employer Name',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'employer_address' => 'Employer Address',
            'employment_letter_url' => 'Employment Letter',
            'latest_payslip_url' => 'Latest Payslip',
            'employer_phone_number' => 'Employer Phone Number',
            'gross_salary' => 'Gross Salary',
            'net_salary' => 'Net Salary',
            'currency' => 'Currency',
            'tax_identification_number' => 'Tax ID Number',
            'epf_number' => 'EPF Number',
            'socso_number' => 'SOCSO Number',
            'is_verified' => 'Is Verified',
            'verified_by' => 'Verified By',
            'verified_at' => 'Verified At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'other_income' => 'Other Income',
            'other_income_source' => 'Other Income Source',
        ];
    }

    /**
     * Relation to UserDetails
     */
    public function getUserDetails()
    {
        return $this->hasOne(UserDetails::class, ['user_id' => 'user_id']);
    }

    /**
     * Relation to Users (verified by)
     */
    public function getVerifiedBy()
    {
        return $this->hasOne(Users::class, ['user_id' => 'verified_by']);
    }

    public static function optsEmploymentType()
    {
        return [
            self::EMPLOYMENT_TYPE_FULL_TIME => 'Full-Time',
            self::EMPLOYMENT_TYPE_PART_TIME => 'Part-Time',
            self::EMPLOYMENT_TYPE_CONTRACT => 'Contract',
            self::EMPLOYMENT_TYPE_FREELANCE => 'Freelance',
            self::EMPLOYMENT_TYPE_SELF_EMPLOYED => 'Self-Employed',
            self::EMPLOYMENT_TYPE_UNEMPLOYED => 'Unemployed',
        ];
    }

    public static function optsEmploymentStatus()
    {
        return [
            self::EMPLOYMENT_STATUS_ACTIVE => 'Active',
            self::EMPLOYMENT_STATUS_RESIGNED => 'Resigned',
            self::EMPLOYMENT_STATUS_TERMINATED => 'Terminated',
            self::EMPLOYMENT_STATUS_RETIRED => 'Retired',
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
        return new UserJobDetailsQuery(get_called_class());
    }
}