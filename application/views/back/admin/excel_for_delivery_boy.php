<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<div id="content-container">
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo translate( 'Add New Delivery Boy' ); ?></h1>
    </div>
    <div class="tab-base">
        <div class="panel">
            <div class="panel-body">
                <div class="tab-content">
                    <!-- LIST -->
                    <div class="tab-pane fade active in" id="" style="border:1px solid #ebebeb; border-radius:4px;">
						<?php
						echo form_open( base_url() . 'index.php/admin/importDeliveryboy/saveDeliveryData', array(
							'class'   => 'form-horizontal',
							'method'  => 'post',
							'id'      => 'importData',
							'enctype' => 'multipart/form-data'
						) );
						?>
                         <div class="form-group">
                             
                             <p style="margin-top: 50px;"></p>
                            <label class="col-sm-4 control-label" for="demo-hor-2">
                                    <?php echo translate('select_xls');?>
                            </label>
                            <div class="col-sm-6">
                                <input type="file" name="importproductexcel" id="demo-hor-2" 
                                    class="form-control required" >
                            </div>

                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="demo-hor-2">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            </label>
                            <div class="col-sm-6">
                                <p style="color:blue;font-weight: bold">Note : For City ID and Area IDs values, please download and check the values from City and Area Master Button below</p>
                                
                                <div class="col-md-4">
                                     <a  class="btn btn-success" id="sample_vs_exp" href="<?php echo base_url().'admin/importDeliveryboy/download' ; ?>">Download Sample File</a>
                                </div>
                                
                                <div class="col-md-2">
                                    <input type="submit" value="Submit" class="btn btn-primary">
                                </div>

                                <br/> 
                                <div class="col-md-6">
                                    
                                </div>
                             </div>
                            
                            
                            

                        </div>

                    </form>
                    </div>
                    <div class="panel-footer">
                     <div class="row">
                                     <div class="col-md-5">
                                         <a  class="btn btn-warning pull-right mar-rgt" id="sample_vs_exp" href="<?php echo base_url().'admin/importDeliveryboy/downloadArea' ; ?>">Download Area Master Data</a>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" 
                                            onclick="ajax_redirect('admins');"><?php echo translate('back_to_staff_list');?>
                                        </button>
                                    </div>
                                    
                                    
                                    <div class="col-md-5">
                                        <a  class="btn btn-warning" id="sample_vs_exp" href="<?php echo base_url().'admin/exportCityData' ; ?>">Download City Master Data</a>
                                    </div>
                        </div>
                    </div>
                    
                    
                    
                    
                </div>
            </div>
        </div>
        <!--Panel body-->
    </div>
</div>
</div>
<script>
	var base_url = '<?php echo base_url(); ?>'
	var user_type = 'admin';
	var module = '';
	var list_cont_func = '';
	var dlt_cont_func = '';
</script>
