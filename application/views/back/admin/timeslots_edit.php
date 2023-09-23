<div>
    <?php
		echo form_open(base_url() . 'index.php/admin/timeslots/update/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'timeslots_edit',
			'enctype' => 'multipart/form-data'
		));
	?>
        <div class="panel-body">
            <input type="hidden" name="timeslots_id" value="<?php echo $timeslots_id; ?>">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-5"><?php echo translate('Day');?></label>
                <div class="col-sm-4">
                    <input type="text" disabled value="<?php echo ucfirst($this->crud_model->get_type_name_by_id('timeslots',$timeslots_id,'day')); ?>" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="day" value="<?php echo $this->crud_model->get_type_name_by_id('timeslots',$timeslots_id,'day') ?>">
                <label class="col-sm-4 control-label" for="demo-hor-5"><?php echo translate('Start Time');?></label>
                <div class="col-sm-4">
                    <input type="time" value="<?php echo $this->crud_model->get_type_name_by_id('timeslots',$timeslots_id,'start_time'); ?>" name="start_time" size="35" placeholder="start_time" class="form-control" required />

                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-6"><?php echo translate('End Time');?></label>
                <div class="col-sm-4">
                        <input type="time" value="<?php echo $this->crud_model->get_type_name_by_id('timeslots',$timeslots_id,'end_time'); ?>" name="end_time" size="35" placeholder="end_time" class="form-control" required />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-7"><?php echo translate('Order_limit');?></label>
                <div class="col-sm-4">
                    <input type="number" value="<?php echo $this->crud_model->get_type_name_by_id('timeslots',$timeslots_id,'order_limit'); ?>" name="order_limit" size="35" class="form-control required"  />
                </div>
            </div>
  
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
            $("form").submit(function(e){
                    event.preventDefault();
            });
    });
</script>
<div id="reserve"></div>

