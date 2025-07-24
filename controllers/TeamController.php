<?php

namespace app\controllers;

use app\models\dynamic\PersonalOffsetDynamic;
use app\models\DynamicModel;
use app\models\LoginForm;
use app\models\PartyTeam;
use app\repositories\DynamicModelRepository;
use app\repositories\HistoryRepository;
use app\repositories\PartyTeamRepository;
use app\repositories\PersonalOffsetRepository;
use app\repositories\SiClickRepository;
use app\repositories\TeamRepository;
use app\repositories\TimerRepository;
use app\repositories\UserRepository;
use app\services\SiteService;
use app\services\TeamService;
use Yii;
use app\models\Team;
use app\models\SearchTeam;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;

/**
 * TeamController implements the CRUD actions for Team model.
 */
class TeamController extends Controller
{

    public DynamicModelRepository $dynamicModelRepository;
    public PartyTeamRepository $partyTeamRepository;
    public SiClickRepository $siClickRepository;
    public TeamRepository $teamRepository;
    public UserRepository $userRepository;
    public TimerRepository $timerRepository;
    public TeamService $teamService;
    public function __construct(
        $id,
        $module,
        TeamRepository $teamRepository,
        PartyTeamRepository $parTeamRepository,
        SiClickRepository $ClickRepository,
        UserRepository $usRepository,
        DynamicModelRepository $dynamicRepository,
        TimerRepository $timeRepository,
        TeamService $service,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->teamService = $service;
        $this->teamRepository = $teamRepository;
        $this->userRepository = $usRepository;
        $this->siClickRepository = $ClickRepository;
        $this->partyTeamRepository = $parTeamRepository;
        $this->dynamicModelRepository = $dynamicRepository;
        $this->timerRepository = $timeRepository;
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    /**
     * Lists all Team models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('index.php?r=site/login');
        }
        $array = $this->teamRepository->findTeamByQuery();
        return $this->render('index', [
            'searchModel' => $array[1],
            'dataProvider' => $array[0],
        ]);
    }

    /**
     * Displays a single Team model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->teamRepository->findModel($id),
        ]);
    }

    /**
     * Creates a new Team model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = Yii::createObject(Team::class);
        $modelTeams = [Yii::createObject(PartyTeam::class)];
        $requestPost = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            $this->dynamicModelRepository->updateTeams($model, $requestPost);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
            'modelTeams' => $modelTeams,
        ]);
    }

    /**
     * Updates an existing Team model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->teamRepository->findModel($id);
        $modelTeams = [Yii::createObject(PartyTeam::class)];
        $requestPost = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            $this->dynamicModelRepository->updateTeams($model, $requestPost);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
            'modelTeams' => $modelTeams,
        ]);
    }
    public function actionTeamView($id)
    {
        $scores = $this->partyTeamRepository->findByTeamId($id);
        $timer = $this->timerRepository->findByName();
        return $this->render('team-view', [
            'scores' => $scores,
            'timer' => $timer,
        ]);
    }
    public function actionTimer()
    {
        $seconds = Yii::$app->request->post('sec');
        $minutes = Yii::$app->request->post('min');
        $hours = Yii::$app->request->post('h');
        $this->timerRepository->updateTime($seconds,$minutes,$hours);
    }

    public function actionReset()
    {
        $this->timerRepository->resetTime();
    }

    public function actionTimerVisible($id)
    {
        $this->teamService->currentVisible();
        $scores = $this->teamService->findByTeamId($id);
        $timer = $this->teamService->findByName();
        return $this->render('team-view', [
            'scores' => $scores,
            'timer' => $timer,
        ]);
    }


    /**
     * Deletes an existing Team model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->teamRepository->findModel($id);
        $this->partyTeamRepository->deleteById($model->id);
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionDeletePartyTeam($id, $modelId)
    {
        $this->partyTeamRepository->deleteByIdTeam($id);
        return $this->redirect('index?r=team/update&id='.$modelId);
    }

    /**
     * Finds the Team model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Team the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

}
