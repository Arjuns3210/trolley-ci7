    <div class="panel-body" id="demo_s">
        <table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >
            <thead>
                <tr>
                    <th><?php echo translate('no'); ?></th>
                    <th><?php echo translate('id'); ?></th>
                    <th><?php echo translate('name'); ?></th>
                    <th><?php echo translate('email'); ?></th>
                    <th><?php echo translate('role'); ?></th>
                    <th><?php echo translate('status'); ?></th>
                    <?php if( $this->crud_model->admin_permission('admin_edit') || $this->crud_model->admin_permission('admin_delete')){ ?>
                    <th class="text-right"><?php echo translate('options'); ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody >
            <?php
				$i = 0;
                foreach($all_admins as $row){
                
                    $i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['admin_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('role',$row['role']); ?></td>
                    
                    <?php if(($_SESSION['role_id'] == '1') && ($row['role'] == '4')){ ?>
                    <td>
                        <input id="slide_<?php echo $row['admin_id']; ?>" class="slide" type="checkbox" data-id="<?php echo $row['admin_id']; ?>" <?php if($row['status']=='Active'){ echo 'checked'; } ?> />
                    </td>
                    <?php }else{ ?>
                    <td><?php echo '-' ?></td>
                    <?php }?>
                    
                    <?php if($this->crud_model->admin_permission('admin_edit')  || $this->crud_model->admin_permission('admin_delete')){ ?>
                    <td class="text-right">
                        <?php if($this->crud_model->admin_permission('admin_edit')){ ?>
                        <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                            onclick="ajax_modal('edit','<?php echo translate('edit_admin'); ?>','<?php echo translate('successfully_edited!'); ?>','admin_edit','<?php echo $row['admin_id']; ?>')" 
                                data-original-title="Edit" data-container="body">
                                    <?php echo translate('edit');?>
                        </a>
                        <?php } ?>
                        <?php if( $this->crud_model->admin_permission('admin_delete') && $row['admin_id'] != $_SESSION['admin_id']){ ?>
                        <a onclick="delete_confirm('<?php echo $row['admin_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" 
                        	class="btn btn-danger btn-xs btn-labeled fa fa-trash" 
                            	data-toggle="tooltip"data-original-title="Delete" data-container="body">
                                	<?php echo translate('delete');?>
                        </a>
                        <?php } ?>
                    </td>
                    <?php } ?>
                </tr>
            <?php
                }
            ?>
            </tbody>
        </table>
    </div>
           
    <div id='export-div'>
        <h1 style="display:none;"><?php echo translate('staffs');?></h1>
        <table id="export-table" data-name='staffs' data-orientation='l' style="display:none;">
                <thead>
                    <tr>
                        <th><?php echo translate('no');?></th>
                        <th><?php echo translate('name');?></th>
                        <th><?php echo translate('email');?></th>
                        <th><?php echo translate('phone');?></th>
                        <th><?php echo translate('sddress');?></th>
                        <th><?php echo translate('role');?></th>
                    </tr>
                </thead>
                    
                <tbody >
                <?php
                    $i = 0;
                    foreach($all_admins as $row){
                        $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('role',$row['role']); ?></td>
                </tr>
                <?php
                    }
                ?>
                </tbody>
        </table>
    </div>
           
<script>
var base_url = '<?php echo base_url(); ?>'
var user_type = 'admin';
var module = 'admins';
function set_switchery(){
	$(".slide").each(function(){
		new Switchery(document.getElementById('slide_'+$(this).data('id')), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
		var changeCheckbox = document.querySelector('#slide_'+$(this).data('id'));
		changeCheckbox.onchange = function() {
		  ajax_load(base_url+'index.php/'+user_type+'/'+module+'/admin_publish/'+$(this).data('id')+'/'+changeCheckbox.checked,'','');
		  if(changeCheckbox.checked == true){
			$.activeitNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : 'Acivated Successfully',
				container : 'floating',
				timer : 3000
			});
			sound('published');
		  } else {
			$.activeitNoty({
				type: 'danger',
				icon : 'fa fa-check',
				message : 'Admin Inactivated Successfully',
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