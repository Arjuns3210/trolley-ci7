<html>
    <head>
    <style>
    body {font-family: sans-serif;
            font-size: 10pt;
    }
    p {	
        margin: 0pt; 
    }
    table.items {
            border: 0.1mm solid #000000;
    }
    td { vertical-align: top; }
    .items td {
            border-left: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
    }
    table thead td { background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
            font-variant: small-caps;
    }
    .items td.blanktotal {
            background-color: #EEEEEE;
            border: 0.1mm solid #000000;
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
            border-bottom: 0.1mm solid #000000;
    }
    .items td.totals {
            text-align: right;
            border: 0.1mm solid #000000;
    }
    .items td.cost {
            text-align: "." center;
    }
    </style>
    </head>
    
     <?php
        	foreach($sale as $row){
                $info = json_decode($row['shipping_address'],true);
                $customerPhoneNumber  =  $info['address_number'];
                $displayNumber = maskMobileNumber($customerPhoneNumber);
                
                $user_choice = json_decode($row['user_choice'],true);
                $payment_status_array = json_decode($row['payment_status'],true);
                $payment_status = ucfirst($payment_status_array[0]['status']);
                //invoice and map
                $delivery_type = $row['delivery_type'];
                $store_data = $this->db->get_where('store_master',array('store_master_id'=>$row['store_master_id']))->row_array();
                $assign_delivery_data =  json_decode($row['assign_delivery_data'],true);
                $Curr_symbol = DEFAULT_CURRENCY_NAME;
                $delivery_date_timeslots =  json_decode($row['delivery_date_timeslot'],true);
                $user_choice = json_decode($row['user_choice'], true);
                $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                $assign_stores_db_data =  json_decode($row['assign_stores_data'],true);
                $assign_store_print = FALSE;
                }
                ?>
    <body style="direction:ltr;">
        
        <div class="col-lg-6 col-md-6 col-sm-6 pad-all">
      
                            </div>
       
    <htmlpageheader name="myheader">
    <table width="100%">
        <tr>
            <td width="50%"><img class="img-responsive logo" src="<?php echo $this->crud_model->logo('home_top_logo'); ?>" alt="Maison Galaxy Logo" width="120" height="120"></td>
            <td width="50%" style="text-align: right;">Invoice No.<br /><span style="font-weight: bold; font-size: 12pt;"> <?php echo $row['sale_code']; ?></span></td>
        </tr>
    </table>
    </htmlpageheader>
    <htmlpagefooter name="myfooter">
        <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm;"></div>
    </htmlpagefooter>
   <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
    <sethtmlpagefooter name="myfooter" value="on" />
    
    
    <div style="text-align: right">Sale Date: <?php echo date('d M, Y',$row['sale_datetime'] ); ?></div>
    <table width="100%" style="font-family: serif;overflow-x: scroll;overflow-y: scroll;" cellpadding="10">
        <tr>
            <td width="45%" style="border: 0.1mm solid #888888; "><span style="font-size: 7pt; color: #555555; font-family: sans;">Customer Details:</span><br />
                <br />Customer Name :: <?php echo $info['first_name']; ?>
                
                <br />Customer Phone :: <?php echo $displayNumber; ?>
                
                <br />City :: <?php echo $info['city']; ?>
                
                <br />Area :: <?php echo $info['area']; ?>
               
                <br />Assigned To :: <?php if(isset($assign_delivery_data) && !empty($assign_delivery_data)){echo $assign_delivery_data['name'];} ?>
               
            </td>
            
            <td width="10%">&nbsp;</td>
            <td width="45%" style="border: 0.1mm solid #888888;"><span style="font-size: 7pt; color: #555555; font-family: sans;">Order Details:</span> <br />
                <br />Delivery Date :: <?php echo date('d M, Y',strtotime($delivery_date_timeslots[0]['date']));?>
                
                <br />Timeslot :: <?php echo $delivery_date_timeslots[0]['timeslot'];?>
               
                
                <br />Payment Status ::  <?php echo  $payment_status ;?>
           
                <br />Payment Method :: 
                <?php if($info['payment_type'] == 'payInCard'){
                    echo 'Pay In Card';
                }else if($info['payment_type'] == 'payInCash'){
                    echo 'Pay In Cash';
                }else if($info['payment_type'] == 'ePaymentCard'){
                    echo 'E-Payment (Card)';
                }else if($info['payment_type'] == 'ePaymentWallet'){
                    echo 'E-Payment (Wallet)';
                }else if($info['payment_type'] == 'trolleyCredit'){
                    echo 'Trolley Credit';
                }
                ?>
                <br />Payment Date :: <?php echo (!empty($row['payment_timestamp']) && $payment_status == 'Paid') ? date('d M, Y',$row['payment_timestamp'] ) : ' ';?>
           
            </td>
        </tr>
    </table>
    <br />
    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
    <thead>
    <tr>
       <td colspan="7" align="center" style="background-color:#faae02;" > PRODUCT DETAILS </td>
    </tr>
            
    <tr>
    <td width="20%">Sku Code.</td>
    <td width="10%">Quantity</td>
    <td width="30%">Item Name</td>
    <td width="10%">Option</td>
    <td width="15%">Unit Price</td>
    <td width="15%">Amount</td>
    </tr>
    </thead>
    <tbody>
    <!-- ITEMS HERE -->
    
    <?php
        $product_details = $row['product_details'];
        
        array_multisort(array_column($product_details, 'category'), SORT_ASC,
                array_column($product_details, 'sub_category'),      SORT_ASC,
                $product_details);
        
         if(is_array($assign_stores_db_data)) { 
             $assign_store_print = TRUE;
         }else{
             $assign_store_print = FALSE;
         }
        
        $i =0;
        $j =0;
        $total = 0;
        $assign_stores_data = array();
        foreach ($product_details as $row1) {
            $i++;
            $sku_code = $this->crud_model->get_type_name_by_id('variation', $row1['variation_id'], 'sku_code');
            $quantity =  $row1['qty'];
            $variation_id =  $row1['variation_id'];
            $product_id =  $row1['product_id'];
            $brand =  $row1['brand'];
            $nameAr = $row1['name_ar'];
            $nameEng = $row1['name'];
            $supplierName = $this->crud_model->get_type_name_by_id('supplier', $row1['supplier'], 'supplier_name');
            $option = $row1['weight'];
            $unit_price =  $Curr_symbol . get_converted_currency($row1['price'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
            $subTotal_price =  $Curr_symbol .get_converted_currency($row1['subtotal'],DEFAULT_CURRENCY,$sale_currency_conversion_rate); 
            $total += $row1['subtotal'];
            
            if($assign_store_print){
                $assign_stores_data[$j]['sku_code'] = $sku_code;
                $assign_stores_data[$j]['supplier_name'] = $supplierName;
                $assign_stores_data[$j]['supplier_id'] = $row1['supplier'];
                $assign_stores_data[$j]['product_id'] = $product_id;
                $assign_stores_data[$j]['variation_id'] = $variation_id;
                $prdtkey = array_search($variation_id, array_column($assign_stores_db_data, 'variation_id'));
                $assign_stores_data[$j]['supplier_store_id'] = $assign_stores_db_data[$prdtkey]['supplier_store_id'];
            }
            
    ?>
    
    
    <tr>
    <td align="center"><?php echo $sku_code; ?><br /><?php echo $supplierName; ?></td>
    <td align="center"><?php echo $quantity; ?></td>
    <td><?php echo $nameEng; ?><br /><?php echo $nameAr; ?></td>
    <td><?php echo $option; ?><br /><?php echo $brand; ?></td>
    <td class="cost"><?php echo $unit_price; ?></td>
    <td class="cost"><?php echo $subTotal_price; ?></td>
    </tr>
        <?php $j++;
        
            }
        
        $subtotal =  $Curr_symbol .get_converted_currency($total,DEFAULT_CURRENCY,$sale_currency_conversion_rate);
        if(isset($row1['coupon_applied']) && $row1['coupon_applied'] == 'yes') {
            $couponDiscount =  $Curr_symbol .get_converted_currency($row['discount_amount'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
        }
        $sku_code = $this->crud_model->get_type_name_by_id('variation', $row1['variation_id'], 'sku_code');
            
        $serviceFees =  $Curr_symbol .get_converted_currency($row['vat'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
        $deliveryCharge =  $Curr_symbol .get_converted_currency($row['delivery_charge'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
        $grandTotal =  $Curr_symbol.get_converted_currency($row['invoice_amount'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
        
        ?>
    <!-- END ITEMS HERE -->
    <tr>
    <td class="blanktotal" colspan="4" rowspan="7"></td>
    <td class="totals">Subtotal:</td>
    <td class="totals cost"><?php echo $subtotal; ?></td>
    </tr>
    <?php if(isset($row1['coupon_applied']) && $row1['coupon_applied'] == 'yes') { ?>
    <tr>
    <td class="totals">Discount:</td>
    <td class="totals cost"><?php echo $couponDiscount; ?></td>
    </tr>
    <?php } ?>
    <tr>
    <td class="totals">Service Fees:</td>
        <td class="totals cost"><?php echo $serviceFees; ?></td>
    </tr>
    <tr>
    <td class="totals">Delivery Charge:</td>
    <td class="totals cost"><?php echo $deliveryCharge; ?></td>
    </tr>
    <tr>
    <td class="totals"><b>TOTAL:</b></td>
    <td class="totals cost"><b><?php echo $grandTotal; ?></b></td>
    </tr>
    </tbody>
    </table>
    
    <br />
    
    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; overflow-x: scroll;overflow-y: scroll;" cellpadding="8">
    <thead>
    <tr>
       <td colspan="4" align="center" style="background-color:#faae02;" > SUPPLIER PICK UP DETAILS </td>
    </tr>
            
    <tr>
    <td width="20%">Sku Code.</td>
    <td width="20%">Store Number</td>
    <td width="30%">Store Name</td>
    <td width="30%">Address</td>
    </tr>
    </thead>
    <tbody>
    <!-- ITEMS HERE -->
    
    <?php
       
        $i =0;
        if(is_array($assign_stores_data) && isset($assign_stores_data[0]) ) { 
            foreach ($assign_stores_data as $row1) {
                $i++;
                $sku_code =  $row1['sku_code'];
//                $sku_code = $this->crud_model->get_type_name_by_id('variation', $row1['variation_id'], 'sku_code');
                $storename  = $this->crud_model->get_type_name_by_id('supplier_store', $row1['supplier_store_id'], 'store_name');
                $store_number  = $this->crud_model->get_type_name_by_id('supplier_store', $row1['supplier_store_id'], 'store_number');
                $store_address  = $this->crud_model->get_type_name_by_id('supplier_store', $row1['supplier_store_id'], 'store_address');
    ?>
    
    
    <tr>
    <td align="center"><?php echo $sku_code; ?></td>
    <td align="center"><?php echo $store_number; ?></td>
    <td><?php echo $storename; ?></td>
    <td><?php echo $store_address; ?></td>
    </tr>
            <?php
                    }
                } ?>
    </tbody>
    </table>
    
    <div style="text-align: center; font-style: italic;">Invoice Generated From Trolley For Delivery Team</div>
    </body>
</html>