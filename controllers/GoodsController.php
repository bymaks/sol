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


class GoodsController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new GoodsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Goods();

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
        if (($model = Goods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGoodsStok()
    {
        $searchModel = new GoodsStokSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (!empty(Yii::$app->request->post('hasEditable'))) {
            $request = Yii::$app->request->post();

            if(Yii::$app->user->can('Booker') || Yii::$app->user->can('Manager')){
                $goodStok = GoodsStok::find()->where(['id'=>Yii::$app->request->post('editableKey')])->one();
                if(!empty($goodStok)){
                    if($goodStok->load($request) && $goodStok->save(true)){
                        $output = $goodStok->good_count;
                        $message = 'Сохранено';
                    }
                    else{
                        $output = ''; $message = 'Не сохранено';
                    }
                }
                else {
                    $output = ''; $message = 'Не найден товар';
                }
            }
            else{
                $output = ''; $message = 'Не хватает прав';
            }

            $out = Json::encode(['output'=>$output, /*'message'=>$message*/]);
            echo $out;
            return;
        }
        return $this->render('goodsStok', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStokCreate($id = null, $search = false){

        $model = new GoodsStok();
        $goods = false;
        $id_changed =Yii::$app->request->post('id_changed');

        if(intval($id_changed)==1 || empty($search)){
            if ($model->load(Yii::$app->request->post()) && $model->save(true)) {
                return $this->redirect(['goods-stok', 'id' => $model->id]);//TODO редирект на грид
            }
        }

        if(!empty($search)){
            $goods = Goods::find() ->where(['like', 'name', $search])->orWhere(['like', 'vendor_code', $search])->all();
        }

        return $this->render(
            'stokCreate', [
                'model' => $model,
                'search'=>$search,
                'search_output' => $goods,
            ]
        );



    }
}
