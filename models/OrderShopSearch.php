<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrderShop;

/**
 * OrderShopSearch represents the model behind the search form of `app\models\OrderShop`.
 */
class OrderShopSearch extends OrderShop
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'create_by_user', 'season_tikets_id', 'minuts', 'discont_minute', 'status'], 'integer'],
            [['create_at', 'comment'], 'safe'],
            [['summ', 'discont'], 'number'],
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
        $query = OrderShop::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(isset($params['dateStart'])){
            $query->andFilterWhere(['>=', 'order_shop.create_at', Date('Y-m-d 0:00:00', strtotime($params['dateStart']))]);
        }

        if(isset($params['dateEnd'])){
            $query->andFilterWhere(['<=', 'order_shop.create_at', Date('Y-m-d 23:59:59', strtotime($params['dateEnd']))]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'create_by_user' => $this->create_by_user,
            'create_at' => $this->create_at,
            'season_tikets_id' => $this->season_tikets_id,
            'minuts' => $this->minuts,
            'summ' => $this->summ,
            'discont' => $this->discont,
            'discont_minute' => $this->discont_minute,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
