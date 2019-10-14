<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of doku_onecheckout
 *
 * @lordsanjay
 */

// Heading
$_['heading_title']                = 'DOKU Onecheckout';
                                   
// Text                            
$_['text_payment']                 = 'Payment';
$_['text_success']                 = 'Success: You have modified DOKU Onecheckout account details!';
$_['text_doku_onecheckout']        = '<a onclick="window.open(\'https://www.doku.com\');"><img src="view/image/payment/doku_onecheckout.png" alt="DOKU Onecheckout" title="DOKU Onecheckout" style="border: 1px solid #EEEEEE;" /><br /></a>';
                                   
// Parameter                       
$_['server_params']                = 'Payment Server Parameter';
$_['opencart_params']              = 'Opencart Server Parameter';
$_['paymentchannel_params']        = 'Payment Channel Parameter';
                                   
// Entry                           
$_['entry_server_set']             		= 'Server Set :';
$_['entry_mallid_prod']            		= 'Mall ID :';
$_['entry_sharedkey_prod']         		= 'Shared Key :';
$_['entry_chain_prod']            		= 'Chain Number :';
$_['entry_mallid_dev']             		= 'Mall ID :';
$_['entry_sharedkey_dev']          		= 'Shared Key :';
$_['entry_chain_dev']              		= 'Chain Number :';
$_['entry_mallid_merchant_hosted'] 		= 'Mall ID :';
$_['entry_sharedkey_merchant_hosted']   = 'Shared Key :';
$_['entry_chain_merchant_hosted']       = 'Chain Number :';
$_['entry_review_edu']             		= 'Use EDU Services ?';
$_['entry_identify']               		= 'Use Identify ?';
$_['entry_order_status']           		= 'Order Status:';
$_['entry_expired_time']           		= 'Expired Time Status:';
$_['entry_check_key']              		= 'Check Status Security Key:';
$_['entry_geo_zone']               		= 'Geo Zone:';
$_['entry_status']                 		= 'Status:';
$_['entry_sort_order']             		= 'Sort Order:';
$_['entry_payment_channel']        		= 'Payment Channel:';
$_['list_payment_channel']         = array( '' => 'Display All', '01' => 'Credit Card', '02' => 'Mandiri ClickPay', '04' => 'DokuWallet', '05' => 'ATM' );
$_['entry_doku_name']              = 'DOKU Payment Method name:';
$_['entry_minimal_amount']         = 'Minimal amount to process:';
                                   
$_['paymentchannel_selection']     = 'Payment Channel to show:';
$_['paymentchannel_cc']            = 'Credit Card channel:';
$_['paymentchannel_clickpay']      = 'Mandiri Clickpay channel:';
$_['paymentchannel_dokuwalet']     = 'Dokuwallet channel:';
$_['paymentchannel_permatavalite'] = 'Permata VA channel:';
$_['paymentchannel_epaybri']       = 'ePay BRI channel:';
$_['paymentchannel_dokualfa']      = 'DOKU Alfa channel:';

// Error
$_['error_permission']             = 'Warning: You do not have permission to modify payment DOKU Onecheckout!';
$_['error_server_set']             = 'Server target has to be set!';
$_['error_mallid_prod']            = 'Mall ID Production Server Required!';
$_['error_sharedkey_prod']         = 'Shared Key Production Required!';
$_['error_chain_prod']             = 'Chain Number Production Server Required!';
$_['error_mallid_merchant_hosted']            = 'Mall ID Merchant Hosted Server Required!';
$_['error_sharedkey_merchant_hosted']         = 'Shared Key Merchant Hosted Required!';
$_['error_chain_merchant_hosted']             = 'Chain Number Merchant Hosted Server Required!';
$_['error_mallid_dev']             = 'Mall ID Development Required!';
$_['error_sharedkey_dev']          = 'Shared Key Development Required!';
$_['error_chain_dev']              = 'Chain Number Production Server Required!';
$_['error_expired_time']           = 'Expired Time Status Required!';
$_['error_check_key']              = 'Check Key Required!';
$_['error_payment_method']         = 'Payment Method Name Required!';
$_['error_minimal_amount']         = 'Minimal Amount Required!';
$_['error_payment_name']           = 'Payment Channel Name Required!';
$_['error_doku_name']              = 'DOKU Payment Method Name Required!';

// Merchant hosted configuration
$_['merchanthosted_title']              = 'Merchant Hosted Configuration';

// URL                             
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

$myservername                = $_SERVER['SERVER_NAME'] . $serverpath;
$_['url_title']              = 'URL to be set on DOKU System';
$_['url_identify']           = $myserverprotocol.'://'.$myservername.'/index.php?route=payment/doku_onecheckout/dokuidentify';
$_['url_notify']             = $myserverprotocol.'://'.$myservername.'/index.php?route=payment/doku_onecheckout/dokunotify';
$_['url_redirect']           = $myserverprotocol.'://'.$myservername.'/index.php?route=payment/doku_onecheckout/dokuredirect';
$_['url_review']             = $myserverprotocol.'://'.$myservername.'/index.php?route=payment/doku_onecheckout/dokureview';

?>
