<?php 
    $row = $message_data[0]; 
    $opened_status = $row['view_status'] == 'Opened' ? 'selected' : '';
    $closed_status = $row['view_status'] == 'Closed' ? 'selected' : '';
?>
    <div>
    <?php
			echo form_open(base_url() . 'index.php/admin/enquiries/status_form/' . $row['ticket_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'view_status',
			));
		?>
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('view_status');?></label>
                    <div class="col-sm-6">
                        <select class="form-control" name="view_status">
                            <option value="Opened" <?php echo $opened_status; ?>>Opened</option>
                            <option value="Closed" <?php echo $closed_status; ?>>Closed</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
