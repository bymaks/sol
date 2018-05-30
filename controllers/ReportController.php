<?php

namespace app\controllers;

use app\models\OrderItemSearch;
use app\models\OrderShop;
use app\models\OrderShopSearch;
use Yii;
use app\models\Shop;
use app\models\ShopSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['orders','items'],
                        'allow' => true,
                        'roles' => ['Admin','Booker',],
                    ],
                ],
            ],
        ];
    }

    public function actionOrders()
    {
        $params = Yii::$app->request->queryParams;

        if(empty($params['dateStart']) && empty($params['dateEnd'])){
            $params['dateStart'] = Date('Y-m-d 00:00:00', strtotime('-1 week', time()));
            $params['dateEnd'] = Date('Y-m-d 23:59:59', time());
        }
        else{
            $params['dateStart'] = Date('Y-m-d 00:00:00', strtotime($params['dateStart']));
            $params['dateEnd'] = Date('Y-m-d 23:59:59', strtotime($params['dateEnd']));
        }

        $searchModel = new OrderShopSearch();
        $dataProvider = $searchModel->search($params);


        return $this->render('orders', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params'=>$params,
        ]);
    }


    public function actionItems()
    {
        $params = Yii::$app->request->queryParams;

        if(empty($params['dateStart']) && empty($params['dateEnd'])){
            $params['dateStart'] = Date('Y-m-d 00:00:00', strtotime('-1 week', time()));
            $params['dateEnd'] = Date('Y-m-d 23:59:59', time());
        }
        else{
            $params['dateStart'] = Date('Y-m-d 00:00:00', strtotime($params['dateStart']));
            $params['dateEnd'] = Date('Y-m-d 23:59:59', strtotime($params['dateEnd']));
        }

        $searchModel = new OrderItemSearch();
        $dataProvider = $searchModel->search($params);

        return $this->render('items', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params'=>$params,
        ]);
    }
}
