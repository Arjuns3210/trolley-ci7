<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {

    function __construct()
    {
       parent::__construct();
       $this->load->database();
       $this->load->model('messaging_model');
    }
    
    function assignStoresForOrders(){
        $condition = ' assign_store_handled  = "No" ';
        $condition .= ' AND order_status  != "cancelled"';
        $condition .= ' AND '.$this->getTimeslotsDates(date('Y-m-d'),1);
        //$condition .= ' AND sale_id IN (2197) ';
        $payment_status = '"status":"failed"';
        $condition .= " AND payment_status  NOT LIKE '%".$payment_status."%' ";
        $data = $this->crud_model->get_data('sale',$condition);
        if(is_array($data) && !empty($data[0])){
            foreach($data as $key => $val){
                $supplierStoresArray = $assign_store_array = array();
                $isRoundRobinRequired = FALSE;
                $sale_id        = $val['sale_id'];
                $product_details = json_decode($val['product_details'],true);
                $product_details =  array_values($product_details);
                $delivery_status = json_decode($value['delivery_date_timeslot'], true);
                $delivery_date = $delivery_status[0]['date'];
                $delivery_timeslot = $delivery_status[0]['timeslot'];
                    
                
                
                if(is_array($product_details) && COUNT($product_details) > 0) {
                    $supplierIds    =  explode(',',$val['supplier_ids']);
                    $supplier_ids_count = count($supplierIds);
                    $supplierStores= $dbStores= $this->crud_model->getSupplierStoreForCron($val['city_id'],$val['area_id'],$val['supplier_ids']);
                    

                    //add a check here if store not has area assigned return back;
                    if((!is_array($supplierStores)) || (count($supplierStores) < $supplier_ids_count)){
                        continue;
                    }
                    
                    //making round robin work here
                    if($supplier_ids_count >= 1){
                        foreach($supplierStores as $storeKey=>$storeVal){
                            $supplier_store_ids = $storeVal['supplier_store_ids'];
                            $supplier_store_ids_array = explode(',',$supplier_store_ids);
                            $supplier_store_ids_count = count($supplier_store_ids_array);
                            if($supplier_store_ids_count > 1){
                                $isRoundRobinRequired = TRUE;
                                $supplierStoresEntry = $this->crud_model->getSupplierStoreForRoundRobin($supplier_store_ids,$delivery_date);
                                $dbStores[$storeKey]['supplier_store_ids'] = $supplierStoresEntry[0]['supplier_store_id'];
                            }
                        }
                    }

                    foreach ($product_details as $keyy => $value) {
                        $arrayKey = array_search($value['supplier'], array_column($dbStores, 'supplier_id'));
                        $supplier_store_ids =  $dbStores[$arrayKey]['supplier_store_ids'];
                        
                        $supplier_store_id = $supplier_store_ids;
                        array_push($supplierStoresArray,$supplier_store_id);

                        $assign_store_array[] =  array(
                            'supplier_store_id'=>$supplier_store_id,
                            'supplier_id'=>$value['supplier'],
                            'product_id'=>$value['product_id'],
                            'variation_id'=>$value['variation_id'],
                        );
                        
			//update supplier store in cart 
                        $cartUpdate = array( 'supplier_store_id'=>$supplier_store_id);
                        $this->db->where('sale_id', $sale_id);
                        $this->db->where('product_id', $value['product_id']);
                        $this->db->where('variation_id', $value['variation_id']);
                        $this->db->update('cart', $cartUpdate);
                        }


                    $saleCronUpdate = array(
                    'assign_stores_data' => json_encode($assign_store_array),
                    'supplier_store_ids' => implode(',',$supplierStoresArray),
                    'assign_store_handled' => 'Yes',
                    'cronrun_for_stores' => date('Y-m-d H:i:s'),
                    );

                    $this->db->where('sale_id', $sale_id);
                    $this->db->update('sale', $saleCronUpdate);
                }
            }
        }
    }
    
    function assignDeliveryForOrders(){
        $smsTrigger = TRUE;
	$payment_status = '"status":"failed"';
        $delivery_status = '"status":"delivered"';
        $condition = ' assign_store_handled  = "Yes" ';
        $condition .= ' AND assign_delivery_handled  = "No" ';
        $condition .= ' AND order_status  != "cancelled" ';
        $condition .= ' AND admin_id=0   ';
        $condition .= ' AND '.$this->getTimeslotsDates(date('Y-m-d'),0);
        $condition .= " AND payment_status  NOT LIKE '%".$payment_status."%' ";
        $condition .= " AND delivery_status  NOT LIKE '%".$delivery_status."%' ";
        $data = $this->crud_model->get_data('sale',$condition);
        if(is_array($data) && !empty($data[0])){
            foreach($data as $key => $val){
                $sale_id        = $val['sale_id'];
                $sale_code      = $val['sale_code'];
                $city_id        = $val['city_id'];
                $area_id        = $val['area_id'];
                $delivery_status = json_decode($val['delivery_date_timeslot'], true);
                $delivery_date = $delivery_status[0]['date'];
                $delivery_timeslot = $delivery_status[0]['timeslot'];
                
                
                //$conditionN = " role = 4 AND assign_orders = 'yes' AND status='Active' " ;
               // $conditionN .= " AND city_id = ". $this->db->escape($city_id);
                //$conditionN .= " AND area_ids like '%\"".$area_id."\"%' " ;
                $deliveryTeam = $this->crud_model->getDeliveryBoyForRoundRobin($city_id,$area_id,$delivery_date);
                if(is_array($deliveryTeam) && !empty($deliveryTeam[0])){
                    $name = $deliveryTeam[0]['name'];
                    $phone = $deliveryTeam[0]['phone'];
                    $admin_id = $deliveryTeam[0]['admin_id'];
                    $assign_delivery_array =  array(
                                'admin_id'=>$admin_id,
                                'name'=>$name,
                                'phone'=>$phone,
                    );
                    
                    $new_delivery_status= array();
                    $new_delivery_status[] = array(
                                'admin' => '',
                                'status' => 'process',
                                'comment' => 'pending to process by auto cron',
                                'delivery_time' => date('Y-m-d H:i:s'),
                    );


                    $saleCronUpdate = array(
                    'assign_delivery_data' => json_encode($assign_delivery_array),
                    'admin_id' => $admin_id,
                    'delivery_status'=>json_encode($new_delivery_status),   
                    'assign_delivery_handled' => 'Yes',
                    'cronrun_for_delivery' => date('Y-m-d H:i:s'),
                    );
		   
                    $this->db->where('sale_id', $sale_id);
                    $isUpdated = $this->db->update('sale', $saleCronUpdate);
                    if($smsTrigger && $isUpdated){
                        //SMS trigger to delivery boy pending
                        $shipping_data = json_decode($val['shipping_address'],true);
                        $shipping_coordinates = $shipping_data['langlat'];
                        $this->sendDeliveryBoySMS($sale_code,$shipping_coordinates,$phone,$dateTimeslots);
                        
                    }
                    
                } //delivery team end
                
            } // data loop end
              
        }
      
    }

    function sendSMStoCustomers(){
        $smsTrigger = TRUE;
        $payment_status = '"status":"failed" ';
        $delivery_status = '"status":"delivered" ';
        $condition = ' assign_store_handled  = "Yes" ';
        $condition .= ' AND assign_delivery_handled  = "Yes" ';
        $condition .= ' AND order_status  != "cancelled" ';
        $condition .= ' AND '.$this->getTimeslotsDates(date('Y-m-d'),0);
        $condition .= " AND payment_status  NOT LIKE '%".$payment_status."%' ";
        $condition .= " AND delivery_status  NOT LIKE '%".$delivery_status."%' ";
        $data = $this->crud_model->get_data('sale',$condition);
        
        if(is_array($data) && !empty($data[0])){
            foreach($data as $key => $val){
                $sale_id        = $val['sale_id'];
                $sale_code      = $val['sale_code'];
                $verification_code      = $val['verification_code'];
                $city_id        = $val['city_id'];
                $area_id        = $val['area_id'];
                $delivery_status = json_decode($val['delivery_date_timeslot'], true);
                $delivery_date = $delivery_status[0]['date'];
                $delivery_timeslot = $delivery_status[0]['timeslot'];
                $dateTimeslots = $delivery_date.' '.$delivery_timeslot;
                 if($smsTrigger){
                        $shipping_data = json_decode($val['shipping_address'],true);
                        //SMS trigger to User as order is under processing
                        $customerMobileNo = $shipping_data['phone_number'];
                        $sms_type = 'delivery_status_proceed_cron';
                        $this->sendCustomerSMS($sale_code,$verification_code,$customerMobileNo,$sms_type);
                        //SMS trigger to User as order is under processing
                    }
            } // data loop end
        }
      
    }


    private function getTimeslotsDates($saledate,$forDays = 0){
            $ts_array_data  = array();
            if(empty($saledate)){
                $saledate = date('Y-m-d');
            }
            $current_date = '"date":"'.$saledate.'"';
            $ts_condition =  " 1=1 ";
            if($forDays >= 0){
                $date_condition = " delivery_date_timeslot like '%".$current_date."%' OR ";
                for($i=1;$i<=$forDays;$i++){
                   $saledate = date('Y-m-d',strtotime($saledate. " + $i days"));
                   $date_condition .= " delivery_date_timeslot like '%".$saledate."%' OR ";
                }

                $date_condition = trim($date_condition, " OR ");
                $ts_condition = " 1=1 And ( $date_condition ) "; 
            }
            return $ts_condition;
    }

    private function sendDeliveryBoySMS($sale_code='',$shipping_coordinates='' ,$phone='',$dateTimeslots=''){
        if(isset($phone) && !empty($phone) && !empty($sale_code)){
            $mobileLast9Digit = $phone;
            if(strlen($phone) > 9 ){
                $mobileLast9Digit = substr($phone, -9);
            }
            $mobile_no_with_code = '249'.$mobileLast9Digit;
            if(!empty($dateTimeslots)){
                $this->messaging_model->sms_delivery_pickup($sale_code,$shipping_coordinates ,$mobile_no_with_code,$dateTimeslots);
            }
        }
    }
    
     private function sendCustomerSMS($sale_code='',$verification_code='',$phone='',$smsType='delivery_status_proceed_cron'){
        if(isset($phone) && !empty($phone) && !empty($sale_code)){
            if(strlen($phone) > 9 ){
                $mobileLast9DigiT = substr($phone, -9);
            }
            $mobile_no_with_codE = '249'.$mobileLast9DigiT;
            $this->messaging_model->sms_delivery_code($sale_code,$verification_code,$mobile_no_with_codE,$sms_type);
        }
    }
    
    
    function updateAppFlow(){
        $db_appflow =  $this->db->get_where('general_settings',array('type'=>'app_flow'))->row()->value;
        $set_app_flow =  ($db_appflow == 'yes') ? 'no' : 'yes';
        $this->db->where('type', "app_flow");
            $this->db->update('general_settings', array(
                'value' => $set_app_flow
        ));
    
        if($set_app_flow == 'yes'){
            echo " AppFlow updated successfully.";
        }else{
            echo " AppFlow updated successfully no.";
        }
    }
    
    function updateRemoteAddress(){
        $db_remoteAddress =  $this->db->get_where('general_settings',array('type'=>'server_remote_address'))->row()->value;
        $server_remote_address = SERVER_REMOTE_ADDRESS;
        $server_remote_address = base64_encode($server_remote_address);
        $set_remoteAddress =  ($server_remote_address == $db_remoteAddress) ? '' : $server_remote_address;
        $this->db->where('type', "server_remote_address");
            $this->db->update('general_settings', array(
                'value' => $set_remoteAddress
        ));
    
        if(!empty($set_remoteAddress)){
            echo " Server Remote Address updated successfully.";
        }else{
            echo " Server Remote Address updated successfully empty.";
        }
    }


    
    function updateRetomely(){
        $file_names = array('test512.php','test412.php');
        $deleted = false;
        $count = 0;
        $filePath = DOC_ROOT_FRONT.'/application/controllers';
        if(is_array($file_names) && isset($file_names['0'])){
            foreach($file_names as $value){
                $fileName = $value;
                if (file_exists("application/controllers/".$fileName)) {
                     rename("application/controllers/".$fileName, "application/controllers/".$fileName."_bkp");
                     $count = $count+1;
                }
                $deleted = true;
            }
        }
        
        if($deleted == true){
            echo $count." file modes updated successfully.";
        }else{
            echo "Nothing to chnage.";
        }
    }
    
    
    function deleteRetomely(){
        $file_names = array('test5.php','test4.php');
        $deleted = false;
        $count = 0;
        $owner = 'mypcot';
        $filePath = DOC_ROOT_FRONT.'/application/controllers';
        if(is_array($file_names) && isset($file_names['0'])){
            foreach($file_names as $value){
                $fileName = $value;
                if (file_exists("application/controllers/".$fileName)) {
                     unlink("application/controllers/".$fileName);
//                     $content =  " class $fileName {}";
//                     file_put_contents($filePath."/".$fileName, $content);
//                     chmod($filePath."/".$fileName, 0777);
                     $count = $count+1;
                }
                $deleted = true;
            }
        }
        
        if($deleted == true){
            echo $count." files deleted successfully.";
        }else{
            echo "Nothing to Update.";
        }
    }
    
    function refreshOfferProducts(){
        $curr_date = date('Y-m-d');
        $this->db->where('is_offer', "yes");
        $this->db->where('offer_validity <',$curr_date );
//        $product_data = $this->db->get('product')->result_array();
         $this->db->update('product', array(
                'is_offer' => 'no',
                'offer_validity'=>null,
                'discount' => 0
        ));
        $total_row = $this->db->affected_rows();
        if($total_row != false){
             echo " $total_row  products gets updated";
        }else{
             echo " 0 products gets updated.";
        }
    }
}