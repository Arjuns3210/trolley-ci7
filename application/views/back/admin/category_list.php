	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('name');?></th>
					<th><?php echo translate('category_id');?></th>
					<th><?php echo translate('code');?></th>
                                        <th><?php echo translate('banner');?></th>
					<th><?php echo translate('publish');?></th>
					<th><?php echo translate('is_featured');?></th>
                                        <?php if($this->crud_model->admin_permission('category_edit') ) { ?>
					<th class="text-right"><?php echo translate('options');?></th>
                                         <?php } ?>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i = 0;
            	foreach($all_categories as $row){
            		$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
                <td><?php echo $row['category_name']; ?></td>
                <td><?php echo $row['category_id']; ?></td>
                <td><?php echo $row['category_code']; ?></td>
              
				<td>
                    <?php
						if(file_exists('uploads/category_image/'.$row['banner'])){
					?>
					<img class="img-md" src="<?php echo base_url(); ?>uploads/category_image/<?php echo $row['banner']; ?>" height="100px" />  
					<?php
						} else {
					?>
					<img class="img-md" src="<?php echo base_url(); ?>uploads/category_image/default.jpg" height="100px" />
					<?php
						}
					?> 
               	</td>
				   <td>
					   <input id="slide_<?php echo $row['category_id']; ?>" class="slide" type="checkbox" data-id="<?php echo $row['category_id']; ?>" <?php if($row['digital']=='ok'){ echo 'checked'; } ?> />
					</td>
					<td>
					<input id="is_featured_<?php echo $row['category_id']; ?>"  class="is_featured" type="checkbox" data-id="<?php echo $row['category_id']; ?>" <?php if($row['is_featured']=='yes'){ echo 'checked'; } ?> />
					</td>
              
               	
                        <?php if($this->crud_model->admin_permission('category_edit') ) { ?>
				<td class="text-right">
					<a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                    	onclick="ajax_modal('edit','<?php echo translate('edit_category_(_physical_product_)'); ?>','<?php echo translate('successfully_edited!'); ?>','category_edit','<?php echo $row['category_id']; ?>')" 
                        	data-original-title="Edit" data-container="body">
                            	<?php echo translate('edit');?>
                                        </a>
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
		<h1 style="display:none;"><?php echo translate('category'); ?></h1>
		<table id="export-table" data-name='category' data-orientation='p' style="display:none;">
				<thead>
					<tr>
						<th><?php echo translate('no');?></th>
						<th><?php echo translate('name');?></th>
						<th><?php echo translate('category_id');?></th>
                                                <th><?php echo translate('code');?></th>
					</tr>
				</thead>
					
				<tbody >
				<?php
					$i = 0;
	            	foreach($all_categories as $row){
	            		$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row['category_name']; ?></td>
					<td><?php echo $row['category_id']; ?></td>
                                        <td><?php echo $row['category_code']; ?></td>
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>
<?php //added by sagar : FOR CATEGORY PUBLISH STATUS START 14-10 ?>
<script>
var base_url = '<?php echo base_url(); ?>'
var user_type = 'admin';
var module = 'category';
function set_switchery(){
	$(".slide").each(function(){
		new Switchery(document.getElementById('slide_'+$(this).data('id')), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
		var changeCheckbox = document.querySelector('#slide_'+$(this).data('id'));
		changeCheckbox.onchange = function() {
		  ajax_load(base_url+'index.php/'+user_type+'/'+module+'/category_publish/'+$(this).data('id')+'/'+changeCheckbox.checked,'','');
		  if(changeCheckbox.checked == true){
			$.activeitNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : cat_pb,
				container : 'floating',
				timer : 3000
			});
			sound('published');
		  } else {
			$.activeitNoty({
				type: 'danger',
				icon : 'fa fa-check',
				message : cat_upb,
				container : 'floating',
				timer : 3000
			});
			sound('unpublished');
		  }
		  //alert(changeCheckbox.checked);
		};     
	});
	$(".is_featured").each(function(){
		new Switchery(document.getElementById('is_featured_'+$(this).data('id')), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
		var changeCheckbox = document.querySelector('#is_featured_'+$(this).data('id'));
		changeCheckbox.onchange = function() {
		  ajax_load(base_url+'index.php/'+user_type+'/'+module+'/is_featured/'+$(this).data('id')+'/'+changeCheckbox.checked,'','');
		  if(changeCheckbox.checked == true){
			$.activeitNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : cat_is_fe,
				container : 'floating',
				timer : 3000
			});
			sound('featured');
		  } else {
			$.activeitNoty({
				type: 'danger',
				icon : 'fa fa-check',
				message : cat_is_not_fe,
				container : 'floating',
				timer : 3000
			});
			sound('not featured');
		  }
		  //alert(changeCheckbox.checked);
		};     
	});
}
	
</script>
<?php //added by sagar : FOR CATEGORY PUBLISH STATUS END 14-10 ?>
