<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "season_tikets".
 *
 * @property int $id
 * @property int $tiket_id
 * @property int $minute_all
 * @property string $minute_balance
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
    public $goodName;
    public $saleCount;
    public $summSell;
    public $stok;
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
            [['tiket_id', 'minute_all', 'minute_balance',  'create_at', 'price'], 'required'],
            [['minute_all', 'create_by_user', 'status'], 'integer'],
            [['create_by_user'], 'default', 'value'=>(!empty($this->create_by_user)?$this->create_by_user:Yii::$app->user->id) ],
            [['minute_balance', 'price'], 'number'],
            [['create_at', 'exp_at'], 'safe'],
            [['tiket_id'], 'string', 'max' => 16],
            [['comment'], 'string', 'max' => 512],
            [['tiket_id'], 'unique'],
        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tiket_id' => 'Номер',
            'minute_all' => 'Всего минут',
            'minute_balance' => 'Баланс Минут',
            'create_by_user' => 'Создан',
            'create_at' => 'Время создания',
            'exp_at' => 'Истакает',
            'comment' => 'Комментарий',
            'price' => 'Стоимость',
            'status' => 'Активность',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderShops()
    {
        return $this->hasMany(OrderShop::className(), ['season_tikets_id' => 'id']);
    }

    public function getSeasonTiketTransactions()
    {
        return $this->hasMany(SeasonTiketTransaction::className(), ['season_tiket_id' => 'id']);
    }
}
