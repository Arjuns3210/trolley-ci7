	<div class="panel-body" id="demo_s">
		<table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,4" data-show-toggle="true" data-show-columns="false" data-search="true" >

			<thead>
				<tr>
					<th><?php echo translate('no');?></th>
					<th><?php echo translate('group_name');?></th>
					<th><?php echo translate('payment_mode');?></th>
					<th><?php echo translate('service_fee ( %)');?></th>
					<th><?php echo translate('card_no');?></th>
                                        <?php if($this->crud_model->admin_permission('service_charge_edit')){ ?>
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
                    <td><?php echo $row['group_name']; ?></td>
                    <td><?php echo $row['payment_mode']; ?></td>
                    <td><?php echo $row['service_fees']; ?></td>
                    <td><?php echo $row['card_no']; ?></td>
                    
                    <?php if($this->crud_model->admin_permission('service_charge_edit')){ ?>
                    <td class="text-right">
                        <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                            onclick="ajax_modal('edit','<?php echo translate('edit_service_charge'); ?>','<?php echo translate('successfully_edited!'); ?>','service_charge_edit','<?php echo $row['service_charge_id']; ?>')" 
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
		<h1 style="display:none;"><?php echo translate('service_charge'); ?></h1>
		<table id="export-table" data-name='service_charge' data-orientation='p' style="display:none;">
				<thead>
					<tr>
						<th><?php echo translate('no');?></th>
						<th><?php echo translate('group_name');?></th>
						<th><?php echo translate('payment_mode');?></th>
						<th><?php echo translate('service_fees');?></th>
						<th><?php echo translate('card_no');?></th>
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
                    <td><?php echo $row['group_name']; ?></td>
                    <td><?php echo $row['payment_mode']; ?></td>
                    <td><?php echo $row['service_fees']; ?></td>
                    <td><?php echo $row['card_no']; ?></td>
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







           