<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SeasonTiketsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Абонементы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="season-tikets-index">
    <?php $layoutGrid= '<div style="float: right;">'.Html::a('Добавить абоненмент', ['create-tiket'], ['class' => 'btn btn-success']).'<too></too>{toolbar}</div>
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
            'attribute' => 'tiket_id',
            'label'=>'Номер абонемента',
            'width'=>'30%',
            'value' => function($model){
                return Html::a($model->tiket_id, 'update-tiket?id='.$model->id);
            },
            'format'=>'html',
        ],
        [
            'attribute' => 'price',
            'width'=>'10%',
            'value' => function($model){
                $result=0;
                if(!empty($model->price)){
                    $result = $model->price;
                }
                return number_format($result, 0, '.', ' ').'р.';
            },
        ],
        [
            'attribute' => 'minute_all',
            'label'=>'Куплено минут',
            'width'=>'10%',
            'value' => function($model){
                return $model->minute_all;
            },
            'format'=>'html',
        ],
        [
            'attribute' => 'minute_balance',
            'label'=>'Текущий баланс',
            'width'=>'10%',
            'value' => function($model){
                return $model->minute_balance;
            },
            'format'=>'html',
        ],
        [
            'label' => 'Срок действия',
            'width'=>'20%',
            'value' => function($model){
                $result='';
                if(!empty($model->create_at) && !empty($model->exp_at)){
                    $result = Date('d.m.Y', strotime($model->create_at)).' - '.Date('d.m.Y',strtotime($model->exp_at));
                }
                return $result;
            },
            'filter'=>false,
            'format'=>'html'
        ],
        [
            'attribute'=>'comment',
            'label'=>'Коментарий',
            'width'=>'15%',
            'value' => function($model){
                $result = '';
                if(!empty($model->comment)){
                    $result = $model->comment;
                }
                return $result ;
            },
            'format'=>'html'
        ],
        [
            'attribute'=>'status',
            'label'=>'Статус',
            'width'=>'5%',
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
