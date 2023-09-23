<?php
	foreach($admin_data as $row){
?>
    <div class="tab-pane fade active in" id="edit">
        <?php
			echo form_open(base_url() . 'index.php/admin/admins/update/' . $row['admin_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'admin_edit'
			));
		?>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-1">
                    	<?php echo translate('name'); ?>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" name="name" value="<?php echo $row['name']; ?>" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('name'); ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2">
                    	<?php echo translate('email'); ?>
                    </label>
                    <div class="col-sm-6">
                        <?php echo $row['email']; ?>
                		<div class="label label-danger" style="display:none;" id='email_note'></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="change_password"><?php echo translate('password');?></label>
                    <div class="col-sm-4">
                        <input type="text" name="password" disabled id="change_password" value="" placeholder="" class="form-control">
                    </div>
                    <input type="checkbox" name="password_check" id="password_check" value="yes" ><label for="password_check">check here to change</label>
                </div>
              
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-4">
                    	<?php echo translate('phone'); ?>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" name="phone" value="<?php echo $row['phone']; ?>" id="demo-hor-4" class="form-control" placeholder="<?php echo translate('phone'); ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-5">
                    	<?php echo translate('address'); ?>
                    </label>
                    <div class="col-sm-6">
                        <textarea name="address" class="form-control" id="demo-hor-5" placeholder="<?php echo translate('address'); ?>"><?php echo $row['address']; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" >
                    	<?php echo translate('role'); ?>
                    </label>
                    <div class="col-sm-6">
                        <?php
                           echo $this->crud_model->select_html('role', 'role', 'name', 'edit', 'demo-chosen-select required', $row['role'],'','','getDeliveryType'); 
						?>
                    </div>
                </div>
                
                <span id="showDeliveryType" style="display: none;">
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-usertype">Delivery User Type</label>
                    <div class="col-sm-6" >
                        <input type="radio" style="display: inline;" name="user_type" id="user_type1" value="Internal" <?php echo ($row['user_type'] == 'Internal') ? 'checked':''; ?> ><label for="user_type1"> Internal</label>&nbsp;&nbsp; 
                        <input type="radio" style="display: inline;" name="user_type" id="user_type2" value="External" <?php echo ($row['user_type'] == 'External') ? 'checked':''; ?>><label for="user_type2"> External</label>&nbsp;&nbsp; 
                    </div>
                </div>

		<div class="form-group">
                        <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('city');?></label>
                        <div class="col-sm-6">
                          <?php echo $this->crud_model->select_html('city','city','city_name_en','edit','demo-chosen-select CITY',$row['city_id'],'','','getArea'); ?>
                        </div>
                </div>

                <div class="form-group" id="cityID">
                       <label class="col-sm-4 control-label" for="demo-hor-31"><?php echo translate('area');?></label>
                       <div class="col-sm-6" id="cityy">
                           <!--demo-chosen-select-->
                             <?php echo $this->crud_model->select_html( 'area', 'area', 'area_name_en', 'edit', 'demo-cs-multiselect', $row['area_ids'], 'city_id', $row['city_id'], 'other' ); ?>
                       </div>
                </div>
                </span> 
                
                <span id="showSupplier" style="display: none;">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="demo-hor-suppliers">Select Supplier</label>
                        <div class="col-sm-6">
                            <?php echo $this->crud_model->select_html( 'supplier', 'supplier', 'supplier_name|mobile_number|company_name', 'edit', 'demo-chosen-select SUPPLIER', $row['supplier_id'], '', '', 'getStores' ); ?>
                        </div>
                    </div>

                    <div class="form-group" id="suppStoreID">
                       <label class="col-sm-4 control-label" for="demo-hor-311">Select Store</label>
                       <div class="col-sm-6" id="suppStore">
                            <?php  echo $this->crud_model->select_html('supplier_store', 'supplier_store', 'store_name|store_number|store_address', 'edit', 'demo-chosen-select SUPPLIER', $row['supplier_store_id'], 'supplier_id', $row['supplier_id'], 'other'); ?>
                       </div>
                    </div>
                </span> 
                
                
            </div>
    	</form>
    </div>
<?php
	}
?>
<script>
	$(document).ready(function() {
		$("form").submit(function(e){
			return false;
		});
		$('.demo-chosen-select').chosen();
		$('.demo-cs-multiselect').chosen({width:'100%'});
                //$('#change_password').prop('required',false);
                $('#change_password').removeClass('required');
                <?php if( isset($admin_data[0]['role']) ) {?>
                    getDeliveryType(<?php echo $admin_data[0]['role']?>);
                <?php } ?>  
	});
	
        function getArea(id){
            $('#cityID').hide('slow');
            ajax_load(base_url+'index.php/admin/admins/cityarea/'+id,'cityy','other');
            $('#cityID').show('slow');
        }
	
        function getStores(id){
            $('#suppStoreID').hide('slow');
             ajax_load(base_url+'index.php/admin/admins/suppStores/'+id,'suppStore','other');
            $('#suppStoreID').show('slow');
        }
        
        
        function other(){
            $('.demo-chosen-select').chosen();
            $('.demo-cs-multiselect').chosen({width:'100%'});
        }
	
        //added by sagar : START
        $('#password_check').change(function() {
            if (this.checked) {
                $('#change_password').prop("disabled",false);
                $('#change_password').addClass('required');
                $('#change_password').prop('placeholder','Enter password');
            } else {
                $('#change_password').prop("disabled",true);
                $('#change_password').removeClass('required');
                $('#change_password').prop('placeholder','');
            }
        });
        //added by sagar : END
   
        function getDeliveryType(role_id){
            if(role_id == '4'){
                $('#showDeliveryType').show();
                $('#showSupplier').hide();
                var currentType = $("input[name='user_type']:checked").val();
                if(currentType == undefined){
                    $('#user_type1').attr('checked','checked');
                }
                $('.CITY').addClass('required');
                 $('.SUPPLIER').removeClass('required');
            }else if(role_id == '9'){
                $('#showSupplier').show();
                $('#showDeliveryType').hide();
                $('.CITY').removeClass('required');
                $('.SUPPLIER').addClass('required');
            }else{
                $('#showDeliveryType').hide(); $('#showSupplier').hide();
                $('.CITY').removeClass('required');
                $('.SUPPLIER').removeClass('required');
            }
        }
	
	
</script>