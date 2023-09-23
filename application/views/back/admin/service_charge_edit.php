<?php				
	foreach($data as $row){
?>
    <div>
        <?php
			echo form_open(base_url() . 'index.php/admin/service_charge/update/' . $row['service_charge_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'service_charge_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
            <div class="panel-body">
                <?php if($row['service_charge_id'] <= 4  ) { ?> 
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="payment_mode"><?php echo translate('Payment_mode');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="payment_mode" readonly id="payment_mode" value="<?php echo $row['payment_mode']; ?>" 
                            placeholder="<?php echo translate('payment_mode'); ?>" class="form-control required">
                    </div>
                </div>
                
                <?php }else{ ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="group_name"><?php echo translate('group_name');?></label>
                            <div class="col-sm-6">
                                <input type="text" name="group_name" id="group_name" value="<?php echo $row['group_name']; ?>" 
                                    placeholder="<?php echo translate('group_name'); ?>" class="form-control required">
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="payment_mode">Payment Mode</label>
                            <div class="col-sm-6" >
                                <input type="radio"  style="display: inline;" name="payment_mode"  id="payment_mode_1" value="Online card"  <?php if(!empty($row['payment_mode']) && $row['payment_mode'] == 'Online card') echo 'checked'; ?>><label for="payment_mode_1"> Online Card</label>&nbsp;&nbsp; 
                                <input type="radio" style="display: inline;" name="payment_mode"  id="payment_mode_2" value="Online wallet" <?php if(!empty($row['payment_mode']) && $row['payment_mode'] == 'Online wallet') echo 'checked'; ?>><label for="payment_mode_2"> Online Wallet</label>&nbsp;&nbsp; 
                            </div>
                        </div>
                
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="card_no" id="card_label"><?php echo translate('card_no');?></label>
                            <div class="col-sm-6">
                                <input type="text" name="card_no" id="card_no"   value="<?php echo $row['card_no']; ?>"
                                    placeholder="" class="form-control required">
                            </div>
                        </div>
                <?php } ?>
                  
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
                var payment_mode = $("input[name='payment_mode']:checked").val();
                changeLabels(payment_mode);
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


