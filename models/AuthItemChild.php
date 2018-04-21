<?php
namespace app\models;
use Yii;
class AuthItemChild extends \yii\db\ActiveRecord
{
    public static function tableName(){
        return 'auth_item_child';
    }
    public function rules(){
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64],
            [['parent', 'child'], 'unique', 'targetAttribute' => ['parent', 'child']],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['parent' => 'name']],
            [['child'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['child' => 'name']],
        ];
    }
    public function attributeLabels(){
        return [
            'parent' => 'Parent',
            'child' => 'Child',
        ];
    }
    public function getParent0(){
        return $this->hasOne(AuthItem::className(), ['name' => 'parent']);
    }
    public function getChild0(){
        return $this->hasOne(AuthItem::className(), ['name' => 'child']);
    }
}