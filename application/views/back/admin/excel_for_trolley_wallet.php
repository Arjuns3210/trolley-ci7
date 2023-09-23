<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<div id="content-container">
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo translate( 'Trolley Balance Import' ); ?></h1>
    </div>
    <div class="tab-base">
        <div class="panel">
            <div class="panel-body">
                <div class="tab-content">
                    <!-- LIST -->
                    <div class="tab-pane fade active in" id="" style="border:1px solid #ebebeb; border-radius:4px;">
						<?php
						echo form_open( base_url() . 'index.php/admin/importTrolleyBalance/saveTrolleyBalance', array(
							'class'   => 'form-horizontal',
							'method'  => 'post',
							'id'      => 'trolleybalanceid',
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
                             
                                <div class="col-sm-6">
                                    <a  class="btn btn-info" id="sample_vs_exp" href="<?php echo base_url().'admin/importTrolleyBalance/download' ; ?>">Download Sample File</a>
                                    <input type="submit" value="Submit" class="btn btn-primary">
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="panel-footer">
                    </div>
                    </form>
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
