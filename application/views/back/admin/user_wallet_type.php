<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/user/update_user_wallet_type/' . $user_id, array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'wallet_type',
				'enctype' => 'multipart/form-data'
		));
	?>
            <div class="panel-body">

                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                 <div class="form-group">
                    <label class="col-sm-4 control-label"
                           for="demo-hor-115"><?php echo translate( 'wallet_type' ); ?></label>
                    <div class="col-sm-6">
                        <select class="demo-chosen-select" name="wallet_type" id="wallet_type"  >
                                    <?php 
                                    $from = array('vip'=>'VIP','normal'=>'Normal' );
                                    foreach($from as $key => $val){
                                        $sel = "";
                                        if($key == $user_wallet_type){
                                            $sel = 'Selected';
                                        }
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php echo $sel; ?> ><?php echo $val; ?></option>
                                    <?php } ?>
                        </select>
                    </div>
                </div>

            </div>
	</form>
</div>
<script>
	$(document).ready(function() {
            $('.demo-chosen-select').chosen();
            
            $("form").submit(function(e){
                event.preventDefault();
            });
                    
        });
</script>