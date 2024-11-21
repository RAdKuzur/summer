<?php

namespace app\repositories\tournament_event;

use app\models\tournament_event\School;

class SchoolRepository
{
    public function getAll(){
        return School::find()->all();
    }
}