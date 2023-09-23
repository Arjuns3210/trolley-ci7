 
	<div class="tab-pane fade active in" id="edit">
		<?php
			echo form_open(base_url() . 'index.php/admin/product/variation_doedit/' .$product_details[0]['product_id'].'/'.$variation[0]['variation_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'variation_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="sku_code">
                    	<?php echo translate('sku_code');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="sku_code"  
                                                       value="<?php echo $variation[0]['sku_code']; ?>" id="sku_code" 
                            	class="form-control required" placeholder="<?php echo translate('sku_code');?>" >
					</div>
				</div>
                            
                            
				<div class="form-group">
                                <label class="col-sm-4 control-label" for="supplier_price">
                                <?php echo translate('supplier_sale_price');?>
                        	</label>
					<div class="col-sm-6">
                                            <input type="number" min="0" step="0.01" name="supplier_price"  
                        	value="<?php echo $variation[0]['supplier_price']; ?>" id="supplier_price" 
                            	class="form-control required" placeholder="<?php echo translate('supplier_sale_price');?>" >
					</div>
				</div>
                            
				<div class="form-group">
                                <label class="col-sm-4 control-label" for="sale_price">
                                <?php echo translate('sale_price');?>
                        	</label>
					<div class="col-sm-6">
                                            <input type="number" min="0" step="0.01" name="sale_price"  
                        	value="<?php echo $variation[0]['sale_price']; ?>" id="sale_price" 
                            	class="form-control required" placeholder="<?php echo translate('sale_price');?>" >
					</div>
				</div>
                               
                               
				<div class="form-group">
					<label class="col-sm-4 control-label" for="title">
                                <?php echo translate('title');?>
                        	</label>
					<div class="col-sm-6">
						<input type="text" name="title"  
                        	value="<?php echo $variation[0]['title']; ?>" id="sale_price" 
                            	class="form-control required" placeholder="<?php echo translate('title');?>" >
					</div>
				</div>
                                <?php 
                                if(is_array($all_attribute)){
                                    foreach($all_attribute as $attr_key => $attr){
                                        
                                   
                                ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="attr<?php echo $attr['attribute_id']; ?>">
                                                <?php echo translate($attr['attribute_name'].' : ');?>
                                        </label>
                                        <div class="col-sm-6">
                                            <select name="attr<?php echo $attr['attribute_id']; ?>" id="attr<?php echo $attr['attribute_id']; ?>" 
                                                class="form-control required demo-chosen-select" style="width: 100%;">
                                                <option value="" >Choose One</option>
                                                <?php 
                                                    if(is_array($attr['attr_value'])){
                                                        foreach($attr['attr_value'] as $attrval_key => $attrval){
                                                            $sel = '';
                                                            if(isset($variation_data_map[$attrval['attribute_id']]) && $variation_data_map[$attrval['attribute_id']]==$attrval['attributevalue_id']){
                                                                $sel = 'selected';
                                                            }
                                                            
                                                ?>
                                                <option value="<?php echo $attrval['attributevalue_id']; ?>" <?php echo $sel; ?>><?php echo $attrval['value']; ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                    }
                                }
                            ?>
                                
                
			</div>
		</form>
	</div>


<script>
	$(document).ready(function() {
	    $("form").submit(function(e) {
	        return false;
	    });
            $('.demo-chosen-select').chosen();
	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
	
			reader.onload = function(e) {
				$('#wrap').hide('fast');
				$('#blah').attr('src', e.target.result);
				$('#wrap').show('fast');
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#imgInp").change(function() {
		readURL(this);
	});
	
</script>