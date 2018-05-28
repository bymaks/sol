<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Goods */

$this->title = 'Создать товар';
$this->params['breadcrumbs'][] = ['label' => 'Товар', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
