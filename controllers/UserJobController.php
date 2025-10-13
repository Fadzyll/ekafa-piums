<?php

namespace app\controllers;

use Yii;
use app\models\UserJob;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
                        'roles' => ['@'], // Only logged-in users
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
     * Handles both creating and updating the logged-in user's job record.
     * Mirrors how UserDetailsController::actionProfile works.
     */
    public function actionProfile()
    {
        $userId = Yii::$app->user->identity->user_id ?? Yii::$app->user->id;

        // Try to find an existing job for the user
        $model = UserJob::findOne(['user_id' => $userId]);
        if (!$model) {
            $model = new UserJob();
            $model->user_id = $userId;
        }

        // Handle form submission
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Job information saved successfully.');
                return $this->redirect(['user-details/view']); // back to profile page
            } else {
                Yii::$app->session->setFlash('error', 'Please correct the errors below.');
            }
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * Displays the logged-in user's job details.
     * If not found, redirects to the job profile form.
     */
    public function actionView()
    {
        $userId = Yii::$app->user->identity->user_id ?? Yii::$app->user->id;
        $model = UserJob::findOne(['user_id' => $userId]);

        if (!$model) {
            Yii::$app->session->setFlash('warning', 'No job information found. Please complete your job details.');
            return $this->redirect(['profile']);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes the logged-in user's job record.
     */
    public function actionDelete()
    {
        $userId = Yii::$app->user->identity->user_id ?? Yii::$app->user->id;
        $model = UserJob::findOne(['user_id' => $userId]);

        if ($model && $model->delete()) {
            Yii::$app->session->setFlash('success', 'Job record deleted successfully.');
        } else {
            Yii::$app->session->setFlash('warning', 'No job record found to delete.');
        }

        return $this->redirect(['user-details/view']);
    }
}