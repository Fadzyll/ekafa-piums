<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use app\models\PartnerDetails;
use app\models\UserDetails;

class PartnerDetailsController extends Controller
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
        ];
    }

    /**
     * ✅ FIXED: Partner profile with validation
     */
    public function actionProfile()
    {
        $userId = Yii::$app->user->id;

        // ✅ Check if user_details exists
        $userDetails = UserDetails::findOne(['user_id' => $userId]);
        if (!$userDetails) {
            Yii::$app->session->setFlash('warning', 'Please complete your personal profile first.');
            return $this->redirect(['user-details/profile']);
        }

        // Find or create partner record using user_id
        $model = PartnerDetails::findOne(['partner_id' => $userId]);
        if (!$model) {
            $model = new PartnerDetails();
            $model->partner_id = $userId;
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
                    $oldFile = Yii::getAlias('@webroot/' . ltrim($model->profile_picture_url, '/'));
                    if (is_file($oldFile)) {
                        @unlink($oldFile);
                    }
                }

                // Save new image
                $filename = 'partner_' . $model->partner_id . '_' . time() . '.' . $model->imageFile->extension;
                $filePath = $uploadDir . $filename;

                if ($model->imageFile->saveAs($filePath)) {
                    $model->profile_picture_url = 'uploads/' . $filename;
                }
            }

            // Save without re-validation
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Partner details saved successfully!');
                return $this->redirect(['user-details/view']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save partner details.');
            }
        }

        return $this->render('profile', ['model' => $model]);
    }

    /**
     * View partner details
     */
    public function actionView($partner_id = null)
    {
        $userId = $partner_id ?? Yii::$app->user->id;
        $model = $this->findModel($userId);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Find partner model
     */
    protected function findModel($partner_id)
    {
        if (($model = PartnerDetails::findOne(['partner_id' => $partner_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested partner record does not exist.');
    }
}