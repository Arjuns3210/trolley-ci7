<?php
    foreach($product_data as $row){
?>
<div class="row">
    <div class="col-md-12">

                <!-- ---------------------- sample div for multiple start ---------------------------- -->
                <div class="sample_div_multiple_class" style= "display:none">
                    <div class="col-md-12 main_div_multiple mt-0" id="sample_div_multiple">
                        <div class="row">
                            <div class="col-md-3 mt-1">
                                <input type="text" class="img_name_sample_class form-control" id="img_name_1" name="img_name[]" placeholder="Image Name"><br/>
                            </div>
                            <div class="col-md-3">
                                <input type="file" class="img_file_sample_class form-control" id="img_file_1" name="img_file[]" ><br/>
                                <input type="hidden" name="extra_id[]" value="">
                            </div>
                            <div class="col-md-2">
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
            <!-- ----------------------- sample div for multiple end--------------------------- --> 
            <!-- ---------------------- sample div for category start ---------------------------- -->
                <div class="sample_div_category_class" style= "display:none">
                    <div class="col-md-12 main_div_category mt-0" id="sample_div_category">
                        <div class="row">
                            <div class="col-md-3">
                                <select class=" category_sample_class form-control category" id="category_1" name="category[]" style="width: 100% !important;">
                                <option value="">Select</option>
                                <?php foreach($category_all as $categories){ ?>
                                    <option value="<?php echo $categories['category_id'] ?>"><?php echo $categories['category_name'] ?></option>
                                <?php  } ?>
                                </select><br/><br>                                                        
                            </div>
                            <div class="col-md-3">
                                <select class=" sub_category_sample_class form-control sub_category" id="sub_category_1" name="sub_category[]" style="width: 100% !important;" multiple="multiple">
                                </select><br/><br>                                                        
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm" onclick="remove_cat_row(this);" ><i class="fa fa-trash fa-lg"></i></button><br/>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- ----------------------- sample div for category end--------------------------- -->
        <?php
			echo form_open(base_url() . 'index.php/admin/collection/update/' . $row['collection_id'], array(
				'class' => 'form-horizontal',
				'method' => 'post',
				'id' => 'collection_edit',
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
                        <?php if ($row['type'] == "multiple" || $row['type'] == "category") { ?>
                        <li class="extra-details-li">
                            <a data-toggle="tab" href="#extra_details"><?php echo translate( 'extra_details' ); ?></a>
                        </li>
                        <?php  } ?>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="tab-base">
                    <div class="tab-content">
                        <div id="details" class="tab-pane fade in active">
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label" for="demo-hor-1"><?php echo translate('type');?></label>
                                <div class="col-sm-5">
                                    <select class="collection_type form-control" id="collection_type" name="collection_type" style="width: 100% !important;">
                                        <option value="<?php echo $row['type']?>"><?php echo ucfirst($row['type']) ?></option>
                                    </select><br/><br>
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label" for="demo-hor-1"><?php echo translate('title_in_english');?></label>
                                <div class="col-sm-7">
                                    <input type="text" name="title" id="demo-hor-1" value="<?php echo $row['title']; ?>" placeholder="<?php echo translate('title_in_english');?>" class="form-control required">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label" for="demo-hor-11"><?php echo translate('title_in_arabic');?></label>
                                <div class="col-sm-7">
                                    <input type="text" name="title_ar" id="demo-hor-11"  value="<?php echo $row['title_ar']; ?>" placeholder="<?php echo translate('title_in_arabic');?>" class="form-control required">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label"><?php echo translate('display_in_columns');?></label>
                                <div class="col-sm-3">
                                    <input type="number" name="display_in_columns"  value="<?php echo $row['display_in_columns']; ?>" placeholder="<?php echo translate('display_in_columns');?>" class="form-control required">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label"><?php echo translate('visible_on_home_page');?></label>
                                <div class="col-sm-7">
                                    <input type="checkbox" name="visible_on_home_page" <?php if(!empty($row['visible_on_home_page']) && $row['visible_on_home_page'] == 'yes') { echo 'checked="checked"'; } ?> value="yes" placeholder="<?php echo translate('visible_on_home_page');?>">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label"><?php echo translate('visible_on_search_page');?></label>
                                <div class="col-sm-7">
                                    <input type="checkbox" name="visible_on_search_page" <?php if(!empty($row['visible_on_search_page']) && $row['visible_on_search_page'] == 'yes') { echo 'checked="checked"'; } ?> value="yes" placeholder="<?php echo translate('visible_on_search_page');?>">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label"><?php echo translate('is_scrollable');?></label>
                                <div class="col-sm-7">
                                    <input type="checkbox" name="is_scrollable" <?php if(!empty($row['is_scrollable']) && $row['is_scrollable'] == 'yes') { echo 'checked="checked"'; } ?> value="yes" placeholder="<?php echo translate('is_scrollable');?>">
                                </div>
                            </div>
                            <?php  if($row['type'] == 'brands') { ?>
                                <div class="form-group btm_border">
                                    <label class="col-sm-3 control-label" for="brand_for_collection"><?php echo translate('brand_for_collection');?></label>
                                    <div class="col-sm-7">
                                        <select class="demo-chosen-select" name="brand_for_collection[]" id="brand_for_collection" multiple="" >
                                            <?php
                                            $brand_ids = array();
                                            if(!empty($row['brand_for_collection'])){
                                                $brand_ids = json_decode($row['brand_for_collection']);
                                            }

                                            if(is_array($main_brand_data)){
                                                foreach($main_brand_data as $ak => $val){
                                                    $sel = '';
                                                    if(is_array($brand_ids)){
                                                        if(in_array($val['brand_id'],$brand_ids)){
                                                            $sel = 'selected';
                                                        }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $val['brand_id']; ?>" <?php  echo $sel;  ?> ><?php echo $val['name']; ?></option>
                                                <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php  if($row['type'] == 'product') { ?>
                                <div class="form-group btm_border">
                                        <label class="col-sm-3 control-label" for="product_for_collection"><?php echo translate('product_for_collection');?></label>
                                        <div class="col-sm-7">
                                            <select class="demo-chosen-select" name="product_for_collection[]" id="product_for_collection" multiple="" >
                                                <?php 
                                                $product_ids = array();
                                                if(!empty($row['product_for_collection'])){
                                                        $product_ids = json_decode($row['product_for_collection']);
                                                }
                                                
                                                if(is_array($main_product_data)){
                                                    foreach($main_product_data as $ak => $val){
                                                        $sel = '';
                                                        if(is_array($product_ids)){
                                                            if(in_array($val['product_id'],$product_ids)){
                                                                $sel = 'selected';
                                                            }
                                                        }
                                                ?>
                                                <option value="<?php echo $val['product_id']; ?>" <?php  echo $sel;  ?> ><?php echo $val['title']; ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                </div>
                            <?php } ?>
                            <div class="form-group  btm_border">
                                <label class="col-sm-3 control-label" for="demo-hor-211"><?php echo translate('order_by_collection');?></label>
                                <div class="col-sm-3">
                                    <input type="number" name="order_by_collection" min="0" id="order_by_collection" class="form-control totals required" value="<?php echo $row['order_by_collection']; ?>">
                                </div>
                            </div>
                                    <?php /*
                                    <div class="form-group  btm_border">
                                    <label class="col-sm-3 control-label" for="demo-hor-211"><?php echo translate('order_by_collection');?></label>
                                    <div class="col-sm-3">
                                        <input type="number" name="order_by_collection" min="0" id="order_by_collection" class="form-control totals required" value="<?php echo $row['order_by_collection']; ?>">
                                    </div>
                                </div>
                        
                                <div class="form-group btm_border">
                                    <label class="col-sm-3 control-label" for="demo-hor-1"><?php echo translate('is_offer');?></label>
                                    <div class="col-sm-7">
                                        <input type="checkbox"  id="is-offer"  name="is_offer" value="yes" onchange="ShowHideDiv(this.id)"  <?php if(!empty($row['is_offer']) && $row['is_offer'] == 'yes') { echo 'checked'; }  ?> >
                                    </div>
                                </div>
                                    */ ?>
                            <?php  if($row['is_offer'] == 'yes') { ?>
                                    <div id="showImageOption" style="/*display:none;*/">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="demo-hor-2"><?php echo translate('sub-collection_banner');?></label>
                                            <div class="col-sm-7">
                                                <span class="pull-left btn btn-default btn-file">
                                                    <?php echo translate('select_collection_banner');?>
                                                    <input type="file" name="img" id="imgInp" accept="image" <?php if (empty($row['collection_image'])) echo 'class="required"'; ?>>

                                                </span>
                                                <br><br>
                                                <p style="color:blue;font-weight: bold">Note : Upload file size <?php echo SIZE_DIMENSIONS['collection']; ?></p>
                                                <span id='wrap' class="pull-left" >
                                                <?php
                                                    if(!empty($row['collection_image'])){
                                                ?>
                                                <img src="<?php echo base_url(); ?>uploads/collection_image/<?php echo $row['collection_image']; ?>" width="50%" id='blah' />  
                                                <?php
                                                    } else {
                                                ?>
                                                <img src="<?php echo base_url(); ?>uploads/collection_image/default.jpg" width="50%" id='blah' />
                                                <?php
                                                    }
                                                ?> 
                                                </span>

                                            </div>
                                            </div>
                                    </div>
                            <?php } ?>
                        </div> 
                        <div id="extra_details" class="tab-pane fade">
                            <?php if (!empty($collection_type_multiple) || $data['type'] == "multiple"): ?>
                                <!-- -------------------multiple_div start----------------------- -->
                                <div class="col-md-12 div_multiple">
                                    <div class="row" id="dummy_div_multiple">
                                        <div class="col-md-12 mb-0">
                                            <div class="row">
                                                <div class="col-md-3 mt-1">
                                                    <label>Image Name</label>
                                                </div>
                                                <div class="col-md-2 mt-1">
                                                    <label>Image Upload<span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-1 mt-1">
                                                    <label>Image File</label>
                                                </div>
                                                <div class="col-md-2 mt-1">
                                                    <label>Is Clickable</label>
                                                </div>
                                                <div class="col-md-1 mt-1">
                                                    <a href="javascript:void(0);" onclick="add_multi_row();" class="btn btn-primary btn-sm"><i class="fa fa-plus fa-lg"></i></a><br/>
                                                </div>
                                            </div>
                                            <div class="row col-md-12">
                                                <div class="col-md-3 mt-1">
                                                </div>
                                            </div>
                                            <?php foreach($collection_type_multiple as $key => $value): ?>
                                                <div class="row main_div_multiple">
                                                    <div class="col-md-3 mt-1">
                                                        <input type="text" class="form-control" id="img_name_<?=$key?>" name="img_name[]" placeholder="Image Name" value="<?=$value['img_names']?>"><br/>
                                                    </div>
                                                    <div class="col-md-2 mt-1">
                                                        <input type="file" class="form-control" id="img_file_<?=$key?>" name="img_file[]" ><br/>
                                                        <input type="hidden" name="extra_id[]" value="<?=$value['extra_id']?>">
                                                    </div>
                                                    <div class="col-md-1 mt-1">
                                                        <span style="white-space:nowrap;">
                                                            <a href="<?php echo base_url(); ?>uploads/collection_image/collection_<?php echo $value['extra_id'] . '.jpg' ?>" target="_blank" class="btn btn-primary btn-sm" data-size="large" data-title="View Image" title="View"><i class="fa fa-eye"></i></a>
                                                        </span>                                                                              
                                                    </div>
                                                    <div class="col-md-2 mt-1">
                                                        <select class="demo-chosen-select" id="img_clickable_<?=$key?>" name="img_clickable[]" style="width: 100% !important;">
                                                            <?php foreach($dropdownStatus as $keyone => $val): ?>
                                                                <?php if ($val == $value['img_clickables']): ?>
                                                                    <option value="<?=$keyone?>" selected><?=$val?></option>
                                                                <?php else: ?>
                                                                    <option value="<?=$keyone?>"><?=$val?></option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select><br/><br>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="remove_multi_row(this);" ><i class="fa fa-trash fa-lg"></i></button><br/>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- -------------------multiple_div end----------------------- -->
                            <?php endif ?>

                            <?php if (!empty($collection_type_category) || $row['type'] == "category"): ?>
                                    <!-- -------------------category_div start----------------------- -->
                                    <div class="col-md-12 div_category">
                                        <div class="row" id="dummy_div_category">
                                            <div class="col-md-12 mb-0">
                                                <div class="row">
                                                    <div class="col-md-3 mt-1">
                                                        <label>Category<span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-3 mt-1">
                                                        <label>SubCategory<span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-1 mt-1">
                                                        <a href="javascript:void(0);" onclick="add_cat_row();" class="btn btn-primary btn-sm"><i class="fa fa-plus fa-lg"></i></a><br/>
                                                    </div>
                                                    <div class="row col-md-12">
                                                        <div class="col-md-3 mt-1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (!empty($collection_type_category['extra_details'])): ?>
                                                    <?php foreach ($collection_type_category['extra_details'] as $key => $value): ?>
                                                        <div class="row main_div_category">
                                                            <div class="col-md-3 mt-1">
                                                                <select class="demo-chosen-select category" id="category_<?php echo $key; ?>" name="category[]" style="width: 100% !important;">
                                                                    <?php foreach ($category_all as $val): ?>
                                                                        <?php if ($val['category_id'] == $value['category_id']): ?>
                                                                            <option value="<?php echo $val['category_id']; ?>" selected><?php echo $val['category_name']; ?></option>
                                                                        <?php else: ?>
                                                                            <option value="<?php echo $val['category_id']; ?>"><?php echo $val['category_name']; ?></option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </select><br/><br>
                                                            </div>
                                                            <div class="col-md-3 mt-1">
                                                                <select class="demo-cs-multiselect sub_category sub_category_<?php echo $key; ?>" id="sub_category_<?php echo $key; ?>" name="sub_category[]" style="width: 100% !important;" multiple="multiple">
                                                                    <?php foreach ($sub_category_all as $val): ?>
                                                                        <?php if ($val['category'] == $value['category_id']): ?>
                                                                            <?php $selected = in_array($val['sub_category_id'], $value['sub_category']) ? 'selected' : ''; ?>
                                                                            <option value="<?php echo $val['sub_category_id']; ?>" <?php echo $selected; ?>><?php echo $val['sub_category_name']; ?></option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </select><br/><br>
                                                            </div>
                                                            <div class="col-md-1 mt-1">
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="remove_cat_row(this);"><i class="fa fa-trash fa-lg"></i></button><br/>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -------------------category_div end----------------------- -->
                                <?php endif; ?>
  
                        </div>  
                    </div>
                </div>  
            </div>   
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-11">
                    	<span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right" 
                            onclick="ajax_set_full('edit','<?php echo translate('edit_collection'); ?>','<?php echo translate('successfully_edited!'); ?>','collection_edit','<?php echo $row['collection_id']; ?>') "><?php echo translate('reset');?>
                        </span>
                     </div>
                     <div class="col-md-1">
                     	<span class="btn btn-success btn-md btn-labeled fa fa-wrench pull-right enterer" onclick="form_submit('collection_edit','<?php echo translate('successfully_edited!'); ?>');proceed('to_add');" ><?php echo translate('edit');?></span> 
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
<?php /*  ?>
<input type="hidden" id="option_count" value="<?php if($r == 1){ echo $row1['no']; } else { echo '0'; } ?>">
<?php */  ?>
<script type="text/javascript">
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

    function add_cat_row(){
        var i = parseInt($('[id^=category_]:last').attr('id').substr(9))+1;
        $('.sample_div_category_class .category_sample_class').attr({'name':'category[]','id':'category_'+ i});
        $('.sample_div_category_class .sub_category_sample_class').attr({'name':'sub_category[]','id':'sub_category_'+ i,'class':'demo-cs-multiselect sub_category_sample_class form-control sub_category_'+ i});

        var sampleClone = $('#sample_div_category').clone();
        sampleClone.find('.demo-chosen-select').chosen();
        sampleClone.find('.demo-cs-multiselect').chosen({width: '100%'});

        $('#dummy_div_category').append(sampleClone);
    }
    function remove_cat_row($this)
    {
        $($this).parents('.main_div_category').remove();
    }
  
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
        <?php /*if($product_data[0]['is_offer'] == 'yes') {?>
           $('#showImageOption').show('slow');
        <?php } */ ?>  
    });

    
    function ShowHideDiv(id){
//        if($("#"+id).is(":checked")){  
//           $('#showImageOption').show('slow');
//        }else{
//            $('#showImageOption').hide('slow');
//        }
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
                }
            });
        });

    });
</script>
<style>
	.btm_border{
		border-bottom: 1px solid #ebebeb;
		padding-bottom: 15px;	
	}
</style>

