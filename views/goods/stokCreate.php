<?php
use yii\helpers\Html;


$this->title = 'Добавить товар на склад';
$this->params['breadcrumbs'][] = ['label' => 'Остатки', 'url' => ['goods-stok']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-create">

    <?= $this->render('_formStok', [
        'search'=>$search,
        'search_output' => $search_output,
        'model' => $model,
    ]) ?>

</div>
