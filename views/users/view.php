<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = $model->first_name.' '.$model->second_name.' ('.$model->login.')';
$this->params['breadcrumbs'][] = ['label' => 'Сотрудики', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-view">

    <p>
        <?= Html::a(Html::encode($this->title),['update', 'id' => $model->id]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'login',
            'phone',
            'telegramm',
            'email:email',
            'first_name',
            'second_name',
            'last_name',
            'gender',
            'birthday',
            'time_out:datetime',
            'created_at',
            'updated_at',
            'create_by_user',
            'update_by_user',
            'status',
        ],
    ]) ?>

</div>
