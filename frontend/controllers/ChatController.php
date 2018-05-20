<?php

namespace frontend\controllers;

use common\models\User;
use Yii;
use app\models\Genre;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ChatController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('index', []);
    }
}
