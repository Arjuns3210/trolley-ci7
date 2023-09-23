<?php				
	foreach($city_data as $row){
?>
    <div>
        <?php
			echo form_open(base_url() . 'index.php/admin/city/update/' . $row['city_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'city_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
            <div class="panel-body">
                
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('city_name_in_english');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="city_name_en" id="demo-hor-1" value="<?php echo $row['city_name_en']; ?>" 
                            placeholder="<?php echo translate('city_name_in_english'); ?>" class="form-control required">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('city_name_in_arabic');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="city_name_ar" id="demo-hor-2" value="<?php echo $row['city_name_ar']; ?>" 
                            placeholder="<?php echo translate('city_name_in_arabic'); ?>" class="form-control required">
                    </div>
                </div>

            </div>
        </form>
    </div>

<?php
	}
?>

<script src="<?php echo base_url(); ?>template/back/js/custom/brand_form.js"></script>



