<?php

namespace app\controllers;

use app\models\tournament_event\Student;
use app\repositories\tournament_event\SchoolRepository;
use app\repositories\tournament_event\StudentRepository;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class StudentController extends Controller
{
    public StudentRepository $studentRepository;
    public SchoolRepository $schoolRepository;
    public function __construct(
        $id,
        $module,
        StudentRepository $studentRepository,
        SchoolRepository $schoolRepository,
        $config = [])
    {
        $this->studentRepository = $studentRepository;
        $this->schoolRepository = $schoolRepository;
        parent::__construct($id, $module, $config);
    }
    public function actionIndex(){
        $queryParams = Yii::$app->request->queryParams;
        $array = $this->studentRepository->searchStudent($queryParams);
        return $this->render('index', [
            'searchModel' => $array[0],
            'dataProvider' => $array[1],
        ]);
    }
    public function actionCreate(){
        $model = new Student();
        $schools = $this->schoolRepository->getAll();
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model ,
            'schools' => $schools,
        ]);
    }
    public function actionView($id)
    {
        $queryParams = Yii::$app->request->queryParams;
        return $this->render('view', [
            'model' => $this->studentRepository->getById($id),
        ]);
    }
    public function actionUpdate($id){
        $model = $this->studentRepository->getById($id);
        $schools = $this->schoolRepository->getAll();
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model ,
            'schools' => $schools,
        ]);
    }
    public function actionDelete($id){
        $this->studentRepository->getById($id)->delete();
        return $this->redirect(['index']);
    }
}