<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Users models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Check if username is available (AJAX endpoint)
     * @param string $username
     * @param int|null $id Current user ID (for updates)
     * @return array
     */
    public function actionCheckUsername($username, $id = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $query = Users::find()->where(['username' => $username]);
        
        // Exclude current user when updating
        if ($id) {
            $query->andWhere(['!=', 'user_id', $id]);
        }
        
        return ['available' => $query->count() == 0];
    }

    /**
     * Check if email is available (AJAX endpoint)
     * @param string $email
     * @param int|null $id Current user ID (for updates)
     * @return array
     */
    public function actionCheckEmail($email, $id = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $query = Users::find()->where(['email' => $email]);
        
        // Exclude current user when updating
        if ($id) {
            $query->andWhere(['!=', 'user_id', $id]);
        }
        
        return ['available' => $query->count() == 0];
    }

    /**
     * Displays a single Users model.
     * @param int $user_id User ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($user_id)
    {
        $model = $this->findModel($user_id);
        $model->refresh(); // Ensure relations are loaded
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Users();
        $model->scenario = 'insert'; // Use insert scenario

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'User created successfully.');
                return $this->redirect(['view', 'user_id' => $model->user_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to create user.');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $user_id User ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($user_id)
    {
        $model = $this->findModel($user_id);
        
        // Determine if editing is restricted
        $isRestricted = in_array($model->role, ['Teacher', 'Parent']);
        
        if ($this->request->isPost && $model->load($this->request->post())) {
            // If password is empty on update, don't change it
            if (empty($model->password)) {
                $model->password = null; // Will be ignored in beforeSave
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'User updated successfully.');
                return $this->redirect(['view', 'user_id' => $model->user_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update user.');
            }
        }

        // Refresh to ensure relations are loaded
        $model->refresh();

        return $this->render('update', [
            'model' => $model,
            'isRestricted' => $isRestricted,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $user_id User ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($user_id)
    {
        $this->findModel($user_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $user_id User ID
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id)
    {
        if (($model = Users::findOne(['user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}