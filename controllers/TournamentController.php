<?php

namespace app\controllers;
use app\models\tournament_event\forms\SquadForm;
use app\models\tournament_event\Tournament;
use app\repositories\tournament_event\SchoolRepository;
use app\repositories\tournament_event\TournamentRepository;
use Yii;
use yii\web\Controller;

class TournamentController extends Controller
{
    public TournamentRepository $tournamentRepository;
    public SchoolRepository $schoolRepository;
    public function __construct(
        $id,
        $module,
        TournamentRepository $tournamentRepository,
        SchoolRepository $schoolRepository,
        $config = [])
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->schoolRepository = $schoolRepository;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(){
        $queryParams = Yii::$app->request->queryParams;
        $array = $this->tournamentRepository->searchTournament($queryParams);
        return $this->render('index', [
            'searchModel' => $array[0],
            'dataProvider' => $array[1],
        ]);
    }
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->tournamentRepository->getById($id),
        ]);
    }
    public function actionCreate(){
        $model = new Tournament();
        $modelSquads = [Yii::createObject(SquadForm::class)];
        $schools = $this->schoolRepository->getAll();
        $post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            $this->tournamentRepository->createSquads($post, $model->id);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model, 'modelSquads' => $modelSquads, 'schools' => $schools]);
    }
    public function actionUpdate($id){
        $model = $this->tournamentRepository->getById($id);
        $modelSquads = [Yii::createObject(SquadForm::class)];
        $schools = $this->schoolRepository->getAll();
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', ['model' => $model , 'modelSquads' => $modelSquads, 'schools' => $schools]);
    }
    public function actionDelete($id){
        $this->tournamentRepository->getById($id)->delete();
        return $this->redirect(['index']);
    }

}