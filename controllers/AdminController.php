<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\UserDetails;
use yii\data\ActiveDataProvider;

class AdminController extends Controller
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
                            return \Yii::$app->user->identity->role === 'Admin';
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionDashboard()
    {
        return $this->render('dashboard');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionManageUsers()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => UserDetails::find(),
            'pagination' => ['pageSize' => 10],
            'sort' => ['defaultOrder' => ['full_name' => SORT_ASC]],
        ]);

        return $this->render('manage-users', [
            'dataProvider' => $dataProvider,
        ]);
    }
}