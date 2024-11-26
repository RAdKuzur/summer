<?php
namespace app\models\tournament_event;
use app\models\tournament_event\general\SquadStudent;
use app\repositories\tournament_event\StudentRepository;
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
 * @property int|null $tournament_score
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
            [['school_id', 'olymp_score' , 'tournament_score'], 'integer'],
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
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'school_id' => 'School ID',
            'olymp_score' => 'Баллы',
            'school' => 'Школа',
            'tournament_score' => 'Турнирные очки'
        ];
    }
    public function __toString()
    {
        return $this->surname.' '.$this->name.' '.$this->patronymic;
    }
    public function getSchool(){
        return $this->hasOne(School::class, ['id' => 'school_id']);
    }
    public function getFullFio(){
        return $this->surname.' '.$this->name.' '.$this->patronymic;
    }
    public function getSquadStudents()
    {
        return $this->hasMany(SquadStudent::class, ['student_id' => 'id']);
    }
    public function getTournamentScore(){
        $repository = new StudentRepository();
        return $repository->getTournamentScore($this->id);
    }
}