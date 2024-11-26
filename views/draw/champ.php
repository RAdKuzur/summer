<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $tournament \app\models\tournament_event\Tournament */
/* @var $squad \app\models\tournament_event\Squad */
/* @var $dataProvider ActiveDataProvider*/
$this->title = 'Чемпион турнира '.$tournament->name;
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
<body background="back2.png" style="background-size: 100% 100%;">
<div class="draw-champ-index">
    <h1> Команда <?php
        echo $squad->name ?> - чемпионы турнира <?php $tournament->name ?>
    </h1>
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'student',
                'header' => 'ФИО участника', // Название столбца
            ],
            'student.olymp_score',
            'student.tournament_score'
        ],
    ]);
    ?>
</div>
