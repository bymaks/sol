<?php
namespace app\components\widgets;
/**
 * Created by PhpStorm.
 * User: 34max
 * Date: 09.05.2018
 * Time: 20:19
 */

use app\models\System;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class WSearchItem extends Widget
{
    public $goodId = NULL;

    public function init() {
        parent::init();
        if (empty($this->goodId)) {
            $this->goodId = NULL;
        }
    }

    public function run(){
        if(!empty($this->goodId)){
            $good = \app\models\Goods::find()->where(['id'=>$this->goodId, 'show_status'=>1, 'status'=>1])->one();
            if(!empty($good)){
                //найти картинку товара
                $image = System::getMainImg($good);
                if(!empty($image)){
                    $srcImage = $image->path;
                }
                else{
                    $srcImage = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAACA0lEQVR4Xu2YQYvCMBCFx4MURKigFb31IIJ4E/z/P6DgTbwoihdFtCCKWHrY5QVGql3WtXGppS9Hm0wyLzPvw1TCMPySEo8KBWAFsAXoASX2QKEJkgKkAClACpACJVaAGCQGiUFikBgsMQT4Z4gYJAaJQWKQGCQGS6zAWzAYBIEcj0cjY7fbleFweCfpdDqVzWYjvV5PfN9/Kve74/22obUAOGwcxzIajWSxWMhut5PBYCCdTsfsezqdZDKZmDl/EeDd8Z6pbSWAJtdqtVK3rhsnb1MFSFZEs9k0AlWrVen3+4Jvr8Z7luS/VcB2u5XZbCb1ev3WAslbXq1WslwuBUmiMvTb9Xq9VQUSj6LIVA1Glni5C+A4TqoFGo3G7WY9z5P5fH7XAhAHv2G4rivj8VhU0Czxsopg1QJ64Ha7bVpAk4IRYuz3eyPM4XBICaBVoLcPz7CJl4sAjx6gCaDkz+ezXC6X1LkefQATarWaEQpGCT9QD3glXi4CYFOYHBJFAuv1OkUBzNHK0OST3oHkgUjFZ5Z4WZPHOqsWQAAtZb3tn1D3KACSRIXA+NQrFKUwRVTBK/FyFcBm809Ya10Bn5CEzRkoAF+E+CLEFyG+CNm4aNHXkgKkAClACpACRXdym/OTAqQAKUAKkAI2Llr0taQAKUAKkAKkQNGd3Ob8pEDZKfANMHZanyc+RysAAAAASUVORK5CYII=';
                }
                ?>
                <div class="media border">
                    <a class="pull-left" href="#">
                        <img class="media-object" style="max-width: 64px;" src="<?=$srcImage?>" alt="...">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?= $good->name ?><?=((!empty($good->ci))? ' 1 '.$good->ci->name:'')?></h4>
                        <div class="small text-muted"><?=((!empty($good->category))? $good->category->name:'')?> / <?=((!empty($good->vendor_code))? $good->vendor_code:'н.д.')?></div>
                        <div class="small text-muted">цена: <?=$good->price?> р.</div>
                    </div>
                    <div class="action pull-right" title="Добавить" onclick="addItemToBasket(<?=$good->id?>);"><i class="glyphicon glyphicon-plus text-success"></i>
                        <!--<br><span class="text-danger small">Ecf</span>-->
                    </div>
                    <!--
                    <div class="action pull-right" title="Добавить"><i class="glyphicon glyphicon glyphicon-ok text-success"></i> </div>
                    <div class="action pull-right" title="Добавить"><i class="glyphicon glyphicon-plus text-muted"></i> </div>
                    <div class="action pull-right" title="Добавить"><i class="glyphicon glyphicon-plus text-primary"></i> </div>

                    -->
                </div>
                <?php
            }
        }

    }
}