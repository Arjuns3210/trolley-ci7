<div id="content-container">
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo translate('manage_suggested_products');?></h1>
    </div>
    <div class="tab-base">
        <div class="panel">
            <div class="panel-body">
                <div class="tab-content"> 
                    <div class="col-md-12" style="border-bottom: 1px solid #ebebeb;padding: 5px;">
                    
                        <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" 
                            style="display:none;"  onclick="ajax_set_list();  proceed('to_add');"><?php echo translate('back_to_suggested_list');?>
                        </button> 
                    </div>         
                                      
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
    var module = 'suggested';
    var list_cont_func = 'list';
</script>

