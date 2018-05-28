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
    <?php
    if($model->isNewRecord){
        echo $form->field($model, 'minute_all')->dropDownList(ArrayHelper::map(SeasonMinutePrice::find()->where(['status'=>1])->all(), 'id', 'minute'),[]);
    }
    else{
        echo $form->field($model, 'minute_all')->textInput(['disabled'=>'disabled']);
        echo $form->field($model, 'minute_balance')->textInput([ 'disabled'=>'disabled']);
    }
    ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end();

    if(!$model->isNewRecord){
        //вывести лог расхода минут
        $transactions = $model->seasonTiketTransactions;
        if(!empty($transactions)){
            foreach ($transactions as $transaction){
                echo Date('d.m.Y H:i:s', strtotime($transaction->create_at))." : списано минут - ".$transaction->minute." </br>";
            }
        }
    }
    ?>
</div>
