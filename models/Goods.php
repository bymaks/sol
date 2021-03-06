<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name
 * @property string $vendor_code
 * @property string $price
 * @property int $category_id
 * @property int $ci_id
 * @property string $description
 * @property int $show_status
 * @property int $create_by_user
 * @property int $update_by_user
 * @property string $create_time
 * @property string $update_time
 * @property int $status
 *
 * @property Users $updateByUser
 * @property Users $createByUser
 * @property Users $updateByUser0
 * @property OrderItem[] $orderItems
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'vendor_code', 'price', 'ci_id', ], 'required', 'message'=>'Не может быть пустым'],
            [['price'], 'number'],
            [['category_id', 'ci_id', 'show_status', 'create_by_user', 'update_by_user', 'status'], 'integer'],
            [['description'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 256],
            [['vendor_code'], 'string', 'max' => 128],
            [['update_by_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['update_by_user' => 'id']],
            [['create_by_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_by_user' => 'id']],
            [['update_by_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['update_by_user' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodsCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['ci_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodsCi::className(), 'targetAttribute' => ['ci_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'vendor_code' => 'Артикул',
            'price' => 'Цена',
            'category_id' => 'Категория',
            'ci_id' => 'ЕИ',
            'description' => 'Описание',
            'show_status' => 'Отображать',
            'create_by_user' => 'Создан',
            'update_by_user' => 'Оновлен',
            'create_time' => 'Время создания',
            'update_time' => 'Время обновления',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateByUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'update_by_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateByUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'create_by_user']);
    }

    public function getCategory()
    {
        return $this->hasOne(GoodsCategory::className(), ['id' => 'category_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['good_id' => 'id']);
    }

    public function getImages()
    {
        return $this->hasMany(GoodsImages::className(), ['goods_id' => 'id'])->where(['status'=>1]);
    }

    public function getCi()
    {
        return $this->hasOne(GoodsCi::className(), ['id' => 'ci_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if(!empty($insert)){
            //создаем по каждома складу запись в таблице
            $shops = Shop::find()->where(['status'=>1])->all();
            if(!empty($shops)){
                foreach ($shops as $shop){
                    $goodStok = new GoodsStok();
                    $goodStok->good_count = 0;
                    $goodStok->good_id = $this->id;
                    $goodStok->shop_id = $shop->id;
                    $goodStok->status=$this->status;
                    $goodStok->save(true);
                    unset($goodStok);
                }
            }
        }
        else{
            // если удаляем то скрываем все остатки статусом
            if($this->oldAttributes['status']==1 && $this->status==0){
                GoodsStok::updateAll(['status'=>1], ['good_id'=>$this->id]);
            }
        }
    }
}
