<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Shop */

$this->title = 'Создать точку';
$this->params['breadcrumbs'][] = ['label' => 'Тояки продаж', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-create">

   <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
