<?php
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
  
  $myservername = $_SERVER['SERVER_NAME'] . $serverpath;  
  $redirect=$myserverprotocol.'://'.$myservername.'/index.php?route=payment/doku_onecheckout/'.$redirectResult;
?>
<?php echo $header; ?>
<!-- <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>
<script src="<?php echo $destination; ?>/doku-js/assets/js/doku-1.4.js?version=<?php echo time()?>"></script> 
<link href="<?php echo $destination; ?>/doku-js/assets/css/doku.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" rel="stylesheet">


<div class="container">
    <form action="" method="POST" id="payment-form">   
    <div doku-div='form-payment'>     
      <input id="doku-token" name="doku-token" type="hidden" />     
      <input id=" doku-pairing-code" name="doku-pairing-code" type="hidden" />    
    </div>       
    </form>

  <script type="text/javascript">
  $(function() {

    var data = new Object();

    data.req_merchant_code = '<?php echo $oco_mallid_merchant_hosted;?>'; //mall id or merchant id
    data.req_chain_merchant = '<?php echo $oco_chain_merchant_hosted;?>'; //chain merchant id
    data.req_payment_channel = '<?php echo $paymentchannel?>'; //payment channel
    data.req_server_url = '<? echo $redirect ?>'; //merchant payment url to receive pairing code & token
    data.req_transaction_id = '<?php echo $oco_transidmerchant;?>'; //invoice no
    data.req_amount = '<?php echo $oco_amount; ?>';
    data.req_currency = '<?php echo $oco_currency; ?>'; //360 for IDR
    data.req_purchase_currency = '<?php echo $oco_currency; ?>'; //360 for IDR
    data.req_words = '<?php echo $oco_words_merchant_hosted;?>'; 
    data.req_session_id = '<?php echo $oco_session_id;?>'; //your server timestamp
    data.req_form_type = 'full';

    <?php echo $env; ?>;

  });
</script>
</div>
