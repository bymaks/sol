<?php
namespace app\models;
use Yii;
class AuthItem extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return \Yii::$app->db;
    }
    public static function tableName(){
        return 'auth_item';
    }
    public function rules(){
        return [
            [['name', 'type'], 'required'],
            [['type'], 'integer'],
            [['description', 'data',], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['ru_name',], 'string', 'max' => 128],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }
    public function attributeLabels(){
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'ru_name' => 'Роль',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getRuleName(){
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }
    public function getAuthAssignments(){
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }
    public function getUsers(){
        return $this->hasMany(Users::className(), ['id' => 'user_id'])->viaTable('auth_assignment', ['item_name' => 'name']);
    }
    public function getAuthItemChildren(){
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }
    public function getAuthItemParent(){
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }
    public function getChildren(){
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->via('authItemChildren');//Table('auth_item_child', ['parent' => 'name']);
    }
    public function getParents(){
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->via('authItemParent');//Table('auth_item_child', ['child' => 'name']);
    }
    //AuthItemQuery the active query used by this AR class
    public static function find(){
        return new AuthItemQuery(get_called_class());
    }
}