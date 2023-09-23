<?php
    foreach($sales_data as $row){
        $assign_delivery_data = json_decode($row['assign_delivery_data'],true);
?>
<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/sales/update_assign_delivery/' . $sale_id, array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'assign_delivery_data',
				'enctype' => 'multipart/form-data'
		));
	?>
            <div class="panel-body">
                 <div class="form-group">
                    <label class="col-sm-3 control-label"
                           for="delivery">Select Delivery man</label>
                    <div class="col-sm-7">
                        <?php
                            if ( is_array( $delivery_team ) ) { ?>
                        <select class="demo-chosen-select required" name="assigned_delivery" id="delivery">

                                    <option value="">Choose delivery man </option>
                                        <?php   foreach ( $delivery_team as $k => $val ) {
                                                            $sel = '';
                                                            if($val['admin_id'] == $assign_delivery_data['admin_id'] ){
                                                               $sel = "Selected"; 
                                                            }
                                                            ?>
                                    <option value="<?php echo $val['admin_id'] .'|'. $val['name'].'|'.$val['phone']; ?>" <?php echo $sel; ?>><?php echo $val['name'].' - '.$val['phone']; ?></option>
                                        <?php } ?>
                        </select>
                        <?php } ?>
                    </div>
            </div>
            </div>
	</form>
    </div>
    <?php } ?>
<script>
	$(document).ready(function() {
            $('.demo-chosen-select').chosen();
            $('.demo-cs-multiselect').chosen({width:'100%'});
    	
            $("form").submit(function(e){
                            event.preventDefault();
                    });
        });
</script>