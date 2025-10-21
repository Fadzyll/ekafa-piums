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
use app\models\UserDetails;
use app\models\UserJob;
use app\models\PartnerDetails;

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
     * ✅ FIXED: Check ALL profile sections before allowing access
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (!Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->id;
            $userRole = Yii::$app->user->identity->role;
            $route = Yii::$app->controller->route;

            // ✅ Define allowed routes without completed profile
            $allowedRoutes = [
                'user-details/profile',
                'user-details/view',
                'user-job/profile',
                'partner-details/profile',
                'partner-job/profile',
                'site/logout',
                'site/error',
                'site/dashboard',
                'admin/dashboard',
                'admin/index',
                'teacher/dashboard',
                'teacher/index',
                'parent/dashboard',
            ];

            // ✅ Admins can bypass all checks
            if ($userRole === 'Admin') {
                return true;
            }

            // ✅ Check profile completion status
            $completionStatus = $this->getProfileCompletionStatus($userId, $userRole);

            // ✅ If on allowed routes, let them through
            if (in_array($route, $allowedRoutes)) {
                return true;
            }

            // ✅ Redirect to incomplete section
            if (!$completionStatus['user_details']) {
                Yii::$app->session->setFlash('warning', 'Please complete your personal information first.');
                return $this->redirect(['user-details/profile'])->send();
            }

            if (!$completionStatus['user_job']) {
                Yii::$app->session->setFlash('warning', 'Please complete your employment information.');
                return $this->redirect(['user-job/profile'])->send();
            }

            // ✅ Parents must complete partner info
            if ($userRole === 'Parent') {
                if (!$completionStatus['partner_details']) {
                    Yii::$app->session->setFlash('warning', 'Please complete your partner information.');
                    return $this->redirect(['partner-details/profile'])->send();
                }

                if (!$completionStatus['partner_job']) {
                    Yii::$app->session->setFlash('warning', 'Please complete your partner employment information.');
                    return $this->redirect(['partner-job/profile'])->send();
                }
            }
        }

        return true;
    }

    /**
     * ✅ NEW: Get detailed profile completion status
     * Returns array with completion status for each section
     */
    protected function getProfileCompletionStatus($userId, $userRole)
    {
        $userDetails = UserDetails::findOne(['user_id' => $userId]);
        $userJob = UserJob::findOne(['user_id' => $userId]);
        $partnerDetails = PartnerDetails::findOne(['partner_id' => $userId]);
        
        $status = [
            'user_details' => false,
            'user_job' => false,
            'partner_details' => false,
            'partner_job' => false,
        ];

        // ✅ Check User Details (required fields only)
        if ($userDetails && 
            !empty($userDetails->full_name) && 
            !empty($userDetails->ic_number) && 
            !empty($userDetails->phone_number)) {
            $status['user_details'] = true;
        }

        // ✅ Check User Job (all fields required)
        if ($userJob && 
            !empty($userJob->job) && 
            !empty($userJob->employer) && 
            !empty($userJob->employer_address) && 
            !empty($userJob->employer_phone_number) && 
            !empty($userJob->gross_salary) && 
            !empty($userJob->net_salary)) {
            $status['user_job'] = true;
        }

        // ✅ Check Partner Details (only for Parents)
        if ($userRole === 'Parent') {
            if ($partnerDetails && 
                !empty($partnerDetails->partner_name) && 
                !empty($partnerDetails->partner_ic_number)) {
                $status['partner_details'] = true;

                // ✅ Check Partner Job (only if partner exists)
                $partnerJob = $partnerDetails->partnerJob;
                if ($partnerJob && 
                    !empty($partnerJob->partner_job) && 
                    !empty($partnerJob->partner_employer) && 
                    !empty($partnerJob->partner_employer_address) && 
                    !empty($partnerJob->partner_employer_phone_number) && 
                    !empty($partnerJob->partner_gross_salary) && 
                    !empty($partnerJob->partner_net_salary)) {
                    $status['partner_job'] = true;
                }
            }
        } else {
            // ✅ Teachers don't need partner info
            $status['partner_details'] = true;
            $status['partner_job'] = true;
        }

        return $status;
    }

    /**
     * ✅ SIMPLIFIED: Old method for backward compatibility
     */
    protected function isProfileComplete($profile)
    {
        if (!$profile) {
            return false;
        }
        
        return !empty($profile->full_name) && 
               !empty($profile->ic_number) && 
               !empty($profile->phone_number);
    }

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
            $userRole = Yii::$app->user->identity->role;

            // ✅ Admins skip profile check
            if ($userRole === 'Admin') {
                return $this->redirect(['/admin/dashboard']);
            }

            // ✅ Check profile completion
            $profile = UserDetails::findOne(['user_id' => $userId]);
            $isComplete = $this->isProfileComplete($profile);

            if (!$isComplete) {
                Yii::$app->session->setFlash('warning', 'Please complete your profile.');
                return $this->redirect(['user-details/profile']);
            }

            // ✅ Redirect based on role
            switch ($userRole) {
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