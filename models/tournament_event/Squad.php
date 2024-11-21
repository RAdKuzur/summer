<?php

namespace app\models\tournament_event;

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
            [['total_score', 'tournament_id', 'school_id'], 'integer'],
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
            'name' => 'Name',
            'total_score' => 'Общий счёт',
            'tournament_id' => 'Tournament ID',
            'school_id' => 'School ID',
            'school' => 'Название школы',
            'tournament' => 'Турнир'
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
    public function getSchool(){
        return $this->hasOne(School::class, ['id' => 'school_id']);
    }
    public function getTournament(){
        return $this->hasOne(Tournament::class, ['id' => 'tournament_id']);
    }
}