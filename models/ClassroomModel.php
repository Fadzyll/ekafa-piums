<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "documents".
 *
 * @property int $document_id
 * @property int $user_id
 * @property int|null $uploaded_by
 * @property int|null $verified_by
 * @property string|null $verified_at
 * @property string $document_type
 * @property string $document_name
 * @property int|null $version
 * @property int|null $is_latest_version
 * @property string|null $original_filename
 * @property string $file_url
 * @property int|null $file_size
 * @property string|null $mime_type
 * @property string|null $file_hash
 * @property string|null $status
 * @property string|null $upload_date
 * @property string|null $updated_at
 * @property string|null $expiry_date
 * @property int|null $category_id
 * @property string|null $admin_notes
 * @property string|null $rejection_reason
 * @property string|null $owner_type
 * @property int $owner_id
 *
 * @property Users $user
 * @property Users $uploadedBy
 * @property Users $verifiedBy
 * @property DocumentCategory $category
 */
class UserDocuments extends \yii\db\ActiveRecord
{
    /** @var UploadedFile|null */
    public $file;

    const STATUS_PENDING_REVIEW = 'Pending Review';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';
    const STATUS_EXPIRED = 'Expired';
    const STATUS_REPLACED = 'Replaced';
    const STATUS_DELETED = 'Deleted';

    const OWNER_TYPE_USER = 'User';
    const OWNER_TYPE_STUDENT = 'Student';
    const OWNER_TYPE_TEACHER = 'Teacher';
    const OWNER_TYPE_PARTNER = 'Partner';

    public static function tableName()
    {
        return 'documents';
    }

    public function rules()
    {
        return [
            [['status'], 'default', 'value' => self::STATUS_PENDING_REVIEW],
            [['document_name'], 'default', 'value' => 'Untitled'],
            [['version'], 'default', 'value' => 1],
            [['is_latest_version'], 'default', 'value' => 1],
            [['owner_id'], 'default', 'value' => 0],

            [['user_id', 'document_type', 'file_url', 'owner_id'], 'required'],
            [['user_id', 'uploaded_by', 'verified_by', 'version', 'is_latest_version', 'file_size', 'category_id', 'owner_id'], 'integer'],
            [['verified_at', 'upload_date', 'updated_at', 'expiry_date'], 'safe'],
            [['admin_notes', 'rejection_reason'], 'string'],
            [['status', 'owner_type'], 'string'],
            
            [['document_type', 'mime_type'], 'string', 'max' => 100],
            [['document_name', 'original_filename', 'file_url'], 'string', 'max' => 255],
            [['file_hash'], 'string', 'max' => 64],
            
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            ['owner_type', 'in', 'range' => array_keys(self::optsOwnerType())],
            
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],
            [['uploaded_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['uploaded_by' => 'user_id']],
            [['verified_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['verified_by' => 'user_id']],
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
            'uploaded_by' => 'Uploaded By',
            'verified_by' => 'Verified By',
            'verified_at' => 'Verified At',
            'document_type' => 'Document Type',
            'document_name' => 'Document Name',
            'version' => 'Version',
            'is_latest_version' => 'Is Latest Version',
            'original_filename' => 'Original Filename',
            'file_url' => 'Uploaded File',
            'file' => 'Upload File',
            'file_size' => 'File Size (bytes)',
            'mime_type' => 'MIME Type',
            'file_hash' => 'File Hash',
            'status' => 'Status',
            'upload_date' => 'Upload Date',
            'updated_at' => 'Updated At',
            'expiry_date' => 'Expiry Date',
            'category_id' => 'Document Category',
            'admin_notes' => 'Admin Notes',
            'rejection_reason' => 'Rejection Reason',
            'owner_type' => 'Owner Type',
            'owner_id' => 'Owner ID',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
    }

    public function getUploadedBy()
    {
        return $this->hasOne(Users::class, ['user_id' => 'uploaded_by']);
    }

    public function getVerifiedBy()
    {
        return $this->hasOne(Users::class, ['user_id' => 'verified_by']);
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
            self::STATUS_PENDING_REVIEW => 'Pending Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_REPLACED => 'Replaced',
            self::STATUS_DELETED => 'Deleted',
        ];
    }

    public static function optsOwnerType()
    {
        return [
            self::OWNER_TYPE_USER => 'User',
            self::OWNER_TYPE_STUDENT => 'Student',
            self::OWNER_TYPE_TEACHER => 'Teacher',
            self::OWNER_TYPE_PARTNER => 'Partner',
        ];
    }

    public function displayStatus()
    {
        return self::optsStatus()[$this->status] ?? $this->status;
    }

    public function isStatusPendingReview() { return $this->status === self::STATUS_PENDING_REVIEW; }
    public function setStatusToPendingReview() { $this->status = self::STATUS_PENDING_REVIEW; }

    public function isStatusApproved() { return $this->status === self::STATUS_APPROVED; }
    public function setStatusToApproved() { $this->status = self::STATUS_APPROVED; }

    public function isStatusRejected() { return $this->status === self::STATUS_REJECTED; }
    public function setStatusToRejected() { $this->status = self::STATUS_REJECTED; }

    public function isStatusExpired() { return $this->status === self::STATUS_EXPIRED; }
    public function setStatusToExpired() { $this->status = self::STATUS_EXPIRED; }

    public function isStatusReplaced() { return $this->status === self::STATUS_REPLACED; }
    public function setStatusToReplaced() { $this->status = self::STATUS_REPLACED; }

    public function isStatusDeleted() { return $this->status === self::STATUS_DELETED; }
    public function setStatusToDeleted() { $this->status = self::STATUS_DELETED; }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->updated_at = date('Y-m-d H:i:s');
            if ($insert && $this->upload_date === null) {
                $this->upload_date = $this->updated_at;
            }
            return true;
        }
        return false;
    }

    public function uploadFile()
    {
        if ($this->file instanceof UploadedFile) {
            $directory = Yii::getAlias('@webroot/uploads/documents/');
            if (!is_dir($directory)) {
                mkdir($directory, 0775, true);
            }

            $this->original_filename = $this->file->name;
            $this->file_size = $this->file->size;
            $this->mime_type = $this->file->type;

            $fileName = 'doc_' . $this->user_id . '_' . ($this->category_id ?? 0) . '_' . time() . '.' . $this->file->extension;
            $filePath = $directory . $fileName;

            if ($this->file->saveAs($filePath)) {
                $this->file_url = 'uploads/documents/' . $fileName;
                $this->file_hash = hash_file('sha256', $filePath);
                return true;
            }
        }
        return false;
    }
}