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

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>

<h4><?= Html::encode($this->title)?>. Сформирован <?=date('d.m.Y H:i');?></h4>
<div class="order-shop-index">
    <div class="calendar-fast">
        <a class="dashed" href="<?=Url::to(['/report/orders', 'dateStart' => Date("Y-m-d"), 'dateEnd' => Date("Y-m-d")]);?>">Сегодня</a> |
        <a class="dashed" href="<?=Url::to(['/report/orders', 'dateStart' => Date('Y-m-d', strtotime('-1 day')), 'dateEnd' => Date('Y-m-d', strtotime('-1 day'))]);?>">Вчера</a> |
        <a class="dashed" href="<?=Url::to(['/report/orders', 'dateStart' => Date('Y-m-d', strtotime('-2 day')), 'dateEnd' => Date('Y-m-d', strtotime('-2 day'))]);?>">Позавчера</a> |
        <a class="dashed" href="<?=Url::to(['/report/orders', 'dateStart' => Date('Y-m-d', strtotime('-1 week')), 'dateEnd' => Date('Y-m-d')]);?>">Прош. неделя</a> |
        <a class="dashed" href="<?=Url::to(['/report/orders', 'dateStart' => Date('Y-m-d', strtotime('-1 month')), 'dateEnd' => Date("Y-m-d")]);?>">Прош. месяц</a>
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
    <div class="form-group" style="float: right;">
        <?=Html::submitButton('Сформировать', ['class' => 'btn btn-primary']);?>
    </div>
    <?php ActiveForm::end();?>
</div>
<br>
<br>
<div class="order-shop-index">
<?php
    $itemsStatusDel = [
        '1' =>'Да',
        '0' =>'Нет',
    ];
    $colorPluginOptions =  [];

    $gridColumns[] =  [
        'class'=>'kartik\grid\SerialColumn',
        'width'=>'36px',
        'header'=>'',
    ];
    $gridColumns[] =  [
        'attribute' => 'shop_id',
        'label' => 'Точка продаж',
        'width'=>'30%',
        'value' => function($model){
            $result ='';
            if(!empty($model->shop)){
                $result = $model->shop->name;
            }
            return Html::a($result,'#',[]);
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(\app\models\Shop::find()->asArray()->all(), 'id','name'),
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'filterInputOptions'=>['placeholder'=>'Точка продаж'],
        'vAlign' => 'middle',
        'format'=>'html'
    ];
    $gridColumns[] =  [
        'attribute' => 'create_at',
        'width'=>'10%',
        'label' => 'Время создания',
        'value'=>function ($model, $key, $index, $widget) {
            return Date('d.m.Y H:i', strtotime($model->create_at));
        },
        'filter'=>false,
        'mergeHeader'=>true,
        'format'=>'html',
    ];
    $gridColumns[] =  [
        'attribute'=>'season_tikets_id',
        'width'=>'15%',
        'label' => 'Абонемент',
        'value'=>function ($model, $key, $index, $widget) {
            $result ='';
            if(!empty($model->seasonTikets)){
                $result = $model->seasonTikets->tiket_id;
            }
            return Html::a($result,'#',[]);
        },
        'format'=>'html',
    ];
    $gridColumns[] = [
        'class'=>'kartik\grid\ExpandRowColumn',
        'width'=>'50px',
        'value'=>function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail'=>function ($model, $key, $index, $column) use ($params){
            return Yii::$app->controller->renderPartial('_ordersItemsDetail', ['model'=>$model,]);
        },
        'headerOptions'=>['class'=>'kartik-sheet-style'],
        'expandOneOnly'=>true,
    ];
    $gridColumns[] =  [
        'attribute'=>'minuts',
        'width'=>'15%',
        'label' => 'Минуты',
        'value'=>function ($model, $key, $index, $widget) {
            $result =0;
            if(!empty($model->minuts)){
                $result = $model->minuts;
            }
            $discount = 0;
            if(!empty($model->discont_minute)){
                $discount = $model->discont_minute;
            }
            return number_format($result, 0, '.', ' ').'('.number_format($discount, 0, '.', ' ').')';
        },
        'pageSummary'=>function ($summary, $data, $widget) {
            $summ =0;
            $sumDiscount =0;
            foreach ($data as $val){
                $summ += intval(preg_replace('/[^\d]+/','',substr($val, 0, stripos($val, '('))));
                $sumDiscount += intval(preg_replace('/[^\d]+/','',substr($val, (stripos($val, '(')+1))));
            }
            return number_format($summ, 0, '.', ' ').'('.number_format($sumDiscount, 0, '.', ' ').')';
        },
        'pageSummaryFunc'=>GridView::F_SUM,
        'mergeHeader'=>true,
        'filter'=>false,
        'format'=>'html',
    ];
    $gridColumns[] =  [
        'attribute'=>'summ',
        'width'=>'15%',
        'label' => 'Сумма',
        'value'=>function ($model, $key, $index, $widget) {
            $result =0;
            if(!empty($model->summ)){
                $result = $model->summ;
            }
            $discount = 0;
            if(!empty($model->discont)){
                $discount = $model->discont;
            }
            return number_format($result, 0, '.', ' ').' р. ('.number_format($discount, 0, '.', ' ').') р.';
        },
        'pageSummary'=>function ($summary, $data, $widget) {
            $summ =0;
            $sumDiscount =0;
            foreach ($data as $val){
                $summ += intval(preg_replace('/[^\d]+/','',substr($val, 0, stripos($val, '('))));
                $sumDiscount += intval(preg_replace('/[^\d]+/','',substr($val, (stripos($val, '(')+4))));
            }
            return number_format($summ, 0, '.', ' ').'('.number_format($sumDiscount, 0, '.', ' ').')';
        },
        'pageSummaryFunc'=>GridView::F_SUM,
        'mergeHeader'=>true,
        'filter'=>false,
        'format'=>'html',
    ];
    $gridColumns[] =  [
        'attribute'=>'discontItem',
        'label'=>'Скидка',
        'width'=>'10%',
        'value' => function($model){
            $result = false;
            if(\app\models\OrderItem::find()->where(['order_shop_id'=>$model->id, 'status'=>1])->sum('discont')>0){
                $result = true;
            }
            return $result ? Html::tag('span', 'Есть', ['data-label'=>'Status', 'class'=>'text-success']) :  Html::tag('span', 'Нет', ['data-label'=>'Status', 'class'=>'text-danger']);
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>$itemsStatusDel,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'filterInputOptions'=>['placeholder'=>'Скидка'],
        'format'=>'html',
    ];
    $gridColumns[] =  [
        'attribute'=>'status',
        'label'=>'Статус',
        'width'=>'10%',
        'value' => function($model){
            return $model->status ? Html::tag('span', 'Да', ['data-label'=>'Status', 'class'=>'text-success']) :  Html::tag('span', 'Нет', ['data-label'=>'Status', 'class'=>'text-danger']);
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>$itemsStatusDel,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'filterInputOptions'=>['placeholder'=>'Статус'],
        'format'=>'html',
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
