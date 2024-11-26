<?php

namespace app\controllers;

use app\helpers\DrawHelper;

use app\models\tournament_event\general\SquadStudent;
use app\models\tournament_event\Squad;
use app\models\tournament_event\Tournament;
use app\repositories\tournament_event\GameRepository;
use app\repositories\tournament_event\SquadRepository;
use app\repositories\tournament_event\SquadStudentGameRepository;
use app\repositories\tournament_event\SquadStudentRepository;
use app\repositories\tournament_event\StudentRepository;
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
    public StudentRepository $studentRepository;
    public function __construct(
        $id,
        $module,
        TournamentRepository $tournamentRepository,
        GameRepository $gameRepository,
        DrawService $drawService,
        SquadStudentRepository $squadStudentRepository,
        SquadRepository $squadRepository,
        SquadStudentGameRepository $squadStudentGameRepository,
        StudentRepository $studentRepository,
        $config = [])
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->gameRepository = $gameRepository;
        $this->drawService = $drawService;
        $this->squadStudentRepository = $squadStudentRepository;
        $this->squadRepository = $squadRepository;
        $this->squadStudentGameRepository = $squadStudentGameRepository;
        $this->studentRepository = $studentRepository;
        parent::__construct($id, $module, $config);
    }
    public function actionIndex($tournamentId){
        $tournament = $this->tournamentRepository->getById($tournamentId);
        $games = $this->gameRepository->getTourAndTournamentGames($tournament->current_tour, $tournamentId);
        $query = $this->gameRepository->getTourAndTournamentGamesQuery($tournament->current_tour, $tournamentId);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index', ['tournament' => $tournament, 'games' => $games, 'dataProvider' => $dataProvider]);
    }
    public function actionCreate($tournamentId, $tour){
        /* @var $tournament Tournament */
        $tournament = $this->tournamentRepository->getById($tournamentId);
        $typeButton = 0;
        /* play-off system
        $this->drawService->checkWinners($tournamentId, $tour);
        if($tour == 1) {
            $squadList = $this->drawService->createSquadList($tournamentId);
        }
        else {
            $squadList = $this->drawService->createNewSquadList($tournamentId, $tour);
        }
        if(!$this->gameRepository->getZeroStatuses($tour - 1, $tournamentId) || $tour == 1) {
            if (!DrawHelper::isPowerOfTwo(count($squadList)) && count($squadList) != 0) {
                var_dump('Ошибка, колво команд не равно 2^n');
            }
            if(count($squadList) != 0) {
                $squads = $this->drawService->createGames($squadList, $tour, $tournamentId);
            }
        }*/
        // swiss system
        /* @var $squad Squad */
        $squads = $this->squadRepository->getByTournamentId($tournamentId);
        foreach ($squads as $squad){
            $this->squadRepository->setWins($squad);
        }
        $this->drawService->checkWinners($tournamentId, $tour);
        if($tour == 1) {
            foreach ($squads as $squad) {
                $squad->total_score = $squad->getScore();
                $squad->save();
            }
            $squadList = $this->drawService->createSwissSquad($squads);
        }
        else {
            foreach ($squads as $squad) {
                $squad->total_score = $squad->getPoints() + $squad->getScore();
                $squad->save();
            }
            $squadList = $this->drawService->createSwissSquad($squads);
        }
        $squadList = $this->drawService->createPairs($squadList, $tournamentId);
        if(2 ** $tour < count($squads)) {
            if (!$this->gameRepository->getZeroStatuses($tour - 1, $tournamentId) || $tour == 1) {
                if (!DrawHelper::isPowerOfTwo(count($squadList)) && count($squadList) != 0) {
                    var_dump('Ошибка, колво команд не равно 2^n');
                }
                if (count($squadList) != 0) {
                    $squads = $this->drawService->createSwissGames($squadList, $tour, $tournamentId);
                }
            }
        }
        else if (2 ** $tour == count($squads)) {
            if (!$this->gameRepository->getZeroStatuses($tour - 1, $tournamentId) || $tour == 1) {
                if (!DrawHelper::isPowerOfTwo(count($squadList)) && count($squadList) != 0) {
                    var_dump('Ошибка, колво команд не равно 2^n');
                }
                if (count($squadList) != 0) {
                    $squads = $this->drawService->createFinalGame($squadList, $tour, $tournamentId);
                }
            }
        }
        else if (2 ** $tour > count($squads)) {
            if (!$this->gameRepository->getZeroStatuses($tour - 1, $tournamentId) || $tour == 1) {
                if (!DrawHelper::isPowerOfTwo(count($squadList)) && count($squadList) != 0) {
                    var_dump('Ошибка, колво команд не равно 2^n');
                }
                if (count($squadList) != 0) {
                    return $this->redirect(['champ', 'tournamentId' => $tournamentId]);
                }
            }
        }
        return $this->redirect(['index',
            'tournamentId' => $tournament->id,
        ]);
    }
    public function actionChamp($tournamentId){
        $tournament = $this->tournamentRepository->getById($tournamentId);
        $tour = $tournament->current_tour;
        $championSquad = $this->gameRepository->getWinnerSquadId($tour, $tournamentId);
        $champStudents = $this->squadStudentRepository->getBySquadId($championSquad[0]->id);
        foreach ($champStudents as $champStudent) {
            $student = $this->studentRepository->getById($champStudent->student_id);
            $student->tournament_score = $student->getTournamentScore();
            $student->save();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => SquadStudent::find()
                ->select([
                    'squad_student.*',
                    'student.surname',
                    'student.name',
                    'student.patronymic',
                    'student.tournament_score'
                ])
                ->joinWith(['student', 'squadStudentGames']) // Объединяем с таблицей студентов и играми
                ->where(['squad_id' => $championSquad[0]->id])
                ->groupBy('squad_student.id'), // Группируем по идентификатору студент
            'sort' =>
                ['attributes' => [
                    'student.olymp_score',
                    'student.tournament_score'
                ],
            ],
        ]);
        return $this->render('champ', [
            'dataProvider' => $dataProvider,
            'tournament' => $tournament,
            'squad' => $championSquad[0]
        ]);
    }
    public function actionView($id){
        $model = $this->gameRepository->getById($id);
        $firstSquad = $this->squadRepository->getById($model->first_squad_id);
        $secondSquad = $this->squadRepository->getById($model->second_squad_id);
        $firstSquadStudent = $this->squadStudentRepository->getBySquadIdQuery($firstSquad->id);
        $secondSquadStudent = $this->squadStudentRepository->getBySquadIdQuery($secondSquad->id);
        $firstDataProvider = new ActiveDataProvider(['query' => $firstSquadStudent]);
        $secondDataProvider = new ActiveDataProvider(['query' => $secondSquadStudent]);
        return $this->render('view', [
            'model' => $model,
            'firstDataProvider' => $firstDataProvider,
            'secondDataProvider' => $secondDataProvider,
        ]);
    }
    public function actionPlusScore($id, $score, $gameId){
        $this->squadStudentGameRepository->changeScore($id, $score);
        $this->redirect(['view', 'id' => $gameId]);
    }
    public function actionMinusScore($id, $score, $gameId){
        $this->squadStudentGameRepository->changeScore($id, -$score);
        $this->redirect(['view', 'id' => $gameId]);
    }
    public function actionDeleteDrawTournament($tournamentId){
        /* @var $squad Squad*/
        $tournament = $this->tournamentRepository->getById($tournamentId);
        $tournament->current_tour = 0;
        $games = $this->gameRepository->getByTournamentId($tournamentId);
        foreach ($games as $game) {
            $squadStudentGames = $this->squadStudentGameRepository->getByGameId($game->id);
            foreach ($squadStudentGames as $squadStudentGame) {
                $squadStudentGame->delete();
            }
            $game->delete();
        }
        $squads = $this->squadRepository->getByTournamentId($tournamentId);
        foreach ($squads as $squad) {
            $squad->win = 0;
            $squad->total_score = 0;
            $squad->save();
        }
        $tournament->save();
        return $this->redirect(['index', 'tournamentId' => $tournamentId]);
    }
}