<?php				
	foreach($area_data as $row){
?>
    <div>
        <?php
			echo form_open(base_url() . 'index.php/admin/area/update/' . $row['area_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'area_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
            <div class="panel-body">
                
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('area_name_in_english');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="area_name_en" id="demo-hor-1" value="<?php echo $row['area_name_en']; ?>" 
                            placeholder="<?php echo translate('area_name_in_english'); ?>" class="form-control required">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('area_name_in_arabic');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="area_name_ar" id="demo-hor-2" value="<?php echo $row['area_name_ar']; ?>" 
                            placeholder="<?php echo translate('area_name_in_arabic'); ?>" class="form-control required">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-4 control-label" >
                    	<?php echo translate('city'); ?>
                    </label>
                    <div class="col-sm-6">
                        <?php
                           echo $this->crud_model->select_html('city', 'city_id', 'city_name_en', 'edit', 'demo-chosen-select required', $row['city_id']); 
						?>
                    </div>
                </div>
                  
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo translate('delivery_charge (In $)');?></label>
                    <div class="col-sm-6">
                        <input type="number" name="delivery_charge"  value="<?php echo $row['delivery_charge']; ?>" 
                            placeholder="<?php echo translate('delivery_charge'); ?>" class="form-control required">
                    </div>
                </div>

            </div>
        </form>
    </div>

<?php
	}
?>

<script src="<?php echo base_url(); ?>template/back/js/custom/brand_form.js"></script>
<script>
    $(document).ready(function() {
		$("form").submit(function(e){
			return false;
		});
		$('.demo-chosen-select').chosen();
		$('.demo-cs-multiselect').chosen({width:'100%'});
//		$('body .modal-dialog').find('.btn-purple').addClass('disabled');
    });
	
</script>    


