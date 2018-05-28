<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "siason_minute_price".
 *
 * @property int $id
 * @property int $minute
 * @property int $price
 * @property int $status
 */
class SeasonMinutePrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'season_minute_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['minute', 'price'], 'required'],
            [['minute', 'price', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'minute' => 'Количество минут',
            'price' => 'Цена',
            'status' => 'Активность',
        ];
    }
}
