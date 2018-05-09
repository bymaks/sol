<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
//TODO::добавить отключение товара и отоборажение смена цены так же
?>
<div class="goods-index">
    <?php $layoutGrid= '<div style="float: right;">'.Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']).'<too></too>{toolbar}</div>
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
            'label'=>'Название товара',
            'width'=>'30%',
            'value' => function($model){
                $result = Html::a($model->name, '/goods/update?id='.$model->id);
                return $result;
            },
            'format'=>'html',
        ],
        [
            'attribute' => 'category_id',
            'label'=>'Категория товара',
            'width'=>'30%',
            'value' => function($model){
                return $model->category->name;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>\yii\helpers\ArrayHelper::map(\app\models\GoodsCategory::find()->where(['status'=>1])->all(), 'id', 'name'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Категория'],
            'format'=>'html',
        ],
        [
            'attribute' => 'price',
            'width'=>'5%',
            'value' => function($model){
                $result=0;
                if(!empty($model->price)){
                    $result = $model->price;
                }
                return number_format($result, 0, '.', ' ').'р.';
            },
        ],
        [
            'attribute'=>'show_status',
            'label'=>'Отображение',
            //'rowOptions'=>['data-label' => Yii::t('card', 'Status')],
            'value' => function($model){
                return $model->show_status ? Html::tag('span', 'Да', ['data-label'=>'Status', 'class'=>'text-success']) :  Html::tag('span', 'Нет', ['data-label'=>'Status', 'class'=>'text-danger']);
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>$itemsStatusDel,
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Отображение'],
            'format'=>'raw'
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
