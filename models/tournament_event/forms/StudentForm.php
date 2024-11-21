<?php

namespace app\models\tournament_event\forms;

use yii\base\Model;

class StudentForm extends Model
{
    public $id;
    public $surname;
    public $name;
    public $patronymic;
    public $school_id;
    public $olymp_score;

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