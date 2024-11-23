<?php

namespace app\repositories\tournament_event;

use app\models\tournament_event\Game;
use app\models\tournament_event\Squad;
use app\models\tournament_event\Tournament;
use Yii;

class GameRepository
{
    private SquadRepository $squadRepository;
    public function __construct(
        SquadRepository $squadRepository
    ){
        $this->squadRepository = $squadRepository;
    }
    public function getById($id){
        return Game::findOne($id);
    }
    public function getTourAndTournamentGames($tour, $tournamentId){
        return Game::find()->andWhere(['tour' => $tour])->andWhere(['tournament_id' => $tournamentId])->all();
    }
    public function getByTournamentId($tournamentId){
        return Game::find()->where(['tournament_id' => $tournamentId])->all();
    }
    public function getByTournamentIdQuery($tournamentId){
        return Game::find()->where(['tournament_id' => $tournamentId]);
    }
    public function getTourAndTournamentGamesQuery($tour, $tournamentId){
        return Game::find()->andWhere(['tour' => $tour])->andWhere(['tournament_id' => $tournamentId]);
    }
    public function getUnigueGame($firstSquadId, $secondSquadId, $tournamentId,$tour)
    {
        return Game::find()
            ->andWhere(['tour' => $tour])
            ->andWhere(['tournament_id' => $tournamentId])
            ->andWhere(['first_squad_id' => $firstSquadId])
            ->andWhere(['second_squad_id' => $secondSquadId])
            ->one();
    }
    public function getZeroStatuses($tour, $tournamentId)
    {
        return Game::find()
            ->andWhere(['tour' => $tour])
            ->andWhere(['tournament_id' => $tournamentId])
            ->andWhere(['status' => 0])
            ->all();
    }
    public function createGame($data){
        $squad = Game::fill($data["firstSquadId"], $data["secondSquadId"], $data["tournamentId"],  $data["tour"],$data["status"]);
        $command = Yii::$app->db->createCommand();
        $command->insert($squad::tableName(), $squad->getAttributes());
        $command->execute();
    }
    public function updateTour($tour, $tournamentId){
        $tournament = Tournament::findOne($tournamentId);
        $tournament->current_tour = $tour;
        $tournament->save();
    }
    public function getWinnerSquadId($tour, $tournamentId){
        $winners = [];
        /* @var $game Game */
        $firstWinnerGames = Game::find()
            ->andWhere(['tour' => $tour])
            ->andWhere(['tournament_id' => $tournamentId])
            ->andWhere(['status' => 1])
            ->all();
        foreach($firstWinnerGames as $game){
            $winners[] = $this->squadRepository->getById($game->first_squad_id);
        }
        $secondWinnerGames = Game::find()
            ->andWhere(['tour' => $tour])
            ->andWhere(['tournament_id' => $tournamentId])
            ->andWhere(['status' => 2])
            ->all();
        foreach($secondWinnerGames as $game){
            $winners[] = $this->squadRepository->getById($game->second_squad_id);
        }
        return $winners;
    }
}