<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * @property int $document_id
 * @property int $user_id
 * @property int $category_id (NEW - foreign key to document_categories)
 * @property string $document_type
 * @property string $file_url
 * @property string|null $status
 * @property string|null $upload_date
 * @property string|null $admin_notes
 *
 * @property Users $user
 * @property DocumentCategory $category (NEW)
 */
class UserDocuments extends \yii\db\ActiveRecord
{
    /** @var UploadedFile|null */
    public $file;

    const STATUS_COMPLETED = 'Completed';
    const STATUS_INCOMPLETE = 'Incomplete';
    const STATUS_PENDING_REVIEW = 'Pending Review';
    const STATUS_REJECTED = 'Rejected';

    public static function tableName()
    {
        return 'user_documents';
    }

    public function rules()
    {
        return [
            [['status'], 'default', 'value' => self::STATUS_PENDING_REVIEW],
            [['user_id', 'category_id'], 'required'],
            [['user_id', 'category_id'], 'integer'],
            [['status'], 'string'],
            [['upload_date'], 'safe'],
            [['document_type'], 'string', 'max' => 100],
            [['file_url'], 'string', 'max' => 255],
            [['admin_notes'], 'string'],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentCategory::class, 'targetAttribute' => ['category_id' => 'category_id']],
            
            // file upload rule
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, pdf', 'maxSize' => 5 * 1024 * 1024, 'tooBig' => 'File must be smaller than 5MB.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'document_id' => 'Document ID',
            'user_id' => 'User ID',
            'category_id' => 'Document Category',
            'document_type' => 'Document Type',
            'file_url' => 'Uploaded File',
            'file' => 'Upload File',
            'status' => 'Status',
            'upload_date' => 'Upload Date',
            'admin_notes' => 'Admin Notes',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(DocumentCategory::class, ['category_id' => 'category_id']);
    }

    public static function find()
    {
        return new UserDocumentsQuery(get_called_class());
    }

    public static function optsStatus()
    {
        return [
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_INCOMPLETE => 'Incomplete',
            self::STATUS_PENDING_REVIEW => 'Pending Review',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public function displayStatus()
    {
        return self::optsStatus()[$this->status] ?? $this->status;
    }

    public function isStatusCompleted() { return $this->status === self::STATUS_COMPLETED; }
    public function setStatusToCompleted() { $this->status = self::STATUS_COMPLETED; }

    public function isStatusIncomplete() { return $this->status === self::STATUS_INCOMPLETE; }
    public function setStatusToIncomplete() { $this->status = self::STATUS_INCOMPLETE; }

    public function isStatusPendingReview() { return $this->status === self::STATUS_PENDING_REVIEW; }
    public function setStatusToPendingReview() { $this->status = self::STATUS_PENDING_REVIEW; }

    public function isStatusRejected() { return $this->status === self::STATUS_REJECTED; }
    public function setStatusToRejected() { $this->status = self::STATUS_REJECTED; }

    public function uploadFile()
    {
        if ($this->file instanceof UploadedFile) {
            $directory = Yii::getAlias('@webroot/uploads/documents/');
            if (!is_dir($directory)) {
                mkdir($directory, 0775, true);
            }

            $fileName = 'doc_' . $this->user_id . '_' . $this->category_id . '_' . time() . '.' . $this->file->extension;
            $filePath = $directory . $fileName;

            if ($this->file->saveAs($filePath)) {
                $this->file_url = 'uploads/documents/' . $fileName;
                return true;
            }
        }
        return false;
    }
}