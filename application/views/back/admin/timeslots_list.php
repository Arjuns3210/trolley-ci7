<div class="row">
    <div class="col-md-12">
        <div class=" btm_border">
            <h4 class="text-2x text-center"><?php echo translate( 'TimeSlots' ); ?></h4>
        </div>
        <div class="col-md-12">
            <div class="panel-body" id="demo_s">
             <?php $day = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');  ?>
                <?php foreach($day as $val){ ?>
                <div class="col-md-6">
                    <h4 class="text-thin text-center"><?php echo ucfirst($val); ?></h4>
                <table  class="table table-striped" >
                    <thead>
                        <tr>
                            <th><?php echo translate('Timeslots');?></th>
                            <th><?php echo translate('status');?></th>
                            <th><?php echo translate('order limit');?></th>
                            <?php if($this->crud_model->admin_permission('timeslots_edit')){ ?>
                            <th class="text-center"><?php echo translate('option');?></th>
                            <?php } ?>
                        </tr>
                    </thead>				
                    <tbody>
                    <?php $day_array  = $this->db->order_by('start_time', 'ASC')->get_where('timeslots',array('day'=>$val))->result_array(); 
                        if(is_array($day_array) && !empty($day_array[0])){ 
                                foreach($day_array as $key => $val ){ 
                    ?>
                    <tr>
                        <td><?php echo date('h:i A',strtotime($val['start_time'])) . ' - '. date('h:i A',strtotime($val['end_time'])) ?></td>
                        <td>
                            <input id="slide_<?php echo $val['timeslots_id']; ?>" class="slide" type="checkbox" data-id="<?php echo $val['timeslots_id']; ?>" <?php if($val['status']=='ok'){ echo 'checked'; } ?> />
                        </td>
                        <td><?php echo $val['order_limit'] ?></td>
                         <?php if($this->crud_model->admin_permission('timeslots_edit')){ ?>
                        <td class="text-center">
                            <a class="btn btn-dark btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                                onclick="ajax_modal('edit','<?php echo translate('edit Timeslots'); ?>','<?php echo translate('Timeslots updated successfully!'); ?>', 'timeslots_edit', '<?php echo $val['timeslots_id'] ?>' );" data-original-title="View" data-container="body">
                                    <?php echo translate('Edit');?>
                            </a>
                        </td>
                         <?php } ?>
                    </tr>
                    <?php
                        } }
                    ?>
                    </tbody>
                </table>
                 </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>



<style>
    .label {
        font-size: 100% !important;
    }
</style>
<script>
var base_url = '<?php echo base_url(); ?>'
var user_type = 'admin';
var module = 'timeslots';
function set_switchery(){
	$(".slide").each(function(){
		new Switchery(document.getElementById('slide_'+$(this).data('id')), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
		var changeCheckbox = document.querySelector('#slide_'+$(this).data('id'));
		changeCheckbox.onchange = function() {
		  ajax_load(base_url+'index.php/'+user_type+'/'+module+'/timeslots_publish/'+$(this).data('id')+'/'+changeCheckbox.checked,'','');
		  if(changeCheckbox.checked == true){
			$.activeitNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : '<?php echo translate("timeslot_published!"); ?>',
				container : 'floating',
				timer : 3000
			});
			sound('published');
		  } else {
			$.activeitNoty({
				type: 'danger',
				icon : 'fa fa-check',
				message : '<?php echo translate("timeslot_unpublished!"); ?>',
				container : 'floating',
				timer : 3000
			});
			sound('unpublished');
		  }
		  //alert(changeCheckbox.checked);
		};
	});
}
	
</script>

   

