<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "season_tiket_transaction".
 *
 * @property int $id
 * @property int $order_id
 * @property int $season_tiket_id
 * @property int $type_transaction 0-spisanie,1-zachislenie
 * @property string $minute
 * @property string $create_at
 * @property int $create_by_user
 * @property int $status
 *
 * @property OrderShop $order
 * @property SeasonTikets $seasonTiket
 * @property Users $createByUser
 */
class SeasonTiketTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'season_tiket_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'season_tiket_id', 'type_transaction', 'minute'], 'required'],
            [['order_id', 'season_tiket_id', 'type_transaction', 'create_by_user', 'status'], 'integer'],
            [['minute'], 'number'],
            [['create_at'], 'safe'],
            [['create_at'], 'default', 'value'=>(empty($this->create_at)?Date('Y-m-d H:i:s'):$this->create_at)],
            [['create_by_user'], 'default', 'value'=>(empty($this->create_by_user)?Yii::$app->user->id:$this->create_by_user)],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderShop::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['season_tiket_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeasonTikets::className(), 'targetAttribute' => ['season_tiket_id' => 'id']],
            [['create_by_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_by_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'season_tiket_id' => 'Season Tiket ID',
            'type_transaction' => 'Type Transaction',
            'minute' => 'Minute',
            'create_at' => 'Create At',
            'create_by_user' => 'Create By User',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(OrderShop::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeasonTiket()
    {
        return $this->hasOne(SeasonTikets::className(), ['id' => 'season_tiket_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateByUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'create_by_user']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if($insert){
            if($this->status==1){
                if($tiket = $this->seasonTiket){
                    if($this->type_transaction==0){// списание
                        $tiket->minute_balance = ($tiket->minute_balance - $this->minute);
                        if($tiket->minute_balance<=0){
                            $tiket->status=0;
                        }
                        if(!$tiket->save(true)){
                            System::mesprint($tiket->errors);die();
                        }
                    }
                    //elseif($this->type_transaction==1){//зачисление
                    //}
                }
            }
        }
    }
}
