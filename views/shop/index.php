<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ShopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Точки продаж';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-index">

    <?php $layoutGrid= '<div style="float: right;">'.Html::a('Добавить точку', ['create'], ['class' => 'btn btn-success']).'<too></too>{toolbar}</div>
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
            'attribute' => 'name',
            'label'=>'Название',
            'width'=>'30%',
            'value' => function($model){
                $result = Html::a($model->name, '/shop/update?id='.$model->id);
                return $result;
            },
            'format'=>'html',
        ],
        [
            'attribute' => 'adress',
            'width'=>'5%',
            'value' => function($model){
                $result=0;
                if(!empty($model->adress)){
                    $result = $model->adress;
                }
                return $result;
            },
        ],
        [
            'attribute'=>'status',
            'label'=>'Статус',
            //'rowOptions'=>['data-label' => Yii::t('card', 'Status')],
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
