<link href="<?php echo base_url(); ?>template/back/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<div>
	<?php
        echo form_open(base_url() . 'index.php/admin/coupon/do_add/', array(
            'class' => 'form-horizontal',
            'method' => 'post',
            'id' => 'coupon_add',
            'enctype' => 'multipart/form-data'
        ));
    ?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('coupon_title');?></label>
                <div class="col-sm-6">
                    <input type="text" name="title" id="demo-hor-1" 
                        placeholder="<?php echo translate('title'); ?>" class="form-control required">
                </div>
            </div>
            <div class="form-group">
                     <label class="col-sm-4 control-label" for="start_from"><?php echo translate('Start_from'); ?></label>
                     <div class="col-sm-6" >
                         <input type="text" name="start_from" id='start_from' value="" class="form-control datepicker ">
                     </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('valid_till');?></label>
                <div class="col-sm-6">
                    <input type="text" name="till" id="demo-hor-1" class="form-control datepicker">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('coupon_code');?></label>
                <div class="col-sm-6">
                    <input type="text" name="code" id="demo-hor-1" 
                        placeholder="<?php echo translate('code'); ?>" class="form-control required">
                </div>
            </div>
            <?php /*
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('discount_type');?></label>
                <div class="col-sm-6">
                    <?php
                        $array = array('percent','amount');
                        echo $this->crud_model->select_html($array,'discount_type','','add','demo-chosen-select required'); 
                    ?>
                </div>
            </div>
            */ ?>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('discount_value');?></label>
                <div class="col-sm-6">
                    <input type="number" name="discount_value" id="demo-hor-1" 
                        placeholder="<?php echo translate('discount_value'); ?>" class="form-control required">
                </div>
                <span class="btn">%</span>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('status');?></label>
                <div class="col-sm-6">
                    <?php
                        $array1 = array('Active','In-active');
                        echo $this->crud_model->select_html($array1,'status','','add','demo-chosen-select required'); 
                    ?>
                </div>
            </div>
        </div>
	</form>
</div>
<script src="<?php echo base_url(); ?>template/back/js/custom/brand_form.js"></script>
<script>
$('.datepicker').datetimepicker({format: 'yyyy-mm-dd hh:ii'});
</script>
