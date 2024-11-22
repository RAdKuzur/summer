<?php

namespace app\controllers;

use app\helpers\DrawHelper;

use app\models\tournament_event\School;
use app\models\tournament_event\Tournament;
use app\repositories\tournament_event\GameRepository;
use app\repositories\tournament_event\SquadRepository;
use app\repositories\tournament_event\SquadStudentGameRepository;
use app\repositories\tournament_event\SquadStudentRepository;
use app\repositories\tournament_event\TournamentRepository;
use app\services\tournament_event\DrawService;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class DrawController extends Controller
{
    public TournamentRepository $tournamentRepository;
    public GameRepository $gameRepository;
    public DrawService $drawService;
    public SquadStudentRepository $squadStudentRepository;
    public SquadRepository $squadRepository;
    public SquadStudentGameRepository $squadStudentGameRepository;
    public function __construct(
        $id,
        $module,
        TournamentRepository $tournamentRepository,
        GameRepository $gameRepository,
        DrawService $drawService,
        SquadStudentRepository $squadStudentRepository,
        SquadRepository $squadRepository,
        SquadStudentGameRepository $squadStudentGameRepository,
        $config = [])
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->gameRepository = $gameRepository;
        $this->drawService = $drawService;
        $this->squadStudentRepository = $squadStudentRepository;
        $this->squadRepository = $squadRepository;
        $this->squadStudentGameRepository = $squadStudentGameRepository;
        parent::__construct($id, $module, $config);
    }
    public function actionIndex($tournamentId){

        $tournament = $this->tournamentRepository->getById($tournamentId);
        $games = $this->gameRepository->getTourAndTournamentGames($tournament->current_tour, $tournamentId);

        return $this->render('index', ['tournament' => $tournament, 'games' => $games]);
    }
    public function actionCreate($tournamentId, $tour){
        /* @var $tournament Tournament */
        $tournament = $this->tournamentRepository->getById($tournamentId);
        if($tour == 1) {
            $teamList = $this->drawService->createSquadList($tournamentId);
        }
        else {
            $teamList = $this->drawService->createNewSquadList($tournamentId);
        }
        if (!DrawHelper::isPowerOfTwo(count($teamList))) {
            var_dump('Ошибка, колво команд не равно 2^n');
        }
        $squads = $this->drawService->createGames($teamList, $tour, $tournamentId);
        return $this->redirect(['index',
            'tournamentId' => $tournament->id,
        ]);
    }
    public function actionView($id){
        $model = $this->gameRepository->getById($id);
        $firstSquad = $this->squadRepository->getById($model->first_squad_id);
        $secondSquad = $this->squadRepository->getById($model->second_squad_id);
        $firstDataProvider = $this->squadStudentGameRepository->getByGameId($model->id);
        $secondDataProvider = $this->squadStudentGameRepository->getByGameId($model->id);
        //$studentLists = $this->drawService->createStudentLists($firstSquadStudent, $secondSquadStudent);
        $firstDataProvider = new ActiveDataProvider([
            'query' => $this->squadStudentGameRepository->getByGameId($model->id),
        ]);
        $secondDataProvider = new ActiveDataProvider([
            'query' => $this->squadStudentGameRepository->getByGameId($model->id),
        ]);
        return $this->render('view', [
            'model' => $model,
            'firstDataProvider' => $firstDataProvider,
            'secondDataProvider' => $secondDataProvider,
        ]);
    }
}