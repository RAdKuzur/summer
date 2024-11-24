<?php

namespace app\services\tournament_event;

use app\helpers\DrawHelper;
use app\models\tournament_event\Game;
use app\models\tournament_event\general\SquadStudent;
use app\models\tournament_event\general\SquadStudentGame;
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
    public SquadStudentRepository $squadStudentRepository;
    public function __construct(
        SquadRepository $squadRepository,
        GameRepository $gameRepository,
        SquadStudentGameRepository $squadStudentGameRepository,
        StudentRepository $studentRepository,
        SquadStudentRepository $squadStudentRepository
    )
    {
        $this->squadRepository = $squadRepository;
        $this->gameRepository = $gameRepository;
        $this->squadStudentGameRepository = $squadStudentGameRepository;
        $this->studentRepository = $studentRepository;
        $this->squadStudentRepository = $squadStudentRepository;
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
    public function createNewSquadList($tournamentId, $tour){
        /* @var $squad Squad */
        $squads = $this->gameRepository->getWinnerSquadId($tour - 1, $tournamentId);
        $arrayList = [];
        $games = $this->gameRepository->getTourAndTournamentGames($tour - 1, $tournamentId);
        foreach ($games as $game){
            $firstScore = 0;
            $secondScore = 0;
            $firstSquad = $this->squadStudentRepository->getBySquadId($game->first_squad_id);
            $secondSquad = $this->squadStudentRepository->getBySquadId($game->second_squad_id);
            foreach ($firstSquad as $squad) {
                $squadStudentGame = $this->squadStudentGameRepository->getByStudentAndGame($squad->id, $game->id);
                $firstScore += $squadStudentGame->score;
            }
            foreach ($secondSquad as $squad) {
                $squadStudentGame = $this->squadStudentGameRepository->getByStudentAndGame($squad->id, $game->id);
                $secondScore += $squadStudentGame->score;
            }
            if($secondScore > $firstScore){
                array_push($arrayList, ['id' => $game->second_squad_id, 'score' => $secondScore ]);
            }
            if($secondScore < $firstScore){
                array_push($arrayList, ['id' => $game->first_squad_id, 'score'=> $firstScore ]);
            }
        }
        $arrayList = DrawHelper::sortByScore($arrayList);
        return $arrayList;
    }
    public function checkWinners($tournamentId, $tour)
    {
        /* @var $game Game*/
        $games = $this->gameRepository->getTourAndTournamentGames($tour - 1, $tournamentId);
        foreach ($games as $game){
            $firstScore = 0;
            $secondScore = 0;
            $firstSquad = $this->squadStudentRepository->getBySquadId($game->first_squad_id);
            $secondSquad = $this->squadStudentRepository->getBySquadId($game->second_squad_id);
            foreach ($firstSquad as $squad) {
                $squadStudentGame = $this->squadStudentGameRepository->getByStudentAndGame($squad->id, $game->id);
                $firstScore += $squadStudentGame->score;
            }
            foreach ($secondSquad as $squad) {
                $squadStudentGame = $this->squadStudentGameRepository->getByStudentAndGame($squad->id, $game->id);
                $secondScore += $squadStudentGame->score;
            }
            if ($firstScore > $secondScore) {
                $game->status = 1;
            }
            if ($firstScore < $secondScore) {
                $game->status = 2;
            }
            $game->save();
        }
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