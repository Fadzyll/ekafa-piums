<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user_documents".
 *
 * @property int $document_id
 * @property int $user_id
 * @property string $document_type
 * @property string $file_url
 * @property string|null $status
 * @property string|null $upload_date
 *
 * @property Users $user
 */
class UserDocuments extends \yii\db\ActiveRecord
{
    /** @var UploadedFile|null */
    public $file; // used for file upload form input

    /**
     * ENUM field values
     */
    const STATUS_COMPLETED = 'Completed';
    const STATUS_INCOMPLETE = 'Incomplete';
    const STATUS_PENDING_REVIEW = 'Pending Review';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => self::STATUS_PENDING_REVIEW],
            [['user_id', 'document_type'], 'required'],
            [['user_id'], 'integer'],
            [['status'], 'string'],
            [['upload_date'], 'safe'],
            [['document_type'], 'string', 'max' => 100],
            [['file_url'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],
            
            // file upload rule
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, pdf', 'maxSize' => 5 * 1024 * 1024, 'tooBig' => 'File must be smaller than 5MB.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'document_id' => 'Document ID',
            'user_id' => 'User ID',
            'document_type' => 'Document Type',
            'file_url' => 'Uploaded File',
            'file' => 'Upload File',
            'status' => 'Status',
            'upload_date' => 'Upload Date',
        ];
    }

    /**
     * Relation: Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return UserDocumentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserDocumentsQuery(get_called_class());
    }

    /**
     * Returns list of ENUM status options
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_INCOMPLETE => 'Incomplete',
            self::STATUS_PENDING_REVIEW => 'Pending Review',
        ];
    }

    /**
     * Display readable status label
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status] ?? $this->status;
    }

    // === Helper methods for checking status ===
    public function isStatusCompleted() { return $this->status === self::STATUS_COMPLETED; }
    public function setStatusToCompleted() { $this->status = self::STATUS_COMPLETED; }

    public function isStatusIncomplete() { return $this->status === self::STATUS_INCOMPLETE; }
    public function setStatusToIncomplete() { $this->status = self::STATUS_INCOMPLETE; }

    public function isStatusPendingReview() { return $this->status === self::STATUS_PENDING_REVIEW; }
    public function setStatusToPendingReview() { $this->status = self::STATUS_PENDING_REVIEW; }

    /**
     * Handle file upload logic.
     * Call this before saving the model (in controller).
     */
    public function uploadFile()
    {
        if ($this->file instanceof UploadedFile) {
            $directory = Yii::getAlias('@webroot/uploads/documents/');
            if (!is_dir($directory)) {
                mkdir($directory, 0775, true);
            }

            $fileName = 'doc_' . $this->user_id . '_' . time() . '.' . $this->file->extension;
            $filePath = $directory . $fileName;

            if ($this->file->saveAs($filePath)) {
                $this->file_url = 'uploads/documents/' . $fileName;
                return true;
            }
        }
        return false;
    }
}