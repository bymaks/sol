<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\editable\Editable;
use kartik\form\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderShopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Продажа товаров';
$this->params['breadcrumbs'][] = $this->title;
?>

<h4><?= Html::encode($this->title)?>. Сформирован <?=date('d.m.Y H:i');?></h4>
<div class="order-shop-index">
    <div class="calendar-fast">
        <a class="dashed" href="<?=Url::to(['/report/items', 'dateStart' => Date("Y-m-d"), 'dateEnd' => Date("Y-m-d")]);?>">Сегодня</a> |
        <a class="dashed" href="<?=Url::to(['/report/items', 'dateStart' => Date('Y-m-d', strtotime('-1 day')), 'dateEnd' => Date('Y-m-d', strtotime('-1 day'))]);?>">Вчера</a> |
        <a class="dashed" href="<?=Url::to(['/report/items', 'dateStart' => Date('Y-m-d', strtotime('-2 day')), 'dateEnd' => Date('Y-m-d', strtotime('-2 day'))]);?>">Позавчера</a> |
        <a class="dashed" href="<?=Url::to(['/report/items', 'dateStart' => Date('Y-m-d', strtotime('-1 week')), 'dateEnd' => Date('Y-m-d')]);?>">Прош. неделя</a> |
        <a class="dashed" href="<?=Url::to(['/report/items', 'dateStart' => Date('Y-m-d', strtotime('-1 month')), 'dateEnd' => Date("Y-m-d")]);?>">Прош. месяц</a>
    </div>
    <?php
    $form = ActiveForm::begin([
        'id' => 'form-vertical',
        'type' => ActiveForm::TYPE_VERTICAL,
        'method' => 'get',
    ]);?>
    <div class="form-group">

        <?= DatePicker::widget([
            'name' => 'dateStart',
            'value' => isset($params['dateStart'])? date('d.m.Y',strtotime($params['dateStart'])): '',
            'type' => DatePicker::TYPE_RANGE,
            'name2' => 'dateEnd',
            'value2' =>  isset($params['dateEnd'])? date('d.m.Y',strtotime($params['dateEnd'])): '',
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'dd.mm.yyyy',
            ],
            'layout' => '<span class="input-group-addon">с</span>
            {input1}
            <span class="input-group-addon">по</span>
            {input2}'
        ]);?>
    </div>
    <div class="form-group row">
        <div class="col-sm-4">
            <?=Html::label('Точка продаж')?><br>
            <?=Html::dropDownList('Shop[id]', (!empty($params['Shop']['id'])?$params['Shop']['id']:NULL), ArrayHelper::map(\app\models\Shop::find()->where(['status'=>1])->asArray()->all(), 'id', 'name'), ['prompt'=>'Точка не выбрана', 'class'=>'form-control col-md-6', 'id'=>'ShopId'])?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="form-group" style="float: right;">
        <?=Html::submitButton('Сформировать', ['class' => 'btn btn-primary']);?>
    </div>
    <?php ActiveForm::end();?>
</div>
<br>
<br>
<div class="order-shop-index">
    <?php

    $colorPluginOptions =  [];

    $gridColumns[] =  [
        'class'=>'kartik\grid\SerialColumn',
        'width'=>'36px',
        'header'=>'',
    ];
    $gridColumns[] =  [
        'attribute' => 'goodName',
        'label' => 'Товары',
        'width'=>'30%',
        'value' => function($model){
            $result = $model->goodName;
            if($model->stok==-999){
                $result = 'Абонименты '.$model->goodName;
            }
            return $result;
        },

        'vAlign' => 'middle',
        'format'=>'html'
    ];
    $gridColumns[] =  [
        'attribute' => 'saleCount',
        'label' => 'Количество',
        'width'=>'30%',
        'value' => function($model){
            return number_format($model->saleCount, 0, '.', ' ').' шт.';
        },
        'pageSummary'=>function ($summary, $data, $widget) use ($params) {
            $summ =0;
            foreach ($data as $val){
                $summ += intval(preg_replace('/[^\d]+/','',$val));
            }
            $mintesAll = \app\models\OrderShop::find()->where(['between', 'order_shop.create_at',Date('Y-m-d 00:00:00', strtotime($params['dateStart'])), Date('Y-m-d 23:59:59', strtotime($params['dateEnd']))])
                ->andWhere(['status'=>1])->sum('minuts');
            $mintesDiscont = \app\models\OrderShop::find()->where(['between', 'order_shop.create_at',Date('Y-m-d 00:00:00', strtotime($params['dateStart'])), Date('Y-m-d 23:59:59', strtotime($params['dateEnd']))])
                ->andWhere(['status'=>1])->sum('discont_minute');

            return 'Всего: '.number_format($summ, 0, '.', ' ').' шт. <br>'
                .'Минуты за деньги: '.number_format(($mintesAll-$mintesDiscont), 0, '.', ' ').'<br>'
                .'Минуты по Аб: '.number_format($mintesDiscont, 0, '.', ' ')
                ;
        },
        'pageSummaryFunc'=>GridView::F_SUM,
        'mergeHeader'=>true,
        'filter'=>false,
        'vAlign' => 'middle',
        'format'=>'html'
    ];
    $gridColumns[] =  [
        'attribute' => 'summSell',
        'label' => 'Сумма',
        'width'=>'30%',
        'value' => function($model){
            return number_format($model->summSell, 0, '.', ' ').' р.';
        },
        'pageSummary'=>function ($summary, $data, $widget)  use ($params) {
            $summ =0;
            foreach ($data as $val){
                $summ += intval(preg_replace('/[^\d]+/','',$val));
            }

            $summarySells = \app\models\OrderItem::find()
                ->select([
                    'minuteSum'=>'(sum(order_shop.minuts) - sum(order_shop.discont_minute))*order_item.good_price',
                ])
                ->from('order_shop, order_item, goods')
                ->where(['between', 'order_shop.create_at',Date('Y-m-d 00:00:00', strtotime($params['dateStart'])), Date('Y-m-d 23:59:59', strtotime($params['dateEnd']))])
                ->andWhere(['order_shop.status'=>1])
                ->andWhere('order_item.order_shop_id = order_shop.id and  
		                    goods.id = order_item.good_id')
                ->andWhere(['goods.category_id'=>Yii::$app->params['categoryMinut']])
                ->asArray()->one();
            return 'Всего: '.number_format($summ, 0, '.', ' ').' р. <br>'
                    .'Минут: ' . (!empty($summarySells)?$summarySells['minuteSum']:0).'<br>'
                    .'Доп товары: '.number_format(($summ-(!empty($summarySells)?$summarySells['minuteSum']:0)), 0, '.', ' ').' р.';
        },
        'pageSummaryFunc'=>GridView::F_SUM,
        'mergeHeader'=>true,
        'filter'=>false,
        'vAlign' => 'middle',
        'format'=>'html'
    ];
    $gridColumns[] =  [
        'attribute' => 'stok',
        'label' => 'Остаток',
        'width'=>'30%',
        'value' => function($model){
            $result = number_format($model->stok, 0, '.', ' ').' шт.';
            if($model->stok==-999){
                $result = 'Неограничено';
            }
            return $result;
        },
        'mergeHeader'=>true,
        'filter'=>false,
        'vAlign' => 'middle',
        'format'=>'html'
    ];




    $layoutGrid= '
        <div style="float: right"> {toolbar}</div>
        {summary}
        <div class="clearfix"></div>
        {items}
        <div class="clearfix"></div>
        {pager}
        ';

    echo GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'columns'=>$gridColumns,
        'layout' => $layoutGrid,
        'responsive'=>false,
        'responsiveWrap'=>false,
//        'tableOptions' => [
//            'class' => 'table table-striped table-bordered',//mobile
//        ],
        //'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        //'responsive'=>false,
        //'responsiveWrap'=>false,

        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'pjax'=>true, // pjax is set to always true for this demo
        // set your toolbar
        'toolbar'=> [
//            ['content'=>
//                Html::a('Add Card', ['create'], ['class' => 'btn btn-success']),
//                //Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title'=>Yii::t('kvgrid', 'Add Book'), 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
//                //Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>Yii::t('card', 'Reset Grid')])
//            ],
            '{export}',
            '{toggleData}',
        ],
        // set export properties
        'export'=>[
            'fontAwesome'=>true
        ],
        // parameters from the demo form
        'panel'=>[
            //'type'=>GridView::TYPE_PRIMARY,
            //'heading'=>$heading,
        ],
        'showPageSummary'=>true,
        'persistResize'=>false,
        //'exportConfig'=>$exportConfig,
    ]);


    ?>
</div>
