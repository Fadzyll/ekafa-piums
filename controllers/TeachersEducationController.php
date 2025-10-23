<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
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
     * Profile action - handles both create and update
     */
    public function actionProfile($id = null)
    {
        if ($id !== null) {
            // Update mode
            $model = $this->findModel($id);
            
            // Ensure the education record belongs to the logged-in user
            if ($model->user_id !== Yii::$app->user->id) {
                throw new NotFoundHttpException('The requested education record does not exist.');
            }
        } else {
            // Create mode
            $model = new TeachersEducation();
            $model->user_id = Yii::$app->user->id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $message = $id !== null ? 'Education record updated successfully!' : 'Education record added successfully!';
            Yii::$app->session->setFlash('success', $message);
            return $this->redirect(['user-details/view']);
        }

        return $this->render('profile', ['model' => $model]);
    }

    /**
     * Create a new education record (redirects to profile)
     */
    public function actionCreate()
    {
        return $this->redirect(['profile']);
    }

    /**
     * Update an existing education record (redirects to profile with id)
     */
    public function actionUpdate($id)
    {
        return $this->redirect(['profile', 'id' => $id]);
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

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Education record deleted successfully!');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete education record.');
        }

        return $this->redirect(['user-details/view']);
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