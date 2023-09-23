<div id="content-container">
	<div id="page-title">
		<h1 class="page-header text-overflow"><?php echo translate('manage_service_charge');?></h1>
	</div>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
					<div class="col-md-12" style="border-bottom: 1px solid #ebebeb;padding:10px;">
                                              <?php if($this->crud_model->admin_permission('service_charge_add')){ ?>
						<button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right" 
                        	onclick="ajax_modal('add','<?php echo translate('add_service_charge'); ?>','<?php echo translate('successfully_added!');?>','service_charge_add','')">
								<?php echo translate('add_service_charge');?>
						</button>
                                              <?php } ?>
					</div>
					<div class="tab-pane fade active in" id="list" 
                    	style="border:1px solid #ebebeb; 
                        	border-radius:4px;">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'service_charge';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
</script>

