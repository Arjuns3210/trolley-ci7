<div class="row" style="border-bottom: 1px solid #ebebeb;padding: 25px 5px 5px 5px;">

    <div class="col-md-12">
        <span><h3>Attribute : <?php echo $attribute_data[0]['attribute_name']; ?></h3></span>
        <?php if($this->crud_model->admin_permission('attribute_value_add')){ ?>
        <button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt " 
                onclick="ajax_modal('valueadd','<?php echo translate('add_attribute_value'); ?>','<?php echo translate('successfully_added!'); ?>','attributevalue_add','<?php echo $attribute_data[0]['attribute_id']; ?>');">
                <?php echo translate('create_'.$attribute_data[0]['attribute_name'].'_attribute_value');?>
        </button>
        <?php } ?>
    </div>
    <div class="col-md-12">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('attribute_value');?></th>
					<th class="text-right"><?php echo translate('options');?></th>
				</tr>
			</thead>
				
			<tbody >
			<?php
                        
                            $attribute_value_edit_permission = $this->crud_model->admin_permission('attribute_value_edit');
                            $attribute_value_delete_permission = $this->crud_model->admin_permission('attribute_value_delete');
				$i = 0;
            	foreach($attribute_value as $row){
            		$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
                                <td><?php echo $row['value']; ?></td>
                                
				<td class="text-right">
                                    <?php if($attribute_value_edit_permission){ ?>
                                   <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                                        onclick="ajax_modal('valueedit','<?php echo translate('edit_attribute_value'); ?>','<?php echo translate('successfully_edited!'); ?>','attributevalue_edit','<?php echo $row['attributevalue_id']; ?>')" 
                        	data-original-title="Edit" data-container="body">
                            	<?php echo translate('edit');?>
                                    </a>
                                    <?php } ?>
                                    <?php /* if($attribute_value_delete_permission){ ?>
                                    <a onclick="delete_confirm('<?php echo $row['attributevalue_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" 
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
						<th><?php echo translate($attribute_data[0]['attribute_name'].' : attribute_value');?></th>
					</tr>
				</thead>
					
				<tbody >
				<?php
					$i = 0;
	            	foreach($attribute_value as $row){
	            		$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row['value']; ?></td>
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>

</div>

<script>
    list_cont_func = 'attributeAddEdit';
    dlt_cont_func = 'deleteValue';
    $('#demo-table').bootstrapTable();
</script>