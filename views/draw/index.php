<?php

use app\models\tournament_event\Game;
use app\models\tournament_event\general\SquadStudent;
use app\models\tournament_event\general\SquadStudentGame;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $tournament \app\models\tournament_event\Tournament */
/* @var $games \app\models\tournament_event\Game */
/* @var $dataProvider ActiveDataProvider*/
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
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'firstSquad' , 'secondSquad',
                    [
                        'class' => 'yii\grid\DataColumn',
                        'label' => 'Счёт', // Заголовок столбца
                        'format' => 'raw', // Чтобы использовать HTML
                        'value' => function ($model)  {
                            /*  @var $model Game */
                            $firstScore = 0;
                            $secondScore = 0;
                            $firstSquad = SquadStudent::find()->where(['squad_id' => $model->first_squad_id])->all();
                            $secondSquad = SquadStudent::find()->where(['squad_id' => $model->second_squad_id])->all();
                            foreach ($firstSquad as $squad) {
                                $squadStudentGame = SquadStudentGame::find()
                                    ->andWhere(['squad_student_id' => $squad->id])
                                    ->andWhere(['game_id' => $model->id])
                                    ->one();
                                $firstScore += $squadStudentGame->score;
                            }
                            foreach ($secondSquad as $squad) {
                                $squadStudentGame = SquadStudentGame::find()
                                    ->andWhere(['squad_student_id' => $squad->id])
                                    ->andWhere(['game_id' => $model->id])
                                    ->one();
                                $secondScore += $squadStudentGame->score;
                            }
                            return $firstScore.' : '.$secondScore;
                        },
                    ],
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
    <?=  Html::a("Провести жеребьёвку тура № ".($tournament->current_tour + 1),
        Url::to(['create', 'tournamentId' => $tournament->id, 'tour' => $tournament->current_tour + 1]),
        ['class' => 'btn btn-success']); ?>
</div>
