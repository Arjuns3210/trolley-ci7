<header id="navbar">
    <div id="navbar-container" class="boxed">
        <!--Brand logo & name-->
        <div class="navbar-header">
            <a href="<?php echo base_url(); ?>index.php/<?php echo $this->session->userdata('title'); ?>" class="navbar-brand">
              <img src="<?php echo $this->crud_model->logo('admin_login_logo'); ?>" alt="<?php echo $system_name;?>" class="brand-icon" style="padding:8px;"> 
               
                <div class="brand-title">

                    <span class="brand-text"><?php echo translate($system_name) ;?></span>
                </div>
               
            </a>
        </div>

        <!--End brand logo & name-->

        <!--Navbar Dropdown-->
        <div class="navbar-content clearfix">
            <ul class="nav navbar-top-links pull-left">
                <!--Navigation toogle button-->
                <li class="tgl-menu-btn">
                    <a class="mainnav-toggle">
                        <i class="fa fa-toggle-on" aria-hidden="true" style="font-size:20px;cursor: pointer;"></i>
                        <i class="fa fa-toggle-off" aria-hidden="true" style="font-size:20px;display: none;cursor: pointer;"></i>
                    </a>
                </li>
                <li class="tgl-menu-btn hidden-xs">
                    <div class="lang-selected" style="padding: 6px;padding-right: 15px;">
                        <h5><b><?php echo translate($_SESSION['login_type']).' User';?></b></h5>
                    </div>
                </li>
                <!--End Navigation toogle button-->
            </ul>
            
            <ul class="nav navbar-top-links pull-right">
                <li>
                    <div class="lang-selected" style="margin-top:10px;">
                           
                            <?php
                                if($this->session->userdata('title') == 'admin' || $this->session->userdata('title') == 'samplewarehouse'){
                            ?>
                                <!-- <a href="<?php echo base_url(); ?>" target="_blank" class="btn btn-default">
                                    <i class="fa fa-desktop"></i>  <?php echo translate('visit_home_page');?>
                                </a> -->
                            <?php
                                } elseif ($this->session->userdata('title') == 'vendor') {
                            ?>
                                <a href="<?php echo $this->crud_model->vendor_link($this->session->userdata('vendor_id')); ?>" target="_blank" class="btn btn-default">
                                    <i class="fa fa-desktop"></i>  <?php echo translate('visit_my_homepage');?>
                                </a>
                            <?php
                                }
                            ?>
                    </div>
                </li>
                <li id="dropdown-user" class="dropdown">
                    <a href="<?php echo base_url(); ?>template/back/#" data-toggle="dropdown" class="dropdown-toggle text-right">
                        <span class="pull-right">
                            <?php
                                if($this->session->userdata('title') == 'admin'  || $this->session->userdata('title') == 'samplewarehouse' ){
                            ?>
                                <img class="img-circle img-user media-object" src="<?php echo base_url(); ?>template/back/img/av1.png" alt="Profile Picture">
                            <?php
                                } elseif ($this->session->userdata('title') == 'vendor') {
									if(file_exists("uploads/vendor_logo_image/logo_".$this->session->userdata('vendor_id').".png")){
                            ?>
                            	<img class="img-circle img-user media-object" src="<?php echo base_url(); ?>uploads/vendor_logo_image/logo_<?php echo $this->session->userdata('vendor_id'); ?>.png" alt="Profile Picture">
								<?php
                                    }else{
                                ?>
                                <img class="img-circle img-user media-object" src="<?php echo base_url(); ?>uploads/vendor_logo_image/default.jpg" alt="Profile Picture">
                            <?php
									}
                                }
                            ?>
                        </span>
                        <div class="username hidden-xs">
							<?php 
								if($this->session->userdata('title') == 'admin'){
									echo $this->session->userdata('admin_name');
								} elseif ($this->session->userdata('title') == 'vendor') {
									echo $this->session->userdata('vendor_name');	
								}elseif ($this->session->userdata('title') == 'samplewarehouse') {
									echo $this->session->userdata('warehouse_admin_name');	
								}
							?>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right with-arrow panel-default">
                        <!-- User dropdown menu -->
                        <ul class="head-list">
                            <li>
                                <a href="<?php echo base_url(); ?>index.php/<?php echo $this->session->userdata('title'); ?>/manage_admin/">
                                    <i class="fa fa-user fa-fw fa-lg"></i> <?php echo translate($_SESSION['login_type']).' '.translate('profile');?>
                                </a>
                            </li>
                        </ul>

                        <!-- Dropdown footer -->
                        <div class="pad-all text-right">
                            <a href="<?php echo base_url(); ?>index.php/<?php echo $this->session->userdata('title'); ?>/logout/" class="btn btn-primary">
                                <i class="fa fa-sign-out fa-fw"></i> <?php echo translate('logout');?>
                            </a>
                        </div>
                    </div>
                </li>
                <!--End user dropdown-->
            </ul>
        </div>
    </div>
</header>
<script>
    $('.fa-toggle-on').on('click',function(){
        $(this).hide();
        $('.fa-toggle-off').show();
    });
    $('.fa-toggle-off').on('click', function() {
      $(this).hide();
      $('.fa-toggle-on').show();
    });
</script>