

<div class="row">
    <div class="col-md-12">
         <div class="form-group btm_border">
                            <h4 class="text-thin text-center"><?php echo translate( 'Assign_stores' ); ?></h4>
        </div>
		
            <?php
		echo form_open(base_url() . 'index.php/admin/sales/update_assign_stores/' . $sale_id, array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'assign_stores_dd',
				'enctype' => 'multipart/form-data'
		));
		?>
            <div class="panel-body">
                
                    <div class="form-group btm_border">
                            <!--<div class="col-sm-12">-->
                            <?php  
                              $counter = 0;
                            if(is_array($sales_data) && !empty($sales_data[0])){
                                $product_details = json_decode($sales_data[0]['product_details'],true);
                                $assign_stores_data = json_decode($sales_data[0]['assign_stores_data'],true);
//                                $assign_stores="";
//                                if(is_array($assign_stores_data)){
//                                    $assign_stores = array_column($assign_stores_data,'supplier_store_id');
//                                }
                                $product_details =  array_values($product_details);
                                foreach ($product_details as $loopKey => $row) {
                                    
                                    $product_name = $row['name'];
                                    if($row['product_type'] == 'variation'){
                                        $product_name .= " ( ". $row['variation_title'] . " ) ";
                                    }
                                    
                                    $supplier_name = $this->db->get_where('supplier',array('supplier_id'=>$row['supplier']))->row()->supplier_name;
                                    $supplier_stores = $this->db->get_where('supplier_store',array('supplier_id'=>$row['supplier']))->result_array(); 
                                    
                                ?>
                              <input type="hidden" id="product_id" name="product_id[<?php echo $counter;?>]" value="<?php echo $row['product_id']; ?>" />
                              <input type="hidden" id="variation_id" name="variation_id[<?php echo $counter;?>]" value="<?php echo $row['variation_id']; ?>" />
                              <input type="hidden" id="supplier_id" name="supplier_id[<?php echo $counter;?>]" value="<?php echo $row['supplier']; ?>" />
                              <div class="col-md-12" style="margin-bottom:8px;">
                                            <div class="col-sm-5">
                                                <label>Product Name</label>
                                                <input type="text" size="35" value="<?php echo $product_name; ?>"  size="35" disabled class="form-control" />
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Supplier Name</label>
                                                <input type="text" size="35" value="<?php echo $supplier_name; ?>"  size="35"  disabled class="form-control" />
                                            </div>
                                          
                                            <div class="col-sm-3">
                                                <label>Select Store</label>
                                                <?php 
                                                if(is_array($supplier_stores) && !empty($supplier_stores)) { ?>
                                                        <select class="demo-chosen-select  required"  name="stores[<?php echo $counter;?>]" > 
                                                        <option value="">Choose One</option>
                                                 <?php   foreach($supplier_stores as $key => $val) {
                                                            $sel = "";
                                                            if(is_array($assign_stores_data)) {
                                                                if($assign_stores_data[$loopKey]['supplier_store_id'] == $val['supplier_store_id']){
                                                                    $sel = 'selected';
                                                                }
                                                            }
                                                    ?>
                                                            <option value="<?php echo $val['supplier_store_id'];?>"  <?php echo $sel; ?>><?php echo $val['store_name']; ?></option>

                                                <?php } ?>
                                                        </select>
                                                <?php  }  ?>
                                           
                                            </div>
                                          
                                    </div>
                                       
                                 <?php $counter++; } } ?>
                    <!--</div>-->
                </div>
                </div>

                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-11">
                                <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right "
                                      onclick="ajax_set_full('assign_store','<?php echo translate( 'assign_store' ); ?>','<?php echo translate( 'successfully_changed!' ); ?>','assign_store_data','<?php echo $sale_id; ?>'); "><?php echo translate( 'reset' ); ?>
                                </span>
                        </div>

                        <div class="col-md-1">
                            <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right enterer"
                                  onclick="form_submit('assign_stores_dd','<?php echo translate( 'store_assigned_successfully' ); ?>');proceed('to_add');"><?php echo translate( 'Edit' ); ?></span>
                        </div>

                    </div>
                </div>
         
        
        </form>

</div>
</div>


<script src="<?php $this->benchmark->mark_time();
echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js">
</script>

<input type="hidden" id="option_count" value="-1">

<script>
    $(document).ready(function () {
        $("form").submit(function (e) {
            event.preventDefault();
            
        });
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
        
    });
    
    
	
</script>

<style>
    .btm_border {
        border-bottom: 1px solid #ebebeb;
        padding-bottom: 15px;
    }
</style>


<!--Bootstrap Tags Input [ OPTIONAL ]-->

