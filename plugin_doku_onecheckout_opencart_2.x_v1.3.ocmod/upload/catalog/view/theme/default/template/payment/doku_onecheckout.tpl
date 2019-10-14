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
?>

<script type="text/javascript">

$(document).ready(function() {

$( "#doku_submit" ).click(function() {
    $.post( "<?php echo $myserverprotocol ?>://<?php echo $myservername ?>/index.php?route=payment/doku_onecheckout/processdoku", { TRANSIDMERCHANT: "<?php echo $oco_transidmerchant; ?>" }, function( data ) {
        document.doku_form.submit();
    });
});

});

</script>

<form id="doku_form" name="doku_form" action="<?php echo $myserverprotocol ?>://<?php echo $myservername ?>/index.php?route=payment/doku_onecheckout/redirect" method="post">

  <div class="buttons">
	<?php

	if ($payment_select==0)
	{
	?>
	    <input type=hidden name="PAYMENTCHANNEL" value="">
	<?php
		    if($payment_select_merchant_hosted){

		    echo $select_payment;
            $m=1;
	    foreach ($payment_list_merchant_hosted as $key_m=>$data_m)
	    {
					if ( $m==1 )
					{
						$flag = "checked";
					}
					else
					{
						$flag = "";
					}
					if ($data_m){
	?>
          <ul><input type="radio" name="PAYMENTCHANNEL" value="<?php echo $data_m ?>_MH_<?php echo $payment_name_merchant_hosted[$key_m]?>"> <?php echo $payment_name_merchant_hosted[$key_m] ?></ul>
	<?php
	}
          $m++;
	    }

	    }
	}
	else
	{

            echo $select_payment;
            $n=1;
	    foreach ($payment_list as $key=>$data)
	    {
					if ( $n==1 )
					{
						$flag = "checked";
					}
					else
					{
						$flag = "";
					}
					if ($data){
	?>
          <ul><input type="radio" name="PAYMENTCHANNEL" value="<?php echo $data ?>_DH" <?php echo $flag ?>> <?php echo $payment_name[$key] ?></ul>
	<?php
	}
          $n++;
	    }
	    if($payment_select_merchant_hosted){
            $m=1;
	    foreach ($payment_list_merchant_hosted as $key_m=>$data_m)
	    {
					if ( $m==1 )
					{
						$flag = "checked";
					}
					else
					{
						$flag = "";
					}
					if ($data_m){
	?>
          <ul><input type="radio" name="PAYMENTCHANNEL" value="<?php echo $data_m ?>_MH_<?php echo $payment_name_merchant_hosted[$key_m]?>"> <?php echo $payment_name_merchant_hosted[$key_m] ?></ul>
	<?php
	}
          $m++;
	    }
	    }
   
	}	
	?>
	
  </div>
  
	<div class="buttons">
		<div class="pull-right">
        <input type="button" id="doku_submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>