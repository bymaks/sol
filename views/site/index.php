<?php

/* @var $this yii\web\View */

$this->title = 'Solarium v1.0.1';
?>
<div class="site-index">
    <div class="row">

        <div class="col-md-4"><div id="curve_chart_avg_visit" style="width: 30%; height: auto;"></div>
            <div class="panel panel-primary">
                <div class="panel-heading">Товары и услуги</div>
                <div class="panel-body">
                    <div class="form-group has-error">
                        <input type="text" class="form-control" id="search_goods" placeholder="Название товара">
                        <p class="help-block ">Найденые товары:</p>
                    </div>
                    <div class="goods-items">
                        <?=  app\components\widgets\WSearchItem::widget(['goodId'=>1])?>

                        <?=  app\components\widgets\WSearchItem::widget(['goodId'=>1])?>

                        <?=  app\components\widgets\WSearchItem::widget(['goodId'=>1])?>
                    </div>

                </div>
            </div>
        </div>



        <div class="col-md-8">
            <div class="panel panel-primary info-order">
                <div class="panel-heading">Информация по заказу</div>
                <div class="panel-body">
                    <?=app\components\widgets\WBasket::widget()?>
                    <!--<div class="row">
                        <div class="col-md-6">
                            <div class="goods-items">
                                <div class="media border">
                                    <a class="pull-left" href="#">
                                        <img class="media-object" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAACA0lEQVR4Xu2YQYvCMBCFx4MURKigFb31IIJ4E/z/P6DgTbwoihdFtCCKWHrY5QVGql3WtXGppS9Hm0wyLzPvw1TCMPySEo8KBWAFsAXoASX2QKEJkgKkAClACpACJVaAGCQGiUFikBgsMQT4Z4gYJAaJQWKQGCQGS6zAWzAYBIEcj0cjY7fbleFweCfpdDqVzWYjvV5PfN9/Kve74/22obUAOGwcxzIajWSxWMhut5PBYCCdTsfsezqdZDKZmDl/EeDd8Z6pbSWAJtdqtVK3rhsnb1MFSFZEs9k0AlWrVen3+4Jvr8Z7luS/VcB2u5XZbCb1ev3WAslbXq1WslwuBUmiMvTb9Xq9VQUSj6LIVA1Glni5C+A4TqoFGo3G7WY9z5P5fH7XAhAHv2G4rivj8VhU0Czxsopg1QJ64Ha7bVpAk4IRYuz3eyPM4XBICaBVoLcPz7CJl4sAjx6gCaDkz+ezXC6X1LkefQATarWaEQpGCT9QD3glXi4CYFOYHBJFAuv1OkUBzNHK0OST3oHkgUjFZ5Z4WZPHOqsWQAAtZb3tn1D3KACSRIXA+NQrFKUwRVTBK/FyFcBm809Ya10Bn5CEzRkoAF+E+CLEFyG+CNm4aNHXkgKkAClACpACRXdym/OTAqQAKUAKkAI2Llr0taQAKUAKkAKkQNGd3Ob8pEDZKfANMHZanyc+RysAAAAASUVORK5CYII=" alt="...">
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">Апельсин 2 кг.</h4>
                                        <div class="small text-muted">Фрукты / артикул</div>
                                        <div class="small text-muted">цена: 300 р.</div>
                                    </div>
                                    <div class="action pull-right" title="Удалить"><i class="glyphicon glyphicon glyphicon-remove text-danger"></i> </div>
                                </div>
                                <div class="media border">
                                    <a class="pull-left" href="#">
                                        <img class="media-object" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAACA0lEQVR4Xu2YQYvCMBCFx4MURKigFb31IIJ4E/z/P6DgTbwoihdFtCCKWHrY5QVGql3WtXGppS9Hm0wyLzPvw1TCMPySEo8KBWAFsAXoASX2QKEJkgKkAClACpACJVaAGCQGiUFikBgsMQT4Z4gYJAaJQWKQGCQGS6zAWzAYBIEcj0cjY7fbleFweCfpdDqVzWYjvV5PfN9/Kve74/22obUAOGwcxzIajWSxWMhut5PBYCCdTsfsezqdZDKZmDl/EeDd8Z6pbSWAJtdqtVK3rhsnb1MFSFZEs9k0AlWrVen3+4Jvr8Z7luS/VcB2u5XZbCb1ev3WAslbXq1WslwuBUmiMvTb9Xq9VQUSj6LIVA1Glni5C+A4TqoFGo3G7WY9z5P5fH7XAhAHv2G4rivj8VhU0Czxsopg1QJ64Ha7bVpAk4IRYuz3eyPM4XBICaBVoLcPz7CJl4sAjx6gCaDkz+ezXC6X1LkefQATarWaEQpGCT9QD3glXi4CYFOYHBJFAuv1OkUBzNHK0OST3oHkgUjFZ5Z4WZPHOqsWQAAtZb3tn1D3KACSRIXA+NQrFKUwRVTBK/FyFcBm809Ya10Bn5CEzRkoAF+E+CLEFyG+CNm4aNHXkgKkAClACpACRXdym/OTAqQAKUAKkAI2Llr0taQAKUAKkAKkQNGd3Ob8pEDZKfANMHZanyc+RysAAAAASUVORK5CYII=" alt="...">
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">Апельсин 2 кг.</h4>
                                        <div class="small text-muted">Фрукты / артикул</div>
                                        <div class="small text-muted">цена: 300 р.</div>
                                    </div>
                                    <div class="action pull-right" title="Удалить"><i class="glyphicon glyphicon glyphicon-remove text-danger"></i> </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="img">
                                <img class="size-1" src="https://cdn.pixabay.com/photo/2014/08/19/14/06/coupon-421600_960_720.jpg">
                                <div class="text-center buttons"> <button class="btn-success btn">Подключить</button></div>
                            </div>
                            <div class="total">
                                <div class="form-group has-error">
                                    <input type="text" class="form-control col-md-4" placeholder="Номер сертификат">
                                    <p class="help-block ">Номер не найден</p>
                                </div>
                                <div class="total-money">
                                    <div><b>Цена:</b> <span class="money">3 100 р.</span></div>
                                    <div><b>Скидка:</b> <span class="money text-danger">-100 р.</span></div>
                                    <div class="result"><h4>Итого:</h4> <span class="money">3 000 р.</span></div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>

</div>
