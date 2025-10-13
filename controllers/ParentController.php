<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class ParentController extends Controller
{
    /**
     * Access control to allow only logged-in users with 'Parent' role.
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
                            return Yii::$app->user->identity->role === 'Parent';
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Parent dashboard
     */
    public function actionDashboard()
    {
        return $this->render('dashboard');
    }

    /**
     * View child info
     */
    public function actionChildInfo()
    {
        return $this->render('child-info');
    }
}
