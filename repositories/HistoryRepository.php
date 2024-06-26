<?php
namespace app\repositories;

use app\models\PartyPersonal;
use app\models\PartyTeam;
use app\models\PersonalOffset;
use app\models\Team;
use app\models\History;
use http\Exception\RuntimeException;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\SiClick;
use app\services\SiteService;
class HistoryRepository
{
    public function siteWriteHistory($score, $party_team_id){
        $history = Yii::createObject(History::class);
        $history->dataset($score, $party_team_id);
        $this->saveModel($history);
    }
    public function saveModel(History $model)
    {
        if (!$model->save()) {
            throw new NotFoundHttpException('The model cannot be saved');
        }

    }



}