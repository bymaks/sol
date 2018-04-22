<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_shop".
 *
 * @property int $id
 * @property int $shop_id
 * @property int $create_by_user
 * @property string $create_at
 * @property int $season_tikets_id
 * @property int $used_minuts
 * @property int $all_minuts
 * @property string $summ
 * @property string $comment
 * @property int $status
 *
 * @property OrderItem[] $orderItems
 * @property Shop $shop
 * @property Users $createByUser
 * @property SeasonTikets $seasonTikets
 */
class OrderShop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'create_by_user', 'create_at', 'season_tikets_id'], 'required'],
            [['shop_id', 'create_by_user', 'season_tikets_id', 'used_minuts', 'all_minuts', 'status'], 'integer'],
            [['create_at'], 'safe'],
            [['summ'], 'number'],
            [['comment'], 'string', 'max' => 512],
            [['shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shop::className(), 'targetAttribute' => ['shop_id' => 'id']],
            [['create_by_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_by_user' => 'id']],
            [['season_tikets_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonTikets::className(), 'targetAttribute' => ['season_tikets_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'create_by_user' => 'Create By User',
            'create_at' => 'Create At',
            'season_tikets_id' => 'Season Tikets ID',
            'used_minuts' => 'Used Minuts',
            'all_minuts' => 'All Minuts',
            'summ' => 'Summ',
            'comment' => 'Comment',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_shop_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateByUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'create_by_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeasonTikets()
    {
        return $this->hasOne(SeasonTikets::className(), ['id' => 'season_tikets_id']);
    }
}
