<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SeasonTikets */

$this->title = 'Создать абонемент';
$this->params['breadcrumbs'][] = ['label' => 'Абонемент', 'url' => ['tikets']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="season-tikets-create">
    <?= $this->render('_formTiket', [
        'model' => $model,
    ]) ?>

</div>
