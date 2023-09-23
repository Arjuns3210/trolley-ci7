<div>
	<?php
        echo form_open(base_url() . 'index.php/admin/service_charge/do_add/', array(
            'class' => 'form-horizontal',
            'method' => 'post',
            'id' => 'service_charge_add',
            'enctype' => 'multipart/form-data'
        ));
    ?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="group_name"><?php echo translate('group_name');?></label>
                <div class="col-sm-6">
                    <input type="text" name="group_name" id="group_name"
                        placeholder="<?php echo translate('group_name'); ?>" class="form-control required">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="payment_mode">Payment Mode</label>
                <div class="col-sm-6" >
                    <input type="radio"  style="display: inline;" name="payment_mode" id="payment_mode_1" value="Online card" ><label for="payment_mode_1" > Online Card</label>&nbsp;&nbsp; 
                    <input type="radio" style="display: inline;" name="payment_mode" id="payment_mode_2" value="Online wallet"><label for="payment_mode_2"> Online Wallet</label>&nbsp;&nbsp; 
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="card_no" id="card_label"><?php echo translate('card_no');?></label>
                <div class="col-sm-6">
                    <input type="text" name="card_no" id="card_no" 
                        placeholder="" class="form-control required">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label"><?php echo translate('service_fees (%)');?></label>
                <div class="col-sm-6">
                    <input type="number" name="service_fees"  value="<?php echo $row['service_fees']; ?>" 
                        placeholder="<?php echo translate('service_fees'); ?>" class="form-control required">
                </div>
            </div>
          
          

          
        </div>
	</form>
</div>
<script src="<?php echo base_url(); ?>template/back/js/custom/brand_form.js"></script>

<script>
    $(document).ready(function() {
		$("form").submit(function(e){
			return false;
		});
		$('.demo-chosen-select').chosen();
		$('.demo-cs-multiselect').chosen({width:'100%'});
    });
	
    //Added by sagar : 18-09-2020 -START
    $("input[type='radio']").click(function(){
            var payment_mode = $("input[name='payment_mode']:checked").val();
            changeLabels(payment_mode);
    });
    function changeLabels(payment_mode){
            if(payment_mode == 'Online card'){
                $('#card_no').prop('placeholder','eg.10001,10002');
                $('#card_label').text('Card no');
            }else{
                $('#card_no').prop('placeholder','eg.90,91');
                $('#card_label').text('Wallet no');
            }
    }
    //Added by sagar : 18-09-2020 -END
</script>    