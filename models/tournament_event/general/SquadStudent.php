<?php

namespace app\models\tournament_event\general;
use app\models\tournament_event\Squad;
use app\models\tournament_event\Student;
use Yii;

/**
 * This is the model class for table "squad_student".
 *
 * @property int $id
 * @property int $student_id
 * @property int $squad_id
 */
class SquadStudent extends \yii\db\ActiveRecord
{
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
    public static function fill(
        $studentId, $squadId
    )
    {
        $entity = new SquadStudent();
        $entity->student_id = $studentId;
        $entity->squad_id = $squadId;
        return $entity;

    }
    public function getSquadName(){
        return $this->hasOne(Squad::class, ['id' => 'squad_id']);
    }
    public function getStudent(){
        return $this->hasOne(Student::class, ['id' => 'student_id']);
    }
    public function getSquadStudentGames()
    {
        return $this->hasMany(SquadStudentGame::class, ['squad_student_id' => 'id']);
    }
}