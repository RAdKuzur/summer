<?php

namespace app\repositories\tournament_event;

use app\models\tournament_event\Student;
use Yii;

class StudentRepository
{
    public function searchStudent($queryParams)
    {
        $searchModel = Yii::createObject(\app\models\tournament_event\search\SearchStudent::class);
        $dataProvider = $searchModel->search($queryParams);
        return [$searchModel,  $dataProvider];
    }
    public function getById($id) {
        return Student::findOne($id);
    }
    public function getByIdQuery($id) {
        return Student::find()->where(['id' => $id]);
    }
    public function getBySchoolId($schoolId) {
        return Student::find()->where(['school_id' => $schoolId])->all();
    }
    public function searchStudentWithSchool($queryParams, $schoolId)
    {
        $searchModel = Yii::createObject(\app\models\tournament_event\search\SearchStudent::class);
        $dataProvider = $searchModel->searchWithSchools($queryParams, $schoolId);
        return [$searchModel,  $dataProvider];
    }
}