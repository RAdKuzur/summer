<?php
/* @var $model \app\models\tournament_event\School  */
use app\models\tournament_event\forms\SquadForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="school-form">
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']);?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Название школы') ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

