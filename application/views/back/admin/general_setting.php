<?php 
    $contact_no =  $this->db->get_where('general_settings',array('type' => 'contact_phone'))->row()->value;
    $contact_email =  $this->db->get_where('general_settings',array('type' => 'contact_email'))->row()->value;
    $product_tax =  $this->db->get_where('general_settings',array('type' => 'product_fixed_tax'))->row()->value;
    $currency_conversion =  $this->db->get_where('general_settings',array('type' => 'currency_conversion'))->row()->value;
    $about_us_en =  $this->db->get_where('general_settings',array('type' => 'about_us_en'))->row()->value;
    $about_us_ar =  $this->db->get_where('general_settings',array('type' => 'about_us_ar'))->row()->value;
    $terms_conditions_en =  $this->db->get_where('general_settings',array('type' => 'terms_conditions_en'))->row()->value;
    $terms_conditions_ar =  $this->db->get_where('general_settings',array('type' => 'terms_conditions_ar'))->row()->value;
    $privacy_policy_en =  $this->db->get_where('general_settings',array('type' => 'privacy_policy_en'))->row()->value;
    $privacy_policy_ar =  $this->db->get_where('general_settings',array('type' => 'privacy_policy_ar'))->row()->value;
    
    $instant_delivery_title_en =  $this->db->get_where('general_settings',array('type' => 'instant_delivery_title_en'))->row()->value;
    $instant_delivery_title_ar =  $this->db->get_where('general_settings',array('type' => 'instant_delivery_title_ar'))->row()->value;
    $instant_delivery_description_en =  $this->db->get_where('general_settings',array('type' => 'instant_delivery_description_en'))->row()->value;
    $instant_delivery_description_ar =  $this->db->get_where('general_settings',array('type' => 'instant_delivery_description_ar'))->row()->value;
    $store_pickup_title_en =  $this->db->get_where('general_settings',array('type' => 'store_pickup_title_en'))->row()->value;
    $store_pickup_title_ar =  $this->db->get_where('general_settings',array('type' => 'store_pickup_title_ar'))->row()->value;
    $store_pickup_description_en =  $this->db->get_where('general_settings',array('type' => 'store_pickup_description_en'))->row()->value;
    $store_pickup_description_ar =  $this->db->get_where('general_settings',array('type' => 'store_pickup_description_ar'))->row()->value;
    //added by sagar : START 5-NOV
    $free_delivery_amount =  $this->db->get_where('general_settings',array('type' => 'free_delivery_amount'))->row()->value;
    $delivery_charge =  $this->db->get_where('general_settings',array('type' => 'delivery_charge'))->row()->value;
    //added by sagar : END 5-NOV
    $android_url =  $this->db->get_where('general_settings',array('type' => 'android_url'))->row()->value;
    $ios_url =  $this->db->get_where('general_settings',array('type' => 'ios_url'))->row()->value;
    $order_cancellation_time =  $this->db->get_where('general_settings',array('type' => 'order_cancellation_time'))->row()->value;
    $min_order_amount =  $this->db->get_where('general_settings',array('type' => 'min_order_amount'))->row()->value;
    $timeslot_minute =  $this->db->get_where('general_settings',array('type' => 'timeslot_minute'))->row()->value;
    $actual_android_version =  json_decode($this->db->get_where('general_settings',array('type' => 'actual_android_version'))->row()->value,true);
    $actual_ios_version =  json_decode($this->db->get_where('general_settings',array('type' => 'actual_ios_version'))->row()->value,true);
?>

<div id="content-container">
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo translate('Manage_General_setting');?></h1>
    </div>
    <div class="tab-base">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control" style="float: left;">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#general_setting"><?php echo translate( 'general_setting' ); ?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#about_us"><?php echo translate( 'about_us' ); ?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#terms_condition"><?php echo translate( 'terms_and_condition' ); ?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#privacy_policy"><?php echo translate( 'privacy_policy' ); ?></a>
                        </li>
                        <!-- <li>
                            <a data-toggle="tab" href="#instant_delivery"><?php echo translate( 'instant_delivery' ); ?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#store_pickup"><?php echo translate( 'store_pickup' ); ?></a>
                        </li> -->

                    </ul>
                </div>
            </div>
<?php
        echo form_open(base_url() . 'index.php/admin/general_setting/updateSetting/', array(
            'class' => 'form-horizontal',
            'method' => 'post'
        ));
?>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="general_setting" style="border:1px solid #ebebeb; border-radius:4px;">
                        
                        <div class="panel-heading">
                            <center><h3 class="panel-title"><?php echo translate('General_Setting');?></h3></center>
                        </div>
                     
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Contact Number </label>
                                    <div class="col-sm-7">
                                        <input type="number" name="contact_phone" value="<?php echo $contact_no; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Contact Email </label>
                                    <div class="col-sm-7">
                                        <input type="text" name="contact_email" value="<?php echo $contact_email; ?>" class="form-control">
                                    </div>
                                </div>
                                <?php /*
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Fixed Product Tax (In %)</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="product_tax" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'  value="<?php echo $product_tax; ?>" class="form-control">
                                    </div>
                                </div>
                                */ ?>
                                <div class="form-group btm_border">
                                    <label class="col-sm-3 control-label">Currency Conversion</label>
                                    <span class="btn col-sm-2">1 EGP (£E) </span>
                                   <div class="col-sm-2">
                                        <input type="number" name="currency_conversion" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'  value="<?php echo $currency_conversion; ?>" class="form-control">
                                   </div>
                                   <span class="btn">USD ($)</span>
                               </div>
                                <?php // added by sagar : START 5-Nov ?>
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label">Free Delivery Above (In <?php echo DEF_CURR; ?>)</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="free_delivery_amount" id="free_delivery" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'  value="<?php echo $free_delivery_amount; ?>" class="form-control">
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label">Minimum Order Amount(In <?php echo DEF_CURR; ?>)</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="min_order_amount" id="min_order_amt" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'  value="<?php echo $min_order_amount; ?>" class="form-control">
                                    </div>
                                </div>
                                 <?php /*
                                 <div class="form-group">
                                     <label class="col-sm-3 control-label">Home Delivery Charges (In <?php echo DEF_CURR; ?>)</label>
                                           <div class="col-sm-2">
                                            <input type="text" name="delivery_charge"  onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode ==46'  value="<?php echo $delivery_charge; ?>" class="form-control">
                                       </div>
                                     <span class="btn">  for order below <?php echo DEF_CURR; ?><span id="delivery_charge_set"><?php echo $free_delivery_amount; ?></span></span>
                                </div>
                                  */ ?>
                                  <?php // added by sagar : END 5-Nov ?>
                                
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label">Android URL</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="android_url" value="<?php echo $android_url; ?>" class="form-control">
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label">ios URL</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="ios_url" value="<?php echo $ios_url; ?>" class="form-control">
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label">Allowed Android Version</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="actual_android_version" value="<?php echo end($actual_android_version); ?>" class="form-control">
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label">Allowed iOS Version</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="actual_ios_version" value="<?php echo end($actual_ios_version); ?>" class="form-control">
                                    </div>
                                </div>
                                
                                <!--//added by sagar : 15-04 START --> 
                                  <div class="form-group" hidden>
                                     <label class="col-sm-3 control-label">Order Cancellation Time before</label>
                                           <div class="col-sm-2">
                                            <input type="text" name="order_cancellation_time"  onkeypress='return event.charCode >= 48 && event.charCode <= 57'  value="<?php echo $order_cancellation_time; ?>" class="form-control">
                                       </div>
                                     <span class="btn">Minutes</span>
                                </div>
                                 <!--//added by sagar : 15-04 END--> 
                                <div class="form-group" hidden>
                                     <label class="col-sm-3 control-label">Timeslot closing time before</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="timeslot_minute"  onkeypress='return event.charCode >= 48 && event.charCode <= 57'  value="<?php echo $timeslot_minute; ?>" class="form-control">
                                        </div>
                                     <span class="btn">Minutes</span>
                                </div>
                                
                            </div>
                    </div>
                    <div id="about_us" class="tab-pane fade" style="border:1px solid #ebebeb; border-radius:4px;">
                       
                            <div class="form-group btm_border">
                                   <h4 class="text-thin text-center"><?php echo translate( 'about_us' ); ?></h4>
                            </div>
                               <div class="form-group btm_border">
                                    <label class="col-sm-3 control-label">About Us In English</label>
                                    <div class="col-sm-7">
                                        <textarea rows="9" class="summernotes" data-height="200" data-name="about_us_en"><?php echo $about_us_en; ?></textarea>
                                    </div>
                                </div>    
                                <div class="form-group btm_border">
                                    <label class="col-sm-3 control-label">About Us In Arabic</label>
                                    <div class="col-sm-7">
                                        <textarea rows="9" class="summernotes" data-height="200" data-name="about_us_ar"><?php echo $about_us_ar; ?></textarea>
                                    </div>
                                </div>    
                                
                    </div>
                    <div id="privacy_policy" class="tab-pane fade" style="border:1px solid #ebebeb; border-radius:4px;">
                        <div class="form-group btm_border">
                            <h4 class="text-thin text-center"><?php echo translate( 'privacy_policy' ); ?></h4>
                        </div>
                        <div class="form-group btm_border">
                            <label class="col-sm-3 control-label">Privacy Policy In English</label>
                            <div class="col-sm-7">
                                <textarea rows="9" class="summernotes" data-height="200" data-name="privacy_policy_en"><?php echo $privacy_policy_en; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group btm_border">
                            <label class="col-sm-3 control-label">Privacy Policy In Arabic</label>
                            <div class="col-sm-7">
                                <textarea rows="9" class="summernotes" data-height="200" data-name="privacy_policy_ar"><?php echo $privacy_policy_ar; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div id="terms_condition" class="tab-pane fade" style="border:1px solid #ebebeb; border-radius:4px;">
                         
                            <div class="form-group btm_border">
                                   <h4 class="text-thin text-center"><?php echo translate( 'terms_and_condition' ); ?></h4>
                            </div>
                     
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label">Terms & Condition In English</label>
                                <div class="col-sm-7">
                                    <textarea rows="9" class="summernotes" data-height="200" data-name="terms_conditions_en"><?php echo $terms_conditions_en; ?></textarea>
                                </div>
                            </div>    
                            <div class="form-group btm_border">
                                <label class="col-sm-3 control-label">Terms & Condition In Arabic</label>
                                <div class="col-sm-7">
                                    <textarea rows="9" class="summernotes" data-height="200" data-name="terms_conditions_ar"><?php echo $terms_conditions_ar; ?></textarea>
                                </div>
                            </div>  
                    </div>
                    <div id="instant_delivery" class="tab-pane fade" style="border:1px solid #ebebeb; border-radius:4px;">
                                <div class="form-group btm_border">
                                   <h4 class="text-thin text-center"><?php echo translate( 'instant_delivery' ); ?></h4>
                               </div>
                     
                                 <div class="form-group">
                                    <label class="col-sm-3 control-label">Title In English</label>
                                    <div class="col-sm-7">
                                        <input type="text"  name="instant_delivery_title_en" value="<?php echo $instant_delivery_title_en ?>" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Title In Arabic</label>
                                    <div class="col-sm-7">
                                        <input type="text"  name="instant_delivery_title_ar" value="<?php echo $instant_delivery_title_ar; ?>" class="form-control ">
                                    </div>
                                </div>
                                 <div class="form-group btm_border">
                                    <label class="col-sm-3 control-label">Description In English</label>
                                    <div class="col-sm-7">
                                        <textarea rows="4" class="" cols="100"  name="instant_delivery_description_en" ><?php echo $instant_delivery_description_en; ?></textarea>
                                    </div>
                                </div>    
                                <div class="form-group btm_border">
                                    <label class="col-sm-3 control-label">Description In Arabic</label>
                                    <div class="col-sm-7">
                                        <textarea rows="4" cols="100" class="" name="instant_delivery_description_ar" ><?php echo $instant_delivery_description_ar; ?></textarea>
                                    </div>
                                </div>  
                    </div>
                    <div id="store_pickup" class="tab-pane fade" style="border:1px solid #ebebeb; border-radius:4px;">
                                <div class="form-group btm_border">
                                    <h4 class="text-thin text-center"><?php echo translate( 'store_pickup' ); ?></h4>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Title In English</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="store_pickup_title_en" value="<?php  echo $store_pickup_title_en?>" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Title In Arabic</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="store_pickup_title_ar" value="<?php  echo $store_pickup_title_ar?>" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group btm_border">
                                    <label class="col-sm-3 control-label">Description In English</label>
                                    <div class="col-sm-7">
                                        <textarea rows="4" cols="100" class="" name="store_pickup_description_en"><?php echo $store_pickup_description_en;  ?></textarea>
                                    </div>
                                </div>    
                                <div class="form-group btm_border">
                                    <label class="col-sm-3 control-label">Description In Arabic</label>
                                    <div class="col-sm-7">
                                        <textarea rows="4" cols="100" class="" name="store_pickup_description_ar"><?php echo $store_pickup_description_ar;  ?></textarea>
                                    </div>
                                </div>    
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <span class="btn btn-info submitter enterer" data-ing='<?php echo translate('updating..'); ?>' data-msg='<?php echo translate('Successfully_updated!'); ?>'>
                                                        <?php echo translate('save_Content');?>
                </span>
            </div>
        <!--Panel body-->
            </form>
        </div>
        <!--Panel Panel-->
    </div>
</div>
<script src="<?php echo base_url(); ?>template/back/js/custom/business.js"></script>
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    var user_type = 'admin';
    var module = 'general_setting';
    var list_cont_func = '';
    var dlt_cont_func = '';
    
    $(".imgInp2").change(function() {
		var tar = $(this).closest('.form-group').find('.img_show2');
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				tar.attr('src', e.target.result);
			}
			reader.readAsDataURL(this.files[0]);
		}
	});
        
    $("#free_delivery").on('keyup', function () {
        $("#delivery_charge_set").html($("#free_delivery").val());
    });
    
</script>


