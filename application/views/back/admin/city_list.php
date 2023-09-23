	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,4" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('city_name');?></th>
					<th><?php echo translate('city_name_ar');?></th>
					<th><?php echo translate('status');?></th>
                                        <?php if($this->crud_model->admin_permission('city_edit')){ ?>
					<th class="text-right"><?php echo translate('options');?></th>
                                        <?php } ?>
				</tr>
			</thead>
				
			<tbody >
			<?php
				$i=0;
            	foreach($all_data as $row){
            		$i++;
			?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['city_name_en']; ?></td>
                    <td><?php echo $row['city_name_ar']; ?></td>
                   
                    <td>
                        <input id="pub_<?php echo $row['city_id']; ?>" class='sw1' type="checkbox" data-id='<?php echo $row['city_id']; ?>' <?php if($row['status'] == 'ok'){ ?>checked<?php } ?> />
                    </td>
                    <?php if($this->crud_model->admin_permission('city_edit')){ ?>
                    <td class="text-right">
                        <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                            onclick="ajax_modal('edit','<?php echo translate('edit_city'); ?>','<?php echo translate('successfully_edited!'); ?>','city_edit','<?php echo $row['city_id']; ?>')" 
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
    <div id="coupn"></div>
	<div id='export-div'>
		<h1 style="display:none;"><?php echo translate('city'); ?></h1>
		<table id="export-table" data-name='city' data-orientation='p' style="display:none;">
				<thead>
					<tr>
						<th><?php echo translate('no');?></th>
						<th><?php echo translate('city_name');?></th>
						<th><?php echo translate('city_name_ar');?></th>
					</tr>
				</thead>
					
				<tbody >
				<?php
					$i = 0;
	            	foreach($all_data as $row){
	            		$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
                    <td><?php echo $row['city_name_en']; ?></td>
                    <td><?php echo $row['city_name_ar']; ?></td>
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







           