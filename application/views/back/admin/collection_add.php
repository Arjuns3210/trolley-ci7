        <!-- ---------------------- sample div for multiple ---------------------------- -->
        <div class="sample_div_multiple_class" style= "display:none">
            <div class="col-md-12 main_div_multiple mt-0" id="sample_div_multiple">
                <div class="row">
                    <div class="col-md-3 mt-1">
                        <input type="text" class="img_name_sample_class form-control" id="img_name_1" name="img_name[]" placeholder="Image Name"><br/>
                    </div>
                    <div class="col-md-3">
                        <input type="file" class="img_file_sample_class form-control" id="img_file_1" name="img_file[]" ><br/>
                    </div>
                    <div class="col-md-3">
                        <select class="select img_clickable_sample_class form-control" id="img_clickable_1" name="img_clickable[]" style="width: 100% !important;">
                            <option value = "1">Yes</option>
                            <option value = "0">No</option>
                        </select><br/><br>                                                        
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm" onclick="remove_multi_row(this);" ><i class="fa fa-trash fa-lg"></i></button><br/>
                    </div>
                </div>
            </div>
        </div>
    <!-- ----------------------- sample div for multiple --------------------------- -->
    <!-- ---------------------- sample div for category ---------------------------- -->
    <div class="sample_div_category_class" style= "display:none">
            <div class="col-md-12 main_div_category mt-0" id="sample_div_category">
                <div class="row">
                    <div class="col-md-3">
                    <?php // echo $this->crud_model->select_html('category','category','category_name','add','category_sample_class demo-chosen-select required'); ?>
                        <select class="select category_sample_class form-control category" id="category_1" name="category[]" style="width: 100% !important;">
                        <option value="">Select</option>
                        <?php  foreach($category_data as $categories){ ?>
                            <option value="<?php  echo $categories['category_id'] ?>"><?php  echo $categories['category_name'] ?></option>
                        <?php  } ?>
                        </select><br/><br>                                                        
                    </div>
                    <div class="col-md-3">
                        <select class="select sub_category_sample_class form-control sub_category" id="sub_category_1" name="sub_category[]" style="width: 100% !important;" multiple="multiple">
                        </select><br/><br>                                                        
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm" onclick="remove_cat_row(this);" ><i class="fa fa-trash fa-lg"></i></button><br/>
                    </div>
                </div>
            </div>
        </div>
    <!-- ----------------------- sample div for category --------------------------- -->

<div class="row">
    <div class="col-md-12">
		<?php
                
            echo form_open(base_url() . 'index.php/admin/collection/do_add/', array(
                'class' => 'form-horizontal',
                'method' => 'post',
                'id' => 'collection_add',
                'enctype' => 'multipart/form-data'
            ));
        ?>
            <!--Panel heading-->
            <div class="panel-heading">
                <div class="panel-control" style="float: left;">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#details"><?php echo translate( 'details' ); ?></a>
                        </li>
                        <li class="extra-details-li">
                            <a data-toggle="tab" href="#extra_details"><?php echo translate( 'extra_details' ); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="tab-base">
                    <!--Tabs Content-->
                    <div class="tab-content">
                        <div id="details" class="tab-pane fade active in">
                            <div class="form-group btm_border">
                                    <label class="col-sm-3 control-label" for="demo-hor-1"><?php echo translate('type');?></label>
                                    <div class="col-sm-5">
                                        <select class="collection_type form-control" id="collection_type" name="collection_type" style="width: 100% !important;">
                                            <option value="single">Single</option>
                                            <option value="multiple">Multiple</option>
                                            <!-- <option value="category">Category</option> -->
                                            <option value="product">Product</option>
                                            <option value="brands">Brands</option>
                                            <option value="label">Label</option>
                                        </select><br/><br>
                                    </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label" for="demo-hor-1"><?php echo translate('title_in_english');?></label>
                                <div class="col-sm-7">
                                    <input type="text" name="title" id="demo-hor-1" placeholder="<?php echo translate('title_in_english');?>" class="form-control required">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label" for="demo-hor-11"><?php echo translate('title_in_arabic');?></label>
                                <div class="col-sm-7">
                                    <input type="text" name="title_ar" id="demo-hor-11"  placeholder="<?php echo translate('title_in_arabic');?>" class="form-control required">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label"><?php echo translate('display_in_columns');?></label>
                                <div class="col-sm-3">
                                    <input type="number" name="display_in_columns"  placeholder="<?php echo translate('display_in_columns');?>" class="form-control required">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label"><?php echo translate('visible_on_home_page');?></label>
                                <div class="col-sm-7">
                                    <input type="checkbox" name="visible_on_home_page"  value="yes" placeholder="<?php echo translate('visible_on_home_page');?>">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label"><?php echo translate('visible_on_search_page');?></label>
                                <div class="col-sm-7">
                                    <input type="checkbox" name="visible_on_search_page"  value="yes" placeholder="<?php echo translate('visible_on_search_page');?>">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label"><?php echo translate('is_scrollable');?></label>
                                <div class="col-sm-7">
                                    <input type="checkbox" name="is_scrollable"  value="yes" placeholder="<?php echo translate('is_scrollable');?>">
                                </div>
                            </div>
                            <div class="showProductOption" style="display:none">
                                <div class="form-group btm_border">
                                        <label class="col-sm-3 control-label"
                                            for="products"><?php echo translate( 'product_for_collection' ); ?></label>
                                        <div class="col-sm-7">
                                            <select class="demo-chosen-select" name="product_for_collection[]" id="product_for_collection"
                                                    multiple="" >
                                                            <?php
                                                                if ( is_array( $product_data ) ) {

                                                                        foreach ( $product_data as $ak => $attr_val ) {
                                                                                $sel = '';
                                                                                ?>
                                                        <option value="<?php echo $attr_val['product_id']; ?>" <?php echo $sel; ?> ><?php echo $attr_val['title']; ?></option>
                                                                <?php }
                                                        } ?>
                                            </select>
                                        </div>
                                </div>
                            </div>

                            <div class="showBrandOption" style="display:none">
                                <div class="form-group btm_border">
                                    <label class="col-sm-3 control-label"
                                           for="collection"><?php echo translate('brand_for_collection');?></label>
                                    <div class="col-sm-7">
                                        <select class="demo-chosen-select" name="brand_for_collection[]" id="brand_for_collection"
                                                multiple="" >
                                            <?php
                                            if ( is_array( $brand_data ) ) {

                                                foreach ( $brand_data as $ak => $attr_val ) {
                                                    $sel = '';
                                                    ?>
                                                    <option value="<?php echo $attr_val['brand_id']; ?>" <?php echo $sel; ?> ><?php echo $attr_val['name']; ?></option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="form-group  btm_border">
                                <label class="col-sm-3 control-label" for="demo-hor-211"><?php echo translate('order_by_collection');?></label>
                                <div class="col-sm-3">
                                    <input type="number" name="order_by_collection" min="0" id="order_by_collection" class="form-control totals required" value="">
                                </div>
                            </div>
                        
                            <div class="form-group btm_border" style="display:none">
                                <label class="col-sm-3 control-label" for="demo-hor-1"><?php echo translate('is_offer');?></label>
                                <div class="col-sm-7">
                                    <input type="checkbox"  id="is-offer"  name="is_offer" value="yes" onchange="ShowHideDiv(this.id)" >
                                </div>
                            </div>
                            <div id="showImageOption" style="display:none">
                            <div class="form-group" >
                                <label class="col-sm-3 control-label" for="demo-hor-2">
                                    <?php echo translate('Image');?>
                                </label>
                                <div class="col-sm-7">
                                    <span class="pull-left btn btn-default btn-file">
                                        <?php echo translate('choose_banner Image');?>
                                        <input type="file" name="img" id='imgInp' accept="image">
                                    </span>
                                    <br><br>
                                    
                                    <span id='wrap' class="pull-left" >
                                        <img src="<?php echo base_url(); ?>uploads/collection_image/default.jpg" 
                                            width="50%" id='blah' > 
                                    </span>
                                </div>
                            </div>
                            </div>  
                        </div>
                        <div id="extra_details" class="tab-pane fade">
                                <!-- -------------------multiple_div start----------------------- -->
                                <div class="col-md-12 div_multiple">
                                    <div class="row" id="dummy_div_multiple">
                                        <div class="col-md-12 main_div_multiple mb-0">
                                            <div class="row">
                                                <div class="col-md-3 mt-1">
                                                    <label>Image Name</label>
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <label>Image Upload<span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <label>Is Clickable</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 mt-1">
                                                    <input type="text" class="form-control" id="img_name_1" name="img_name[]" placeholder="Image Name"><br/>
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <input type="file" class="form-control" id="img_file_1" name="img_file[]" ><br/>
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <select class="demo-cs-multiselect" id="img_clickable_1" name="img_clickable[]" style="width: 100% !important;">
                                                    <option value = "1">Yes</option>
                                                    <option value = "0">No</option>
                                                    </select><br/><br>
                                                </div>
                                                <div class="col-md-1 mt-1">
                                                    <a href="javascript:void(0);" onclick="add_multi_row();" class="btn btn-primary btn-sm"><i class="fa fa-plus fa-lg"></i></a><br/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- -------------------multiple_div end----------------------- -->
                                <!-- -------------------category_div start----------------------- -->
                                <div class="col-md-12 div_category">
                                    <div class="row" id="dummy_div_category">
                                        <div class="col-md-12 main_div_category mb-0">
                                            <div class="row">
                                                <div class="col-md-3 mt-1">
                                                    <label>Category<span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <label>SubCategory<span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 mt-1">
                                                    <?php //echo $this->crud_model->select_html('category','category','category_name','add','category_sample_class demo-chosen-select required'); ?>

                                                    <select class="demo-chosen-select category" id="category_1" name="category[]" style="width: 100% !important;">
                                                    <option value="">Select</option>
                                                    <?php  foreach($category_data as $categories){ ?>
                                                        <option value="<?php  echo $categories['category_id'] ?>"><?php  echo $categories['category_name'] ?></option>
                                                    <?php  } ?>
                                                    </select><br/><br>
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <select class="demo-cs-multiselect sub_category sub_category_1" id="sub_category_1" name="sub_category[]" style="width: 100% !important;" multiple="multiple">
                                                    </select><br/><br>
                                                </div>
                                                <div class="col-md-1 mt-1">
                                                    <a href="javascript:void(0);" onclick="add_cat_row();" class="btn btn-primary btn-sm"><i class="fa fa-plus fa-lg"></i></a><br/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- -------------------category_div end----------------------- -->
                        </div> 
                    </div>
                </div>  
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-11" style="padding-right: 40px;">
                        <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right " 
                            onclick="ajax_set_full('add','<?php echo translate('add_collection'); ?>','<?php echo translate('successfully_added!'); ?>','collection_add',''); "><?php echo translate('cancel');?>
                        </span>
                    </div>
                    
                    <div class="col-md-1">
                        <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right enterer" onclick="form_submit('collection_add','<?php echo translate('data_has_been_uploaded!'); ?>');proceed('to_add');" ><?php echo translate('submit');?></span>
                    </div>
                    
                </div>
            </div>
    
        </form>
    </div>
</div>

<script src="<?php $this->benchmark->mark_time(); echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js">
</script>

<input type="hidden" id="option_count" value="-1">

<script>

    

        function add_multi_row() 
        {
            var i = parseInt($('[id^=img_name_]:last').attr('id').substr(9)) + 1;
            $('.sample_div_multiple_class .img_name_sample_class').attr({
                'name': 'img_name[]',
                'id': 'img_name_' + i
            });
            $('.sample_div_multiple_class .img_file_sample_class').attr({
                'name': 'img_file[]',
                'id': 'img_file_' + i
            });
            $('.sample_div_multiple_class .img_clickable_sample_class').attr({
                'name': 'img_clickable[]',
                'id': 'img_clickable_' + i
            });

            var sampleClone = $('#sample_div_multiple').clone();
            sampleClone.find("span").remove();
            sampleClone.find("select").chosen(); 
            $('#dummy_div_multiple').append(sampleClone);
        }

        function remove_multi_row($this) {
            $($this).parents('.main_div_multiple').remove();
        }

        function add_cat_row() {
            var i = parseInt($('[id^=category_]:last').attr('id').substr(9)) + 1;

            var sampleClone = $('#sample_div_category').clone();
            sampleClone.find(".category_sample_class").attr({'name': 'category[]', 'id': 'category_' + i});
            sampleClone.find(".sub_category_sample_class").attr({'name': 'sub_category[]', 'id': 'sub_category_' + i, 'class': 'demo-cs-multiselect sub_category_sample_class form-control sub_category_' + i});

            sampleClone.find('.demo-chosen-select').chosen();
            sampleClone.find('.demo-cs-multiselect').chosen({width: '100%'});

            $('#dummy_div_category').append(sampleClone);
        }

        function remove_cat_row($this) {
            $($this).parents('.main_div_category').remove();
        }

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


    function set_select(){
        $('.demo-chosen-select').each(function() {
            var chosenMulti = $(this).chosen().data('chosen');
            var autoCloseMulti = false;
            var chosen_resultSelect_fnMulti = chosenMulti.result_select;
            chosenMulti.search_contains = true;
            chosenMulti.result_select = function(evt) {
                var resultHighlightMulti = null;
                if (autoCloseMulti === false) {
                    evt['metaKey'] = true;
                    evt['ctrlKey'] = true;
                    resultHighlightMulti = chosenMulti.result_highlight;
                }
                var stextMulti = chosenMulti.get_search_text();
                var resultMulti = chosen_resultSelect_fnMulti.call(chosenMulti, evt);
                if (autoCloseMulti === false && resultHighlightMulti !== null) {
                    resultHighlightMulti.addClass('result-selected');
                }

                this.search_field.val(stextMulti);
                this.winnow_results();
                this.search_field_scale();

                return resultMulti;
            };
        });

        $('.demo-cs-multiselect').each(function() {
            var chosenMulti = $(this).chosen().data('chosen');
            var autoCloseMulti = false;
            var chosen_resultSelect_fnMulti = chosenMulti.result_select;
            chosenMulti.search_contains = true;
            chosenMulti.result_select = function(evt) {
                var resultHighlightMulti = null;
                if (autoCloseMulti === false) {
                    evt['metaKey'] = true;
                    evt['ctrlKey'] = true;
                    resultHighlightMulti = chosenMulti.result_highlight;
                }
                var stextMulti = chosenMulti.get_search_text();
                var resultMulti = chosen_resultSelect_fnMulti.call(chosenMulti, evt);
                if (autoCloseMulti === false && resultHighlightMulti !== null) {
                    resultHighlightMulti.addClass('result-selected');
                }

                this.search_field.val(stextMulti);
                this.winnow_results();
                this.search_field_scale();

                return resultMulti;
            };
        });
    }
	
    $(document).ready(function() {
        set_select();
    });
    
    
    function ShowHideDiv(id){
        if($("#"+id).is(":checked")){  
           $('#showImageOption').show('slow');
        }else{
            $('#showImageOption').hide('slow');
        }
    }



    $(document).ready(function() {
            $("form").submit(function(e){
                    event.preventDefault();
            });

        $(document).on('change', '.category', function(e) {
            var idCategoryName = e.target.id; // category_2
            var idCategory = e.target.value; // 1

            var subCategoryDropdown = $('.sub_' + idCategoryName);
            subCategoryDropdown.empty(); // Clear existing options

            $.ajax({
                url: "<?php echo base_url('admin/get_subcat_ids/'); ?>",
                type: "POST",
                data: {
                    category_id: idCategory,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(result) {
                    $.each(result, function(key, value) {
                        subCategoryDropdown.append('<option value="' + value.sub_category_id + '">' + value.sub_category_name + '</option>');
                    });

                    subCategoryDropdown.trigger("chosen:updated");
                    var chosenMulti = $('.sub_' + idCategoryName).chosen().data('chosen');
                    var autoCloseMulti = false;
                    var chosen_resultSelect_fnMulti = chosenMulti.result_select;
                    chosenMulti.search_contains = true;
                    chosenMulti.result_select = function(evt) {
                        var resultHighlightMulti = null;
                        if (autoCloseMulti === false) {
                            evt['metaKey'] = true;
                            evt['ctrlKey'] = true;
                            resultHighlightMulti = chosenMulti.result_highlight;
                        }
                        var stextMulti = chosenMulti.get_search_text();
                        var resultMulti = chosen_resultSelect_fnMulti.call(chosenMulti, evt);
                        if (autoCloseMulti === false && resultHighlightMulti !== null)
                            resultHighlightMulti.addClass('result-selected');

                        this.search_field.val(stextMulti);
                        this.winnow_results();
                        this.search_field_scale();

                        return resultMulti;
                    };

                }
            });
        });



        $(".div_multiple").hide();
            $(".div_category").hide();
            $(".extra-details-li").hide();
            $(".showProductOption").hide();
            $(".showBrandOption").hide();
        
            if ($("#collection_type").val() == 'single'){
                $('#is-offer').prop('checked', true).trigger('change');
                $('#is-offer').prop('readonly', true).trigger('change');
                // $('#imgInp').addClass('required');    
            }
            $("#collection_type").change(function(){
            if ($("#collection_type").val() == 'single'){
                $(".div_category").hide();
                $(".div_multiple").hide();
                $(".div_product").hide();
                $(".extra-details-li").hide();
                $(".showProductOption").hide('slow');
                $(".showBrandOption").hide('slow');
                $(".div_single").show();
                $('#is-offer').prop('checked', true).trigger('change');
                $('#is-offer').prop('readonly', true).trigger('change');
                // $('#imgInp').addClass('required');
            };

            if ($("#collection_type").val() == 'multiple'){
                $(".div_single").hide();
                $(".div_category").hide();
                $(".div_product").hide();
                $(".showProductOption").hide('slow');
                $(".showBrandOption").hide('slow');
                $(".extra-details-li").show();                
                $(".div_multiple").show();
                $('#is-offer').prop('checked', false).trigger('change');
                // $('#imgInp').removeClass('required');
            };

            if ($("#collection_type").val() == 'category'){
                $(".div_single").hide();
                $(".div_multiple").hide();
                $(".div_product").hide();
                $(".showProductOption").hide('slow');
                $(".showBrandOption").hide('slow');
                $(".extra-details-li").show();                
                $(".div_category").show();
                $('#is-offer').prop('checked', false).trigger('change');
                // $('#imgInp').removeClass('required');
            };

            if ($("#collection_type").val() == 'product'){
                // $(".div_multiple").hide();
                // $(".div_category").hide();
                // $(".div_product").show();
                // $(".extra-details-li").show();                
                $(".extra-details-li").hide();                
                $(".showProductOption").show('slow');
                $(".showBrandOption").hide('slow');
                $('#is-offer').prop('checked', false).trigger('change');
                // $('#imgInp').removeClass('required');
            };
            if ($("#collection_type").val() == 'brands'){
                $(".extra-details-li").hide();
                $(".showProductOption").hide('slow');
                $(".showBrandOption").show('slow');
                $('#is-offer').prop('checked', false).trigger('change');
                // $('#imgInp').removeClass('required');
            };
                if ($("#collection_type").val() == 'label'){
                    $(".showProductOption").hide('slow');
                    $(".showBrandOption").hide('slow');
                    $('#is-offer').prop('checked', false).trigger('change');
                    // $('#imgInp').removeClass('required');
                };

        });
    });
</script>

<style>
	.btm_border{
		border-bottom: 1px solid #ebebeb;
		padding-bottom: 15px;	
	}
</style>


<!--Bootstrap Tags Input [ OPTIONAL ]-->

