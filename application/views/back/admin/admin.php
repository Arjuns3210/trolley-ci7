<div id="content-container">
	<div id="page-title">
		<h1 class="page-header text-overflow">
		<?php echo translate('manage_staffs');?>
		</h1>
	</div>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
					<div class="col-md-12" 
                    	style="border-bottom: 1px solid #ebebeb;padding: 25px 5px 5px 5px;">
						<!--Button-->
						<button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt"
                        	onclick="ajax_modal('add','<?php echo translate('add_staff');?>','<?php echo translate('successfully_added!');?>','admin_add','')" >
							<?php echo translate('create_admin');?>
						</button>
                                                
                                                
                                                <?php if($this->crud_model->admin_permission('import_delivery_boy')){ ?>
						<button class="btn btn-dark btn-labeled fa fa-upload pull-right" 
                                                        onclick="ajax_redirect('importDeliveryboy');">
								<?php echo translate('import_delivery_boys');?>
						</button>
                                              <?php } ?>
                                                <?php if($this->crud_model->admin_permission('update_delivery_boy')){ ?>
						<button class="btn btn-purple btn-labeled fa fa-upload pull-right" 
                                                        onclick="ajax_redirect('updateDeliveryboyInfo');">
								<?php echo translate('update_delivery_boys');?>
						</button>
                                              <?php } ?>
					</div>
					<br>
                                        
                                        
                                        
					<!--Main Content : loaded via ajax-->
					<div class="tab-pane fade active in" id="list" 
                    	style="border:1px solid #ebebeb; border-radius:4px;">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var base_url = '<?php echo base_url(); ?>';
	var user_type = 'admin';
	var module = 'admins';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
        
        function proceed(type) {
        if (type == 'to_list') {
            $(".pro_list_btn").show();
        } else if (type == 'to_add') {
            $(".pro_list_btn").hide();
        }
    }
</script>
