<div class="row" style="border-bottom: 1px solid #ebebeb;padding: 25px 5px 5px 5px;">

    <div class="col-md-12">
        <span><h3>Supplier Name : <?php echo $supplier_details[0]['supplier_name']; ?></h3></span>
        <?php if($this->crud_model->admin_permission('supplier_store_add')){ ?>
        <button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt " 
                onclick="ajax_modal('storeadd','<?php echo translate('add_store'); ?>','<?php echo translate('successfully_opened!'); ?>','store_add','<?php echo $supplier_details[0]['supplier_id']; ?>');">
                <?php echo translate('Add Store');?>
        </button>
        <?php } ?>
    </div>
    <div class="col-md-12">
        <?php
        $supplier_store_edit_permission = $this->crud_model->admin_permission('supplier_store_edit');
        ?>
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('store_name');?></th>
					<th><?php echo translate('store_number');?></th>
					<th><?php echo translate('store_address');?></th>
					<th><?php echo translate('status');?></th>
                                        <?php if($supplier_store_edit_permission){ ?>
					<th class="text-right"><?php echo translate('Action');?></th>
                                        <?php } ?>
				</tr>
			</thead>
				
			<tbody >
			<?php
                        
                        
                        
                        
                        
				$i = 0;
            	foreach($supplier_store as $row){
            		$i++;
			?>
			<tr>
				
                                <td><?php echo $row['store_name']; ?></td>
                                <td><?php echo $row['store_number']; ?></td>
                                <td><?php echo $row['store_address']; ?></td>
                              <td>
                                    <?php
                                    
                                    if($row['status'] == 'Active'){
                                        echo '<input id="var_'.$row['supplier_store_id'].'" class="variation_status" type="checkbox" data-id="'.$row['supplier_store_id'].'" checked />';
                                    } else {
                                        echo '<input id="var_'.$row['supplier_store_id'].'" class="variation_status" type="checkbox" data-id="'.$row['supplier_store_id'].'" />';
                                    }
                                    ?>
                                    
                                </td> 
                                <?php if($supplier_store_edit_permission){ ?>
				<td class="text-right">
                                    <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                                            onclick="ajax_modal('storeedit','<?php echo translate('edit_store'); ?>','<?php echo translate('successfully_edited!'); ?>','store_edit','<?php echo $row['supplier_id'].'/'.$row['supplier_store_id']; ?>')" 
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
		<h1 style="display:none;"><?php echo translate('supplier_store'); ?></h1>
		<table id="export-table" data-name='supplier_store' data-orientation='p' style="display:none;">
				<thead>
					<tr>
						<th><?php echo translate('store_name');?></th>
						<th><?php echo translate('store_number');?></th>
						<th><?php echo translate('store_address');?></th>
					</tr>
				</thead>
					
				<tbody >
				<?php
					$i = 0;
	            	foreach($supplier_store as $row){
	            		$i++;
				?>
				<tr>
					<td><?php echo $row['store_name']; ?></td>
                                        <td><?php echo $row['store_number']; ?></td>
                                        <td><?php echo $row['store_address']; ?></td>
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>

</div>

<script>
    list_cont_func = 'manage_store';
    dlt_cont_func = 'deletevariation';
    $('#demo-table').bootstrapTable().on('page-change.bs.table', function (e, size, number) {
            status_build();
        });
    $('.page-header').html('Manage Supplier Stores');
    status_build();
    
    
    
    function status_build(){
        $(".variation_status").each(function(){
            new Switchery(document.getElementById('var_'+$(this).data('id')), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
            var changeCheckbox = document.querySelector('#var_'+$(this).data('id'));
            changeCheckbox.onchange = function() {
              //alert($(this).data('id'));
              ajax_load(base_url+'index.php/'+user_type+'/'+module+'/change_store_status/'+$(this).data('id')+'/'+changeCheckbox.checked,'prod','others');
              if(changeCheckbox.checked == true){
                    $.activeitNoty({
                            type: 'success',
                            icon : 'fa fa-check',
                            message : '<?php echo "Store status changed to Active."; ?>',
                            container : 'floating',
                            timer : 3000
                    });
                    setTimeout(function(){ ajax_set_list(); }, 500);
                    sound('published');
              } else {
                    $.activeitNoty({
                            type: 'danger',
                            icon : 'fa fa-check',
                            message : '<?php echo "Store status changed to In-Active."; ?>',
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