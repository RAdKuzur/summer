<?php

namespace app\models\tournament_event\forms;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "squad".
 *
 * @property int $id
 * @property string $name
 * @property int $total_score
 * @property int $tournament_id
 * @property int|null $school_id
 */
class SquadForm extends Model
{
    public $id;
    public $name;
    public $total_score;
    public $tournament_id;
    public $school_id;
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
            'total_score' => 'Total Score',
            'tournament_id' => 'Tournament ID',
            'school_id' => 'School ID',
        ];
    }
    public static function fill(
        $name, $total_score, $tournament_id, $school_id
    ){
        $entity = new static();
        $entity->name = $name;
        $entity->total_score = $total_score;
        $entity->tournament_id = $tournament_id;
        $entity->school_id = $school_id;
        return $entity;
    }
}