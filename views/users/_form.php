<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use \kartik\datecontrol\DateControl;
//\app\models\System::mesprint(\app\models\AuthAssignment::getRoleTree('GodMode'));die();
//Справочник пола
$gender = [
    ['id' => 0, 'name'=> ' -- Выбрать пол'],
    ['id' => 1, 'name'=> 'Мужчина'],
    ['id' => 2, 'name'=> 'Женщина'],
];
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
/*elseif(key($youRole)!='GodMode'){
    $rolesForCurrent['User']='User';
}*/
//var_dump($modelRole);
//var_dump($roles);die();
////////////////////////////////////////////////////////////////////////////////////////////////////
// Начало формы
////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+79999999999',]) ?>
    <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>


    <?=$form->field($model, 'gender')->DropDownList(ArrayHelper::map($gender,'id','name'));  ?>
    <?=$form->field($model, 'shop_id')->DropDownList(ArrayHelper::map(\app\models\Shop::find()->where(['status'=>1])->all(),'id','name'));  ?>

    <?php /*= $form->field($model, 'birthday')->widget(DateControl::classname(), [
        'options' => ['placeholder' => 'День рождения'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]);*/?>

    <?php
    if(Yii::$app->user->can('Admin')  || ($model->id == Yii::$app->user->id)) {
        echo $form->field($model, 'passwordNew')->passwordInput(['maxlength' => true]);
        echo $form->field($model, 'confirmPassword')->passwordInput(['maxlength' => true]);
    }
    echo $form->field($model, 'status')->checkbox();
    if(Yii::$app->user->can('Admin')  || ($model->id == Yii::$app->user->id)) {
        if(!empty($model->role)){
            echo $form->field($model->role, 'item_name')->DropDownList($rolesForCurrent);
        }
        else{
            echo $form->field(new \app\models\AuthAssignment(), 'item_name')->DropDownList($rolesForCurrent, ['prompt'=>'--'])->label('Role');
        }
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>