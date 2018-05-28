<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_by_shop".
 *
 * @property int $id
 * @property int $user_id
 * @property int $shop_id
 * @property string $date_enable
 * @property string $date_disable
 * @property int $status
 */
class UsersByShop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_by_shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'shop_id', 'date_disable'], 'required'],
            [['user_id', 'shop_id', 'status'], 'integer'],
            [['date_enable', 'date_disable'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'shop_id' => 'Shop ID',
            'date_enable' => 'Date Enable',
            'date_disable' => 'Date Disable',
            'status' => 'Status',
        ];
    }
}
