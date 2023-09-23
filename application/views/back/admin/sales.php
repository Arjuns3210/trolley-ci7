<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<div id="content-container">
	<div id="page-title">
		<h1 class="page-header text-overflow" ><?php echo translate('manage_orders');?></h1>
	</div>
	<div class="tab-base">
		<div class="panel">
		<div class="panel-body">
                    <div class="tab-content">    
                        <div class="col-md-12" style="border-bottom: 1px solid #ebebeb;padding: 5px;">
                            <?php /* if($this->crud_model->admin_permission('sale_add_edit')){ ?>
                            <button class="btn btn-primary btn-labeled fa fa-plus-circle add_pro_btn pull-right" 
                                onclick="ajax_set_full('add','<?php echo translate('add_order'); ?>','<?php echo translate('successfully_added!'); ?>','sales_add',''); proceed('to_list');"><?php echo translate('create_order');?>
                            </button>
                            <?php } */  ?>
                            <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" 
                                style="display:none;"  onclick="ajax_set_list();  proceed('to_add');"><?php echo translate('back_to_orders_list');?>
                            </button>
                        </div>
                <!-- LIST -->
                        <div class="tab-pane fade active in" id="list">

                        </div>
                    </div>
                </div>
                </div>
	</div>
</div>

<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'sales';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
        
        function proceed(type){
		if(type == 'to_list'){
			$(".pro_list_btn").show();
			$(".add_pro_btn").hide();
		} else if(type == 'to_add'){
			$(".add_pro_btn").show();
			$(".pro_list_btn").hide();
		}
	}
        proceed('to_add');
</script>

