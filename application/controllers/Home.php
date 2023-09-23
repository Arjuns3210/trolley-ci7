<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
    } 
       
    function index(){
        $this->load->view('front/template/header.php');
        $this->load->view('front/pages/index.php');
        $this->load->view('front/template/footer.php');
    }

    function index_phpinfo()
    {
        echo 'Curl: ', function_exists('curl_version') ? 'Enabled' . "\xA" : 'Disabled' . "\xA";
		echo ' || CI Version is : '.CI_VERSION;
        phpinfo();
        exit;
    }

    
    function privacy_policy(){
        $this->load->view('front/template/header.php');
        $this->load->view('front/pages/privacy_policy.php');
        $this->load->view('front/template/footer.php');
    }

    function error(){
        $this->load->view('front/template/header.php');
        $this->load->view('front/pages/privacy_policy.php');
        $this->load->view('front/template/footer.php');
    }

    
    function terms_and_condition_old(){
        $this->load->view('front/template/header.php');
        $this->load->view('front/pages/terms_condition.php');
        $this->load->view('front/template/footer.php');
    }
    function terms_and_condition($para1="ar"){
        if($para1== 'en'){
            $loadView = 'front/pages/terms_in_en.php';
        }else if( $para1 == 'ar'){
            $loadView = 'front/pages/terms_in_ar.php';
        }
        $this->load->view('front/template/header.php');
        $this->load->view($loadView);
        $this->load->view('front/template/footer.php');
    }
    
}

?>