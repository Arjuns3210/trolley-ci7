 <link rel="stylesheet" href="<?php echo base_url(); ?>template/back/amcharts/style.css"	type="text/css">
<script src="<?php echo base_url(); ?>template/back/amcharts/amcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/serial.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/themes/light.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/amstock.js" type="text/javascript"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<div id="content-container">
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo translate('delivery_Report');?></h1>
    </div>
    <div class="tab-base">
        <div class="panel">
            <div class="panel-body">
                <div class="tab-content">
                <!-- LIST -->
                    <div class="tab-pane fade active in" id="" style="border:1px solid #ebebeb; border-radius:4px;">
						<?php
                            echo form_open(base_url() . 'index.php/admin/exportDeliveryReport', array(
                                'class' => 'form-horizontal',
                                'method' => 'post',
                                'enctype' => 'multipart/form-data'
                            ));
                        ?>
                        
                        <div class="panel-body">
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="demo-hor-inputpass"><?php echo translate('delivery_date'); ?><span style="color:red">*</span></label>
                                <div class="col-sm-3" >
                                    <input type="date" id='daterange' name="delivery_date" value="<?php echo date('Y-m-d'); ?>" class="form-control required">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="demo-hor-inputpass1">Payment Status</label>
                                <div class="col-sm-6" >
                                    <input type="radio"  style="display: inline;" name="payment_status" id="payment_status_2" value="pending" ><label for="payment_status_2">Pending</label>&nbsp;&nbsp; 
                                    <input type="radio" style="display: inline;" name="payment_status" id="payment_status_1" value="paid"><label for="payment_status_1">Paid</label>&nbsp;&nbsp; 
                                </div>
                            </div>
                            
                        </div>
                         <div class="panel-footer">
                                <div class="row">
                                    <div class="col-sm-7 col-sm-offset-5">
                                        <input type="submit" value="Export" class="btn btn-primary">
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--Panel body-->
        </div>
    </div>
</div>
  
    
 
<script>
    $(document).ready(function() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    });
</script>    
     
<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = '';
	var list_cont_func = '';
	var dlt_cont_func = '';
</script>




