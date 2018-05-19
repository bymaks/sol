<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use \app\models\SeasonMinutePrice;

/* @var $this yii\web\View */
/* @var $model app\models\SeasonTikets */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="season-tikets-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'tiket_id')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'minute_all')->dropDownList(ArrayHelper::map(SeasonMinutePrice::find()->where(['status'=>1])->all(), 'id', 'minute'), ['readonly'=>($model->isNewRecord?false:true)]) ?>
    <?= (!$model->isNewRecord?$form->field($model, 'minute_balance')->dropDownList(ArrayHelper::map(SeasonMinutePrice::find()->where(['status'=>1])->all(), 'id', 'minute'), ['readonly'=>true]):'') ?>
    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end();

    if(!$model->isNewRecord){
        //вывести лог расхода минут
    }



    ?>
</div>
