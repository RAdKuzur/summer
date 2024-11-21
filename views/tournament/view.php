<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \app\models\tournament_event\Tournament */
/* @var $searchModel \app\models\tournament_event\search\SearchSquad */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Турниры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tournament-view">
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
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name'
        ],
    ]) ?>
    <?php
    $modelId = $model->id;
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'total_score',
            'school',
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Действия', // Заголовок столбца
                'format' => 'raw', // Чтобы использовать HTML
                'value' => function ($model) {
                    global $modelId;
                    return Html::a('Внести изменения', Url::to(['update-squad', 'id' => $model->id]), ['class' => 'btn btn-success']) . ' ' .
                        Html::a('Просмотр', Url::to(['view-squad', 'id' => $model->id]), ['class' => 'btn btn-warning']) . ' ' .
                        Html::a('Удалить', Url::to(['delete-squad', 'id' => $model->id]), ['class' => 'btn btn-danger']);                },
            ],
        ],
    ]);
    ?>
</div>