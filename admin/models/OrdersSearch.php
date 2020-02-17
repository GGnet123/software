<?php

namespace admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\Orders;

/**
 * OrdersSearch represents the model behind the search form of `admin\models\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'is_card', 'is_bcc_card', 'total', 'delivery_price'], 'integer'],
            [['client_name', 'client_address', 'date', 'status', 'status_id', 'delivery_type', 'client_phone', 'items', 'taken'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Orders::find();

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
            'store_id' => $this->store_id,
            'is_card' => $this->is_card,
            'is_bcc_card' => $this->is_bcc_card,
            'total' => $this->total,
            'delivery_price' => $this->delivery_price,
        ]);

        $query->andFilterWhere(['like', 'client_name', $this->client_name])
            ->andFilterWhere(['like', 'client_address', $this->client_address])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'status_id', $this->status_id])
            ->andFilterWhere(['like', 'delivery_type', $this->delivery_type])
            ->andFilterWhere(['like', 'client_phone', $this->client_phone])
            ->andFilterWhere(['like', 'items', $this->items])
            ->andFilterWhere(['like', 'taken', $this->taken]);

        return $dataProvider;
    }
}
