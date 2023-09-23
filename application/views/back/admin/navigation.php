<?php
$physical_check = $this->crud_model->get_type_name_by_id('general_settings','68','value');
$digital_check = $this->crud_model->get_type_name_by_id('general_settings','69','value');
?>
<nav id="mainnav-container">
    <div id="mainnav">
        <!--Menu-->
        <div id="mainnav-menu-wrap">
            <div class="nano">
                <div class="nano-content" style="overflow-x:auto;overflow-y:auto;">
                    <ul id="mainnav-menu" class="list-group">
                        <!--Category name-->
                        <li class="list-header"></li>
                        <!--Menu list item-->
                        <li <?php if($page_name=="dashboard_admin"){?> class="active-link" <?php } ?>
                                style="border-top:1px solid rgba(69, 74, 84, 0.7);">
                            <a href="<?php echo base_url(); ?>admin/">
                                <i class="fa fa-tachometer"></i>
                                <span class="menu-title">
                    <?php echo translate('dashboard');?>
                </span>
                            </a>
                        </li>
                            <?php
                                    if($physical_check == 'ok' && $digital_check !== 'ok'){
                                            if($this->crud_model->admin_permission('category') ||
                                               $this->crud_model->admin_permission('sub_category') ||
                                               $this->crud_model->admin_permission('brand') ||
                                               $this->crud_model->admin_permission('product') ||
                                               $this->crud_model->admin_permission('stock') ||
                                               $this->crud_model->admin_permission('update_products_price') ||
                                               $this->crud_model->admin_permission('update_products_discount') ||
                                               $this->crud_model->admin_permission('update_products_stock') ||
                                               $this->crud_model->admin_permission('attribute') ){
                                                    ?>
                                <!--Menu list item-->
                                <li <?php if($page_name=="category" ||
                                            $page_name=="sub_category" ||
                                            $page_name=="brand" ||
                                            $page_name=="product" ||
                                            $page_name=="stock" ||
                                            $page_name=="supplier" ||
                                            $page_name=="excel_for_product_price_update" ||
                                            $page_name=="excel_for_product_discount_update" ||
                                            $page_name=="excel_for_product_stock_update" ||
                                            $page_name=="attribute" ){?>
                                    class="active-sub"
								<?php } ?> >    
                                    <a href="#">
                                        <i class="fa fa-list"></i>
                                        <span class="menu-title">
                                            <?php echo translate('masters');?>
                                        </span>
                                        <i class="fa arrow"></i>
                                    </a>

                                    <!--PRODUCT------------------>
                                    <ul class="collapse <?php if($page_name=="category" ||
                                                                $page_name=="sub_category" ||
                                                                $page_name=="product" ||
                                                                $page_name=="brand" ||
                                                                $page_name=="stock" ||
                                                                $page_name=="supplier" ||
                                                                $page_name=="excel_for_product_price_update" ||
                                                                $page_name=="excel_for_product_discount_update" ||
                                                                $page_name=="excel_for_product_stock_update" ||
                                                                $page_name=="attribute" ){
                                                            ?>
                                                                in
                                                            <?php } ?> " >

                                    <?php   if($this->crud_model->admin_permission('category')){  ?>
                                                <li <?php if($page_name=="category"){?> class="active-link" <?php } ?> >
                                                    <a href="<?php echo base_url(); ?>admin/category">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('category');?>
                                                    </a>
                                                </li>       
                                    <?php  } ?>
                                    <?php   if($this->crud_model->admin_permission('brand')){  ?>
                                                <li <?php if($page_name=="brand"){?> class="active-link" <?php } ?> >
                                                    <a href="<?php echo base_url(); ?>admin/brand">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('brands');?>
                                                    </a>
                                                </li>       
                                    <?php  } ?>
                                    <?php   if($this->crud_model->admin_permission('sub_category')){  ?>
                                                <li <?php if($page_name=="sub_category"){?> class="active-link" <?php } ?> >
                                                    <a href="<?php echo base_url(); ?>admin/sub_category">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('sub_category');?>
                                                    </a>
                                                </li>       
                                    <?php  } ?>
                                    <?php   if($this->crud_model->admin_permission('product')){  ?>
                                                <li <?php if($page_name=="product"){?> class="active-link" <?php } ?> >
                                                    <a href="<?php echo base_url(); ?>admin/product">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('all_products');?>
                                                    </a>
                                                </li> 
                                    <?php  } ?>
                                    <?php   if($this->crud_model->admin_permission('stock')){  ?>                   
                                        <li <?php if($page_name=="stock"){?> class="active-link" <?php } ?> >
                                            <a href="<?php echo base_url(); ?>admin/stock">
                                                <i class="fa fa-circle fs_i"></i>
                                                <span class="menu-title">
                                            <?php echo translate('stock');?>
                                                </span>
                                            </a>
                                        </li> 
                                    <?php } ?>  
                                      
                                    <?php if($this->crud_model->admin_permission('supplier')){  ?>
                                    <li <?php if($page_name=="supplier"){?> class="active-link" <?php } ?>>
                                        <a href="<?php echo base_url(); ?>admin/supplier">
                                            <i class="fa fa-circle fs_i"></i>
                                            <span class="menu-title">
                                                <?php echo translate('supplier');?>
                                            </span>
                                        </a>
                                    </li>
                                     <?php  } ?>   
                                        
                                    <?php if($this->crud_model->admin_permission('update_products_price')){  ?>
                                                <li <?php if($page_name=="excel_for_product_price_update"){?> class="active-link" <?php } ?> >
                                                    <a href="<?php echo base_url(); ?>admin/updateProductsPrice">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('Update_Products_Price');?>
                                                    </a>
                                                </li> 
                                    <?php  } ?>   
                                    <?php  if($this->crud_model->admin_permission('update_products_discount')){  ?>
                                                <li <?php if($page_name=="excel_for_product_discount_update"){?> class="active-link" <?php } ?> >
                                                    <a href="<?php echo base_url(); ?>admin/updateProductsDiscount">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('Update_Products_Discount');?>
                                                    </a>
                                                </li> 
                                    <?php  } ?>   
                                    <?php   if($this->crud_model->admin_permission('update_products_stock')){  ?>
                                                <li <?php if($page_name=="excel_for_product_stock_update"){?> class="active-link" <?php } ?> >
                                                    <a href="<?php echo base_url(); ?>admin/updateProductsStock">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('Update_Products_stock');?>
                                                    </a>
                                                </li> 
                                    <?php  }  ?>   
                                            
                                                
                                    <?php  if($this->crud_model->admin_permission('attribute')){  ?>
                                                <li <?php if($page_name=="attribute"){?> class="active-link" <?php } ?> >
                                                    <a href="<?php echo base_url(); ?>admin/attribute">
                                                        <i class="fa fa-circle fs_i"></i>
                                                        <?php echo translate('attribute');?>
                                                    </a>
                                                </li>       
                                    <?php  }  ?>
                                                
                                                
                                    </ul>
                                </li>

                                <?php
                                        }
                                    }
                                ?>
                                
                        <?php  if($this->crud_model->admin_permission('sale')){ 
                                $callingFunction = ($_SESSION['role_id'] == 9) ? 'storeSales': 'sales';
//                                $page_name = ($_SESSION['role_id'] == 9) ? 'storeSales': 'sales';
                                
                            ?>
                            <li <?php if($page_name=="sales" || $page_name=="storeSales" ){?> class="active-link" <?php } ?>>
                                <a href="<?php echo base_url(); ?>admin/<?php echo $callingFunction; ?>">
                                    <i class="fa fa-usd"></i>
                                    <span class="menu-title">
                                        <?php echo translate('orders');?>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>  
                                
                        <?php /* if($this->crud_model->admin_permission('user')){ ?>
                            <li <?php if($page_name=="user"){?> class="active-link" <?php } ?>>
                                <a href="<?php echo base_url(); ?>admin/user">
                                    <i class="fa fa-users"></i>
                                    <span class="menu-title">
                                        <?php echo translate('customers');?>
                                    </span>
                                </a>
                            </li>
                            <?php }  */ ?>
                            
                         <?php
                        if($this->crud_model->admin_permission('user')){
                                                ?>
                            <!--Menu list item-->
                            <li <?php if($page_name=="user" || $page_name == 'pending_customers' 
                                    ){?>
                                class="active-sub"
                                                            <?php } ?> >    
                                <a href="#">
                                    <i class="fa fa-list"></i>
                                    <span class="menu-title">
                                        <?php echo translate('customers');?>
                                    </span>
                                    <i class="fa arrow"></i>
                                </a>

                                <!--PRODUCT------------------>
                                <ul class="collapse <?php if($page_name=="user" || $page_name =='excel_for_trolley_wallet' 
                                                            ){
                                                        ?>
                                                            in
                                                        <?php } ?> " >

                                <?php   if($this->crud_model->admin_permission('user')){  ?>
                                            <li <?php if($page_name=="user"){?> class="active-link" <?php } ?> >
                                                <a href="<?php echo base_url(); ?>admin/user">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('customers_list');?>
                                                </a>
                                            </li>       
                                <?php  } ?>
                                <!-- <?php   if($this->crud_model->admin_permission('user_add_wallet_balance') ||  $this->crud_model->admin_permission('user_reduce_wallet_balance') ){  ?>
                                            <li <?php if($page_name=="excel_for_trolley_wallet"){?> class="active-link" <?php } ?> >
                                                <a href="<?php echo base_url(); ?>admin/importTrolleyBalance">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('trolley_balance_import');?>
                                                </a>
                                            </li>       
                                <?php  } ?> -->

                                </ul>
                            </li>

                        <?php
                            }
                        ?>    
                        
                    
                        <?php if($this->crud_model->admin_permission('slides')){ ?>
                         <li <?php if($page_name=="slides"){?> class="active-link" <?php } ?> >
                            <a href="<?php echo base_url(); ?>admin/slides">
                                <i class="fa fa-image"></i>
                                <span class="menu-title">
                            <?php echo translate('Banners');?>
                                </span>
                            </a>
                        </li> 
                        <?php } ?>
                        
                        <?php if($this->crud_model->admin_permission('coupon')){ ?>
                         <li <?php if($page_name=="coupon"){?> class="active-link" <?php } ?> >
                            <a href="<?php echo base_url(); ?>admin/coupon">
                                <i class="fa fa-smile-o"></i>
                                <span class="menu-title">
                            <?php echo translate('coupon');?>
                                </span>
                            </a>
                        </li>   
                        <?php } ?>
                        <?php if($this->crud_model->admin_permission('collection')){ ?>    
                        <li <?php if($page_name=="collection"){?> class="active-link" <?php } ?> >
                            <a href="<?php echo base_url(); ?>admin/collection">
                                <i class="fa fa-tasks"></i>
                                <span class="menu-title">
                            <?php echo translate('home_collection');?>
                                </span>
                            </a>
                        </li>  
                       <?php } ?>
                         <?php if($this->crud_model->admin_permission('newsletter')){ ?>  
                        <li <?php if($page_name=="newsletter"){?> class="active-link" <?php } ?> >
                            <a href="<?php echo base_url(); ?>admin/newsletter">
                                <i class="fa fa-envelope-o"></i>
                                <span class="menu-title">
                            <?php echo translate('newsletter');?>
                                </span>
                            </a>
                        </li>  
                        <?php } ?>
                        <?php if($this->crud_model->admin_permission('enquiry')){ ?>   
                        <?php /*  /* commented by sagar - 26-06
                        <li <?php if($page_name=="enquiry"){?> class="active-link" <?php } ?> >
                            <a href="<?php echo base_url(); ?>admin/enquiry">
                                <i class="fa fa-envelope-o"></i>
                                <span class="menu-title">
                            <?php echo translate('enquiry');?>
                                </span>
                            </a>
                        </li>  
                         */ ?>
                        <li <?php if($page_name=="ticket"){?> class="active-link" <?php } ?> >
                            <a href="<?php echo base_url(); ?>admin/enquiries">
                                <i class="fa fa-envelope-o"></i>
                                <span class="menu-title">
                            <?php echo translate('enquiries');?>
                                </span>
                            </a>
                        </li>  
                        <?php } ?>
                        
                        <?php if($this->crud_model->admin_permission('enquiry')){ ?>
                            <li <?php if($page_name=="enquiry"){?> class="active-link" <?php } ?> >
                                <a href="<?php echo base_url(); ?>admin/contact_us">
                                    <i class="fa fa-envelope-o "></i>
                                    <span class="menu-title">
                                        <?php echo translate('contact_us');?>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if($this->crud_model->admin_permission('timeslots')){ ?>
                            <li <?php if($page_name=="timeslots"){?> class="active-link" <?php } ?> >
                                <a href="<?php echo base_url(); ?>admin/timeslots">
                                    <i class="fa fa-clock-o "></i>
                                    <span class="menu-title">
                                        <?php echo translate('timeslots');?>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if($this->crud_model->admin_permission('city')){ ?>
                            <li <?php if($page_name=="city"){?> class="active-link" <?php } ?> >
                                <a href="<?php echo base_url(); ?>admin/city">
                                    <i class="fa fa-list "></i>
                                    <span class="menu-title">
                                        <?php echo translate('city');?>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if($this->crud_model->admin_permission('suggested')){ ?>
                            <li <?php if($page_name=="suggested"){?> class="active-link" <?php } ?> >
                                <a href="<?php echo base_url(); ?>admin/suggested">
                                    <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
                                    <span class="menu-title">
                                        <?php echo translate('suggested_products');?>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if($this->crud_model->admin_permission('area')){ ?>
                            <li <?php if($page_name=="area"){?> class="active-link" <?php } ?> >
                                <a href="<?php echo base_url(); ?>admin/area">
                                    <i class="fa fa-list "></i>
                                    <span class="menu-title">
                                        <?php echo translate('area');?>
                                    </span>
                                </a>
                            </li>
			<?php } ?>
                        <?php /*if($this->crud_model->admin_permission('service_charge')){ ?>
                            <li <?php if($page_name=="service_charge"){?> class="active-link" <?php } ?> >
                                <a href="<?php echo base_url(); ?>admin/service_charge">
                                    <i class="fa fa-list "></i>
                                    <span class="menu-title">
                                        <?php echo translate('service_charge');?>
                                    </span>
                                </a>
                            </li>
			<?php }*/ ?>
                        
                        <?php
                        if($this->crud_model->admin_permission('report') || $this->crud_model->admin_permission('day_sale_report') || 
                            $this->crud_model->admin_permission('supplier_sale_report') ||  $this->crud_model->admin_permission('product_stock_report')
                            || $this->crud_model->admin_permission('bill_of_qty_report') 
                            || $this->crud_model->admin_permission('bill_of_qty_store_report') 
                            || $this->crud_model->admin_permission('bill_of_qty_date_range_report') 
                            || $this->crud_model->admin_permission('bill_of_qty_date_range_by_store_report') 
                            || $this->crud_model->admin_permission('financial_report') 
                            || $this->crud_model->admin_permission('customer_report') 
                            || $this->crud_model->admin_permission('customer_wallet_report') 
                                    
                        )
                        {                        ?>
                            <li <?php if($page_name=="report" ||
                                        $page_name=="report_day_sale" ||
                                        $page_name=="report_supplier_sale" ||
                                        $page_name=="report_for_stock"||
                                        $page_name=="report_for_customer"||
                                        $page_name=="report_for_delivery" ||
                                        $page_name=="report_for_product_sale" ||
                                        $page_name=="report_bill_of_qty" || 
                                        $page_name=="report_bill_of_qty_date_range_store" || 
                                        $page_name=="report_bill_of_qty_by_store" ||
                                       $page_name=="report_total_orders" ||
                                       $page_name=="report_for_financial" ||
                                        $page_name=="report_for_user_orders" ||
                                        $page_name=="total_order_revenue_report" ||
                                        $page_name=="customer_delivery_address_report" || 
                                        $page_name=="customer_wallet_report"
							            ){?>
                                class="active-sub"
							<?php } ?>>
                                <a href="#">
                                    <i class="fa fa-file-text"></i>
                                    <span class="menu-title">
                                    <?php echo translate('reports');?>
                        </span>
                                    <i class="fa arrow"></i>
                                </a>

                                <!--REPORT-------------------->
                                <ul class="collapse <?php if($page_name=="report" ||
                                                            $page_name=="report_day_sale"||
                                                            $page_name=="report_supplier_sale" || 
                                                            $page_name=="report_for_stock" ||
                                                            $page_name=="report_for_customer"||
                                                            $page_name=="report_for_delivery" ||
                                                            $page_name=="report_for_product_sale" ||
                                                            $page_name=="report_bill_of_qty" ||
                                                            $page_name=="report_bill_of_qty_date_range_store" ||
                                                            $page_name=="report_bill_of_qty_by_store" || 
                                       			    $page_name=="report_total_orders" ||
                                       			$page_name=="report_for_financial" ||
                                                            $page_name=="report_for_user_orders" ||
                                                            $page_name=="total_order_revenue_report" ||
                                                            $page_name=="customer_delivery_address_report" ||
                                                            $page_name=="customer_wallet_report" 
                                                        ){?>
                                                                 in
                                                                    <?php } ?> ">

	                                <?php
	                                if($this->crud_model->admin_permission('day_sale_report')){
		                                ?>
                                        <li <?php if($page_name=="report_day_sale"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/daySaleReport/">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('day_Sale_report');?>
                                            </a>
                                        </li>

					<li <?php if($page_name=="report_total_orders"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/totalOrderReport/">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('total_orders_report');?>
                                            </a>
                                        </li>
                                        
	                                <?php }  
                                        ?>
                                        
                                        <?php 
	                                if($this->crud_model->admin_permission('supplier_sale_report')){
		                                ?>
                                        <li <?php if($page_name=="report_supplier_sale"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/supplierSaleReport/">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('Supplier Sale Report');?>
                                            </a>
                                        </li>
	                                <?php }  
                                        ?>
                                        
                                        <?php 
	                                if($this->crud_model->admin_permission('product_stock_report')){
		                                ?>
                                        <li <?php if($page_name=="report_for_stock"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/stockReport/">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('Product Stock Report');?>
                                            </a>
                                        </li>
	                                <?php }  
                                        ?>
                                        
                                        <?php 
	                                if($this->crud_model->admin_permission('customer_report')){
		                                ?>
                                        <li <?php if($page_name=="report_for_customer"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/customerReport/">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('Customer Report');?>
                                            </a>
                                        </li>

					 <li <?php if($page_name=="report_for_user_orders"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/userOrdersReport/">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('Customer Order Report');?>
                                            </a>
                                        </li>
	                                <?php }  
                                        ?>
                                        
                                        <?php  //added by rushikesh - 24-07-2020 - START
                                        if($this->crud_model->admin_permission('Customer_delivery_address_report')){
                                            ?>
                                            <li <?php if($page_name=="customer_delivery_address_report"){?> class="active-link" <?php  } ?> >
                                                <a href="<?php echo base_url(); ?>admin/customerAddressReport/">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('Customer Address Report');?>
                                                </a>
                                            </li>
                                        <?php }  
                                        //added by rushikesh - 24-07-2020 - END
                                        ?>
                                        
                                        <?php  //added by sagar - 24-08-2020 - START
                                        if($this->crud_model->admin_permission('customer_wallet_report')){
                                            ?>
                                            <li <?php if($page_name=="customer_wallet_report"){?> class="active-link" <?php  } ?> >
                                                <a href="<?php echo base_url(); ?>admin/customerWalletReport">
                                                    <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('Customer Wallet Report');?>
                                                </a>
                                            </li>
                                        <?php }  
                                        //added by sagar - 24-08-2020 - END
                                        ?>
                                        
                                        <?php 
	                                if($this->crud_model->admin_permission('delivery_report')){
		                                ?>
                                        <li <?php if($page_name=="report_for_delivery"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/deliveryReport/">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('Delivery Report');?>
                                            </a>
                                        </li>
	                                <?php }  
                                        ?>
                                        
                                        <?php 
	                                if($this->crud_model->admin_permission('bill_of_qty_report')){
		                                ?>
                                        <li <?php if($page_name=="report_for_product_sale"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/productSaleReport">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('bill_of_qty_report');?>
                                            </a>
                                        </li>
                                        <?php }  
                                        ?>
                                         <?php 
	                                if($this->crud_model->admin_permission('bill_of_qty_date_range_report')){
		                                ?>
                                        
                                        <li <?php if($page_name=="report_bill_of_qty"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/billQtyReport">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('bill_of_qty_on_date_range');?>
                                            </a>
                                        </li>
	                                <?php }  
                                        ?>
                                         <?php 
	                                if($this->crud_model->admin_permission('bill_of_qty_date_range_by_store_report')){
		                                ?>
                                        
                                        <li <?php if($page_name=="report_bill_of_qty_date_range_store"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/billQtyDateRangeStoreReport">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('BOQ_date_range_by_store');?>
                                            </a>
                                        </li>
	                                <?php }  
                                        ?>
                                        
					<?php 
	                                if($this->crud_model->admin_permission('bill_of_qty_store_report') && $_SESSION['role_id'] == 9){
		                                ?>
                                        
                                        <li <?php if($page_name=="report_bill_of_qty_by_store"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/billQtyStoreReport">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('bill_of_qty_by_store');?>
                                            </a>
                                        </li>
	                                <?php }  
                                        ?>
                                        
                                        <?php 
	                                if($this->crud_model->admin_permission('financial_report')){
		                                ?>
                                        
                                        <li <?php if($page_name=="report_for_financial"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/financialReport">
                                                <i class="fa fa-circle fs_i"></i>
				                                <?php echo translate('financial_report');?>
                                            </a>
                                        </li>
	                                <?php }  
                                        ?>
                                    <?php 
                                    //added by rushikesh - 24-07-2020 - START   
                                    if($this->crud_model->admin_permission('total_order_revenue_report')){
                                        ?>
                                        <li <?php if($page_name=="total_order_revenue_report"){?> class="active-link" <?php  } ?> >
                                            <a href="<?php echo base_url(); ?>admin/totalRevenueReport/">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('Revenue Report');?>
                                            </a>
                                        </li>
                                    <?php }  
                                     //added by rushikesh - 24-07-2020 - END
                                        ?>
                                        
                                        
                                        
                                        
                                </ul>
                            </li>
							<?php
                                    }
                            ?>
                        
                        
                        
                        <?php // added by sagar : FOR Notification 28-01 START?>
                        <?php /*if($this->session->userdata('admin_login')){ ?>   
                        <li <?php if($page_name=="notification"){?> class="active-link" <?php } ?> >
                            <a href="<?php echo base_url(); ?>admin/notification">
                                <i class="fa fa-paper-plane"></i>
                                <span class="menu-title">
                            <?php echo translate('notification');?>
                                </span>
                            </a>
                        </li>  
                        <?php } */ ?>
                         <?php // added by sagar : FOR Notification 28-01 END ?>
                        <?php if($this->crud_model->admin_permission('general_setting')){ ?>   
                        <li <?php if($page_name=="general_setting"){?> class="active-link" <?php } ?> >
                            <a href="<?php echo base_url(); ?>admin/general_setting">
                                <i class="fa fa-cog"></i>
                                <span class="menu-title">
                            <?php echo translate('general_setting');?>
                                </span>
                            </a>
                        </li>  
                        <?php } ?>
                         <?php
                            if($this->crud_model->admin_permission('role') || $this->crud_model->admin_permission('admin') ){ ?>
                            <li <?php if($page_name=="role" || $page_name=="admin" ){  ?>
                                class="active-sub"
                            <?php } ?> >
                                <a href="#">
                                    <i class="fa fa-user"></i>
                                        <span class="menu-title">
                                        <?php echo translate('staffs');?>
                                        </span>
                                    <i class="fa arrow"></i>
                                </a>

                                <ul class="collapse <?php if($page_name=="admin" || $page_name=="role"){?>
                                                     in
                                    <?php } ?>" >

                                    <?php  if($this->crud_model->admin_permission('admin')){ ?>
                                                <li <?php if($page_name=="admin"){?> class="active-link" <?php } ?> >
                                                    <a href="<?php echo base_url(); ?>index.php/admin/admins/">
                                                        <i class="fa fa-circle fs_i"></i>
                                                    <?php echo translate('all_staffs');?>
                                                    </a>
                                                </li>
                                                    <?php
                                                }
                                            ?>
                                        <?php if($this->crud_model->admin_permission('role')){ ?>
                                        <!--Menu list item-->
                                        <li <?php if($page_name=="role"){?> class="active-link" <?php } ?> >
                                            <a href="<?php echo base_url(); ?>index.php/admin/role/">
                                                <i class="fa fa-circle fs_i"></i>
                                                <?php echo translate('staff_permissions');?>
                                            </a>
                                        </li>
                                            <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                    
                         
                        <li <?php if($page_name=="manage_admin"){?> class="active-link" <?php } ?> >
                            <a href="<?php echo base_url(); ?>admin/manage_admin/">
                                <i class="fa fa-lock"></i>
                                <span class="menu-title">
                            <?php echo translate('manage_admin_profile');?>
                                </span>
                            </a>
                        </li>
                        
                       
						
						

                </div>
            </div>
        </div>
    </div>
</nav>
<style>
    .activate_bar{
        border-left: 3px solid #1ACFFC;
        transition: all .6s ease-in-out;
    }
    .activate_bar:hover{
        border-bottom: 3px solid #1ACFFC;
        transition: all .6s ease-in-out;
        background:#1ACFFC !important;
        color:#000 !important;
    }
    ul ul ul li a{
        padding-left:80px !important;
    }
    ul ul ul li a:hover{
        background:#2f343b !important;
    }
</style>
