<?php
/* @var $model \app\models\tournament_event\Student  */
/* @var $schools \app\models\tournament_event\School  */
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="student-form">
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']);?>
    <?= $form->field($model, 'surname')->textInput(['maxlength' => true])->label('Фамилия') ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Имя') ?>
    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true])->label('Отчество') ?>
    <?php
    $params = [
        'prompt' => '---',
        'class' => 'form-control'
    ];
    echo $form->field($model, "school_id")->dropDownList(ArrayHelper::map($schools, 'id', 'name'), $params)->label('Школа'); ?>
    <?= $form->field($model, 'olymp_score')->textInput(['maxlength' => true])->label('Количество баллов') ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

