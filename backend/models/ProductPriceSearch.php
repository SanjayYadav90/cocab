<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProductPrice;

/**
 * ProductPriceSearch represents the model behind the search form about `backend\models\ProductPrice`.
 */
class ProductPriceSearch extends ProductPrice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['unit', 'offer_unit', 'offer_flag'], 'safe'],
            [['mrp', 'offer_price','quantity', 'discounted_mrp'], 'number'],
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
        $query = ProductPrice::find();

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
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'mrp' => $this->mrp,
            'offer_price' => $this->offer_price,
            'discounted_mrp' => $this->discounted_mrp,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'offer_unit', $this->offer_unit])
            ->andFilterWhere(['like', 'offer_flag', $this->offer_flag]);

        return $dataProvider;
    }
}
