<?php

namespace app\controllers;

use Yii;
use app\models\UserJobDetails;
use app\models\UserDetails;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class UserJobController extends Controller
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
                            // ✅ Only allow Parents to access employment details
                            return Yii::$app->user->identity->role === 'Parent';
                        },
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->session->setFlash('error', 'Employment information is only available for Parents.');
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
     * ✅ UPDATED: Job profile using UserJobDetails model with validation
     */
    public function actionProfile()
    {
        $userId = Yii::$app->user->id;

        // ✅ Check if user_details exists first
        $userDetails = UserDetails::findOne(['user_id' => $userId]);
        if (!$userDetails) {
            Yii::$app->session->setFlash('warning', 'Please complete your personal profile first.');
            return $this->redirect(['user-details/profile']);
        }

        // Find or create job record using UserJobDetails
        $model = UserJobDetails::findOne(['user_id' => $userId]);
        if (!$model) {
            $model = new UserJobDetails();
            $model->user_id = $userId;
        }

        if ($model->load(Yii::$app->request->post())) {
            // ✅ Validate BEFORE saving
            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', 'Please correct the errors below.');
                return $this->render('profile', ['model' => $model]);
            }

            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Employment information saved successfully!');
                return $this->redirect(['user-details/view']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save employment information.');
            }
        }

        return $this->render('profile', ['model' => $model]);
    }

    /**
     * ✅ UPDATED: View job details using UserJobDetails
     */
    public function actionView()
    {
        $userId = Yii::$app->user->id;
        $model = UserJobDetails::findOne(['user_id' => $userId]);

        if (!$model) {
            Yii::$app->session->setFlash('warning', 'No job information found.');
            return $this->redirect(['profile']);
        }

        return $this->render('view', ['model' => $model]);
    }

    /**
     * ✅ UPDATED: Delete job record using UserJobDetails
     */
    public function actionDelete()
    {
        $userId = Yii::$app->user->id;
        $model = UserJobDetails::findOne(['user_id' => $userId]);

        if ($model && $model->delete()) {
            Yii::$app->session->setFlash('success', 'Job record deleted successfully.');
        }

        return $this->redirect(['user-details/view']);
    }
}