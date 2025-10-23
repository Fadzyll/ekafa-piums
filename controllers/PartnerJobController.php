<?php

namespace app\controllers;

use Yii;
use app\models\PartnerJob;
use app\models\PartnerDetails;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class PartnerJobController extends Controller
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
                            // ✅ Only allow Parents to access partner job details
                            return Yii::$app->user->identity->role === 'Parent';
                        },
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->session->setFlash('error', 'Partner employment information is only available for Parents.');
                    return Yii::$app->response->redirect(['user-details/view']);
                },
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
     * ✅ FIXED: Partner job profile with validation
     */
    public function actionProfile()
    {
        $userId = Yii::$app->user->id;

        // ✅ Check if partner_details exists first
        $partnerDetails = PartnerDetails::findOne(['partner_id' => $userId]);
        if (!$partnerDetails) {
            Yii::$app->session->setFlash('warning', 'Please add your Partner Details first before adding job information.');
            return $this->redirect(['partner-details/profile']);
        }

        // Find or create partner job record
        $model = PartnerJob::findOne(['partner_id' => $partnerDetails->partner_id]);
        if (!$model) {
            $model = new PartnerJob();
            $model->partner_id = $partnerDetails->partner_id;
        }

        if ($model->load(Yii::$app->request->post())) {
            // ✅ Validate BEFORE saving
            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', 'Please correct the errors below.');
                return $this->render('profile', ['model' => $model]);
            }

            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Partner job details saved successfully!');
                return $this->redirect(['user-details/view']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save partner job details.');
            }
        }

        return $this->render('profile', ['model' => $model]);
    }

    /**
     * View partner job details
     */
    public function actionView()
    {
        $userId = Yii::$app->user->id;
        
        $partnerDetails = PartnerDetails::findOne(['partner_id' => $userId]);
        if (!$partnerDetails) {
            Yii::$app->session->setFlash('warning', 'Partner details not found.');
            return $this->redirect(['partner-details/profile']);
        }

        $model = PartnerJob::findOne(['partner_id' => $partnerDetails->partner_id]);
        if (!$model) {
            Yii::$app->session->setFlash('warning', 'No partner job information found.');
            return $this->redirect(['profile']);
        }

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Delete partner job record
     */
    public function actionDelete()
    {
        $userId = Yii::$app->user->id;
        
        $partnerDetails = PartnerDetails::findOne(['partner_id' => $userId]);
        if ($partnerDetails) {
            $model = PartnerJob::findOne(['partner_id' => $partnerDetails->partner_id]);
            if ($model && $model->delete()) {
                Yii::$app->session->setFlash('success', 'Partner job record deleted successfully.');
            }
        }

        return $this->redirect(['user-details/view']);
    }
}