<?php

namespace app\repositories\tournament_event;

use app\models\tournament_event\general\SquadStudent;
use app\models\tournament_event\Squad;
use app\models\tournament_event\Student;
use Yii;
use yii\db\Exception;

class SquadRepository
{
    /**
     * @throws Exception
     */
    public function getAll(){
        return Squad::find()->all();
    }
    public function getById($id){
        return Squad::findOne($id);
    }
    public function getByIdQuery($id){
        return Squad::find()->where(['id' => $id]);
    }
    public function getByTournamentId($tournamentId){
        return Squad::find()->where(['tournament_id' => $tournamentId])->all();
    }
    public function getByTournamentIdQuery($tournamentId){
        return Squad::find()->where(['tournament_id' => $tournamentId]);
    }
    public function fill($data, $tournament_id){
        /* @var $squad Squad */
        $squad = Squad::fill($data["name"], 0, $tournament_id, $data["school_id"]);
        // $squad->save();
        $command = Yii::$app->db->createCommand();
        $command->insert($squad::tableName(), $squad->getAttributes());
        $command->execute();
    }
    public function searchSquadWithoutId($queryParams)
    {
        $searchModel = Yii::createObject(\app\models\tournament_event\search\SearchSquad::class);
        $dataProvider = $searchModel->searchAll($queryParams);
        return [$searchModel,  $dataProvider];
    }
    public function getScore(Squad $squad){
        $score = 0;
        $squadStudents = SquadStudent::find()->where(['squad_id' => $squad->id])->all();
        foreach ($squadStudents as $squadStudent){
            $student = Student::findOne($squadStudent->student_id);
            $score += $student->olymp_score;
        }
        return $score;
    }
    public function getTournamentScore($squadId)
    {
        $squadStudentRepository = new SquadStudentRepository();
        $gameRepository = new GameRepository($this);
        $squadStudentGameRepository = new SquadStudentGameRepository($squadStudentRepository,$this, $gameRepository);
        $squadScore = 0;
        $squadStudents = $squadStudentRepository->getBySquadId($squadId);
        foreach ($squadStudents as $squadStudent) {
            $squadStudentGames = $squadStudentGameRepository->getBySquadStudentId($squadStudent->id);
            $studentScore = 0;
            foreach ($squadStudentGames as $squadStudentGame) {
                $studentScore = $studentScore + $squadStudentGame->score;
            }
            $squadScore = $squadScore + $studentScore;
        }
        return $squadScore;
    }
    public function getWins($squadId, $tournamentId)
    {
        /* @var $squad Squad */
        $gameRepository = new GameRepository($this);
        return $gameRepository->amountSquadWins($squadId, $tournamentId);
    }
    public function getLoses($squadId, $tournamentId)
    {
        /* @var $squad Squad */
        $gameRepository = new GameRepository($this);
        return $gameRepository->amountSquadLose($squadId, $tournamentId);
    }
    public function setWins(Squad $squad)
    {
        $squad->win = $squad->getWins();
        $squad->save();
    }
    public function insertByData($name, $schoolId, $tournamentId)
    {
        $model = new Squad();
        $model->name = $name;
        $model->school_id = $schoolId;
        $model->total_score = 0;
        $model->tournament_id = $tournamentId;
        $model->win = 0;
        $model->save();
    }
}