<?php
namespace app\components\widgets;
/**
 * Created by PhpStorm.
 * User: 34max
 * Date: 09.05.2018
 * Time: 20:18
 */
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
        <?php
        if(!empty($this->order)){
            ?>
            <div class="col-md-6">
                <div class="goods-items">
                <?php
                if(!empty($this->order['order']['items'])){
                    foreach ($this->order['order']['items'] as $item){
                        echo WBasketItem::widget(['goodId'=>$item['goodId'], 'count'=>$item['count']]);
                    }
                }
                ?>
                </div>
            </div><!--div col-md-6-->
            <div class="col-md-6">
                <div class="img">
                    <img class="size-1" src="https://cdn.pixabay.com/photo/2014/08/19/14/06/coupon-421600_960_720.jpg">
                    <div class="text-center buttons"> <button class="btn-success btn">Подключить</button></div>
                </div>
                <div class="total">
                    <div class="form-group has-error">
                        <input type="text" class="form-control col-md-4" placeholder="Номер сертификат">
                        <p class="help-block ">Номер не найден</p>
                    </div>
                    <div class="total-money">
                        <div><b>Цена:</b> <span class="money"><?=(!empty($this->order['order']['itogo'])?$this->order['order']['itogo']:0)?> р.</span></div>
                        <div><b>Скидка:</b> <span class="money text-danger">-100 р.</span></div>
                        <div class="result"><h4>Итого:</h4> <span class="money">3 000 р.</span></div>
                        <div class="text-right"> <button class="btn-success btn" onclick="createOrder(<?=!empty($_SESSION['order']['unique'])?$_SESSION['order']['unique']:false?>);">Оформить</button></div>
                    </div>
                </div>
            </div><!--div col-md-6-->
            <?php
        }
        ?>
        </div><!--div row-->
        <?php
    }
}