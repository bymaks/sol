<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SeasonMinutePrice */

$this->title = 'Добавить цену на абонемент';
$this->params['breadcrumbs'][] = ['label' => 'Цены на абонемент', 'url' => ['season-minutes']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="season-minute-price-create">

    <?= $this->render('_formSeasonMinute', [
        'model' => $model,
    ]) ?>

</div>