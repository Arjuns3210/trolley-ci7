	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="false" data-ignorecol="0,4" data-show-toggle="true" data-show-columns="false" data-search="false" >
			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('image');?></th>
                                <?php /* <th><?php echo translate('button');?></th> */ ?>
                                        <th><?php echo translate('banner_language');?></th>
                                        <th><?php echo translate('status');?></th>
                                        <?php if($this->crud_model->admin_permission('slides_edit') ) { ?>
					<th class="text-right"><?php echo translate('options');?></th>
                                        <?php } ?>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i=0;
            	foreach($all_slidess as $row){
            		$i++;
			?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td>
                <?php
                    if(!empty($row['button_link'])){
                ?>
                <img class="img-md" src="<?php echo base_url(); ?>uploads/slides_image/<?php echo $row['button_link']?>" height="100px" />  
                <?php
                    } else {
                ?>
                <img class="img-md" src="<?php echo base_url(); ?>uploads/slides_image/default.jpg" height="100px" />
                <?php
                    }
                ?>
            </td>
               <?php /* 
                    <td>
                    	<?php if($row['button_text']!=NULL){ ?>
                    	<a class="btn btn-xs" style="background:<?php echo $row['button_color']; ?>; color:<?php echo $row['text_color']; ?>" href="<?php echo $row['button_link']; ?>"
                        	data-toggle="tooltip" title="<?php echo translate('click_to_check_link');?>">
							<?php echo $row['button_text']; ?>
                        </a>
                        <?php } ?>
                    </td>
                */ ?>
                    <?php // added by sagar : START 22-10 banner based on app language ?>
                    <td><?php echo ($row['slides_lang'] == 'ar') ? 'Arabic' : 'English' ; ?></td>
                    <?php // added by sagar : END 22-10 banner based on app language ?>
                    <td>
                    	<input id="slide_<?php echo $row['slides_id']; ?>" class="slide" type="checkbox" data-id="<?php echo $row['slides_id']; ?>" <?php if($row['status']=='ok'){ echo 'checked'; } ?> />
                    </td>
                     <?php if($this->crud_model->admin_permission('slides_edit') ) { ?>
                    <td class="text-right">
                        <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                            onclick="ajax_modal('edit','<?php echo translate('edit_banner'); ?>','<?php echo translate('successfully_edited!'); ?>','slides_edit','<?php echo $row['slides_id']; ?>')" 
                                data-original-title="Edit" 
                                    data-container="body"><?php echo translate('edit');?>
                        </a>
                        <?php /* 
                        <a onclick="delete_confirm('<?php echo $row['slides_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" 
                            class="btn btn-danger btn-xs btn-labeled fa fa-trash" 
                                data-toggle="tooltip" data-original-title="Delete" 
                                    data-container="body"><?php echo translate('delete');?>
                        </a>
                        */ ?>
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
		<h1 style="display:none;"><?php echo translate('slides'); ?></h1>
		<table id="export-table" data-name='slides' data-orientation='p' style="display:none;">
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
	            	foreach($all_slidess as $row){
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
var module = 'slides';
function set_switchery(){
	$(".slide").each(function(){
		new Switchery(document.getElementById('slide_'+$(this).data('id')), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
		var changeCheckbox = document.querySelector('#slide_'+$(this).data('id'));
		changeCheckbox.onchange = function() {
		  //alert($(this).data('id'));
		  ajax_load(base_url+'index.php/'+user_type+'/'+module+'/slide_publish_set/'+$(this).data('id')+'/'+changeCheckbox.checked,'','');
		  if(changeCheckbox.checked == true){
			$.activeitNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : s_e,
				container : 'floating',
				timer : 3000
			});
			sound('published');
		  } else {
			$.activeitNoty({
				type: 'danger',
				icon : 'fa fa-check',
				message : s_d,
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
