<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Modal;
AppAsset::register($this);
Yii::$app->name = "Black Style"
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="loadAjax"><div class="loader"></div></div>

<!--Modal Оплата-->
<?php
    Modal::begin(['header' => '<h4></h4>',
        'closeButton' => ['tag' => 'button', 'label' => '&times;', 'style'=>'color:#ffffff'],
        'id' => 'modal-global',
        'size'=>Modal::SIZE_LARGE,
        'bodyOptions'=>[
            'class'=>'modal-body bgimage',
        ],
        'headerOptions'=>[
            'class'=>'modal-header modal-backgroud-header '
        ],
        // 'size'=>'modal-sm',
    ]);
    Modal::end();
?>

<!--Ппанель уведомления -->
<div class="alert alert__fix js-alert-close">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <div class="messages"></div>
</div>
<!--./Ппанель уведомления -->

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' =>  '<img src="/images/logo.png" style="height:37px ;">',//Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    ?>
    <div class="col-sm-4 col-md-push-2 search-input">
        <div class="input-group">
            <input type="text" class="form-control js-value-search" placeholder="Введите номер сертификата">
            <span class="input-group-btn"><button class="btn btn-default js-button-search" id="js-button-search-id" type="button">Поиск</button></span>
        </div><!-- /input-group -->

    </div>
    <div class="col-sm-4 col-md-push-2 search-input">
        <div class="input-group">
            <?=Html::a('Оформить абонемент', ['/goods/create-tiket'], ['class' => 'btn btn-success margin-left-right'])?>
        </div>

    </div><!-- /.col-lg-6 -->

    <?php
    echo '<div class="pull-right">';
    echo ButtonDropdown::widget([
        'label' => 'Меню',
        'options' => [
            'class' => 'btn-lg btn-link nav navbar-nav',
            'style' => 'margin:5px'
        ],
        'dropdown' => [
           // 'options' => [''],
            'items' => [

                ['label' => 'Товары', 'url' => ['/goods/index']],
                ['label' => 'Остатки', 'url' => ['/goods/goods-stok']],
                ['label' => 'Абонементы', 'url' => ['/goods/tikets']],
                ['label' => 'Цены на абонементы', 'url' => ['/goods/season-minutes']],
                ['label' => 'Точки продаж', 'url' => ['/shop/index']],
                ['label' => 'Пользователи', 'url' => ['/users/index']],
                ['label' => 'Заказы', 'url' => ['/report/orders']],
                ['label' => 'Продажи', 'url' => ['/report/items']],

                Yii::$app->user->isGuest ? (
                ['label' => 'Вход', 'url' => ['/site/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Выход (' . Yii::$app->user->identity->login . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]
    ]);
    echo '</div>';




//    echo Nav::widget([
//        'options' => ['class' => 'navbar-nav navbar-right'],
//
//        'items' => [
//
//            ['label' => 'Home', 'url' => ['/site/index']],
//            ['label' => 'About', 'url' => ['/site/about']],
//            ['label' => 'Contact', 'url' => ['/site/contact']],
//
//            Yii::$app->user->isGuest ? (
//                ['label' => 'Login', 'url' => ['/site/login']]
//            ) : (
//                '<li>'
//                . Html::beginForm(['/site/logout'], 'post')
//                . Html::submitButton(
//                    'Logout (' . Yii::$app->user->identity->login . ')',
//                    ['class' => 'btn btn-link logout']
//                )
//                . Html::endForm()
//                . '</li>'
//            )
//        ],
//    ]);
    NavBar::end();
    ?>
    <br>
    <br>
    <br>
    <div class="container-fluid">

        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'Солярий', 'url' => '/'],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ])  ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy;Black style <?= date('Y') ?></p>

        <p class="pull-right">Yii2</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
