<div class="row" style="border-bottom: 1px solid #ebebeb;padding: 25px 5px 5px 5px;">

    <div class="col-md-12">
        <span><h3>Product Title : <?php echo $product_details[0]['title']; ?></h3></span>
        <?php if($this->crud_model->admin_permission('product_variation_add')){ ?>
        <button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt " 
                onclick="ajax_modal('variationadd','<?php echo translate('add_variation'); ?>','<?php echo translate('successfully_opened!'); ?>','variation_add','<?php echo $product_details[0]['product_id']; ?>');">
                <?php echo translate('Add Variation');?>
        </button>
        <?php } ?>
    </div>
    <div class="col-md-12">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,2" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('SKU');?></th>
					<th><?php echo translate('Title');?></th>
					<th><?php echo translate('Supplier Sale Price');?></th>
					<th><?php echo translate('Sale Price');?></th>
					<th><?php echo translate('is_default?');?></th>
                                        <th><?php echo translate('current_stock');?></th>
					<th><?php echo translate('Status');?></th>
					<th><?php echo translate('Attribute : Value');?></th>
					<th class="text-right"><?php echo translate('Action');?></th>
				</tr>
			</thead>
				
			<tbody >
			<?php
                        
                        $product_variation_permission = $this->crud_model->admin_permission('product_variation');
                        $product_variation_edit_permission = $this->crud_model->admin_permission('product_variation_edit');
                        $product_variation_delete_permission = $this->crud_model->admin_permission('product_variation_delete');
                        $product_variation_default_permission = $this->crud_model->admin_permission('product_variation_default');
                        $stock_permission = $this->crud_model->admin_permission('stock'); 
                        $stock_edit_permission = $this->crud_model->admin_permission('stock_edit');
                        $stock_add_permission = $this->crud_model->admin_permission('stock_add');
                        
                        
				$i = 0;
            	foreach($product_variations as $row){
            		$i++;
			?>
			<tr>
				
                                <td><?php echo $row['sku_code']; ?></td>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo DEF_CURR .$row['supplier_price']; ?></td>
                                <td><?php echo DEF_CURR .$row['sale_price']; ?></td>
                                <td><?php echo translate($row['is_default']);?></td>
                          
                                <td><?php 
                                
                                        if($row['current_stock'] > 0){ 
                                            echo '<center>'.$row['current_stock'].'</center>';                     
                                        } else {
                                            echo '<span class="label label-danger">'.translate('out_of_stock').'</span>';
                                        }
                                
                                ?></td>
                             
                                <td>
                                    <?php
                                    
                                    if($row['status'] == 'Active'){
                                        echo '<input id="var_'.$row['variation_id'].'" class="variation_status" type="checkbox" data-id="'.$row['variation_id'].'" checked />';
                                    } else {
                                        echo '<input id="var_'.$row['variation_id'].'" class="variation_status" type="checkbox" data-id="'.$row['variation_id'].'" />';
                                    }
                                    ?>
                                    
                                </td>
                                <td>
                                    <?php 
                                            $query = $this->db->query('Select a.attribute_name,av.value
                                                            From attribute_mapping am
                                                            Left Join attribute a ON (am.attribute_id = a.attribute_id)
                                                            Left Join attributevalue av ON (am.attributevalue_id = av.attributevalue_id)
                                                            Where am.variation_id = '.$this->db->escape($row['variation_id']).'
                                                            ');
                                           
                                            $res = $query->result_array();
                                            if(is_array($res) && count($res) > 0){
                                                foreach($res as $keya => $vala){
                                                    echo $vala['attribute_name'].' : '.$vala['value'].'<br>';
                                                }
                                            }

                                        ?>
                                </td>
                                
				<td class="text-right">
                                    
                                    <?php if($stock_permission && $stock_add_permission){ ?>
                                    <a class="btn btn-mint btn-xs btn-labeled fa fa-plus-square" data-toggle="tooltip" 
                                        onclick="ajax_modal('add_stock','<?php echo translate('add_variation_quantity') ?>','<?php echo translate('quantity_added!') ?>','stock_add','<?php echo $row['product_id'].'/'.$row['variation_id'] ?>')" data-original-title="Edit" data-container="body">
                                            <?php echo translate('stock') ?>
                                    </a>
                                    <?php }  ?>
                                    <?php if($stock_permission && $stock_edit_permission){ ?>
                                    <a class="btn btn-dark btn-xs btn-labeled fa fa-minus-square" data-toggle="tooltip" 
                                        onclick="ajax_modal('destroy_stock','<?php echo translate('reduce_product_quantity')  ?>','<?php echo translate('quantity_reduced!') ?>','destroy_stock','<?php echo $row['product_id'].'/'.$row['variation_id'] ?>')" data-original-title="Edit" data-container="body">
                                            <?php echo translate('stock') ?>
                                    </a>
                                    <?php }  ?>
                                    <?php if($product_variation_edit_permission){ ?>
                                    <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                                            onclick="ajax_modal('variationedit','<?php echo translate('edit_variation'); ?>','<?php echo translate('successfully_edited!'); ?>','variation_edit','<?php echo $row['product_id'].'/'.$row['variation_id']; ?>')" 
                                    data-original-title="Edit" data-container="body">
                                    <?php echo translate('edit');?>
                                    </a>
                                    <?php } ?>
                                    <?php if($product_variation_default_permission){ ?>
                                   <a onclick="msg_confirm('<?php echo $row['product_id'].'/'.$row['variation_id']; ?>','<?php echo translate('are you sure to add this as default variation?'); ?>','set_as_default','Successfully added as default variation')" class="btn btn-warning btn-xs btn-labeled fa fa-check-square" data-toggle="tooltip" 
                                        data-original-title="Set as Default" data-container="body">
                                                <?php echo translate('Set as default');?>
                                    </a>
                                    <?php } ?>
                                    <?php /* if($product_variation_delete_permission){ ?>
                                    <a onclick="delete_confirm('<?php echo $row['variation_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip" 
                                        data-original-title="Delete" data-container="body">
                                                <?php echo translate('delete');?>
                                    </a>
                                    <?php }*/  ?>
				</td>
			</tr>
            <?php
            	}
            ?>
			</tbody>
		</table>
	</div>
           
	<div id='export-div'>
		<h1 style="display:none;"><?php echo translate('product_variation'); ?></h1>
		<table id="export-table" data-name='product_variation' data-orientation='p' style="display:none;">
				<thead>
					<tr>
						<th><?php echo translate('no');?></th>
						<th><?php echo translate('product_name');?></th>
						<th><?php echo translate('SKU');?></th>
                                                <th><?php echo translate('Title');?></th>
                                                <th><?php echo translate('Price');?></th>
					</tr>
				</thead>
					
				<tbody >
				<?php
					$i = 0;
	            	foreach($product_variations as $row){
	            		$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $product_details[0]['title']; ?></td>
					<td><?php echo $row['sku_code']; ?></td>
                                        <td><?php echo $row['title']; ?></td>
                                        <td><?php echo $row['sale_price']; ?></td>
				</tr>
	            <?php
	            	}
				?>
				</tbody>
		</table>
	</div>

</div>

<script>
    list_cont_func = 'manage_variation';
    dlt_cont_func = 'deletevariation';
    $('#demo-table').bootstrapTable().on('page-change.bs.table', function (e, size, number) {
            status_build();
        });
    $('.page-header').html('Manage Product Variation');
    
    
    //$(document).ready(function(){
   
        status_build();
    
//        $("input[placeholder='Search']").bind("change", function(e) {
//            status_build();
//        });
    //});
    
    
    
    function status_build(){
        $(".variation_status").each(function(){
            new Switchery(document.getElementById('var_'+$(this).data('id')), {color:'rgb(100, 189, 99)', secondaryColor: '#cc2424', jackSecondaryColor: '#c8ff77'});
            var changeCheckbox = document.querySelector('#var_'+$(this).data('id'));
            changeCheckbox.onchange = function() {
              //alert($(this).data('id'));
              ajax_load(base_url+'index.php/'+user_type+'/'+module+'/change_variation_status/'+$(this).data('id')+'/'+changeCheckbox.checked,'prod','others');
              if(changeCheckbox.checked == true){
                    $.activeitNoty({
                            type: 'success',
                            icon : 'fa fa-check',
                            message : vpss,
                            container : 'floating',
                            timer : 3000
                    });
                    setTimeout(function(){ ajax_set_list(); }, 500);
                    sound('published');
              } else {
                    $.activeitNoty({
                            type: 'danger',
                            icon : 'fa fa-check',
                            message : vpsd,
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