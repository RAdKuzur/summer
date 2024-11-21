<?php
namespace app\models\tournament_event;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tournament".
 *
 * @property int $id
 * @property string $name
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
        ];
    }
}