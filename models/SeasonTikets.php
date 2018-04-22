<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "season_tikets".
 *
 * @property int $id
 * @property int $minute_all
 * @property int $minute_used
 * @property int $create_by_user
 * @property string $create_at
 * @property string $exp_at
 * @property string $comment
 * @property string $price
 * @property int $status
 *
 * @property OrderShop[] $orderShops
 */
class SeasonTikets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'season_tikets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['minute_all', 'minute_used', 'create_by_user', 'create_at', 'exp_at', 'price'], 'required'],
            [['minute_all', 'minute_used', 'create_by_user', 'status'], 'integer'],
            [['create_at', 'exp_at'], 'safe'],
            [['price'], 'number'],
            [['comment'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'minute_all' => 'Minute All',
            'minute_used' => 'Minute Used',
            'create_by_user' => 'Create By User',
            'create_at' => 'Create At',
            'exp_at' => 'Exp At',
            'comment' => 'Comment',
            'price' => 'Price',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderShops()
    {
        return $this->hasMany(OrderShop::className(), ['season_tikets_id' => 'id']);
    }
}
