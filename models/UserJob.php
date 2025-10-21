<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_jobs".
 *
 * @property int $userJob_id
 * @property int $user_id
 * @property string|null $job
 * @property string|null $employer
 * @property string|null $employer_address
 * @property string|null $employer_phone_number
 * @property float|null $gross_salary
 * @property float|null $net_salary
 *
 * @property UserDetails $userDetails
 */
class UserJob extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user_jobs';
    }

    public function rules()
    {
        return [
            // ✅ REQUIRED FIELDS
            [['user_id', 'job', 'employer', 'employer_address', 'employer_phone_number', 'gross_salary', 'net_salary'], 'required'],

            [['user_id'], 'integer'],
            [['employer_address'], 'string'],
            
            // ✅ Salary validation
            [['gross_salary', 'net_salary'], 'number', 'min' => 0],
            
            // ✅ Net salary cannot exceed gross salary
            ['net_salary', 'compare', 'compareAttribute' => 'gross_salary', 'operator' => '<=', 'message' => 'Net salary cannot be greater than gross salary.'],
            
            [['job'], 'string', 'max' => 100],
            [['employer'], 'string', 'max' => 255],
            [['employer_phone_number'], 'string', 'max' => 20],
            
            // ✅ Phone validation
            [['employer_phone_number'], 'match', 'pattern' => '/^(\+?6?01)[0-9]{8,9}$/', 'message' => 'Please enter a valid Malaysian phone number.'],

            // ✅ FIXED: Foreign key
            [['user_id'], 'exist', 'skipOnError' => true,
                'targetClass' => UserDetails::class,
                'targetAttribute' => ['user_id' => 'user_id']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'userJob_id' => 'Job ID',
            'user_id' => 'User ID',
            'job' => 'Job Title',
            'employer' => 'Employer Name',
            'employer_address' => 'Employer Address',
            'employer_phone_number' => 'Employer Phone Number',
            'gross_salary' => 'Gross Salary (RM)',
            'net_salary' => 'Net Salary (RM)',
        ];
    }

    /**
     * ✅ FIXED: Relation to UserDetails
     */
    public function getUserDetails()
    {
        return $this->hasOne(UserDetails::class, ['user_id' => 'user_id']);
    }

    public static function find()
    {
        return new UserJobQuery(get_called_class());
    }
}