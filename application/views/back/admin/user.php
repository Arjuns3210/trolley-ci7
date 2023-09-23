<div id="content-container">
	<div id="page-title">
		<h1 class="page-header text-overflow"><?php echo translate('Manage_customers');?></h1>
	</div>
	<div class="tab-base">
		<div class="panel">
			<div class="panel-body">
				<div class="tab-content">
					<div class="col-md-12"></div>
					<br>
                    <!-- LIST -->
                    <div class="tab-pane fade active in" id="list" style="border:1px solid #ebebeb; border-radius:4px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="customerlist" style="display:none;"></span>
<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = 'user';
	var list_cont_func = 'list';
	var dlt_cont_func = 'delete';
    //added by sagar : 19-01  START
        function proceed(type){
		if(type == 'to_list'){
			$(".pro_list_btn").show();
			$(".add_pro_btn").hide();
		} else if(type == 'to_add'){

			$(".add_pro_btn").show();
			$(".pro_list_btn").hide();

		}
	}
    //added by sagar : 19-01  END

</script>
