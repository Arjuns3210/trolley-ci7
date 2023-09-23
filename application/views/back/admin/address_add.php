<div>
    <?php
    
		echo form_open(base_url() . 'index.php/admin/user/do_address/'.$user_id, array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'address_add',
			'enctype' => 'multipart/form-data'
		));
	?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="address_1"><?php echo translate('address_1');?></label>
                <div class="col-sm-6">
                    <input type="text" name="address_1" id="address_1" class="form-control required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="address_2"><?php echo translate('address_2');?></label>
                <div class="col-sm-6">
                    <input type="text" name="address_2" id="address_2" class="form-control">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('Country');?></label>
                <div class="col-sm-6">
                    <?php echo $this->crud_model->select_html('country','country','name','add','demo-chosen-select required','','status','Active','get_state'); ?>
                </div>
            </div>

            <div class="form-group" id="states" style="display:none;">
                <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('province');?></label>
                <div class="col-sm-6" id="state_here">
                </div>
            </div>

            <div class="form-group" id="cities" style="display:none;">
                <label class="col-sm-4 control-label" for="demo-hor-3"><?php echo translate('city');?></label>
                <div class="col-sm-6" id="city_here">
                </div>
            </div>

            

            <div class="form-group">
                <label class="col-sm-4 control-label" for="pincode"><?php echo translate('pincode');?></label>
                <div class="col-sm-6">
                    <input type="text" name="pincode" id="pincode" class="form-control">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="shipping_address"><?php echo translate('set_as_shipping_address');?></label>
                <div class="col-sm-6">
                    <input type="checkbox" name="shipping_address" id="shipping_address" value="yes" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="delivery_instructions"><?php echo translate('delivery_instructions');?></label>
                <div class="col-sm-6">
                    <textarea type="text" rows="3" name="delivery_instructions" id="delivery_instructions" class="form-control"></textarea>
                </div>
            </div>
        </div>
	</form>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    });

    function other(){
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
        $('#reserve').hide();
        $('#rate').val($('#reserve').html());
    }
    
    function get_state(id){
        $('#states').hide('slow');
        $('#cities').hide('slow');
        ajax_load(base_url+'index.php/admin/user/state/'+id,'state_here','other');
        $('#states').show('slow');
    }
    
    function get_city(id){
        $('#cities').hide('slow');
        ajax_load(base_url+'index.php/admin/user/city/'+id,'city_here','other');
        $('#cities').show('slow');
    }
    


	$(document).ready(function() {
		$("form").submit(function(e){
			event.preventDefault();
		});
	});
</script>
<div id="reserve"></div>

