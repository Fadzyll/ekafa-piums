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

/**
 * UserDocumentsController implements the CRUD actions for UserDocuments model.
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
     * Creates a new UserDocuments model with file upload support.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new UserDocuments();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');

            // Set upload date automatically
            $model->upload_date = date('Y-m-d H:i:s');

            if ($model->file && $model->uploadFile()) {
                $model->status = UserDocuments::STATUS_PENDING_REVIEW;
            }

            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Document uploaded successfully.');
                return $this->redirect(['view', 'document_id' => $model->document_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to upload document.');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionMyDocuments()
    {
        $userId = Yii::$app->user->id;
        $userRole = Yii::$app->user->identity->role;

        // Get all active categories for this user's role
        $categories = DocumentCategory::getActiveCategories($userRole);

        // Get user's uploaded documents
        $uploadedDocuments = UserDocuments::find()
            ->where(['user_id' => $userId])
            ->indexBy('category_id')
            ->all();

        if (Yii::$app->request->isPost) {
            $categoryId = Yii::$app->request->post('category_id');
            $file = UploadedFile::getInstanceByName('file');

            if ($categoryId && $file) {
                // Check if document already exists
                $document = UserDocuments::findOne(['user_id' => $userId, 'category_id' => $categoryId]);
                
                if (!$document) {
                    $document = new UserDocuments();
                    $document->user_id = $userId;
                    $document->category_id = $categoryId;
                }

                $document->file = $file;
                $document->upload_date = date('Y-m-d H:i:s');
                $document->status = UserDocuments::STATUS_PENDING_REVIEW;

                if ($document->uploadFile() && $document->save(false)) {
                    Yii::$app->session->setFlash('success', 'Document uploaded successfully!');
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to upload document.');
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
     * Updates an existing UserDocuments model with file re-upload support.
     * @param int $document_id Document ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($document_id)
    {
        $model = $this->findModel($document_id);
        $oldFileUrl = $model->file_url;

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');

            // If a new file is uploaded, replace the old one
            if ($model->file && $model->uploadFile()) {
                // Optionally delete the old file
                if ($oldFileUrl && file_exists(Yii::getAlias('@webroot/' . $oldFileUrl))) {
                    @unlink(Yii::getAlias('@webroot/' . $oldFileUrl));
                }
            } else {
                // Keep old file if no new upload
                $model->file_url = $oldFileUrl;
            }

            $model->upload_date = date('Y-m-d H:i:s');

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
     * Deletes an existing UserDocuments model and its file.
     * @param int $document_id Document ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($document_id)
    {
        $model = $this->findModel($document_id);

        // Delete associated file if it exists
        if ($model->file_url && file_exists(Yii::getAlias('@webroot/' . $model->file_url))) {
            @unlink(Yii::getAlias('@webroot/' . $model->file_url));
        }

        $model->delete();

        Yii::$app->session->setFlash('success', 'Document deleted successfully.');
        return $this->redirect(['index']);
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