<html>
    <head>
        <!--Bootstrap Stylesheet [ REQUIRED ]-->
	<link href="<?php echo base_url(); ?>template/back/css/bootstrap.min.css" rel="stylesheet">
        <!--Font Awesome [ OPTIONAL ]-->
	<link href="<?php echo base_url(); ?>template/back/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <?php $ext =  $this->db->get_where('ui_settings',array('type' => 'fav_ext'))->row()->value; $this->benchmark->mark_time();?>
	<link rel="shortcut icon" href="<?php echo base_url(); ?>uploads/others/favicon.<?php echo $ext; ?>">
	 <!--jQuery [ REQUIRED ]-->
        <script src="<?php echo base_url(); ?>template/back/js/jquery-2.1.1.min.js"></script>
        <script src="<?php echo base_url(); ?>template/back/js/front_js/jquery.validate.js"></script>
        <script src="<?php echo base_url(); ?>template/back/js/front_js/jquery.form.js"></script>
    </head>
<body class="hold-transition login-page">
<div class="login-box">

  <!-- /.login-logo -->
  <div class="login-box-body">
      <div><span id="show_msg"></span></div>
      
      <center><img src="<?php echo $this->crud_model->logo('admin_login_logo'); ?>" style="margin-top:50px;width:100px;height: 100px;"class="log_icon"></center>
      <p class="login-box-msg" id="password_heading"><b>Change Password</b></p>
      <?php if(!$error){ ?>
      
        <form id="form-validate" method="post">
         <input type="hidden" name="email" value="<?php echo $email; ?>">
         <input type="hidden" name="verification_code" value="<?php echo $verification_code; ?>">
         <div class="row">
             <div class="col-md-12">
                <div class=" col-lg-4 col-md-4 col-sm-8 col-lg-offset-4 col-sm-offset-4 form-group has-feedback">
                  <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                  <span class="glyphicon glyphicon-lock form-control-feedback" id="lock_icon"></span>
                  <label for="password" generated="true" class="error"></label>
                </div>
                <div class="col-lg-4 col-md-8 col-sm-8 col-lg-offset-4 col-sm-offset-2  form-group has-feedback">
                  <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" id="confirm_password">
                  <span class="glyphicon glyphicon-lock form-control-feedback"  id="lock_icon"></span>
                  <label for="confirm_password" generated="true" class="error"></label>
                </div>
         
                <div class="col-lg-6 col-md-8 col-sm-8 col-lg-offset-3 col-sm-offset-2">
                    <center><button type="submit" id="password_update" class="btn btn-primary btn-block btn-flat">Update Password</button></center> 
                </div>
               
            </div>
            
      </div>
<!--      <div class="row">
        
      </div>-->
    </form>
    <?php }else{ ?>
           <center style="color:red;margin-top: 50%;"><?php echo $msg; ?></center>
         <?php } ?>
         
   
  </div>
  <!-- /.login-box-body -->
</div>
 
    
<script>
var vRules = {
	password:{required:true,minlength: 6, maxlength: 10}, 
	confirm_password:{required:true,equalTo:"#password" }
	
};
var vMessages = {
	newpassword:{required:"Please enter your New Password.",minlength: "Minimum password length is 6.", maxlength: "Maximum password length is 10."},
	confirmpassword:{required:"Please enter your confirm password.", equalTo:"Password and Confirm password must be same." }
};

$("#form-validate").validate({
	rules: vRules,
	messages: vMessages,
	submitHandler: function(form) 
	{
		var act = "<?php echo base_url();?>userAuthentication/update_password";
               
		$("#form-validate").ajaxSubmit({
			url: act, 
			type: 'post',
			cache: false,
			clearForm: false,
                                          beforeSubmit: function(arr, $form, options) { 
                                            $('#password_update').prop('disabled',true);
                                            $("#show_msg").html('<span style="color:#blue;">Please wait...</span>');
                                        },
			success: function (response) {
                                $('#password_update').prop('disabled',false);
				var res = eval('('+response+')');
				if(res['success'] == "1")
				{
					$("#show_msg").html('<div class="alert alert-success"><strong><center>'+res['msg']+'</center></strong></div>');
					setTimeout(function(){
//						window.location = "<?php //echo base_url();?>";

					},2000);

				}
				else
				{	
					$("#show_msg").html('<div class="alert alert-danger"><strong><center>'+res['msg']+'</center></strong></div>');
                                        setTimeout(function(){
						$("#show_msg").html('');
					},3000);
					return false;
				}
			}
		});
	}
});
</script>
</body>
</html>


<style>
    #password_heading
    {
        padding-top: 2%;
        padding-bottom: 2%;
        text-align: center;
        font-size: 21px;
    }
    
 #lock_icon {
        position: absolute;
        top: 4px;
        right: 20px;
    }
   #password_update {
        width: 200px;
        padding: 11px 17px;
        font-size: 15px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    
    

</style>

<!--BootstrapJS [ RECOMMENDED ]-->
<script src="<?php echo base_url(); ?>template/back/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>template/back/js/ajax_login.js"></script>
	
       
   

