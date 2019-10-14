<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-doku-onecheckout" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
	
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
		
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-doku-onecheckout" class="form-horizontal">
		
		<!-- Make order status default -->		
		<input type="hidden" name="doku_onecheckout_order_status_id" value=2>
				
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $server_params; ?></h3>
      </div>
      <div class="panel-body">
				
				<div class="form-group required">
            <label class="col-sm-2 control-label" for="doku_onecheckout_status"><?php echo $entry_status; ?></label>			
            <div class="col-sm-10">
								<select name="doku_onecheckout_status" id="doku_onecheckout_status" class="form-control">
									<?php if ($doku_onecheckout_status) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>								
            </div>
				</div>

				<div class="form-group required">				
            <label class="col-sm-2 control-label" for="doku_onecheckout_server_set"><?php echo "Choose Server Destination"; ?></label>									
            <div class="col-sm-10">			
								<select name="doku_onecheckout_server_set" id="doku_onecheckout_server_set" class="form-control">
									<?php if ($doku_onecheckout_server_set) { ?>
									<option value="1" selected="selected">Production</option>
									<option value="0">Development</option>
									<?php } else { ?>
									<option value="1">Production</option>
									<option value="0" selected="selected">Development</option>
									<?php } ?>
								</select>														
            </div>						
				</div>

				<div class="form-group">
            <label class="col-sm-2 control-label">Production Server</label>			
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_mallid_prod; ?></label>			
            <div class="col-sm-10">
								<input type="text" name="doku_onecheckout_mallid_prod" id="doku_onecheckout_mallid_prod" value="<?php echo ( $doku_onecheckout_mallid_prod=='' ? "99999999" : $doku_onecheckout_mallid_prod ); ?>" placeholder="Input your Production Mall ID here" class="form-control" />
            </div>						
						<?php if ($error_mallid_prod) { ?>
						<span class="error"><?php echo $error_mallid_prod; ?></span>
						<?php } ?>						
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_sharedkey_prod; ?></label>			
            <div class="col-sm-10">
								<input type="text" name="doku_onecheckout_sharedkey_prod" id="doku_onecheckout_sharedkey_prod" value="<?php echo ( $doku_onecheckout_sharedkey_prod=='' ? "99999999" : $doku_onecheckout_sharedkey_prod ); ?>" placeholder="Input your Production Shared Key here" class="form-control" />
            </div>						
						<?php if ($error_sharedkey_prod) { ?>
						<span class="error"><?php echo $error_sharedkey_prod; ?></span>
						<?php } ?>						
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_chain_prod; ?></label>			
            <div class="col-sm-10">
								<input type="text" name="doku_onecheckout_chain_prod" id="doku_onecheckout_chain_prod" value="<?php echo ( $doku_onecheckout_chain_prod=='' ? "NA" : $doku_onecheckout_chain_prod ); ?>" placeholder="Input your Production Chain Number here" class="form-control" />
            </div>						
						<?php if ($error_chain_prod) { ?>
						<span class="error"><?php echo $error_chain_prod; ?></span>
						<?php } ?>						
				</div>
								
				<div class="form-group">
            <label class="col-sm-2 control-label">Development Server</label>			
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_mallid_dev; ?></label>			
            <div class="col-sm-10">
								<input type="text" name="doku_onecheckout_mallid_dev" id="doku_onecheckout_mallid_dev" value="<?php echo ( $doku_onecheckout_mallid_dev=='' ? "99999999" : $doku_onecheckout_mallid_dev ); ?>" placeholder="Input your Development Mall ID here" class="form-control" />
            </div>						
						<?php if ($error_mallid_dev) { ?>
						<span class="error"><?php echo $error_mallid_dev; ?></span>
						<?php } ?>						
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_sharedkey_dev; ?></label>			
            <div class="col-sm-10">
								<input type="text" name="doku_onecheckout_sharedkey_dev" id="doku_onecheckout_sharedkey_dev" value="<?php echo ( $doku_onecheckout_sharedkey_dev=='' ? "99999999" : $doku_onecheckout_sharedkey_dev ); ?>" placeholder="Input your Development Shared Key here" class="form-control" />
            </div>						
						<?php if ($error_sharedkey_dev) { ?>
						<span class="error"><?php echo $error_sharedkey_dev; ?></span>
						<?php } ?>						
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_chain_dev; ?></label>			
            <div class="col-sm-10">
								<input type="text" name="doku_onecheckout_chain_dev" id="doku_onecheckout_chain_dev" value="<?php echo ( $doku_onecheckout_chain_dev=='' ? "NA" : $doku_onecheckout_chain_dev ); ?>" placeholder="Input your Development Chain Number here" class="form-control" />
            </div>						
						<?php if ($error_chain_dev) { ?>
						<span class="error"><?php echo $error_chain_dev; ?></span>
						<?php } ?>						
				</div>
			</div>
		</div>	

						
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $opencart_params; ?></h3>
      </div>
      <div class="panel-body">
			
				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_review_edu; ?></label>			
            <div class="col-sm-10">
								<select name="doku_onecheckout_review_edu" id="doku_onecheckout_review_edu" class="form-control">
									<?php if ($doku_onecheckout_review_edu) { ?>
									<option value="1" selected="selected">Yes</option>
									<option value="0">No</option>
									<?php } else { ?>
									<option value="1">Yes</option>
									<option value="0" selected="selected">No</option>
									<?php } ?>
								</select>																				
								<span class="breadcrumb">Are you using DOKU EDU Services? No if you unsure.</span>
            </div>						
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_identify; ?></label>			
            <div class="col-sm-10">
								<select name="doku_onecheckout_identify" id="doku_onecheckout_identify" class="form-control">
									<?php if ($doku_onecheckout_identify) { ?>
									<option value="1" selected="selected">Yes</option>
									<option value="0">No</option>
									<?php } else { ?>
									<option value="1">Yes</option>
									<option value="0" selected="selected">No</option>
									<?php } ?>
								</select>																				
								<span class="breadcrumb">Are you using Identify process? No if you unsure.</span>
            </div>						
				</div>
				
				<div class="form-group">
						<label class="col-sm-2 control-label" for="doku_onecheckout_geo_zone_id"><?php echo $entry_geo_zone; ?></label>
						<div class="col-sm-10">
								<select name="doku_onecheckout_geo_zone_id" id="doku_onecheckout_geo_zone_id" class="form-control">
									<option value="0"><?php echo $text_all_zones; ?></option>
									<?php foreach ($geo_zones as $geo_zone) { ?>
									<?php if ($geo_zone['geo_zone_id'] == $doku_onecheckout_geo_zone_id) { ?>
									<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select>
						</div>
				</div>
				
				<div class="form-group">
						<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
						<div class="col-sm-10">
								<input type="text" name="doku_onecheckout_sort_order" value="<?php echo $doku_onecheckout_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
						</div>
				</div>
				
			</div>
		</div>	


    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $paymentchannel_params; ?></h3>
      </div>
      <div class="panel-body">

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_doku_name; ?></label>			
            <div class="col-sm-10">
								<input type="text" name="doku_onecheckout_doku_name" id="doku_onecheckout_doku_name" value="<?php echo ( $doku_onecheckout_doku_name=='' ? "DOKU Payment Gateway" : $doku_onecheckout_doku_name ); ?>" placeholder="Input your Production Shared key here" class="form-control" />
            </div>						
						<?php if ($error_doku_name) { ?>
						<span class="error"><?php echo $error_doku_name; ?></span>
						<?php } ?>
				</div>

				<div class="form-group">
						<label class="col-sm-2 control-label" for="doku_onecheckout_geo_zone_id"><?php echo $paymentchannel_selection; ?></label>
						<div class="col-sm-10">
								<select name="doku_onecheckout_payment_select" id="doku_onecheckout_payment_select" class="form-control">
									<?php if ($doku_onecheckout_payment_select) { ?>
									<option value="1" selected="selected">Selected</option>
									<option value="0">All</option>
									<?php } else { ?>
									<option value="1">Selected</option>
									<option value="0" selected="selected">All</option>
									<?php } ?>
								</select>																				
						</div>
				</div>				

				<div class="form-group">
            <label class="control-label" style="margin-left: 30px;">If using 'Selected' payment channel, please choose payment channel to display</label>			
				</div>
				
				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $paymentchannel_cc; ?></label>			
            <div class="col-sm-10">
								<select name="doku_onecheckout_payment_list[1]" id="doku_onecheckout_payment_list[1]" class="form-control">
									<?php if ($doku_onecheckout_payment_list[1]) { ?>
									<option value="15" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="15">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>																												
								<input type="hidden" name="doku_onecheckout_payment_name[1]" value="VISA / Master Credit Card">								
            </div>						
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $paymentchannel_clickpay; ?></label>			
            <div class="col-sm-10">
								<select name="doku_onecheckout_payment_list[2]" id="doku_onecheckout_payment_list[2]" class="form-control">
									<?php if ($doku_onecheckout_payment_list[2]) { ?>
									<option value="02" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="02">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>																												
								<input type="hidden" name="doku_onecheckout_payment_name[2]" value="Mandiri Clickpay">								
            </div>						
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $paymentchannel_dokuwalet; ?></label>			
            <div class="col-sm-10">
								<select name="doku_onecheckout_payment_list[4]" id="doku_onecheckout_payment_list[4]" class="form-control">
									<?php if ($doku_onecheckout_payment_list[4]) { ?>
									<option value="04" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="04">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>																												
								<input type="hidden" name="doku_onecheckout_payment_name[4]" value="Dokuwallet">								
            </div>						
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $paymentchannel_epaybri; ?></label>			
            <div class="col-sm-10">
								<select name="doku_onecheckout_payment_list[6]" id="doku_onecheckout_payment_list[6]" class="form-control">
									<?php if ($doku_onecheckout_payment_list[6]) { ?>
									<option value="05" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="05">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>																												
								<input type="hidden" name="doku_onecheckout_payment_name[6]" value="ePay BRI">								
            </div>						
				</div>
				
				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $paymentchannel_permatavalite; ?></label>			
            <div class="col-sm-10">
								<select name="doku_onecheckout_payment_list[5]" id="doku_onecheckout_payment_list[5]" class="form-control">
									<?php if ($doku_onecheckout_payment_list[5]) { ?>
									<option value="06" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="06">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>																												
								<input type="hidden" name="doku_onecheckout_payment_name[5]" value="ATM Transfer">								
            </div>						
				</div>
				
				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $paymentchannel_dokualfa; ?></label>			
            <div class="col-sm-10">
								<select name="doku_onecheckout_payment_list[14]" id="doku_onecheckout_payment_list[14]" class="form-control">
									<?php if ($doku_onecheckout_payment_list[14]) { ?>
									<option value="14" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="14">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>																												
								<input type="hidden" name="doku_onecheckout_payment_name[14]" value="DOKU Alfa">								
            </div>						
				</div>
				
			</div>
		</div>	

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $merchanthosted_title; ?></h3>
      </div>
      <div class="panel-body">
			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="doku_onecheckout_merchant_hosted_status"><?php echo "Status Merchant Hosted"; ?></label>									
            <div class="col-sm-10">			
								<select name="doku_onecheckout_merchant_hosted_status" id="doku_onecheckout_merchant_hosted_status" class="form-control">
									<?php if ($doku_onecheckout_merchant_hosted_status) { ?>
									<option value="1" selected="selected">True</option>
									<option value="0">False</option>
									<?php } else { ?>
									<option value="1">True</option>
									<option value="0" selected="selected">False</option>
									<?php } ?>
								</select>														
            </div>						
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_mallid_merchant_hosted; ?></label>			
            <div class="col-sm-10">
								<input type="text" name="doku_onecheckout_mallid_merchant_hosted" id="doku_onecheckout_mallid_merchant_hosted" value="<?php echo ( $doku_onecheckout_mallid_merchant_hosted=='' ? "99999999" : $doku_onecheckout_mallid_merchant_hosted ); ?>" placeholder="Input your Merchant Hosted Mall ID here" class="form-control" />
            </div>						
						<?php if ($error_mallid_merchant_hosted) { ?>
						<span class="error"><?php echo $error_mallid_merchant_hosted; ?></span>
						<?php } ?>						
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_sharedkey_merchant_hosted; ?></label>			
            <div class="col-sm-10">
								<input type="text" name="doku_onecheckout_sharedkey_merchant_hosted" id="doku_onecheckout_sharedkey_merchant_hosted" value="<?php echo ( $doku_onecheckout_sharedkey_merchant_hosted=='' ? "99999999" : $doku_onecheckout_sharedkey_merchant_hosted ); ?>" placeholder="Input your Merchant Hosted Shared Key here" class="form-control" />
            </div>						
						<?php if ($error_sharedkey_merchant_hosted) { ?>
						<span class="error"><?php echo $error_sharedkey_merchant_hosted; ?></span>
						<?php } ?>						
				</div>

				<div class="form-group required">
            <label class="col-sm-2 control-label" for=""><?php echo $entry_chain_merchant_hosted; ?></label>			
            <div class="col-sm-10">
								<input type="text" name="doku_onecheckout_chain_merchant_hosted" id="doku_onecheckout_chain_merchant_hosted" value="<?php echo ( $doku_onecheckout_chain_merchant_hosted=='' ? "NA" : $doku_onecheckout_chain_merchant_hosted ); ?>" placeholder="Input your Merchant Hosted Chain Number here" class="form-control" />
            </div>						
						<?php if ($error_chain_merchant_hosted) { ?>
						<span class="error"><?php echo $error_chain_merchant_hosted; ?></span>
						<?php } ?>						
				</div>

				<div class="form-group">
            <label class="control-label" style="margin-left: 30px;">Payment Channel Merchant Hosted</label>	
				</div>
			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="merchant_hosted_creditcard"><?php echo "Visa / Master Credit Card Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[1]" id="doku_onecheckout_payment_merchant_hosted_list[1]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[1]) { ?>
									<option value="15" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="15">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>
								<div class="col-sm-7">
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[1]" value="Visa / Master Credit Card MH" placeholder="Input channel name Visa/master credit card" class="form-control">								
            </div>						
				</div>

												<div class="form-group required">				
            <label class="col-sm-2 control-label" for="merchant_hosted_dokuwallet"><?php echo "Dokuwallet Merchant Hosteed"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[2]" id="doku_onecheckout_payment_merchant_hosted_list[04]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[2]) { ?>
									<option value="04" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="04">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>																							
								</div>
								<div class="col-sm-7">
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[2]" value="Dokuwallet MH" placeholder="Input channel name Dokuwallet" class="form-control">								
            </div>						
				</div>

												<div class="form-group required">				
            <label class="col-sm-2 control-label" for="merchant_hosted_sinarmas"><?php echo "Virtual Account Sinarmas Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[3]" id="doku_onecheckout_payment_merchant_hosted_list[3]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[3]) { ?>
									<option value="22" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="22">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>								
							</div>
								<div class="col-sm-4">			
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[3]" value="Virtual Account Sinarmas MH" placeholder="Input channel name Sinarmas VA" class="form-control">								
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[22]" id="doku_onecheckout_payment_merchant_hosted_list_va[22]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[22]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[22] ); ?>" placeholder="Input your BIN for Sinarmas VA" class="form-control" />
            </div>						
				</div>

												<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account BCA Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[4]" id="doku_onecheckout_payment_merchant_hosted_list[4]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[4]) { ?>
									<option value="29" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="29">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[4]" value="Virtual Account BCA MH" placeholder="Input channel name BCA VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[29]" id="doku_onecheckout_payment_merchant_hosted_list_va[29]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[29]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[29] ); ?>" placeholder="Input your BIN for BCA VA" class="form-control" />
            </div>						
				</div>

 			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account Indomaret Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[5]" id="doku_onecheckout_payment_merchant_hosted_list[5]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[5]) { ?>
									<option value="31" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="31">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[5]" value="Virtual Account Indomaret MH" placeholder="Input channel name Indomaret VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[31]" id="doku_onecheckout_payment_merchant_hosted_list_va[31]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[31]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[31] ); ?>" placeholder="Input your BIN for BCA Indomaret" class="form-control" />
            </div>						
				</div>

			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account CIMB Niaga Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[6]" id="doku_onecheckout_payment_merchant_hosted_list[6]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[6]) { ?>
									<option value="32" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="32">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[6]" value="Virtual Account CIMB Niaga MH" placeholder="Input channel name CIMB Niaga VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[32]" id="doku_onecheckout_payment_merchant_hosted_list_va[32]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[32]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[32] ); ?>" placeholder="Input your BIN for CIMB Niaga VA" class="form-control" />
            </div>						
				</div>

			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account Danamon Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[7]" id="doku_onecheckout_payment_merchant_hosted_list[7]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[7]) { ?>
									<option value="33" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="33">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[7]" value="Virtual Account Danamon MH" placeholder="Input channel name Danamon VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[33]" id="doku_onecheckout_payment_merchant_hosted_list_va[33]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[33]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[33] ); ?>" placeholder="Input your BIN for Danamon VA" class="form-control" />
            </div>						
				</div>

			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account BRI Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[8]" id="doku_onecheckout_payment_merchant_hosted_list[8]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[8]) { ?>
									<option value="34" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="34">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[8]" value="Virtual Account BRI MH" placeholder="Input channel name BRI VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[34]" id="doku_onecheckout_payment_merchant_hosted_list_va[34]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[34]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[34] ); ?>" placeholder="Input your BIN for BRI VA" class="form-control" />
            </div>						
				</div>	

			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account Alfa Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[9]" id="doku_onecheckout_payment_merchant_hosted_list[9]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[9]) { ?>
									<option value="35" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="35">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[9]" value="Virtual Account Alfa MH" placeholder="Input channel name Alfa VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[35]" id="doku_onecheckout_payment_merchant_hosted_list_va[35]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[35]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[35] ); ?>" placeholder="Input your BIN for Alfa VA" class="form-control" />
            </div>						
				</div>

			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account Permata Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[10]" id="doku_onecheckout_payment_merchant_hosted_list[10]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[10]) { ?>
									<option value="36" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="36">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[10]" value="Virtual Account Permata MH" placeholder="Input channel name Permata VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[36]" id="doku_onecheckout_payment_merchant_hosted_list_va[36]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[36]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[36] ); ?>" placeholder="Input your BIN for Permata VA" class="form-control" />
            </div>						
				</div>

			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account BNI Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[11]" id="doku_onecheckout_payment_merchant_hosted_list[11]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[11]) { ?>
									<option value="40" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="40">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[11]" value="Virtual Account BNI MH" placeholder="Input channel name BNI VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[40]" id="doku_onecheckout_payment_merchant_hosted_list_va[40]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[40]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[40] ); ?>" placeholder="Input your BIN for BNI VA" class="form-control" />
            </div>						
				</div>

			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account Mandiri Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[12]" id="doku_onecheckout_payment_merchant_hosted_list[12]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[12]) { ?>
									<option value="41" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="41">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[12]" value="Virtual Account Mandiri MH" placeholder="Input channel name Mandiri VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[41]" id="doku_onecheckout_payment_merchant_hosted_list_va[41]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[41]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[41] ); ?>" placeholder="Input your BIN for Mandiri VA" class="form-control" />
            </div>						
				</div>

			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account QNB Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[13]" id="doku_onecheckout_payment_merchant_hosted_list[13]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[13]) { ?>
									<option value="42" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="42">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[13]" value="Virtual Account QNB MH" placeholder="Input channel name QNB VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[42]" id="doku_onecheckout_payment_merchant_hosted_list_va[42]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[42]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[42] ); ?>" placeholder="Input your BIN for QNB VA" class="form-control" />
            </div>						
				</div>

			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account BTN Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[14]" id="doku_onecheckout_payment_merchant_hosted_list[14]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[14]) { ?>
									<option value="43" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="43">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[14]" value="Virtual Account BTN MH" placeholder="Input channel name BTN VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[43]" id="doku_onecheckout_payment_merchant_hosted_list_va[43]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[43]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[43] ); ?>" placeholder="Input your BIN for BTN VA" class="form-control" />
            </div>						
				</div>


			<div class="form-group required">				
            <label class="col-sm-2 control-label" for="test_checkbox"><?php echo "Virtual Account Maybank Merchant Hosted"; ?></label>											
            <div class="col-sm-3">
								<select name="doku_onecheckout_payment_merchant_hosted_list[15]" id="doku_onecheckout_payment_merchant_hosted_list[15]" class="form-control">
									<?php if ($doku_onecheckout_payment_merchant_hosted_list[15]) { ?>
									<option value="44" selected="selected">Enable</option>
									<option value="">Disable</option>
									<?php } else { ?>
									<option value="44">Enable</option>
									<option value="" selected="selected">Disable</option>
									<?php } ?>
								</select>
								</div>												
			<div class="col-sm-4">					
								<input type="text" name="doku_onecheckout_payment_merchant_hosted_name[15]" value="Virtual Account Maybank MH" placeholder="Input channel name Maybank VA" class="form-control">		
            </div>						
            <div class="col-sm-3">			
				<input type="text" name="doku_onecheckout_payment_merchant_hosted_list_va[44]" id="doku_onecheckout_payment_merchant_hosted_list_va[44]" value="<?php echo ( $doku_onecheckout_payment_merchant_hosted_list_va[44]=='' ? "99999999" : $doku_onecheckout_payment_merchant_hosted_list_va[44] ); ?>" placeholder="Input your BIN for Maybank VA" class="form-control" />
            </div>						
				</div>

			</div>
		</div>	

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $url_title; ?></h3>
      </div>
      <div class="panel-body">

				<div class="form-group required">			
						<label class="col-sm-2 control-label" for="input-sort-order">Identify</label>
						<div class="col-sm-10">
								<input type="text" value="<?php echo $url_identify; ?>" class="form-control" readonly="readonly"/>
						</div>			
				</div>

				<div class="form-group required">			
						<label class="col-sm-2 control-label" for="input-sort-order">Notify</label>
						<div class="col-sm-10">
								<input type="text" value="<?php echo $url_notify; ?>" class="form-control" readonly="readonly"/>
						</div>			
				</div>

				<div class="form-group required">			
						<label class="col-sm-2 control-label" for="input-sort-order">Redirect</label>
						<div class="col-sm-10">
								<input type="text" value="<?php echo $url_redirect; ?>" class="form-control" readonly="readonly"/>
						</div>			
				</div>

				<div class="form-group required">			
						<label class="col-sm-2 control-label" for="input-sort-order">Review</label>
						<div class="col-sm-10">
								<input type="text" value="<?php echo $url_review; ?>" class="form-control" readonly="readonly"/>
						</div>			
				</div>
				
			</div>
		</div>	
					
	</div>	
	
	</form>
	
</div>

<?php echo $footer; ?>