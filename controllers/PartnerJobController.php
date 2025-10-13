<?php

namespace app\controllers;

use app\models\PartnerJob;
use app\models\PartnerJobSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartnerJobController implements the CRUD actions for PartnerJob model.
 */
class PartnerJobController extends Controller
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
     * Lists all PartnerJob models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PartnerJobSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PartnerJob model.
     * @param int $partner_id Partner ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($partner_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($partner_id),
        ]);
    }

    /**
     * Creates a new PartnerJob model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PartnerJob();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'partner_id' => $model->partner_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PartnerJob model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $partner_id Partner ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($partner_id)
    {
        $model = $this->findModel($partner_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'partner_id' => $model->partner_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PartnerJob model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $partner_id Partner ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($partner_id)
    {
        $this->findModel($partner_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PartnerJob model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $partner_id Partner ID
     * @return PartnerJob the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($partner_id)
    {
        if (($model = PartnerJob::findOne(['partner_id' => $partner_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
