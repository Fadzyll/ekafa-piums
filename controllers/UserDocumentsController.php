<?php

namespace app\controllers;

use app\models\UserDocuments;
use app\models\UserDocumentsSearch;
use app\models\DocumentCategory;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ✅ UPDATED: UserDocumentsController with support for new document table structure
 */
class UserDocumentsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all UserDocuments models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserDocumentsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserDocuments model.
     * @param int $document_id Document ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($document_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($document_id),
        ]);
    }

    /**
     * ✅ UPDATED: Creates a new UserDocuments model with new fields support
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new UserDocuments();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            
            // ✅ Set new fields
            $model->uploaded_by = Yii::$app->user->id;
            $model->upload_date = date('Y-m-d H:i:s');
            
            // ✅ Set default owner if not specified
            if (empty($model->owner_type)) {
                $model->owner_type = UserDocuments::OWNER_TYPE_USER;
            }
            if (empty($model->owner_id)) {
                $model->owner_id = $model->user_id;
            }
            
            // Handle file upload - check both 'file' and 'file_url' attributes
            $uploadedFile = UploadedFile::getInstance($model, 'file_url');
            if (!$uploadedFile) {
                $uploadedFile = UploadedFile::getInstance($model, 'file');
            }
            
            if ($uploadedFile) {
                $model->file = $uploadedFile;
            }

            // Upload file if provided
            if ($model->file && $model->uploadFile()) {
                // File uploaded successfully
                if (!$model->status) {
                    $model->status = UserDocuments::STATUS_PENDING_REVIEW;
                }
            }

            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Document uploaded successfully.');
                return $this->redirect(['view', 'document_id' => $model->document_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to upload document. Please check all required fields.');
            }
        } else {
            $model->loadDefaultValues();
            // Set default status
            if (!$model->status) {
                $model->status = UserDocuments::STATUS_PENDING_REVIEW;
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * ✅ UPDATED: User document upload page for logged-in users with new fields
     * @return string|\yii\web\Response
     */
    public function actionMyDocuments()
    {
        $userId = Yii::$app->user->id;
        $userRole = Yii::$app->user->identity->role;

        // Get all active categories for this user's role
        $categories = DocumentCategory::getActiveCategories($userRole);

        // ✅ UPDATED: Get user's uploaded documents indexed by category_id, only latest versions
        $uploadedDocuments = UserDocuments::find()
            ->where(['user_id' => $userId, 'is_latest_version' => 1])
            ->andWhere(['!=', 'status', UserDocuments::STATUS_DELETED])
            ->indexBy('category_id')
            ->all();

        if (Yii::$app->request->isPost) {
            $categoryId = Yii::$app->request->post('category_id');
            $file = UploadedFile::getInstanceByName('file');

            if ($categoryId && $file) {
                // Check if document already exists for this category
                $existingDoc = UserDocuments::findOne([
                    'user_id' => $userId, 
                    'category_id' => $categoryId,
                    'is_latest_version' => 1
                ]);
                
                if ($existingDoc) {
                    // ✅ Mark old version as replaced
                    $existingDoc->is_latest_version = 0;
                    $existingDoc->status = UserDocuments::STATUS_REPLACED;
                    $existingDoc->save(false);
                    
                    // Create new version
                    $document = new UserDocuments();
                    $document->user_id = $userId;
                    $document->category_id = $categoryId;
                    $document->version = $existingDoc->version + 1;
                } else {
                    // Create new document
                    $document = new UserDocuments();
                    $document->user_id = $userId;
                    $document->category_id = $categoryId;
                    $document->version = 1;
                }

                // ✅ Set new fields
                $document->uploaded_by = $userId;
                $document->owner_type = UserDocuments::OWNER_TYPE_USER;
                $document->owner_id = $userId;
                
                // Get category name for document_type
                $category = DocumentCategory::findOne($categoryId);
                if ($category) {
                    $document->document_type = $category->category_name;
                    $document->document_name = $category->category_name;
                }

                $document->file = $file;
                $document->upload_date = date('Y-m-d H:i:s');
                $document->status = UserDocuments::STATUS_PENDING_REVIEW;

                if ($document->uploadFile() && $document->save(false)) {
                    Yii::$app->session->setFlash('success', 'Document uploaded successfully!');
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to upload document. Please try again.');
                }

                return $this->refresh();
            }
        }

        return $this->render('my-documents', [
            'categories' => $categories,
            'uploadedDocuments' => $uploadedDocuments,
        ]);
    }

    /**
     * ✅ UPDATED: Updates an existing UserDocuments model with versioning support
     * @param int $document_id Document ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($document_id)
    {
        $model = $this->findModel($document_id);
        $oldFileUrl = $model->file_url;

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Handle file upload - check both 'file' and 'file_url' attributes
            $uploadedFile = UploadedFile::getInstance($model, 'file_url');
            if (!$uploadedFile) {
                $uploadedFile = UploadedFile::getInstance($model, 'file');
            }
            
            if ($uploadedFile) {
                $model->file = $uploadedFile;
                
                // If a new file is uploaded, replace the old one
                if ($model->uploadFile()) {
                    // Delete the old file
                    if ($oldFileUrl && file_exists(Yii::getAlias('@webroot/' . $oldFileUrl))) {
                        @unlink(Yii::getAlias('@webroot/' . $oldFileUrl));
                    }
                }
            } else {
                // Keep old file if no new upload
                $model->file_url = $oldFileUrl;
            }

            // ✅ Update timestamp
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Document updated successfully.');
                return $this->redirect(['view', 'document_id' => $model->document_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update document.');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * ✅ UPDATED: Soft delete - marks as deleted instead of physical deletion
     * @param int $document_id Document ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($document_id)
    {
        $model = $this->findModel($document_id);

        // ✅ Soft delete - mark as deleted
        $model->status = UserDocuments::STATUS_DELETED;
        $model->is_latest_version = 0;
        $model->updated_at = date('Y-m-d H:i:s');
        
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Document deleted successfully.');
        }

        return $this->redirect(['index']);
    }

    /**
     * ✅ NEW: Approve document (Admin only)
     */
    public function actionApprove($document_id)
    {
        $model = $this->findModel($document_id);
        
        // Check if user is admin
        if (Yii::$app->user->identity->role !== 'Admin') {
            throw new \yii\web\ForbiddenHttpException('Only admins can approve documents.');
        }

        $model->status = UserDocuments::STATUS_APPROVED;
        $model->verified_by = Yii::$app->user->id;
        $model->verified_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');

        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Document approved successfully.');
        }

        return $this->redirect(['view', 'document_id' => $model->document_id]);
    }

    /**
     * ✅ NEW: Reject document (Admin only)
     */
    public function actionReject($document_id)
    {
        $model = $this->findModel($document_id);
        
        // Check if user is admin
        if (Yii::$app->user->identity->role !== 'Admin') {
            throw new \yii\web\ForbiddenHttpException('Only admins can reject documents.');
        }

        if (Yii::$app->request->isPost) {
            $model->status = UserDocuments::STATUS_REJECTED;
            $model->verified_by = Yii::$app->user->id;
            $model->verified_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');
            $model->rejection_reason = Yii::$app->request->post('rejection_reason', '');

            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Document rejected.');
                return $this->redirect(['view', 'document_id' => $model->document_id]);
            }
        }

        return $this->render('reject', ['model' => $model]);
    }

    /**
     * Finds the UserDocuments model based on its primary key value.
     * @param int $document_id Document ID
     * @return UserDocuments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($document_id)
    {
        if (($model = UserDocuments::findOne(['document_id' => $document_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested document does not exist.');
    }
}