<?php
namespace app\models\tournament_event;
use Yii;
/**
 * This is the model class for table "student".
 *
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @property int $school_id
 * @property int $olymp_score
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['surname', 'name', 'patronymic', 'school_id', 'olymp_score'], 'required'],
            [['school_id', 'olymp_score'], 'integer'],
            [['surname', 'name', 'patronymic'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'surname' => 'Surname',
            'name' => 'Name',
            'patronymic' => 'Patronymic',
            'school_id' => 'School ID',
            'olymp_score' => 'Olymp Score',
        ];
    }
}