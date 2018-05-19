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
 * @property int $discont
 * @property int $minuts
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
            [['shop_id', ], 'required'],
            [['shop_id', 'create_by_user', 'season_tikets_id', 'minuts', 'status'], 'integer'],
            [['create_at'], 'safe'],
            [['create_at'], 'default', 'value'=>(empty($this->create_at)?Date('Y-m-d H:i:s'):$this->create_at)],
            [['create_by_user'], 'default', 'value'=>(empty($this->create_by_user)?Yii::$app->user->id:$this->create_by_user)],
            [['discont','summ'], 'number'],
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
            'shop_id' => 'Точка продаж',
            'create_by_user' => 'Кем создан',
            'create_at' => 'Время создания',
            'season_tikets_id' => 'Абонемент',
            'minuts' => 'All Minuts',
            'summ' => 'Сумма',
            'discont' => 'Скидка',
            'comment' => 'Комментарий',
            'status' => 'Активность',
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
