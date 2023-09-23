 <link rel="stylesheet" href="<?php echo base_url(); ?>template/back/amcharts/style.css"	type="text/css">
<script src="<?php echo base_url(); ?>template/back/amcharts/amcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/serial.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/themes/light.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/amstock.js" type="text/javascript"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<div id="content-container">
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo translate('City Report');?></h1>
    </div>
    <div class="tab-base">
        <div class="panel">
            <div class="panel-body">
                <div class="tab-content">
                <!-- LIST -->
                    <div class="tab-pane fade active in" id="" style="border:1px solid #ebebeb; border-radius:4px;">
						<?php
                            echo form_open(base_url() . 'admin/exportCityData', array(
                                'class' => 'form-horizontal',
                                'method' => 'post',
                                'enctype' => 'multipart/form-data'
                            ));
                        ?>
                        
                         <div class="panel-footer">
                                <div class="row">
                                     <div class="col-md-4">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="submit" value="Export City Data" class="btn btn-primary fa fa-download">
                                    </div>
                                    
                                    
                                    <div class="col-md-6">
                                    </div>
                                </div>
                        </div>
                        
                        </form>
                            
                        <div class="row">
                                     <div class="col-md-4">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" 
                                            onclick="ajax_redirect('city');"><?php echo translate('back_to_city_list');?>
                                        </button>
                                    </div>
                                    
                                    
                                    <div class="col-md-6">
                                    </div>
                        </div>
                                
                    </div>
                </div>
            </div>
            <!--Panel body-->
        </div>
    </div>
</div>
<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = '';
	var list_cont_func = '';
	var dlt_cont_func = '';
</script>
<script>
    $(document).ready(function() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    });
    
    function other(){
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    }
    
</script>    
  
    
    


