<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_categories".
 *
 * @property int $category_id
 * @property string $category_name
 * @property string $description
 * @property string $required_for_role (Teacher, Parent, Both)
 * @property int $is_required (0 or 1)
 * @property string $status (Active, Inactive)
 * @property string $created_at
 * @property string $updated_at
 *
 * @property UserDocuments[] $userDocuments
 */
class DocumentCategory extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';
    
    const ROLE_TEACHER = 'Teacher';
    const ROLE_PARENT = 'Parent';
    const ROLE_BOTH = 'Both';

    public static function tableName()
    {
        return 'document_categories';
    }

    public function rules()
    {
        return [
            [['category_name', 'required_for_role'], 'required'],
            [['description'], 'string'],
            [['is_required'], 'integer'],
            [['is_required'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['created_at', 'updated_at'], 'safe'],
            [['category_name'], 'string', 'max' => 100],
            [['category_name'], 'unique'],
            [['required_for_role'], 'in', 'range' => [self::ROLE_TEACHER, self::ROLE_PARENT, self::ROLE_BOTH]],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'category_name' => 'Document Category Name',
            'description' => 'Description',
            'required_for_role' => 'Required For Role',
            'is_required' => 'Is Mandatory?',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUserDocuments()
    {
        return $this->hasMany(UserDocuments::class, ['category_id' => 'category_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }

    public static function getActiveCategories($role = null)
    {
        $query = static::find()->where(['status' => self::STATUS_ACTIVE]);
        
        if ($role) {
            $query->andWhere([
                'or',
                ['required_for_role' => $role],
                ['required_for_role' => self::ROLE_BOTH]
            ]);
        }
        
        return $query->all();
    }
}