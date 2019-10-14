<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<div class="container"><?php echo $content_top; ?>
  <div style="text-align:center;">
    <h2><?php echo $heading_title; ?></h2>
		<h1><strong><?php echo $payment_code; ?></strong></h1>		
    <p><?php echo $text_message; ?></p>
		<div class="buttons">
			<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
		</div>		
  </div>
  <?php echo $content_bottom; ?>
</div>
	
<?php echo $footer; ?>