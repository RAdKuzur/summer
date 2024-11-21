<?php

namespace app\repositories\tournament_event;

use app\models\tournament_event\Squad;
use Yii;
use yii\db\Exception;

class SquadRepository
{
    /**
     * @throws Exception
     */
    public function getAll(){
        return Squad::find()->all();
    }
    public function getById($id){
        return Squad::findOne($id);
    }
    public function getByTournamentId($tournamentId){
        return Squad::find()->where(['tournament_id' => $tournamentId])->all();
    }
    public function fill($data, $tournament_id){
        /* @var $squad Squad */
        $squad = Squad::fill($data["name"], 0, $tournament_id, $data["school_id"]);
        // $squad->save();
        $command = Yii::$app->db->createCommand();
        $command->insert($squad::tableName(), $squad->getAttributes());
        $command->execute();
    }
    /*public function searchSquad($queryParams)
    {
        $searchModel = Yii::createObject(\app\models\tournament_event\search\SearchSquad::class);
        $dataProvider = $searchModel->search($queryParams);
        return [$searchModel,  $dataProvider];
    }*/
    public function searchSquadWithoutId($queryParams)
    {
        $searchModel = Yii::createObject(\app\models\tournament_event\search\SearchSquad::class);
        $dataProvider = $searchModel->searchAll($queryParams);
        return [$searchModel,  $dataProvider];
    }
}