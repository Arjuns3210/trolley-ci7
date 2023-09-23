<div id="content-container">
	<div id="page-title">
		<h1 class="page-header text-overflow"><?php echo translate('manage_city');?></h1>
	</div>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
					<div class="col-md-12" style="border-bottom: 1px solid #ebebeb;padding:10px;">
                                              <?php if($this->crud_model->admin_permission('city_add')){ ?>
						<button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right" 
                        	onclick="ajax_modal('add','<?php echo translate('add_city'); ?>','<?php echo translate('successfully_added!');?>','city_add','')">
								<?php echo translate('create_city');?>
						</button>
                                              <?php } ?>
                                            
                                            <?php if($this->crud_model->admin_permission('city_report')){ ?>
						<button class="btn btn-dark btn-labeled fa fa-download pull-right" 
                                                        onclick="ajax_redirect('exportCityData');">
								<?php echo translate('export_city_report');?>
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
	var module = 'city';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
</script>

