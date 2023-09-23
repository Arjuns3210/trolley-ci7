<div>
	<?php
		echo form_open(base_url() . 'index.php/admin/admins/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'admin_add'
		));
	?>
        <div class="panel-body">
        
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                    <?php echo translate('name'); ?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="name" id="demo-hor-1" class="form-control required" placeholder="<?php echo translate('name'); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-2">
                    <?php echo translate('email'); ?>
                </label>
                <div class="col-sm-6">
                    <input type="email" name="email" id="demo-hor-2" class="emails form-control required" placeholder="<?php echo translate('email'); ?>">
                	<div class="label label-danger" style="display:none;" id='email_note'></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-3">
                    <?php echo translate('password'); ?>
                </label>
                <div class="col-sm-6">
                    <input type="password" name="password" id="demo-hor-3" class="form-control required" placeholder="<?php echo translate('password'); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-4">
                    <?php echo translate('phone'); ?>
                </label>
                <div class="col-sm-6">
                    <input type="text" name="phone" id="demo-hor-4" class="phones form-control required" placeholder="<?php echo translate('phone'); ?>">
                    <div class="label label-danger" style="display:none;" id='phone_note'></div>
              
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-5">
                    <?php echo translate('address'); ?>
                </label>
                <div class="col-sm-6">
                    <textarea name="address" id="demo-hor-5" class="form-control " placeholder="<?php echo translate('address'); ?>"></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" >
                    <?php echo translate('role'); ?>
                </label>
                <div class="col-sm-6">
                    <?php echo $this->
                    crud_model->select_html('role','role','name','add','demo-chosen-select required','','','','getDeliveryType'); ?>
                </div>
                
            
            
            <span id="showSupplier" style="display: none;">
            <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-suppliers">Select Supplier</label>
                    <div class="col-sm-6">
                        <?php echo $this->crud_model->select_html( 'supplier', 'supplier', 'supplier_name|mobile_number|company_name', 'add', 'demo-chosen-select SUPPLIER', '', '', '', 'getStores' ); ?>
                    </div>
                </div>
                
                <div class="form-group" id="suppStoreID" style="display:none;">
                   <label class="col-sm-4 control-label" for="demo-hor-311">Select Store</label>
                   <div class="col-sm-6" id="suppStore">
                   </div>
                </div>
            </span> 
            
            <span id="showDeliveryType" style="display: none;">
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-usertype">Delivery User Type</label>
                    <div class="col-sm-6" >
                        <input type="radio" style="display: inline;" name="user_type" id="user_type1" value="Internal" checked ><label for="user_type1"> Internal</label>&nbsp;&nbsp; 
                        <input type="radio" style="display: inline;" name="user_type" id="user_type2" value="External" ><label for="user_type2"> External</label>&nbsp;&nbsp; 
            </div>
                </div>

		<div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('city');?></label>
                    <div class="col-sm-6">
                        <?php echo $this->crud_model->select_html('city','city','city_name_en','add','demo-chosen-select CITY','','','','getArea'); ?>
                    </div>
            </div>

            <div class="form-group" id="cityID" style="display:none;">
                   <label class="col-sm-4 control-label" for="demo-hor-31"><?php echo translate('area');?></label>
                   <div class="col-sm-6" id="cityy">
                   </div>
            </div>
            </span> 
            
            
        </div>
	</form>
</div>

<script>
	$(document).ready(function() {
		$("form").submit(function(e){
			return false;
		});
		$('.demo-chosen-select').chosen();
		$('.demo-cs-multiselect').chosen({width:'100%'});
//		$('body .modal-dialog').find('.btn-purple').addClass('disabled');
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
	
	
	$(".emails").blur(function(){
		var email = $(".emails").val();
		$.post("<?php echo base_url(); ?>index.php/admin/exists",
		{
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			email: email
		},
		function(data, status){
			if(data == 'yes'){
				$("#email_note").show();
				$("#email_note").html('*<?php echo 'email_already_registered'; ?>');
				$("body .modal-dialog .btn-purple").addClass("disabled");
			} else if(data == 'no'){
				$("#email_note").hide();
				$("#email_note").html('');
				$("body .modal-dialog .btn-purple").removeClass("disabled");
			}
		});
	});

	$(".phones").blur(function(){
		var email = $(".phones").val();
		$.post("<?php echo base_url(); ?>index.php/admin/phoneExists",
		{
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			phone: email
		},
		function(data, status){
			if(data == 'yes'){
				$("#phone_note").show();
				$("#phone_note").html('*<?php echo 'phone already exists'; ?>');
				$("body .modal-dialog .btn-purple").addClass("disabled");
			} else if(data == 'no'){
				$("#phone_note").hide();
				$("#phone_note").html('');
				$("body .modal-dialog .btn-purple").removeClass("disabled");
			}
		});
	});
        
        function getDeliveryType(role_id){
            if(role_id == '4'){
                $('#showDeliveryType').show();
                $('#showSupplier').hide();
                $('.CITY').addClass('required');
                $('.SUPPLIER').removeClass('required');
            }else if(role_id == '9'){
                $('#showSupplier').show();
                $('#showDeliveryType').hide();
                $('.CITY').removeClass('required');
                $('.SUPPLIER').addClass('required');
            }else{
                $('#showDeliveryType').hide();
                $('#showSupplier').hide();
                $('.CITY').removeClass('required');
                $('.SUPPLIER').removeClass('required');
            }
        }
</script>