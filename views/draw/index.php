<?php

use app\models\tournament_event\Game;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $tournament \app\models\tournament_event\Tournament */
/* @var $games \app\models\tournament_event\Game */

$this->title = 'Жеребьёвка турнира '.$tournament->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="draw-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>
        Таблица матчей
    </h2>
    <?php
        if($games != NULL) {
            $query = Game::find()->andWhere(['tournament_id' => $tournament->id])->andWhere(['tour' => $tournament->current_tour ]);
            // add conditions that should always apply here
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'firstSquad' , 'secondSquad',
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
        }
        else { ?>
            <p>
                Жеребьёвка ещё не проводилась
            </p>
        <?php
        }

    ?>
    <?=  Html::a("Провести жеребьёвку тура № ".($tournament->current_tour + 1), Url::to(['create', 'tournamentId' => $tournament->id, 'tour' => $tournament->current_tour + 1]), ['class' => 'btn btn-success']); ?>
</div>
