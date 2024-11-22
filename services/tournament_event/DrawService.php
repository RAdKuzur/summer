<?php

namespace app\services\tournament_event;

use app\helpers\DrawHelper;
use app\models\tournament_event\Game;
use app\models\tournament_event\general\SquadStudent;
use app\models\tournament_event\Squad;
use app\models\tournament_event\Tournament;
use app\repositories\tournament_event\GameRepository;
use app\repositories\tournament_event\SquadRepository;
use app\repositories\tournament_event\SquadStudentGameRepository;
use app\repositories\tournament_event\SquadStudentRepository;
use app\repositories\tournament_event\StudentRepository;
use app\repositories\tournament_event\TournamentRepository;

class DrawService
{
    public SquadRepository $squadRepository;
    public GameRepository $gameRepository;
    public SquadStudentGameRepository $squadStudentGameRepository;
    public StudentRepository $studentRepository;
    public function __construct(
        SquadRepository $squadRepository,
        GameRepository $gameRepository,
        SquadStudentGameRepository $squadStudentGameRepository,
        StudentRepository $studentRepository
    )
    {
        $this->squadRepository = $squadRepository;
        $this->gameRepository = $gameRepository;
        $this->squadStudentGameRepository = $squadStudentGameRepository;
        $this->studentRepository = $studentRepository;
    }
    public function createSquadList($tournamentId){
        /* @var $squad Squad */
        $squads = $this->squadRepository->getByTournamentId($tournamentId);
        $arrayList = [];
        foreach ($squads as $squad){
            array_push($arrayList, ['id' => $squad->id, 'score'=> $this->squadRepository->getScore($squad)]);
        }
        $arrayList = DrawHelper::sortByScore($arrayList);
        return $arrayList;
    }
    public function createNewSquadList($tournamentId){
        /* @var $squad Squad */
        $squads = $this->squadRepository->getByTournamentId($tournamentId);
        $arrayList = [];
        foreach ($squads as $squad){
            array_push($arrayList, ['id' => $squad->id, 'score'=> $this->squadRepository->getScore($squad)]);
        }
        $arrayList = DrawHelper::sortByScore($arrayList);
        return $arrayList;
    }
    public function createGames($teamList, $tour, $tournamentId){
        /* @var $game Game */
        $twoSquads = DrawHelper::splitArray($teamList);
        $firstSquad = $twoSquads[0];
        $secondSquad = $twoSquads[1];
        for($i = 0; $i < count($firstSquad); $i++){
            $data = [
                'firstSquadId' => $firstSquad[$i]['id'],
                'secondSquadId' => $secondSquad[$i]['id'],
                'tournamentId' => $tournamentId,
                'tour' => $tour,
                'status' => 0,
            ];
            $this->gameRepository->createGame($data);
            $game = $this->gameRepository->getUnigueGame($firstSquad[$i]['id'], $secondSquad[$i]['id'], $tournamentId, $tour);
            $this->squadStudentGameRepository->createSquadStudentGames($firstSquad[$i]['id'], $secondSquad[$i]['id'], $game->id);
        }
        $games = $this->gameRepository->getTourAndTournamentGames($tour, $tournamentId);
        $this->gameRepository->updateTour($tour, $tournamentId);
        return $games;
    }
    public function createStudentLists($firstSquadStudent, $secondSquadStudent)
    {
        $firstStudents = [];
        $secondStudents = [];
        /* @var $student SquadStudent */
        foreach($firstSquadStudent as $student){
            array_push($firstStudents, $this->studentRepository->getById($student->student_id));
        }
        foreach($secondSquadStudent as $student){
            array_push($secondStudents, $this->studentRepository->getById($student->student_id));
        }
        return [
            $firstStudents,
            $secondStudents
        ];
    }
}