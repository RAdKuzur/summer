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
<style>
    .breadcrumb {
        background-color: rgba(255, 255, 255, 0.5); /* Прозрачный белый фон */
        padding: 10px; /* Добавьте нужные отступы */
        border-radius: 5px; /* Скругленные углы (по желанию) */
    }
    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th, .table td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
        font-family: Century Gothic, serif;
    }
    .table th {
        background-color: orange; /* Зеленый фон для заголовка */
        color: white; /* Белый цвет текста в заголовке */
    }
    .table a {
        text-decoration: none;
        color: white;
    }
    .table tr:nth-child(even) {background-color: #f2f2f2;} /* Зебра для строк */
    .table tr:hover {background-color: #ddd;} /* Подсветка строки при наведении */
</style>
<?php
$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ 
        $.pjax.reload({container: '#pjax-container', timeout: 2000});
    }, 3000);
});
JS;
$this->registerJs($script);
?>
<body background="back2.png" style="background-size: 100% 100%;">
<div class="leaderboard-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['id' => 'pjax-container']); ?>
    <?= Html::a("Refresh", ['index', 'tournamentId' => $tournament->id], ['class' => 'hidden', 'id' => 'refreshButton']) ?>
    <br>
    <?php
        if($squads != NULL) {
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['class' => 'table table-striped table-bordered'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    [
                        'attribute' => 'win',
                        'label' => 'Результаты', // Название объединенного столбца
                        'value' => function ($model) {
                            return $model->getWins() . '-' . $model->getLoses();
                        },
                        'format' => 'raw', // Разрешает форматирование HTML
                    ],
                    'points',
                ]
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
