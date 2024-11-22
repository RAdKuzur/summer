<?php
namespace app\models\tournament_event;
use Yii;
/**
 * This is the model class for table "game".
 * @property int $id
 * @property int $first_squad_id
 * @property int $second_squad_id
 * @property int $tournament_id
 * @property int $tour
 * @property int $status
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_squad_id', 'second_squad_id', 'tournament_id', 'tour', 'status'], 'required'],
            [['first_squad_id', 'second_squad_id', 'tournament_id', 'tour', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_squad_id' => 'Команда 1',
            'second_squad_id' => 'Команда 2',
            'tournament_id' => 'Турнир',
            'tour' => 'Тур',
            'status' => 'Status',
            'firstSquad' => 'Команда 1',
            'secondSquad' => 'Команда 2'
        ];
    }
    public static function fill(
        $first_squad_id, $second_squad_id, $tournament_id, $tour, $status
    )
    {
        $entity = new Game();
        $entity->first_squad_id = $first_squad_id;
        $entity->second_squad_id = $second_squad_id;
        $entity->tournament_id = $tournament_id;
        $entity->tour = $tour;
        $entity->status = $status;
        return $entity;
    }
    public function getFirstSquad(){
        return $this->hasOne(Squad::class, ['id' => 'first_squad_id']);
    }
    public function getSecondSquad(){
        return $this->hasOne(Squad::class, ['id' => 'second_squad_id']);
    }
    public function getSquadName($id){
        return Squad::findOne($id)->name;
    }
}