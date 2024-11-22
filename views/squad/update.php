<?php

use app\models\tournament_event\forms\SquadForm;
use app\models\tournament_event\forms\SquadStudentForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\tournament_event\Squad */
/* @var $modelSquads SquadStudentForm */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $students \app\models\tournament_event\Student */
$this->title = 'Редактировать команду: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Команды', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
    <div class="squad-update">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= $this->render('_form', [
            'model' => $model,
            'modelSquads' => $modelSquads,
            'dataProvider' => $dataProvider,
            'students' => $students,
        ]) ?>

    </div>
<?php
