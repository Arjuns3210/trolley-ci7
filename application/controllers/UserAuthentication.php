<?php

class UserAuthentication extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->load->database();
//        $this->load->library('mcrypt');
    }
    
    function checkUser($para1=""){
//        $string = $this->mcrypt->decrypt($para1);
        $data = explode('------pqrst)))))', base64_decode($para1));
        $string = $data[1];
        
        $urlDetails = explode('|', $string);
        $todays_date =  date('Y-m-d');
        $msg = "";
        $result = array();
        $lastapproval = $this->db->get_where('user',array('user_id'=>$urlDetails[0]))->row()->approval_status;
        
        if($todays_date > $urlDetails[1]){
            $msg = "Link is expired. Please contact Admin Team";
        }else{
            if($lastapproval == 'approved'){
                $msg = "Link is already used for Aunthentication. Please contact Admin Team";
            }else{
                $data= array(
                    'status'=>'Active',
                    'approval_status'=>'approved',
                );
                $this->db->where('user_id', $urlDetails[0]);
                $this->db->update('user', $data);
                $msg = "Thank You..You are Successfully Authenticated.";
            }
        }
        $result = array("msg"=>$msg);
        $this->load->view('back/user_authentication',$result);
    }
    
    function checkResetPwdLink($para1=""){
        $data = explode('------abcde)))))', base64_decode($para1));
        if(!empty($data[0]) && !empty($data[1])){
               $emailId = $data[1];
               $randomKey = $data[0];
               if (filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
                  $emailExists=$login_data = $this->crud_model->checkFPWDflagInDB($emailId,$randomKey);
                   if(is_array($emailExists)){ 
                           $data = array('error'=>false,'email'=>$emailId,'verification_code'=>$randomKey);
//                           $this->load->view('front/template/header.php');
                           $this->load->view('password_change',$data);
//                           $this->load->view('front/template/footer.php');
                   }else {
                       $this->load->view('issue');
                   }
               }else{
                       $this->load->view('issue');
               }
       }else{
           $this->load->view('issue');
      }
        
    }
    
    
    function update_password(){
        if(isset($_POST['email']) && isset($_POST['verification_code']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
                $res = $this->crud_model->checkFPWDflagInDB($_POST['email'],$_POST['verification_code']);
                if(is_array($res)){
                    if (isset($_POST['password']) && !empty($_POST['password'])) {
                            if(strlen($_POST['password'])>10 || strlen($_POST['password'])<6 ){
                             echo json_encode(array('success' => "0", 'msg' => 'New Password must be 6 to 10 characters long'));
                             exit;
                            }
                    } 
                    if($_POST['password']==$_POST['confirm_password']){
                       $data_array =array('password'=>md5($_POST['email'].$_POST['password']));
                       $data_array['fpwd_key'] = '';
                       $data_array['fpwd_flag'] = 'In-active';
                       $user_id = $res[0]['user_id'];
                        $this->db->where( 'user_id', $user_id );
                        $result = $this->db->update( 'user', $data_array );
//                       $result =  $this->apis_models1->updateRecord('user', $data_array, $check_condition);
                       if (!empty($result) && $result !== false) {
                                echo json_encode(array("success"=>"1","msg"=>'Password updated successfully..'));
                                exit;
                            } else {
                                echo json_encode(array('success' => "0", 'msg' => 'Some problem occured please try again later.'));
                                exit;
                            }      

                    }else{
                      echo json_encode(array("success"=>false,"msg"=>'Password and confirm password do not match.'));
                      exit;
                    }
                }else{
                  echo json_encode(array("success"=>false,"msg"=>'Invalid user.'));
                    exit;
                }
            }else{
              echo json_encode(array("success"=>false,"msg"=>'Something is wrong.'));
                  exit;
            }
    }
}