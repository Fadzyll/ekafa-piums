<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\models\PartnerDetails;

class PartnerDetailsController extends Controller
{
    /**
     * Add or update partner details (including image upload).
     */
    public function actionProfile()
    {
        $userId = Yii::$app->user->id;

        // Load existing record or create a new one
        $model = PartnerDetails::findOne(['partner_id' => $userId]);
        if (!$model) {
            $model = new PartnerDetails();
            $model->partner_id = $userId;
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate()) {
                // ✅ Handle profile picture upload
                if ($model->imageFile) {
                    $uploadDir = Yii::getAlias('@webroot/uploads/');
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0775, true);
                    }

                    // Delete old profile picture safely
                    if (!empty($model->profile_picture_url)) {
                        $oldFile = Yii::getAlias('@webroot/' . ltrim($model->profile_picture_url, '/'));
                        if (is_file($oldFile)) {
                            @unlink($oldFile);
                        }
                    }

                    // Generate new filename and save
                    $filename = 'partner_' . $model->partner_id . '_' . time() . '.' . $model->imageFile->extension;
                    $filePath = $uploadDir . $filename;

                    if ($model->imageFile->saveAs($filePath)) {
                        $model->profile_picture_url = 'uploads/' . $filename;
                    }
                }

                // Save the updated record
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Partner details saved successfully.');

                    // ✅ Redirect back to the user details page
                    return $this->redirect(['user-details/view']);
                }
            }
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * View partner details (mainly for admin or debug use).
     */
    public function actionView($partner_id = null)
    {
        $userId = $partner_id ?? Yii::$app->user->id;
        $model = $this->findModel($userId);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the PartnerDetails model based on partner_id.
     * @param int $partner_id
     * @return PartnerDetails
     * @throws NotFoundHttpException
     */
    protected function findModel($partner_id)
    {
        if (($model = PartnerDetails::findOne(['partner_id' => $partner_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested partner record does not exist.');
    }
}