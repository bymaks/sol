<?php
use yii\helpers\Html;
$this->title = 'Редактировать сотрудника: ' . $model->login;
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->login, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="user-update">
    <?= $this->render('_form', [
        'model' => $model,
        'modelRole' => $modelRole,
    ]) ?>
</div>