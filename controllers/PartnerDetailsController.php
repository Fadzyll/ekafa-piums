<?php

namespace app\controllers;

use app\models\PartnerDetails;
use app\models\PartnerDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartnerDetailsController implements the CRUD actions for PartnerDetails model.
 */
class PartnerDetailsController extends Controller
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
     * Lists all PartnerDetails models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PartnerDetailsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PartnerDetails model.
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
     * Creates a new PartnerDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PartnerDetails();

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
     * Updates an existing PartnerDetails model.
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
     * Deletes an existing PartnerDetails model.
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
     * Finds the PartnerDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $partner_id Partner ID
     * @return PartnerDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($partner_id)
    {
        if (($model = PartnerDetails::findOne(['partner_id' => $partner_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
