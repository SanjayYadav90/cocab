<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AccountStatement;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use backend\models\Delivery;
use backend\models\CowMilkBilling;


/**
 * AccountStatementSearch represents the model behind the search form about `backend\models\AccountStatement`.
 */
class AccountStatementSearch extends AccountStatement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at','delivery_boy_id', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['transaction_date', 'due_date', 'payment_status', 'payment_mode','type','bank_name','cheque_number','bank_branch','mobile'], 'safe'],
            [['amount'], 'number'],
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
        $query = AccountStatement::find()->orderBy('transaction_date DESC');

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
            'transaction_date' => $this->transaction_date,
            'due_date' => $this->due_date,
            'amount' => $this->amount,
            'user_id' => $this->user_id,
			'delivery_boy_id' => $this->delivery_boy_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
			
        ]);

        $query->andFilterWhere(['like', 'payment_mode', $this->payment_mode]);
		$query->andFilterWhere(['like', 'type', $this->type]);
		$query->andFilterWhere(['like', 'bank_name', $this->bank_name]);
		$query->andFilterWhere(['like', 'cheque_number', $this->cheque_number]);
		$query->andFilterWhere(['like', 'bank_branch', $this->bank_branch]);
		$query->andFilterWhere(['like', 'payment_status', $this->payment_status]);
		$query->andFilterWhere(['=', 'user_id', $this->mobile]);

        return $dataProvider;
    }
	
	public function search4($params)
    {
        $start_date = date("Y-m-d",strtotime("first day of last month")); //date("Y-m-d",strtotime("-31 day",strtotime(date("Y-m-d"))));
		$end_date = date("Y-m-d");
		$d_boy = isset($params['AccountStatementSearch']['delivery_boy_id']) ? $params['AccountStatementSearch']['delivery_boy_id'] : 0;
		$mobile = isset($params['AccountStatementSearch']['mobile']) ? $params['AccountStatementSearch']['mobile'] : 0;
		$user = isset($params['AccountStatementSearch']['user_id']) ? $params['AccountStatementSearch']['user_id'] : 0;
		if($mobile!= 0 || $user != 0)
		{
			$user_id = ($mobile == 0)? $user :$mobile;
			$subsc_model = Subscription::find()->select('user_id')->where(['<=', 'start_date', $end_date])->andWhere(['>=', 'end_date', $start_date])->andWhere(['type'=>'Daily','user_id'=>$user_id])->orderBy('user_id desc')->distinct()->All();
		
		}
		else{
			$subsc_model = Subscription::find()->select('user_id')->where(['<=', 'start_date', $end_date])->andWhere(['>=', 'end_date', $start_date])->andWhere(['type'=>'Daily'])->orderBy('user_id desc')->distinct()->All();
		
		}
		
		if(isset($subsc_model))
		{
			foreach($subsc_model as $sub){
				$acc_mod = new AccountStatement();
				if($d_boy != 0 && $d_boy !='')
				{
					if($sub->users->delivery_boy_id == $d_boy)
					{
						$report[] = $acc_mod->getPendingBillAmountReport($sub->user_id,0);
					}
				}else{
					
					 $report[] = $acc_mod->getPendingBillAmountReport($sub->user_id,$d_boy);
				}
			}
		}
		
		$dataProvider = new ArrayDataProvider([
			'allModels' => isset($report) ? $report :[],
			//'modelClass' => 'AccountStatement',
			'pagination' => [
				'pageSize' => 25,
			],
		]);

        return $dataProvider;
    }
}
