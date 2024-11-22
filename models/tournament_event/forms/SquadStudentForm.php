<?php

namespace app\models\tournament_event\forms;

use yii\base\Model;

class SquadStudentForm extends Model
{
    public $id;
    public $student_id;
    public $squad_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'squad_student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'squad_id'], 'required'],
            [['student_id', 'squad_id'], 'integer'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student ID',
            'squad_id' => 'Squad ID',
        ];
    }
}