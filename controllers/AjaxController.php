<?php

namespace app\controllers;

use app\components\widgets\WBasket;
use app\components\widgets\WSearchItem;
use app\models\Goods;
use app\models\OrderItem;
use app\models\OrderShop;
use app\models\SeasonTikets;
use app\models\System;
use app\models\User;
use app\models\Users;
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
                        'actions' => ['search-goods','add-good', 'remove-good', 'create-order', 'cancel-basket', 'add-season-tiket'],
                        'allow' => true,
                        'roles' => ['seller',],
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
        $params = Yii::$app->request->post();
        $result =['status'=>'false', 'message'=>'Пустая строка', 'error'=>1001, 'html'=>'', ];
        if(!empty($params['search']) && strlen($params['search'])>=3){//поиск товаров
            $goodsSearch = Goods::find()->where(['status'=>1, 'show_status'=>1])->andWhere(['like', 'name', $params['search']])->limit(4)->all();
            $shopId = Users::find()->where(['id'=>Yii::$app->user->id])->one()->shop_id;
            if(!empty($goodsSearch) && !empty($shopId)){
                //проверить количество на данном магазине
                $html = '';

                foreach ($goodsSearch as $good){
                    $goodStok = GoodsStok::find()->where(['status'=>1, 'shop_id'=>$shopId, 'good_id'=>$good->id])->andWhere(['>', 'good_count', 0])->one();
                    if(!empty($goodStok)){
                        //добавляем рендер виджета в результат
                        $html .= WSearchItem::widget(['goodId'=>$good->id]);
                    }
                }
                if(strlen($html)==0){
                    $result =['status'=>'false', 'message'=>'Ничего не найдено', 'error'=>1003, 'html'=>'', ];
                }
                else{
                    $result =['status'=>'true', 'message'=>'Найдено', 'error'=>0, 'html'=>$html, ];
                }

            }
            else{
                $result =['status'=>'false', 'message'=>'Товар не найден', 'error'=>1002, 'html'=>'', ];
            }
        }
        return json_encode($result);
    }

    public function actionAddGood(){
        /*
        session[
            order=>[
                itens=>[
                    1=>[],
                    2=>[],
                ]
                'ticket'
                'createBy'
                ''
            ]
        ]
        $session = Yii::$app->session;
        Yii::$app->session->set('order', []);
        $session = Yii::$app->session->get('order');
        var_dump($session);die();
        */
        $params = Yii::$app->request->post();
        $result =['status'=>'false', 'message'=>'Не найдено', 'error'=>2001, 'html'=>'', ];
        if(!empty($params['goodId'])){
            //найти товар посчитать количество
            ////количиство добавленого не больше чем есть на складе
            //найти в сессии и добавить
            $good = Goods::find()->where(['id'=>intval($params['goodId']), 'status'=>1, 'show_status'=>1])->one();
            $shopId = Users::find()->where(['id'=>Yii::$app->user->id])->one()->shop_id;
            if(!empty($good) && !empty($shopId)){
                // проверяем количество
                $goodStok = GoodsStok::find()->where(['status'=>1, 'shop_id'=>$shopId, 'good_id'=>$good->id])->one();
                if(!empty($goodStok) && $goodStok->good_count>0){
                    $session = Yii::$app->session->get('order');
                    $goodCountCurrent = 0;
                    if(!empty($session['order']['items'][$good->id])){
                        $goodCountCurrent = $session['order']['items'][$good->id]['count'];
                    }
                    if(($goodCountCurrent+1)<=$goodStok->good_count){
                        // добавляем к заказу
                        if(!empty($session['order']['items'][$good->id])){
                            $session['order']['items'][$good->id]['count']++;
                        }
                        else{
                            $session['order']['items'][$good->id]= [
                                'count'=>1,
                                'goodId'=>$good->id,
                            ];
                        }
                        //пересчитываем заказ
                        $session = System::refreshSummaryOrderInfo($session);
                        Yii::$app->session->set('order',$session);
                        $result =['status'=>'true', 'message'=>'Добавлен', 'error'=>0, 'html'=>WBasket::widget(['order'=>$session]), ];
                    }
                    else{
                        $result =['status'=>'false', 'message'=>'На складе больше нет', 'error'=>2003, 'html'=>'', ];
                    }
                }
                else{
                    $result =['status'=>'false', 'message'=>'Нет на складе', 'error'=>2003, 'html'=>'', ];
                }
            }
            else{
                $result =['status'=>'false', 'message'=>'Товар не найден', 'error'=>2002, 'html'=>'', ];
            }
        }
        return json_encode($result);
    }

    public function actionRemoveGood(){
        $params = Yii::$app->request->post();
        $result =['status'=>'false', 'message'=>'Не найдено', 'error'=>3001, 'html'=>'', ];
        if(!empty($params['goodId'])){
           //найти товар в сессии у уменьшить на один если но то удалить изсессии
            $session = Yii::$app->session->get('order');
            if(!empty($session)){
                $good = Goods::find()->where(['id'=>intval($params['goodId']), 'status'=>1, 'show_status'=>1])->one();
                if(!empty($good)){
                    if(!empty($session['order']['items'][$params['goodId']])){//товар в сессии есть
                        if($session['order']['items'][$params['goodId']]['count']>1){
                            $session['order']['items'][$params['goodId']]['count']--;
                        }
                        else{
                            unset($session['order']['items'][$params['goodId']]);
                        }
                        //TODO: пересчитать цену скидку все пересчитать
                        //пересчитываем заказ
                        $session = System::refreshSummaryOrderInfo($session);
                        Yii::$app->session->set('order',$session);
                        $result =['status'=>'true', 'message'=>'Удален', 'error'=>0, 'html'=>WBasket::widget(['order'=>$session]), ];
                    }
                    else{
                        $result =['status'=>'false', 'message'=>'Не найдено в корзине', 'error'=>3003, 'html'=>'', ];
                    }
                }
                else{
                    $result =['status'=>'false', 'message'=>'Товар не существует', 'error'=>2004, 'html'=>'', ];
                }
            }
            else{
                $result =['status'=>'false', 'message'=>'Не найдено в корзине', 'error'=>3002, 'html'=>'', ];
            }
        }
        return json_encode($result);
    }

    public function actionCreateOrder(){
        //перебираем сессию на наличие данных по заказу
        //создаем заказ
        //списываем деньги по ебонименту

        $params = Yii::$app->request->post();
        $result =['status'=>'false', 'message'=>'Не найдено', 'error'=>4000, 'html'=>'', ];
        $session = Yii::$app->session->get('order');

        if(!empty($params['uni']) && !empty($session['order']['unique']) && $params['uni']==$session['order']['unique']){
            //можно создавать заказ не шляпа
            $transaction = Yii::$app->db->beginTransaction();
            $user = Users::find()->where(['id'=>Yii::$app->user->id])->one();
            $session = System::refreshSummaryOrderInfo($session);// сделаем пересчет на всякий слушяай
            if(!empty($user) && !empty($user->shop_id)){
                $order = new OrderShop();
                $order->shop_id = $user->shop_id;
                $order->season_tikets_id = (!empty($session['order']['seasonTiket'])?$session['order']['seasonTiket']:NULL);
                $order->minuts = (!empty($session['order']['minuts'])?$session['order']['minuts']:0);
                $order->summ = (!empty($session['order']['summ'])?$session['order']['summ']:0);
                $order->discont = (!empty($session['order']['discont'])?$session['order']['discont']:0);
                $order->comment = (!empty($session['order']['comment'])?$session['order']['comment']:NULL);
                $order->status = 0;
                if($order->save(true) && !empty($session['order']['items'])){
                    //перебираем айтемся и добавляем их
                    $flagItem = true;
                    foreach ($session['order']['items'] as $item){
                        $goods = Goods::find()->where(['id'=>$item['goodId']])->one();
                        $orderItem = new OrderItem();
                        $orderItem->order_shop_id = $order->id;
                        $orderItem->good_id = $goods->id;
                        $orderItem->good_count = $item['count'];
                        $orderItem->good_price = $goods->price;
                        $orderItem->commis = 0;
                        $orderItem->discont =0;
                        $orderItem->status = 1;
                        if(!$orderItem->save(true)){
                            $flagItem = false;
                        }
                        unset($orderItem, $goods);
                    }
                    if($flagItem){
                        $order->status=1;
                        if($order->save(true)){
                            $transaction->commit();
                            Yii::$app->session->remove('order');
                            $result =['status'=>'true', 'message'=>'Сохранено', 'error'=>0, 'html'=>'', ];
                        }
                        else{
                            $transaction->rollBack();
                            $result =['status'=>'false', 'message'=>'Возникли ошибки', 'error'=>4001, 'html'=>'', ];
                        }
                    }
                    else{
                        $transaction->rollBack();
                        $result =['status'=>'false', 'message'=>'Произошел откат не все товары добавлены', 'error'=>4002, 'html'=>'', ];
                    }
                }
                else{
                    $transaction->rollBack();
                    $result =['status'=>'false', 'message'=>'Товары не найдены', 'error'=>4003, 'html'=>'', ];
                }
            }
            else{
                $result =['status'=>'false', 'message'=>'Авторизируйтесь', 'error'=>4005, 'html'=>'', ];
            }
        }
        return json_encode($result);
    }

    public function actionCancelBasket(){
        $params = Yii::$app->request->post();
        $result =['status'=>'false', 'message'=>'Не найдено', 'error'=>5001, 'html'=>'', ];
        $session = Yii::$app->session->get('order');
        if(!empty($params['uni']) && !empty($session['order']['unique']) && $params['uni']==$session['order']['unique']){
            Yii::$app->session->remove('order');
            $result =['status'=>'true', 'message'=>'Очищено', 'error'=>0, 'html'=>'', ];
        }
        return json_encode($result);
    }

    public function actionAddSeasonTiket(){
        $params = Yii::$app->request->post();
        $result =['status'=>'false', 'message'=>'Не найдено', 'error'=>5001, 'html'=>'', ];
        $session = Yii::$app->session->get('order');
        if(!empty($params['certificate'])){
            if($seasonTiket = SeasonTikets::find()->where(['tiket_id'=>$params['certificate'], 'status'=>1])->andWhere(['>','minute_balance',0])->one()){
                $session['order']['seasonTiket'] = $seasonTiket->id;
                $session = System::refreshSummaryOrderInfo($session);// сделаем пересчет на всякий слушяай
                Yii::$app->session->set('order', $session);
                $result =['status'=>'true', 'message'=>'Добавлен', 'error'=>0, 'html'=>WBasket::widget(['order'=>$session]), ];
            }
            else{
                $result =['status'=>'false', 'message'=>'Не найдено', 'error'=>6001, 'html'=>'', ];
            }
        }
        return json_encode($result);
    }

    //TODO: обновление баданса абонементов
    //TODO: лог транзакций абонементов

    //TODO: Оформление вида модалки по ебонементу так же как и в заказе
    //TODO: картинки на товары вывести во вобюхе и дать возможность удалять

}