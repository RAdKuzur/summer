<?php

namespace app\repositories\tournament_event;

use app\models\tournament_event\Squad;

class SquadRepository
{
    public function fill($data, $tournament_id){
        /* @var $squad Squad*/
        $squad = Squad::fill($data["name"], 0, $tournament_id, $data["school_id"]);
        $squad->save();
    }
}