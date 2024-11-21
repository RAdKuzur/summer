<?php

namespace app\models\tournament_event\search;

use app\models\tournament_event\Student;
use yii\data\ActiveDataProvider;

class SearchStudent extends Student
{
    public function search($params){
        $query = Student::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        return $dataProvider;
    }
}