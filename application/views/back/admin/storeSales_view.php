<div class="panel-body ">
    <div class="tab-base"> 
        <?php
        	foreach($sale as $row){
                $info = json_decode($row['shipping_address'],true);
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
             
               
        ?>

        <div class="col-md-2"></div>
        <div class="col-md-8 bordered print">
            <div class="tab-content">
                <div id="full" class="tab-pane fade active in">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 pad-all">
                                <img class="img-responsive logo" src="<?php echo $this->crud_model->logo('home_top_logo'); ?>" alt="Maison Galaxy Logo" width="55%">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 pad-all">
                                <b class="pull-right">
                                    <?php /* echo translate('invoice_no:');?> : <?php //echo $this->crud_model->get_sale_code($row['sale_id']); */?>
                                    <?php echo translate('invoice_no:');?> : <?php echo $row['sale_code']; ?>
                                </b>
                                <br>
                                <b class="pull-right">
                                    <?php echo translate('date_:');?> <?php echo date('d M, Y',$row['sale_datetime'] );?>
                                </b>
                            </div>
                        </div>
                        
                        <div class="col-lg-12 col-md-12 col-sm-12 pad-top">
                           
                            <div class="col-lg-6 col-md-6 col-sm-6">
                            <!--Panel heading-->
                                <div class="panel panel-bordered-grey shadow-none">
                                    <div class="panel-heading">
                                        <h1 class="panel-title"><?php echo translate('order_detail');?></h1>
                                    </div>
                                    <!--List group-->
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td><b><?php echo translate('payment_status');?></b></td>
                                                <td><?php echo  $payment_status ; //translate($this->crud_model->sale_payment_status($row['sale_id'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b><?php echo translate('payment_method');?></b></td>
                                                <td>
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
                                                </td>
                                                <tr>
                                                    <td><b><?php echo translate('payment_date');?></b></td>
                                                    <td><?php echo (!empty($row['payment_timestamp']) && $payment_status == 'Paid') ? date('d M, Y',$row['payment_timestamp'] ) : ' - ';?></td>
                                                </tr>
                                                <tr>
                                                    <td><b><?php echo translate('delivery_date');?></b></td>
                                                    <td><?php echo $delivery_date_timeslots[0]['date']?></td>
                                                </tr>
                                                <tr>
                                                    <td><b><?php echo translate('delivery_timeslote');?></b></td>
                                                    <td><?php echo $delivery_date_timeslots[0]['timeslot']?></td>
                                                </tr>
                                             
                                            </tr>
                                        </tbody>
                                    </table>    
                                </div>
                            </div>
                       </div>
                    </div>

                    <div class="panel-body" id="demo_s">
                        <div class="fff panel panel-bordered panel-dark shadow-none">
                            <div class="panel-heading">
                                <h1 class="panel-title"><?php echo translate('payment_invoice');?></h1>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo translate('no');?></th>
                                            <th><?php echo translate('item');?><br> sku-code</th>
                                            <th><?php echo translate('item (Ar)');?></th>
                                            <th><?php echo translate('brand');?></th>
                                            <th><?php echo translate('supplier_name');?></th>
                                            <th><?php echo translate('options');?></th>
                                            <th><?php echo translate('quantity');?></th>
                                            <th><?php echo translate('unit_cost');?></th>
                                            <th><?php echo translate('total');?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $product_details = json_decode($row['product_details'], true);
                                            
                                            array_multisort(array_column($product_details, 'category'), SORT_ASC,
                                                             array_column($product_details, 'sub_category'),      SORT_ASC,
                                            $product_details);
                                            
                                            //PRODUCT LIST BASED ON CATEGORY - START
                                            $categories = array_unique(array_column($product_details, 'category'));
                                            $product_details =  array_values($product_details);
                                            //PRODUCT LIST BASED ON CATEGORY - END
                                            
                                            $i =0;
                                            $total = 0;
                                            $service_Fee = 0;
                                            foreach ($product_details as $prodKey => $row1) {
                                                if($mapped_supplier_id != $row1['supplier']){
                                                    continue;
                                                }
                                                $i++;
                                                
//                                                $service_Fee  += ($row1['tax'] * $row1['qty']);
                                             
                                        ?>
                                        <?php //added by sagar : START - 30-06-2020
                                        if(isset($categories[$prodKey])){
                                            $category_name_in_en = $this->crud_model->get_type_name_by_id('category', $row1['category'], 'category_name'); 
                                            $category_name_in_ar = $this->crud_model->get_type_name_by_id('category', $row1['category'], 'category_name_ar'); 
                                            ?>
                                        <tr>
                                            <th colspan="9">
                                                <span style="float: left;"><?php echo 'Category : '.$category_name_in_en; ?></span>
                                                <span style="float: right;"><?php echo $category_name_in_ar; ?></span>
                                            </th>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row1['name']; ?> <br/> 
                                                <?php echo '<b>'.$this->crud_model->get_type_name_by_id('variation', $row1['variation_id'], 'sku_code'); '<b>'?> 
                                            </td>
                                            <td><?php echo $row1['name_ar']; ?> <br/> 
                                            <td><?php echo $row1['brand']; ?> <br/> 
                                            <td><?php echo $this->crud_model->get_type_name_by_id('supplier', $row1['supplier'], 'supplier_name');; ?> </td>
                                            <td>
                                                <?php 
                                                    $all_o = json_decode($row1['option'],true);
                                                  if(count($all_o) > 0) {
                                                    if(isset($all_o['color']) && !empty($all_o['color'])){
                                                    $color = $all_o['color']['value'];
                                                        if($color){
                                                             echo $all_o['color']['title'].' :'; 
                                                ?>
                                                <div style="background:<?php echo $color; ?>; height:25px; width:25px;" ></div>
                                                <?php
                                                    }
                                                    }
                                                ?>
                                                <?php
                                                    foreach ($all_o as $l => $op) {
                                                        if($l !== 'color' && $op['value'] !== '' && $op['value'] !== NULL){
                                                ?>
                                                    <?php echo $op['title'] ?> : 
                                                    <?php 
                                                        if(is_array($va = $op['value'])){ 
                                                            echo $va = join(', ',$va); 
                                                        } else {
                                                            echo $va;
                                                        }
                                                    ?>
                                                    <br>
                                                <?php
                                                        }
                                                    }
                                                  }else { 
                                                      echo $row1['weight'];
                                                  }
                                                  
                                                  
                                                ?>
                                                    
                                               
                                            </td>
                                            <td><?php echo $row1['qty'] /*.' ('.$row1['unit'].')'*/ ; ?></td>
                                            <td><?php echo $Curr_symbol . get_converted_currency($row1['price'],DEFAULT_CURRENCY,$sale_currency_conversion_rate); ?></td>
                                            <td><?php echo $Curr_symbol .get_converted_currency($row1['subtotal'],DEFAULT_CURRENCY,$sale_currency_conversion_rate); $total += $row1['subtotal']; ?></td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="col-lg-6 col-md-6 col-sm-6 pull-right margin-top-20">
                                    <div class="panel panel-colorful panel-grey shadow-none">
                                        <table class="table" border="0">
                                            <tbody>
                                                <tr>
                                                    <td><b><?php echo translate('total_amount');?></b></td>
                                                    <td><?php echo $Curr_symbol .get_converted_currency($total,DEFAULT_CURRENCY,$sale_currency_conversion_rate); ?></td>
                                                </tr>
                                               
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>  
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                 <!--/A section removed as it is not in use-->
            </div>
<!--            <div class="row" style="height:300px;" id="mapa"></div>-->
        </div>
        <div class="col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2 print_btn">
            <span class="btn btn-success pull-right btn-md btn-labeled fa fa-reply margin-top-10"
                onclick="print()" >
                    <?php echo translate('print');?>
            </span>
        </div>
    </div>
</div>
<!--End Invoice Footer-->
<?php
	}
?>
<style>
@media print {
	.print_btn{
		display:none;	
	}
    #navbar-container{
        display: none;
    }
    #page-title{
        display: none;
    }
	#mapa{
		display: none;
	}
	.panel-heading{
		display: none;
	}
    .print{
        width: 106%;
    }
    .col-md-6{
        width: 50%;
        float: left;
    }
}
</style>
