<?php

namespace app\models\tournament_event;

use Yii;

/**
 * This is the model class for table "school".
 *
 * @property int $id
 * @property string $name
 */
class School extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school';
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
            'name' => 'Название школы',
            'score' => 'Очки'
        ];
    }
    public function __toString()
    {
        return $this->name; // Предположим, что у вас есть свойство name
    }
    public function getScore(){
        return Student::find()->where(['school_id' => $this->id])->sum('olymp_score');
    }
}