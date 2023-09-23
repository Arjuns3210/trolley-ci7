<?php 
	foreach($user_data as $row)
	{ 
?>
    <div id="content-container" style="padding-top:0px !important;">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-body">
                <table class="table table-striped" style="border-radius:3px;">
                    <tr>
                        <th class="custom_td"><?php echo translate('phone_number');?></th>
                        <td class="custom_td"><?php echo $row['phone']; ?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('first_name');?></th>
                        <td class="custom_td"><?php echo $row['first_name']; ?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('last_name');?></th>
                        <td class="custom_td"><?php echo $row['fourth_name']; ?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('sex');?></th>
                        <td class="custom_td"><?php echo $row['sex']; ?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('city');?></th>
                        <td class="custom_td"><?php echo $this->crud_model->get_type_name_by_id('city', $row['city_id'], 'city_name_en')?? '-' ?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('area');?></th>
                        <td class="custom_td"><?php echo $this->crud_model->get_type_name_by_id('area', $row['area_id'], 'area_name_en')?? '-' ?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('job_type');?></th>
                        <td class="custom_td"><?php echo $row['job_type']; ?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('social_status');?></th>
                        <td class="custom_td"><?php echo $row['social_status']; ?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('email');?></th>
                        <td class="custom_td"><?php echo $row['email']?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('Address Location');?></th>
                        <td class="custom_td"><?php echo !empty($row['langlat']) ? $row['langlat'] : '-'; ?></td>
                    </tr>
                    <tr>
                        <th class="custom_td"><?php echo translate('creation_date');?></th>
                        <td class="custom_td"><?php echo date('d M,Y',$row['creation_date']);?></td>
                    </tr>
                </table>
              </div>
            </div>
        </div>					
    </div>					
<?php 
	}
?>
            
<style>
.custom_td{
border-left: 1px solid #ddd;
border-right: 1px solid #ddd;
border-bottom: 1px solid #ddd;
}
</style>
<script>
$(document).ready(function(e) {
    $('.modal-footer').find('.btn-purple').hide();
});
</script>