<?php

namespace app\models;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "class".
 *
 * @property int $class_id
 * @property string $class_name
 * @property string|null $description
 * @property string|null $objectives
 * @property string|null $prerequisites
 * @property string|null $grade_level
 * @property int|null $min_age
 * @property int|null $max_age
 * @property int $year
 * @property int|null $session_id
 * @property string $session_type
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $days_of_week
 * @property string|null $classroom_location
 * @property string|null $class_photo_url
 * @property string|null $building
 * @property int|null $floor
 * @property int $user_id
 * @property int|null $assistant_teacher_id
 * @property int $quota
 * @property int|null $minimum_enrollment
 * @property float|null $monthly_fee
 * @property float|null $registration_fee
 * @property int $current_enrollment
 * @property int|null $waiting_list_count
 * @property string|null $status
 * @property int|null $is_visible
 * @property string|null $admin_notes
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property string|null $enrollment_start_date
 * @property string|null $enrollment_end_date
 * @property string|null $class_start_date
 * @property string|null $class_end_date
 *
 * @property Users $user
 * @property Users $assistantTeacher
 * @property Users $createdBy
 */
class ClassroomModel extends \yii\db\ActiveRecord
{
    const SESSION_TYPE_MORNING = 'Morning';
    const SESSION_TYPE_EVENING = 'Evening';
    
    const STATUS_DRAFT = 'Draft';
    const STATUS_OPEN = 'Open';
    const STATUS_CLOSED = 'Closed';
    const STATUS_FULL = 'Full';
    const STATUS_IN_PROGRESS = 'In Progress';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_CANCELLED = 'Cancelled';

    const GRADE_PRE_SCHOOL = 'Pre-School';
    const GRADE_YEAR_1 = 'Year 1';
    const GRADE_YEAR_2 = 'Year 2';
    const GRADE_YEAR_3 = 'Year 3';
    const GRADE_YEAR_4 = 'Year 4';
    const GRADE_YEAR_5 = 'Year 5';
    const GRADE_YEAR_6 = 'Year 6';

    public static function tableName()
    {
        return 'class';
    }

    public function rules()
    {
        return [
            [['current_enrollment'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::STATUS_DRAFT],
            [['minimum_enrollment'], 'default', 'value' => 5],
            [['waiting_list_count'], 'default', 'value' => 0],
            [['is_visible'], 'default', 'value' => 1],

            [['class_name', 'year', 'session_type', 'user_id', 'quota'], 'required'],
            [['year', 'min_age', 'max_age', 'session_id', 'floor', 'user_id', 'assistant_teacher_id', 'quota', 'minimum_enrollment', 'current_enrollment', 'waiting_list_count', 'is_visible', 'created_by'], 'integer'],
            [['description', 'objectives', 'prerequisites', 'admin_notes'], 'string'],
            [['session_type', 'status', 'grade_level'], 'string'],
            [['start_time', 'end_time', 'created_at', 'updated_at', 'enrollment_start_date', 'enrollment_end_date', 'class_start_date', 'class_end_date'], 'safe'],
            [['monthly_fee', 'registration_fee'], 'number'],
            
            [['class_name', 'days_of_week', 'classroom_location'], 'string', 'max' => 100],
            [['class_photo_url'], 'string', 'max' => 255],
            [['building'], 'string', 'max' => 50],
            
            ['session_type', 'in', 'range' => array_keys(self::optsSessionType())],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            ['grade_level', 'in', 'range' => array_keys(self::optsGradeLevel())],
            
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],
            [['assistant_teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['assistant_teacher_id' => 'user_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['created_by' => 'user_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'class_id' => 'Class ID',
            'class_name' => 'Class Name',
            'description' => 'Description',
            'objectives' => 'Objectives',
            'prerequisites' => 'Prerequisites',
            'grade_level' => 'Grade Level',
            'min_age' => 'Minimum Age',
            'max_age' => 'Maximum Age',
            'year' => 'Year',
            'session_id' => 'Session ID',
            'session_type' => 'Session Type',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'days_of_week' => 'Days of Week',
            'classroom_location' => 'Classroom Location',
            'class_photo_url' => 'Class Photo',
            'building' => 'Building',
            'floor' => 'Floor',
            'user_id' => 'Teacher',
            'assistant_teacher_id' => 'Assistant Teacher',
            'quota' => 'Quota',
            'minimum_enrollment' => 'Minimum Enrollment',
            'monthly_fee' => 'Monthly Fee (RM)',
            'registration_fee' => 'Registration Fee (RM)',
            'current_enrollment' => 'Current Enrollment',
            'waiting_list_count' => 'Waiting List Count',
            'status' => 'Status',
            'is_visible' => 'Is Visible',
            'admin_notes' => 'Admin Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'enrollment_start_date' => 'Enrollment Start Date',
            'enrollment_end_date' => 'Enrollment End Date',
            'class_start_date' => 'Class Start Date',
            'class_end_date' => 'Class End Date',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
    }

    public function getAssistantTeacher()
    {
        return $this->hasOne(Users::class, ['user_id' => 'assistant_teacher_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(Users::class, ['user_id' => 'created_by']);
    }

    public static function find()
    {
        return new ClassQuery(get_called_class());
    }

    public static function optsSessionType()
    {
        return [
            self::SESSION_TYPE_MORNING => 'Morning',
            self::SESSION_TYPE_EVENING => 'Evening',
        ];
    }

    public static function optsStatus()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_OPEN => 'Open',
            self::STATUS_CLOSED => 'Closed',
            self::STATUS_FULL => 'Full',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public static function optsGradeLevel()
    {
        return [
            self::GRADE_PRE_SCHOOL => 'Pre-School',
            self::GRADE_YEAR_1 => 'Year 1',
            self::GRADE_YEAR_2 => 'Year 2',
            self::GRADE_YEAR_3 => 'Year 3',
            self::GRADE_YEAR_4 => 'Year 4',
            self::GRADE_YEAR_5 => 'Year 5',
            self::GRADE_YEAR_6 => 'Year 6',
        ];
    }

    public function displaySessionType()
    {
        return self::optsSessionType()[$this->session_type];
    }

    public function displayStatus()
    {
        return self::optsStatus()[$this->status] ?? $this->status;
    }

    public function isSessionTypeMorning()
    {
        return $this->session_type === self::SESSION_TYPE_MORNING;
    }

    public function setSessionTypeToMorning()
    {
        $this->session_type = self::SESSION_TYPE_MORNING;
    }

    public function isSessionTypeEvening()
    {
        return $this->session_type === self::SESSION_TYPE_EVENING;
    }

    public function setSessionTypeToEvening()
    {
        $this->session_type = self::SESSION_TYPE_EVENING;
    }

    public function isStatusDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function setStatusToDraft()
    {
        $this->status = self::STATUS_DRAFT;
    }

    public function isStatusOpen()
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function setStatusToOpen()
    {
        $this->status = self::STATUS_OPEN;
    }

    public function isStatusClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function setStatusToClosed()
    {
        $this->status = self::STATUS_CLOSED;
    }

    public function isStatusFull()
    {
        return $this->status === self::STATUS_FULL;
    }

    public function setStatusToFull()
    {
        $this->status = self::STATUS_FULL;
    }

    public function isStatusInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function setStatusToInProgress()
    {
        $this->status = self::STATUS_IN_PROGRESS;
    }

    public function isStatusCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function setStatusToCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
    }

    public function isStatusCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function setStatusToCancelled()
    {
        $this->status = self::STATUS_CANCELLED;
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
}