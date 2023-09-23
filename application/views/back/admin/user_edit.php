<div class="row">
    
    
    <div class="col-md-12">
        <div class="col-md-12" style="border-bottom: 1px solid #ebebeb;padding: 5px;">
        
        <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" style="display: block;" onclick="ajax_set_list();  proceed('to_add');">Back To Customer List </button>
    </div>
        <?php
        
//        echo '<pre>';
//        print_r($user_data);
//        exit();
        
            echo form_open(base_url() . 'index.php/admin/user/update/'.$user_data[0]['user_id'], array(
                'class' => 'form-horizontal',
                'method' => 'post',
                'id' => 'user_edit',
                'enctype' => 'multipart/form-data'
            ));
        ?>
            <!--Panel heading-->
            
           
            <div id="product_details" class="tab-pane fade active in">

                <div class="form-group btm_border">
                    <h4 class="text-thin text-center"><?php echo translate('customer_details'); ?></h4>                            
                </div>

                <div class="form-group btm_border">
                    <label class="col-sm-4 control-label" for="first_name"><?php echo translate('first_name');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="first_name" id="first_name" value="<?php echo $user_data[0]['username']; ?>" placeholder="<?php echo translate('first_name');?>" class="form-control required">
                        <input type="hidden" name="user_id" id="first_name" value="<?php echo $user_data[0]['user_id']; ?>" placeholder="<?php echo translate('first_name');?>" class="form-control required">
                    </div>
                </div>

                <div class="form-group btm_border">
                    <label class="col-sm-4 control-label" for="last_name"><?php echo translate('last_name');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="last_name" id="last_name" value="<?php echo $user_data[0]['surname']; ?>"  placeholder="<?php echo translate('last_name');?>" class="form-control required">
                    </div>
                </div>

                <div class="form-group btm_border">
                    <label class="col-sm-4 control-label" for="contact_number"><?php echo translate('contact_number');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="contact_number" readonly id="contact_number" value="<?php echo $user_data[0]['phone']; ?>" placeholder="<?php echo translate('contact_number');?>" class="form-control required">
                    </div>
                    <input type="checkbox" name="change_number" id="change_number" value="yes" ><label for="change_number"> Change Number</label>
                </div>
                <div class="form-group btm_border">
                    <label class="col-sm-4 control-label" for="email_address"><?php echo translate('email_address');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="email_address" readonly="" id="email_address" value="<?php echo $user_data[0]['email']; ?>" placeholder="<?php echo translate('email_address');?>" class="form-control">
                    </div>
                    <input type="checkbox" name="change_email" id="change_email" value="yes" ><label for="change_email"> Change Email</label>
                </div>

            </div>
                        
                        
                        
                    
    
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-9">
                        <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right" 
                            onclick="ajax_set_full('edit','<?php echo translate('edit_user'); ?>','<?php echo translate('successfully_edited!'); ?>','user_edit','<?php echo $user_data[0]['user_id']; ?>') "><?php echo translate('reset');?>
                        </span>
                    </div>
                    
                    <div class="col-md-3">
                        <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right enterer" onclick="form_submit('user_edit','<?php echo translate('customer_created_successfully!'); ?>');" ><?php echo translate('Update_customer');?></span>
                    </div>
                    
                </div>
            </div>
    
        </form>
    </div>
</div>

<script src="<?php $this->benchmark->mark_time(); echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js">
</script>

<input type="hidden" id="option_count" value="-1">


<style>
	.btm_border{
		border-bottom: 1px solid #ebebeb;
		padding-bottom: 15px;	
	}
</style>
<script>
    $(document).ready(function(){ 
    $('#change_email').change(function() {
        // this will contain a reference to the checkbox   
        if (this.checked) {
            $('#email_address').prop("readonly",false);
        } else {
            $('#email_address').prop("readonly",true);
        }
    });
    $('#change_number').change(function() {
        // this will contain a reference to the checkbox   
        if (this.checked) {
            $('#contact_number').prop("readonly",false);
        } else {
            $('#contact_number').prop("readonly",true);
        }
    });
});
</script>
<?php

    $address_query = $this->db->query('Select u.*,c.name as country_name,s.name as state_name,cit.name as city_name 
                                      From user_address u
                                      Left Join country c ON (c.country_id = u.country_id)
                                      Left Join state s ON (s.state_id = u.state_id)
                                      Left Join city cit ON (cit.city_id = u.city_id)
                                      where u.user_id = '.$this->db->escape($user_data[0]['user_id']));

    $get_address = '';
    if($address_query !== FALSE && $address_query->num_rows() >0){
        $get_address = $address_query->result_array();
        
        
    }
    
?>
<div class="row">
    <div class="col-md-12">
        <center>
            <h3><?php echo $user_data[0]['username']; ?>'s Address(s)</h3>
        </center>
    </div>
    <div class="col-md-12" style="border-bottom: 1px solid #ebebeb;padding: 5px;">
        
        <button class="btn btn-primary btn-labeled fa fa-plus-circle pull-right mar-rgt" 
                onclick="ajax_modal('addaddress','<?php echo translate('add_address'); ?>','<?php echo translate('address added successfully!'); ?>', 'address_add', '<?php echo $user_data[0]['user_id']; ?>' )">
                                    <?php echo translate('create_address');?>
            </button>
    </div>
    <div class="col-md-12">
        <div class="panel-body" id="demo_s">
            
                <table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" >
                    <thead>
                        <tr>
                            <th><?php echo translate('no');?></th>
                            <th><?php echo translate('address_1');?></th>
                            <th><?php echo translate('address_2');?></th>
                            <th><?php echo translate('country');?></th>
                            <th><?php echo translate('province');?></th>
                            <th><?php echo translate('city');?></th>
                            <th class="text-right"><?php echo translate('option');?></th>
                        </tr>
                    </thead>				
                    <tbody>
                    <?php
                        $i = 0;
                        if(is_array($get_address)){
                            
                        foreach($get_address as $row){
                            $i++;
                    ?>                
                    <tr>
                        <td><?php echo $i; ?></td>
                        
                        <td><?php echo $row['address_1']; ?></td>
                        <td><?php echo $row['address_2']; ?></td>
                        <td><?php echo $row['country_name']; ?></td>
                        <td><?php echo $row['state_name']; ?></td>
                        <td><?php echo $row['city_name']; ?></td>
                        
                        <td class="text-right">
                            
                            <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                                onclick="ajax_modal('editaddress','<?php echo translate('edit_address'); ?>','<?php echo translate('address updated successfully!'); ?>', 'address_edit', '<?php echo $user_data[0]['user_id'].'--'.$row['address_id']; ?>' );" data-original-title="View" data-container="body">
                                    <?php echo translate('Edit');?>
                            </a>
<!--                            <a onclick="delete_confirm('<?php echo $row['address_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" class="btn btn-xs btn-danger btn-labeled fa fa-trash" data-toggle="tooltip" 
                                data-original-title="Delete" data-container="body">
                                    <?php echo translate('delete');?>
                            </a>-->
                        </td>
                    </tr>
                    <?php
                        }}
                        else{
                            echo 'No Data';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
    </div>
</div>


<!--Bootstrap Tags Input [ OPTIONAL ]-->

