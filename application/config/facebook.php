<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Facebook App details
| -------------------------------------------------------------------
|
| To get an facebook app details you have to be a registered developer
| at http://developer.facebook.com and create an app for your project.
|
|  facebook_app_id               string   Your facebook app ID.
|  facebook_app_secret           string   Your facebook app secret.
|  facebook_login_type           string   Set login type. (web, js, canvas)
|  facebook_login_redirect_url   string   URL tor redirect back to after login. Do not include domain.
|  facebook_logout_redirect_url  string   URL tor redirect back to after login. Do not include domain.
|  facebook_permissions          array    The permissions you need.
|  facebook_graph_version        string   Set Facebook Graph version to be used. Eg v2.6
|  facebook_auth_on_load         boolean  Set to TRUE to have the library to check for valid access token on every page load.
*/


$CI=& get_instance();
//$CI->load->database();$config['facebook_app_id']              = $CI->db->get_where('general_settings',array('type'=>'fb_appid'))->row()->value;;
//$config['facebook_app_secret']          = $CI->db->get_where('general_settings',array('type'=>'fb_secret'))->row()->value;;
$config['facebook_app_id']              = '193311244551821';
$config['facebook_app_secret']          = '174178abc37f438c2f2bdee6331e10b0';
$config['facebook_login_type']          = 'web';
$config['facebook_login_redirect_url']  = 'index.php/home/login_set/back/fart';
$config['facebook_logout_redirect_url'] = 'index.php/home/logout';
$config['facebook_permissions']         = array('public_profile', 'publish_actions', 'email');
$config['facebook_graph_version']       = 'v2.6';
$config['facebook_auth_on_load']        = TRUE;
