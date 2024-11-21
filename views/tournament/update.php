<?php

use app\models\tournament_event\forms\SquadForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PersonalOffset */
/* @var $modelSquads SquadForm */
/* @var $schools \app\models\tournament_event\School */

$this->title = 'Редактировать турнир: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Турниры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
    <div class="tournament-update">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
            'modelSquads' => $modelSquads,
            'schools' => $schools,
        ]) ?>

    </div>
<?php
