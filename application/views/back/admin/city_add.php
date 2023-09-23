<div>
	<?php
        echo form_open(base_url() . 'index.php/admin/city/do_add/', array(
            'class' => 'form-horizontal',
            'method' => 'post',
            'id' => 'city_add',
            'enctype' => 'multipart/form-data'
        ));
    ?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('city_name_in_english');?></label>
                <div class="col-sm-6">
                    <input type="text" name="city_name_en" id="demo-hor-1" 
                        placeholder="<?php echo translate('city_name_in_english'); ?>" class="form-control required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('city_name_in_arabic');?></label>
                <div class="col-sm-6">
                    <input type="text" name="city_name_ar" id="demo-hor-2" 
                        placeholder="<?php echo translate('city_name_in_arabic'); ?>" class="form-control required">
                </div>
            </div>
          
          

          
        </div>
	</form>
</div>
<script src="<?php echo base_url(); ?>template/back/js/custom/brand_form.js"></script>

