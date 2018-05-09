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
Yii::$app->name = "SolSale"
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
<!--Modal Оплата-->
<?php Modal::begin(['header' => '<h4></h4>',
    'closeButton' => ['tag' => 'button', 'label' => '&times;'],
    'id' => 'modal-global',
    // 'size'=>'modal-sm',
]);?>
<?php Modal::end(); ?>
<!--Ппанель уведомления -->
<div class="alert alert__fix js-alert-close">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <div class="messages"></div>
</div>
<!--./Ппанель уведомления -->

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    ?>
    <div class="col-sm-4 col-md-push-2 search-input">
        <div class="input-group">
            <input type="text" class="form-control js-value-search" placeholder="Введите номер сертификата">
            <span class="input-group-btn"><button class="btn btn-default js-button-search" type="button">Поиск</button></span>
        </div><!-- /input-group -->
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

                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],

                Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->login . ')',
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
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
