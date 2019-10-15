<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
class ControllerExtensionPaymentDOKU extends Controller 
{
    
    public $ip_range = "103.10.129.";

    public function getServerConfig()
    {
				$dokuconfig = array();
        $data['oco_server_set'] = $this->config->get('payment_doku_server_set');
				
        if ( $data['oco_server_set']==0 )
        {				
						$dokuconfig['destination']      = 'https://staging.doku.com';
						$dokuconfig['oco_action']       = 'https://staging.doku.com/Suite/Receive';
						$dokuconfig['oco_check_status'] = 'http://staging.doku.com/Suite/CheckStatus';						
        }
        else
        {
						$dokuconfig['destination']      = 'https://pay.doku.com';
						$dokuconfig['oco_action']       = 'https://pay.doku.com/Suite/Receive';
						$dokuconfig['oco_check_status'] = 'https://pay.doku.com/Suite/CheckStatus';						
				}
				
				return $dokuconfig;
    }


    public function index() 
    {

        		$this->language->load('extension/payment/doku');
				$this->load->model('checkout/order');

				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
				        $serverconfig = $this->getUrlLocal();
				$data ['urlLocal'] = $serverconfig."/index.php?route=extension/payment/doku/redirect";
				$data ['urlSetProses'] = $serverconfig."/index.php?route=extension/payment/doku/processdoku";
                $data['button_confirm']         = $this->language->get('button_confirm');                
                $data['oco_transidmerchant']    = $this->session->data['order_id'];
				$data['payment_select']         = $this->config->get('payment_doku_channel_select');
				$data['select_payment']         = $this->language->get('select_payment');
				$data['payment_doku_name']      = $this->config->get('payment_doku_name');
				$data['payment_token']			= $this->config->get('payment_doku_tokenization');
				$data['payment_list'] 			= $this->config->get('payment_doku_channel_list');
				$data['payment_name'] 			= $this->config->get('payment_doku_channel_name_list');
				$data['payment_categori']		= $this->config->get('payment_doku_channel_categori');
				$data['oco_transidmerchant']    = $this->session->data['order_id'];
				$data['minamount']				= $this->config->get('payment_doku_installment_minimum');
				$data['amount'] 				= $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);

				if ($data['amount']>=$data['minamount']){
				$data['status_installment']		= $this->config->get('payment_doku_installment_status');
				$data['installment']			= $this->config->get('payment_doku_installment');
				}
    return $this->load->view('extension/payment/doku', $data);
		
    }	 

    public function getUrlLocal()
    {
    	    $myserverpath = explode ( "/", $_SERVER['PHP_SELF'] );
			if ( $myserverpath[1] <> 'admin' ) {
				$serverpath = '/' . $myserverpath[1];    
			} else {
				$serverpath = '';
			}
	
			if((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443){
				$myserverprotocol = "https";
			} else {
				$myserverprotocol = "http";    
			}
	
		$myservername = $_SERVER['SERVER_NAME'] . $serverpath;	
    return $myserverprotocol.'://'.$myservername;

    }

    public function processdoku()
    {
				if ( isset($this->request->post['TRANSIDMERCHANT']) )
				{
						$transidmerchant = $this->request->post['TRANSIDMERCHANT'];
						$this->load->model('checkout/order');        
						
						$this->model_checkout_order->addOrderHistory($transidmerchant, $this->config->get('payment_doku_companyid'), 'DOKU Payment Initiate', true);

				}
				else
				{
            echo "Stop : Access Not Valid";
						$this->log->write("DOKU Process Not in Correct Format - IP Logged ".$this->getipaddress());	    				
				}
    }


    public function getipaddress()    
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


    public function add_dokuonecheckout($datainsert) 
    {
        $SQL = "";
        
        foreach ( $datainsert as $field_name=>$field_data )
        {
            $SQL .= " $field_name = '$field_data',";
        }
        $SQL = substr( $SQL, 0, -1 );

        $this->db->query("INSERT INTO " . DB_PREFIX . "doku SET $SQL");
    }


	public function redirect()
	{
		if ( empty($this->request->post) )
        {
		echo "Stop : Access Not Valid";
		$this->log->write("DOKU Redirect Not in Correct Format - IP Logged ".$this->getipaddress());	    
            die;
        }

        $paymentchannel = $_POST['PAYMENTCHANNEL'];
       	$data['acquirer'] = '0';
        $pos = strpos($paymentchannel, '_');
        if ($pos){
       	$arrPaymentchannel = explode("_", $paymentchannel);
       	$paymentchannel = '15';
       	$data['acquirer'] = $arrPaymentchannel[0];
		$data['promo'] = $arrPaymentchannel[1];
       	$data['tenor'] = $arrPaymentchannel[2];
       	$data['type'] = $arrPaymentchannel[3];
        }
        $this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		if ($this->customer->isLogged())
		{	        
				$data['email'] 	          = $this->customer->getEmail();
				$data['telephone'] 	      = $this->customer->getTelephone();
								
				$this->load->model('account/address');
				
				$trx_data = $this->model_account_address->getAddress($this->session->data['payment_address']['address_id']);			    
		}
		elseif (isset($this->session->data['guest']))            
		{
				$data['email'] 	          = $this->session->data['guest']['email'];
				$data['telephone'] 	      = $this->session->data['guest']['telephone'];
						
				$trx_data = $this->session->data['payment_address'];
		}
		$data['tokenization']           = $this->config->get('payment_doku_tokenization');

		$serverconfig = $this->getServerConfig();
		$data['oco_action']             = $serverconfig['oco_action'];
		$data['oco_check_status']       = $serverconfig['oco_check_status'];
		$data['destination']			= $serverconfig['destination'];   				
		$data['oco_mallid']       		= $this->config->get('payment_doku_mallid');						
		$data['oco_sharedkey']    		= $this->config->get('payment_doku_shared');						
		$data['oco_chain']        		= $this->config->get('payment_doku_chain');
		$data['oco_amount'] 			= number_format($this->currency->format($order_info['total'], $order_info['currency_code'], false, false), 2, '.', '');
		$data['button_confirm']         = $this->language->get('button_confirm');                
        $data['oco_session_id']         = $this->session->getId();
		$data['oco_transidmerchant']    = $this->session->data['order_id'];
        $data['oco_words']              = sha1(trim($data['oco_amount']).
                                                     trim($data['oco_mallid']).
                                                     trim($data['oco_sharedkey']).
                                                     trim($data['oco_transidmerchant']));
		$data['oco_currency']           = 360; # IDR currency only : 360
        $data['oco_allname']            = $trx_data['firstname'] .' '.$trx_data['lastname'] ;        
        $data['oco_address']          = $trx_data['address_1'].' '.$trx_data['address_2'];
        $data['oco_city']               = $trx_data['city'];
        $data['oco_postcode']           = $trx_data['postcode'];
        $data['oco_zone']               = $trx_data['zone'];
        $data['oco_request_datetime']   = date("YmdHis");
		$data['select_payment']         = $this->language->get('select_payment');
        $data['oco_ip_address']         = $this->getipaddress();
		$data['oco_country_id']         = $trx_data['country_id'];
		$data['paymentchannel']         = $paymentchannel;
		$data['data_product']           = 'Transaction Transidmerchant '.
										   $data['oco_transidmerchant'].
										   ','.
										   $data['oco_amount'].
										   ',1,'.
										   $data['oco_amount'].
										   ';';


        $trx['ip_address']                    = $data['oco_ip_address'];
		$trx['process_datetime']              = date("Y-m-d H:i:s");
		$trx['process_type']                  = 'REQUEST';
        $trx['transidmerchant']               = $data['oco_transidmerchant'];
        $trx['amount']                        = $data['oco_amount'];
        $trx['words']                         = $data['oco_words'];
		$trx['session_id']                    = $data['oco_session_id'];
		$trx['payment_channel']               = $paymentchannel;
		$trx['message']             		  = "Transaction request start";

        $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->cart->clear();
		$this->add_dokuonecheckout($trx);

		if ($data['acquirer']<>'0'){
		$this->response->setOutput($this->load->view('extension/payment/doku_redirect', $data));
		}else if ($paymentchannel=='15' && $data['tokenization']=='1'){
		$data['paymentchannel']         = '16';
		$this->response->setOutput($this->load->view('extension/payment/doku_redirect', $data));
		} else if ($paymentchannel=='37'){
		$data['oco_mallid']       		= $this->config->get('payment_doku_mallid_kredivo');						
		$data['oco_sharedkey']    		= $this->config->get('payment_doku_shared_kredivo');						
		$data['oco_chain']        		= $this->config->get('payment_doku_chain_kredivo');
		$data['oco_words']              = sha1(trim($data['oco_amount']).
                                          trim($data['oco_mallid']).
                                          trim($data['oco_sharedkey']).
                                          trim($data['oco_transidmerchant']));	
		$this->response->setOutput($this->load->view('extension/payment/doku_redirect', $data));	
		} else if ($paymentchannel=='22'){
		$url 							= $data['destination'].'/api/payment/doGeneratePaymentCode';
		$bin 			        		= $this->config->get('payment_doku_bin_list');
		$data['bin_prefix']				= $bin[$paymentchannel];						
		$data['oco_mallid']       		= $this->config->get('payment_doku_mallid_va_sinarmas');						
		$data['oco_sharedkey']    		= $this->config->get('payment_doku_shared_va_sinarmas');						
		$data['oco_chain']        		= $this->config->get('payment_doku_chain_va_sinarmas');
		$data['oco_words']              = sha1(trim($data['oco_amount']).
                                          trim($data['oco_mallid']).
                                          trim($data['oco_sharedkey']).
                                          trim($data['oco_transidmerchant']).
                                          trim($data['oco_currency']));	
		
		$dataPayment = array(
  			  	'req_mall_id' => $data['oco_mallid'],
    			'req_chain_merchant' => $data['oco_chain'],
    			'req_amount' => $data['oco_amount'],
    			'req_words' => $data['oco_words'],
    			'req_trans_id_merchant' => $data['oco_transidmerchant'],
    			'req_purchase_amount' => $data['oco_currency'],
    			'req_request_date_time' => date('YmdHis'),
    			'req_session_id' => $data['oco_session_id'],
    			'req_email' => $data['email'],
    			'req_name' => $data['oco_allname']
				);

		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, 'data='. json_encode($dataPayment));
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

		$responseJson = curl_exec( $ch );

		curl_close($ch);

		if(is_string($responseJson)){
			$responsePayment = json_decode($responseJson);
		}else{
			$responsePayment = $responseJson;
		}
		if ($responsePayment->res_response_code == "0000"){

			$data['channel_name']		 = 'ATM Transfer Bank Sinarmas';
			$trx['status_code']          = $responsePayment->res_payment_code;
        	$trx['response_code']        = $responsePayment->res_payment_code;
			$trx['payment_channel']      = $data['paymentchannel'];
			$trx['doku_payment_datetime']= date("Y-m-d H:i:s");							
            $trx['result_msg']           = 'PENDING';          
        	$trx['session_id']       	 = $responsePayment->res_session_id;
			$trx['process_datetime']     = date("Y-m-d H:i:s");
			$trx['payment_code']		 = $data['bin_prefix'].$responsePayment->res_pay_code;
			$trx['process_type']         = 'PENDING_MH_VA';

			$data['payment_code']        = $trx['payment_code'];
			
			$trx['message'] = "Redirect process Payment channel using VA Sinarmas, transaction is pending for payment, with paymentcode ".$trx['payment_code'];  
			$data['return_message'] = "This is your Payment Code : ".$trx['payment_code']."<br>Please do the payment before expired.<br>If you need help for payment, please contact our customer service.<br>";	
			$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 1, $trx['message'],true);
  			$this->add_dokuonecheckout($trx);
  			$this->cart->clear();
			$this->response->setOutput($this->load->view('extension/payment/doku_pending_merchant_hosted', $data));
  			
  			} else {
  				$this->response->redirect($this->url->link('common/home', '', true));
  			}



		} else if ($paymentchannel=='40'){
		$url 							= $data['destination'].'/api/payment/doGeneratePaymentCode';
		$bin 			        		= $this->config->get('payment_doku_bin_list');
		$data['bin_prefix']				= $bin[$paymentchannel];
		$data['oco_mallid']       		= $this->config->get('payment_doku_mallid_va_bni');						
		$data['oco_sharedkey']    		= $this->config->get('payment_doku_shared_va_bni');						
		$data['oco_chain']        		= $this->config->get('payment_doku_chain_va_bni');
		$data['oco_words']              = sha1(trim($data['oco_amount']).
                                          trim($data['oco_mallid']).
                                          trim($data['oco_sharedkey']).
                                          trim($data['oco_transidmerchant']).
                                          trim($data['oco_currency']));
        		$dataPayment = array(
  			  	'req_mall_id' => $data['oco_mallid'],
    			'req_chain_merchant' => $data['oco_chain'],
    			'req_amount' => $data['oco_amount'],
    			'req_words' => $data['oco_words'],
    			'req_trans_id_merchant' => $data['oco_transidmerchant'],
    			'req_purchase_amount' => $data['oco_currency'],
    			'req_request_date_time' => date('YmdHis'),
    			'req_session_id' => $data['oco_session_id'],
    			'req_email' => $data['email'],
    			'req_name' => $data['oco_allname']
				);

		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, 'data='. json_encode($dataPayment));
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

		$responseJson = curl_exec( $ch );

		curl_close($ch);

		if(is_string($responseJson)){
			$responsePayment = json_decode($responseJson);
		}else{
			$responsePayment = $responseJson;
		}
		if ($responsePayment->res_response_code == "0000"){
			
			$data['channel_name']		 = 'ATM Transfer BNI';
			$trx['status_code']          = $responsePayment->res_payment_code;
        	$trx['response_code']        = $responsePayment->res_payment_code;
			$trx['payment_channel']      = $data['paymentchannel'];
			$trx['doku_payment_datetime']= date("Y-m-d H:i:s");							
            $trx['result_msg']           = 'PENDING';          
        	$trx['session_id']       	 = $responsePayment->res_session_id;
			$trx['process_datetime']     = date("Y-m-d H:i:s");
			$trx['payment_code']		 = $data['bin_prefix'].$responsePayment->res_pay_code;
			$trx['process_type']         = 'PENDING_MH_VA';
			
			$data['payment_code']        = $trx['payment_code'];

			$trx['message'] = "Redirect process Payment channel using VA BNI, transaction is pending for payment, with paymentcode ".$trx['payment_code'];  
			$data['return_message'] = "This is your Payment Code : ".$trx['payment_code']."<br>Please do the payment before expired.<br>If you need help for payment, please contact our customer service.<br>";	
			$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 1, $trx['message'],true);
  			$this->add_dokuonecheckout($trx);
  			$this->cart->clear();

			$this->response->setOutput($this->load->view('extension/payment/doku_pending_merchant_hosted', $data));

  			} else {
  				$this->response->redirect($this->url->link('common/home', '', true));
  			}
	
		} else if ($paymentchannel=='18'){
		$url 							= $data['destination'].'/Suite/ReceiveMIP';
		$data['oco_mallid']       		= $this->config->get('payment_doku_mallid_KlikpayBCA');						
		$data['oco_sharedkey']    		= $this->config->get('payment_doku_shared_KlikpayBCA');						
		$data['oco_chain']        		= $this->config->get('payment_doku_chain_KlikpayBCA');
		$data['oco_words']              = sha1(trim($data['oco_amount']).
                                          trim($data['oco_mallid']).
                                          trim($data['oco_sharedkey']).
                                          trim($data['oco_transidmerchant']));

    	$dataPayment = array(
  		'BASKET' => $data['data_product'],
  		'MALLID' => $data['oco_mallid'],
  		'CHAINMERCHANT' => $data['oco_chain'],
  		'CURRENCY' => $data['oco_currency'],
  		'PURCHASECURRENCY' => $data['oco_currency'],
  		'AMOUNT' => $data['oco_amount'],
  		'PURCHASEAMOUNT' => $data['oco_amount'],
  		'TRANSIDMERCHANT' => $data['oco_transidmerchant'],
  		'WORDS' => $data['oco_words'],
  		'REQUESTDATETIME' => $data['oco_request_datetime'],
  		'SESSIONID' => $data['oco_session_id'],
  		'PAYMENTCHANNEL' => $paymentchannel,
  		'EMAIL' => $data['email'],
  		'NAME' => $data['oco_allname'],
  		'ADDRESS' => $data['oco_address'],
  		'COUNTRY' => $data['oco_country_id'],
  		'STATE' => $data['oco_zone'],
  		'CITY' => $data['oco_city'],
  		'ZIPCODE' => $data['oco_postcode'],
  		'HOMEPHONE' => $data['telephone'],
  		'MOBILEPHONE' => $data['telephone'],
  		'WORKPHONE' => $data['telephone']	 
  		);	

    	$parameter = http_build_query($dataPayment) . "\n";
    	
		define('POSTURL' , $url);
		define('POSTVARS', $parameter);


  		$ch = curl_init(POSTURL);
  		curl_setopt($ch, CURLOPT_POST, 1);
  		curl_setopt($ch, CURLOPT_POSTFIELDS, POSTVARS);
  		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  		curl_setopt($ch, CURLOPT_HEADER, 0);
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  		curl_setopt($ch, CURLOPT_TIMEOUT, 18);
  		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  		$GETDATARESULT = curl_exec($ch);
 	 	curl_close($ch);

 	 	if (strstr($GETDATARESULT,'STOP')){
  		$this->response->redirect($this->url->link('common/home', '', true));
 	 	} else {
	    $xml = simplexml_load_string($GETDATARESULT);
	    $data['bca_action'] = $xml->REDIRECTURL;
	  	$REDIRECTPARAMETER = $xml->REDIRECTPARAMETER;
	  	$data_array = explode( ";;", $REDIRECTPARAMETER);
		$data_raw   = array_map('urldecode', $data_array);

		$descp				= $data_raw[0];
		$descp_array 		= explode( "||", $descp);
		$data['descp']		= $descp_array[1];

		$klikPayCode		= $data_raw[1];
		$klikPayCode_array 	= explode( "||", $klikPayCode);
		$data['klikPayCode']= $klikPayCode_array[1];

		$callback			= $data_raw[2];
		$callback_array 	= explode( "||", $callback);
		$data['callback']	= $callback_array[1];

		$totalAmount		= $data_raw[3];
		$totalAmount_array 	= explode( "||", $totalAmount);
		$data['totalAmount']= $totalAmount_array[1];

		$payType			= $data_raw[4];
		$payType_array 		= explode( "||", $payType);
		$data['payType']	= $payType_array[1];

		$transactionNo				= $data_raw[5];
		$transactionNo_array 		= explode( "||", $transactionNo);
		$data['transactionNo']		= $transactionNo_array[1];

		$signature				= $data_raw[6];
		$signature_array 		= explode( "||", $signature);
		$data['signature']		= $signature_array[1];

		$transactionDate				= $data_raw[7];
		$transactionDate_array	 		= explode( "||", $transactionDate);
		$data['transactionDate']		= $transactionDate_array[1];

		$currency 				= $data_raw[8];
		$currency_array 		= explode( "||", $currency);
		$data['currency']		= $currency_array[1];

		$this->response->setOutput($this->load->view('extension/payment/doku_klikpaybca', $data));
	}

		} else {
		$this->response->setOutput($this->load->view('extension/payment/doku_redirect', $data));				
		}

	}		      

	public function dokuidentify()
	{
            if ( empty($this->request->post) )
            {
                echo "Stop : Access Not Valid";
				$this->log->write("DOKU Identify Not in Correct Format - IP Logged ".$this->getipaddress());	    
                die;
            }
                            
            if (substr($this->getipaddress(),0,strlen($this->ip_range)) !== $this->ip_range)
            {
                echo "Stop : IP Not Allowed";
								$this->log->write("DOKU Identify From IP Not Allowed - IP Logged ".$this->getipaddress());
            }
            else
            {
                $this->load->model('checkout/order');

            	$trx['payment_code']	 = $this->request->post['PAYMENTCODE'];
                $trx['amount']           = $this->request->post['AMOUNT'];
                $trx['transidmerchant']  = $this->request->post['TRANSIDMERCHANT']; 
                $trx['payment_channel']  = $this->request->post['PAYMENTCHANNEL'];
                $trx['session_id']       = $this->request->post['SESSIONID'];
                $trx['process_datetime'] = date("Y-m-d H:i:s");
                $trx['process_type']     = 'IDENTIFY';
                $trx['ip_address']       = $this->getipaddress();
                if ($trx['payment_code']){
				$trx['message']          = "Identify process message come from DOKU and payment code : ".$trx['payment_code'];
                } else {
                $trx['message']          = "Identify process message come from DOKU";
                }

                $this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 15, $trx['message'], true);
                $this->add_dokuonecheckout($trx);
                echo "Continue";
            }       	
	}		      

	public function dokunotify()
	{
        if ( empty($this->request->post) )
        {
            echo "Stop : Access Not Valid";
						$this->log->write("DOKU Notify Not in Correct Format - IP Logged ".$this->getipaddress());	    
            die;
        }
        	$trx['words']                     = $this->request->post['WORDS'];
			$trx['amount']                    = $this->request->post['AMOUNT'];
            $trx['transidmerchant']           = $this->request->post['TRANSIDMERCHANT'];
            $trx['result_msg']                = $this->request->post['RESULTMSG'];            
            $trx['verify_status']             = $this->request->post['VERIFYSTATUS'];
			$trx['ip_address']            	  = $this->getipaddress();
			$trx['response_code']         	  = $this->request->post['RESPONSECODE'];
			$trx['approval_code']         	  = $this->request->post['APPROVALCODE'];
			$trx['payment_channel']       	  = $this->request->post['PAYMENTCHANNEL'];
			$trx['payment_code']          	  = $this->request->post['PAYMENTCODE'];
			$trx['session_id']            	  = $this->request->post['SESSIONID'];
			$trx['bank_issuer']           	  = $this->request->post['BANK'];
			$trx['creditcard']            	  = $this->request->post['MCN'];                   
			$trx['doku_payment_datetime'] 	  = $this->request->post['PAYMENTDATETIME'];
			$trx['process_datetime']      	  = date("Y-m-d H:i:s");
			$trx['verify_id']             	  = $this->request->post['VERIFYID'];
			$trx['verify_score']          	  = $this->request->post['VERIFYSCORE'];
			$trx['notify_type']           	  = $this->request->post['STATUSTYPE'];
			
			switch ( $trx['notify_type'] )
			{
					case "P":
					$trx['process_type'] = 'NOTIFY';
					break;
			
					case "V":
					$trx['process_type'] = 'REVERSAL';
					break;
			}	
			$result = $this->checkTrx($trx);

			if ( $result < 1 )
			{
					echo "Stop : Transaction Not Found";
					$this->log->write("DOKU Notify Can Not Find Transactions - IP Logged ".$this->getipaddress());
					die;		    
					}
					else
					{										
					$this->load->model('checkout/order');
					$this->cart->clear();

					$use_edu = intval($this->config->get('payment_doku_review'));
					switch (TRUE)
					{
							case ( $trx['result_msg']=="SUCCESS" && $trx['notify_type']=="P" && in_array($trx['payment_channel'], array("05","14","22","29","31","32","33","34","35","36","40","41","42","43","44")) ):
							$trx['message'] = "Notify process message come from DOKU. Payment Success : Completed";
							$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 5, $trx['message'], true);												
							break;

							case ( $trx['result_msg']=="SUCCESS" && $trx['notify_type']=="P" && $use_edu == 1 ):
							$trx['message'] = "Notify process message come from DOKU. Payment success but wait for EDU verification : Processed";
							$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 15, $trx['message'], true);
							break;

							case ( $trx['result_msg']=="SUCCESS" && $trx['notify_type']=="P" && $use_edu == 0 ):
							$trx['message'] = "Notify process message come from DOKU. Payment Success : Completed";
							$this->model_checkout_order->addOrderHistory($trx['transidmerchant'],  5, $trx['message'], true);												
							break;

							case ( $trx['notify_type']=="V" ):
							$trx['message'] = "Notify process message come from DOKU. Payment Void by EDU : Denied";
							$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 8, $trx['message']);
							break; 

							default:
							$trx['message'] = "Notify process message come from DOKU. Payment Failed by default : Cancelled";
							$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 10, $trx['message']);
							break;

					}
					$this->add_dokuonecheckout($trx);
										
					echo "Continue";

		}
	}		      

	public function dokuredirect()
	{
        if ($this->request->post){
		$trx['words']                = $this->request->post['WORDS'];
        $trx['amount']               = $this->request->post['AMOUNT'];
        $trx['transidmerchant']      = $this->request->post['TRANSIDMERCHANT']; 
        $trx['status_code']          = $this->request->post['STATUSCODE'];
		$trx['payment_code']         = $this->request->post['PAYMENTCODE'];
		$trx['payment_channel']  	 = $this->request->post['PAYMENTCHANNEL'];
		$trx['session_id']       	 = $this->request->post['SESSIONID'];
        } else if ($this->request->get){
        $trx['words']                = $this->request->get['WORDS'];
        $trx['amount']               = $this->request->get['AMOUNT'];
        $trx['transidmerchant']      = $this->request->get['TRANSIDMERCHANT']; 
        $trx['status_code']          = $this->request->get['STATUSCODE'];
		$trx['payment_code']         = $this->request->get['PAYMENTCODE'];
		$trx['payment_channel']  	 = $this->request->get['PAYMENTCHANNEL'];
		$trx['session_id']       	 = $this->request->get['SESSIONID'];
        }
        $serverconfig = $this->getServerConfig();
		$this->load->model('checkout/order');
		$use_edu  = intval($this->config->get('payment_doku_review'));
		
		$trx['ip_address']       = $this->getipaddress();
		$trx['process_datetime'] = date("Y-m-d H:i:s");
		$trx['process_type']     = 'REDIRECT';

				if ( in_array($trx['payment_channel'], array("05","14","22","29","31","32","33","34","35","36","40","41","42","43","44")) && $trx['status_code'] == "5511" ){
				$trx['message'] = "Redirect process come from DOKU. Payment channel using ATM Transfer / Convenience Store, transaction is pending for payment";  
				$status         = "pending";									
				$data['return_message'] = "This is your Payment Code : ".$trx['payment_code']."<br>Please do the payment before expired.<br>If you need help for payment, please contact our customer service.<br>";																
				$data['heading_title']	='Your Transaction is Waiting for Your Payment';	
				$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 1, $trx['message']);
				} else {

				$result = $this->checkTrx($trx, 'NOTIFY', 'SUCCESS');

				if ( $result < 1 ){
					$trx['message'] = "Redirect process with no notify message come from DOKU. Transaction is Failed. Please check on Back Office."; 
					$status         = "failed";				
					$data['return_message'] = "Your payment is failed. Please check your payment detail or please try again later.";
					$data['heading_title']	='Your Transaction is Failed';	
					$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 10, $trx['message'], true);
				} else {
					if ( intval($use_edu) == 1 && $trx['payment_channel']== "15")
					{					
						$trx['message'] = "Redirect process with no notify message come from DOKU. Transaction is Success, wait for EDU Verification. Please check on Back Office.";  
						$status         = "on-hold";									
						$data['return_message'] = "Thank you for shopping with us. We will process your payment soon.";
						$data['heading_title']	='Your Transaction is Waiting for Payment Verification<br />Please Wait While We Verified Your Payment';	
					    $this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 15, $trx['message'], true);
					}
					else
					{
							$trx['message'] = "Redirect process with no notify message come from DOKU. Transaction is Success. Please check on Back Office.";  
							$status         = "completed";				
							$data['return_message'] = "Your payment is success. We will process your order. Thank you for shopping with us.";
							$data['heading_title']	='Your Transaction is Success';	
							$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 5, $trx['message'], true);
					}				
				}

				}

				$this->add_dokuonecheckout($trx);
        		$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				$this->response->setOutput($this->load->view('extension/payment/doku_result', $data));				

	}		      

	public function dokureview()
	{
        if ( empty($this->request->post) )
        {
            echo "Stop : Access Not Valid";
						$this->log->write("DOKU Review Not in Correct Format - IP Logged ".$this->request->server['REMOTE_ADDR']);	    
            die;
        }
        $use_review = $this->config->get('payment_doku_review');
        if ( $use_review==1 )
        {                           
        	    $serverconfig = $this->getServerConfig();
        	    $data['oco_mallid']       		= $this->config->get('payment_doku_mallid');						
				$data['oco_sharedkey']    		= $this->config->get('payment_doku_shared');						
				$data['oco_chain']        		= $this->config->get('payment_doku_chain');
                $trx['amount']                	= $this->request->post['AMOUNT'];
                $trx['transidmerchant']       	= $this->request->post['TRANSIDMERCHANT'];
                $trx['result_msg']            	= $this->request->post['RESULTMSG'];            
                $trx['verify_status']         	= $this->request->post['VERIFYSTATUS'];        
                $trx['words']                 	= $this->request->post['WORDS'];
                                                
                $words = sha1(trim($trx['amount']).
                              trim($serverconfig['oco_mallid']).
                              trim($serverconfig['oco_sharedkey']).
                              trim($trx['transidmerchant']).
                              trim($trx['result_msg']).
                              trim($trx['verify_status']));

                if ( $trx['words']==$words )
                {
                	$trx['process_datetime']      = date("Y-m-d H:i:s");
                    $trx['process_type']          = 'REVIEW';
                    $trx['ip_address']            = $this->getipaddress();
                    $trx['notify_type']           = $this->request->post['STATUSTYPE'];                
                    $trx['response_code']         = $this->request->post['RESPONSECODE'];
                    $trx['approval_code']         = $this->request->post['APPROVALCODE'];
                    $trx['payment_channel']       = $this->request->post['PAYMENTCHANNEL'];
                    $trx['payment_code']          = $this->request->post['PAYMENTCODE'];
                    $trx['session_id']            = $this->request->post['SESSIONID'];
                    $trx['bank_issuer']           = $this->request->post['BANK'];
                    $trx['creditcard']            = $this->request->post['MCN'];                   
                    $trx['doku_payment_datetime'] = $this->request->post['PAYMENTDATETIME'];
                    $trx['verify_id']             = $this->request->post['VERIFYID'];
                    $trx['verify_score']          = $this->request->post['VERIFYSCORE'];

                    $result = $this->checkTrx($trx);
                    
                    if ( $result < 1 )
                    {
                        echo "Stop : Transaction Not Found";
						$this->log->write("DOKU Notify Can Not Find Transactions - IP Logged ".$this->request->server['REMOTE_ADDR']);
                        die;            
                    }
                    else
                    { 	                   
                        $this->load->model('checkout/order');
                        $this->add_dokuonecheckout($trx);

                    	switch (TRUE)
                        {
                            case ( $trx['verify_status']=="APPROVE" ):
                            $this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 5, 'Payment Process Success'.$trx['verify_status'], true);
                            break;
                            
                            case ( $trx['verify_status']=="REVIEW" ):
                            $this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 5, 'Payment Process Success'.$trx['verify_status'], true);
                            break;
                            
                            case ( $trx['verify_status']=="REJECT" || $trx['verify_status']=="HIGHRISK" || $trx['verify_status']=="NA" ):
                            $this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 8, 'DOKU Verification result is bad : '.$trx['verify_status'], true);
                            break;
                            
                            default:
                            $this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 10, 'DOKU Verification result is bad', true);
                            break;
                        }
                        
                        echo "Continue";

                }                             

        }  else {
                	echo "Stop : Request Not Valid";
					$this->log->write("DOKU Redirect Words Not Correct - IP Logged ".$this->request->server['REMOTE_ADDR']);	    
                    die;                    
                }
        } else {
        	         echo "Stop : Request Not Valid";
					$this->log->write("DOKU Redirect Not Use EDU - IP Logged ".$this->request->server['REMOTE_ADDR']);	    
                    die;                    
        	}

	}	

	public function checkTrx($trx, $process='REQUEST', $result_msg='')
    {
				if ( $result_msg == "PENDING" ) return 0;
				
				$check_result_msg = "";
				if ( !empty($result_msg) )
				{
					$check_result_msg = " AND result_msg = '$result_msg'";
				}		
		
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "doku" .
																	" WHERE process_type = '$process'" .
																	$check_result_msg.
																	" AND transidmerchant = '" . $trx['transidmerchant'] . "'" .
																	" AND amount = '". $trx['amount'] . "'".
																	" AND session_id = '". $trx['session_id'] . "'" );        
        return $query->num_rows;
    }


}