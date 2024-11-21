<?php

namespace app\controllers;

use app\models\tournament_event\Squad;
use app\repositories\tournament_event\SquadRepository;
use app\repositories\tournament_event\StudentRepository;
use Yii;
use yii\web\Controller;

class SquadController extends Controller
{

    public SquadRepository $squadRepository;
    public StudentRepository $studentRepository;
    public function __construct(
        $id,
        $module,
        SquadRepository $squadRepository,
        StudentRepository $studentRepository,
        $config = [])
    {
        $this->squadRepository = $squadRepository;
        $this->studentRepository = $studentRepository;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(){
        $queryParams = Yii::$app->request->queryParams;
        $array = $this->squadRepository->searchSquadWithoutId($queryParams);
        return $this->render('index', [
            'searchModel' => $array[0],
            'dataProvider' => $array[1],
        ]);
    }
    public function actionView($id){
        $queryParams = Yii::$app->request->queryParams;
        $array = $this->studentRepository->searchStudent($queryParams);
        return $this->render('view', [
            'searchModel' => $array[0],
            'dataProvider' => $array[1],
        ]);

    }
}