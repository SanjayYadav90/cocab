<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paytm
 *
 * @author Akhilesh Jha <akhileshjha.48@gmail.com>
 */

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\Crypto;


class Paytm extends Component

{

    /**
     * @author Akhilesh Jha<akhileshjha.48@gmail.com>
     * @param type $params  data required for checkout
     * @param type $env  environment will be test or production
     */
    
    static function configPaytm($params, $env)
    {

     

        //for live
        if ($env == 'live') {
           $paytm_domain =  'https://secure.paytm.in/oltp-web/processTransaction';
            $params['MID'] =  '****************'; // Shared by Paytm
            $params['INDUSTRY_TYPE_ID'] =  '****************'; // Shared by Paytm
            $params['CHANNEL_ID'] =  '****************'; // Shared by Paytm
            $params['WEBSITE'] =  '****************'; // Shared by Paytm
            $params['Merchant_Key'] =  '****************'; // Shared by Paytm
           
        }

        //for test server if any
        if ($env == 'test') {
            $paytm_domain =  "pguat.paytm.com";
            $params['MID'] =  'Sanjee02202796869081'; // Shared by Paytm
            $params['INDUSTRY_TYPE_ID'] =  'Retail'; // Shared by Paytm
            $params['CHANNEL_ID'] =  'WEB'; // Shared by Paytm
            $params['WEBSITE'] =  'doodhvale.in'; // Shared by Paytm
            $params['Merchant_Key'] =  'wTYKqXnoezVACwjA'; // Shared by Paytm
			$params['payt_STATUS'] = '1';
        }
        $paytm_refund_url =  'https://'.$paytm_domain.'/oltp/HANDLER_INTERNAL/REFUND';
        $paytm_status_query_url =  'https://'.$paytm_domain.'/oltp/HANDLER_INTERNAL/TXNSTATUS';
        $paytm_status_query_new_url =  'https://'.$paytm_domain.'/oltp/HANDLER_INTERNAL/getTxnStatus';
        $paytm_txn_url =  'https://'.$paytm_domain.'/oltp-web/processTransaction';
        define('PAYTM_REFUND_URL', 'https://'.$paytm_domain.'/oltp/HANDLER_INTERNAL/REFUND');
		define('PAYTM_STATUS_QUERY_URL', 'https://'.$paytm_domain.'/oltp/HANDLER_INTERNAL/TXNSTATUS');
		define('PAYTM_STATUS_QUERY_NEW_URL', 'https://'.$paytm_domain.'/oltp/HANDLER_INTERNAL/getTxnStatus');
		define('PAYTM_TXN_URL', 'https://'.$paytm_domain.'/oltp-web/processTransaction');
		define('PAYTM_MERCHANT_KEY', 'wTYKqXnoezVACwjA'); 

       $cryptoModel = new Crypto;
       $checkSum = $cryptoModel->getChecksumFromArray($params,$params['Merchant_Key']);
	   //$out= json_encode(array("CHECKSUMHASH" => $checkSum,"ORDER_ID" => $params["ORDER_ID"], "payt_STATUS" => "1"));
       //$encrypted_data = encrypt($merchant_data, $working_key);
      //print_r($out);exit;
		//$url = "https://pguat.paytm.com/oltp-web/processTransaction?orderid=".$params['ORDER_ID'];
        echo '<form name="f1" id="2checkout" action="'.$paytm_txn_url.'" method="post">';
          foreach($params as $name => $value) {
                echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
            }
        echo ' <input type="hidden" name="CHECKSUMHASH" value="' .$checkSum . '">';
        echo '<script type="text/javascript">document.getElementById("2checkout").submit();</script>';
        echo '<input type="submit" value="Click here if you are not redirected automatically" /></form>';
        echo '</form>';
        }




       }
