<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\UserDetails;

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
        ];
    }

    /**
     * ✅ UPDATED: Profile edit with validation
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

            // ✅ Validate BEFORE processing
            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', 'Please correct the errors below.');
                return $this->render('profile', ['model' => $model]);
            }

            // Handle image upload
            if ($model->imageFile) {
                $uploadDir = Yii::getAlias('@webroot/uploads/');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }

                // Delete old image
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

            // ✅ Save without re-validation
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Profile updated successfully!');

                // ✅ UPDATED: Check userJobDetails instead of userJob
                if (!$model->userJobDetails) {
                    return $this->redirect(['user-job/profile']);
                }

                return $this->redirect(['view']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save profile.');
            }
        }

        return $this->render('profile', ['model' => $model]);
    }

    /**
     * ✅ UPDATED: Display profile with all relations using updated relation names
     */
    public function actionView()
    {
        $userId = Yii::$app->user->id;

        $model = UserDetails::find()
            ->where(['user_id' => $userId])
            ->with([
                'userJobDetails',  // ✅ UPDATED: was 'userJob'
                'partnerDetails',
                'partnerDetails.partnerJob'
            ])
            ->one();

        if (!$model) {
            Yii::$app->session->setFlash('warning', 'Please complete your profile.');
            return $this->redirect(['profile']);
        }

        return $this->render('view', ['model' => $model]);
    }

    protected function findModel($id)
    {
        if (($model = UserDetails::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested user profile does not exist.');
    }
}