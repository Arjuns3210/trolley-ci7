 <link rel="stylesheet" href="<?php echo base_url(); ?>template/back/amcharts/style.css"	type="text/css">
<script src="<?php echo base_url(); ?>template/back/amcharts/amcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/serial.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/themes/light.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/amstock.js" type="text/javascript"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<div id="content-container">
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo translate('Bill_of_quantity_report');?></h1>
    </div>
    <div class="tab-base">
        <div class="panel">
            <div class="panel-body">
                <div class="tab-content">
                <!-- LIST -->
                    <div class="tab-pane fade active in" id="" style="border:1px solid #ebebeb; border-radius:4px;">
						<?php
                            echo form_open(base_url() . 'admin/exportProductSaleReport', array(
                                'class' => 'form-horizontal',
                                'method' => 'post',
                                'enctype' => 'multipart/form-data'
                            ));
                        ?>
                        
                        <div class="panel-body">
                             <div class="form-group">
                                <label class="col-sm-4 control-label" for="demo-hor-inputpass"><?php echo translate('delivery_date'); ?><span style="color:red">*</span></label>
                                <div class="col-sm-3" >
                                    <input type="date"  name="sale_date" value="<?php echo date('Y-m-d'); ?>"  onchange="getTimeSlots(this.value)"class="form-control required">
                                </div>
                            </div>
                            
                            <div class="form-group" id="slots" >
                                <label class="col-sm-4 control-label" for="timeslotss"><?php echo translate('Timeslots');?><span style="color:red">*</span></label>
                                <div class="col-sm-3">
                                    <select name="timeslot" id="timeslotss" class="form-control" required="" style="width: 100%;">
                                        <option value="">Select</option>
                                    </select>
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
    var base_url = '<?php echo base_url(); ?>'
    var user_type = 'admin';
    var module = 'productSaleReport';
    var list_cont_func  = '';
    $('#daterange').daterangepicker();
    var today ='<?php echo date('Y-m-d');?>'
    
    $(document).ready(function() {
        getTimeSlots(today);
    });

    function getTimeSlots(date)
    {
        $('#slots').hide('slow');
        if(date != "" )
        {
            $.ajax({
                    url:"<?php echo base_url();?>admin/productSaleReport/fetchTimeslots",
                    data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',date : date},
                    dataType: 'json',
                    cache: false,
                    method:'post',
                    success: function(res)
                    {
                        if(res['status']=="success")
                        {
                            if(res['option'] != "")
                            {
                                $("#timeslotss").html("<option value=''>Select</option>"+res['option']);
                            }
                            else
                            {
                                $("#timeslotss").html("<option value=''>Select</option>");
                            }
                        }
                        else
                        {	
                            $("#timeslotss").html("<option value=''>Select</option>");
                        }
                    }
            });
        }else{
            $("#timeslotss").html("<option value=''>Select</option>");
        }
        $('#slots').show('slow');
    }
    
</script>    
  
    
    


