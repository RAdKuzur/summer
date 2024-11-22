<?php


namespace app\models\tournament_event\general;
use app\models\tournament_event\Game;

/**
 * This is the model class for table "squad_student_game".
 *
 * @property int $id
 * @property int $squad_student_id
 * @property int $game_id
 * @property int $score
 */
class SquadStudentGame extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'squad_student_game';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['squad_student_id', 'game_id', 'score'], 'required'],
            [['squad_student_id', 'game_id', 'score'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'squad_student_id' => 'Squad Student ID',
            'game_id' => 'Game ID',
            'score' => 'Score',
        ];
    }
    public static function fill(
        $squadStudentId, $gameId, $score
    )
    {
        $entity = new static();
        $entity->squad_student_id = $squadStudentId;
        $entity->game_id = $gameId;
        $entity->score = $score;
        return $entity;
    }
    public function getGame()
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }
}
