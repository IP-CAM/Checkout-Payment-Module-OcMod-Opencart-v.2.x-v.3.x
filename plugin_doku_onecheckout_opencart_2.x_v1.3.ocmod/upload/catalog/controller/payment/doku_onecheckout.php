<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DOKU Onecheckout
 *
 * @lordsanjay
 */
 
class ControllerPaymentDOKUOnecheckout extends Controller 
{
    
    public $ip_range = "103.10.129.";

    public function getServerConfig()
    {
				$dokuconfig = array();
        $data['oco_server_set'] = $this->config->get('doku_onecheckout_server_set');
				
        if ( $data['oco_server_set']==0 )
        {				
        				$dokuconfig['env']				= "getForm(data,'staging')";
						$dokuconfig['destination']      = 'https://staging.doku.com';
						$dokuconfig['oco_action']       = 'https://staging.doku.com/Suite/Receive';
						$dokuconfig['oco_mallid']       = $this->config->get('doku_onecheckout_mallid_dev');						
						$dokuconfig['oco_sharedkey']    = $this->config->get('doku_onecheckout_sharedkey_dev');						
						$dokuconfig['oco_chain']        = $this->config->get('doku_onecheckout_chain_dev');						
						$dokuconfig['oco_check_status'] = 'http://staging.doku.com/Suite/CheckStatus';						
        }
        else
        {
        				$dokuconfig['env']				= "getForm(data)";
						$dokuconfig['destination']      = 'https://pay.doku.com';
						$dokuconfig['oco_action']       = 'https://pay.doku.com/Suite/Receive';
						$dokuconfig['oco_mallid']       = $this->config->get('doku_onecheckout_mallid_prod');						
						$dokuconfig['oco_sharedkey']    = $this->config->get('doku_onecheckout_sharedkey_prod');						
						$dokuconfig['oco_chain']        = $this->config->get('doku_onecheckout_chain_prod');						
						$dokuconfig['oco_check_status'] = 'https://pay.doku.com/Suite/CheckStatus';						
				}
				
				return $dokuconfig;
    }
    
    public function processdoku()
    {
				if ( isset($this->request->post['TRANSIDMERCHANT']) )
				{
						$transidmerchant = $this->request->post['TRANSIDMERCHANT'];
						$this->load->model('checkout/order');        
						
						$this->model_checkout_order->addOrderHistory($transidmerchant, $this->config->get('doku_onecheckout_order_status_id'), 'DOKU Payment Initiate');
				}
				else
				{
            echo "Stop : Access Not Valid";
						$this->log->write("DOKU Process Not in Correct Format - IP Logged ".$this->getipaddress());	    				
				}
    }
    
    public function index() 
    {
        $data = array();
        		$this->language->load('payment/doku_onecheckout');
				$this->load->model('checkout/order');

				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $serverconfig = $this->getServerConfig();
                $data['button_confirm']         = $this->language->get('button_confirm');                
                $data['oco_transidmerchant']    = $this->session->data['order_id'];
				$data['payment_select']         = $this->config->get('doku_onecheckout_payment_select');
				$data['payment_select_merchant_hosted']         = $this->config->get('doku_onecheckout_merchant_hosted_status');
				$data['select_payment']         = $this->language->get('select_payment');
				if ( $data['payment_select']==1 )
				{
						$data['payment_list'] =  $this->config->get('doku_onecheckout_payment_list');
						$data['payment_name'] =  $this->config->get('doku_onecheckout_payment_name');
				}    

				if ( $data['payment_select_merchant_hosted']==1 )
				{
						$data['oco_mallid_merchant_hosted']       = $this->config->get('doku_onecheckout_mallid_merchant_hosted');				
						$data['oco_sharedkey_merchant_hosted']    = $this->config->get('doku_onecheckout_sharedkey_merchant_hosted');
						$data['oco_chain_merchant_hosted']        = $this->config->get('doku_onecheckout_chain_merchant_hosted');
						$data['payment_list_merchant_hosted'] =  $this->config->get('doku_onecheckout_payment_merchant_hosted_list');
						$data['payment_name_merchant_hosted'] =  $this->config->get('doku_onecheckout_payment_merchant_hosted_name');
				}		

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/doku_onecheckout.tpl')) 
				{
						return $this->load->view($this->config->get('config_template') . '/template/payment/doku_onecheckout.tpl', $data);
				} 
				else 
				{
						return $this->load->view('default/template/payment/doku_onecheckout.tpl', $data);
				}
		
    }	 
	
    public function dokuidentify()
    {    
        $use_identify = $this->config->get('doku_onecheckout_identify');
        if ( intval($use_identify) == 1 )
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
                
                $trx['amount']           = $this->request->post['AMOUNT'];
                $trx['transidmerchant']  = $this->request->post['TRANSIDMERCHANT']; 
                $trx['payment_channel']  = $this->request->post['PAYMENTCHANNEL'];
                $trx['session_id']       = $this->request->post['SESSIONID'];
                $trx['process_datetime'] = date("Y-m-d H:i:s");
                $trx['process_type']     = 'IDENTIFY';
                $trx['ip_address']       = $this->getipaddress();
								$trx['message']          = "Identify process message come from DOKU";
                
                $this->model_checkout_order->update($trx['transidmerchant'], 15, 'DOKU Payment Process');
                $this->add_dokuonecheckout($trx);
            }
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
                        
        if (substr($this->getipaddress(),0,strlen($this->ip_range)) !== $this->ip_range)
        {
            echo "Stop : IP Not Allowed";
						$this->log->write("DOKU Notify From IP Not Allowed - IP Logged ".$this->getipaddress());
				}
        else
        {	    
						$trx = array();
	        
						$trx['words']                     = $this->request->post['WORDS'];
						$trx['amount']                    = $this->request->post['AMOUNT'];
            $trx['transidmerchant']           = $this->request->post['TRANSIDMERCHANT'];
            $trx['result_msg']                = $this->request->post['RESULTMSG'];            
            $trx['verify_status']             = $this->request->post['VERIFYSTATUS'];	    
	        
            $serverconfig = $this->getServerConfig();
            
						$words = sha1(trim($trx['amount']).
													trim($serverconfig['oco_mallid']).
													trim($serverconfig['oco_sharedkey']).
													trim($trx['transidmerchant']).
													trim($trx['result_msg']).
                          trim($trx['verify_status']));

						if ( $trx['words']==$words )
						{		    
								$trx['ip_address']            = $this->getipaddress();
								$trx['response_code']         = $this->request->post['RESPONSECODE'];
								$trx['approval_code']         = $this->request->post['APPROVALCODE'];
								$trx['payment_channel']       = $this->request->post['PAYMENTCHANNEL'];
								$trx['payment_code']          = $this->request->post['PAYMENTCODE'];
								$trx['session_id']            = $this->request->post['SESSIONID'];
								$trx['bank_issuer']           = $this->request->post['BANK'];
								$trx['creditcard']            = $this->request->post['MCN'];                   
								$trx['doku_payment_datetime'] = $this->request->post['PAYMENTDATETIME'];
								$trx['process_datetime']      = date("Y-m-d H:i:s");
								$trx['verify_id']             = $this->request->post['VERIFYID'];
								$trx['verify_score']          = $this->request->post['VERIFYSCORE'];
								$trx['notify_type']           = $this->request->post['STATUSTYPE'];
							
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

										$use_edu = intval($this->config->get('doku_onecheckout_review_edu'));
									
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
												$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 5, $trx['message'], true);												
												break;

												case ( $trx['result_msg']=="FAILED" && $trx['notify_type']=="P" ):
												$trx['message'] = "Notify process message come from DOKU. Payment Failed";
												$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 10, $trx['message'], true);
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
						else
						{
								echo "Stop : Request Not Valid";
								$this->log->write("DOKU Notify Words Not Correct - IP Logged ".$this->getipaddress());
								die;
						}
        }        
    }
        
    public function dokuredirect()
    {
        if ( empty($this->request->post) )
        {
            echo "Stop : Access Not Valid";
						$this->log->write("DOKU Redirect Not in Correct Format - IP Logged ".$this->getipaddress());	    
            die;
        }
        
		$trx['words']                = $this->request->post['WORDS'];
        $trx['amount']               = $this->request->post['AMOUNT'];
        $trx['transidmerchant']      = $this->request->post['TRANSIDMERCHANT']; 
        $trx['status_code']          = $this->request->post['STATUSCODE'];
        
        if ( isset($this->request->post['PAYMENTCODE']) )
				{
						$trx['payment_code']                 = $this->request->post['PAYMENTCODE'];
						$this->session->data['payment_code'] = $this->request->post['PAYMENTCODE'];
				}

        $serverconfig = $this->getServerConfig();
        
        $words = sha1(trim($trx['amount']).
											trim($serverconfig['oco_sharedkey']).
											trim($trx['transidmerchant']).
											trim($trx['status_code']));
        
        if ( $trx['words']==$words )
        {
						$this->load->model('checkout/order');
						$use_edu  = intval($this->config->get('doku_onecheckout_review_edu'));
						
						$trx['payment_channel']  = $_POST['PAYMENTCHANNEL'];
						$trx['session_id']       = $_POST['SESSIONID'];
						$trx['ip_address']       = $this->getipaddress();
						$trx['process_datetime'] = date("Y-m-d H:i:s");
						$trx['process_type']     = 'REDIRECT';
						
						# Skip notify checking for VA / ATM / ALFA Payment
						if ( in_array($trx['payment_channel'], array("05","14")) && $trx['status_code'] == "5511" )
						{
								$trx['message'] = "Redirect process come from DOKU. Payment channel using VA / Alfa, transaction is pending for payment";  
								$status         = "pending";									
								$return_message = "This is your Payment Code : ".$trx['payment_code']."<br>Please do the payment before expired.<br>If you need help for payment, please contact our customer service.<br>";																		
								$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 1, $trx['message']);
						}
						else
						{
					
								switch ($trx['status_code'])
								{
										case "0000":
										$result_msg = "SUCCESS";
										break;
										
										default:
										$result_msg = "FAILED";
										break;
								}
									
								$result = $this->checkTrx($trx, 'NOTIFY', $result_msg);
								
								if ( $result < 1 )
								{
										$check_result_msg = $this->doku_check_status($trx);
										
										if ( $check_result_msg == 'SUCCESS' )
										{
												if ( intval($use_edu) == 1 )
												{					
														$trx['message'] = "Redirect process with no notify message come from DOKU. Transaction is Success, wait for EDU Verification. Please check on Back Office.";  
														$status         = "on-hold";									
														$return_message = "Thank you for shopping with us. We will process your payment soon.";
												    $this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 15, $trx['message'], true);														
												}
												else
												{
														$trx['message'] = "Redirect process with no notify message come from DOKU. Transaction is Success. Please check on Back Office.";  
														$status         = "completed";				
														$return_message = "Your payment is success. We will process your order. Thank you for shopping with us.";
														$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 5, $trx['message'], true);																										
												}				
										}
										else
										{
												$trx['message'] = "Redirect process with no notify message come from DOKU. Transaction is Failed. Please check on Back Office."; 
												$status         = "failed";				
												$return_message = "Your payment is failed. Please check your payment detail or please try again later.";
												$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 10, $trx['message'], true);												
										}
								}
								else
								{								
										if ( $trx['status_code']=="0000" )
										{
												if ( intval($use_edu) == 1 )
												{					
														$trx['message'] = "Redirect process message come from DOKU. Transaction is Success, wait for EDU Verification";  
														$status         = "on-hold";									
														$return_message = "Thank you for shopping with us. We will process your payment soon.";
														$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 15, $trx['message']);
												}
												else
												{
														$trx['message'] = "Redirect process message come from DOKU. Transaction is Success";  
														$status         = "completed";				
														$return_message = "Your payment is success. We will process your order. Thank you for shopping with us.";
														$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 5, $trx['message']);
												}
										}
										else
										{
												$trx['message'] = "Redirect process message come from DOKU. Transaction is Failed";  
												$status         = "failed";				
												$return_message = "Your payment is failed. Please check your payment detail or please try again later.";
												$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 10, $trx['message']);												
										}														
								}																																			

						}

						# Insert transaction redirect to table onecheckout
						$this->add_dokuonecheckout($trx);                    
												
						switch ( $status )
						{
								case "completed":
								$this->dokusuccess();
								break;
								
								case "failed":
								$this->dokucancel();		
								break;
						
								case "pending":
								$this->dokupending();
								break;
								
								case "on-hold":
								$this->dokuonhold();
								break;
						}						
						
				}
        else
        {
            echo "Stop : Request Not Valid";
						$this->log->write("DOKU Redirect Words Not Correct - IP Logged ".$this->getipaddress());	    
						die;
        }  
    }
           
    public function dokureview()
    {        
        if ( empty($this->request->post) )
        {
            echo "Stop : Access Not Valid";
						$this->log->write("DOKU Review Not in Correct Format - IP Logged ".$this->request->server['REMOTE_ADDR']);	    
            die;
        }

        $use_review = $this->config->get('doku_onecheckout_review_edu');
        if ( $use_review==1 )
        {                           
            if (substr($this->request->server['REMOTE_ADDR'],0,strlen($this->ip_range)) !== $this->ip_range)
            {
                echo "Stop : IP Not Allowed";
								$this->log->write("DOKU Review From IP Not Allowed - IP Logged ".$this->request->server['REMOTE_ADDR']);
            }
            else
            {
                $serverconfig = $this->getServerConfig();

                $trx['amount']                = $this->request->post['AMOUNT'];
                $trx['transidmerchant']       = $this->request->post['TRANSIDMERCHANT'];
                $trx['result_msg']            = $this->request->post['RESULTMSG'];            
                $trx['verify_status']         = $this->request->post['VERIFYSTATUS'];        
                $trx['words']                 = $this->request->post['WORDS'];
                                                
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
                }
                else
                {
                    echo "Stop : Request Not Valid";
										$this->log->write("DOKU Redirect Words Not Correct - IP Logged ".$this->request->server['REMOTE_ADDR']);	    
                    die;                    
                }
            }        
        }
    }

    public function doku_check_status($transaction)
    {		
				$serverconfig = $this->getServerConfig();
				$result = $this->getCheckStatusList($transaction);
				
				if ( empty($result) )
				{
						return "FAILED";
				}

				$trx     = $result;
				
				$words   = sha1( 	trim($serverconfig['oco_mallid']).
													trim($serverconfig['oco_sharedkey']).
													trim($trx['transidmerchant']) );
														
				$data = "MALLID=".$serverconfig['oco_mallid']."&CHAINMERCHANT=".$serverconfig['oco_chain']."&TRANSIDMERCHANT=".$trx['transidmerchant']."&SESSIONID=".$trx['session_id']."&PAYMENTCHANNEL=&WORDS=".$words;
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $serverconfig['oco_check_status']);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); 
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, true);        
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$output = curl_exec($ch);
				$curl_errno = curl_errno($ch);
				$curl_error = curl_error($ch);
				curl_close($ch);        
				
				if ($curl_errno > 0)
				{
						#return "Stop : Connection Error";
				}             
				
				libxml_use_internal_errors(true);
				$xml = simplexml_load_string($output);
				
				if ( !$xml )
				{
						$this->updateCountCheckStatusTrx($transaction);
				}                
				else
				{
						$trx = array();
						$trx['ip_address']            = $this->getipaddress();
						$trx['process_type']          = "CHECKSTATUS";
						$trx['process_datetime']      = date("Y-m-d H:i:s");
						$trx['transidmerchant']       = (string) $xml->TRANSIDMERCHANT;
						$trx['amount']                = (string) $xml->AMOUNT;
						$trx['notify_type']           = (string) $xml->STATUSTYPE;
						$trx['response_code']         = (string) $xml->RESPONSECODE;
						$trx['result_msg']            = (string) $xml->RESULTMSG;
						$trx['approval_code']         = (string) $xml->APPROVALCODE;
						$trx['payment_channel']       = (string) $xml->PAYMENTCHANNEL;
						$trx['payment_code']          = (string) $xml->PAYMENTCODE;
						$trx['words']                 = (string) $xml->WORDS;
						$trx['session_id']            = (string) $xml->SESSIONID;
						$trx['bank_issuer']           = (string) $xml->BANK;
						$trx['creditcard']            = (string) $xml->MCN;
						$trx['verify_id']             = (string) $xml->VERIFYID;
						$trx['verify_score']          = (int) $xml->VERIFYSCORE;
						$trx['verify_status']         = (string) $xml->VERIFYSTATUS;            
						
						# Insert transaction check status to table onecheckout
						$this->add_dokuonecheckout($trx);
						
						return $xml->RESULTMSG;
				}						
    }
        
    public function dokucancel()
    {
        $this->load->model('checkout/order');
        $this->response->redirect($this->url->link('checkout/doku_cancel'));
    }

    public function dokusuccess() 
    {
        $this->load->model('checkout/order');
        $this->response->redirect($this->url->link('checkout/doku_success'));
    }

    public function dokupending()
    {
        $this->load->model('checkout/order');
        $this->response->redirect($this->url->link('checkout/doku_pending'));
    }

    public function dokuonhold()
    {
        $this->load->model('checkout/order');
        $this->response->redirect($this->url->link('checkout/doku_onhold'));
    }

    public function dokutest()
    {
        $this->load->model('checkout/order');
        $this->response->redirect($this->url->link('checkout/doku_test'));
    }
		
    public function checkTrx($trx, $process='REQUEST', $result_msg='')
    {
				if ( $result_msg == "PENDING" ) return 0;
				
				$check_result_msg = "";
				if ( !empty($result_msg) )
				{
					$check_result_msg = " AND result_msg = '$result_msg'";
				}		
		
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "dokuonecheckout" .
																	" WHERE process_type = '$process'" .
																	$check_result_msg.
																	" AND transidmerchant = '" . $trx['transidmerchant'] . "'" .
																	" AND amount = '". $trx['amount'] . "'".
																	" AND session_id = '". $trx['session_id'] . "'" );        
        return $query->num_rows;
    }

		public function getCheckStatusList($trx='')
		{
				$query = "";
				if ( !empty($trx) )
				{
						$query  = " AND transidmerchant = '".$trx['transidmerchant']."'";
						$query .= " AND amount = '". $trx['amount'] . "'";
						$query .= " AND session_id = '". $trx['session_id'] . "'";
				}
				else
				{
						$query  = " AND check_status = 0";
				}
				
				$result = $this->db->query(	"SELECT * FROM ". DB_PREFIX ."dokuonecheckout" .
																		" WHERE process_type = 'REQUEST'" .
																		$query.
																		" AND count_check_status < 3" .
																		" ORDER BY trx_id DESC LIMIT 1" );
																		
				return $result->row;
		}			

		public function updateCountCheckStatusTrx($trx)
		{
				$this->db->query(	"UPDATE ". DB_PREFIX ."dokuonecheckout" .
													" SET count_check_status = count_check_status + 1,".
													" check_status = 0".
													" WHERE process_type = 'REQUEST'" .
													" AND transidmerchant = '" . $trx['transidmerchant'] . "'" .
													" AND amount = '". $trx['amount'] . "'".
													" AND session_id = '". $trx['session_id'] . "'" );        
		}			
		
    public function add_dokuonecheckout($datainsert) 
    {
        $SQL = "";
        
        foreach ( $datainsert as $field_name=>$field_data )
        {
            $SQL .= " $field_name = '$field_data',";
        }
        $SQL = substr( $SQL, 0, -1 );

        $this->db->query("INSERT INTO " . DB_PREFIX . "dokuonecheckout SET $SQL");
    }
     
    public function getipaddress()    
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

        public function redirect() 
    {
    	    	  if ( empty($this->request->post) )
        {
			            echo "Stop : Access Not Valid";
						$this->log->write("DOKU Redirect Not in Correct Format - IP Logged ".$this->getipaddress());	    
            die;
        }

        $data = array();
        		$parameter = $_POST['PAYMENTCHANNEL'];
        		$arrParameter = explode("_", $parameter);
				$data['paymentchannel'] = $arrParameter[0];
				$data['status'] = isset($arrParameter[1]) ? $arrParameter[1] : '';
				$this->language->load('checkout/doku_redirect');
				$this->load->model('checkout/order');
				
				if ($data['paymentchannel']=='15'){
					$data['redirectResult']='redirectResultCreditCard';
				}else if ($data['paymentchannel']=='04'){
					$data['redirectResult']='redirectResultDokuWallet';
				}else{
					$data['redirectResult']='redirectResultVirtualAccount';
				}

				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
						
				# PRODUCT ITEM
				$StringProduk = '' ;
				$total = 0;
				foreach ($this->cart->getProducts() as $product) 
				{
						$product_name = preg_replace("/([^a-zA-Z0-9.\-,=:;&% ]+)/", " ", $product['name']);
						
						$product_price = number_format($product['price'], 2, '.', '');
						$product_total = number_format($product['total'], 2, '.', '');
						
						$StringProduk .= $product_name.',';
						$StringProduk .= $product_price.',';
						$StringProduk .= $product['quantity'].',';
						$StringProduk .= $product_total.';';
						
						$total += $product['total'];	    		    
				}
				$data['data_product'] = $StringProduk;

				# COUPON
				if ( isset($this->session->data['coupon']) )
				{
						$coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);
						
						switch ( $coupon['type'] )
						{
								case "F":
										$coupon_amount = $coupon['discount'];
								break;
									
								case "P":
										$coupon_amount = ( $coupon['discount'] * $total ) / 100;
								break;
						}
						
						$coupon_amount = number_format($coupon_amount, 2, '.', '');
						
						$data['data_product'] .= "Coupon ".$coupon['name'].",-$coupon_amount,1,-$coupon_amount;";						
						$total = $total - $coupon_amount;
				}

				# TAX		
				$total_tax = $this->tax->getTax($total, $product['tax_class_id']);
				if ( $total_tax > 0 )
				{
						$total_tax = number_format($total_tax, 2, '.', '');
				}
				else
				{
						$taxes = $this->cart->getTaxes();
						
						$total_tax = 0;
						foreach ( $taxes as $taxpaid )
						{
								$total_tax += $taxpaid;
						}			
				}
				
				if ( $total_tax > 0 )
				{
						$tax = "Tax,$total_tax,1,$total_tax;";
						$data['data_product'] .= $tax;
						
						$total = $total + $total_tax;									
				}
				
				
				# SHIPPING
				$shipping_cost = $this->session->data['shipping_method']['cost'];
				if ( !empty($shipping_cost) )
				{
						$shipping_cost = number_format($shipping_cost, 2, '.', '');
						
						$ship = "Shipping,$shipping_cost,1,$shipping_cost;";
						$data['data_product'] .= $ship;
						
						$total = $total + $shipping_cost;
				}
				
				# VOUCHER
				if ( isset($this->session->data['voucher']) )
				{
						$voucher = $this->model_checkout_voucher->getVoucher($this->session->data['voucher']);		    
						$voucher_amount = $voucher['amount'];
						
						$voucher_amount = number_format($voucher_amount, 2, '.', '');
						
						if ( !empty($voucher_amount) )
						{
								$data['data_product'] .= "Voucher,-$voucher_amount,1,-$voucher_amount;";
						}
						
						$total = $total - $voucher_amount;
				}
				
				$data['oco_amount'] = number_format($total, 2, '.', '');
		    
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
				
        $serverconfig = $this->getServerConfig();
				$data['oco_action']             = $serverconfig['oco_action'];
				$data['oco_mallid']             = $serverconfig['oco_mallid'];
				$data['oco_sharedkey']          = $serverconfig['oco_sharedkey'];    
				$data['oco_chain']              = $serverconfig['oco_chain'];    
				$data['oco_check_status']       = $serverconfig['oco_check_status'];
				$data['destination']			= $serverconfig['destination'];   				
				$data['env']					= $serverconfig['env'];   				

        $data['button_confirm']         = $this->language->get('button_confirm');                
        $data['oco_session_id']         = session_id();
        $data['oco_payment_channel']    = $this->config->get('doku_onecheckout_payment_channel');
        $data['oco_currency']           = 360; # IDR currency only : 360
        $data['oco_transidmerchant']    = $this->session->data['order_id'];
        $data['oco_words']              = sha1(trim($data['oco_amount']).
                                                     trim($data['oco_mallid']).
                                                     trim($data['oco_sharedkey']).
                                                     trim($data['oco_transidmerchant']));
        $data['oco_allname']            = $trx_data['firstname'] .' '.$trx_data['lastname'] ;        
        $data['oco_address_1']          = $trx_data['address_1'].' ';
        $data['oco_address_2']          = $trx_data['address_2'];
        $data['oco_city']               = $trx_data['city'];
        $data['oco_postcode']           = $trx_data['postcode'];
        $data['oco_zone']               = $trx_data['zone'];
        $data['oco_request_datetime']   = date("YmdHis");
		$data['select_payment']         = $this->language->get('select_payment');
        $data['oco_ip_address']         = $this->getipaddress();
		$data['oco_country_id']         = $trx_data['country_id'];

		$data['payment_select']         = $this->config->get('doku_onecheckout_payment_select');
		$data['payment_select_merchant_hosted']         = $this->config->get('doku_onecheckout_merchant_hosted_status');
		$data['payment_list'] =  $this->config->get('doku_onecheckout_payment_list');
		$data['payment_name'] =  $this->config->get('doku_onecheckout_payment_name');
		$data['oco_mallid_merchant_hosted']       = $this->config->get('doku_onecheckout_mallid_merchant_hosted');				
		$data['oco_sharedkey_merchant_hosted']    = $this->config->get('doku_onecheckout_sharedkey_merchant_hosted');
		$data['oco_chain_merchant_hosted']        = $this->config->get('doku_onecheckout_chain_merchant_hosted');
		$data['oco_words_merchant_hosted']        = sha1(trim($data['oco_amount']).
                                     				trim($data['oco_mallid_merchant_hosted']).
                                     				trim($data['oco_sharedkey_merchant_hosted']).
                                     				trim($data['oco_transidmerchant']).
                                     				trim($data['oco_currency']));						
		$data['payment_list_merchant_hosted'] 		=  $this->config->get('doku_onecheckout_payment_merchant_hosted_list');
		$data['payment_name_merchant_hosted'] 		=  $this->config->get('doku_onecheckout_payment_merchant_hosted_name');
		$data['payment_list_va_merchant_hosted'] 	=  $this->config->get('doku_onecheckout_payment_merchant_hosted_list_va');

							    
        $trx['ip_address']                    = $data['oco_ip_address'];
		$trx['process_datetime']              = date("Y-m-d H:i:s");
		$trx['process_type']                  = 'REQUEST';
        $trx['transidmerchant']               = $data['oco_transidmerchant'];
        $trx['amount']                        = $data['oco_amount'];
        $trx['words']                         = $data['oco_words'];
		$trx['session_id']                    = $data['oco_session_id'];
		$trx['payment_channel']               = $data['oco_payment_channel'];
		$trx['message']             		  = "Transaction request start";

        $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
				
		        $this->add_dokuonecheckout($trx);

		       if ($data['status']=="MH"){
		       	if ($data['paymentchannel']=='15' || $data['paymentchannel']=='04' ){
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/doku_redirect_merchant_hosted.tpl')) 
				{
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/doku_redirect_merchant_hosted.tpl', $data));
				} 
				else 
				{
						$this->response->setOutput($this->load->view('default/template/payment/doku_redirect_merchant_hosted.tpl', $data));
				}
			}else{
				$bin = $data['payment_list_va_merchant_hosted'][$data['paymentchannel']];
				$url = $data['destination'].'/api/payment/doGeneratePaymentCode';
				$data['button_continue'] = $this->language->get('button_continue');
				$data['continue'] = $this->url->link('common/home');

		        $dataPayment = array(
  			  	'req_mall_id' => $data['oco_mallid_merchant_hosted'],
    			'req_chain_merchant' => $data['oco_chain_merchant_hosted'],
    			'req_amount' => $data['oco_amount'],
    			'req_words' => $data['oco_words_merchant_hosted'],
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
				// echo $responseJson;
				$data['payment_code'] = $bin.$responsePayment->res_pay_code;
				$data['channel_name'] = $arrParameter[2];
			
			$trx['status_code']          = $responsePayment->res_payment_code;
        	$trx['response_code']        = $responsePayment->res_payment_code;
			$trx['payment_channel']      = $data['paymentchannel'];
			$trx['doku_payment_datetime']= date("Y-m-d H:i:s");							
            $trx['result_msg']           = 'PENDING';          
        	$trx['session_id']       	 = $responsePayment->res_session_id;
			$trx['process_datetime']     = date("Y-m-d H:i:s");
			$trx['payment_code']		 = $bin.$responsePayment->res_pay_code;
			$trx['process_type']         = 'PENDING_MH_VA';
			
			$trx['message'] = "Redirect process Payment channel using ".$data['channel_name'].", transaction is pending for payment";  
			$data['return_message'] = "This is your Payment Code : ".$trx['payment_code']."<br>Please do the payment before expired.<br>If you need help for payment, please contact our customer service.<br>";	
			$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 1, $trx['message'],true);
  			$this->add_dokuonecheckout($trx);
  			$this->cart->clear();
					
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/doku_pending_merchant_hosted.tpl')) 
				{
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/doku_pending_merchant_hosted.tpl', $data));
				} 
				else 
				{
						$this->response->setOutput($this->load->view('default/template/payment/doku_pending_merchant_hosted.tpl', $data));
				}
				// {"res_pay_code":"69300000555","res_pairing_code":"150719101559995847","res_response_msg":"SUCCESS","res_payment_code":"5511","res_response_code":"0000","res_session_id":"4f97ead4f54fca7ffbec2f9b32454027"}
				// echo $responseJson;

			}
			} else 
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/doku_redirect.tpl')) 
				{
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/doku_redirect.tpl', $data));
				} 
				else 
				{
						$this->response->setOutput($this->load->view('default/template/payment/doku_redirect.tpl', $data));
				}

		
    }

    public function redirectResultCreditCard() 
    {
    	  if ( empty($this->request->post) )
        {
            echo "Stop : Access Not Valid";
						$this->log->write("DOKU Redirect Not in Correct Format - IP Logged ".$this->getipaddress());	    
            die;
        }

        	$myserverpath = explode ( "/", $_SERVER['PHP_SELF'] );
		if ( $myserverpath[1] <> 'admin' ) 
		{
			$serverpath = '/' . $myserverpath[1];    
		}
		else
		{
			$serverpath = '';
		}
	
		if((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443)
		{
			$myserverprotocol = "https";
		}
		else
		{
			$myserverprotocol = "http";    
		}
	
		$myservername = $_SERVER['SERVER_NAME'] . $serverpath;	
		$url_redirect = $myserverprotocol.'://'.$myservername.'/index.php?route=payment/doku_onecheckout/dokusuccess';

        $serverconfig = $this->getServerConfig();
        $this->load->model('checkout/order');
    	$data = array();
    	$data['destination']			= $serverconfig['destination']; 
        $url = $data['destination'].'/api/payment/paymentMip';
   		$serverconfig = $this->getServerConfig();
   		$data['oco_sharedkey_merchant_hosted']    = $this->config->get('doku_onecheckout_sharedkey_merchant_hosted');
   		$data['oco_words_merchant_hosted']        = sha1(trim($_POST['doku_amount']).
                                                     	trim($_POST['doku_mall_id']).
                                                     	trim($data['oco_sharedkey_merchant_hosted']).
                                                     	trim($_POST['doku_invoice_no']).
                                                     	trim($_POST['doku_currency']).
                                                     	trim($_POST['doku_token']).
                                                     	trim($_POST['doku_pairing_code']));	
        $data['session_id']						  = session_id();					
						
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

		$dataPayment = array(

        'req_mall_id' => $_POST['doku_mall_id'],
        'req_chain_merchant' => $_POST['doku_chain_merchant'],
        'req_amount' => $_POST['doku_amount'],
        'req_words' => $data['oco_words_merchant_hosted'],
        'req_purchase_amount' => $_POST['doku_amount'],
        'req_trans_id_merchant' => $_POST['doku_invoice_no'],
        'req_request_date_time' => date('YmdHis'),
        'req_currency' => $_POST['doku_currency'],
        'req_purchase_currency' => $_POST['doku_currency'],
        'req_session_id' => $data['session_id'],
        'req_name' => $trx_data['firstname'] .' '.$trx_data['lastname'],
        'req_payment_channel' => '15',
        'req_basket'=>'Transidmerchant '.$_POST['doku_invoice_no'].','.$_POST['doku_amount'].',1,'.$_POST['doku_amount'].';',
        'req_email' => $data['email'],
        'req_token_id' => $_POST['doku_token'],
        'req_mobile_phone' => $data['telephone'],
        'req_address' => $trx_data['address_1'].' '.$trx_data['address_2']
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

		if ($responsePayment){
			$trx = array();

			$trx['words']                = $responsePayment->res_words;
        	$trx['amount']               = $responsePayment->res_amount;
        	$trx['transidmerchant']      = $responsePayment->res_trans_id_merchant; 
        	$trx['status_code']          = $responsePayment->res_response_code;
        	$trx['response_code']        = $responsePayment->res_response_code;
			$trx['approval_code']        = $responsePayment->res_approval_code;
			$trx['payment_channel']      = '15';
			$trx['bank_issuer']          = $responsePayment->res_bank;
			$trx['creditcard']           = $responsePayment->res_mcn;                  
			$trx['doku_payment_datetime']= $responsePayment->res_payment_date_time;							
            $trx['result_msg']           = $responsePayment->res_response_msg;          
            $trx['verify_status']        = $responsePayment->res_verify_status;	    
        	$trx['session_id']       	 = $data['session_id'];
			$trx['ip_address']           = $this->getipaddress();
			$trx['process_datetime']     = date("Y-m-d H:i:s");
			$trx['verify_id']            = $responsePayment->res_verify_id;
			$trx['verify_score']         = $responsePayment->res_verify_score;
			$trx['process_type']         = 'REDIRECT_MH_CC';
}

			if($responsePayment->res_response_code == '0000'){

		//redirect process to doku
			$trx['message'] = "Transaction is Success with Merchant Hosted Doku Channel Credit Card";  
			$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 5, $trx['message'], true);									
			$this->add_dokuonecheckout($trx);		
							
		$responsePayment->res_redirect_url = $url_redirect;
		$responsePayment->res_show_doku_page = false; //true if you want to show doku page first before redirecting to redirect url

		echo json_encode($responsePayment);

	}else{

		$trx['message'] = "Redirect process message come from DOKU. Transaction is Failed";  
		$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 10, $trx['message'],true);
		$this->add_dokuonecheckout($trx);

		echo json_encode($responsePayment);

	}



	}

	    public function redirectResultDokuWallet() 
    {
    	  if ( empty($this->request->post) )
        {
            echo "Stop : Access Not Valid";
						$this->log->write("DOKU Redirect Not in Correct Format - IP Logged ".$this->getipaddress());	    
            die;
        }

        	$myserverpath = explode ( "/", $_SERVER['PHP_SELF'] );
			if ( $myserverpath[1] <> 'admin' ) 
	{
			$serverpath = '/' . $myserverpath[1];    
	}
			else
	{
			$serverpath = '';
	}
	
			if((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443)
	{
			$myserverprotocol = "https";
	}
			else
	{
			$myserverprotocol = "http";    
	}
	
			$myservername = $_SERVER['SERVER_NAME'] . $serverpath;	

		$url_redirect = $myserverprotocol.'://'.$myservername.'/index.php?route=payment/doku_onecheckout/dokusuccess';

        $serverconfig = $this->getServerConfig();
        $this->load->model('checkout/order');
    	$data = array();
    	$data['destination']			= $serverconfig['destination']; 
        $url = $data['destination'].'/api/payment/paymentMip';


   		$serverconfig = $this->getServerConfig();
   		$data['oco_sharedkey_merchant_hosted']    = $this->config->get('doku_onecheckout_sharedkey_merchant_hosted');
   		$data['oco_words_merchant_hosted']        = sha1(trim($_POST['doku_amount']).
                                                     	trim($_POST['doku_mall_id']).
                                                     	trim($data['oco_sharedkey_merchant_hosted']).
                                                     	trim($_POST['doku_invoice_no']).
                                                     	trim($_POST['doku_currency']).
                                                     	trim($_POST['doku_token']).
                                                     	trim($_POST['doku_pairing_code']));	
        $data['session_id']						  = session_id();					
						
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

		$dataPayment = array(

        'req_mall_id' => $_POST['doku_mall_id'],
        'req_chain_merchant' => $_POST['doku_chain_merchant'],
        'req_amount' => $_POST['doku_amount'],
        'req_words' => $data['oco_words_merchant_hosted'],
        'req_purchase_amount' => $_POST['doku_amount'],
        'req_trans_id_merchant' => $_POST['doku_invoice_no'],
        'req_request_date_time' => date('YmdHis'),
        'req_currency' => $_POST['doku_currency'],
        'req_purchase_currency' => $_POST['doku_currency'],
        'req_session_id' => $data['session_id'],
        'req_name' => $trx_data['firstname'] .' '.$trx_data['lastname'],
        'req_payment_channel' => '04',
        'req_basket'=>'Transidmerchant '.$_POST['doku_invoice_no'].','.$_POST['doku_amount'].',1,'.$_POST['doku_amount'].';',
        'req_email' => $data['email'],
        'req_token_id' => $_POST['doku_token'],
        'req_mobile_phone' => $data['telephone'],
        'req_address' => $trx_data['address_1'].' '.$trx_data['address_2']
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

		if ($responsePayment){

			$result_msg = $responsePayment->res_response_msg;
			if ($result_msg=="Berhasil")
			{
				$result_msg='SUCCESS';
			}else {
				$result_msg='FAILED';
			}

			$trx = array();

			$trx['words']                = $data['oco_words_merchant_hosted'];
        	$trx['amount']               = $_POST['doku_amount'];
        	$trx['transidmerchant']      = $responsePayment->res_trans_id_merchant; 
        	$trx['status_code']          = $responsePayment->res_response_code;
        	$trx['response_code']        = $responsePayment->res_response_code;
			$trx['approval_code']        = $responsePayment->res_approval_code;
			$trx['payment_channel']      = '04';
			$trx['bank_issuer']          = $responsePayment->res_bank;
			$trx['creditcard']           = $responsePayment->res_tracking_id;                  
			$trx['doku_payment_datetime']= date('YmdHis');						
            $trx['result_msg']           = $result_msg;          
            $trx['verify_status']        = '';
        	$trx['session_id']       	 = $data['session_id'];
			$trx['ip_address']           = $this->getipaddress();
			$trx['process_datetime']     = date("Y-m-d H:i:s");
			$trx['verify_id']            = '';
			$trx['verify_score']         = '';
			$trx['process_type']         = 'REDIRECT_MH_DW';
}

			if($responsePayment->res_response_code == '0000'){

		//redirect process to doku
			$trx['message'] = "Transaction is Success with Merchant Hosted Doku Channel DokuWallet";
			$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 5, $trx['message'], true);									
			$this->add_dokuonecheckout($trx);		
							
		$responsePayment->res_redirect_url = $url_redirect;
		$responsePayment->res_show_doku_page = false; //true if you want to show doku page first before redirecting to redirect url

		echo json_encode($responsePayment);

	}else{

		$trx['message'] = "Redirect process message come from DOKU. Transaction is Failed";  
		$this->model_checkout_order->addOrderHistory($trx['transidmerchant'], 10, $trx['message'],true);
		$this->add_dokuonecheckout($trx);

		echo json_encode($responsePayment);

	}



	}	      
}

?>
