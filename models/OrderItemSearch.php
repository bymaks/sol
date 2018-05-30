<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrderItem;

/**
 * OrderItemSearch represents the model behind the search form of `app\models\OrderItem`.
 */
class OrderItemSearch extends OrderItem
{
    /**
     * @inheritdoc
     */
    public $goodName;
    public $saleCount;
    public $summSell;
    public $stok;

    public function rules()
    {
        return [
            [['id', 'order_shop_id', 'good_id', 'good_count', 'status', 'saleCount', 'summSell', 'stok',], 'integer'],
            [['good_price', 'commis', 'discont'], 'number'],
            [['goodName'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = OrderItem::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_shop_id' => $this->order_shop_id,
            'good_id' => $this->good_id,
            'good_count' => $this->good_count,
            'good_price' => $this->good_price,
            'commis' => $this->commis,
            'discont' => $this->discont,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }

    public function searchGroup($params)
    {
        /*
SELECT  goods.name as 'goodName',
        sum(order_item.good_count) as 'saleCount',
        sum(order_item.good_price*order_item.good_count) as 'summSell',
        goods_stok.good_count as 'stok'
FROM order_shop, order_item, shop ,goods
left join goods_stok on goods_stok.good_id = goods.id
WHERE order_shop.`create_at` BETWEEN '2018-05-30 00:00:00.000000' AND '2018-05-31 00:00:00.000000' AND
		order_shop.status = 1 and
        order_item.order_shop_id = order_shop.id and
		goods.id = order_item.good_id and
        goods_stok.shop_id = order_shop.shop_id
GROUP by order_item.good_id
        */

        $query = OrderItem::find()
            ->select([
                'goodName'=>'goods.name',
                'saleCount'=>'sum(order_item.good_count)',
                'summSell'=>'sum(order_item.good_price*order_item.good_count)',
                'stok'=>'goods_stok.good_count',
            ])
            ->from('order_shop, order_item, shop ,goods')
            ->leftJoin('goods_stok', 'goods_stok.good_id = goods.id ')
            ->where(['between', 'order_shop.create_at',Date('Y-m-d 00:00:00', strtotime($params['dateStart'])), Date('Y-m-d 23:59:59', strtotime($params['dateEnd']))])
            ->andWhere(['order_shop.status'=>1])
            ->andWhere('    order_item.order_shop_id = order_shop.id and  
		                    goods.id = order_item.good_id and 
                            goods_stok.shop_id = order_shop.shop_id');
        if(isset($params['shop_id']) && is_numeric($params['shop_id'])){
            $query->andWhere(['order_shop.shop_id'=>$params['shop_id']]);
        }
        //TODO:добавить поиск по названию товара

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'order_shop_id' => $this->order_shop_id,
            //'good_id' => $this->good_id,
            //'good_count' => $this->good_count,
            //'good_price' => $this->good_price,
            //'commis' => $this->commis,
            //'discont' => $this->discont,
            //'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
