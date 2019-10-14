<?php echo $header; ?><body onload="document.doku_form.submit()">
<?php echo $column_left; ?><?php echo $column_right; ?>

<div class="container"><?php echo $content_top; ?>
<form id="doku_form" name="doku_form" action="<?php echo $oco_action; ?>" method="post">
  <input type=hidden name="MALLID" value="<?php echo $oco_mallid  ;?>">
  <input type=hidden name="CHAINMERCHANT" value="<?php echo $oco_chain  ;?>">
  <input type=hidden name="AMOUNT" value="<?php echo $oco_amount  ;?>">
  <input type=hidden name="PURCHASEAMOUNT" value="<?php echo $oco_amount  ;?>">
  <input type=hidden name="TRANSIDMERCHANT" value="<?php echo $oco_transidmerchant ;?>">
  <input type=hidden name="WORDS" value="<?php echo $oco_words ;?>">
  <input type=hidden name="REQUESTDATETIME" value="<?php echo $oco_request_datetime ;?>">
  <input type=hidden name="CURRENCY" value="<?php echo $oco_currency ;?>">
  <input type=hidden name="PURCHASECURRENCY" value="<?php echo $oco_currency ;?>">
  <input type=hidden name="SESSIONID" value="<?php echo $oco_session_id ;?>">
  <input type=hidden name="NAME" value="<?php echo $oco_allname ;?>">
  <input type=hidden name="PAYMENTCHANNEL" value="<?php echo $paymentchannel ;?>">
  <input type=hidden name="EMAIL" value="<?php echo $email ;?>">
  <input type=hidden name="HOMEPHONE" value="<?php echo $telephone ;?>">
  <input type=hidden name="WORKPHONE" value="<?php echo $telephone ;?>">
  <input type=hidden name="MOBILEPHONE" value="<?php echo $telephone ;?>">
  <input type=hidden name="BASKET" value="<?php echo $data_product  ;?>">
  <input type=hidden name="ADDRESS" value="<?php echo $oco_address_1 . " " . $oco_address_2 ;?>">
  <input type=hidden name="CITY" value="<?php echo $oco_city  ;?>">
  <input type=hidden name="STATE" value="<?php echo $oco_zone  ;?>">
  <input type=hidden name="ZIPCODE" value="<?php echo $oco_postcode  ;?>">

</br></br></br></br></br></br>
  <div align="center">Please wait, this page will redirect to DOKU
  </br></br></br>
<input type="submit" name="continue" value="Continue" class="button">
</form>
  </div>
</br></br></br></br></br></br></br></br></br>
  <?php echo $content_bottom; ?>
</div>
	
<?php echo $footer; ?>