<?php

use app\models\tournament_event\general\SquadStudentGame;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
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
<div class="draw-view">
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
            'firstSquad',
        ],
    ]);
    echo GridView::widget([
        'dataProvider' => $firstDataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'student',
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Очки',
                'value' => function ($squadStudent) use ($model) {
                    $item = SquadStudentGame::find()
                        ->andWhere(['squad_student_id' => $squadStudent->id])
                        ->andWhere(['game_id' => $model->id])
                        ->one();
                    return $item->score;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Баллы', // Заголовок столбца
                'format' => 'raw', // Чтобы использовать HTML
                'value' => function ($squadStudent) use ($model) {
                    $item = SquadStudentGame::find()
                        ->andWhere(['squad_student_id' => $squadStudent->id])
                        ->andWhere(['game_id' => $model->id])
                        ->one();
                    return
                        Html::a('+2', Url::to(['plus-score', 'id' => $item->id, 'score' => 2, 'gameId' => $model->id]), ['class' => 'btn btn-success']) . ' ' .
                        Html::a('-2', Url::to(['minus-score', 'id' => $item->id, 'score' => 2, 'gameId' => $model->id]), ['class' => 'btn btn-danger']) . ' ';
                },
            ],
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
            'student',
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Очки',
                'value' => function ($squadStudent) use ($model) {
                    $item = SquadStudentGame::find()
                        ->andWhere(['squad_student_id' => $squadStudent->id])
                        ->andWhere(['game_id' => $model->id])
                        ->one();
                    return $item->score;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Баллы', // Заголовок столбца
                'format' => 'raw', // Чтобы использовать HTML
                'value' => function ($squadStudent) use ($model) {
                    $item = SquadStudentGame::find()
                        ->andWhere(['squad_student_id' => $squadStudent->id])
                        ->andWhere(['game_id' => $model->id])
                        ->one();
                    return
                        Html::a('+2', Url::to(['plus-score', 'id' => $item->id, 'score' => 2, 'gameId' => $model->id]), ['class' => 'btn btn-success']) . ' ' .
                        Html::a('-2', Url::to(['minus-score', 'id' => $item->id, 'score' => 2, 'gameId' => $model->id]), ['class' => 'btn btn-danger']) . ' ';
                },
            ],
        ],
    ]);
    ?>
    <div class="save-button">
        <?= Html::a('Сохранить', Url::to(['update', 'id' => $model->id]), ['class' => 'btn btn-success']) ?>
    </div>
</div>