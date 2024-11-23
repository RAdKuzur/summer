<?php

namespace app\repositories\tournament_event;


use app\models\tournament_event\general\SquadStudentGame;
use Yii;

class SquadStudentGameRepository
{
    public SquadRepository $squadRepository;
    public SquadStudentRepository $squadStudentRepository;
    public GameRepository $gameRepository;
    public function __construct(
        SquadStudentRepository $squadStudentRepository,
        SquadRepository $squadRepository,
        GameRepository $gameRepository
    )
    {
        $this->squadStudentRepository = $squadStudentRepository;
        $this->squadRepository = $squadRepository;
        $this->gameRepository = $gameRepository;
    }
    public function fill($squadStudentId, $gameId, $score)
    {
        /* @var $model SquadStudentGame */
        $model = SquadStudentGame::fill($squadStudentId, $gameId, $score);
        //$squad->save();
        $command = Yii::$app->db->createCommand();
        $command->insert($model::tableName(), $model->getAttributes());
        $command->execute();
    }
    public function createSquadStudentGames($firstSquadId, $secondSquadId, $gameId)
    {
        $firstSquad = $this->squadRepository->getById($firstSquadId);
        $secondSquad = $this->squadRepository->getById($secondSquadId);
        $firstSquadStudent = $this->squadStudentRepository->getBySquadId($firstSquad->id);
        $secondSquadStudent = $this->squadStudentRepository->getBySquadId($secondSquad->id);
        foreach ($firstSquadStudent as $squadStudent) {
            $this->fill($squadStudent->id, $gameId, 0);
        }
        foreach ($secondSquadStudent as $squadStudent) {
            $this->fill($squadStudent->id, $gameId, 0);
        }
    }
    public function getByGameId($gameId){
        return SquadStudentGame::find()->where(["game_id" => $gameId])->all();
    }
    public function changeScore($id, $score){
        $model = SquadStudentGame::findOne($id);
        $model->score = $model->score + $score;
        $model->save();
    }
}