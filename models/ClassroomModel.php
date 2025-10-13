<?php

namespace app\models;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "class".
 *
 * @property int $class_id
 * @property string $class_name
 * @property int $year
 * @property string $session_type
 * @property int $user_id
 * @property int $quota
 * @property int $current_enrollment
 * @property string $status
 *
 * @property Users $user
 */
class ClassroomModel extends \yii\db\ActiveRecord
{
    const SESSION_TYPE_MORNING = 'Morning';
    const SESSION_TYPE_EVENING = 'Evening';
    const STATUS_OPEN = 'Open';
    const STATUS_CLOSED = 'Closed';
    const STATUS_FULL = 'Full';

    public static function tableName()
    {
        return 'class';
    }

    public function rules()
    {
        return [
            [['current_enrollment'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 'Open'],
            [['class_name', 'year', 'session_type', 'user_id', 'quota'], 'required'],
            [['year', 'user_id', 'quota', 'current_enrollment'], 'integer'],
            [['session_type', 'status'], 'string'],
            [['class_name'], 'string', 'max' => 100],
            ['session_type', 'in', 'range' => array_keys(self::optsSessionType())],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'class_id' => 'Class ID',
            'class_name' => 'Class Name',
            'year' => 'Year',
            'session_type' => 'Session Type',
            'user_id' => 'Teacher',
            'quota' => 'Quota',
            'current_enrollment' => 'Current Enrollment',
            'status' => 'Status',
        ];
    }

    // ✅ FIX: Rename relation to getUser() (not getUsers())
    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
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
            self::STATUS_OPEN => 'Open',
            self::STATUS_CLOSED => 'Closed',
            self::STATUS_FULL => 'Full',
        ];
    }

    public function displaySessionType()
    {
        return self::optsSessionType()[$this->session_type];
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

    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
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
}