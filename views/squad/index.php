<?php
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel \app\models\tournament_event\search\SearchSquad */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Команды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="squad-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php /*Html::a('Добавить школу', ['create'], ['class' => 'btn btn-success'])*/ ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
                'name', 'school' , 'tournament',
            ['class' => 'yii\grid\ActionColumn'],
        ],

    ]); ?>


</div>