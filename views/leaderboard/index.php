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
/* @var $squads \app\models\tournament_event\Game */
/* @var $dataProvider ActiveDataProvider*/
$this->title = 'Матчи турнира '.$tournament->name;
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
<div class="leaderboard-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?= Html::a("Refresh", ['index', 'tournamentId' => $tournament->id], ['class' => 'hidden', 'id' => 'refreshButton']) ?>
    <br>
    <?php
        if($squads != NULL) {
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name', 'points', 'total_score', 'wins'
                ],
            ]);
        }
        else { ?>
            <p>
                Команд нет.
            </p>
        <?php
        }
    ?>
    <?php Pjax::end(); ?>
    <?=  Html::a("Перейти к турниру ",
        Url::to(['tournament/view', 'id' => $tournament->id]),
        ['class' => 'btn btn-success']); ?>

</div>
