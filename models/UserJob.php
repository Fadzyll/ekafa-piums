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
            // Make these fields required:
            [['user_id', 'job', 'employer', 'employer_address', 'employer_phone_number', 'gross_salary', 'net_salary'], 'required'],

            [['user_id'], 'integer'],
            [['employer_address'], 'string'],
            [['gross_salary', 'net_salary'], 'number'],
            [['job'], 'string', 'max' => 100],
            [['employer'], 'string', 'max' => 255],
            [['employer_phone_number'], 'string', 'max' => 20],

            [['user_id'], 'exist', 'skipOnError' => true,
                'targetClass' => UserDetails::class,
                'targetAttribute' => ['user_id' => 'user_details_id']
            ],
        ];
    }


    public function attributeLabels()
    {
        return [
            'userJob_id' => 'User Job ID',
            'user_id' => 'User ID',
            'job' => 'Job',
            'employer' => 'Employer',
            'employer_address' => 'Employer Address',
            'employer_phone_number' => 'Employer Phone Number',
            'gross_salary' => 'Gross Salary',
            'net_salary' => 'Net Salary',
        ];
    }

        public function getUserDetails()
    {
        return $this->hasOne(\app\models\UserDetails::class, ['user_id' => 'user_id']);
    }


    public static function find()
    {
        return new UserJobQuery(get_called_class());
    }
}
