<?php

namespace app\controllers;

use app\models\tournament_event\School;
use app\repositories\tournament_event\SchoolRepository;
use Yii;
use yii\web\Controller;

class SchoolController extends Controller
{
    public SchoolRepository $schoolRepository;
    public function __construct(
        $id,
        $module,
        SchoolRepository $schoolRepository,
        $config = [])
    {
        $this->schoolRepository = $schoolRepository;
        parent::__construct($id, $module, $config);
    }
    public function actionIndex(){
        $queryParams = Yii::$app->request->queryParams;
        $array = $this->schoolRepository->searchSchool($queryParams);
        return $this->render('index', [
            'searchModel' => $array[0],
            'dataProvider' => $array[1],
        ]);
    }
    public function actionCreate(){
        $model = new School();
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model ,
        ]);
    }
    public function actionView($id)
    {
        $queryParams = Yii::$app->request->queryParams;
        return $this->render('view', [
            'model' => $this->schoolRepository->getById($id),
        ]);
    }
    public function actionUpdate($id){
        $model = $this->schoolRepository->getById($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model ,
        ]);
    }
    public function actionDelete($id){
        $this->schoolRepository->getById($id)->delete();
        return $this->redirect(['index']);
    }
}