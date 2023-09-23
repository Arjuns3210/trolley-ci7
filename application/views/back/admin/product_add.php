<div class="row">
    <div class="col-md-12">
		<?php
		echo form_open( base_url() . 'admin/product/do_add/', array(
			'class'   => 'form-horizontal',
			'method'  => 'post',
			'id'      => 'product_add',
			'enctype' => 'multipart/form-data'
		) );
		?>
        <!--Panel heading-->
        <div class="panel-heading">
            <div class="panel-control" style="float: left;">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#product_details"><?php echo translate( 'product_details' ); ?></a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#business_details"><?php echo translate( 'business_details' ); ?></a>
                    </li>
                  
                    <li>
                        <a data-toggle="tab" href="#variation"><?php echo translate( 'product_attributes' ); ?></a>
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
								<?php echo translate( 'product_type' ); ?>
                            </label>
                            <div class="col-sm-6">
                                <select name="product_type" id="product_type" onchange="showProductAttribute();" class="form-control required">
                                    <option value="simple">Simple Product</option>
                                    
                                </select>
                            </div>
                            
                        </div>
                        <?php /* <input type="hidden" name="product_type" id="product_type" value="simple"> */ ?>
                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="demo-hor-1"><?php echo translate( 'product_title_in_english' ); ?></label>
                            <div class="col-sm-6">
                                <input type="text" name="title" id="demo-hor-1"
                                       placeholder="<?php echo translate( 'product_title_in_english' ); ?>"
                                       class="form-control required">
                            </div>
                        </div>
                        <?php // added by sagar  : 16-08 START ?>
                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="demo-hor-1"><?php echo translate( 'product_title_in_arabic' ); ?></label>
                            <div class="col-sm-6">
                                <input type="text" name="title_ar" id="demo-hor-1"
                                       placeholder="<?php echo translate( 'product_title_in_arabic' ); ?>"
                                       class="form-control required">
                            </div>
                        </div>
                         <?php // added by sagar  : 16-08 END ?>
                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="demo-hor-46"><?php echo translate( 'product_code' ); ?></label>
                            <div class="col-sm-6">
                                <input type="text" name="product_code" id="demo-hor-46"
                                       placeholder="<?php echo translate( 'product_code' ); ?>"
                                       class="form-control required">
                            </div>
                        </div>
                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="demo-hor-2"><?php echo translate( 'category' ); ?></label>
                            <div class="col-sm-6">
								<?php // echo $this->crud_model->select_html( 'category', 'category', 'category_name', 'add', 'demo-chosen-select required', '', 'digital', null, 'get_cat' ); ?>
								<?php echo $this->crud_model->select_html( 'category', 'category', 'category_name', 'add', 'demo-chosen-select required', '', '', '', 'get_cat' ); ?>
                            </div>
                        </div>

                        <div class="form-group btm_border" id="sub" style="display:none;">
                            <label class="col-sm-4 control-label"
                                   for="demo-hor-3"><?php echo translate( 'sub-category' ); ?></label>
                            <div class="col-sm-6" id="sub_cat">
                            </div>
                        </div>

                        <div class="form-group btm_border" id="brn" style="display:none;">
                            <label class="col-sm-4 control-label"
                                   for="demo-hor-4"><?php echo translate( 'brand' ); ?></label>
                            <div class="col-sm-6" id="brand">
                            </div>
                        </div>

                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                for="products"><?php echo translate( 'similar_product' ); ?></label>
                            <div class="col-sm-6">
                                <select class="demo-chosen-select" name="similar_product[]" id="similar_product"
                                        multiple="" >
                                                <?php
                                                    if ( is_array( $product_data ) ) {

                                                            foreach ( $product_data as $val ) {
                                                                    ?>
                                            <option value="<?php echo $val['product_id']; ?>" ><?php echo $val['title']; ?></option>
                                                    <?php }
                                            } ?>
                                </select>
                            </div>
                        </div>

                        <?php //added by sagar : START ON 29-07 ?>
                        <div class="form-group btm_border">
                             <label class="col-sm-4 control-label"
                                   for="demo-hor-offer"><?php echo translate( 'is_offer' ); ?></label>
                            <div class="col-sm-6">
                                <input type="radio" style="display: inline;margin-right: 10px;" name="is_offer" id="o1" value="yes" onchange="offerValidity(this.value);" ><label for="o1">Yes</label>
                                <input type="radio"  style="display: inline;margin-left: 20px;" name="is_offer" id="o2" value="no"  onchange="offerValidity(this.value);" ><label style="margin-left: 10px;" for="o2">No</label>
                            </div>
                        </div>
                        <div class="form-group btm_border display_offer_date" style="display:none;">
                            <label class="col-sm-4 control-label"
                                   for="demo-offer_validity"><?php echo translate( 'offer_validity' ); ?></label>
                            <div class="col-sm-6">
                                <input type="date" name="offer_validity" id="demo-offer_validity"  class="form-control">
                            </div>
                        </div>
                        <?php //added by sagar : END ON 29-07 ?>
                        
                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="demo-hor-5"><?php echo translate( 'unit' ); ?></label>
                            <div class="col-sm-6">
                                <input type="text" name="unit" id="demo-hor-5"
                                       placeholder="<?php echo translate( 'unit_(e.g._kg,_pc_etc.)' ); ?>"
                                       class="form-control unit required">
                            </div>
                        </div>
                      
                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="demo-hor-12"><?php echo translate( 'images' ); ?></label>
                            <div class="col-sm-6">
                                <span class="pull-left btn btn-default btn-file"> <?php echo translate( 'choose_file' ); ?>
                                    <input type="file" multiple name="images[]" onchange="preview(this);"
                                           id="demo-hor-12" class="form-control">
                                    </span>
                                <br><br>
                                <p style="color:blue;font-weight: bold">Note : Upload file size <?php echo SIZE_DIMENSIONS['product']; ?></p>
                                <span id="previewImg"></span>
                            </div>
                        </div>


                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="demo-hor-13"><?php echo translate( 'description_in_english' ); ?></label>
                            <div class="col-sm-6">
                                <textarea rows="9" class="summernotes" data-height="200" data-name="description"></textarea>
                            </div>
                        </div>
                         <?php // added by sagar  : 16-08 START ?>
                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="demo-hor-13"><?php echo translate( 'description_in_arabic' ); ?></label>
                            <div class="col-sm-6">
                                <textarea rows="9" class="summernotes" data-height="200" data-name="description_ar"></textarea>
                            </div>
                        </div>
                         <?php // added by sagar  : 16-08 EMD ?>
                    </div>
                    <div id="business_details" class="tab-pane fade">
                        <div class="form-group btm_border">
                            <h4 class="text-thin text-center"><?php echo translate( 'business_details' ); ?></h4>
                        </div>

                        <span id="simple_product_data">
                                <div class="form-group btm_border">
                                    <label class="col-sm-4 control-label"
                                           for="sku_code"><?php echo translate( 'sku_code' ); ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="sku_code" id="sku_code" value="<?php ?>"
                                               placeholder="<?php echo translate( 'sku_code' ); ?>"
                                               class="form-control required">
                                    </div>
                                </div>

                        </span>
                        
                        <div class="form-group ">
                                <label class="col-sm-4 control-label"
                                       for="demo-hor-supplier"><?php echo translate( 'supplier' ); ?></label>
                                <div class="col-sm-4">
                                    <?php echo $this->crud_model->select_html( 'supplier', 'supplier', 'supplier_name|mobile_number|company_name', 'add', 'demo-chosen-select required', '', '', null, '' ); ?>
                                </div>
                            
                        </div>
                        
                         <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="supplier_price"><?php echo translate( 'supplier_sale_price' ); ?></label>
                            <div class="col-sm-4">
                                <input type="number" name="supplier_price" id="supplier_price" min='0' step='.01'
                                       placeholder="<?php echo translate( 'supplier_sale_price' ); ?>"
                                       class="form-control required">
                            </div>
                            <span class="btn"><?php echo DEF_CURR; ?> / </span>
                            <span class="btn unit_set"></span>
                        </div>
                                
                        
                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="sale_price"><?php echo translate( 'app_sale_price' ); ?></label>
                            <div class="col-sm-4">
                                <input type="number" name="sale_price" id="sale_price" min='0' step='.01'
                                       placeholder="<?php echo translate( 'sale_price' ); ?>"
                                       class="form-control required decimal">
                            </div>
                            <span class="btn"><?php echo DEF_CURR;//echo currency( '', 'def' ); ?> / </span>
                            <span class="btn unit_set"></span>
                        </div>
                        
                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="demo-hor-10"><?php echo translate( 'product_discount' ); ?></label>
                            <div class="col-sm-4">
                                <input type="number" name="discount" id="demo-hor-10" min='0' step='.01' value="0"
                                       placeholder="<?php echo translate( 'product_discount' ); ?>"
                                       class="form-control">
                            </div>
                            <span class="btn">%</span>
                        </div>
                        <div class="form-group btm_border">
                            <label class="col-sm-4 control-label"
                                   for="discounted_amount"><?php echo translate( 'final_selling_price' ); ?></label>
                            <div class="col-sm-4">
                                <input type="number" name="discounted_amount" id="discounted_amount" min='0' step='.01'
                                       placeholder="<?php echo translate( 'discounted_amount' ); ?>"
                                       class="form-control decimal">
                            </div>
                            <span class="btn"><?php echo DEF_CURR;//echo currency( '', 'def' ); ?> / </span>
                            <span class="btn unit_set"></span>
                        </div>
                        
                    </div>
              
                    <div id="variation" class="tab-pane fade">
                         <div class="form-group btm_border">
                            <h4 class="text-thin text-center"><?php echo translate( 'product_attribute' ); ?></h4>
                        </div> 
                        
                       
                        
                        <div class="form-group btm_border product_attribute">
                            <label class="col-sm-4 control-label"
                                   for="product_attributes"><?php echo translate( 'product_attribute' ); ?></label>
                            <div class="col-sm-6">
                                <select class="demo-chosen-select" name="product_attribute[]" id="product_attribute">
                                    <option value="">Choose one</option>
									<?php
									if ( is_array( $attribute_data ) ) {
										foreach ( $attribute_data as $ak => $attr_val ) {
											$sel = '';

											?>
                                            <option value="<?php echo $attr_val['attribute_id']; ?>" <?php echo $sel; ?> ><?php echo $attr_val['attribute_name']; ?></option>
										<?php }
									} ?>
                                </select>
                                
                            </div>
                        </div>
                        
                       
                        
                        <div class="form-group btm_border product_weight">
                            <label class="col-sm-4 control-label"
                                   for="product_weight"><?php echo translate( 'product_weight' ); ?></label>
                            <div class="col-sm-6">
                                <input type="text" id="product_weight" name="product_weight" 
                                       placeholder="<?php echo translate( 'e.g 1 kg' ); ?>"
                                       class="form-control ">
                            </div>
                        </div>

                        
                    </div>
                  
                </div>
            </div>

            <span class="btn btn-purple btn-labeled fa fa-hand-o-right pull-right"
                  onclick="next_tab()"><?php echo translate( 'next' ); ?></span>
            <span class="btn btn-purple btn-labeled fa fa-hand-o-left pull-right"
                  onclick="previous_tab()"><?php echo translate( 'previous' ); ?></span>

        </div>

        <div class="panel-footer">
            <div class="row">
                <div class="col-md-11">
                        <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right "
                              onclick="ajax_set_full('add','<?php echo translate( 'add_product' ); ?>','<?php echo translate( 'successfully_added!' ); ?>','product_add',''); "><?php echo translate( 'reset' ); ?>
                        </span>
                </div>

                <div class="col-md-1">
                    <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right enterer"
                          onclick="form_submit('product_add','<?php echo translate( 'product_has_been_uploaded!' ); ?>');proceed('to_add');"><?php echo translate( 'upload' ); ?></span>
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

<script>
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

//     function getattribute_value(){
//        $.ajax({
//                url: "<?php //echo base_url(); ?>admin/product/attribute_value_list", 
//                data:{'<?php //echo $this->security->get_csrf_token_name(); ?>':'<?php //echo $this->security->get_csrf_hash(); ?>','attribute':$('#product_attribute').val()},
//                type:'post',
//                dataType:'json',
//                success: function(result){
//                
//                }
//        });
//        
//    }
    
    
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

    function other_forms() {
    }

    function set_summer() {
        $('.summernotes').each(function () {
            var now = $(this);
            var h = now.data('height');
            var n = now.data('name');
            if (now.closest('div').find('.val').length == 0) {
                now.closest('div').append('<input type="hidden" class="val" name="' + n + '">');
            }
            now.summernote({
                height: h,
                onChange: function () {
                    now.closest('div').find('.val').val(now.code());
                }
            });
            now.closest('div').find('.val').val(now.code());
        });
    }

    function option_count(type) {
        var count = $('#option_count').val();
        if (type == 'add') {
            count++;
        }
        if (type == 'reduce') {
            count--;
        }
        $('#option_count').val(count);
    }

    function set_select() {
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width: '100%'});
    }

    $(document).ready(function () {
        set_select();
        set_summer();
        createColorpickers();
    });

    function other() {
        set_select();
        $('#sub').show('slow');
    }

    function get_cat(id, now) {
        $('#sub').hide('slow');
        ajax_load(base_url + 'admin/product/sub_by_cat/' + id, 'sub_cat', 'other');
    }

    function get_brnd(id) {
        $('#brn').hide('slow');
        ajax_load(base_url + 'admin/product/brand_by_sub/' + id, 'brand', 'other');
        $('#brn').show('slow');
    }

    function get_sub_res(id) {
    }

    $(".unit").on('keyup', function () {
        $(".unit_set").html($(".unit").val());
        $(".unit_link").html($(".unit").val());
    });
    

    function createColorpickers() {

        $('.demo2').colorpicker({
            format: 'rgba'
        });

    }

    $("#more_btn").click(function () {
        $("#more_additional_fields").append(''
            + '<div class="form-group">'
            + '    <div class="col-sm-4">'
            + '        <input type="text" name="ad_field_names[]" class="form-control required"  placeholder="<?php echo translate( 'field_name' ); ?>">'
            + '    </div>'
            + '    <div class="col-sm-5">'
            + '        <textarea rows="9"  class="summernotes" data-height="100" data-name="ad_field_values[]"></textarea>'
            + '    </div>'
            + '    <div class="col-sm-2">'
            + '        <span class="remove_it_v rms btn btn-danger btn-icon btn-circle icon-lg fa fa-times" onclick="delete_row(this)"></span>'
            + '    </div>'
            + '</div>'
        );
        set_summer();
    });

    function next_tab() {
        $('.nav-tabs li.active').next().find('a').click();
    }

    function previous_tab() {
        $('.nav-tabs li.active').prev().find('a').click();
    }

    $("#more_option_btn").click(function () {
        option_count('add');
        var co = $('#option_count').val();
        $("#more_additional_options").append(''
            + '<div class="form-group" data-no="' + co + '">'
            + '    <div class="col-sm-4">'
            + '        <input type="text" name="op_title[]" class="form-control required"  placeholder="<?php echo translate( 'customer_input_title' ); ?>">'
            + '    </div>'
            + '    <div class="col-sm-5">'
            + '        <select class="demo-chosen-select op_type required" name="op_type[]" >'
            + '            <option value="">(none)</option>'
            + '            <option value="text">Text Input</option>'
            + '            <option value="single_select">Dropdown Single Select</option>'
            + '            <option value="multi_select">Dropdown Multi Select</option>'
            + '            <option value="radio">Radio</option>'
            + '        </select>'
            + '        <div class="col-sm-12 options">'
            + '          <input type="hidden" name="op_set' + co + '[]" value="none" >'
            + '        </div>'
            + '    </div>'
            + '    <input type="hidden" name="op_no[]" value="' + co + '" >'
            + '    <div class="col-sm-2">'
            + '        <span class="remove_it_o rmo btn btn-danger btn-icon btn-circle icon-lg fa fa-times" onclick="delete_row(this)"></span>'
            + '    </div>'
            + '</div>'
        );
        set_select();
    });

    $("#more_additional_options").on('change', '.op_type', function () {
        var co = $(this).closest('.form-group').data('no');
        if ($(this).val() !== 'text' && $(this).val() !== '') {
            $(this).closest('div').find(".options").html(''
                + '    <div class="col-sm-12">'
                + '        <div class="col-sm-12 options margin-bottom-10"></div><br>'
                + '        <div class="btn btn-mint btn-labeled fa fa-plus pull-right add_op">'
                + '        <?php echo translate( 'add_options_for_choice' );?></div>'
                + '    </div>'
            );
        } else if ($(this).val() == 'text' || $(this).val() == '') {
            $(this).closest('div').find(".options").html(''
                + '    <input type="hidden" name="op_set' + co + '[]" value="none" >'
            );
        }
    });

    $("#more_additional_options").on('click', '.add_op', function () {
        var co = $(this).closest('.form-group').data('no');
        $(this).closest('.col-sm-12').find(".options").append(''
            + '    <div>'
            + '        <div class="col-sm-10">'
            + '          <input type="text" name="op_set' + co + '[]" class="form-control required"  placeholder="<?php echo translate( 'option_name' ); ?>">'
            + '        </div>'
            + '        <div class="col-sm-2">'
            + '          <span class="remove_it_n rmon btn btn-danger btn-icon btn-circle icon-sm fa fa-times" onclick="delete_row(this)"></span>'
            + '        </div>'
            + '    </div>'
        );
    });

    $('body').on('click', '.rmo', function () {
        $(this).parent().parent().remove();
    });

    $('body').on('click', '.rmon', function () {
        var co = $(this).closest('.form-group').data('no');
        $(this).parent().parent().remove();
        if ($(this).parent().parent().parent().html() == '') {
            $(this).parent().parent().parent().html(''
                + '   <input type="hidden" name="op_set' + co + '[]" value="none" >'
            );
        }
    });

    $('body').on('click', '.rms', function () {
        $(this).parent().parent().remove();
    });

    $("#more_color_btn").click(function () {
        $("#more_colors").append(''
            + '      <div class="col-md-12" style="margin-bottom:8px;">'
            + '          <div class="col-md-10">'
            + '              <div class="input-group demo2">'
            + '		     	   <input type="text" value="#ccc" name="color[]" class="form-control" />'
            + '		     	   <span class="input-group-addon"><i></i></span>'
            + '		        </div>'
            + '          </div>'
            + '          <span class="col-md-2">'
            + '              <span class="remove_it_v rmc btn btn-danger btn-icon icon-lg fa fa-trash" ></span>'
            + '          </span>'
            + '      </div>'
        );
        createColorpickers();
    });

    $('body').on('click', '.rmc', function () {
        $(this).parent().parent().remove();
    });


    $(document).ready(function () {
        
        $("form").submit(function (e) {
            event.preventDefault();
        });
		<?php // added by dev -- start ?>
        if ($('#product_type').val() == 'simple') {

            $('#product_attribute').removeClass('required');
            $('#simple_product_data').show();
            $('#sku_code').addClass('required');
            $('#sale_price').addClass('required');
            $('#demo-hor-5').addClass('required');
            $('#product_weight').addClass('required');

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
            $('#simple_product_data').hide();
            $('#sku_code').removeClass('required');
            $('#sale_price').removeClass('required');
            $('#product_weight').removeClass('required');
        }
        $('.product_attribute').hide('slow');
        <?php // added by dev -- End ?>
    });
    
    //added by sagar : START 29-08
    function showProductAttribute(){
       var selectedVal =  $('#product_type').find(':selected').val();
       console.log(selectedVal);
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
    }
    //added by sagar : START 29-08
    
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
    .btm_border {
        border-bottom: 1px solid #ebebeb;
        padding-bottom: 15px;
    }
</style>


<!--Bootstrap Tags Input [ OPTIONAL ]-->

