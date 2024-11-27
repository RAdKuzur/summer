<?php

use app\models\tournament_event\Game;
use app\models\tournament_event\general\SquadStudent;
use app\models\tournament_event\general\SquadStudentGame;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $tournament \app\models\tournament_event\Tournament */
/* @var $games \app\models\tournament_event\Game */
/* @var $dataProvider ActiveDataProvider*/
$this->title = 'Жеребьёвка турнира "'.$tournament->name.'"';
$this->params['breadcrumbs'][] = ['label' => 'Турнир '.$tournament->name, 'url' => ['tournament/view', 'id' => $tournament->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#refreshButton").click(); }, 3000);
});
JS;
$this->registerJs($script);
?>
<div class="draw-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>
        Таблица матчей. <?php
        if ($tournament->current_tour != 0) {
            echo 'Тур ' . $tournament->current_tour;
        }
        if(count($games) == 1){
            echo '. Финал';
        }
        ?>
    </h2>

    <?php Pjax::begin(); ?>
    <?= Html::a("Refresh", ['index', 'tournamentId' => $tournament->id], ['class' => 'hidden', 'id' => 'refreshButton']) ?>
    <br>
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
                    [
                        'class' => 'yii\grid\DataColumn',
                        'label' => 'Действия', // Заголовок столбца
                        'format' => 'raw', // Чтобы использовать HTML
                        'value' => function ($model) {
                            return Html::a('Перейти к выставлению баллов', Url::to(['view', 'id' => $model->id]), ['class' => 'btn btn-primary']);
                        },
                    ],
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
    <?php Pjax::end(); ?>

    <?php
        if((count($games) == 0) || (count($games) > 1) && (count($games) != 2 ** $tournament->current_tour)) {
            echo Html::a("Провести жеребьёвку тура № ".($tournament->current_tour + 1),
                Url::to(['create', 'tournamentId' => $tournament->id, 'tour' => $tournament->current_tour + 1]),
                ['class' => 'btn btn-success']);
        }
        if(count($games) == 2 ** $tournament->current_tour){
            echo Html::a("Перейти к финалу",
                Url::to(['create', 'tournamentId' => $tournament->id, 'tour' => $tournament->current_tour + 1]),
                ['class' => 'btn btn-success']);
        }
        if(count($games) == 1){
            echo Html::a("Определить победителя",
                Url::to(['create', 'tournamentId' => $tournament->id, 'tour' => $tournament->current_tour + 1]),
                ['class' => 'btn btn-success']);
        }
     ?>
    <?=  Html::a("Сбросить жеребьевку" ,
        Url::to(['delete-draw-tournament', 'tournamentId' => $tournament->id]),
        ['class' => 'btn btn-danger']); ?>
</div>
