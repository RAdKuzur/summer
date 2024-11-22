<?php

namespace app\models\tournament_event\search;

use app\models\tournament_event\general\SquadStudent;
use yii\data\ActiveDataProvider;

class SearchSquadStudent extends SquadStudent
{
    public function search($params ,$squad_id){
        $query = SquadStudent::find()->where(['squad_id' => $squad_id]);
        $dataProvider = new  ActiveDataProvider([
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
        //$query->andFilterWhere(['like', 'name', $this->squad_id]);
        return $dataProvider;
    }
}