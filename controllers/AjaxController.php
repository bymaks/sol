<?php

namespace app\controllers;

use app\models\GoodsStok;
use app\models\GoodsStokSearch;
use Yii;
use app\models\Goods;
use app\models\GoodsSearch;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class AjaxController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],

            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }


    //Поиск сретификат
    public function actionSearchInput() {
        if( Yii::$app->request->post('search')) {
            $value = Yii::$app->request->post('value');
            return $value;
        }
    }
}
