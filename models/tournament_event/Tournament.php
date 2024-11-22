<?php
namespace app\models\tournament_event;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tournament".
 *
 * @property int $id
 * @property string $name
 * @property int $current_tour
 */
class Tournament extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tournament';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['current_tour'], 'integer'],
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
            'name' => 'Название турнира',
            'current_tour' => 'Текущий тур',
        ];
    }
    public function __toString()
    {
        return $this->name;
    }
}