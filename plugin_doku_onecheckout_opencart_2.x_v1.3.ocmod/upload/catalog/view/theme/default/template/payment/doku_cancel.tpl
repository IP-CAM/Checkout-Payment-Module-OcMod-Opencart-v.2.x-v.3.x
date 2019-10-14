<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<div class="container"><?php echo $content_top; ?>
  <div style="text-align:center;">
    <h2><?php echo $heading_title; ?></h2>
    <p><?php echo $text_message; ?></p>
</br></br></br>
		<div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
       </div>	
       </br></br></br></br></br></br>	
  </div>
  <?php echo $content_bottom; ?>
    <?php echo $column_right; ?>
</div>
	
<?php echo $footer; ?>
