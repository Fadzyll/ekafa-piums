<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class TeacherController extends Controller
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
                            return Yii::$app->user->identity->role === 'Teacher';
                        }
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
}