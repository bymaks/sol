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
            [['shop_id', 'good_id'], 'required'],
            [['shop_id', 'good_id', 'good_count'], 'integer'],
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
            'good_id' => 'Good ID',
            'good_count' => 'Good Count',
        ];
    }
}
