<div class="panel-body" id="demo_s">
    <table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,3" data-show-toggle="true" data-show-columns="false" data-search="true" >
        <thead>
            <tr>
                <th><?php echo translate('no');?></th>
                <th><?php echo translate('name');?></th>
                <th><?php echo translate('banner');?></th>
                <th><?php echo translate('category');?></th>
                <th><?php echo translate('brands');?></th>
                <th><?php echo translate('publish');?></th>
                 <?php if($this->crud_model->admin_permission('sub_category_edit') ) { ?>
                <th class="text-right"><?php echo translate('options');?></th>
                 <?php } ?>
            </tr>
        </thead>				
        <tbody >
        <?php
            $i=0;
            foreach($all_sub_category as $row){
                $i++;
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row['sub_category_name']; ?></td>
          
            <td>
                <?php
                    if(file_exists('uploads/sub_category_image/'.$row['banner'])){
                        ?>
                        <img class="img-md" src="<?php echo base_url(); ?>uploads/sub_category_image/<?php echo $row['banner']; ?>" height="100px" />  
                        <?php
                                } else {
                        ?>
                        <img class="img-md" src="<?php echo base_url(); ?>uploads/sub_category_image/default.jpg" height="100px" />
                        <?php
                                }
                    ?>
            </td>
     
             
            <td><?php echo $this->crud_model->get_type_name_by_id('category',$row['category'],'category_name'); ?></td>
            <?php
            	$brands=json_decode($row['brand'],true);
			?>
            <td>
				<?php 
					foreach($brands as $row1){
				?>
                    <span class="label label-info" style="margin-right: 5px;">
                        <?php echo $this->crud_model->get_type_name_by_id('brand',$row1,'name');?>
                    </span>
               	<?php 
					} 
				?>
          	</td>
                <td>
                    <input id="slide_<?php echo $row['sub_category_id']; ?>" class="slide" type="checkbox" data-id="<?php echo $row['sub_category_id']; ?>" <?php if($row['digital']=='ok'){ echo 'checked'; } ?> />
                </td>
            <?php if($this->crud_model->admin_permission('sub_category_edit') ) { ?>
            <td class="text-right">
                <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                    onclick="ajax_modal('edit','<?php echo translate('edit_sub-category_(_physical_product_)'); ?>','<?php echo translate('successfully_edited!'); ?>','sub_category_edit','<?php echo $row['sub_category_id']; ?>')" data-original-title="Edit" data-container="body">
                        <?php echo translate('edit');?>
                </a>
                <?php /*
                <a onclick="delete_confirm('<?php echo $row['sub_category_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" 
                        class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip"
                            data-original-title="Delete" data-container="body">
                                <?php echo translate('delete');?>
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
    <h1 style="display:none;"><?php echo translate('sub_category');?></h1>
    <table id="export-table" data-name='sub_category' data-orientation='p' style="display:none;">
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
                foreach($all_sub_category as $row){
                    $i++;
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row['sub_category_name']; ?></td>
                <td><?php echo $this->crud_model->get_type_name_by_id('category',$row['category'],'category_name'); ?></td>
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
var module = 'sub_category';
function set_switchery(){
	$(".slide").each(function(){
		new Switchery(document.getElementById('slide_'+$(this).data('id')), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
		var changeCheckbox = document.querySelector('#slide_'+$(this).data('id'));
		changeCheckbox.onchange = function() {
		  ajax_load(base_url+'index.php/'+user_type+'/'+module+'/subcategory_publish/'+$(this).data('id')+'/'+changeCheckbox.checked,'','');
		  if(changeCheckbox.checked == true){
			$.activeitNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : subcat_pb,
				container : 'floating',
				timer : 3000
			});
			sound('published');
		  } else {
			$.activeitNoty({
				type: 'danger',
				icon : 'fa fa-check',
				message : subcat_upb,
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
           