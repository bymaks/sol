<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use skeeks\yii2\ckeditor\CKEditorWidget;
use skeeks\yii2\ckeditor\CKEditorPresets;

?>

<div class="goods-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ci_id')->dropdownList(\yii\helpers\ArrayHelper::map(\app\models\GoodsCi::find()->where(['status'=>1])->all(), 'id', 'name'))->label('ЕМ') ?>

    <?= $form->field($model, 'description')->widget(CKEditorWidget::className(), [
        'options' => ['rows' => 6],
        'preset' => CKEditorPresets::STANDART,
    ])->label('Текст') ?>

    <?= $form->field($model, 'show_status')->checkbox() ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end();

    /*if(!$model->isNewRecord){
        echo "<h3>Добавить изображение</h3>";
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
        echo $form->field($model_file, 'imageFile[]')->widget(
            \kartik\file\FileInput::classname(), [
                'options' => ['multiple' => true],
                'pluginOptions' => ['previewFileType' => 'any',
                    'uploadUrl' => Url::to(
                        ['/cards-types/upload?model_id=' . $model->id]
                    )],
            ]
        )->label(false);
        ActiveForm::end();
    }*/
    ?>
</div>

