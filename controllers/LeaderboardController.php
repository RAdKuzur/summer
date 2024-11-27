<?php

namespace app\controllers;

use app\models\tournament_event\Squad;
use app\repositories\tournament_event\GameRepository;
use app\repositories\tournament_event\SquadRepository;
use app\repositories\tournament_event\SquadStudentGameRepository;
use app\repositories\tournament_event\SquadStudentRepository;
use app\repositories\tournament_event\TournamentRepository;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class LeaderboardController extends Controller
{
    public GameRepository $gameRepository;
    public SquadRepository $squadRepository;
    public SquadStudentRepository $squadStudentRepository;
    public SquadStudentGameRepository $squadStudentGameRepository;
    public TournamentRepository $tournamentRepository;
    public function __construct(
        $id,
        $module,
        GameRepository $gameRepository,
        SquadRepository $squadRepository,
        SquadStudentRepository $squadStudentRepository,
        SquadStudentGameRepository $squadStudentGameRepository,
        TournamentRepository $tournamentRepository,
        $config = []
    )
    {
        $this->gameRepository = $gameRepository;
        $this->squadRepository = $squadRepository;
        $this->squadStudentRepository = $squadStudentRepository;
        $this->squadStudentGameRepository = $squadStudentGameRepository;
        $this->tournamentRepository = $tournamentRepository;
        parent::__construct($id, $module, $config);
    }
    public function actionIndex($tournamentId){
        /* @var $squad Squad */
        $tournament = $this->tournamentRepository->getById($tournamentId);
        $squads = $this->squadRepository->getByTournamentId($tournamentId);
        foreach ($squads as $squad){
            $this->squadRepository->setWins($squad);
        }
        $squadQuery = $this->squadRepository->getByTournamentIdQuery($tournamentId);
        $squadQuery->orderBy(['win' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $squadQuery,
            'pagination' => false,
        ]);
        return $this->render('index' ,[
            'dataProvider' => $dataProvider,
            'tournament' => $tournament,
            'squads' => $squads,
        ]);
    }
}