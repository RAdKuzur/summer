<?php

namespace app\repositories\tournament_event;

use app\models\tournament_event\Game;
use app\models\tournament_event\Tournament;
use Yii;

class GameRepository
{
    public function getById($id){
        return Game::findOne($id);
    }
    public function getTourAndTournamentGames($tour, $tournamentId){
        return Game::find()->andWhere(['tour' => $tour])->andWhere(['tournament_id' => $tournamentId])->all();
    }
    public function getUnigueGame($firstSquadId, $secondSquadId, $tournamentId,$tour)
    {
        return Game::find()->andWhere(['tour' => $tour])->andWhere(['tournament_id' => $tournamentId])
            ->andWhere(['first_squad_id' => $firstSquadId])->andWhere(['second_squad_id' => $secondSquadId])->one();
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
}