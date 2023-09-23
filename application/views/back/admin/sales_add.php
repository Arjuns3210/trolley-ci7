 <script src="<?php echo base_url(); ?>template/back/js/autojs/jquery-ui.js"></script>
<link href="<?php echo base_url(); ?>template/back/css/jquery-ui.css" rel="stylesheet">


<div class="row">
    <div class="col-md-12">
		<?php
            echo form_open(base_url() . 'index.php/admin/sales/do_add/', array(
                'class' => 'form-horizontal',
                'method' => 'post',
                'id' => 'sales_add',
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
                            <a data-toggle="tab" href="#customer_details"><?php echo translate('customer_details'); ?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#shipping_details"><?php echo translate('shipping_details'); ?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#payment_details"><?php echo translate('payment_details'); ?></a>
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
                                <h4 class="text-thin text-center"><?php echo translate('product_details'); ?></h4>                            
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">

                                        <label  for="prod_here"><?php echo translate('search_product');?></label>
                                        <!--<input type="text" id="prod_here" name='prod_here' placeholder="Enter Product Title Name or Product Code" class="form-control">-->
                                        <select style="width:100%;" class="js-data-example-ajax"></select> 
                                        <span id="product_data"></span>
                                         
                                </div>
                            </div>
                            

                            <div  class="col-md-12" style="overflow-x: scroll;">
                            <div id="wait_msg"></div>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Sr.no</th>
                                                <th>Name<br/>Prdt Code</th>
                                                <th>Sale Price</th>
                                                <th>Select Options</th>
                                                <th>QTY</th>
                <!--                                <th>GST/Discount Rate</th>-->
                                                <th>Available Stock</th>
                                                <th>Amount</th>

                                            </tr>
                                        </thead>
                                        <tbody id="add_here">
                                        <?php
                                        if (isset($productsrow)) {
                                            
                                                echo $productsrow;
                                            
                                        }
                                        ?>
                                            <tr id="subtotal_from_here" class="row-top">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            
                                            <td></td>
                                             <td>

                                             </td>
                                            <td>Subtotal</td>

                                            <td>
                                                <input style="width:100%;" class="input-xlarge form-control" type="text" autocomplete="off"  onfocus="this.select();"  name="subtotal"  class="widthcalculation " id="subtotal" onblur="" value="" readonly="" onkeydown=""><span ></span>
                                            </td>
                                        </tr>
                                        <tr class="disscount">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            
                                            <td></td>
                                             <td>

                                             </td>
                                            <td>Total Discount</td>

                                            <td>
                                                <input style="width:100%;" class="input-xlarge form-control" type="text" autocomplete="off"  onfocus="this.select();"  name="totaldiscount"  class="widthcalculation " id="totaldiscount" onblur="" value="" readonly="" onkeydown=""><span ></span>
                                            </td>
                                        </tr>
                                        <tr class="taxx">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            
                                            <td></td>
                                             <td>

                                             </td>
                                            <td>Total TAX</td>

                                            <td>
                                                <input style="width:100%;" class="input-xlarge form-control" type="text" autocomplete="off"  onfocus="this.select();"  name="totaltax"  class="widthcalculation " id="totaltax" onblur="" value="" readonly="" onkeydown=""><span ></span>
                                            </td>
                                        </tr>
                                        <tr class="shippingg">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            
                                            <td></td>
                                             <td>

                                             </td>
                                            <td>Total Shipping</td>

                                            <td>
                                                <input style="width:100%;" class="input-xlarge form-control" type="text" autocomplete="off"  onfocus="this.select();"  name="totalshipping"  class="widthcalculation " id="totalshipping" onblur="" value="" readonly="" onkeydown=""><span ></span>
                                            </td>
                                        </tr>
                                        <tr >
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                           
                                            <td></td>
                                             <td>

                                             </td>
                                            <td>Final Total</td>

                                            <td>
                                                <input style="width:100%;" class="input-xlarge form-control" type="text" autocomplete="off"  onfocus="this.select();"  name="finaltotal"  class="widthcalculation " id="finaltotal" onblur="" value="" readonly="" onkeydown=""><span ></span>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                            </div>
                            
                            
                    </div>
               
                    <div id="customer_details" class="tab-pane fade">
                            <div class="form-group btm_border">
                                <h4 class="text-thin text-center"><?php echo translate('customer_details'); ?></h4>                            
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-6"><?php echo translate('existing_customer');?></label>
                                <div class="col-sm-6">
                                      <input type="radio" class="required" onclick="ShowHideDiv(this)" name="existing_custr" id="yes" value="Yes"  id="existing_custr"><label for="yes">Yes</label> &nbsp;
                                      <input type="radio" class="required" onclick="ShowHideDiv(this)" name="existing_custr" id="no" value="No"  id="existing_custr"><label for="no">No</label>
                                </div>
                            </div>
                            
                            <div id="custttomer" class="form-group btm_border" style="display:none;">
                                  <label class="col-sm-4 control-label" for="demo-hor-7"><?php echo translate('Customer');?></label>
                                    <div class="col-sm-6">
                                    <?php /* $customer =  $this->crud_model->get_customer(); 
                                    ?>
                                       <select name="customer_id" id="demo-hor-7" onChange="get_customer_data(this.value,this)" class="demo-chosen-select customer_id"  >
                                             <option value="">Select Customer</option>
                                            <?php 
                                           
                                            if(isset($customer) && is_array($customer)){
                                                foreach($customer as $key => $val){
                                                ?>
                                            <option value="<?php echo $val['user_id']; ?>" ><?php echo $val['username'].' '.$val['surname'].' '.$val['email'].' '.$val['phone']; ?></option>
                                            <?php }} ?>
                                        </select>
                                        */ ?>
                                         <select style="width:100%;" name="customer_id" onChange="get_customer_data(this.value,this)"  class="js-data-example-ajax1"></select> 
                                </div>
                            </div>
                            
                            <div id="customer_data" style="display:none;">
                                <div id="customer_data_here">
                                </div>
                            </div>
                        
                            <div id="newcustttomer" style="display:none;">
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="n_first_name"><?php echo translate('first_name*');?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="n_first_name" id="first_name" value="" placeholder="<?php echo translate('first_name');?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="n_last_name"><?php echo translate('last_name*');?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="n_last_name" id="last_name" value="" placeholder="<?php echo translate('last_name');?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="n_contact_number"><?php echo translate('contact_number*');?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="n_contact_number" id="contact_number" value="" placeholder="<?php echo translate('contact_number');?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="n_email_address"><?php echo translate('email_address*');?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="n_email_address" id="email_address" value="" placeholder="<?php echo translate('email_address');?>" class="form-control">
                                </div>
                            </div>
                           </div>
                       </div>
                        
                       <div id="shipping_details" class="tab-pane fade">
                            <div class="form-group btm_border">
                                <h4 class="text-thin text-center"><?php echo translate('shipping_details'); ?></h4>                            
                            </div>
                            <div class="form-group">
                                    <label class="col-sm-2"  for="demo-hor-7"><?php echo translate('Search Vendor');?></label>
                                    <div class="col-sm-10">
                                        <select style="width:100%;" onChange="get_vendor_data(this.value,this)" class="js-data-example-ajax2"></select> 
                                    </div>
                            </div>
                            <div id="vendor_data" style="display:none">
                                <div id="vendor_data_here">
                                </div>
                            </div>
                           
                           <div class="panel panel-default" id="fetchedaddresses"></div>
                           <div class="panel panel-default" id="newCustomerAddress" >
                                <div class="panel-heading">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        <h4 class="panel-title">Add New Address</h4>
                                    </a>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse "> <?php // collapse in ?>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="address_1"><?php echo translate('address_1*');?></label>
                                            <div class="col-sm-6">
                                                <input type="text" name="address_1" id="address_1" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="address_2"><?php echo translate('address_2');?></label>
                                            <div class="col-sm-6">
                                                <input type="text" name="address_2" id="address_2" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('Country*');?></label>
                                            <div class="col-sm-6">
                                                <?php echo $this->crud_model->select_html('country','country','name','add','demo-chosen-select','','status','Active','get_state'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group" id="states" style="display:none;">
                                            <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('province*');?></label>
                                            <div class="col-sm-6" id="state_here">
                                            </div>
                                        </div>
                                        <div class="form-group" id="cities" style="display:none;">
                                            <label class="col-sm-4 control-label" for="demo-hor-3"><?php echo translate('city*');?></label>
                                            <div class="col-sm-6" id="city_here">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="pincode"><?php echo translate('pincode');?></label>
                                            <div class="col-sm-6">
                                                <input type="text" name="pincode" id="pincode" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php 
                                            /* <label class="col-sm-4 control-label" for="shipping_address"><?php echo translate('set_as_shipping_address');?></label>
                                            <div class="col-sm-6">
                                                <input type="radio" name="shipping_address" id="shipping_address" value="yes" >
                                            </div>
                                             */  ?> 
                                            <label class="col-sm-4 control-label" for="selected_address"><?php echo translate('set_as_shipping_address');?></label>
                                            <div class="col-sm-6">
                                                <input type="radio" name="selected_address" id="selected_address_yes" value="" >
                                                <input type="hidden" name="shipping_address" id="shipping_address_true" value="" >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="delivery_instructions"><?php echo translate('delivery_instructions');?></label>
                                            <div class="col-sm-6">
                                                <textarea type="text" rows="3" name="delivery_instructions" id="delivery_instructions" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                       </div>
                       <div id="payment_details" class="tab-pane fade">
                           <div class="form-group">
                               <label class="col-sm-4 control-label" for="payment_method"><?php echo translate('payment_type'); ?></label>
                               <div class="col-sm-6" id="payment_method">
                                   <input type="radio" class="required" name="payment_method" value="fnb"> <label for="fnb">Card Payment through FNB Payment Gateway</label><br/>
                                   <input type="radio" class="required" name="payment_method" value="eft"> <label for="eft">EFT</label><br/>
                                   <input type="radio" class="required" name="payment_method" value="cash_deposit"> <label for="cash_deposit">Cash Deposit using an ATM</label><br/>
                               </div>
                           </div>
                           
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-16"><?php echo translate('other_comments'); ?></label>
                                <div class="col-sm-6">
                                    <textarea rows="3" class="form-control" id="demo-hor-16"  name="other_description" ></textarea>
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
                	
                        <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right " 
                            onclick="ajax_set_full('add','<?php echo translate('add_sale'); ?>','<?php echo translate('order_successfully_created!'); ?>','sales_add',''); "><?php echo translate('reset');?>
                        </span>
                   
                        <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right enterer" onclick="form_submit('sales_add','<?php echo translate('sales_data_created_successfully!'); ?>');" ><?php echo translate('upload');?></span>
                    
                    
                </div>
            </div>
                
                
    
        </form>
    </div>
</div>

<script src="<?php $this->benchmark->mark_time(); echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js">
</script>

<script>
    //added by ritesh : start
    proceed('to_list');
    var counter = 0;
    var pincode = '';
    var url = "<?php echo base_url(); ?>index.php/admin/addRow";
    $(document).ready(function() {
        
        $('.js-data-example-ajax').select2({
            
            ajax: {
              url: "<?php echo base_url(); ?>index.php/admin/searchProductsselect2",
              dataType: 'json',
              data: function (params) {
                    var query = {
                      enteredValue: params.term,
                    }
                    return query;
                },
              processResults: function (data) {
                return data;
              },
                
            }
            
          });
          
    //added by sagar :: serachCustomer Dropdown  START : 23-01  
        $('.js-data-example-ajax1').select2({
            ajax: {
              url: "<?php echo base_url(); ?>index.php/admin/searchCustomerselect2",
              dataType: 'json',
              data: function (params) {
                    var query = {
                      enteredValue: params.term,
                    }
                    return query;
                },
              processResults: function (data) {
                return data;
              },
            }
          });
    //added by sagar :: serachCustomer Dropdown  End : 23-01  
          
        $('.js-data-example-ajax').change(
        function(){
         add_row(url,$(this).val());   
         $(this).val('');
        }    
        );
          
          
       
        $('.demo-chosen-select').chosen();
        // $('#newAddressButton').hide();
        $('.demo-cs-multiselect').chosen({width:'100%'});
//        $("form").submit(function(e){
//            alert("inside form submit :: "+e.keyCode);
//                e.preventDefault();
//        });
        
          $('#sales_add').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
//             console.log("inside sales_add keypress submit :: "+e.keyCode);
//                console.log("inside sales_add keypress submit :: "+e.which);
//                console.log("inside sales_add keyCode submit :: "+keyCode);
            if (keyCode == 13) { 
                console.log("inside sales_add keyCode submit :: "+keyCode);
//              e.preventDefault();
//              return false;
            $("#sales_add").submit(function(e){
                 alert("inside form submit alert:: "+e.keyCode);
		   e.preventDefault();
		});
            }
          });
        
          
          $('.js-data-example-ajax2').select2({
            ajax: {
              url: "<?php echo base_url(); ?>admin/searchVendorselect2",
              dataType: 'json',
              data: function (params) {
                    var query = {
                      enteredValue: params.term,
                      //pincode :pincode
                    }
                    return query;
                },
              processResults: function (data) {
                return data;
              },
            }
          });
        
        
    });
    
//     $('input[type="radio"]').click(function(){
//        var inputValue = $(this).attr("value");
//        var targetBox = $("." + inputValue);
//        $(".box").not(targetBox).hide();
//        $(targetBox).show();
//    });
        
    function next_tab(){
        $('.nav-tabs li.active').next().find('a').click();                    
    }
    function previous_tab(){
        $('.nav-tabs li.active').prev().find('a').click();                     
    }
    
    function other(){
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
        $('#reserve').hide();
        $('#rate').val($('#reserve').html());
    }
    
    function ShowHideDiv(temp){
        var value = temp.value;
        if(value=="Yes"){
           $('#custttomer').show('slow');
           $('#fetchedaddresses').show('slow');
           $('#newcustttomer').hide('slow');
//           $('#newCustomerAddress').hide('slow');
        }else if(value=="No"){
            $('#custttomer').hide('slow');
            $('#customer_data').hide('slow');
            $('#newcustttomer').show('slow');
            $('#fetchedaddresses').hide('slow');
//            $('#newCustomerAddress').show('slow');
        }
    }
    
    
    function get_customer_data(id){
        $('#customer_data').hide('slow');
        ajax_load(base_url+'index.php/admin/sales/userData/'+id,'customer_data_here','');
        $('#customer_data').show('slow');
        get_customer_address_data(id);
    }
    
    function get_vendor_data(id){
         $('#vendor_data').hide('slow');
         ajax_load(base_url+'index.php/admin/sales/vendorData/'+id,'vendor_data_here','');
        $('#vendor_data').show('slow');
    }
    
    function get_state(id){
        $('#states').hide('slow');
        $('#cities').hide('slow');
        ajax_load(base_url+'index.php/admin/sales/state/'+id,'state_here','other');
        $('#states').show('slow');
    }
    
    function get_city(id){
        $('#cities').hide('slow');
        ajax_load(base_url+'index.php/admin/sales/city/'+id,'city_here','other');
        $('#cities').show('slow');
    }
    
    
    function get_customer_address_data(customer_id){
        
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/admin/fetchCustomerAddress/"+customer_id,
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',custId: customer_id},
            dataType: 'json',
            cache: false,
            method: 'post',
            beforeSend: function () {
                $('#wait_msg').html('<span style="color:blue;">Please wait...</span>');
            },
            success: function (res)
            {

                $('#wait_msg').html('');
                if (res['status'] == "success")
                {
                //    console.log("Fetehced db hs :: "+res['body']);
                    $('#fetchedaddresses').html(res['body']);
//                    $('#subtotal_from_here').before(res['body']);
//                    $("html, body").animate({scrollTop: $(document).height() - $(window).height()});
                }
            }


        });
    }
    
    
    
    
    
    
    jQuery.curCSS = function(element, prop, val) {
        return jQuery(element).css(prop, val);
    };
    
    $("#prod_here").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/admin/searchProducts",
                dataType: "json",
                data: {
                    enteredValue: request.term
                },
                success: function (data) {
                    response(data);
                }
             });
        },
        minLength: 2,
        select: function (event, ui) {
            
            add_row(url,ui.item.value);
            $(this).val('');
            return false;
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
          },
          close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
          }

    });
    
    
     
        //added by ritesh : start
      function add_row(url,productId) {
        counter += 1;
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/admin/addRow/"+counter,
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',productId: productId},
            dataType: 'json',
            cache: false,
            method: 'post',
            beforeSend: function () {
                $('#wait_msg').html('<span style="color:blue;">Please wait...</span>');
            },
            success: function (res)
            {

                $('#wait_msg').html('');
                if (res['status'] == "success")
                {
                    $('#subtotal_from_here').before(res['body']);
                    calculate_srno();
                    $("html, body").animate({scrollTop: $(document).height() - $(window).height()});
                }
            }


        });
    }
    
    
     function calculate_srno() {
        var srno = 0;
        $('.srno').each(function () {
            srno += 1;
            $(this).html(srno);
        });
    }
    
    
    function remove_this_row(row) {
            var r = confirm("Are you sure you want to remove the selected product row ?");
        if (r == 1) {
//            if ($('.srno').length !== 1) {
                $('.' + row).remove();
                calculate_srno();
                final_calculation();
//            } else {
//                alert('atleast one product required.');
//            }
        }
//                        $( "#dialog-salesremove-confirm" ).dialog({
//                                    resizable: false,
//                                    height: "auto",
//                                    width: 400,
//                                    modal: true,
//                                    buttons: {
//                                      "Delete": function() {
//                                                  $( this ).dialog( "close" );
//                                                  $('.' + row).remove();
//                                                  calculate_srno();
//                                                  final_calculation();  
//                                      },
//                                      Cancel: function() {
//                                        $( this ).dialog( "close" );
//                                      }
//                                    }
//                                  });
    }
    function change_values(row){
       var data = $('input[name="variation['+row+']"]:checked');
       $('#sale_price'+row).html(data.attr('data-saleprice'));
       $('#sku_code'+row).html(data.attr('data-skucode'));
       $('#total'+row).val(data.attr('data-price-after-discount'));
       $('#total'+row).attr('data-calculatedvalue',data.attr('data-price-after-discount'));
       $('#discount_amt'+row).val(data.attr('data-discount'));
       $('#discount_amt'+row).attr('data-discountvalue',data.attr('data-discount'));
       $('#tax'+row).val(data.attr('data-tax'));
       $('#tax'+row).attr('data-taxvalue',data.attr('data-tax'));
       var stock = data.attr('data-stock');
        if(parseInt(data.attr('data-stock')) <=0){
           stock = '<label class="label label-danger">Out of Stock</label>';
       }
       $('#current_stock'+row).html(stock);
       
       startCalculation(row);
   }
    function startCalculation(row){
        
        var quantity = parseFloat($('#quantity'+row).val());
        var rate = parseFloat($('#rate'+row).val());
        var after_discount_value = parseFloat($('#total'+row).attr("data-calculatedValue"));
        var tax = parseFloat($('#tax'+row).attr("data-taxValue"));
        var discount_Amount = parseFloat($('#discount_amt'+row).attr("data-discountValue"));
        var shipping = parseFloat($('#shipping'+row).attr("data-shippingValue"));
        
        var total_value = 0;
        if(isNaN(after_discount_value)){
            total_value = 0;
            after_discount_value = parseInt($('#total'+row).attr("data-calculatedValue"));
        }
       
        total_value = after_discount_value*quantity;
        total_value = total_value.toFixed(2);
        $('#total'+row).val(total_value);
        
        
        var discount_value = 0;
        if(isNaN(discount_Amount)){
            discount_value = 0;
            discount_Amount = parseInt($('#discount_amt'+row).attr("data-discountValue"));
        }
        discount_value = discount_Amount*quantity;
        discount_value = discount_value.toFixed(2);
        $('#discount_amt'+row).val(discount_value);
        
        
        var shipping_value = 0;
        if(isNaN(shipping)){
            shipping_value = 0;
            shipping = parseInt($('#shipping'+row).attr("data-shippingValue"));
        }
        shipping_value = shipping*quantity;
        shipping_value = shipping_value.toFixed(2);
        $('#shipping'+row).val(shipping_value);
        
        
        var tax_value = 0;
        if(isNaN(tax)){
            tax_value = 0;
            tax = parseInt($('#tax'+row).attr("data-taxValue"));
        }
        tax_value = tax*quantity;
        tax_value = tax_value.toFixed(2);
        $('#tax'+row).val(tax_value);
        
        
        
        
        final_calculation();
    }
    
    function final_calculation(){
        var sub_total = 0;
        $('.taxable_value').each(function(){
            if(!isNaN(parseFloat($(this).val()))){
                sub_total += parseFloat($(this).val());
            }
            
        });
        if(isNaN(sub_total)){
            sub_total = 0;
        }
        sub_total = sub_total.toFixed(2);
        $('#subtotal').val(sub_total);
        
        var total_tax = 0;
        $('.tax').each(function(){
            if(!isNaN(parseFloat($(this).val()))){
                total_tax += parseFloat($(this).val());
            }
        });
        if(isNaN(total_tax)){
            total_tax = 0;
        }
        total_tax = total_tax.toFixed(2);
        $('#totaltax').val(total_tax);
        
        var total_discount = 0;
        $('.discount_amt').each(function(){
            if(!isNaN(parseFloat($(this).val()))){
                total_discount += parseFloat($(this).val());
            }
        });
        if(isNaN(total_discount)){
            total_discount = 0;
        }
        total_discount = total_discount.toFixed(2);
        $('#totaldiscount').val(total_discount);
        
        var total_shipping = 0;
        $('.shipping').each(function(){
            if(!isNaN(parseFloat($(this).val()))){
                total_shipping += parseFloat($(this).val());
            }
        });
        if(isNaN(total_shipping)){
            total_shipping = 0;
        }
        if (sub_total < 500){
            total_shipping = 100;
        }
        total_shipping = total_shipping.toFixed(2);
        $('#totalshipping').val(total_shipping);
        
        var final_total = parseFloat(sub_total)+parseFloat(total_shipping)+parseFloat(total_tax);
        final_total = final_total.toFixed(2);
        $('#finaltotal').val(final_total);
    }
    
    //added by ritesh : end
    
    //added by ritesh : end
    function checkKeyPressed(e) {
       
        if (e.keyCode == "13") {
            $("form").submit(function(e){
		   e.preventDefault();
		});
        }
    }
    window.addEventListener("keydown", checkKeyPressed, false);
    
        
</script>

<style>
	.btm_border{
		border-bottom: 1px solid #ebebeb;
		padding-bottom: 15px;	
	}
        
/*    .ui-autocomplete
    {
        position:absolute;
        cursor:default;
        z-index:1001 !important
    }*/
    .autocomplete-suggestions { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-no-suggestion { padding: 2px 5px;}
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: bold; color: #000; }
    .autocomplete-group { padding: 2px 5px; font-weight: bold; font-size: 16px; color: #000; display: block; border-bottom: 1px solid #000; }
    .button_hover{background-color: pink !important;color: black !important;}
    .row-top{border-top: #000 solid;}
    
</style>

<div id="dialog-salesremove-confirm" title="Delete This Product?" style="display: none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Are you sure you want to remove the selected product row?</p>
</div>
<!--Bootstrap Tags Input [ OPTIONAL ]-->

