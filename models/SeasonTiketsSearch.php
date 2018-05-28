<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SeasonTikets;

/**
 * SeasonTiketsSearch represents the model behind the search form of `app\models\SeasonTikets`.
 */
class SeasonTiketsSearch extends SeasonTikets
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'minute_all', 'create_by_user', 'status'], 'integer'],
            [['minute_balance', 'price'], 'number'],
            [['tiket_id', 'create_at', 'exp_at', 'comment'], 'safe'],
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
        $query = SeasonTikets::find();

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
            'minute_all' => $this->minute_all,
            'minute_balance' => $this->minute_balance,
            'create_by_user' => $this->create_by_user,
            'create_at' => $this->create_at,
            'exp_at' => $this->exp_at,
            'price' => $this->price,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
