<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CowMilkBilling;

/**
 * CowMilkBillingSearch represents the model behind the search form about `backend\models\CowMilkBilling`.
 */
class CowMilkBillingSearch extends CowMilkBilling
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'subscription_id', 'user_id', 'delivery_boy_id', 'delivered_quantity', 'created_by', 'mobile', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['bill_cycle','start_date','end_date', 'billing_gen_date'], 'safe'],
            [['sub_total', 'referral_discount', 'voucher_discount', 'tax', 'bill_amount', 'previous_due_amount', 'net_payable_amount'], 'number'],
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
        $query = CowMilkBilling::find()->orderBy('id DESC');
		
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
		if(isset($params['CowMilkBillingSearch']['delivery_boy_id']) && $params['CowMilkBillingSearch']['delivery_boy_id'] !='')
		{
			$users_list = Users::find()->select('id')->where(['delivery_boy_id'=>$params['CowMilkBillingSearch']['delivery_boy_id']])->asArray()->all();
			$out = [];
			foreach($users_list as $item)
			{
				array_push($out,$item['id']);
			}
			$query->andFilterWhere(['IN', 'user_id', $out]);
		}
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'subscription_id' => $this->subscription_id,
            'user_id' => $this->user_id,
            'bill_cycle' => $this->bill_cycle,
            'delivered_quantity' => $this->delivered_quantity,
            'sub_total' => $this->sub_total,
            'referral_discount' => $this->referral_discount,
            'voucher_discount' => $this->voucher_discount,
            'tax' => $this->tax,
            'bill_amount' => $this->bill_amount,
            'previous_due_amount' => $this->previous_due_amount,
            'net_payable_amount' => $this->net_payable_amount,
            'billing_gen_date' => $this->billing_gen_date,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
		$query->andFilterWhere(['=', 'user_id', $this->mobile]);
		
        return $dataProvider;
    }
}
