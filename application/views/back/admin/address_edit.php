<div>
    <?php
    
		echo form_open(base_url() . 'index.php/admin/user/do_address_edit/'.$user_id.'/'.$address_id, array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'address_edit',
			'enctype' => 'multipart/form-data'
		));
                            
                            $address_result='';
                            $query = $this->db->query('Select * From user_address where user_id='.$this->db->escape($user_id) .' And address_id = '.$this->db->escape($address_id));
                            if($query !== FALSE && $query->num_rows() > 0){
                                $address_result = $query->result_array();
                            }
//                            echo '<pre>';
//print_r($address_result[0]['city_id']);
//exit;
                            
	?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="address_1"><?php echo translate('address_1');?></label>
                <div class="col-sm-6">
                    <input type="text" name="address_1" id="address_1" value="<?php if(isset($address_result[0]['address_1'])){ echo $address_result[0]['address_1']; } ?>" class="form-control required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="address_2"><?php echo translate('address_2');?></label>
                <div class="col-sm-6">
                    <input type="text" name="address_2" value="<?php if(isset($address_result[0]['address_2'])){ echo $address_result[0]['address_2']; } ?>" id="address_2" class="form-control">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('Country');?></label>
                <div class="col-sm-6">
                    <?php echo $this->crud_model->select_html('country','country','name','edit','demo-chosen-select required',$address_result[0]['country_id'],'status','Active','get_state'); ?>
                </div>
            </div>

            <div class="form-group" id="states" >
                <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('province');?></label>
                <div class="col-sm-6" id="state_here">
                    <?php echo $this->crud_model->select_html('state','state','name','edit','demo-chosen-select required',$address_result[0]['state_id'],'country_id',$address_result[0]['country_id'],'get_city'); ?>
                </div>
            </div>

            <div class="form-group" id="cities" >
                <label class="col-sm-4 control-label" for="demo-hor-3"><?php echo translate('city');?></label>
                <div class="col-sm-6" id="city_here">
                    <?php echo $this->crud_model->select_html('city','city','name','edit','demo-chosen-select required',$address_result[0]['city_id'],'state_id',$address_result[0]['state_id'],''); ?>
                </div>
            </div>

            

            <div class="form-group">
                <label class="col-sm-4 control-label" for="pincode"><?php echo translate('pincode');?></label>
                <div class="col-sm-6">
                    <input type="text" name="pincode" value="<?php if(isset($address_result[0]['pincode'])){ echo $address_result[0]['pincode']; } ?>" id="pincode" class="form-control">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="shipping_address"><?php echo translate('set_as_shipping_address');?></label>
                <div class="col-sm-6">
                    <input type="checkbox" <?php if(isset($address_result[0]['default_address']) && $address_result[0]['default_address'] == 'ok'){ echo 'checked'; } ?> name="shipping_address" id="shipping_address" value="yes" >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="delivery_instructions"><?php echo translate('delivery_instructions');?></label>
                <div class="col-sm-6">
                    <textarea type="text" rows="3" name="delivery_instructions" id="delivery_instructions" class="form-control"><?php if(isset($address_result[0]['delivery_instructions'])){ echo $address_result[0]['delivery_instructions']; } ?></textarea>
                </div>
            </div>
        </div>
	</form>
</div>
<script type="text/javascript">

    $(document).ready(function() {
       
        
        
        $('.demo-cs-multiselect').chosen({width:'100%'});
        $('.demo-chosen-select').chosen();
       
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

