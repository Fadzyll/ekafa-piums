<?php

namespace app\models;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "class".
 * Updated to match E-Kafa Database Data Dictionary v1.0 - Table #4
 *
 * @property int $class_id
 * @property int $user_id
 * @property string $class_name
 * @property string $session_type
 * @property int $year
 * @property int $quota
 * @property int $current_enrollment
 * @property string|null $status
 * @property string|null $grade_level
 * @property string|null $classroom_location
 * @property string|null $class_start_date
 * @property string|null $class_end_date
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Users $user
 */
class ClassroomModel extends \yii\db\ActiveRecord
{
    // Session Type Constants
    const SESSION_TYPE_MORNING = 'Morning';
    const SESSION_TYPE_EVENING = 'Evening';
    
    // Status Constants
    const STATUS_DRAFT = 'Draft';
    const STATUS_OPEN = 'Open';
    const STATUS_CLOSED = 'Closed';
    const STATUS_FULL = 'Full';
    const STATUS_IN_PROGRESS = 'In Progress';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_CANCELLED = 'Cancelled';

    // Grade Level Constants
    const GRADE_YEAR_1 = 'Year 1';
    const GRADE_YEAR_2 = 'Year 2';
    const GRADE_YEAR_3 = 'Year 3';
    const GRADE_YEAR_4 = 'Year 4';
    const GRADE_YEAR_5 = 'Year 5';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'class';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Required fields
            [['class_name', 'session_type', 'user_id', 'quota', 'year'], 'required'],
            
            // Integer fields
            [['user_id', 'quota', 'current_enrollment', 'year'], 'integer'],
            
            // Default values
            [['current_enrollment'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::STATUS_DRAFT],
            [['year'], 'default', 'value' => date('Y')],
            
            // String fields with length limits
            [['class_name', 'classroom_location'], 'string', 'max' => 100],
            
            // Date fields
            [['class_start_date', 'class_end_date', 'created_at', 'updated_at'], 'safe'],
            
            // ENUM validations
            ['session_type', 'in', 'range' => array_keys(self::optsSessionType())],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            ['grade_level', 'in', 'range' => array_keys(self::optsGradeLevel())],
            
            // Foreign key validation
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],
            
            // Business logic validations
            ['quota', 'integer', 'min' => 1, 'message' => 'Quota must be at least 1'],
            ['current_enrollment', 'integer', 'min' => 0],
            ['current_enrollment', 'compare', 'compareAttribute' => 'quota', 'operator' => '<=', 'message' => 'Current enrollment cannot exceed quota'],
            ['year', 'integer', 'min' => 2020, 'max' => 2050],
            
            // Date range validation - using validateDateRange method instead of closure
            ['class_end_date', 'validateDateRange'],
        ];
    }
    
    /**
     * Custom validator for date range
     */
    public function validateDateRange($attribute, $params)
    {
        if (!empty($this->class_start_date) && !empty($this->class_end_date)) {
            if ($this->class_end_date < $this->class_start_date) {
                $this->addError($attribute, 'End date must be after start date');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'class_id' => 'Class ID',
            'user_id' => 'Teacher',
            'class_name' => 'Class Name',
            'session_type' => 'Session Type',
            'year' => 'Year',
            'quota' => 'Maximum Enrollment',
            'current_enrollment' => 'Current Enrollment',
            'status' => 'Status',
            'grade_level' => 'Grade Level',
            'classroom_location' => 'Classroom Location',
            'class_start_date' => 'Class Start Date',
            'class_end_date' => 'Class End Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[Teacher]].
     * Alias for getUser()
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->getUser();
    }

    /**
     * {@inheritdoc}
     * @return ClassQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClassQuery(get_called_class());
    }

    /**
     * Session Type options
     * @return array
     */
    public static function optsSessionType()
    {
        return [
            self::SESSION_TYPE_MORNING => 'Morning',
            self::SESSION_TYPE_EVENING => 'Evening',
        ];
    }

    /**
     * Status options
     * @return array
     */
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

    /**
     * Grade Level options
     * @return array
     */
    public static function optsGradeLevel()
    {
        return [
            self::GRADE_YEAR_1 => 'Year 1',
            self::GRADE_YEAR_2 => 'Year 2',
            self::GRADE_YEAR_3 => 'Year 3',
            self::GRADE_YEAR_4 => 'Year 4',
            self::GRADE_YEAR_5 => 'Year 5',
        ];
    }

    /**
     * Display methods for ENUMs
     */
    public function displaySessionType()
    {
        return self::optsSessionType()[$this->session_type] ?? $this->session_type;
    }

    public function displayStatus()
    {
        return self::optsStatus()[$this->status] ?? $this->status;
    }

    public function displayGradeLevel()
    {
        return self::optsGradeLevel()[$this->grade_level] ?? $this->grade_level;
    }

    /**
     * Session Type checker methods
     */
    public function isSessionTypeMorning()
    {
        return $this->session_type === self::SESSION_TYPE_MORNING;
    }

    public function isSessionTypeEvening()
    {
        return $this->session_type === self::SESSION_TYPE_EVENING;
    }

    /**
     * Status checker methods
     */
    public function isStatusDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isStatusOpen()
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isStatusClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function isStatusFull()
    {
        return $this->status === self::STATUS_FULL;
    }

    public function isStatusInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isStatusCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isStatusCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Status setter methods
     */
    public function setStatusToDraft()
    {
        $this->status = self::STATUS_DRAFT;
    }

    public function setStatusToOpen()
    {
        $this->status = self::STATUS_OPEN;
    }

    public function setStatusToClosed()
    {
        $this->status = self::STATUS_CLOSED;
    }

    public function setStatusToFull()
    {
        $this->status = self::STATUS_FULL;
    }

    public function setStatusToInProgress()
    {
        $this->status = self::STATUS_IN_PROGRESS;
    }

    public function setStatusToCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
    }

    public function setStatusToCancelled()
    {
        $this->status = self::STATUS_CANCELLED;
    }

    /**
     * Business logic methods
     */
    
    /**
     * Get available slots
     * @return int
     */
    public function getAvailableSlots()
    {
        return max(0, $this->quota - $this->current_enrollment);
    }

    /**
     * Get enrollment percentage
     * @return float
     */
    public function getEnrollmentPercentage()
    {
        if ($this->quota <= 0) {
            return 0;
        }
        return round(($this->current_enrollment / $this->quota) * 100, 2);
    }

    /**
     * Check if class is full
     * @return bool
     */
    public function isFull()
    {
        return $this->current_enrollment >= $this->quota;
    }

    /**
     * Check if class has available slots
     * @return bool
     */
    public function hasAvailableSlots()
    {
        return $this->current_enrollment < $this->quota;
    }

    /**
     * Increment enrollment
     * @return bool
     */
    public function incrementEnrollment()
    {
        if ($this->isFull()) {
            return false;
        }
        
        $this->current_enrollment++;
        
        // Auto-update status to Full if quota reached
        if ($this->isFull() && $this->status !== self::STATUS_FULL) {
            $this->status = self::STATUS_FULL;
        }
        
        return $this->save(false);
    }

    /**
     * Decrement enrollment
     * @return bool
     */
    public function decrementEnrollment()
    {
        if ($this->current_enrollment <= 0) {
            return false;
        }
        
        $wasFull = $this->isFull();
        $this->current_enrollment--;
        
        // Auto-update status if was full and now has space
        if ($wasFull && $this->status === self::STATUS_FULL) {
            $this->status = self::STATUS_OPEN;
        }
        
        return $this->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Set updated_at timestamp
            $this->updated_at = date('Y-m-d H:i:s');
            
            // Set created_at on insert
            if ($insert && $this->created_at === null) {
                $this->created_at = $this->updated_at;
            }
            
            // Auto-set status to Full if enrollment reaches quota
            if ($this->current_enrollment >= $this->quota && $this->status !== self::STATUS_FULL) {
                $this->status = self::STATUS_FULL;
            }
            
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        
        // You can add any post-processing logic here
    }
}