<?php
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel \app\models\tournament_event\search\SearchStudent */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Участники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить участника', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'surname', 'name', 'patronymic', 'school', 'olymp_score',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>