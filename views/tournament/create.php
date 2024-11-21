<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\tournament_event\Tournament */
/* @var $modelSquads \app\models\tournament_event\forms\SquadForm */
/* @var $schools \app\models\tournament_event\School */

$this->title = 'Добавить турнир';
$this->params['breadcrumbs'][] = ['label' => 'Турнир', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tournament-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'modelSquads' => $modelSquads,
        'schools' => $schools,
    ]) ?>
</div>