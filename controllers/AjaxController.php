<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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