<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shop".
 *
 * @property int $id
 * @property string $name
 * @property string $adress
 * @property int $status
 *
 * @property OrderShop[] $orderShops
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'adress'], 'required'],
            [['status'], 'integer'],
            [['name', 'adress'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'adress' => 'Адрес',
            'status' => 'Активность',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderShops()
    {
        return $this->hasMany(OrderShop::className(), ['shop_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if(empty($insert)){
            if($this->oldAttributes['status']==1 && $this->status==0){
                //отключаем все остатки
                GoodsStok::updateAll(['status'=>0,], ['shop_id'=>$this->id]);
            }

            if($this->oldAttributes['status']==0 && $this->status==1){
                //включаем все остатки
                GoodsStok::updateAll(['status'=>0,], ' shop_id ='.$this->id.' and good_count>0' );
            }
        }
    }
}
