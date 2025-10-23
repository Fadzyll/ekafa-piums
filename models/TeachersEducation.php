<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "teachers_education".
 *
 * @property int $education_id
 * @property int $user_id
 * @property string|null $institution_name
 * @property string|null $degree_level
 * @property string|null $field_of_study
 * @property string|null $graduation_date
 * @property string|null $certificate_url
 * @property string|null $transcript_url
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Users $user
 * @property UploadedFile $certificateFile
 * @property UploadedFile $transcriptFile
 */
class TeachersEducation extends \yii\db\ActiveRecord
{
    /** @var UploadedFile */
    public $certificateFile;
    
    /** @var UploadedFile */
    public $transcriptFile;

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
            // ✅ REQUIRED FIELDS
            [['user_id', 'institution_name', 'degree_level', 'field_of_study'], 'required'],
            
            // Integer fields
            [['user_id'], 'integer'],
            
            // String fields with length limits
            [['institution_name', 'field_of_study', 'certificate_url', 'transcript_url'], 'string', 'max' => 255],
            
            // Enum fields
            [['degree_level'], 'string'],
            [['degree_level'], 'in', 'range' => array_keys(self::optsDegreeLevel())],
            
            // Date fields
            [['graduation_date', 'created_at', 'updated_at'], 'safe'],
            
            // Foreign key validation
            [['user_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Users::class,
                'targetAttribute' => ['user_id' => 'user_id']
            ],

            // ✅ Certificate file validation
            [['certificateFile'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['pdf', 'jpg', 'jpeg', 'png'],
                'mimeTypes' => ['application/pdf', 'image/jpeg', 'image/png'],
                'maxSize' => 5 * 1024 * 1024,
                'tooBig' => 'The certificate file is too large. Maximum size is 5MB.',
                'checkExtensionByMimeType' => true,
            ],

            // ✅ Transcript file validation
            [['transcriptFile'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['pdf', 'jpg', 'jpeg', 'png'],
                'mimeTypes' => ['application/pdf', 'image/jpeg', 'image/png'],
                'maxSize' => 5 * 1024 * 1024,
                'tooBig' => 'The transcript file is too large. Maximum size is 5MB.',
                'checkExtensionByMimeType' => true,
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
            'certificate_url' => 'Certificate URL',
            'transcript_url' => 'Transcript URL',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'certificateFile' => 'Upload Certificate',
            'transcriptFile' => 'Upload Transcript',
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

    /**
     * ✅ Auto timestamp handling
     */
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

    /**
     * Handle file uploads
     */
    public function uploadCertificate()
    {
        if ($this->certificateFile) {
            $uploadDir = Yii::getAlias('@webroot/uploads/certificates/');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            // Delete old file
            if (!empty($this->certificate_url)) {
                $oldPath = Yii::getAlias('@webroot/' . ltrim($this->certificate_url, '/'));
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            // Save new file
            $filename = 'cert_' . $this->user_id . '_' . time() . '.' . $this->certificateFile->extension;
            $filePath = $uploadDir . $filename;

            if ($this->certificateFile->saveAs($filePath)) {
                $this->certificate_url = 'uploads/certificates/' . $filename;
                return true;
            }
        }
        return false;
    }

    /**
     * Handle transcript uploads
     */
    public function uploadTranscript()
    {
        if ($this->transcriptFile) {
            $uploadDir = Yii::getAlias('@webroot/uploads/transcripts/');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            // Delete old file
            if (!empty($this->transcript_url)) {
                $oldPath = Yii::getAlias('@webroot/' . ltrim($this->transcript_url, '/'));
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            // Save new file
            $filename = 'transcript_' . $this->user_id . '_' . time() . '.' . $this->transcriptFile->extension;
            $filePath = $uploadDir . $filename;

            if ($this->transcriptFile->saveAs($filePath)) {
                $this->transcript_url = 'uploads/transcripts/' . $filename;
                return true;
            }
        }
        return false;
    }

    public static function find()
    {
        return new TeachersEducationQuery(get_called_class());
    }
}