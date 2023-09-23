<div id="content-container">
	<div id="page-title">
		<h1 class="page-header text-overflow" ><?php echo translate('Manage_Attributes');?></h1>
	</div>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
					<div style="border-bottom: 1px solid #ebebeb;padding: 25px 5px 5px 5px;"
                    	class="col-md-12" >
                                        
                                            <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" 
                                                  style="display:none;"  onclick="dlt_cont_func = 'delete';list_cont_func = 'list';ajax_set_list();  proceed('to_list');"><?php echo translate('back_to_attribute_list');?>
                                            </button>
					</div>
					<br>
                    <div class="tab-pane fade active in" 
                    	id="list" style="border:1px solid #ebebeb; border-radius:4px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'attribute';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
        
        function proceed(type){
		if(type == 'to_list'){
			$(".pro_list_btn").hide();
			$(".add_pro_btn").show();
		} else if(type == 'to_add'){
			$(".add_pro_btn").hide();
			$(".pro_list_btn").show();
		}
	}
</script>

