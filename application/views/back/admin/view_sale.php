<style>
    .boxShadow {
        margin-top: 18px;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    }
</style>
<div class="panel-body">
    <div class="tab-base">
        <?php
        $row = $sale[0];
        $info = json_decode($row['shipping_address'], true);
        $delivery_check = json_decode($row['delivery_status'], true);
        $user_choice = json_decode($row['user_choice'], true);
        $payment_status_array = json_decode($row['payment_status'], true);
        $payment_status = ucfirst($payment_status_array[0]['status']);
        //invoice and map
        $Curr_symbol = ' EGP ';
        $delivery_date_timeslots = json_decode($row['delivery_date_timeslot'], true);
        $user_choice = json_decode($row['user_choice'], true);
        $sale_currency_conversion_rate = $user_choice[0]['currency_conversion'];
        $order_status = $row['order_status'];
        $order_cancellation_remarks = $row['order_cancel_comment'];
        $assign_stores_db_data = json_decode($row['assign_stores_data'], true);
        $assign_store_print = FALSE;
        $sale_code = $row['sale_code'];
        $product_details = json_decode($row['product_details'], true);

        $statusCount =0;

            foreach($product_details as $key => $prod) {
                if(!array_key_exists('status', $prod)) {
                    $statusCount++;
                }
            }
        ?>
        <div class="tab-content">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 boxShadow">
                    <div class="col-lg-6 col-md-6 col-sm-6 pad-all">
                        <span><?php echo substr($sale_code, -4); ?></span><br><br>
                        <span><?php echo translate('customer_name'); ?> :<strong><?php echo $info['first_name']; ?></strong></span><br><br>
                        <span><?php echo translate('customer_mobile'); ?> :<strong><?php echo $info['address_number']; ?></strong></span><br><br>
                        <span><?php echo translate('delivery_date'); ?> :<strong><?php echo date('d M, Y', $row['sale_datetime']); ?></strong></span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 pad-all">
                        <span class="pull-right"><?php echo translate('order_code'); ?> :<strong>#<?php echo $row['sale_code']; ?></strong></span>
                        <br><br><br><br><br><br>
                        <span class="pull-right"><?php echo translate('total_amount');?> :<strong style="font-size: 15px;"><?php echo $Curr_symbol.get_converted_currency($row['invoice_amount'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);;?></strong></span>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:25px;box-shadow: rgba(17, 17, 26, 0.1) 0px 4px 16px, rgba(17, 17, 26, 0.05) 0px 8px 32px;">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td style="width:136px" id="MainTd">
                                        <?php if($statusCount ==0 ){ ?>
                                            <i class="fa fa-times-circle text-danger" aria-hidden="true" style="margin-left: 23px"></i>
                                        <?php }else { ?>
                                            <input type="checkbox" id="selectAll" style="margin-left:24px">
                                        <?php } ?>
                                        <strong>&nbsp;&nbsp;Select&nbsp;All</strong></td>
                                    <th colspan="2">
                                        <div style="margin-left: 105px">
                                            <?php echo translate('product'); ?>
                                        </div>
                                    </th>
                                    <th><?php echo translate('quantity'); ?></th>
                                    <th><?php echo translate('amount'); ?></th>
                                    <th><?php echo translate('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $product_details = json_decode($row['product_details'], true);
                                $product_details = array_values($product_details);
                                $i = 0;
                                $total = 0;
                                foreach ($product_details as  $prodKey => $row1) {
                                    if ($row1['status'] == 'cancelled') {
                                ?>

                                        <tr style="opacity:0.5">
                                            <td style="padding: 31px;width:75px;"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></td>
                                            <td width="6%"><img class="img-md" src="<?php echo base_url(); ?>uploads/product_image/trolley_default.png" height="100px" /></td>
                                            <td><span style="margin-left: 25px;color:red;"><?php echo $row1['name']; ?></span></td>
                                            <td style="color:red;"><?php echo $row1['qty'] ?></td>
                                            <td style="color:red;"><?php echo $Curr_symbol . get_converted_currency($row1['subtotal'], DEFAULT_CURRENCY, $sale_currency_conversion_rate);
                                                                        $total += $row1['subtotal']; ?></td>
                                            <td><i class="fa fa-trash text-danger fa-lg" aria-hidden="true" style="margin-left: 6px;"></i></td>
                                        </tr>
                                    <?php } else if ($delivery_check[0]['status'] == 'delivered') { ?>
                                        <tr>
                                            <td style="padding: 31px;width:75px;color: green;"><i class="fa fa-check-square-o" aria-hidden="true"></i></td>
                                            <td width="6%"><img class="img-md" src="<?php echo base_url(); ?>uploads/product_image/trolley_default.png" height="100px" /></td>
                                            <td valign="middle"><span style="margin-left: 25px;"><?php echo $row1['name']; ?></span></td>
                                            <td><?php echo $row1['qty'] ?></td>
                                            <td><?php echo $Curr_symbol . get_converted_currency($row1['subtotal'], DEFAULT_CURRENCY, $sale_currency_conversion_rate);
                                                                                $total += $row1['subtotal']; ?></td>
                                            <td style="opacity:0.5"><i class="fa fa-trash text-danger fa-lg" aria-hidden="true" style="margin-left:6px"></i></td>
                                        </tr>
                                    <?php }else{?>
                                        <tr id="myTable<?php echo $row1['product_id']; ?>">
                                            <td style="padding: 31px;width:75px"><input type="checkbox" class="selectMe" name="product[]" value="<?php echo $row1['product_id']; ?>" id="<?php echo $row1['product_id']; ?>"></td>
                                            <td width="6%" id="image<?php echo $row1['product_id']; ?>">
                                            <?php
                                                if(file_exists('uploads/product_image/'.$row1['image'])){
                                            ?>
                                                <img class="img-md" src="<?php echo base_url(); ?>uploads/product_image/<?php echo $row1['image']; ?>" height="100px" />  
                                                <?php } else { ?>
                                                <img class="img-md" src="<?php echo base_url(); ?>uploads/product_image/trolley_default.png" height="100px" /></td>
                                            <?php } ?>
                                            <td valign="middle" id="name<?php echo $row1['product_id']; ?>"><span style="margin-left: 25px;"><?php echo $row1['name']; ?></span></td>
                                            <td id="qty<?php echo $row1['product_id']; ?>"><?php echo $row1['qty'] ?></td>
                                            <td id="subtotal<?php echo $row1['product_id']; ?>"><?php echo $Curr_symbol . get_converted_currency($row1['subtotal'], DEFAULT_CURRENCY, $sale_currency_conversion_rate);
                                                                                $total += $row1['subtotal']; ?></td>
                                            <td><button style="border:none;background-color:snow;" id="x<?php echo $row1['product_id']; ?>" onclick="deleteSingleProduct(this,'<?php echo $row1['product_id']; ?>','<?php echo $row['sale_id']; ?>')"><i class="fa fa-trash text-danger fa-lg" aria-hidden="true"></i></button style="border:none;"></td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($delivery_check[0]['status'] != 'delivered'){?>
                    <div class="col-md-12" style="padding-top: 10px; padding-bottom: 20px;">
                        <?php if($statusCount !=0 ){ ?>
                            <div class="pull-right">
                                <button type="button" class="btn btn-outline-danger" id='remove_selected' disabled><?php echo translate('remove_selected_product'); ?></button>
                            </div>
                        <?php } ?>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.js"></script>
<script type="">
    //this is for multi delete
    $('#remove_selected').click(function() {
        var baseUrl = '<?php echo base_url() ?>';
        var sale_id = '<?php echo $row['sale_id'] ?>';
        var selectedIds = []; // Array to store selected checkbox IDs
        $('input[name="product[]"]:checked').each(function() {
            var checkboxId = $(this).attr('id'); // Get the ID of the selected checkbox
            selectedIds.push(checkboxId); // Add the ID to the selectedIds array
        });
        Swal.fire({
            title: "Are you sure you want to Remove?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: baseUrl + 'index.php/admin/deleteMultipleProducts',
                    type: 'post',
                    dataType: 'html',
                    data: {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                        product_id: selectedIds, // Pass the selectedIds array to the 'product_id' parameter
                        sale_id: sale_id
                    },
                    success: function(response) {
                        Swal.fire("Removed!", "Proceeding...", "success");

                        // Update the table data values based on the selected checkboxes
                        $('input[name="product[]"]:checked').each(function() {
                            var productId = $(this).attr('id'); // Get the ID of the selected checkbox

                            // Get the values from existing table data elements
                            var name = $('#name' + productId).text();
                            var qty = $('#qty' + productId).text();
                            var subtotal = $('#subtotal' + productId).text();
                            
                            // Create new table data elements with the provided HTML code and the extracted values
                            var td1 = $('<td>').html('<i class="fa fa-times-circle text-danger" aria-hidden="true"></i>').css({
                                padding: '31px',
                                width: '75px'
                            });
                            var td2 = $('<td>').attr('width', '6%').html('<img class="img-md" src="' + baseUrl + 'uploads/product_image/trolley_default.png" height="100px" />');
                            var td3 = $('<td>').html('<span style="margin-left: 25px;color:red;">' + name + '</span>');
                            var td4 = $('<td>').css('color', 'red').text(qty);
                            var td5 = $('<td>').css('color', 'red').text(subtotal);
                            var td6 = $('<td>').html('<i class="fa fa-trash text-danger fa-lg" aria-hidden="true" style="margin-left: 6px;"></i>');
                            
                            // Get the table row containing the checkbox and replace its table data with the new table data elements
                            var newRow = $('<tr>').attr('id', 'myTable' + productId).css('opacity', '0.5');
                            newRow.append(td1, td2, td3, td4, td5, td6);
                            
                            $('#myTable' + productId).replaceWith(newRow);
                        });
                        if(response == '"Failed"'){
                            $('#remove_selected').hide();
                            $('#MainTd').html('<i class="fa fa-times-circle text-danger" aria-hidden="true" style="margin-left: 23px"></i><strong>&nbsp;&nbsp;Select&nbsp;All</strong></td>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });
    });



    //this is for single delete
    function deleteSingleProduct(td,product_id, sale_id) {
        
        var id = product_id;
        var name = $('#name'+id).text();
        var qty = $('#qty'+id).text();
        var subtotal = $('#subtotal'+id).text();
        var baseUrl = '<?php echo base_url() ?>';
        product = [];
        product[0] = product_id;

        Swal.fire({
            title: "Are you sure you want to Remove?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: baseUrl + 'index.php/admin/deleteMultipleProducts',
                    type: 'post',
                    dataType: 'html',
                    data: {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                        product_id: product,
                        sale_id: sale_id
                    },
                    success: function(response) {
                        Swal.fire("Removed!", "Proceeding...", "success");
                       
                        // Create a new <tr> element with the desired code
                        var newTR = document.createElement('tr');
                        newTR.setAttribute('id', 'newTableRow'+id);
                        newTR.setAttribute('style', 'opacity: 0.5');
                        newTR.innerHTML = '<td style="padding: 31px;width:75px;"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></td>' +
                                          '<td width="6%"><img class="img-md" src="' + baseUrl + 'uploads/product_image/trolley_default.png" height="100px" /></td>' +
                                          '<td><span style="margin-left: 25px;color:red;">' + name + '</span></td>' +
                                          '<td style="color:red;">' + qty + '</td>' +
                                          '<td style="color:red;">' + subtotal + '</td>' +
                                          '<td><i class="fa fa-trash text-danger fa-lg" aria-hidden="true" style="margin-left: 6px;"></i></td>';

                        // Replace the existing <tr> element with the new <tr> element
                        var originalTR = document.getElementById('myTable'+id);
                        originalTR.parentNode.replaceChild(newTR, originalTR);
                        if(response == '"Failed"'){
                            $('#remove_selected').hide();
                            $('#MainTd').html('<i class="fa fa-times-circle text-danger" aria-hidden="true" style="margin-left: 23px"></i><strong>&nbsp;&nbsp;Select&nbsp;All</strong></td>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });
    }


checkboxes = document.querySelectorAll('input[type="checkbox"]');
const button = document.getElementById('remove_selected');
const selectAllCheckbox = document.getElementById('selectAll');

// Add change event listeners to all checkboxes
checkboxes.forEach(function(checkbox) {
  checkbox.addEventListener('change', function() {
    // Check if any checkbox (except "Select All") is checked
    const isChecked = Array.from(checkboxes).some(function(checkbox) {
      return checkbox.checked && checkbox !== selectAllCheckbox;
    });

    // Enable or disable the button based on the checkbox selection
    button.disabled = !isChecked;
    
    // Update the "Select All" checkbox state
    selectAllCheckbox.checked = isChecked && Array.from(checkboxes).every(function(checkbox) {
      return checkbox.checked;
    });
  });
});

// "Select All" checkbox click event handler
selectAllCheckbox.addEventListener('click', function() {
  checkboxes.forEach(function(checkbox) {
    // Skip the "Select All" checkbox itself
    if (checkbox !== selectAllCheckbox) {
      checkbox.checked = selectAllCheckbox.checked;
    }
  });
  
  // Enable or disable the button based on the "Select All" checkbox state
  button.disabled = !selectAllCheckbox.checked;
});


</script>
