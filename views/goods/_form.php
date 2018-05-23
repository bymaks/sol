<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use skeeks\yii2\ckeditor\CKEditorWidget;
use skeeks\yii2\ckeditor\CKEditorPresets;
use \kartik\file\FileInput;


?>

<div class="goods-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ci_id')->dropdownList(\yii\helpers\ArrayHelper::map(\app\models\GoodsCategory::find()->where(['status'=>1])->all(), 'id', 'name'))->label('Категория') ?>

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

    if(!$model->isNewRecord){

        $images = $model->images;
        if(!empty($images)){
            echo '
                <div class="row">';
            $i=0;
            foreach ($images as $image){
                $main='';
                if($image->main==1){
                    $main = '<div class="close text-success" style="opacity: 1; position: absolute; left: 20px; top: -3px;">
                                <span class="glyphicon glyphicon-ok text-success"></span>
                             </div>';
                }

                $del = '<div class="close " style="opacity: 1;  position: absolute; right: 20px; top: -3px;" onClick=" delImage('.$image->id.')">
                            <span class="glyphicon glyphicon-remove text-danger"></span>
                        </div>';

                echo '<div class="col-md-3">'.$main.$del.'<img class="media-object js-set-main" imgid="'.$image->id.'" goodid="'.$image->goods_id.'" src="'.$image->path.'" style="max-width: 100%;" alt="..."></div>';
                $i++;
                if($i==3){
                    $i=0;
                }
            }
            echo '</div>';
        }

        echo "<h3>Добавить изображение</h3>";
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
        echo $form->field($modelImage, 'imageFile[]')->widget(
            FileInput::classname(), [
                'options' => ['multiple' => true],
                'pluginOptions' => ['previewFileType' => 'any',
                    'uploadUrl' => Url::to(
                        ['/goods/upload?modelId=' . $model->id]
                    )],
            ]
        )->label(false);
        ActiveForm::end();
    }
    ?>
</div>

