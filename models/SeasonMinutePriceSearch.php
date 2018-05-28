<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SiasonMinutePrice;

/**
 * SiasonMinutePriceSearch represents the model behind the search form of `app\models\SiasonMinutePrice`.
 */
class SeasonMinutePriceSearch extends SeasonMinutePrice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'minute', 'price', 'status'], 'integer'],
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
        $query = SeasonMinutePrice::find();

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
            'minute' => $this->minute,
            'price' => $this->price,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
