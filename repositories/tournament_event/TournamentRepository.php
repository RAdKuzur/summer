<?php

namespace app\repositories\tournament_event;

use app\models\tournament_event\Tournament;
use Yii;

class TournamentRepository
{
    public SquadRepository $squadRepository;
    public function __construct(
        SquadRepository $squadRepository
    )
    {
        $this->squadRepository = $squadRepository;
    }
    public function getAll(){
        return Tournament::find()->all();
    }
    public function getById($id)
    {
        return Tournament::find()->where(['id' => $id])->one();
    }
    public function searchTournament($queryParams)
    {

        $searchModel = Yii::createObject(\app\models\tournament_event\search\SearchTournament::class);
        $dataProvider = $searchModel->search($queryParams);
        return [$searchModel,  $dataProvider];
    }
    public function searchSquad($queryParams, $tournamentId)
    {
        $searchModel = Yii::createObject(\app\models\tournament_event\search\SearchSquad::class);
        $dataProvider = $searchModel->search($queryParams , $tournamentId);
        return [$searchModel,  $dataProvider];
    }
    public function createSquads($post, $tournament_id){
        foreach ($post['SquadForm'] as $item){
            $this->squadRepository->fill($item, $tournament_id);
        }
    }
    public function initTournament($id, $name){
        $tournament = new Tournament();
        $tournament->id = $id;
        $tournament->name = $name;
        $tournament->current_tour = 0;
        $tournament->save();
    }
}