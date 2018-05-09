<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\GoodsStok;
use app\models\GoodsStokSearch;
use yii\filters\AccessControl;
use yii\helpers\Json;

class AjaxController  extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create','goods-stok', 'stok-create'],
                        'allow' => true,
                        'roles' => ['Admin',],
                    ],
                    [
                        'actions' => ['index', 'view', 'update'],
                        'allow' => true,
                        'roles' => ['Booker',],
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

    public function actionSearchGoods()
    {
        $result = false;
        return json_encode($result);
    }

    public function actionAddGood(){
        $result = false;
        return json_encode($result);
    }

    public function actionCreateOrder(){
        $result = false;
        return json_encode($result);
    }
}