<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Shop */

$this->title = 'Редактирование';
$this->params['breadcrumbs'][] = ['label' => 'Точки продаж', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="shop-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
