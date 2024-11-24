<?php
/* @var $model \app\models\tournament_event\Tournament  */
/* @var $modelSquads SquadForm */
/* @var $schools \app\models\tournament_event\School */
/* @var $dataProvider yii\data\ActiveDataProvider */
use app\models\tournament_event\forms\SquadForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="tournament-form">
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']);?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Название турнира') ?>
    <?php
    if($model->id != NULL) {
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
                    'label' => 'Actions', // Заголовок столбца
                    'format' => 'raw', // Чтобы использовать HTML
                    'value' => function ($model) {
                        global $modelId;
                        return Html::a('Внести изменения', Url::to(['squad/update', 'id' => $model->id]), ['class' => 'btn btn-success']) . ' ' .
                            Html::a('Просмотр', Url::to(['squad/view', 'id' => $model->id]), ['class' => 'btn btn-warning']) . ' ' .
                            Html::a('Удалить', Url::to(['squad/delete', 'id' => $model->id]), ['class' => 'btn btn-danger']);
                    },
                ],
            ],
        ]);
    }
    ?>
    <div class="panel-body">
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper1', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
            'widgetBody' => '.container-items1', // required: css class selector
            'widgetItem' => '.item1', // required: css class
            'limit' => 20, // the maximum times, an element can be cloned (default 999)
            'min' => 1, // 0 or 1 (default 1)
            'insertButton' => '.add-item', // css class
            'deleteButton' => '.remove-item', // css class
            'model' => $modelSquads[0],
            'formId' => 'dynamic-form',
            'formFields' => [
                'name',
            ],
        ]); ?>

        <div class="container-items1" ><!-- widgetContainer -->
            <?php foreach ($modelSquads as $i => $modelSquad): ?>
                <div class="item1 panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left"></h3>
                        <div class="pull-right">
                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <div>
                            <div class="col-xs-4">
                                <?= $form->field($modelSquad, "[{$i}]name")->textInput(['maxlength' => true])->label('Название команды') ?>
                            </div>
                            <div class="col-xs-4">
                                <?php
                                $params = [
                                    'prompt' => '---',
                                    'class' => 'form-control'
                                ];
                                echo $form
                                    ->field($modelSquad, "[{$i}]school_id")
                                    ->dropDownList(ArrayHelper::map($schools, 'id', 'name'), $params)
                                    ->label('Школа');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php DynamicFormWidget::end(); ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

