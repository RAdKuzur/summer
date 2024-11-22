<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\tournament_event\Student */
/* @var $schools \app\models\tournament_event\School  */
$this->title = 'Добавить участника';
$this->params['breadcrumbs'][] = ['label' => 'Участники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'schools' => $schools,
    ]) ?>
</div>