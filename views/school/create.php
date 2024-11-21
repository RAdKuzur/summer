<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\tournament_event\School */

$this->title = 'Добавить турнир';
$this->params['breadcrumbs'][] = ['label' => 'Школы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="school-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>