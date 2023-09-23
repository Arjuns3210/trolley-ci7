<?php
    $product_type = "";
    $is_offer = "";
    foreach($product_data as $row){
        $product_type = $row['product_type'];
        $is_offer = $row['is_offer'];
?>
<div class="row">
    <div class="col-md-12">
        <?php
			echo form_open(base_url() . 'admin/product/update/' . $row['product_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'product_edit',
				'enctype' => 'multipart/form-data'
			));
		?>
            <!--Panel heading-->
            <div class="panel-heading">
                <div class="panel-control" style="float: left;">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#product_details"><?php echo translate('product_details'); ?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#business_details"><?php echo translate('business_details'); ?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#variation"><?php echo translate('product_attributes'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="tab-base">
                    <!--Tabs Content-->                    
                    <div class="tab-content">
                        <div id="product_details" class="tab-pane fade active in">
                             <div class="form-group btm_border">
                                <h4 class="text-thin text-center"><?php echo translate( 'product_details' ); ?></h4>
                            </div>
                      
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="product_type">
                                    <?php echo translate('product_type');?>
                                        </label>
                                <div class="col-sm-6">
                                    <select name="product_type" id="product_type" disabled class="form-control required">
                                        <option value="simple" <?php if($row['product_type']=='simple'){ echo 'selected';} ?>>Simple Product</option>
                                     </select>
                                    <input type="hidden" name="product_type" value="<?php echo $row['product_type']; ?>">
                                </div>
                            </div>
                           
                            
                            <?php /* <input type="hidden" name="product_type" value="<?php echo $row['product_type']; ?>"> */ ?>
                           
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-1">
                                    <?php echo translate('product_title_english');?>
                                        </label>
                                <div class="col-sm-6">
                                    <input type="text" name="title" id="demo-hor-1" value="<?php echo $row['title']; ?>" placeholder="<?php echo translate('product_title_in_english');?>" class="form-control required">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-1">
                                    <?php echo translate('product_title_in_arabic');?>
                                        </label>
                                <div class="col-sm-6">
                                    <input type="text" name="title_ar" id="demo-hor-1" value="<?php echo $row['title_ar']; ?>" placeholder="<?php echo translate('product_title_in_arabic');?>" class="form-control required">
                                </div>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-01">
                                    <?php echo translate('product_code');?>
                                        </label>
                                <div class="col-sm-6">
                                    <input type="text" name="product_code" id="demo-hor-01" value="<?php echo $row['product_code']; ?>" placeholder="<?php echo translate('product_code');?>" class="form-control required">
                                </div>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('category');?></label>
                                <div class="col-sm-6">
                                    <?php // echo $this->crud_model->select_html('category','category','category_name','edit','demo-chosen-select required',$row['category'],'digital',NULL,'get_cat'); ?>
                                    <?php echo $this->crud_model->select_html('category','category','category_name','edit','demo-chosen-select required',$row['category'],'','','get_cat'); ?>
                                </div>
                            </div>
                            <div class="form-group btm_border" id="sub" >
                                <label class="col-sm-4 control-label" for="demo-hor-3"><?php echo translate('sub-category');?></label>
                                <div class="col-sm-6" id="sub_cat">
                                    <?php echo $this->crud_model->select_html('sub_category','sub_category','sub_category_name','edit','demo-chosen-select required',$row['sub_category'],'category',$row['category'],'get_brnd'); ?>
                                </div>
                            </div>
                            
                            <div class="form-group btm_border" id="brn" >
                                <label class="col-sm-4 control-label" for="demo-hor-4"><?php echo translate('brand');?></label>
                                <div class="col-sm-6" id="brand" >
                                    <?php 
                                        $brands=json_decode($this->crud_model->get_type_name_by_id('sub_category',$row['sub_category'],'brand'),true);
                                        if(count($brands)>0){
										  echo $this->crud_model->select_html('brand','brand','name','edit','demo-chosen-select',$row['brand'],'brand_id',$brands,'','multi'); 
                                        }else{
                                            echo translate("No brands are available for this sub category");
                                        }
									?>
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="similar_product"><?php echo translate('similar_product'); ?></label>
                                <div class="col-sm-6">
                                    <select class="demo-chosen-select" name="similar_product[]" id="similar_product" multiple>
                                        <?php foreach ($product_all as $val) { ?>
                                            <?php $selected = in_array($val['product_id'], $similar_products) ? 'selected' : ''; ?>
                                            <option value="<?php echo $val['product_id']; ?>" <?php echo $selected; ?>><?php echo $val['title']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <?php //added by sagar : START ON 29-07 ?>
                            <div class="form-group btm_border">
                                 <label class="col-sm-4 control-label"
                                       for="demo-hor-offer"><?php echo translate( 'is_offer' ); ?></label>
                                <div class="col-sm-6">
                                    <input type="radio" style="display: inline;margin-right: 10px;" name="is_offer" id="o1" value="yes" <?php echo ($row['is_offer'] == 'yes') ? 'checked':''; ?>  onchange="offerValidity(this.value);"  ><label for="o1">Yes</label>
                                    <input type="radio"  style="display: inline;margin-left: 20px;" name="is_offer" id="o2" value="no" <?php echo ($row['is_offer'] == 'no') ? 'checked':''; ?> onchange="offerValidity(this.value);"  ><label style="margin-left: 10px;" for="o2">No</label>
                                </div>
                            </div>
                            <div class="form-group btm_border display_offer_date" style="display:none;">
                                <label class="col-sm-4 control-label"
                                       for="demo-offer_validity"><?php echo translate( 'offer_validity' ); ?></label>
                                <div class="col-sm-6">
                                    <input type="date" name="offer_validity" id="demo-offer_validity" value="<?php if (isset($row['offer_validity'])) { echo date('Y-m-d', strtotime($row['offer_validity'])); } ?>" class="form-control">
                                </div>
                            </div>
                            <?php //added by sagar : END ON 29-07 ?>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label"
                                       for="demo-hor-5"><?php echo translate( 'unit' ); ?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="unit" id="demo-hor-5" value="<?php echo $row['unit'] ?>"
                                           placeholder="<?php echo translate( 'unit_(e.g._kg,_pc_etc.)' ); ?>"
                                           class="form-control unit required">
                                </div>
                            </div>
                          
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-12"><?php echo translate('images');?></label>
                                <div class="col-sm-6">
                                    <span class="pull-left btn btn-default btn-file"> <?php echo translate('choose_file');?>
                                        <input type="file" multiple name="images[]" onchange="preview(this);" id="demo-hor-inputpass" class="form-control">
                                    </span>
                                    <br><br>
                                     <p style="color:blue;font-weight: bold">Note : Upload file size <?php echo SIZE_DIMENSIONS['product']; ?></p>
                                    <span id="previewImg" ></span>
                                </div>
                            </div>

                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-13"></label>
                                <div class="col-sm-6">
                                    <?php 
                                        if(isset($row['num_of_imgs']) && $row['num_of_imgs']>0){
                                        $images = $this->crud_model->file_view('product',$row['product_id'],'','','thumb','src','multi','all');
                                        if($images){
                                            foreach ($images as $row1){
                                                $a = explode('.', $row1);
                                                $a = $a[(count($a)-2)];
                                                $a = explode('_', $a);
                                                $p = $a[(count($a)-2)];
                                                $i = $a[(count($a)-3)];
                                    ?>
                                        <div class="delete-div-wrap">
                                            <span class="close">&times;</span>
                                            <div class="inner-div">
                                                <img class="img-responsive" width="100" src="<?php echo $row1; ?>" data-id="<?php echo $i.'_'.$p; ?>" alt="User_Image" >
                                            </div>
                                        </div>
                                    <?php 
                                            }
                                        } 
                                     }
                                    ?>
                                </div>
                                
                            </div>

                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-45">
                                    <?php echo translate('embed_Link'); ?></label>
                                <div class="col-sm-6">
                                    <textarea rows="3" id="demo-hor-45"  name="embed_link" class="form-control" ><?php echo trim($row['embed_link']); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-14">
                                    <?php echo translate('description_in_english');?>
                                        </label>
                                <div class="col-sm-6">
                                    <textarea rows="9" class="summernotes" data-height="200" data-name="description"> <?php echo $row['description']; ?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-14">
                                    <?php echo translate('description_in_Arabic');?>
                                        </label>
                                <div class="col-sm-6">
                                    <textarea rows="9" class="summernotes" data-height="200" data-name="description_ar"><?php echo $row['description_ar']; ?></textarea>
                                </div>
                            </div>
                            <?php
                                $all_af = $this->crud_model->get_additional_fields($row['product_id']);
                                $all_c = json_decode($row['color']);
                                $all_op = json_decode($row['options'],true);
                            ?>

                            
                        </div>
                        <?php
                        $sku_code = '';
                        $sale_price = '';
                        log_message("error",print_r($simple_product,1));
                        if(is_array($simple_product) && count($simple_product) >0){
                            $sku_code = $simple_product[0]['sku_code'];
                            $sale_price = $simple_product[0]['sale_price'];
                        }
                        
                        ?>
                        <div id="business_details" class="tab-pane fade">
                            <div class="form-group btm_border">
                                <h4 class="text-thin text-center"><?php echo translate( 'business_details' ); ?></h4>
                            </div>
                            <span id="simple_product_data">
                                <div class="form-group btm_border">
                                    <label class="col-sm-4 control-label" for="sku_code"><?php echo translate('sku_code');?></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="sku_code" id="sku_code"  value="<?php echo $row['SKU_code'];  ?>" placeholder="<?php echo translate('sku_code');?>" class="form-control required">
                                    </div>
                                </div>
                            </span>
                            
                            
                         
                            <div class="form-group">
                                    <label class="col-sm-4 control-label" for="demo-hor-supplier"><?php echo translate('supplier');?></label>
                                    <div class="col-sm-4">
                                        <?php echo $this->crud_model->select_html('supplier','supplier','supplier_name|mobile_number|company_name','edit','demo-chosen-select required',$row['supplier'],'',NULL,'get_supp'); ?>
                                    </div>
                            </div>
                            
                             <div class="form-group btm_border">
                                <label class="col-sm-4 control-label"
                                       for="supplier_price"><?php echo translate( 'supplier_sale_price' ); ?></label>
                                <div class="col-sm-4">
                                    <input type="number" name="supplier_price" id="supplier_price" min='0' step='.01' value="<?php echo $row['supplier_price']?>"
                                           placeholder="<?php echo translate( 'supplier_sale_price' ); ?>"
                                           class="form-control required" >
                                </div>
                                <span class="btn"><?php echo DEF_CURR; ?> / </span>
                                <span class="btn unit_set"><?php echo $row['unit']; ?></span>
                            </div>
                            
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label"
                                       for="sale_price"><?php echo translate( 'app_sale_price' ); ?></label>
                                <div class="col-sm-4">
                                    <input type="number" name="sale_price" id="sale_price" min='0' step='.01' value="<?php echo $row['sale_price']?>"
                                           placeholder="<?php echo translate( 'sale_price' ); ?>"
                                           class="form-control required decimal" >
                                </div>
                                <span class="btn"><?php echo DEF_CURR;?> / </span>
                                <span class="btn unit_set"><?php echo $row['unit']; ?></span>
                            </div>
                        
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label"
                                       for="demo-hor-10"><?php echo translate( 'product_discount' ); ?></label>
                                <div class="col-sm-4">
                                    <input type="number" name="discount" id="demo-hor-10" min='0' step='.01' value="<?php echo $row['discount']?>"
                                           placeholder="<?php echo translate( 'product_discount' ); ?>"
                                           class="form-control">
                                </div>
                                <span class="btn">%</span>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label"
                                       for="discounted_amount"><?php echo translate( 'final_selling_price' ); ?></label>
                                <div class="col-sm-4">
                                    <input type="number" name="discounted_amount" id="discounted_amount" min='0' step='.01' value="<?php echo $row['purchase_price'] ?>"
                                           placeholder="<?php echo translate( 'discounted_amount' ); ?>"
                                           class="form-control decimal">
                                </div>
                                <span class="btn"><?php echo DEF_CURR;?> / </span>
                                <span class="btn unit_set"><?php echo $row['unit']; ?></span>
                            </div>
                        </div> 
                        
                       
                        <div id="variation" class="tab-pane fade">
                             <div class="form-group btm_border">
                                <h4 class="text-thin text-center"><?php echo translate( 'product_attribute' ); ?></h4>
                            </div>
                            
                            
                            
                            <div class="form-group btm_border product_attribute ">
                                <label class="col-sm-4 control-label" for="product_attributes"><?php echo translate('product_attribute');?></label>
                                <div class="col-sm-6">
                                    <select class="demo-chosen-select" name="product_attribute[]" id="product_attribute"  onchange="/*getattribute_value();*/">
                                        <option value="">Choose any one</option>
                                        <?php 
                                        $attr_ids = array();
                                        if(!empty($row['attribute_ids'])){
                                             $attr_ids = json_decode($row['attribute_ids']);
                                        }
                                       
                                        if(is_array($attribute_data)){
                                            foreach($attribute_data as $ak => $attr_val){
                                                $sel = '';
                                                if(is_array($attr_ids)){
                                                    if(in_array($attr_val['attribute_id'],$attr_ids)){
                                                        $sel = 'selected';
                                                    }
                                                }
                                           
                                        ?>
                                        
                                        <option value="<?php echo $attr_val['attribute_id']; ?>" <?php  echo $sel;  ?> ><?php echo $attr_val['attribute_name']; ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="form-group btm_border product_weight">
                                <label class="col-sm-4 control-label"
                                       for="demo-hor-wt"><?php echo translate( 'product_weight' ); ?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="product_weight" id="demo-hor-wt"
                                           placeholder="<?php echo translate( 'e.g 1 kg' ); ?>" value="<?php echo $row['weight']; ?>"
                                           class="form-control">
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>

                <span class="btn btn-purple btn-labeled fa fa-hand-o-right pull-right" onclick="next_tab()"><?php echo translate('next'); ?></span>
                <span class="btn btn-purple btn-labeled fa fa-hand-o-left pull-right" onclick="previous_tab()"><?php echo translate('previous'); ?></span>
        
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-11">
                    	<span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right" 
                            onclick="ajax_set_full('edit','<?php echo translate('edit_product'); ?>','<?php echo translate('successfully_edited!'); ?>','product_edit','<?php echo $row['product_id']; ?>') "><?php echo translate('reset');?>
                        </span>
                     </div>
                     <div class="col-md-1">
                     	<span class="btn btn-success btn-md btn-labeled fa fa-wrench pull-right enterer" onclick="form_submit('product_edit','<?php echo translate('successfully_edited!'); ?>');proceed('to_add');" ><?php echo translate('edit');?></span> 
                     </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
    }
?>
<!--Bootstrap Tags Input [ OPTIONAL ]-->
<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<script>
$(document).ready(function() {
  $('#demo-hor-10,#sale_price').on('input', function() {
    var salePrice = parseFloat($('#sale_price').val());
    var discount = parseFloat($('#demo-hor-10').val());

    if (discount > 99) {
      $('#demo-hor-10').val(99);
      discount = 99;
    }

    var discountedAmount = salePrice - (salePrice * discount / 100);
    var roundedDiscountedAmount = discountedAmount.toFixed(2);

    if (isNaN(roundedDiscountedAmount) || roundedDiscountedAmount < 0) {
      $('#discounted_amount').val(salePrice.toFixed(2));
    } else {
      $('#discounted_amount').val(roundedDiscountedAmount.toFixed(2));
    }
  });
});
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $('.decimal').on('input', function() {
            var value = $(this).val();
            if (value.includes('.')) {
                var parts = value.split('.');
                var integerPart = parts[0];
                var decimalPart = parts[1] || '';
                decimalPart = decimalPart.substring(0, 2);
                $(this).val(integerPart + '.' + decimalPart);
            }
        });
    });


    window.preview = function (input) {
        if (input.files && input.files[0]) {
            $("#previewImg").html('');
            $(input.files).each(function () {
                var reader = new FileReader();
                reader.readAsDataURL(this);
                reader.onload = function (e) {
                    $("#previewImg").append("<div style='float:left;border:4px solid #303641;padding:5px;margin:5px;'><img height='80' src='" + e.target.result + "'></div>");
                }
            });
        }
    }

     $('.delete-div-wrap .close').on('click', function() { 
	 	var pid = $(this).closest('.delete-div-wrap').find('img').data('id'); 
		var here = $(this); 
		msg = 'Really want to delete this Image?'; 
		bootbox.confirm(msg, function(result) {
			if (result) { 
				 $.ajax({ 
					url: base_url+''+user_type+'/'+module+'/dlt_img/'+pid, 
					cache: false, 
					success: function(data) { 
						$.activeitNoty({ 
							type: 'success', 
							icon : 'fa fa-check', 
							message : 'Deleted Successfully.', 
							container : 'floating', 
							timer : 3000 
						}); 
						here.closest('.delete-div-wrap').remove(); 
					} 
				}); 
			}else{ 
				$.activeitNoty({ 
					type: 'danger', 
					icon : 'fa fa-minus', 
					message : 'Cancelled', 
					container : 'floating', 
					timer : 3000 
				}); 
			}; 
		  }); 
		});

    function other_forms(){}
	
	function set_summer(){
        $('.summernotes').each(function() {
            var now = $(this);
            var h = now.data('height');
            var n = now.data('name');
			if(now.closest('div').find('.val').length == 0){
            	now.closest('div').append('<input type="hidden" class="val" name="'+n+'">');
			}
            now.summernote({
                height: h,
                onChange: function() {
                    now.closest('div').find('.val').val(now.code());
                }
            });
			now.closest('div').find('.val').val(now.code());
        });
	}

    function option_count(type){
        var count = $('#option_count').val();
        if(type == 'add'){
            count++;
        }
        if(type == 'reduce'){
            count--;
        }
        $('#option_count').val(count);
    }

    function set_select(){
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    }
    
    $(document).ready(function() {
        set_select();
        set_summer();
        createColorpickers();
    });

    function other(){
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
        $('#sub').show('slow');
    }
    function get_cat(id){
		$('#brn').hide('slow');
        $('#sub').hide('slow');
        ajax_load(base_url+'admin/product/sub_by_cat/'+id,'sub_cat','other');
    }
	function get_brnd(id){
        $('#brn').hide('slow');
        ajax_load(base_url+'admin/product/brand_by_sub/'+id,'brand','other');
        $('#brn').show('slow');
    }

    function get_sub_res(id){}

    $(".unit").on('keyup', function () {
        $(".unit_set").html($(".unit").val());
        $(".unit_link").html($(".unit").val());
    });

	
	function createColorpickers() {
	
		$('.demo2').colorpicker({
			format: 'rgba'
		});
		
	}
	
    
    $("#more_btn").click(function(){
        $("#more_additional_fields").append(''
            +'<div class="form-group">'
            +'    <div class="col-sm-4">'
            +'        <input type="text" name="ad_field_names[]" class="form-control"  placeholder="<?php echo translate('field_name'); ?>">'
            +'    </div>'
            +'    <div class="col-sm-5">'
            +'        <textarea rows="9"  class="summernotes" data-height="100" data-name="ad_field_values[]"></textarea>'
            +'    </div>'
            +'    <div class="col-sm-2">'
            +'        <span class="remove_it_v rms btn btn-danger btn-icon btn-circle icon-lg fa fa-times" onclick="delete_row(this)"></span>'
            +'    </div>'
            +'</div>'
        );
        set_summer();
    });
    
    
    $("#more_option_btn").click(function(){
        option_count('add');
        var co = $('#option_count').val();
        $("#more_additional_options").append(''
            +'<div class="form-group" data-no="'+co+'">'
            +'    <div class="col-sm-4">'
            +'        <input type="text" name="op_title[]" class="form-control required"  placeholder="<?php echo translate('customer_input_title'); ?>">'
            +'    </div>'
            +'    <div class="col-sm-5">'
            +'        <select class="demo-chosen-select op_type required" name="op_type[]" >'
            +'            <option value="">(none)</option>'
            +'            <option value="text">Text Input</option>'
            +'            <option value="single_select">Dropdown Single Select</option>'
            +'            <option value="multi_select">Dropdown Multi Select</option>'
            +'            <option value="radio">Radio</option>'
            +'        </select>'
            +'        <div class="col-sm-12 options">'
            +'          <input type="hidden" name="op_set'+co+'[]" value="none" >'
            +'        </div>'
            +'    </div>'
            +'    <input type="hidden" name="op_no[]" value="'+co+'" >'
            +'    <div class="col-sm-2">'
            +'        <span class="remove_it_o rmo btn btn-danger btn-icon btn-circle icon-lg fa fa-times" onclick="delete_row(this)"></span>'
            +'    </div>'
            +'</div>'
        );
        set_select();
    });
    
    $("#more_additional_options").on('change','.op_type',function(){
        var co = $(this).closest('.form-group').data('no');
        if($(this).val() !== 'text' && $(this).val() !== ''){
            $(this).closest('div').find(".options").html(''
                +'    <div class="col-sm-12">'
                +'        <div class="col-sm-12 options margin-bottom-10"></div><br>'
                +'        <div class="btn btn-mint btn-labeled fa fa-plus pull-right add_op">'
                +'        <?php echo translate('add_options_for_choice');?></div>'
                +'    </div>'
            );
        } else if ($(this).val() == 'text' || $(this).val() == ''){
            $(this).closest('div').find(".options").html(''
                +'    <input type="hidden" name="op_set'+co+'[]" value="none" >'
            );
        }
    });
    
    $("#more_additional_options").on('click','.add_op',function(){
        var co = $(this).closest('.form-group').data('no');
        $(this).closest('.col-sm-12').find(".options").append(''
            +'    <div>'
            +'        <div class="col-sm-10">'
            +'          <input type="text" name="op_set'+co+'[]" class="form-control required"  placeholder="<?php echo translate('option_name'); ?>">'
            +'        </div>'
            +'        <div class="col-sm-2">'
            +'          <span class="remove_it_n rmon btn btn-danger btn-icon btn-circle icon-sm fa fa-times" onclick="delete_row(this)"></span>'
            +'        </div>'
            +'    </div>'
        );
    });
    
    $('body').on('click', '.rmo', function(){
        $(this).parent().parent().remove();
    });

    function next_tab(){
        $('.nav-tabs li.active').next().find('a').click();                    
    }
    function previous_tab(){
        $('.nav-tabs li.active').prev().find('a').click();                     
    }
    
    $('body').on('click', '.rmon', function(){
        var co = $(this).closest('.form-group').data('no');
        $(this).parent().parent().remove();
        if($(this).parent().parent().parent().html() == ''){
            $(this).parent().parent().parent().html(''
                +'   <input type="hidden" name="op_set'+co+'[]" value="none" >'
            );
        }
    });

    
    $('body').on('click', '.rms', function(){
        $(this).parent().parent().remove();
    });


    $("#more_color_btn").click(function(){
        $("#more_colors").append(''
            +'      <div class="col-md-12" style="margin-bottom:8px;">'
            +'          <div class="col-md-8">'
            +'              <div class="input-group demo2">'
            +'                 <input type="text" value="#ccc" name="color[]" class="form-control" />'
            +'                 <span class="input-group-addon"><i></i></span>'
            +'              </div>'
            +'          </div>'
            +'          <span class="col-md-4">'
            +'              <span class="remove_it_v rmc btn btn-danger btn-icon btn-circle icon-lg fa fa-times" ></span>'
            +'          </span>'
            +'      </div>'
        );
        createColorpickers();
    });                

    $('body').on('click', '.rmc', function(){
        $(this).parent().parent().remove();
    });

	
    function delete_row(e)
    {
        e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
    }    
	
	
	$(document).ready(function() {
		$("form").submit(function(e){
			event.preventDefault();
		});
                //added by sagar : START 29-08
                var selectedVal =  $('#product_type').find(':selected').val();
                if(selectedVal == 'simple'){
                    $('#product_attribute').removeClass('required');
                    $('#product_weight').addClass('required');
                    $('.product_attribute').hide('slow');
                    $('.product_weight').show('slow');
                }else{
                    $('#product_weight').removeClass('required');
                    $('#product_attribute').addClass('required');
                    $('.product_attribute').show('slow');
                    $('.product_weight').hide('slow');
                }
              //added by sagar : END 29-08
         
        <?php // added by dev -- start ?>
        if ($('#product_type').val() == 'simple') {

            $('#product_attribute').removeClass('required');
            $('#simple_product_data').show();
            $('#sku_code').addClass('required');
            $('#sale_price').addClass('required');

            $('#demo-hor-5').addClass('required');
            $('.product_attribute').hide('slow');

        } else if ($('#product_type').val() == 'affiliate') {
            // $('#demo-hor-5').hide();
            $('#demo-hor-5').removeClass('required');
            $('#product_attribute').removeClass('required');
            $('#simple_product_data').show();
            $('#sku_code').removeClass('required');
            $('#sale_price').removeClass('required');

        } else {
            $('#demo-hor-5').addClass('required');
            $('#product_attribute').addClass('required');
            $('#simple_product_data').show();
            $('#sku_code').removeClass('required');
            $('#sale_price').removeClass('required');
            $('.product_weight').hide('slow');
        }
                
        <?php // added by dev -- End ?>
            
         <?php   if($is_offer == 'yes'){  ?>
                    $('.display_offer_date').show('slow');
         <?php   }  ?>
                
	});
   
    //added by sagar: FOR offer validity START 20-09-2019
    function offerValidity(val){
        if(val == 'yes'){
            $('.display_offer_date').show('slow');
        }else{
            $('.display_offer_date').hide('slow');
        }
    }
    //added by sagar: FOR offer validity END 20-09-2019
        
</script>
<style>
	.btm_border{
		border-bottom: 1px solid #ebebeb;
		padding-bottom: 15px;	
	}
</style>

