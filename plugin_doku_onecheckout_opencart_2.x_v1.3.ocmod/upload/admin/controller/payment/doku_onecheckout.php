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
    
    private $error = array();
                             
    public function index() 
    {
        
        # Set Initial Parameters
				$this->load->language('payment/doku_onecheckout');
				$this->document->setTitle($this->language->get('heading_title'));		
				$this->load->model('setting/setting');
			
				if ( ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() ) 
        {						
						$this->model_setting_setting->editSetting('doku_onecheckout', $this->request->post);
						$this->session->data['success'] = $this->language->get('text_success');
						$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));						
				}
        
				# Get Form Data
				$data['heading_title']                = $this->language->get('heading_title');        
        $data['server_params']                = $this->language->get('server_params');
        $data['opencart_params']              = $this->language->get('opencart_params');
				$data['paymentchannel_params']        = $this->language->get('paymentchannel_params');        
        $data['entry_server_set']             = $this->language->get('entry_server_set');
        $data['entry_mallid_prod']            = $this->language->get('entry_mallid_prod');
        $data['entry_sharedkey_prod']         = $this->language->get('entry_sharedkey_prod');
        $data['entry_chain_prod']             = $this->language->get('entry_chain_prod');
        $data['entry_mallid_merchant_hosted']            = $this->language->get('entry_mallid_merchant_hosted');
        $data['entry_sharedkey_merchant_hosted']         = $this->language->get('entry_sharedkey_merchant_hosted');
        $data['entry_chain_merchant_hosted']             = $this->language->get('entry_chain_merchant_hosted');
        $data['entry_mallid_dev']             = $this->language->get('entry_mallid_dev');
        $data['entry_sharedkey_dev']          = $this->language->get('entry_sharedkey_dev');
        $data['entry_chain_dev']              = $this->language->get('entry_chain_dev');
        $data['entry_review_edu']             = $this->language->get('entry_review_edu');
        $data['entry_identify']               = $this->language->get('entry_identify');
        $data['entry_payment_channel']        = $this->language->get('entry_payment_channel');
        $data['list_payment_channel']         = $this->language->get('list_payment_channel');
        $data['entry_doku_name']              = $this->language->get('entry_doku_name');
        $data['entry_status']                 = $this->language->get('entry_status');
        $data['entry_order_status']           = $this->language->get('entry_order_status');        				
        $data['entry_sort_order']             = $this->language->get('entry_sort_order');
				$data['entry_geo_zone']               = $this->language->get('entry_geo_zone');				
				$data['text_all_zones']               = $this->language->get('text_all_zones');        
				
				/*        
				$data['entry_minimal_amount']         = $this->language->get('entry_minimal_amount');
				$data['entry_expired_time']           = $this->language->get('entry_expired_time');
				$data['entry_check_key']              = $this->language->get('entry_check_key');
        */
        $data['merchanthosted_title']                    = $this->language->get('merchanthosted_title');				
				$data['text_enabled']                 = $this->language->get('text_enabled');
        $data['text_disabled']                = $this->language->get('text_disabled');
        $data['url_title']                    = $this->language->get('url_title');
        $data['url_identify']                 = $this->language->get('url_identify');
        $data['url_notify']                   = $this->language->get('url_notify');
        $data['url_redirect']                 = $this->language->get('url_redirect');
        $data['url_review']                   = $this->language->get('url_review');
				$data['button_save']                  = $this->language->get('button_save');
				$data['button_cancel']                = $this->language->get('button_cancel');
		
        $data['paymentchannel_selection']     = $this->language->get('paymentchannel_selection');
        $data['paymentchannel_cc']            = $this->language->get('paymentchannel_cc');
				$data['paymentchannel_clickpay']      = $this->language->get('paymentchannel_clickpay');
				$data['paymentchannel_dokuwalet']     = $this->language->get('paymentchannel_dokuwalet');
        $data['paymentchannel_permatavalite'] = $this->language->get('paymentchannel_permatavalite');
				$data['paymentchannel_epaybri']       = $this->language->get('paymentchannel_epaybri');
				$data['paymentchannel_dokualfa']      = $this->language->get('paymentchannel_dokualfa');
        
        
				# Set Error Message
        if ( isset($this->error['warning']) ) 
        {
            $data['error_warning'] = $this->error['warning'];
        } 
        else 
        {
            $data['error_warning'] = '';
        }

        if ( isset($this->error['server_set']) ) 
        {
            $data['error_server_set'] = $this->error['error_server_set'];
        } 
        else 
        {
            $data['error_server_set'] = '';
        }
        
        if ( isset($this->error['mallid_prod']) ) 
        {
            $data['error_mallid_prod'] = $this->error['mallid_prod'];
        } 
        else 
        {
            $data['error_mallid_prod'] = '';
        }

        if ( isset($this->error['sharedkey_prod']) ) 
        {
            $data['error_sharedkey_prod'] = $this->error['sharedkey_prod'];
        } 
        else 
        {
            $data['error_sharedkey_prod'] = '';
        }

        if ( isset($this->error['chain_prod']) ) 
        {
            $data['error_chain_prod'] = $this->error['chain_prod'];
        } 
        else 
        {
            $data['error_chain_prod'] = '';
        }
        
        if ( isset($this->error['mallid_dev']) ) 
        {
            $data['error_mallid_dev'] = $this->error['mallid_dev'];
        } 
        else 
        {
            $data['error_mallid_dev'] = '';
        }

        if ( isset($this->error['sharedkey_dev']) ) 
        {
            $data['error_sharedkey_dev'] = $this->error['sharedkey_dev'];
        } 
        else 
        {
            $data['error_sharedkey_dev'] = '';
        }

        if ( isset($this->error['chain_dev']) ) 
        {
            $data['error_chain_dev'] = $this->error['chain_dev'];
        } 
        else 
        {
            $data['error_chain_dev'] = '';
        }
         
          if ( isset($this->error['mallid_merchant_hosted']) ) 
        {
            $data['error_mallid_merchant_hosted'] = $this->error['mallid_merchant_hosted'];
        } 
        else 
        {
            $data['error_mallid_merchant_hosted'] = '';
        }

        if ( isset($this->error['sharedkey_merchant_hosted']) ) 
        {
            $data['error_sharedkey_merchant_hosted'] = $this->error['sharedkey_merchant_hosted'];
        } 
        else 
        {
            $data['error_sharedkey_merchant_hosted'] = '';
        }

        if ( isset($this->error['chain_merchant_hosted']) ) 
        {
            $data['error_chain_merchant_hosted'] = $this->error['chain_merchant_hosted'];
        } 
        else 
        {
            $data['error_chain_merchant_hosted'] = '';
        } 
				/*
        if ( isset($this->error['expired_time']) ) 
        {
            $data['error_expired_time'] = $this->error['expired_time'];
        } 
        else 
        {
            $data['error_expired_time'] = '';
        }

        if ( isset($this->error['check_key']) ) 
        {
            $data['error_check_key'] = $this->error['check_key'];
        } 
        else 
        {
            $data['error_check_key'] = '';
        }

        if ( isset($this->error['minimal_amount']) ) 
        {
            $data['error_minimal_amount'] = $this->error['minimal_amount'];
        } 
        else 
        {
            $data['error_minimal_amount'] = '';
        }
				*/
								
        if ( isset($this->error['payment_method']) ) 
        {
            $data['error_payment_method'] = $this->error['payment_method'];
        } 
        else 
        {
            $data['error_payment_method'] = '';
        }

        if ( isset($this->error['error_doku_name']) ) 
        {
            $data['error_doku_name'] = $this->error['error_doku_name'];
        } 
        else 
        {
            $data['error_doku_name'] = '';
        }
	
        if ( isset($this->error['error_payment_name']) ) 
        {
            $data['error_payment_name'] = $this->error['error_payment_name'];
        } 
        else 
        {
            $data['error_payment_name'] = '';
        }
     	
        
				# Get POST or Config Data      
        if ( isset($this->request->post['doku_onecheckout_server_set']) ) 
        {
            $data['doku_onecheckout_server_set'] = $this->request->post['doku_onecheckout_server_set'];
        } 
        else 
        {
            $data['doku_onecheckout_server_set'] = $this->config->get('doku_onecheckout_server_set');
        }

        if ( isset($this->request->post['doku_onecheckout_mallid_prod']) ) 
        {
            $data['doku_onecheckout_mallid_prod'] = $this->request->post['doku_onecheckout_mallid_prod'];
        } 
        else 
        {
            $data['doku_onecheckout_mallid_prod'] = $this->config->get('doku_onecheckout_mallid_prod');
        }
        
        if ( isset($this->request->post['doku_onecheckout_sharedkey_prod']) ) 
        {
            $data['doku_onecheckout_sharedkey_prod'] = $this->request->post['doku_onecheckout_sharedkey_prod'];
        } 
        else 
        {
            $data['doku_onecheckout_sharedkey_prod'] = $this->config->get('doku_onecheckout_sharedkey_prod');
        }        

        if ( isset($this->request->post['doku_onecheckout_chain_prod']) ) 
        {
            $data['doku_onecheckout_chain_prod'] = $this->request->post['doku_onecheckout_chain_prod'];
        } 
        else 
        {
            $data['doku_onecheckout_chain_prod'] = $this->config->get('doku_onecheckout_chain_prod');
        }
        
        if ( isset($this->request->post['doku_onecheckout_mallid_dev']) ) 
        {
            $data['doku_onecheckout_mallid_dev'] = $this->request->post['doku_onecheckout_mallid_dev'];
        } 
        else 
        {
            $data['doku_onecheckout_mallid_dev'] = $this->config->get('doku_onecheckout_mallid_dev');
        }

        if ( isset($this->request->post['doku_merchant_hosted_sinarmas']) ) 
        {
            $data['doku_merchant_hosted_sinarmas'] = $this->request->post['doku_merchant_hosted_sinarmas'];
        } 
        else 
        {
            $data['doku_merchant_hosted_sinarmas'] = $this->config->get('doku_merchant_hosted_sinarmas');
        }

        if ( isset($this->request->post['doku_merchant_hosted_bca']) ) 
        {
            $data['doku_merchant_hosted_bca'] = $this->request->post['doku_merchant_hosted_bca'];
        } 
        else 
        {
            $data['doku_merchant_hosted_bca'] = $this->config->get('doku_merchant_hosted_bca');
        }

        if ( isset($this->request->post['doku_merchant_hosted_indomaret']) ) 
        {
            $data['doku_merchant_hosted_indomaret'] = $this->request->post['doku_merchant_hosted_indomaret'];
        } 
        else 
        {
            $data['doku_merchant_hosted_indomaret'] = $this->config->get('doku_merchant_hosted_indomaret');
        }
        
        if ( isset($this->request->post['doku_merchant_hosted_cimb']) ) 
        {
            $data['doku_merchant_hosted_cimb'] = $this->request->post['doku_merchant_hosted_cimb'];
        } 
        else 
        {
            $data['doku_merchant_hosted_cimb'] = $this->config->get('doku_merchant_hosted_cimb');
        }
        
        if ( isset($this->request->post['doku_merchant_hosted_danamon']) ) 
        {
            $data['doku_merchant_hosted_danamon'] = $this->request->post['doku_merchant_hosted_danamon'];
        } 
        else 
        {
            $data['doku_merchant_hosted_danamon'] = $this->config->get('doku_merchant_hosted_danamon');
        }

        if ( isset($this->request->post['doku_merchant_hosted_bri']) ) 
        {
            $data['doku_merchant_hosted_bri'] = $this->request->post['doku_merchant_hosted_bri'];
        } 
        else 
        {
            $data['doku_merchant_hosted_bri'] = $this->config->get('doku_merchant_hosted_bri');
        }

        if ( isset($this->request->post['doku_merchant_hosted_alfa']) ) 
        {
            $data['doku_merchant_hosted_alfa'] = $this->request->post['doku_merchant_hosted_alfa'];
        } 
        else 
        {
            $data['doku_merchant_hosted_alfa'] = $this->config->get('doku_merchant_hosted_alfa');
        }

        if ( isset($this->request->post['doku_merchant_hosted_permata']) ) 
        {
            $data['doku_merchant_hosted_permata'] = $this->request->post['doku_merchant_hosted_permata'];
        } 
        else 
        {
            $data['doku_merchant_hosted_permata'] = $this->config->get('doku_merchant_hosted_permata');
        }

        if ( isset($this->request->post['doku_merchant_hosted_bni']) ) 
        {
            $data['doku_merchant_hosted_bni'] = $this->request->post['doku_merchant_hosted_bni'];
        } 
        else 
        {
            $data['doku_merchant_hosted_bni'] = $this->config->get('doku_merchant_hosted_bni');
        }

        if ( isset($this->request->post['doku_merchant_hosted_mandiri']) ) 
        {
            $data['doku_merchant_hosted_mandiri'] = $this->request->post['doku_merchant_hosted_mandiri'];
        } 
        else 
        {
            $data['doku_merchant_hosted_mandiri'] = $this->config->get('doku_merchant_hosted_mandiri');
        }

        if ( isset($this->request->post['doku_merchant_hosted_qnb']) ) 
        {
            $data['doku_merchant_hosted_qnb'] = $this->request->post['doku_merchant_hosted_qnb'];
        } 
        else 
        {
            $data['doku_merchant_hosted_qnb'] = $this->config->get('doku_merchant_hosted_qnb');
        }

        if ( isset($this->request->post['doku_merchant_hosted_btn']) ) 
        {
            $data['doku_merchant_hosted_btn'] = $this->request->post['doku_merchant_hosted_btn'];
        } 
        else 
        {
            $data['doku_merchant_hosted_btn'] = $this->config->get('doku_merchant_hosted_btn');
        }

        if ( isset($this->request->post['doku_merchant_hosted_maybank']) ) 
        {
            $data['doku_merchant_hosted_maybank'] = $this->request->post['doku_merchant_hosted_maybank'];
        } 
        else 
        {
            $data['doku_merchant_hosted_maybank'] = $this->config->get('doku_merchant_hosted_maybank');
        }
        
        if ( isset($this->request->post['doku_onecheckout_sharedkey_dev']) ) 
        {
            $data['doku_onecheckout_sharedkey_dev'] = $this->request->post['doku_onecheckout_sharedkey_dev'];
        } 
        else 
        {
            $data['doku_onecheckout_sharedkey_dev'] = $this->config->get('doku_onecheckout_sharedkey_dev');
        }        

        if ( isset($this->request->post['doku_onecheckout_chain_dev']) ) 
        {
            $data['doku_onecheckout_chain_dev'] = $this->request->post['doku_onecheckout_chain_dev'];
        } 
        else 
        {
            $data['doku_onecheckout_chain_dev'] = $this->config->get('doku_onecheckout_chain_dev');
        }
        
        if ( isset($this->request->post['doku_onecheckout_mallid_merchant_hosted']) ) 
        {
            $data['doku_onecheckout_mallid_merchant_hosted'] = $this->request->post['doku_onecheckout_mallid_merchant_hosted'];
        } 
        else 
        {
            $data['doku_onecheckout_mallid_merchant_hosted'] = $this->config->get('doku_onecheckout_mallid_merchant_hosted');
        }
        
        if ( isset($this->request->post['doku_onecheckout_sharedkey_merchant_hosted']) ) 
        {
            $data['doku_onecheckout_sharedkey_merchant_hosted'] = $this->request->post['doku_onecheckout_sharedkey_merchant_hosted'];
        } 
        else 
        {
            $data['doku_onecheckout_sharedkey_merchant_hosted'] = $this->config->get('doku_onecheckout_sharedkey_merchant_hosted');
        }        

        if ( isset($this->request->post['doku_onecheckout_chain_merchant_hosted']) ) 
        {
            $data['doku_onecheckout_chain_merchant_hosted'] = $this->request->post['doku_onecheckout_chain_merchant_hosted'];
        } 
        else 
        {
            $data['doku_onecheckout_chain_merchant_hosted'] = $this->config->get('doku_onecheckout_chain_merchant_hosted');
        }

        if ( isset($this->request->post['doku_onecheckout_merchant_hosted_status']) ) 
        {
            $data['doku_onecheckout_merchant_hosted_status'] = $this->request->post['doku_onecheckout_merchant_hosted_status'];
        } 
        else 
        {
            $data['doku_onecheckout_merchant_hosted_status'] = $this->config->get('doku_onecheckout_merchant_hosted_status');
        }        

        if ( isset($this->request->post['doku_onecheckout_review_edu']) ) 
        {
            $data['doku_onecheckout_review_edu'] = $this->request->post['doku_onecheckout_review_edu'];
        } 
        else 
        {
            $data['doku_onecheckout_review_edu'] = $this->config->get('doku_onecheckout_review_edu');
        }        

        if ( isset($this->request->post['doku_onecheckout_identify']) ) 
        {
            $data['doku_onecheckout_identify'] = $this->request->post['doku_onecheckout_identify'];
        } 
        else 
        {
            $data['doku_onecheckout_identify'] = $this->config->get('doku_onecheckout_identify');
        }        
        
        if ( isset($this->request->post['doku_onecheckout_payment_channel']) ) 
        {
            $data['doku_onecheckout_payment_channel'] = $this->request->post['doku_onecheckout_payment_channel'];
        } 
        else 
        {
            $data['doku_onecheckout_payment_channel'] = $this->config->get('doku_onecheckout_payment_channel');
        }        

        if ( isset($this->request->post['doku_onecheckout_payment_method']) ) 
        {
            $data['doku_onecheckout_payment_method'] = $this->request->post['doku_onecheckout_payment_method'];
        } 
        else 
        {
            $data['doku_onecheckout_payment_method'] = $this->config->get('doku_onecheckout_payment_method');
        }        
        							
        if ( isset($this->request->post['doku_onecheckout_order_status_id']) ) 
        {
            $data['doku_onecheckout_order_status_id'] = $this->request->post['doku_onecheckout_order_status_id'];
        } 
        else 
        {
            $data['doku_onecheckout_order_status_id'] = $this->config->get('doku_onecheckout_order_status_id'); 
        }
		
				/*
        if ( isset($this->request->post['doku_onecheckout_expired_time']) ) 
        {
            $data['doku_onecheckout_expired_time'] = $this->request->post['doku_onecheckout_expired_time'];
        } 
        else 
        {
            $data['doku_onecheckout_expired_time'] = $this->config->get('doku_onecheckout_expired_time'); 
        }

        if ( isset($this->request->post['doku_onecheckout_check_key']) ) 
        {
            $data['doku_onecheckout_check_key'] = $this->request->post['doku_onecheckout_check_key'];
        } 
        else 
        {
            $data['doku_onecheckout_check_key'] = $this->config->get('doku_onecheckout_check_key'); 
        }

        if ( isset($this->request->post['doku_onecheckout_minimal_amount']) ) 
        {
            $data['doku_onecheckout_minimal_amount'] = $this->request->post['doku_onecheckout_minimal_amount'];
        } 
        else 
        {
            $data['doku_onecheckout_minimal_amount'] = $this->config->get('doku_onecheckout_minimal_amount'); 
        }
				*/
				
				if ( isset($this->request->post['doku_onecheckout_geo_zone_id']) ) 
        {
						$data['doku_onecheckout_geo_zone_id'] = $this->request->post['doku_onecheckout_geo_zone_id'];
				} 
        else 
        {
						$data['doku_onecheckout_geo_zone_id'] = $this->config->get('doku_onecheckout_geo_zone_id'); 
				} 

				$this->load->model('localisation/geo_zone');										
				$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();								
	
				# Payment Channel	
        if ( isset($this->request->post['doku_onecheckout_doku_name']) ) 
        {
            $data['doku_onecheckout_doku_name'] = $this->request->post['doku_onecheckout_doku_name'];
        } 
        else 
        {
            $data['doku_onecheckout_doku_name'] = $this->config->get('doku_onecheckout_doku_name'); 
        }
	
        if ( isset($this->request->post['doku_onecheckout_payment_select']) ) 
        {
            $data['doku_onecheckout_payment_select'] = $this->request->post['doku_onecheckout_payment_select'];
        } 
        else 
        {
            $data['doku_onecheckout_payment_select'] = $this->config->get('doku_onecheckout_payment_select'); 
        }

        if ( isset($this->request->post['doku_onecheckout_payment_list']) ) 
        {
            $data['doku_onecheckout_payment_list'] = $this->request->post['doku_onecheckout_payment_list'];
        } 
        else 
        {
            $data['doku_onecheckout_payment_list'] = $this->config->get('doku_onecheckout_payment_list'); 
        }
	
        if ( isset($this->request->post['doku_onecheckout_payment_name']) ) 
        {
            $data['doku_onecheckout_payment_name'] = $this->request->post['doku_onecheckout_payment_name'];
        } 
        else 
        {
            $data['doku_onecheckout_payment_name'] = $this->config->get('doku_onecheckout_payment_name'); 
        }

        if ( isset($this->request->post['doku_onecheckout_payment_merchant_hosted_list']) ) 
        {
            $data['doku_onecheckout_payment_merchant_hosted_list'] = $this->request->post['doku_onecheckout_payment_merchant_hosted_list'];
        } 
        else 
        {
            $data['doku_onecheckout_payment_merchant_hosted_list'] = $this->config->get('doku_onecheckout_payment_merchant_hosted_list'); 
        }

        if ( isset($this->request->post['doku_onecheckout_payment_merchant_hosted_list_va']) ) 
        {
            $data['doku_onecheckout_payment_merchant_hosted_list_va'] = $this->request->post['doku_onecheckout_payment_merchant_hosted_list_va'];
        } 
        else 
        {
            $data['doku_onecheckout_payment_merchant_hosted_list_va'] = $this->config->get('doku_onecheckout_payment_merchant_hosted_list_va'); 
        }

        if ( isset($this->request->post['doku_onecheckout_payment_merchant_hosted_name']) ) 
        {
            $data['doku_onecheckout_payment_merchant_hosted_name'] = $this->request->post['doku_onecheckout_payment_merchant_hosted_name'];
        } 
        else 
        {
            $data['doku_onecheckout_payment_merchant_hosted_name'] = $this->config->get('doku_onecheckout_payment_merchant_hosted_name'); 
        }
		
        $this->load->model('localisation/order_status');		
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
				
				if ( isset($this->request->post['doku_onecheckout_status']) ) 
        {
						$data['doku_onecheckout_status'] = $this->request->post['doku_onecheckout_status'];
				} 
				else 
        {
						$data['doku_onecheckout_status'] = $this->config->get('doku_onecheckout_status');
				}
        
				if ( isset($this->request->post['doku_onecheckout_sort_order']) ) 
        {
						$data['doku_onecheckout_sort_order'] = $this->request->post['doku_onecheckout_sort_order'];
				} 
        else 
        {
						$data['doku_onecheckout_sort_order'] = $this->config->get('doku_onecheckout_sort_order');
				}

        # Set Breadcrumbs
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
           'text'      => $this->language->get('text_home'),
           'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
           'separator' => false
        );

        $data['breadcrumbs'][] = array(
           'text'      => $this->language->get('text_payment'),
           'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
           'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
           'text'      => $this->language->get('heading_title'),
           'href'      => $this->url->link('payment/doku_onecheckout', 'token=' . $this->session->data['token'], 'SSL'),
           'separator' => ' :: '
        );
                
        $data['action'] = $this->url->link('payment/doku_onecheckout', 'token=' . $this->session->data['token'], 'SSL');        
        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		              
				$this->template = 'payment/doku_onecheckout.tpl';
				$this->children = array(
					'common/header',
					'common/footer'
				);
						
				$data['header'] = $this->load->controller('common/header');
				$data['column_left'] = $this->load->controller('common/column_left');				
				$data['footer'] = $this->load->controller('common/footer');

				$this->response->setOutput($this->load->view('payment/doku_onecheckout.tpl', $data));
				
		}

    private function validate()
    {
        if ( !$this->user->hasPermission('modify', 'payment/doku_onecheckout') ) 
        {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ( !$this->request->post['doku_onecheckout_mallid_prod'] ) 
        {
            $this->error['mallid_prod'] = $this->language->get('error_mallid_prod');
        }
        
        if ( !$this->request->post['doku_onecheckout_sharedkey_prod'] ) 
        {
            $this->error['sharedkey_prod'] = $this->language->get('error_sharedkey_prod');
        }        

        if ( !$this->request->post['doku_onecheckout_chain_prod'] ) 
        {
            $this->error['chain_prod'] = $this->language->get('error_chain_prod');
        }
        
        if ( !$this->request->post['doku_onecheckout_mallid_merchant_hosted'] ) 
        {
            $this->error['mallid_merchant_hosted'] = $this->language->get('error_mallid_merchant_hosted');
        }
        
        if ( !$this->request->post['doku_onecheckout_sharedkey_merchant_hosted'] ) 
        {
            $this->error['sharedkey_merchant_hosted'] = $this->language->get('error_sharedkey_merchant_hosted');
        }        

        if ( !$this->request->post['doku_onecheckout_chain_merchant_hosted'] ) 
        {
            $this->error['chain_merchant_hosted'] = $this->language->get('error_chain_merchant_hosted');
        }

        if ( !$this->request->post['doku_onecheckout_mallid_dev'] ) 
        {
            $this->error['mallid_dev'] = $this->language->get('error_mallid_dev');
        }

        if ( !$this->request->post['doku_onecheckout_sharedkey_dev'] ) 
        {
            $this->error['sharedkey_dev'] = $this->language->get('error_sharedkey_dev');
        }

        if ( !$this->request->post['doku_onecheckout_chain_dev'] ) 
        {
            $this->error['chain_dev'] = $this->language->get('error_chain_dev');
        }

        // if ( !$this->request->post['doku_merchant_hosted_sinarmas'] ) 
        // {
        //     $this->error['mallid_dev'] = $this->language->get('error_mallid_dev');
        // }


				/*
        if ( !$this->request->post['doku_onecheckout_expired_time'] ) 
        {
            $this->error['expired_time'] = $this->language->get('error_expired_time');
        } 

        if ( !$this->request->post['doku_onecheckout_minimal_amount'] ) 
        {
            $this->error['minimal_amount'] = $this->language->get('error_minimal_amount');
        }
	
        if ( !$this->request->post['doku_onecheckout_check_key'] ) 
        {
            $this->error['check_key'] = $this->language->get('error_check_key');
        } 
				*/

        if ( !$this->request->post['doku_onecheckout_doku_name'] ) 
        {
            $this->error['doku_name'] = $this->language->get('error_doku_name');
        }
	
				if ( !$this->error ) 
        {
            return true;
        } 
        else 
        {
            return false;
        }	
    }
		
}

?>
