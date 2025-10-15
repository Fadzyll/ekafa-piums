<?php

namespace app\controllers;

use Yii;
use app\models\PartnerJob;
use app\models\PartnerDetails;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
                        'roles' => ['@'], // ✅ Only logged-in users
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
     * Create or update partner job profile.
     */
    public function actionProfile()
    {
        $userId = Yii::$app->user->id;

        // ✅ Step 1: Find PartnerDetails based on logged-in user's ID
        $partnerDetails = PartnerDetails::findOne(['partner_id' => $userId]);

        if (!$partnerDetails) {
            Yii::$app->session->setFlash('warning', 'Please add your Partner Details first before adding job information.');
            return $this->redirect(['partner-details/profile']);
        }

        // ✅ Step 2: Find or create PartnerJob
        $model = PartnerJob::findOne(['partner_id' => $partnerDetails->partner_id]);
        if (!$model) {
            $model = new PartnerJob();
            $model->partner_id = $partnerDetails->partner_id;
        }

        // ✅ Step 3: Handle form submission
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Partner job details saved successfully.');
            return $this->redirect(['user-details/view']);
        }

        // ✅ Step 4: Render profile form
        return $this->render('profile', [
            'model' => $model,
        ]);
    }
}