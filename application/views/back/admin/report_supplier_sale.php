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
        <h1 class="page-header text-overflow"><?php echo translate('Supplier Day Sale Report');?></h1>
    </div>
    <div class="tab-base">
        <div class="panel">
            <div class="panel-body">
                <div class="tab-content">
                <!-- LIST -->
                    <div class="tab-pane fade active in" id="" style="border:1px solid #ebebeb; border-radius:4px;">
						<?php
                            echo form_open(base_url() . 'index.php/admin/exportSupplierDaySale', array(
                                'class' => 'form-horizontal',
                                'method' => 'post',
                                'enctype' => 'multipart/form-data'
                            ));
                        ?>
                        
                        <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="demo-hor-inputpass"><?php echo translate('date_range'); ?><span style="color:red">*</span></label>
                                    <div class="col-sm-6" >
                                        <input type="text" id='daterange' name="daterange" value="<?php echo $daterange; ?>" class="form-control required">
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                     <label for="supplier" class="col-sm-4 control-label" >Supplier<span style="color:red">*</span></label>
                                     <div class="col-sm-6">
                                         <select id="supplier" name="supplier" class="demo-chosen-select form-control required"  onchange="selectSupplierProduct()">
                                             <option value="0">Select one</option>
                                             <?php 
                                                     if(isset($suppliers) && is_array($suppliers) && !empty($suppliers[0])){
                                                     foreach($suppliers as $row){
                                             ?>
                                                     <option value="<?php echo $row['supplier_id'] ; ?>"><?php echo $row['supplier_name'].'|'. $row['mobile_number'].'|'.$row['company_name']; ?></option>
                                             <?php }}?>
                                     </select>
                                     </div>
                                     
                                 </div>
                            <?php /*
                             <div class="form-group">
                                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('product');?></label>
                                <div class="col-sm-6">
                                    <?php echo $this->crud_model->select_html('product','product','title','add','demo-chosen-select required','','','','get_variations'); ?>
                                </div>
                            </div>
                              */ ?>
                            <div class="form-group" id="prodd" style="display:none;">
                                <label class="col-sm-4 control-label" for="demo-hor-333"><?php echo translate('product');?></label>
                                <div class="col-sm-6" id="product">
                                </div>
                            </div>

                             <div class="form-group" id="var" style="display:none;">
                                <label class="col-sm-4 control-label" for="demo-hor-31"><?php echo translate('variation');?></label>
                                <div class="col-sm-6" id="variation">
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
	var module = '';
	var list_cont_func = '';
	var dlt_cont_func = '';
</script>

<script>
    $('#daterange').daterangepicker();
    
    
    $(document).ready(function() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
        
        $('#supplier').addClass('required');
    });
 
    function get_variations(id){
        $('#var').hide('slow');
        console.log(id);
        ajax_load(base_url+'index.php/admin/supplierSaleReport/var_by_pro/'+id,'variation','other');
        $('#var').show('slow');
    }
    
    function other(){
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    }
    
    function selectSupplierProduct(){
        let supplierId =  $("#supplier").find(':selected').val();
        $('#prodd').hide('slow');
         $('#var').hide('slow');
        ajax_load(base_url+'index.php/admin/supplierSaleReport/supplier_product/'+supplierId,'product','other');
        $('#prodd').show('slow');
    }
    
</script>    
  
    
    


