<?php
namespace app\models;
class AuthItemQuery extends \yii\db\ActiveQuery
{
    public static function getDb(){
        return \Yii::$app->db;
    }
    //@return AuthItem[]|array
    public function all($db = null){
        return parent::all($db);
    }
    //@return AuthItem|array|null
    public function one($db = null){
        return parent::one($db);
    }
}