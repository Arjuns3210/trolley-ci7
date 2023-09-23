 
	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/supplier/store_doadd/' .$supplier_id, array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'store_add',
				'enctype' => 'multipart/form-data'
			));
		?>
			<div class="panel-body">
				<div class="form-group">
                                    <label class="col-sm-4 control-label" for="store_name">
                                    <?php echo translate('store_name');?>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" name="store_name"  value="" id="store_name" 
                                        class="form-control required" placeholder="<?php echo translate('store_name');?>" >
                                    </div>
				</div>
                            
				<div class="form-group">
                                    <label class="col-sm-4 control-label" for="store_number">
                                    <?php echo translate('store_number');?>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" name="store_number"   value="" id="store_number" 
                                        class="form-control required" placeholder="<?php echo translate('store_number');?>" >
                                    </div>
				</div>
                            
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="store_address"><?php echo translate('store_address');?></label>
                                    <div class="col-sm-6">
                                        <textarea name="store_address" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                               
                                <div class="form-group">
                                        <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('city');?></label>
                                        <div class="col-sm-6">
                                            <?php echo $this->crud_model->select_html('city','city','city_name_en','add','demo-chosen-select required','','','','getArea'); ?>
                                        </div>
                                </div>
                
                                <div class="form-group" id="cityID" style="display:none;">
                                       <label class="col-sm-4 control-label" for="demo-hor-31"><?php echo translate('area');?></label>
                                       <div class="col-sm-6" id="cityy">
			</div>
                                </div>
                               
                
			</div>
		</form>
	</div>


<script>
	$(document).ready(function() {
	    $("form").submit(function(e) {
	        return false;
	    });
            $('.demo-chosen-select').chosen();
            $('.demo-cs-multiselect').chosen({width:'100%'});
	});
	
        function getArea(id){
            $('#cityID').hide('slow');
            ajax_load(base_url+'index.php/admin/supplier/cityarea/'+id,'cityy','other');
            $('#cityID').show('slow');
        }
        
        function other(){
            $('.demo-chosen-select').chosen();
            $('.demo-cs-multiselect').chosen({width:'100%'});
        }
	
</script>