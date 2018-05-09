<?php

/**
 * Created by PhpStorm.
 * User: 34max
 * Date: 09.05.2018
 * Time: 20:18
 */
class WSearchPallet extends \yii\base\Widget
{

    public $search=NULL;

    public function init() {
        parent::init();
        if (empty($this->search)) {
            $this->search = NULL;
        }
    }

    public function run(){

    }
}