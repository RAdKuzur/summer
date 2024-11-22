<?php

namespace app\controllers;

use app\repositories\tournament_event\GameRepository;
use yii\web\Controller;

class GameController extends Controller
{
    public GameRepository $gameRepository;
    public function __construct(
        $id,
        $module,
        GameRepository $gameRepository,
        $config = [])
    {
        $this->gameRepository = $gameRepository;
        parent::__construct($id, $module, $config);
    }
    public function actionView($id){
        $model = $this->gameRepository->getById($id);
        $this->render('view',
            [
                'model' => $model
            ]);
    }
}