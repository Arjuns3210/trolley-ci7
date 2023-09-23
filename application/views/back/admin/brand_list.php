	
	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,4" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('logo');?></th>
					<th><?php echo translate('name');?></th>
					<th  class="text-center"><?php echo translate('display_on_homescreen');?></th>
                                         <?php if($this->crud_model->admin_permission('brand_edit')){ ?>
					<th class="text-right"><?php echo translate('options');?></th>
                                         <?php } ?>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i=0;
            	foreach($all_brands as $row){
            		$i++;
			?>
                <tr>
                    <td><?php echo $i; ?></td>
				
                    <td >
                        <?php
							if(file_exists('uploads/brand_image/'.$row['logo']) && !empty($row['logo'])){
						?>
						<img class="img-md" src="<?php echo base_url(); ?>uploads/brand_image/<?php echo $row['logo']; ?>" />  
						<?php
							} else {
						?>
						<img class="img-md" src="<?php echo base_url(); ?>uploads/brand_image/default.jpg" />
						<?php
							}
						?> 
                    </td>
                    
                    <td><?php echo $row['name']; ?></td>
                     <td>
                        <input id="slide_<?php echo $row['brand_id']; ?>" class="slide" type="checkbox" data-id="<?php echo $row['brand_id']; ?>" <?php if($row['status']=='ok'){ echo 'checked'; } ?> />
                    </td>
                    <?php if($this->crud_model->admin_permission('brand_edit')){ ?>
                    <td class="text-right">
                        <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                            onclick="ajax_modal('edit','<?php echo translate('edit_brand_(_physical_product_)'); ?>','<?php echo translate('successfully_edited!'); ?>','brand_edit','<?php echo $row['brand_id']; ?>')" 
                                data-original-title="Edit" 
                                    data-container="body"><?php echo translate('edit');?>
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
		<h1 style="display:none;"><?php echo translate('brand'); ?></h1>
		<table id="export-table" data-name='brand' data-orientation='p' style="display:none;">
				<thead>
					<tr>
						<th><?php echo translate('no');?></th>
						<th><?php echo translate('name');?></th>
						<th><?php echo translate('category');?></th>
					</tr>
				</thead>
					
				<tbody >
				<?php
					$i = 0;
	            	foreach($all_brands as $row){
	            		$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row['name']; ?></td>
					<td><?php echo $this->crud_model->get_type_name_by_id('category',$row['category'],'category_name'); ?></td>
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>

<style>
	.highlight{
		background-color: #E7F4FA;
	}
</style>

<script>
var base_url = '<?php echo base_url(); ?>'
var user_type = 'admin';
var module = 'brand';
function set_switchery(){
	$(".slide").each(function(){
		new Switchery(document.getElementById('slide_'+$(this).data('id')), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
		var changeCheckbox = document.querySelector('#slide_'+$(this).data('id'));
		changeCheckbox.onchange = function() {
		  ajax_load(base_url+'index.php/'+user_type+'/'+module+'/brand_publish/'+$(this).data('id')+'/'+changeCheckbox.checked,'','');
		  if(changeCheckbox.checked == true){
			$.activeitNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : brand_pb,
				container : 'floating',
				timer : 3000
			});
			sound('published');
		  } else {
			$.activeitNoty({
				type: 'danger',
				icon : 'fa fa-check',
				message : brand_upb,
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





           

           