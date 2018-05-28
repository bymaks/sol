<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods_stok".
 *
 * @property int $id
 * @property int $shop_id
 * @property int $good_id
 * @property int $good_count
 * @property int $status
 */
class GoodsStok extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_stok';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'good_id'], 'required', 'message'=>'Не может быть пустым'],
            [['shop_id', 'good_id', 'good_count','status'], 'integer', 'message'=> 'Должно быть числом'],
            [['good_count'], 'default', 'value'=>0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Точка продаж',
            'good_id' => 'Товар',
            'good_count' => 'Количество',
            'status' =>'Активность'
        ];
    }

    public function getShop(){
        return $this->hasOne(Shop::className(), ['id'=>'shop_id']);
    }

    public function getGood(){
        return $this->hasOne(Goods::className(), ['id'=>'good_id']);
    }
}
