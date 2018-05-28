<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;

$this->title = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php
    $layoutGrid= '
        <div style="float: right;">{toolbar}</div>
        {summary} 
        {items}
        {pager}
        <div class="clearfix"></div>
        ';
    $columns =  [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute'=>'login',
            'label' => 'Логин',
            'value' => function($model){
                return $model->login;
            },
        ],
        [
            'attribute'=>'first_name',
            'label' => 'Ф.И.О.',
            'value' => function($model){
                return Html::a($model->first_name.' '.$model->second_name,'/users/update?id='.$model->id);
            },
            'format'=>'html',
        ],
        [
            'attribute'=>'phone',
            'label' => 'Телефон',
            'value' => function($model){
                return $model->phone;
            },
            'format'=>'html',
        ],
        [
            'attribute' => 'birthday',
            'label' => 'Возраст',
            'value' => function($model) {
                $return = Date('d.m.Y', strtotime($model->birthday));
                if(date('m',strtotime($model->birthday.' 00:00:00')) > date('m') || date('m',strtotime($model->birthday.' 00:00:00')) == date('m') && date('d',strtotime($model->birthday.' 00:00:00')) > date('d'))
                    $return.= ' ('. (date('Y') - date('Y',strtotime($model->birthday.' 00:00:00')) - 1) .')';
                else
                    $return.= ' ('.(date('Y') - date('Y',strtotime($model->birthday.' 00:00:00'))) .')';
                return $return;
            }
        ],
        [
            'class'=>'kartik\grid\EditableColumn',
            'attribute' => 'roleName',
            'label' => 'Роль',
            'value' => function($model) {
                $result = 'не установлена';
                if(!empty($model->role)){
                    $result = $model->role->itemName->ru_name;
                }
                return $result;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>\yii\helpers\ArrayHelper::map(\app\models\AuthItem::find()->all(),'name','ru_name'),
            'filterWidgetOptions'=>['pluginOptions'=>['allowClear'=>true]],
            'filterInputOptions'=>['placeholder'=>'Роль'],
            'format'=>'raw',
            'hAlign' => GridView::ALIGN_CENTER,
            'editableOptions'=> [
                'header'=>'баланс',
                'inputType' => Editable::INPUT_HIDDEN,
                'beforeInput' => function ($form){
                    $authA = new \app\models\AuthAssignment();
                    if(Yii::$app->user->Can('Manager')) {//может обновлять только тех у кого приоритет меньше
                        $youRole = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
                        $rolesForCurrent = array_fill_keys(\app\models\AuthAssignment::getRoleTreeName(key($youRole)), 'true');
                        foreach ($rolesForCurrent as $keyRole=>$itemRole){
                            $rolesForCurrent[$keyRole]=$keyRole;
                        }
                        //GodMode->Admin->Booker->Manager->User
                        if(key($youRole)=='Booker'){
                            unset($rolesForCurrent['Admin'], $rolesForCurrent['GodMode']);
                        }
                        elseif(key($youRole)=='Manager'){
                            unset($rolesForCurrent['Booker'], $rolesForCurrent['Admin'], $rolesForCurrent['GodMode']);
                        }
                        elseif(key($youRole)=='User'){
                            unset($rolesForCurrent['Manager'], $rolesForCurrent['Booker'], $rolesForCurrent['Admin'], $rolesForCurrent['GodMode']);
                        }
                        echo '<label>Изменение прав</label><br>';

                        echo $form->field($authA, 'item_name')->dropDownList($rolesForCurrent)->label('');
                    }
                    else{
                        echo 'Недостаточно прав';
                    }
                },
            ],
        ],
        [
            'attribute'=>'status',
            'label' => 'Статус',
            'value' => function($model){
                return $model->status ? "<span class='text-success'>Активный</span>" : "<span class='text-danger'>Не активный</span>";
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>["1"=>"Активый","0"=>"Не активный"],
            'filterWidgetOptions'=>['pluginOptions'=>['allowClear'=>true]],
            'filterInputOptions'=>['placeholder'=>'Статус'],
            'format'=>'html',
        ],
    ];
    ?>
    <?=GridView::widget([
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'showPageSummary'=>false,
        'layout' => $layoutGrid,
//        'tableOptions' => [
//            'class' => 'table table-striped table-bordered mobile'
//        ],
        'pjax'=>true,
        'striped'=>true,
        'hover'=>true,
        //'panel'=>['type'=>'primary', 'heading'=>$this->title],
        'responsive'=>false,
        'responsiveWrap'=>false,
        'toolbar' =>  [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить сотрудника', ['create'], ['class' => 'btn btn-success'])
            ],
            '{export}',
            '{toggleData}',
        ],
        'columns' => $columns,
    ]); ?>
</div>