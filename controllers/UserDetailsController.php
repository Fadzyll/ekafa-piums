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
use app\models\PartnerDetails;
use app\models\PartnerJob; // ✅ Added if you have PartnerJob model

class UserDetailsController extends Controller
{
    /**
     * Access and HTTP verb control.
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // ✅ Only authenticated users
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
     * Create or update user profile (with image upload).
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
                // ✅ Handle profile image upload
                if ($model->imageFile) {
                    $uploadDir = Yii::getAlias('@webroot/uploads/');
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0775, true);
                    }

                    // Delete old image if it exists
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

                // ✅ Save profile
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Profile updated successfully.');

                    // If no job yet, go to job form first
                    if (!$model->userJob) {
                        return $this->redirect(['user-job/profile']);
                    }

                    // Otherwise go back to profile view
                    return $this->redirect(['view']);
                }
            }
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * Display the user's profile with job, partner, and partner job details.
     */
    public function actionView()
    {
        $userId = Yii::$app->user->id;

        // ✅ Preload all related models: userJob + partnerDetails + partnerJob (nested relation)
        $model = UserDetails::find()
            ->where(['user_id' => $userId])
            ->with([
                'userJob',
                'partnerDetails',
                'partnerDetails.partnerJob' // ✅ Include partner job relationship
            ])
            ->one();

        if (!$model) {
            Yii::$app->session->setFlash('warning', 'Profile not found. Please complete your profile.');
            return $this->redirect(['profile']);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Optional: Used if you need to fetch user details elsewhere.
     */
    protected function findModel($id)
    {
        if (($model = UserDetails::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested user profile does not exist.');
    }
}