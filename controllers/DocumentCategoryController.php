<?php

namespace app\controllers;

use Yii;
use app\models\DocumentCategory;
use app\models\DocumentCategorySearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class DocumentCategoryController extends Controller
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
        // Create search model instance
        $searchModel = new DocumentCategorySearch();
        
        // Get data provider with search results
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new DocumentCategory();
        
        // Set default values
        if ($model->isNewRecord) {
            $model->status = DocumentCategory::STATUS_ACTIVE;
            $model->is_required = 0;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Document category created successfully.');
            return $this->redirect(['view', 'id' => $model->category_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Document category updated successfully.');
            return $this->redirect(['view', 'id' => $model->category_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Document category deleted successfully.');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = DocumentCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested document category does not exist.');
    }
}