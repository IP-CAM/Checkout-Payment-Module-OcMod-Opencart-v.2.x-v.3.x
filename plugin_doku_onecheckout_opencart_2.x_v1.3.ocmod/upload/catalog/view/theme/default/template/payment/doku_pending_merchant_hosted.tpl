<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div class="container"><?php echo $content_top; ?>
  <div style="text-align:center;">
    <h2>Your Transaction is Waiting for your Payment</h2>
		<h1><strong><?php echo $payment_code; ?></strong></h1>
		<h1><strong><?php echo $channel_name; ?></strong></h1>	
		</br>	
    <p><?php echo $return_message; ?></p>
		<div class="buttons">
			<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
		</div>		
  </div>
  <?php echo $content_bottom; ?>
</div>
	
<?php echo $footer; ?>