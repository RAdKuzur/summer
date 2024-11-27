<?php

namespace app\repositories\tournament_event;

use app\models\tournament_event\School;
use Yii;

class SchoolRepository
{
    public function getAll(){
        return School::find()->all();
    }
    public function getById($id){
        return School::findOne($id);
    }
    public function getByName($name){
        return School::findOne(['name'=>$name]);
    }
    public function searchSchool($queryParams)
    {
        $searchModel = Yii::createObject(\app\models\tournament_event\search\SearchSchool::class);
        $dataProvider = $searchModel->search($queryParams);
        return [$searchModel,  $dataProvider];
    }
    public function insertByName($name){
        $school = new School();
        $school->name = $name;
        $school->save();

    }
}