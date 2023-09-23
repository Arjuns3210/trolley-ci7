	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('attribute_name');?></th>
					<th class="text-right"><?php echo translate('options');?></th>
				</tr>
			</thead>
				
			<tbody >
			<?php
                        
                        $attribute_edit_permission = $this->crud_model->admin_permission('attribute_edit');
                        $attribute_delete_permission = $this->crud_model->admin_permission('attribute_delete');
                        $attribute_value_permission = $this->crud_model->admin_permission('attribute_value');
				$i = 0;
            	foreach($all_attributes as $row){
            		$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
                                <td><?php echo $row['attribute_name']; ?></td>
                                
				<td class="text-right">
                                    <?php if($attribute_value_permission){ ?>
                                    <a class="btn btn-dark btn-xs btn-labeled fa fa-plus" data-toggle="tooltip" 
                                        onclick="ajax_set_full('attributeAddEdit','<?php echo translate('Add/Edit Attribute Value'); ?>','<?php echo translate('attribute value list fetched successfully!'); ?>','attributevalue_edit','<?php echo $row['attribute_id']; ?>');proceed('to_add');" data-original-title="Edit" data-container="body">
                                            <?php echo translate('Attribute Values'); ?>
                                    </a>
                                    <?php } ?>
                                    <?php if($attribute_edit_permission){ ?>
					<a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                    	onclick="ajax_modal('edit','<?php echo translate('edit_attribute'); ?>','<?php echo translate('successfully_edited!'); ?>','attribute_edit','<?php echo $row['attribute_id']; ?>')" 
                        	data-original-title="Edit" data-container="body">
                                    
                            	<?php echo translate('edit');?>
                                    </a>
                                    <?php } ?>
                                    <?php /* if($attribute_delete_permission){ ?>
                                    <a onclick="delete_confirm('<?php echo $row['attribute_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" 
                                        data-original-title="Delete" data-container="body">
                                                <?php echo translate('delete');?>
                                    </a>
                                    <?php } */ ?>
				</td>
			</tr>
            <?php
            	}
			?>
			</tbody>
		</table>
	</div>
           
	<div id='export-div'>
		<h1 style="display:none;"><?php echo translate('attribute'); ?></h1>
		<table id="export-table" data-name='attribute' data-orientation='p' style="display:none;">
				<thead>
					<tr>
						<th><?php echo translate('no');?></th>
						<th><?php echo translate('attribute_name');?></th>
					</tr>
				</thead>
					
				<tbody >
				<?php
					$i = 0;
	            	foreach($all_attributes as $row){
	            		$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row['attribute_name']; ?></td>
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>

