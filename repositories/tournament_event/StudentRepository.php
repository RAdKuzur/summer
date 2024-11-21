<?php

namespace app\repositories\tournament_event;

use Yii;

class StudentRepository
{
    public function searchStudent($queryParams)
    {
        $searchModel = Yii::createObject(\app\models\tournament_event\search\SearchStudent::class);
        $dataProvider = $searchModel->search($queryParams);
        return [$searchModel,  $dataProvider];
    }
}