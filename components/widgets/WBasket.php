<?php
namespace app\components\widgets;
/**
 * Created by PhpStorm.
 * User: 34max
 * Date: 09.05.2018
 * Time: 20:18
 */
use app\models\SeasonTikets;
use app\models\System;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
class WBasket extends \yii\base\Widget
{

    //данные из сесси
    public $order=NULL;


    public function init() {
        parent::init();
        if (empty($this->order)) {
            $this->order = Yii::$app->session->get('order');
        }
        //\app\models\System::mesprint($this->order);
        //\app\models\System::mesprint(Yii::$app->session->get('order'));
        //die();
    }

    public function run(){
        ?>
        <div class="row basket_result">
            <div class="col-md-6">
        <?php
        $seasonTiketId = '';
        $seasonHtml = '';
        if(!empty($this->order)){
            ?>
                <div class="goods-items-basket">
                <?php
                if(!empty($this->order['order']['items'])){
                    foreach ($this->order['order']['items'] as $item){
                        echo WBasketItem::widget(['goodId'=>$item['goodId'], 'count'=>$item['count'], 'discount'=>(!empty($item['discount'])?$item['discount']:0) ]);
                    }
                }
                ?>
                </div>

            <?php

            if(!empty($this->order['order']['seasonTiket'])){

                $seasonTiket = SeasonTikets::find()->where(['id'=>$this->order['order']['seasonTiket'], 'status'=>1])->one();
                if(!empty($seasonTiket)){
                    $seasonTiketId = $seasonTiket->tiket_id;
                    $seasonHtml = ''
                        .'<div class="center-text">'
                            .'<span class="center-text font-size-xl">Абонемент: '.$seasonTiket->tiket_id.'</span><br>'
                            .'<span class="font-size-l">Баланс минут: '.$seasonTiket->minute_balance.'</span><br>'
                            .'<span class="font-size-l">Создан: '.Date('d.m.Y', strtotime($seasonTiket->create_at)).'</span>'
                        .'</div>';
                }
            }

        }


        ?>
            </div><!--div col-md-6-->
            <div class="col-md-6">
                <div class="img form_group <?=empty($seasonHtml)?'bgimage-center':'bgimage'?>">
                    <!--<img class="size-1 img-responsive" src="/images/sert_back.jpg">-->
                    <!--<!-- /input-group -->
                    <?=$seasonHtml?>

                </div>
                <div class="input-group text-center buttons ">
                    <input type="text" class="form-control" id="cert" value="<?=$seasonTiketId?>" placeholder="Введите номер сертификата">
                    <span class="input-group-btn"> <button class="btn-success btn" id="js-addCert">Подключить</button></span>
                </div>
                <div class="total">

                    <div class="total-money">
                        <!--<div><b>Цена:</b> <span class="money"> р.</span></div>-->
                        <div><b>Скидка:</b> <span class="money text-danger"><?=(!empty($this->order['order']['discont'])?$this->order['order']['discont']:0)?> р.</span></div>
                        <div><b>Минут по абонементу:</b> <span class="money text-danger"><?=(!empty($this->order['order']['discontMinute'])?$this->order['order']['discontMinute']:0)?> мин.</span></div>
                        <div class="result"><h4>Итого:</h4> <span class="money"><?=(!empty($this->order['order']['summ'])?$this->order['order']['summ']:0)?> р.</span></div>
                        <div class="text-right">
                            <button class=" btn-warning btn" id="cancelBasket" onclick="cancelBasket(<?=(!empty($this->order['order']['unique'])?"'".$this->order['order']['unique']."'":"'false'")?>);">Сброс</button>
                            <button class="btn-success btn" id="createOrder" onclick="createOrder(<?=(!empty($this->order['order']['unique'])?"'".$this->order['order']['unique']."'":"'false'")?>);">Оформить</button>
                        </div>
                        <br>


                    </div>
                </div>
            </div><!--div col-md-6-->
        </div><!--div row-->
        <?php
    }
}