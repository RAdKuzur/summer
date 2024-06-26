<?php

namespace app\controllers;

use app\models\PartyPersonal;
use app\models\PartyTeam;
use app\models\PersonalOffset;
use app\models\Team;
use app\models\History;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\SiClick;
use app\services\SiteService;
use app\repositories\ColorRepository;
use app\repositories\ContactFormRepository;
use app\repositories\DynamicModelRepository;
use app\repositories\HistoryRepository;
use app\repositories\LoginFormRepository;
use app\repositories\PartyTeamRepository;
use app\repositories\PartyPersonalRepository;
use app\repositories\PersonalOffsetRepository;
use app\repositories\SiClickRepository;
use app\repositories\TeamRepository;
use app\repositories\TimerRepository;
use app\repositories\UserRepository;
class SiteController extends Controller
{
    public HistoryRepository $historyRepository;
    private SiteService $service;
    public PartyTeamRepository $partyTeamRepository;
    public PartyPersonalRepository $partyPersonalRepository;
    public PersonalOffsetRepository $personalOffsetRepository;
    public SiClickRepository $siClickRepository;
    public TeamRepository $teamRepository;
    public UserRepository $userRepository;
    public function __construct(
        $id,
        $module,
        SiteService $service,
        HistoryRepository $hisRepository,
        PartyTeamRepository $parTeamRepository,
        PersonalOffsetRepository $perOffsetRepository,
        SiClickRepository $ClickRepository,
        TeamRepository $commandRepository,
        UserRepository $usRepository,
        PartyPersonalRepository $partyPerRepository,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->teamRepository = $commandRepository;
        $this->userRepository = $usRepository;
        $this->siClickRepository = $ClickRepository;
        $this->partyTeamRepository = $parTeamRepository;
        $this->historyRepository = $hisRepository;
        $this->personalOffsetRepository = $perOffsetRepository;
        $this->partyPersonalRepository = $partyPerRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'forgot-password', 'si-index', 'si-user', 'si-admin', 'si-confirm', 'si-table', 'si-unblock'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'index-docs-out', 'create-docs-out', 'add-admin', 'feedback', 'feedback-answer',
                            'temp', 'team-view', 'personal-view', 'index-team', 'choose-color', 'plus-one', 'plus-ten', 'minus-one', 'minus-ten', 'plus', 'minus',
                            'plus-val', 'minus-val', 'minus-score','plus-score',
                            'index-personal', 'plus-one-p', 'plus-ten-p', 'minus-one-p', 'plus-two-p', 'si-index', 'si-user', 'si-admin', 'si-confirm', 'si-table', 'si-unblock'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
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

    public function actionSiIndex($name)
    {
        if ($name == 'admin')
            return $this->render('si-admin');
        return $this->redirect(['site/si-user', 'name' => $name]);
    }

    public function actionSiUnblock()
    {
        $clicks = $this->siClickRepository->findSiClickAll();
        foreach ($clicks as $click)
            $this->siClickRepository->deleteSiClickAll($click);
        return $this->render('si-admin');
    }

    public function actionSiTable()
    {
        return $this->render('si-table');
    }

    public function actionSiUser($name)
    {
        $model = Yii::createObject(SiClick::class);
        $this->service->siteSiUser($name);
        if ($model->load(Yii::$app->request->post())) {
            $this->render('si-admin');
        }
        return $this->render('si-user', ['model' => $model]);
    }


    public function actionSiConfirm()
    {
        $model = Yii::createObject(SiClick::class);
        $name = $this->userRepository->findUser();
        $this->service->userUpdateIdTime($model, $name);
        $duplicate = $this->service->findSiClickById($name);
        if ($duplicate == null) {
            $this->service->saveSiClick($model);

        }
        return $this->redirect(['site/si-user', 'name' => Yii::$app->session->get('user')]);
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = Yii::createObject(LoginForm::class);
        if (Yii::$app->user->isGuest) {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        return $this->render('index');
    }
    public function actionIndexTeam()
    {
        $model = Yii::createObject(Team::class);
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model = $this->teamRepository->findTeamById($model);
        }
        return $this->render('index-team', [
            'model' => $model,
        ]);
    }
    public function actionIndexPersonal()
    {
        $model = Yii::createObject(PersonalOffset::class);
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model = $this->personalOffsetRepository->findPersonalOffsetById($model);
        }

        return $this->render('index-personal', [
            'model' => $model,
        ]);
    }
    public function actionChooseColor($id = null, $branch = null)
    {
        $model  = Yii::createObject(PartyTeam::class);
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if ($id !== null) {
            $model = $this->partyTeamRepository->findChooseColor($branch, $id);
        }
        return $this->render('choose-color', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        $model = Yii::createObject(LoginForm::class);
        if (!Yii::$app->user->isGuest)
            return $this->redirect('index');
        if ($model->load(Yii::$app->request->post())) {
            $user = $this->userRepository->findUserLogin($model);
            if ($model->password == '' && $user !== null)
                return $this->redirect(['site/si-index', 'name' => $model->username]);
            if ($model->password !== '' && $model->login()) {
                return $this->render('index');
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $this->service->siteLogout();
        return $this->redirect(['site/login']);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model =  Yii::createObject(ContactForm::class);
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            $this->service->siteContact();
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionTeamView()
    {
        if (Yii::$app->user->isGuest) {
            $model = Yii::createObject(LoginForm::class);
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        return $this->render('team-view');
    }
    public function actionPersonalView()
    {
        if (Yii::$app->user->isGuest) {
            $model = Yii::createObject(LoginForm::class);
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        return $this->render('personal-view');
    }
    public function actionPlus($numb, $id = null, $branch = null)
    {
        if (Yii::$app->user->isGuest) {
            $model = Yii::createObject(LoginForm::class);
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        $model = Yii::createObject(PartyTeam::class);
        if ($id !== null) {
            $model = $this->partyPersonalRepository->plusNumb($id, $numb);
        }
        //$model = Yii::createObject(PersonalOffset::class);
        return $this->redirect(['index-personal','model' => $model]);
    }

    public function actionPlusVal($numb = null, $id = null, $branch = null)
    {
        if (Yii::$app->user->isGuest) {
            $model = new LoginForm();
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        $model = Yii::createObject(PartyTeam::class);
        if (Yii::$app->request->post('PartyTeam')['id']) {
            $id = Yii::$app->request->post('PartyTeam')['id'];
            $score = Yii::$app->request->post('PartyTeam')['score'];
            $lastBranch = Yii::$app->request->post('PartyTeam')['lastBranch'];
            $model = $this->partyTeamRepository->plusScore($id, $score, $lastBranch);
            $this->historyRepository->siteWriteHistory('+' . Yii::$app->request->post('PartyTeam')['score'], $model->id);
        }

        return $this->render('choose-color', [
            'model' => $model,
        ]);
    }
    public function actionMinus($numb, $id = null, $branch = null)
    {
        if (Yii::$app->user->isGuest) {
            $model = Yii::createObject(LoginForm::class);
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        $model = Yii::createObject(PartyTeam::class);
        if ($id !== null) {
            $model = $this->partyPersonalRepository->minusNumb($id, $numb);
        }
        $model = Yii::createObject(PersonalOffset::class);
        return $this->redirect(['index-personal','model' => $model]);

    }
    public function actionMinusVal($numb = null, $id = null, $branch = null)
    {
        if (Yii::$app->user->isGuest) {
            $model= Yii::createObject(LoginForm::class);
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        $model = new PartyTeam();
        if (Yii::$app->request->post('PartyTeam')['id']) {
            $id = Yii::$app->request->post('PartyTeam')['id'];
            $score = Yii::$app->request->post('PartyTeam')['score'];
            $lastBranch = Yii::$app->request->post('PartyTeam')['lastBranch'];
            $model = $this->partyTeamRepository->minusScore($id, $score, $lastBranch);
            $this->historyRepository->siteWriteHistory('-' . Yii::$app->request->post('PartyTeam')['score'], $model->id);
        }
        return $this->render('choose-color', [
            'model' => $model,
        ]);
        //return $this->redirect(['index-team','model' => $model]);
    }
    public function actionMinusScore($numb = null, $id = null, $branch = null)
    {
        if (Yii::$app->user->isGuest) {
            $model= Yii::createObject(LoginForm::class);
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        $model = Yii::createObject(PartyTeam::class);
        $model = $this->partyTeamRepository->minusNumb($id, $numb, $branch);
        $this->historyRepository->siteWriteHistory('-' . $numb, $model->id);
        return $this->render('choose-color', [
            'model' => $model,
        ]);

    }
    public function actionPlusScore($numb = null, $id = null, $branch = null)
    {
        if (Yii::$app->user->isGuest) {
            $model = new LoginForm();
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        $model = Yii::createObject(PartyTeam::class);
        $model = $this->partyTeamRepository->plusNumb($id, $numb, $branch);
        $this->historyRepository->siteWriteHistory('+' . $numb, $model->id);
        return $this->render('choose-color', [
            'model' => $model,
        ]);
    }
}