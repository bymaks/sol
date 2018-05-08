<?php
/**
 * Created by PhpStorm.
 * User: 34max
 * Date: 01.05.2018
 * Time: 14:22
 */
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GoodsStokSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Остатки по точкам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-stok-index">

    <?php $layoutGrid= '<div style="float: right;">'.Html::a('Добавить остаток', ['stok-create'], ['class' => 'btn btn-success']).'<too></too>{toolbar}</div>
        {summary} 
        {items}
        {pager}
        <div class="clearfix"></div>';

    $itemsStatusDel = [
        '1' =>'Да',
        '0' =>'Нет',
    ];

    $column = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'shop_id',
            'label'=>'Название точки',
            'width'=>'30%',
            'value' => function($model){
                $result = Html::a($model->shop->name, '/shop/update?id='.$model->shop_id);
                return $result;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Shop::find()->where('1=1')->all(), 'id', 'name'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Точка продаж'],
            'format'=>'html',
        ],
        [
            'attribute' => 'good_id',
            'label'=>'Название товара',
            'width'=>'30%',
            'value' => function($model){
                $good = $model->good;
                $result = '';
                if(!empty($good)){
                    $result = Html::a($good->name, '/goods/update?id='.$model->good_id)
                        . '<br>'.$good->vendor_code. ' Цена:'. $good->price;
                }
                return $result;
            },
            'format'=>'html',
        ],
        [
            'class'=>'kartik\grid\EditableColumn',
            'attribute'=>'good_count',
            'label'=>'Количество',
            'value' => function($model){
                return $model->good_count;
            },
            'hAlign' => GridView::ALIGN_CENTER,
            'editableOptions'=> [
                'header'=>'Остаток',
                'inputType' => \kartik\editable\Editable::INPUT_HIDDEN,
                'beforeInput' => function ($form, $widget){
                    if( Yii::$app->user->can('Manager') || Yii::$app->user->can('Booker') ){
                        echo '<label>Изменение остатков</label><br>';
                        $stok = new \app\models\GoodsStok();
                        echo $form->field($stok, 'good_count')->textInput()->label('');
                    }
                    else{
                        echo 'Недостаточно прав';
                    }

                },
            ],
            'format'=>'html'
        ],
        [
            'attribute'=>'status',
            'label'=>'Статус',
            'value' => function($model){
                return $model->status ? Html::tag('span', 'Да', ['data-label'=>'Status', 'class'=>'text-success']) :  Html::tag('span', 'Нет', ['data-label'=>'Status', 'class'=>'text-danger']);
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>$itemsStatusDel,
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Статус'],
            'format'=>'raw'
        ],
    ];
    echo GridView::widget([
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'showPageSummary'=>false,
        'layout' => $layoutGrid,
        'pjax'=>false,
        'striped'=>true,
        'hover'=>true,
        'responsive'=>false,
        'responsiveWrap'=>false,
        'columns' => $column,
    ]); ?>
</div>