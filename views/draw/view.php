<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model \app\models\tournament_event\Game */
/* @var $firstDataProvider */
/* @var $secondDataProvider */
$this->title = $model->getSquadName($model->first_squad_id).' против '.$model->getSquadName($model->second_squad_id);
$this->params['breadcrumbs'][] = ['label' => 'Матч', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="game-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <h2>Состав команды <?php echo $model->getSquadName($model->first_squad_id)?> </h2>
    <?php
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'firstSquad'
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $firstDataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'score' , 'student',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);?>

    <h2>Состав команды <?php echo $model->getSquadName($model->second_squad_id)?></h2>
    <?php
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'secondSquad'
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $secondDataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'score' , 'student',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>