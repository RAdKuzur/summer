<?php

namespace app\controllers;

use app\models\tournament_event\forms\SquadStudentForm;
use app\models\tournament_event\general\SquadStudent;
use app\models\tournament_event\Squad;
use app\repositories\tournament_event\GameRepository;
use app\repositories\tournament_event\SquadRepository;
use app\repositories\tournament_event\SquadStudentGameRepository;
use app\repositories\tournament_event\SquadStudentRepository;
use app\repositories\tournament_event\StudentRepository;
use Yii;
use yii\web\Controller;

class SquadController extends Controller
{

    public SquadRepository $squadRepository;
    public StudentRepository $studentRepository;
    public SquadStudentRepository $squadStudentRepository;
    public SquadStudentGameRepository $squadStudentGameRepository;
    public GameRepository $gameRepository;
    public function __construct(
        $id,
        $module,
        SquadRepository $squadRepository,
        StudentRepository $studentRepository,
        SquadStudentRepository $squadStudentRepository,
        SquadStudentGameRepository $squadStudentGameRepository,
        GameRepository $gameRepository,
        $config = [])
    {
        $this->squadRepository = $squadRepository;
        $this->studentRepository = $studentRepository;
        $this->squadStudentRepository = $squadStudentRepository;
        $this->squadStudentGameRepository = $squadStudentGameRepository;
        $this->gameRepository = $gameRepository;
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
        $model = $this->squadRepository->getById($id);
        $array = $this->squadStudentRepository->searchSquadStudent($queryParams, $model->id);
        return $this->render('view', [
            'model' => $model,
            'searchModel' => $array[0],
            'dataProvider' => $array[1],
        ]);
    }
    public function actionCreate(){

    }
    public function actionUpdate($id){
        $model = $this->squadRepository->getById($id);
        $modelSquads = [Yii::createObject(SquadStudentForm::class)];
        $students = $this->studentRepository->getBySchoolId($model->school_id);
        $queryParams = Yii::$app->request->queryParams;
        $array = $this->squadStudentRepository->searchSquadStudent($queryParams, $model->id);
        $post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            $this->squadStudentRepository->createSquadStudent($post, $model->id);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model ,
            'modelSquads' => $modelSquads,
            'students' => $students,
            'searchModel' => $array[0],
            'dataProvider' => $array[1],
        ]);
    }
    public function actionDelete($id){
        $model = $this->squadRepository->getById($id);
        $squadStudents = $this->squadStudentRepository->getBySquadId($model->id);
        foreach ($squadStudents as $squadStudent){
            $squadStudentGame = $this->squadStudentGameRepository->getBySquadStudentId($squadStudent->id);
            foreach ($squadStudentGame as $item){
                $game = $this->gameRepository->getById($item->game_id);
                $item->delete();
                if($game){
                    $game->delete();
                }
            }
            $squadStudent->delete();
        }
        if($model){
            $model->delete();
        }
        return $this->redirect(['index']);
    }
    public function actionDeleteStudent($squadId){
        $squadStudent = $this->squadStudentRepository->getById($squadId);
        $squadStudentGame = $this->squadStudentGameRepository->getBySquadStudentId($squadStudent->id);
        foreach ($squadStudentGame as $item){
            $item->delete();
        }
        if($squadStudent) {
            $squadStudent->delete();
        }
        return $this->redirect(['index']);
    }
}