<link rel="stylesheet" href="<?php echo base_url(); ?>template/back//amcharts/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url(); ?>template/back//css/style.css" type="text/css">
<script src="<?php echo base_url(); ?>template/back/amcharts/amcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/amcharts/serial.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>template/back/plugins/morris-js/morris.min.js"></script>
<script src="<?php echo base_url(); ?>template/back/plugins/gauge-js/gauge.min.js"></script>

<?php

    if($this->crud_model->admin_permission('dashboard_count')) {
        $day_start_date =  date('Y-m-d 00:00:00');
        $day_end_date =  date('Y-m-d 23:59:59');
        $day_start_date_in_time = strtotime($day_start_date);
        $day_end_date_in_time = strtotime($day_end_date);

        //TOday new registration count query
        $result1 = $this->db->get_where('user',array('creation_date <= '=>$day_end_date_in_time,'creation_date > '=>$day_start_date_in_time));
        $res_1 = 0;
    if($result1 !== FALSE && isset($result1) && !empty($result1))
            $res_1 = $result1->num_rows();
        
        //Total users
        $result16 = $this->db->get('user');
        $res_16 = 0;
    if($result16 !== FALSE && isset($result16) && !empty($result16))
            $res_16 = $result16->num_rows();
        
        $res_2 = 0;
        $res_3 = 0;
        //Total male users
       $result2 = $this->db->get_where('user',array('sex'=>'M'));
        $res_2 = 0;
    if($result2 !== FALSE && isset($result2) && !empty($result2))
            $res_2 = $result2->num_rows();
        
        //Total female users
        $result3 = $this->db->get_where('user',array('sex'=>'F'));
        $res_3 = 0;
    if($result3 !== FALSE && isset($result3) && !empty($result3))
        $res_3 = $result3->num_rows();
        
        //Total other users
        $result18 = $this->db->get_where('user',array('sex'=>''));
        $res_18 = 0;
    if($result18 !== FALSE && isset($result18) && !empty($result18))
            $res_18 = $result18->num_rows();
      
        
        //Total products in system
        $result4 = $this->db->get('product');
        $res_4 = 0;
    if($result4 !== FALSE && isset($result4) && !empty($result4))
            $res_4 = $result4->num_rows();
        
        //Active Product on app
        $result5 = $this->db->get_where('product',array('status'=>'ok'));
        $res_5 = 0;
    if($result5 !== FALSE && isset($result5) && !empty($result5))
            $res_5 = $result5->num_rows();
        
        //Products Newly Added Today
        $result6 = $this->db->get_where('product',array('add_timestamp <= '=>$day_end_date_in_time,'add_timestamp > '=>$day_start_date_in_time));
        $res_6 = 0;
    if($result6 !== FALSE && isset($result6) && !empty($result6))
            $res_6 = $result6->num_rows();
        
        //Total Products In Offer Today
        $result7 = $this->db->get_where('product',array('featured'=>'yes'));
        $res_7 = 0;
    if($result7 !== FALSE && isset($result7) && !empty($result7))
            $res_7 = $result7->num_rows();
         
        //Total Orders Today
        $result8 = $this->db->get_where('sale',array('sale_datetime <= '=>$day_end_date_in_time,'sale_datetime > '=>$day_start_date_in_time));
        $res_8 = 0;
    if($result8 !== FALSE && isset($result8) && !empty($result8))
            $res_8 = $result8->num_rows();
        
        
//fetchDashBoardCount() parameters as follows:        
//PARA1 - todays date
//PARA2 - payment status
//PARA3 - delivery status
//PARA4 - order status 
//PARA5 - delivery date 
//PARA6 - isTodaysPaid -- yes|no
//PARA7 - isTodaysDeliveredOrCancelled -- yes|no

        //Orders Paid Todays Count
        $res_9 = 0;
        $res_9 = $this->crud_model->fetchDashBoardCount('','paid','','','','yes');
        
        //Pending Payment Sales Count For Today
        $res_10 = 0;
        $res_10 = $this->crud_model->fetchDashBoardCount('yes','pending');

        //Cancelled Orders Count For Today
        $res_11 = 0;
        $res_11 = $this->crud_model->fetchDashBoardCount('','','','cancelled','','','yes');

        //Revenue Generated Today From Paid Orders
        $res_12 = 0 ;
        $res_12 = $this->crud_model->dashboardAmount('paid');
    
        //Pending Payments Amount For Today
        $res_13 = 0;
        $res_13 = $this->crud_model->dashboardAmount('pending');
    
        //Total Delivery For Today
        $res_14 = 0;
        $deliveryDateToday = date('Y-m-d');
        $res_14 = $this->crud_model->fetchDashBoardCount('','','','',$deliveryDateToday);
        
        //Orders Successfully Delivered Today
        $res_15 = 0;
        $res_15 = $this->crud_model->fetchDashBoardCount('','paid','delivered','','','','yes');
        
        //Overall turnover till now
        $res_17 = 0;
        $res_17 = $this->crud_model->dashboardAmount('','yes');
        $currency = DEFAULT_CURRENCY_NAME;
        
        //User With percentage
        $userPercentage = $this->crud_model->dashboardUserPercentage();
       
        //Overall Orders in system
        $overOrders = $this->db->get('sale');
        $overOrdersCount = 0;
    if($overOrders !== FALSE && isset($overOrders) && !empty($overOrders))
            $overOrdersCount = $overOrders->num_rows();
        
    }
        
       
?>
<div id="content-container">    
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo translate('dashboard');?></h1>
    </div>
    <div id="page-content">
    <?php if($this->crud_model->admin_permission('dashboard_count')) { ?>
        <div class="row">
            <div class="col-md-12">
                <label style="font-size:20px;border-bottom:1px solid #ffcd87;margin-bottom:7px;"><?php echo translate('user_details');?></label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/user.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('New_registrations_today');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_1; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/male.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('total_male');?>(<?php echo (isset($userPercentage[0]) ?  $userPercentage[0] : 0 ).' %' ; ?>)</label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_2; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/female.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('total_female');?>(<?php echo (isset($userPercentage[1]) ?  $userPercentage[1] : 0 ).' %'; ?>)</label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_3; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/others.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('total_other');?>(<?php echo (isset($userPercentage[2]) ?  $userPercentage[2] : 0 ).' %'; ?>)</label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_18; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/total.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('total_user');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_16; ?></label>
                    </div>
                </div>
            </div>
        </div>
        <?php //for order details ?>
        <div class="row">
            <div class="col-md-12">
                <label style="font-size:20px;border-bottom:1px solid #ffcd87;margin-bottom:7px;margin-top: 50px"><?php echo translate('order_details');?></label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/order_paid.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('orders_paid_( today)');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_9; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/pending.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('pending_payments_( today )');?></label><br>
                        <label style="padding: 0;font-size: 25px;"><span style="font-weight: 700;font-size: 14px;"><?php echo $currency; ?></span>&nbsp;<?php echo $res_13; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/cancel.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('cancelled_orders_( today )');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_11; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/order.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('overall_orders');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $overOrdersCount; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/order.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('total_orders_( today )');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_8; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/order.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('total_delivery_for_( today )');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_14; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/order.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('orders_delivered_( today )');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_15; ?></label>
                    </div>
                </div>
            </div>
        </div>
        <?php //for product details ?>
        <div class="row">
            <div class="col-md-12">
                <label style="font-size:20px;border-bottom:1px solid #ffcd87;margin-bottom:7px;margin-top: 50px"><?php echo translate('product_details');?></label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/products.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('products_newly_added_today');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_6; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/products.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('total_products_in_offer_today');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_7; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/products.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('total_products');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_4; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/products.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('active_products');?><br>
                        <label style="padding: 0;font-size: 30px;"><?php echo $res_5; ?></label>
                    </div>
                </div>
            </div>
        </div>
        <?php //for revenue details ?>
        <div class="row">
            <div class="col-md-12">
                <label style="font-size:20px;border-bottom:1px solid #ffcd87;margin-bottom:7px;margin-top: 50px"><?php echo translate('revenue_details');?></label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/pending_amount.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('pending_amount_for_today');?></label><br>
                        <label style="padding: 0;font-size: 25px;"><span style="font-weight: 700;font-size: 14px;"><?php echo $currency; ?></span>&nbsp;<?php echo $res_13; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/revenue.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('revenue_generated_today');?></label><br>
                        <label style="padding: 0;font-size: 30px;"><span style="font-weight: 700;font-size: 14px;"><?php echo $currency; ?></span>&nbsp;<?php echo $res_12; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="tabBody">
                    <div class="">
                        <img src="<?php echo base_url(); ?>uploads/dashboard/overall_turnover.png" class="tabImg">
                    </div>
                    <div class="col-sm-12 text-right" style="padding: 0;">
                        <label style="font-size:14px"><?php echo translate('overall_turnover');?></label><br>
                        <label style="padding: 0;font-size: 25px;"><span style="font-weight: 700;font-size: 14px;"><?php echo $currency; ?></span>&nbsp;<?php echo $res_17; ?></label>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>




<script>
    var base_url = '<?php echo base_url(); ?>';
    var res_1 = <?php if($res_1 == 0){echo .1;} else {echo $res_1;} ?>;
    var res_1_max = <?php echo ($res_1*3.5/3+100); ?>;
    var res_2 = <?php if($res_2 == 0){echo .1;} else {echo $res_2;} ?>;
    var res_2_max = <?php echo ($res_2*3.5/3+100); ?>;
    var res_3 = <?php if($res_3 == 0){echo .1;} else {echo $res_3;} ?>;
    var res_3_max = <?php echo ($res_3*3.5/3+100); ?>;
    var res_4 = <?php if($res_4 == 0){echo .1;} else {echo $res_4;} ?>;
    var res_4_max = <?php echo ($res_4*3.5/3+100); ?>;
        
        var value_txt = currency = cost_txt = loss_txt = pl_txt =  "";
        var user_type = module =  list_cont_func  = "";
//  var currency = '<?php echo currency('','def'); ?>';
//  var cost_txt = '<?php echo translate('cost'); ?>(<?php echo currency('','def'); ?>)';
//  var value_txt = '<?php echo translate('value'); ?>(<?php echo currency('','def'); ?>)';
//  var loss_txt = '<?php echo translate('loss'); ?>(<?php echo currency('','def'); ?>)';
//  var pl_txt = '<?php echo translate('profit'); ?>/<?php echo translate('loss'); ?>(<?php echo currency('','def'); ?>)';
    
    var chartData5 = chartData1 = chartData2 = chartData3 = chartData4 = [
        {
            "country": "Default",
            "visits": "0",
            "color": "#458fd2"
        }
    ];
    
</script>
<script src="<?php echo base_url(); ?>template/back/js/custom/dashboard.js"></script>
<style>
      #actions {
        list-style: none;
        padding: 0;
      }
      #inline-actions {
        padding-top: 10px;
      }
      .item {
        margin-left: 20px;
      }
</style>