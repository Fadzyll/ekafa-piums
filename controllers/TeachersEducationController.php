<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\TeachersEducation;

class TeachersEducationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // Only allow teachers to access this controller
                            return Yii::$app->user->identity->role === 'Teacher';
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all education records for the logged-in teacher
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $educations = TeachersEducation::find()
            ->byUser($userId)
            ->latest()
            ->all();

        return $this->render('index', [
            'educations' => $educations,
        ]);
    }

    /**
     * View a single education record
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // Ensure the education record belongs to the logged-in user
        if ($model->user_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('The requested education record does not exist.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Create a new education record
     */
    public function actionCreate()
    {
        $model = new TeachersEducation();
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post())) {
            $model->certificateFile = UploadedFile::getInstance($model, 'certificateFile');
            $model->transcriptFile = UploadedFile::getInstance($model, 'transcriptFile');

            // ✅ Validate BEFORE processing
            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', 'Please correct the errors below.');
                return $this->render('create', ['model' => $model]);
            }

            // Handle file uploads
            $model->uploadCertificate();
            $model->uploadTranscript();

            // ✅ Save without re-validation
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Education record added successfully!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save education record.');
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Update an existing education record
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        // Ensure the education record belongs to the logged-in user
        if ($model->user_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('The requested education record does not exist.');
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->certificateFile = UploadedFile::getInstance($model, 'certificateFile');
            $model->transcriptFile = UploadedFile::getInstance($model, 'transcriptFile');

            // ✅ Validate BEFORE processing
            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', 'Please correct the errors below.');
                return $this->render('update', ['model' => $model]);
            }

            // Handle file uploads
            $model->uploadCertificate();
            $model->uploadTranscript();

            // ✅ Save without re-validation
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Education record updated successfully!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update education record.');
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Delete an education record
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Ensure the education record belongs to the logged-in user
        if ($model->user_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('The requested education record does not exist.');
        }

        // Delete associated files
        if (!empty($model->certificate_url)) {
            $certPath = Yii::getAlias('@webroot/' . ltrim($model->certificate_url, '/'));
            if (file_exists($certPath)) {
                @unlink($certPath);
            }
        }

        if (!empty($model->transcript_url)) {
            $transcriptPath = Yii::getAlias('@webroot/' . ltrim($model->transcript_url, '/'));
            if (file_exists($transcriptPath)) {
                @unlink($transcriptPath);
            }
        }

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Education record deleted successfully!');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete education record.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Find model by ID
     */
    protected function findModel($id)
    {
        if (($model = TeachersEducation::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested education record does not exist.');
    }
}