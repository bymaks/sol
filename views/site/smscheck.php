<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Подтверждение входа';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-login" style="width: 600px; !important;">
    <h1><?= Html::encode($this->title) ?></h1>


    <?php
    $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg6\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-6 control-label'],
        ],
    ]); ?>
    <div class="form-group">
        <div class="col-lg-6">
            <?= $form->field($model, 'sms_code')->Input(['maxlength' => 6,])->label('SMS Код') ?>
        </div>

        <div class="col-lg-3">
            <?= Html::submitButton('Вход', ['class' => 'btn btn-success', ]) ?>
            <?= $form->field($model, 'login')->hiddenInput()->label('');?>
            <?= $form->field($model, 'rememberMe')->hiddenInput()->label('')?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>