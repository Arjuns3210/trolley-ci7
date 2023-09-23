<?php
    foreach($user_data as $row){
        
?>
<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/user/add_wallet_balance/' . $row['user_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'wallet_balance_add',
				'enctype' => 'multipart/form-data'
		));
	?>
            <div class="panel-body">

                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('wallet_no');?></label>
                    <div class="col-sm-6">
                        <input type="text" disabled class="form-control" value="<?php echo $row['wallet_no']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('current_wallet_balance');?></label>
                    <div class="col-sm-6">
                        <input type="number" disabled value="<?php if(is_array($wallet_amount)){ echo ($wallet_amount[0]['add_amount'] - $wallet_amount[0]['destroy_amount']); }else{ echo "0"; } ?>" class="form-control totals">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('add_balance');?></label>
                    <div class="col-sm-6">
                        <input type="number" name="wallet_balance" min="0" id="wallet_balance" class="form-control totals required">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-5"><?php echo translate('remark');?></label>
                    <div class="col-sm-6">
                        <textarea name="remark" class="form-control" rows="3"></textarea>
                    </div>
                </div>

            </div>
	</form>
</div>
    <?php } ?>
<script>
	$(document).ready(function() {
            $("form").submit(function(e){
                            event.preventDefault();
                    });
        });
</script>