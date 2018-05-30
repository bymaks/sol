<?php

namespace app\controllers;

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
                        'actions' => ['orders',],
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
        $searchModel = new OrderShopSearch();
        $dataProvider = $searchModel->search($params);

        return $this->render('orders', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params'=>$params,
        ]);
    }


    public function actionCreate()
    {
        $model = new Shop();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Shop::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
