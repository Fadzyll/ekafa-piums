<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\UserDetails;
use app\models\UserJob;

class UserDetailsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Only authenticated users
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
     * Create or update user profile (including profile picture).
     */
    public function actionProfile()
    {
        $userId = Yii::$app->user->id;
        $model = UserDetails::findOne(['user_id' => $userId]);

        if (!$model) {
            $model = new UserDetails();
            $model->user_id = $userId;
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate()) {
                // Handle profile image upload
                if ($model->imageFile) {
                    $uploadDir = Yii::getAlias('@webroot/uploads/');
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0775, true);
                    }

                    // Delete old profile image if exists
                    if (!empty($model->profile_picture_url)) {
                        $oldPath = Yii::getAlias('@webroot/' . ltrim($model->profile_picture_url, '/'));
                        if (file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }

                    // Save new image
                    $filename = 'user_' . $model->user_id . '_' . time() . '.' . $model->imageFile->extension;
                    $filePath = $uploadDir . $filename;

                    if ($model->imageFile->saveAs($filePath)) {
                        $model->profile_picture_url = 'uploads/' . $filename;
                    }
                }

                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Profile updated successfully.');

                    // Redirect user to job creation if no job found
                    if (!$model->userJob) {
                        return $this->redirect(['user-job/job']);
                    }

                    return $this->redirect(['view']);
                }
            }
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * Displays the user profile along with related job info.
     */
    public function actionView()
    {
        $userId = Yii::$app->user->id;

        // Load profile + related job using Yii relations
        $model = UserDetails::find()
            ->where(['user_id' => $userId])
            ->with('userJob') // <-- Preload relation
            ->one();

        if (!$model) {
            Yii::$app->session->setFlash('warning', 'Profile not found. Please complete your profile.');
            return $this->redirect(['profile']);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }
}