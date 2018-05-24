<?php

namespace app\controllers;

use app\models\GoodsImages;
use app\models\GoodsStok;
use app\models\GoodsStokSearch;
use app\models\SeasonMinutePrice;
use app\models\SeasonMinutePriceSearch;
use app\models\SeasonTikets;
use app\models\SeasonTiketsSearch;
use app\models\File;
use Yii;
use app\models\Goods;
use app\models\GoodsSearch;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


class GoodsController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'create',
                            'upload',
                            'goods-stok',
                            'stok-create',

                            'tikets',
                            'create-tiket',
                            'update-tiket',

                            'season-minutes',
                            'create-season-minute',
                            'update-season-minute',

                        ],
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
        $modelImage = new File();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelImage' => $modelImage,
        ]);
    }

    public function actionUpload($modelId)
    {
        $model = new File();
        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstances($model, 'imageFile');
            $path = $model->upload($modelId);

            $str = str_replace('\\','',$path);
            $str = str_replace('[','',$str);
            $str = str_replace('"','',$str);
            $str = str_replace(']','',$str);

            $image = new GoodsImages();
            $image->status=1;
            $image->path=$str;
            $image->goods_id=$modelId;
            $image->save();
            return true;
        }
        return false;
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
                        $output =  $request['editableAttribute']=='status'?($goodStok->status ? Html::tag('span', 'Да', ['data-label'=>'Status', 'class'=>'text-success']) :  Html::tag('span', 'Нет', ['data-label'=>'Status', 'class'=>'text-danger'])):$goodStok->good_count;
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
                return $this->redirect(['goods-stok', 'id' => $model->id]);//редирект на грид
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

    public function actionTikets(){
        $searchModel = new SeasonTiketsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('tikets', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateTiket()
    {
        $model = new SeasonTikets();

        if ($model->load(Yii::$app->request->post())) {
            if($seasonMinute = SeasonMinutePrice::find()->where(['id'=>$model->minute_all, 'status'=>1])->one()){
                $model->minute_all = $seasonMinute->minute;
                $model->minute_balance = $seasonMinute->minute;
                $model->create_at = Date('Y-m-d H:i:s');
                $model->price = $seasonMinute->price;
                if($model->save(true)){
                    return $this->redirect(['tikets', 'SeasonTiketSearch[tiket_id]' => $model->tiket_id]);
                }
            }
        }

        return $this->render('createTiket', [
            'model' => $model,
        ]);
    }

    public function actionUpdateTiket($id)
    {
        if (($model = SeasonTikets::findOne($id)) !== null) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect('tikets' /* ['tikets','SeasonTiketsSearch[id]' => $model->id]*/);
            }

            return $this->render('updateTiket', [
                'model' => $model,
            ]);
        }
        throw new NotFoundHttpException('Страница не досутупна');
    }


    //season-minute-price
    public function actionSeasonMinutes()
    {
        $searchModel = new SeasonMinutePriceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('seasonMinute', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateSeasonMinute()
    {
        $model = new SeasonMinutePrice();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['season-minutes', 'id' => $model->id]);
        }

        return $this->render('createSeasonMinute', [
            'model' => $model,
        ]);
    }

    public function actionUpdateSeasonMinute($id)
    {
        if (($model = SeasonMinutePrice::findOne($id)) !== null) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['season-minutes', 'SeasonMinutePrice[id]' => $model->id]);
            }
            return $this->render('updateSeasonMinute', [
                'model' => $model,
            ]);
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
