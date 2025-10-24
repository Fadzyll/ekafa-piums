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
use yii\web\Response;

/**
 * ClassroomController implements the CRUD actions for ClassroomModel.
 * Updated to match E-Kafa Database Data Dictionary v1.0 - Table #4
 */
class ClassroomController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                    'bulk-delete' => ['POST'],
                    'update-status' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ClassroomModel models with enhanced filtering and sorting.
     * @return string
     */
    public function actionIndex()
    {
        // Create search model instance
        $searchModel = new ClassroomModelSearch();

        // Get data provider with search results
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClassroomModel model.
     * @param int $class_id Class ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($class_id)
    {
        $model = $this->findModel($class_id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new ClassroomModel.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new ClassroomModel();

        // Fetch teachers for dropdown
        $teachers = Users::find()
            ->where(['role' => 'Teacher'])
            ->select(['username'])
            ->indexBy('user_id')
            ->column();

        if ($this->request->isAjax && $model->load($this->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Classroom created successfully!');
            return $this->redirect(['view', 'class_id' => $model->class_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Updates an existing ClassroomModel.
     * @param int $class_id Class ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($class_id)
    {
        $model = $this->findModel($class_id);

        // Fetch teachers for dropdown
        $teachers = Users::find()
            ->where(['role' => 'Teacher'])
            ->select(['username'])
            ->indexBy('user_id')
            ->column();

        if ($this->request->isAjax && $model->load($this->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Classroom updated successfully!');
            return $this->redirect(['view', 'class_id' => $model->class_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Deletes an existing ClassroomModel.
     * @param int $class_id Class ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($class_id)
    {
        $model = $this->findModel($class_id);
        
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Classroom deleted successfully!');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete classroom.');
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Bulk delete action for multiple classrooms.
     * @return Response
     */
    public function actionBulkDelete()
    {
        $ids = $this->request->post('ids', []);
        
        if (empty($ids)) {
            Yii::$app->session->setFlash('error', 'No classrooms selected.');
            return $this->redirect(['index']);
        }

        $count = ClassroomModel::deleteAll(['class_id' => $ids]);
        Yii::$app->session->setFlash('success', "Successfully deleted {$count} classroom(s).");
        
        return $this->redirect(['index']);
    }

    /**
     * Quick status update via AJAX.
     * @return array
     */
    public function actionUpdateStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $class_id = $this->request->post('class_id');
        $status = $this->request->post('status');
        
        try {
            $model = $this->findModel($class_id);
            $model->status = $status;
            
            if ($model->save(false)) {
                return [
                    'success' => true,
                    'message' => 'Status updated successfully.',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update status.',
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get classroom statistics via AJAX.
     * @return array
     */
    public function actionGetStats()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $class_id = $this->request->get('class_id');
        
        try {
            $model = $this->findModel($class_id);
            
            return [
                'success' => true,
                'data' => [
                    'enrollment_percentage' => $model->getEnrollmentPercentage(),
                    'available_slots' => $model->getAvailableSlots(),
                    'is_full' => $model->isFull(),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Export classrooms to CSV.
     * @return Response
     */
    public function actionExport()
    {
        $searchModel = new ClassroomModelSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination = false;

        $models = $dataProvider->models;

        $filename = 'classrooms_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, [
            'ID',
            'Class Name',
            'Year',
            'Grade Level',
            'Session Type',
            'Teacher',
            'Quota',
            'Current Enrollment',
            'Available Slots',
            'Status',
            'Classroom Location',
            'Start Date',
            'End Date',
        ]);
        
        // Data
        foreach ($models as $model) {
            fputcsv($output, [
                $model->class_id,
                $model->class_name,
                $model->year,
                $model->grade_level,
                $model->session_type,
                $model->user ? $model->user->username : 'N/A',
                $model->quota,
                $model->current_enrollment,
                $model->getAvailableSlots(),
                $model->status,
                $model->classroom_location,
                $model->class_start_date,
                $model->class_end_date,
            ]);
        }
        
        fclose($output);
        Yii::$app->end();
    }

    /**
     * Finds the ClassroomModel model based on its primary key value.
     * @param int $class_id Class ID
     * @return ClassroomModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($class_id)
    {
        if (($model = ClassroomModel::findOne(['class_id' => $class_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested classroom does not exist.');
    }
}