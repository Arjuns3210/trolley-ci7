<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<div id="content-container">
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo translate( 'Update Delivery Charge for Area' ); ?></h1>
    </div>
    <div class="tab-base">
        <div class="panel">
            <div class="panel-body">
                <div class="tab-content">
                    <!-- LIST -->
                    <div class="tab-pane fade active in" id="" style="border:1px solid #ebebeb; border-radius:4px;">
						<?php
						echo form_open( base_url() . 'index.php/admin/areaReport/saveChanges', array(
							'class'   => 'form-horizontal',
							'method'  => 'post',
							'id'      => 'updateArea',
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
                                <p style="color:blue;font-weight: bold">Note : During Import in STATUS column enter 1 for Active OR 2 for In-active</p>
                                
                                <div class="col-md-4">
                                     <a  class="btn btn-success" id="sample_vs_exp" href="<?php echo base_url().'admin/areaReport/download' ; ?>">Download Sample File</a>
                                </div>
                                
                                <div class="col-md-2">
                                    <input type="submit" value="Submit" class="btn btn-primary">
                                </div>
                                    
                                    
                                <div class="col-md-6">
                                </div>
                                
                            </div>

                        </div>

                    </form>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                                    <div class="col-md-5">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-info btn-labeled fa fa-step-backward pull-right pro_list_btn" 
                                            onclick="ajax_redirect('area');"><?php echo translate('back_to_area_list');?>
                                        </button>
                                    </div>
                                    
                                    
                                    <div class="col-md-5">
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
	var module = 'areaReport';
	var list_cont_func = '';
	var dlt_cont_func = '';
</script>
