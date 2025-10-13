<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegisterAccForm;
use app\models\UserDetails;  // Make sure to import UserDetails model

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * This runs before every action.
     * It checks if user is logged in and has completed their profile.
     * If not, redirect them to profile completion page.
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (!Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->id;
            $profile = UserDetails::findOne(['user_id' => $userId]);
            $route = Yii::$app->controller->route;

            // Define allowed routes without completed profile
            $allowedRoutes = [
                'user-details/profile',
                'site/logout',
                'site/error',
            ];

            // If profile missing or incomplete and trying to access disallowed pages, redirect
            if (
                (!$profile || !$this->isProfileComplete($profile)) &&
                !in_array($route, $allowedRoutes)
            ) {
                return $this->redirect(['user-details/profile'])->send();
            }
        }

        return true;
    }

    /**
     * Simple profile completion check: adjust as needed
     */
    protected function isProfileComplete($profile)
    {
        // Example: require full_name and ic_number to be filled
        return !empty($profile->full_name) && !empty($profile->ic_number);
    }

    // ... your existing actions below unchanged ...

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSelectRole()
    {
        return $this->render('select_roles');
    }

    /**
     * Login action with redirect based on role
     */
    public function actionLogin($role = null)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/dashboard']);
        }

        $model = new LoginForm();
        $model->role = $role;

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $userId = Yii::$app->user->id;
            $profile = \app\models\UserDetails::findOne(['user_id' => $userId]);

            $isComplete = $profile && !empty($profile->full_name) && !empty($profile->ic_number);

            if (!$isComplete) {
                Yii::$app->session->setFlash('warning', 'Please complete your profile.');
                return $this->redirect(['user-details/profile']);
            }

            // Redirect based on role
            $userRole = Yii::$app->user->identity->role;
            switch ($userRole) {
                case 'Admin':
                    return $this->redirect(['/admin/dashboard']);
                case 'Teacher':
                    return $this->redirect(['/teacher/dashboard']);
                case 'Parent':
                    return $this->redirect(['/parent/dashboard']);
                default:
                    Yii::$app->user->logout();
                    throw new \yii\web\ForbiddenHttpException('Invalid role.');
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
            'role' => $role,
        ]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) &&
            $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Redirects to role-specific dashboard controller
     */
    public function actionDashboard()
    {
        $role = Yii::$app->user->identity->role ?? null;

        switch ($role) {
            case 'Admin':
                return $this->redirect(['/admin/dashboard']);
            case 'Teacher':
                return $this->redirect(['/teacher/dashboard']);
            case 'Parent':
                return $this->redirect(['/parent/dashboard']);
            default:
                throw new \yii\web\ForbiddenHttpException('Access denied');
        }
    }

    public function actionRegisterParent()
    {
        $model = new RegisterAccForm();

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->setFlash('success', 'Registration successful. You can now log in.');
            return $this->redirect(['site/login', 'role' => 'parent']);
        }

        return $this->render('register_parents', [
            'model' => $model,
        ]);
    }
}