<div id="content-container">
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo translate('manage_newsletter');?></h1>
    </div>
    <div class="tab-base">
        <div class="panel">
            <div class="panel-body">
                <div class="tab-content"> 
                                      
                    <div class="tab-pane fade active in" id="list" 
                        style="border:1px solid #ebebeb; border-radius:4px;">   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="prod" style="display:none;"></span>
<script>
    var base_url = '<?php echo base_url(); ?>';
    var user_type = 'admin';
    var module = 'newsletter';
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

