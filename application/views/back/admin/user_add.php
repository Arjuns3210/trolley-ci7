<div class="row">
    
    
    <div class="col-md-12">
        <div class="col-md-12" style="border-bottom: 1px solid #ebebeb;padding: 5px;">
        
        <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" style="display: block;" onclick="ajax_set_list();  proceed('to_add');">Back To Customer List </button>
    </div>
		<?php
            echo form_open(base_url() . 'index.php/admin/user/do_add/', array(
                'class' => 'form-horizontal',
                'method' => 'post',
                'id' => 'user_add',
				'enctype' => 'multipart/form-data'
            ));
        ?>
            <!--Panel heading-->
            
           
            <div id="product_details" class="tab-pane fade active in">

                <div class="form-group btm_border">
                    <h4 class="text-thin text-center"><?php echo translate('customer_details'); ?></h4>                            
                </div>

                <div class="form-group btm_border">
                    <label class="col-sm-4 control-label" for="name"><?php echo translate('Name');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="name" id="name" placeholder="<?php echo translate('name');?>" class="form-control required">
                    </div>
                </div>
                <div class="form-group btm_border">
                    <label class="col-sm-4 control-label" for="contact_number"><?php echo translate('contact_number');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="contact_number" id="contact_number" placeholder="<?php echo translate('contact_number');?>" class="form-control required">
                    </div>
                </div>
                <div class="form-group btm_border">
                    <label class="col-sm-4 control-label" for="email_address"><?php echo translate('email_address');?></label>
                    <div class="col-sm-6">
                        <input type="text" name="email_address" id="email_address" placeholder="<?php echo translate('email_address');?>" class="form-control">
                    </div>
                </div>
                <?php /*
                <div class="form-group btm_border">
                    <label class="col-sm-4 control-label"
                          for="demo-hor-type"><?php echo translate( 'Type' ); ?></label>
                   <div class="col-sm-6">
                       <input type="radio" style="display: inline;margin-right: 10px;" name="user_type" id="b2b" value="b2b"  ><label for="b2b">B2B</label>
                       <input type="radio"  style="display: inline;margin-left: 20px;" name="user_type" id="b2c" value="b2c" ><label style="margin-left: 10px;" for="b2c">B2C</label>
                   </div>
               </div>
                 */ ?>

            </div>
                        
                        
                        
                    
    
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-9">
                        <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right " 
                            onclick="ajax_set_full('add','<?php echo translate('add_user'); ?>','<?php echo translate('successfully_added!'); ?>','user_add',''); "><?php echo translate('reset');?>
                        </span>
                    </div>
                    
                    <div class="col-md-3">
                        <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right enterer" onclick="form_submit('user_add','<?php echo translate('customer_created_successfully!'); ?>');proceed('to_add');" ><?php echo translate('Add_customer');?></span>
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


<!--Bootstrap Tags Input [ OPTIONAL ]-->

