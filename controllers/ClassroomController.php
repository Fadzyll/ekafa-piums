<?php

namespace app\controllers;

use Yii;
use app\models\ClassroomModel;
use app\models\ClassroomModelSearch;
use app\models\Users;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ClassroomController extends Controller
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
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->role === 'Admin';
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

    public function actionIndex()
    {
        $searchModel = new ClassroomModelSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($class_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($class_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new ClassroomModel();

        // Fetch teachers for dropdown as [user_id => username]
        $teachers = Users::find()
            ->where(['role' => 'Teacher'])
            ->select(['username'])
            ->indexBy('user_id')
            ->column();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'class_id' => $model->class_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'teachers' => $teachers,
        ]);
    }

    public function actionUpdate($class_id)
    {
        $model = $this->findModel($class_id);

        // Fetch teachers for dropdown as [user_id => username]
        $teachers = Users::find()
            ->where(['role' => 'Teacher'])
            ->select(['username'])
            ->indexBy('user_id')
            ->column();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'class_id' => $model->class_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'teachers' => $teachers,
        ]);
    }

    public function actionDelete($class_id)
    {
        $this->findModel($class_id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($class_id)
    {
        if (($model = ClassroomModel::findOne(['class_id' => $class_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}