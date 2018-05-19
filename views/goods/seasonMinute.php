<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;

$this->title = 'Цены на абонементы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="season-minute-price-index">
    <?php $layoutGrid= '<div style="float: right;">'.Html::a('Добавить цену', ['create-season-minute'], ['class' => 'btn btn-success']).'<too></too>{toolbar}</div>
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
            'attribute' => 'minute',
            'label'=>'Количество минут',
            'width'=>'40%',
            'value' => function($model){
                return Html::a($model->minute, 'update-season-minute?id='.$model->id);
            },
            'format'=>'html',
        ],
        [
            'attribute' => 'price',
            'width'=>'40%',
            'value' => function($model){
                $result=0;
                if(!empty($model->price)){
                    $result = $model->price;
                }
                return Html::a(number_format($result, 0, '.', ' ').'р.', 'update-season-minute?id='.$model->id) ;
            },
            'format'=>'html'
        ],
        [
            'attribute'=>'status',
            'label'=>'Статус',
            'width'=>'15%',
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
