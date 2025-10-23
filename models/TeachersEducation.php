<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teachers_education".
 *
 * @property int $education_id
 * @property int $user_id
 * @property string|null $institution_name
 * @property string|null $degree_level
 * @property string|null $field_of_study
 * @property string|null $graduation_date
 *
 * @property Users $user
 */
class TeachersEducation extends \yii\db\ActiveRecord
{
    const DEGREE_BACHELOR = 'Bachelor';
    const DEGREE_MASTER = 'Master';
    const DEGREE_PHD = 'PhD';
    const DEGREE_DIPLOMA = 'Diploma';
    const DEGREE_CERTIFICATE = 'Certificate';

    public static function tableName()
    {
        return 'teachers_education';
    }

    public function rules()
    {
        return [
            // Required fields
            [['user_id', 'institution_name', 'degree_level', 'field_of_study'], 'required'],
            
            // Integer fields
            [['user_id'], 'integer'],
            
            // String fields with length limits
            [['institution_name', 'field_of_study'], 'string', 'max' => 255],
            
            // Enum fields
            [['degree_level'], 'string'],
            [['degree_level'], 'in', 'range' => array_keys(self::optsDegreeLevel())],
            
            // Date fields
            [['graduation_date'], 'safe'],
            
            // Foreign key validation
            [['user_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Users::class,
                'targetAttribute' => ['user_id' => 'user_id']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'education_id' => 'Education ID',
            'user_id' => 'User ID',
            'institution_name' => 'Institution Name',
            'degree_level' => 'Degree Level',
            'field_of_study' => 'Field of Study',
            'graduation_date' => 'Graduation Date',
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
     * Degree level options
     */
    public static function optsDegreeLevel()
    {
        return [
            self::DEGREE_CERTIFICATE => 'Certificate',
            self::DEGREE_DIPLOMA => 'Diploma',
            self::DEGREE_BACHELOR => 'Bachelor',
            self::DEGREE_MASTER => 'Master',
            self::DEGREE_PHD => 'PhD',
        ];
    }

    /**
     * Display degree level
     */
    public function displayDegreeLevel()
    {
        return self::optsDegreeLevel()[$this->degree_level] ?? '-';
    }

    public static function find()
    {
        return new TeachersEducationQuery(get_called_class());
    }
}