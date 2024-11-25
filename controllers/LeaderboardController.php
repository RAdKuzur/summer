<?php

namespace app\controllers;

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
        $squadQuery = $this->squadRepository->getByTournamentIdQuery($tournamentId);
        $squadsQuery = (new \yii\db\Query())
            ->select([
                's.id AS squad_id',
                's.name AS squad_name',
                'SUM(ssg.score) AS total_score',
            ])
            ->from('squad s')
            ->leftJoin('squad_student ss', 's.id = ss.squad_id')
            ->leftJoin('squad_student_game ssg', 'ss.id = ssg.squad_student_id')
            ->groupBy(['s.id', 's.name'])
            ->orderBy(['total_score' => SORT_DESC]) // Сортировка по убыванию
            ->all();
        $tournament = $this->tournamentRepository->getById($tournamentId);
        $squads = $this->squadRepository->getByTournamentId($tournamentId);
        $dataProvider = new ActiveDataProvider([
            'query' => $squadQuery,
        ]);

        return $this->render('index' ,[
            'dataProvider' => $dataProvider,
            'tournament' => $tournament,
            'squads' => $squads,
        ]);
    }
}