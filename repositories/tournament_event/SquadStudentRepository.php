<?php

namespace app\repositories\tournament_event;

use app\models\tournament_event\general\SquadStudent;
use Yii;

class SquadStudentRepository
{
    public function getById(int $id){
        return SquadStudent::findOne($id);
    }
    public function getBySquadId(int $squadId){
        return SquadStudent::find()->where(['squad_id' => $squadId])->all();
    }
    public function getBySquadIdQuery(int $squadId){
        return SquadStudent::find()->where(['squad_id' => $squadId]);
    }
    public function searchSquadStudent($queryParams, $squadId)
    {
        $searchModel = Yii::createObject(\app\models\tournament_event\search\SearchSquadStudent::class);
        $dataProvider = $searchModel->search($queryParams, $squadId);
        return [$searchModel,  $dataProvider];
    }
    public function fill($item, $squadId)
    {
        /* @var $squad SquadStudent */
        $squad = SquadStudent::fill($item["student_id"], $squadId);
        // $squad->save();
        $command = Yii::$app->db->createCommand();
        $command->insert($squad::tableName(), $squad->getAttributes());
        $command->execute();
    }
    public function createSquadStudent($post, $squadId){
        foreach ($post['SquadStudentForm'] as $item){
            $this->fill($item, $squadId);
        }
    }
}