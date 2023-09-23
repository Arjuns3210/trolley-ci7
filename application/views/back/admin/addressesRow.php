<!--<div class="row">-->
<div class="panel-heading">
    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
      <h4 class="panel-title">  Customer Existing Address</h4>
    </a>
</div>
<div id="collapseTwo" class="panel-collapse collapse in">
    <div class="panel-body">      
<?php if(is_array($my_addresses)) {
     
    ?> 
 <?php    //foreach($my_addresses as $key=>$val){
            for($i=0; $i<count($my_addresses);$i=$i+3){
                
     ?>
                      
<!--<div class="col-md-12">-->
    <?php if(isset($my_addresses[$i])){ ?>
    <div class="col-sm-4" style="margin-bottom: 10px;">
   
                                    <div class="box box-bdr address2 pb-30">
                                        <!--                                    	<div class="box-header">Default billing address</div>-->
                                        
                                        <div class="customer-details" style="border: #000 dotted;padding: 5%;">
                                             <div style="float: right;cursor: pointer;">
                                                 <input type="radio" class="required" data-pincode="<?php echo $my_addresses[$i]['pincode']; ?>" name="selected_address" <?php if($my_addresses[$i]['default_address']=='ok'){echo 'checked'; } ?> value="<?php echo $my_addresses[$i]['address_id']; ?> "  id="selected_address<?php echo $my_addresses[$i]['address_id']; ?>">
                                            </div>
                                            <label for="selected_address<?php echo $my_addresses[$i]['address_id']; ?>">
                                           
                                            <div class="address mb10"></div>
                                            <div style="word-wrap:break-word; ">
                                                <?php echo $my_addresses[$i]['address_1']; ?> 
                                            </div>
                                            <div style="word-wrap:break-word; ">
                                                <?php echo $my_addresses[$i]['address_2']; ?> 
                                            </div>
                                            
                                             <div class="address mb10"><?php echo $my_addresses[$i]['city_name'].'-'.$my_addresses[$i]['pincode'] ?> </div>
                                             <div class="address mb10"><?php echo $my_addresses[$i]['state_name'].','.$my_addresses[$i]['country_name']; ?>  </div>
                                              
                                              <div class="dear-customer" style="word-wrap:break-word;"><?php echo $my_addresses[$i]['delivery_instructions']; ?></div>
                                            
                                        </label>
                                        <div class="clearfix"></div>
                                    </div>
                                        
                                </div>


    </div>
    <?php }
      if(isset($my_addresses[$i+1])){ 
    ?>
    <div class="col-sm-4" style="margin-bottom: 10px;">
   
                                    <div class="box box-bdr address2 pb-30">
                                        <!--                                    	<div class="box-header">Default billing address</div>-->
                                        
                                        <div class="customer-details" style="border: #000 dotted;padding: 5%;">
                                             <div style="float: right;cursor: pointer;">
                                                <input type="radio" class="required" data-pincode="<?php echo $my_addresses[$i+1]['pincode']; ?>" name="selected_address" <?php if($my_addresses[$i+1]['default_address']=='ok'){echo 'checked'; } ?> value="<?php echo $my_addresses[$i+1]['address_id']; ?> "  id="selected_address<?php echo $my_addresses[$i+1]['address_id']; ?>">
                                            </div>
                                            <label for="selected_address<?php echo $my_addresses[$i+1]['address_id']; ?>">
                                           
                                            <div class="address mb10"></div>
                                            <div style="word-wrap:break-word; ">
                                            <?php echo $my_addresses[$i+1]['address_1']; ?> 
                                                
                                            </div>
                                            <div style="word-wrap:break-word; ">
                                                <?php echo $my_addresses[$i+1]['address_2']; ?> 
                                            </div>
                                             <div class="address mb10"><?php echo $my_addresses[$i+1]['city_name'].'-'.$my_addresses[$i+1]['pincode'] ?> </div>
                                             <div class="address mb10"><?php echo $my_addresses[$i+1]['state_name'].','.$my_addresses[$i+1]['country_name']; ?>  </div>
                                             
                                            <div class="dear-customer" style="word-wrap:break-word;"><?php echo $my_addresses[$i+1]['delivery_instructions']; ?></div>
                                            
                                        </label>
                                        <div class="clearfix"></div>
                                    </div>
                                        
                                </div>


    </div>
    <?php }
      if(isset($my_addresses[$i+2])){ 
    ?>
    <div class="col-sm-4" style="margin-bottom: 10px;">
   
                                    <div class="box box-bdr address2 pb-30">
                                        <!--                                    	<div class="box-header">Default billing address</div>-->
                                        
                                        <div class="customer-details" style="border: #000 dotted;padding: 5%;">
                                           <div style="float: right;cursor: pointer;">
                                                 <input type="radio" class="required" data-pincode="<?php echo $my_addresses[$i+2]['pincode']; ?>" name="selected_address" <?php if($my_addresses[$i+2]['default_address']=='ok'){echo 'checked'; } ?> value="<?php echo $my_addresses[$i+2]['address_id']; ?>"  id="selected_address<?php echo $my_addresses[$i+2]['address_id']; ?>">
                                            </div>
                                            <label for="selected_address<?php echo $my_addresses[$i+2]['address_id']; ?>">
                                            
                                            <div class="address mb10"></div>
                                            <div style="word-wrap:break-word; ">
                                            <?php echo $my_addresses[$i+2]['address_1']; ?> 
                                            </div>
                                            <div style="word-wrap:break-word; ">
                                                <?php echo $my_addresses[$i+2]['address_2']; ?> 
                                            </div>
                                            
                                             <div class="address mb10"><?php echo $my_addresses[$i+2]['city_name'].'-'.$my_addresses[$i+2]['pincode'] ?> </div>
                                             <div class="address mb10"><?php echo $my_addresses[$i+2]['state_name'].','.$my_addresses[$i+2]['country_name']; ?>  </div>
                                              
                                              <div class="dear-customer" style="word-wrap:break-word;"><?php echo $my_addresses[$i+2]['delivery_instructions']; ?></div>
                                        </label>
                                        <div class="clearfix"></div>
                                    </div>
                                        
                                </div>


    </div>
    <?php } ?>
    <!--</div>-->
    
<?php } ?>
<?php }else { ?>
     <!--<div class="form-group btm_border" id="userNewAddress" style="display:none">-->
    <center><h4>No Saved Address Found</h4></center>
    <!--</div>-->
        
<?php } ?>
</div>
</div>
<!--</div>-->

<script>
    $(document).ready(function() {
        <?php /* if(!is_array($my_addresses)) {  ?>
                $('#newCustomerAddress').show('slow');
               // $('#newAddressButton').hide('slow');
        <?php }else{ ?>
               // $('#newAddressButton').hide('slow');
                $('#newCustomerAddress').hide('slow');
         <?php } */ ?>
         
        });
        
        //added by sagar : 24-01 
        $("input[name=selected_address]").click(function () {
//          console.log(this.id);
            //console.log($(this).attr('data-pincode'));
            pincode = $(this).attr('data-pincode');
            if(this.id == 'selected_address_yes'){
                $('#shipping_address_true').val('yes'); 
            }else{
                $('#shipping_address_true').val(''); 
            }
        });
        //added by sagar : 24-01
</script>