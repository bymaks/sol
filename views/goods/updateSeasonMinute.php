<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SeasonMinutePrice */

$this->title = 'Редактировать цену на абонемент';
$this->params['breadcrumbs'][] = ['label' => 'Редактировать цену на абонемент', 'url' => ['season-minutes']];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="season-minute-price-update">
    <?= $this->render('_formSeasonMinute', [
        'model' => $model,
    ]) ?>

</div>
