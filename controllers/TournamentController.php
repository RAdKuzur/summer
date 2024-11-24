<?php

namespace app\controllers;
use app\models\tournament_event\forms\SquadForm;
use app\models\tournament_event\Tournament;
use app\repositories\tournament_event\GameRepository;
use app\repositories\tournament_event\SchoolRepository;
use app\repositories\tournament_event\SquadRepository;
use app\repositories\tournament_event\SquadStudentGameRepository;
use app\repositories\tournament_event\SquadStudentRepository;
use app\repositories\tournament_event\TournamentRepository;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class TournamentController extends Controller
{
    public TournamentRepository $tournamentRepository;
    public SchoolRepository $schoolRepository;
    public SquadRepository  $squadRepository;
    public GameRepository $gameRepository;
    public SquadStudentGameRepository $squadStudentGameRepository;
    public SquadStudentRepository $squadStudentRepository;
    public function __construct(
        $id,
        $module,
        TournamentRepository $tournamentRepository,
        SchoolRepository $schoolRepository,
        SquadRepository $squadRepository,
        GameRepository $gameRepository,
        SquadStudentGameRepository $squadStudentGameRepository,
        SquadStudentRepository $squadStudentRepository,
        $config = [])
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->schoolRepository = $schoolRepository;
        $this->squadRepository = $squadRepository;
        $this->gameRepository = $gameRepository;
        $this->squadStudentGameRepository = $squadStudentGameRepository;
        $this->squadStudentRepository = $squadStudentRepository;
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
        $queryParams = Yii::$app->request->queryParams;
        $array = $this->tournamentRepository->searchSquad($queryParams, $id);
        return $this->render('view', [
            'model' => $this->tournamentRepository->getById($id),
            'searchModel' => $array[0],
            'dataProvider' => $array[1],
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
        /* @var $model Tournament */
        $model = $this->tournamentRepository->getById($id);
        $modelSquads = [Yii::createObject(SquadForm::class)];
        $schools = $this->schoolRepository->getAll();
        $post = Yii::$app->request->post();
        $queryParams = Yii::$app->request->queryParams;
        $array = $this->tournamentRepository->searchSquad($queryParams, $id);
        if ($model->load(Yii::$app->request->post())) {
            $model->current_tour = 0;
            $model->save();
            $this->tournamentRepository->createSquads($post, $model->id);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
                'model' => $model ,
                'modelSquads' => $modelSquads,
                'schools' => $schools,
                'searchModel' => $array[0],
                'dataProvider' => $array[1],
        ]);
    }
    public function actionDeleteSquad($id)
    {
        $model = $this->squadRepository->getById($id);
        $modelId = $model->tournament_id;
        if($model){
            $model->delete();
        }
        return $this->redirect(['view', 'id' => $modelId]);
    }
    public function actionViewGame($tournamentId)
    {
        $tournament = $this->tournamentRepository->getById($tournamentId);
        $games = $this->gameRepository->getByTournamentId($tournamentId);
        $query = $this->gameRepository->getByTournamentIdQuery($tournamentId);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('view-game', [
            'dataProvider' => $dataProvider,
            'tournament' => $tournament,
            'games' => $games,
        ]);
    }
    public function actionDelete($id){
        $squads = $this->squadRepository->getByTournamentId($id);
        foreach($squads as $squad){
            $squad->delete();
        }
        $games = $this->gameRepository->getByTournamentId($id);
        foreach ($games as $game) {
            $squadStudentGames = $this->squadStudentGameRepository->getByGameId($game->id);
            foreach ($squadStudentGames as $squadStudentGame) {
                $squadStudent = $this->squadStudentRepository->getById($squadStudentGame->squad_student_id);
                if($squadStudent != NULL){
                    $squadStudent->delete();
                }
                $squadStudentGame->delete();
            }
            $game->delete();
        }
        $this->tournamentRepository->getById($id)->delete();
        return $this->redirect(['index']);
    }
}