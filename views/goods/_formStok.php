<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="goods-stok-form">

    <?php
    \yii\bootstrap\Modal::begin([
        'header' => '<h3><b>Найти товар:</b></h3>',
        'id' => 'search_good',
        'toggleButton' => [
            'tag' => 'button',
            'class' => 'btn btn-warning',
            'label' => 'Найти товар'
        ]
    ]);



    echo '<b>Введите название или артикул</b><br>';
    \yii\widgets\Pjax::begin();

    echo Html::beginForm(['goods/stok-create'], //наш урл
        'get',                                  //наш метод
        [                                       //массив опций
            'data-pjax' => true,                //чтобы работал пъякс
            'enableAjaxValidation' => true,     //чтобы проверял раз аякс
            'class' => 'form-inline'            // класс стилей для формы
        ]);

    echo Html::hiddenInput('id', $model->id);

    echo Html::input('text', 'search', $search,
        [
            'class' => 'form-control',
            'minlength' => 2,
            'maxlength' => 10
        ]);
    echo Html::submitButton('Найти', ['class' => 'btn btn-primary', 'name' => 'hash-button']);//бутон сабмита
    echo Html::endForm();                       // Конец формы
    echo "<br>";

    if(!empty($search_output)) {
        foreach ($search_output as $item) {
            echo "<a href='#'  onClick=\"return funct($item->id,'".$item->name."');\">"
                .$item->vendor_code . " "
                . $item->name."</a><br>";
        }
    }
    else{
        echo "Ничего не найдено <br>";
    }

    \yii\widgets\Pjax::end();
    \yii\bootstrap\Modal::end();                //Конец модала и пъякса


    $form = ActiveForm::begin();
    echo Html::hiddenInput('id_changed', 0, ['id' => 'id_changed']);
    if(empty($goods)){
        echo $form->field($model, 'good_id')->DropDownList([0=>'не выбран',],
            ['readonly' => true, 'id' => 'good_id']);
    }
    else{
        echo $form->field($model, 'good_id')->DropDownList(\yii\helpers\ArrayHelper::map($goods,'id','name'),
            ['readonly' => true, 'id' => 'good_id']);
    }


    ?>

    <?= $form->field($model, 'shop_id')->DropDownList(\yii\helpers\ArrayHelper::map(\app\models\Shop::find()->where(['status'=>1])->all(), 'id', 'name'), ['prompt'=>'не выбрана']) ?>

    <?= $form->field($model, 'good_count')->textInput() ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>