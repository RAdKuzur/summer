<?php

namespace app\models\tournament_event;

use app\repositories\tournament_event\GameRepository;
use app\repositories\tournament_event\SquadRepository;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "squad".
 *
 * @property int $id
 * @property string $name
 * @property int $total_score
 * @property int $tournament_id
 * @property int|null $school_id
 * @property int|null $win
 */
class Squad extends ActiveRecord
{
    public School $schoolModel;
    public Tournament $tournamentModel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'squad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'total_score', 'tournament_id'], 'required'],
            [['total_score', 'tournament_id', 'school_id', 'wins'], 'integer'],
            [['name'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название команды',
            'total_score' => 'Общий счёт',
            'tournament_id' => 'Tournament ID',
            'school_id' => 'School ID',
            'school' => 'Название школы',
            'tournament' => 'Турнир',
            'score' => 'Стартовые баллы',
            'points' => 'Очки',
            'wins' => 'Победы',
            'win' => 'Победы',
            'lose' => 'Поражения'
        ];
    }

    public static function fill(
        $name, $total_score, $tournament_id, $school_id
    ){
        $entity = new Squad();
        $entity->name = $name;
        $entity->total_score = $total_score;
        $entity->tournament_id = $tournament_id;
        $entity->school_id = $school_id;
        return $entity;
    }
    public function __toString()
    {
        return $this->name; // Предположим, что у вас есть свойство name
    }
    public function getSchool(){
        return $this->hasOne(School::class, ['id' => 'school_id']);
    }
    public function getTournament(){
        return $this->hasOne(Tournament::class, ['id' => 'tournament_id']);
    }
    public function getScore()
    {
        $repository = new SquadRepository();
        return $repository->getScore($this);
    }
    public function getPoints(){
        $repository = new SquadRepository();
        return $repository->getTournamentScore($this->id);
    }
    public function getWins(){
        $repository = new SquadRepository();
        return $repository->getWins($this->id, $this->tournament_id);
    }
    public function getLoses(){
        $repository = new SquadRepository();
        return $repository->getLoses($this->id, $this->tournament_id);
    }
}