<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SeasonTikets */

$this->title = 'Редактировать абонемент';
$this->params['breadcrumbs'][] = ['label' => 'Абонемент', 'url' => ['tikets']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="season-tikets-update">

    <?= $this->render('_formTiket', [
        'model' => $model,
    ]) ?>

</div>
