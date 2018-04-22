<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id
 * @property int $order_shop_id
 * @property int $good_id
 * @property int $good_count
 * @property string $good_price
 * @property string $commis
 * @property string $discont
 * @property int $status
 *
 * @property OrderShop $orderShop
 * @property Goods $good
 */
class OrderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_shop_id', 'good_id', 'good_count', 'good_price'], 'required'],
            [['order_shop_id', 'good_id', 'good_count', 'status'], 'integer'],
            [['good_price', 'commis', 'discont'], 'number'],
            [['order_shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderShop::className(), 'targetAttribute' => ['order_shop_id' => 'id']],
            [['good_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['good_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_shop_id' => 'Order Shop ID',
            'good_id' => 'Good ID',
            'good_count' => 'Good Count',
            'good_price' => 'Good Price',
            'commis' => 'Commis',
            'discont' => 'Discont',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderShop()
    {
        return $this->hasOne(OrderShop::className(), ['id' => 'order_shop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGood()
    {
        return $this->hasOne(Goods::className(), ['id' => 'good_id']);
    }
}
