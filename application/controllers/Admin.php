<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->config->cache_query();
        $this->load->model('messaging_model');
    }

    /* index of the admin. Default: Dashboard; On No Login Session: Back to login page. */

    public function index($param1 = '') {
        if ($this->session->userdata('admin_login') == 'yes') {
            $page_name = "dashboard_" . $_SESSION['login_type'];
            $page_data['page_name'] = $page_name;
            $this->load->view('back/index', $page_data);
        } else {
            if ($param1 == 'accounts' || $param1 == 'scheduler' || $param1 == 'callcenter' || $param1 == 'buyer' || $param1 == 'warehouse' || $param1 == 'contenteditor') {
                $page_data['login_type'] = $param1;
            } else {
                $page_data['login_type'] = 'admin';
            }
            $page_data['control'] = "admin";
            $this->load->view('back/login', $page_data);
        }
    }
    
    function general_setting($para1 = "") {
        if ($this->session->userdata('admin_login') != 'yes') {
            redirect(base_url() . 'admin');
        }
        if (!$this->crud_model->admin_permission('general_setting')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'updateSetting') {
            
            $this->db->where('type', "contact_phone");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('contact_phone')
            ));
            $this->db->where('type', "contact_email");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('contact_email')
            ));
//            $this->db->where('type', "product_fixed_tax");
//            $this->db->update('general_settings', array(
//                'value' => $this->input->post('product_tax')
//            ));
            $this->db->where('type', "currency_conversion");
            $this->db->update('general_settings', array(
                'value' => $this->input->post('currency_conversion')
            ));
            //added by sagar : FOR AboutUs and Terms&Condition Part
            $this->db->where('type', "about_us_en");
            $this->db->update('general_settings', array(
                'value' => $_POST['about_us_en']
            ));
            $this->db->where('type', "about_us_ar");
            $this->db->update('general_settings', array(
                'value' => $_POST['about_us_ar']
            ));
            $this->db->where('type', "terms_conditions_en");
            $this->db->update('general_settings', array(
                'value' => $_POST['terms_conditions_en']
            ));
            $this->db->where('type', "terms_conditions_ar");
            $this->db->update('general_settings', array(
                'value' => $_POST['terms_conditions_ar']
            ));
            $this->db->where('type', "privacy_policy_en");
            $this->db->update('general_settings', array(
                'value' => $_POST['privacy_policy_en']
            ));
            $this->db->where('type', "privacy_policy_ar");
            $this->db->update('general_settings', array(
                'value' => $_POST['privacy_policy_ar']
            ));
              //added by sagar : FOR AboutUs and Terms&Condition Part
            
            $this->db->where('type', "instant_delivery_title_en");
            $this->db->update('general_settings', array(
                'value' => $_POST['instant_delivery_title_en']
            ));
            $this->db->where('type', "instant_delivery_title_ar");
            $this->db->update('general_settings', array(
                'value' => $_POST['instant_delivery_title_ar']
            ));
            $this->db->where('type', "instant_delivery_description_en");
            $this->db->update('general_settings', array(
                'value' => $_POST['instant_delivery_description_en']
            ));
            $this->db->where('type', "instant_delivery_description_ar");
            $this->db->update('general_settings', array(
                'value' => $_POST['instant_delivery_description_ar']
            ));
            
            $this->db->where('type', "store_pickup_title_en");
            $this->db->update('general_settings', array(
                'value' => $_POST['store_pickup_title_en']
            ));
            $this->db->where('type', "store_pickup_title_ar");
            $this->db->update('general_settings', array(
                'value' => $_POST['store_pickup_title_ar']
            ));
            
            $this->db->where('type', "store_pickup_description_en");
            $this->db->update('general_settings', array(
                'value' => $_POST['store_pickup_description_en']
            ));
            $this->db->where('type', "store_pickup_description_ar");
            $this->db->update('general_settings', array(
                'value' => $_POST['store_pickup_description_ar']
            ));
            //added by sagar : FOR delivery amount and delivery charge
            $currency_rate = $this->input->post('currency_conversion');
            $this->db->where('type', "free_delivery_amount");
            $this->db->update('general_settings', array(
                'value' => $_POST['free_delivery_amount']
            ));
            
            $this->db->where('type', "free_delivery_amount_ar");
            $fr_value = $_POST['free_delivery_amount'];
            $usd_value = round($fr_value*$currency_rate,3);
            $this->db->update('general_settings', array(
                'value' => $usd_value
            ));
            
            $this->db->where('type', "min_order_amount");
            $this->db->update('general_settings', array(
                'value' => $_POST['min_order_amount']
            ));
            
            $this->db->where('type', "min_order_amount_ar");
            $usd_value = $_POST['min_order_amount'];
            $ar_value = round($usd_value*$currency_rate,3);
            $this->db->update('general_settings', array(
                'value' => $ar_value
            ));
            
             //added by sagar : FOR delivery amount and delivery charge
            
            $this->db->where('type', "android_url");
            $this->db->update('general_settings', array(
                'value' => $_POST['android_url']
            ));
            $this->db->where('type', "ios_url");
            $this->db->update('general_settings', array(
                'value' => $_POST['ios_url']
            ));
            
            $this->db->where('type', "order_cancellation_time");
            $this->db->update('general_settings', array(
                'value' => $_POST['order_cancellation_time']
            ));
        
            $this->db->where('type', "timeslot_minute");
            $this->db->update('general_settings', array(
                'value' => $_POST['timeslot_minute']
            ));
            
            $actual_android_version = json_decode($this->db->get_where('general_settings', array('type' => 'actual_android_version'))->row()->value, true);

            $new_android_version = $this->input->post('actual_android_version');

            if (!empty($new_android_version) && !in_array($new_android_version, $actual_android_version)) {
                $actual_android_version[] = $new_android_version;

                $this->db->where('type', 'actual_android_version');
                $this->db->update('general_settings', array(
                    'value' => json_encode($actual_android_version)
                ));
            }


            $actual_ios_version = json_decode($this->db->get_where('general_settings', array('type' => 'actual_ios_version'))->row()->value, true);

            $new_ios_version = $this->input->post('actual_ios_version');

            if (!empty($new_ios_version) && !in_array($new_ios_version, $actual_ios_version)) {
                $actual_ios_version[] = $new_ios_version;

                $this->db->where('type', 'actual_ios_version');
                $this->db->update('general_settings', array(
                    'value' => json_encode($actual_ios_version)
                ));
            }

        } else {
            $page_data['page_name'] = "general_setting";
            $this->load->view('back/index', $page_data);
        }
    }


    /* Product Category add, edit, view, delete */

    function category($para1 = '', $para2 = '',$para3='') {
        if (!$this->crud_model->admin_permission('category')) {
            redirect(base_url() . 'admin');
        }

        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'admin');
        }

        if ($para1 == 'do_add') {
            $data['category_name'] = $this->input->post('category_name');
            $data['category_name_ar'] = $this->input->post('category_name_ar');
            $data['category_code'] = $this->input->post('category_code');
            $data['is_featured'] = $this->input->post('is_featured');
            $is_category_name_unique = $this->crud_model->verify_if_unique('category', 'category_name = ' . $this->db->escape($data['category_name']));
            if (is_array($is_category_name_unique)) {
                echo "<h5>Category Name in english already exist.<h5>";
                exit;
            }
            $is_category_name_ar_unique = $this->crud_model->verify_if_unique('category', 'category_name_ar = ' . $this->db->escape($data['category_name_ar']));
            if (is_array($is_category_name_ar_unique)) {
                echo "<h5>Category Name in arabic already exist.<h5>";
                exit;
            }
            $is_category_code_unique = $this->crud_model->verify_if_unique('category', 'category_code = ' . $this->db->escape($data['category_code']));
            if (is_array($is_category_code_unique)) {
                echo "<h5>Category Code already exist.<h5>";
                exit;
            }

            $this->db->insert('category', $data);
            $id = $this->db->insert_id();

            $path = $_FILES['img']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data_banner['banner'] = 'category_' . $id . '.' . $ext;
            $this->crud_model->file_up("img", "category", $id, '', 'no', '.' . $ext);
            $this->db->where('category_id', $id);
            $this->db->update('category', $data_banner);
            recache();
        } else if ($para1 == 'edit') {
            $page_data['category_data'] = $this->db->get_where('category', array(
                        'category_id' => $para2
                    ))->result_array();

            $this->load->view('back/admin/category_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['category_name'] = $this->input->post('category_name');
            $data['category_name_ar'] = $this->input->post('category_name_ar');
            $data['category_code'] = $this->input->post('category_code');
            $data['is_featured'] = $this->input->post('is_featured');
            $is_category_name_unique = $this->crud_model->verify_if_unique('category', 'category_name = ' . $this->db->escape($data['category_name']) . ' And category_id!=' . $this->db->escape($para2));
            if (is_array($is_category_name_unique)) {
                echo "<h5>Category Name in english already exist.<h5>";
                exit;
            }
            
            $is_category_name_ar_unique = $this->crud_model->verify_if_unique('category', 'category_name_ar = ' . $this->db->escape($data['category_name_ar']) . ' And category_id!=' . $this->db->escape($para2));
            if (is_array($is_category_name_ar_unique)) {
                echo "<h5>Category Name in arabic already exist.<h5>";
                exit;
            }
            $is_category_code_unique = $this->crud_model->verify_if_unique('category', 'category_code = ' . $this->db->escape($data['category_code']) . ' And category_id!=' . $this->db->escape($para2));
            if (is_array($is_category_code_unique)) {
                echo "<h5>Category Code already exist.<h5>";
                exit;
            }
            $this->db->where('category_id', $para2);
            $this->db->update('category', $data);
            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['banner'] = 'category_' . $para2 . '.' . $ext;
                $this->crud_model->file_up("img", "category", $para2, '', 'no', '.' . $ext);
                $this->db->where('category_id', $para2);
                $this->db->update('category', $data_banner);
            }
            recache();
        } elseif ($para1 == 'delete') {
            unlink("uploads/category_image/" . $this->crud_model->get_type_name_by_id('category', $para2, 'banner'));
            $this->db->where('category_id', $para2);
            $this->db->delete('category');
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('category_id', 'desc');
//            $this->db->where('digital=', null);
            $page_data['all_categories'] = $this->db->get('category')->result_array();
            $this->load->view('back/admin/category_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/category_add');
        }  elseif ($para1 == 'category_publish') {
            $category_id = $para2;
            if ($para3 == 'true') {
                $data['digital'] = 'ok';
            } else {
                $data['digital'] = '0';
            }
            $this->db->where('category_id', $category_id);
            $this->db->update('category', $data);
        }  elseif ($para1 == 'is_featured') {
            $category_id = $para2;
            if ($para3 == 'true') {
                $data['is_featured'] = 'yes';
            } else {
                $data['is_featured'] = 'no';
            }
            $this->db->where('category_id', $category_id);
            $this->db->update('category', $data);
        } else {
            $page_data['page_name'] = "category";
            $page_data['all_categories'] = $this->db->get('category')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Add Edit update,delete store */

    //Product Attribute Added by Dev -- Start
    /* Product Attribute add, edit, view, delete */
    function attribute($para1 = '', $para2 = '') {
        if (!$this->crud_model->admin_permission('attribute')) {
            redirect(base_url() . 'admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings','68','value') !== 'ok') {
                redirect(base_url() . 'admin');
        }
        if ($para1 == 'do_add') {
            $data = array();
            $data['attribute_name'] = $this->input->post('attribute_name');
            if ($this->input->post('is_color') == 'yes') {
                $data['is_color'] = 'ok';
            }
            if ($this->input->post('is_color') == 'no') {
                $data['is_color'] = 'no';
            }
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['created_by'] = $_SESSION['admin_id'];
            $is_attribute_name_unique = $this->crud_model->verify_if_unique('attribute', 'attribute_name = ' . $this->db->escape($data['attribute_name']));
            if (is_array($is_attribute_name_unique)) {
                echo "<h5>Attribute Name already exist.<h5>";
                exit;
            }
            $this->db->insert('attribute', $data);
            $id = $this->db->insert_id();
            recache();
        } else if ($para1 == 'edit') {
            $page_data['attribute_data'] = $this->db->get_where('attribute', array(
                        'attribute_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/attribute_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['attribute_name'] = $this->input->post('attribute_name');
            if ($this->input->post('is_color') == 'yes') {
                $data['is_color'] = 'ok';
            }
            if ($this->input->post('is_color') == 'no') {
                $data['is_color'] = 'no';
            }
            $data['update_on'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $_SESSION['admin_id'];
            $is_attribute_name_unique = $this->crud_model->verify_if_unique('attribute', 'attribute_name = ' . $this->db->escape($data['attribute_name']) . ' And attribute_id!=' . $this->db->escape($para2));
            if (is_array($is_attribute_name_unique)) {
                echo "<h5>Attribute Name already exist.<h5>";
                exit;
            }
            $this->db->where('attribute_id', $para2);
            $this->db->update('attribute', $data);
            recache();
        } elseif ($para1 == 'delete') {
            $this->db->where('attribute_id', $para2);
            $this->db->delete('attribute');
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('attribute_id', 'desc');
            $page_data['all_attributes'] = $this->db->get('attribute')->result_array();
            $this->load->view('back/admin/attribute_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/attribute_add');
        } elseif ($para1 == 'attributeAddEdit') {
            $page_data = array();
            if (empty($para2) || !is_numeric($para2)) {
                if (isset($_SESSION['attribute_id'])) {
                    $para2 = $_SESSION['attribute_id'];
                }
            } else {
                $_SESSION['attribute_id'] = $para2;
            }
            $this->db->where('attribute_id', $para2);
            $attr_value = $this->db->get('attributevalue');
            $page_data['attribute_value'] = $attr_value->result_array();
            $page_data['attribute_data'] = $this->db->get_where('attribute', array(
                        'attribute_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/attribute_value', $page_data);
        } elseif ($para1 == 'valueadd') {
            $page_data = array();
            $page_data['attribute_data'] = $this->db->get_where('attribute', array(
                        'attribute_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/attributevalue_add', $page_data);
        } elseif ($para1 == 'do_valueadd') {
            $data = array();
            $data['attribute_id'] = $this->input->post('attribute_id');
            $data['value'] = $this->input->post('attribute_value');
            if (isset($_POST['color']) && !empty($_POST['color'])) {
                $data['is_color'] = 'yes';
                $data['rgb'] = $_POST['color'];
            } else {
                $data['is_color'] = 'no';
                $data['rgb'] = '';
            }
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['created_by'] = $_SESSION['admin_id'];
            $is_attribute_value_unique = $this->crud_model->verify_if_unique('attributevalue', 'value = ' . $this->db->escape($_POST['attribute_value']) . ' And attribute_id=' . $this->db->escape($_POST['attribute_id']));
            if (is_array($is_attribute_value_unique)) {
                echo "<h5>Attribute Value already exist.<h5>";
                exit;
            }
            $this->db->insert('attributevalue', $data);
	    $id = $this->db->insert_id();
        } elseif ($para1 == 'valueedit') {
            $page_data = array();
            $page_data['attributevalue_data'] = $this->db->get_where('attributevalue', array(
                        'attributevalue_id' => $para2
                    ))->result_array();
            $page_data['attribute_data'] = $this->db->get_where('attribute', array(
                        'attribute_id' => $page_data['attributevalue_data'][0]['attribute_id']
                    ))->result_array();
            $this->load->view('back/admin/attributevalue_edit', $page_data);
        } elseif ($para1 == 'do_valueedit') {
            $data = array();
            $data['value'] = $this->input->post('attribute_value');
            if (isset($_POST['color']) && !empty($_POST['color'])) {
                $data['is_color'] = 'yes';
                $data['rgb'] = $_POST['color'];
            } else {
                $data['is_color'] = 'no';
                $data['rgb'] = '';
            }
            $data['updated_on'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $_SESSION['admin_id'];
            $is_attribute_value_unique = $this->crud_model->verify_if_unique('attributevalue', 'value = ' . $this->db->escape($_POST['attribute_value']) . ' And attribute_id=' . $this->db->escape($_POST['attribute_id']) . ' And attributevalue_id !=' . $this->db->escape($para2));
            if (is_array($is_attribute_value_unique)) {
                echo "<h5>Attribute Value already exist.<h5>";
                exit;
            }
            $this->db->where('attributevalue_id', $para2);
            $this->db->update('attributevalue', $data);
        } elseif ($para1 == 'deleteValue') {
            $this->db->where('attributevalue_id', $para2);
            $this->db->delete('attributevalue');
            recache();
        } else {
            $page_data['page_name'] = "attribute";
            $page_data['all_categories'] = $this->db->get('attribute')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    //Product Attribute Added by Dev --End
    /* Product Sub-category add, edit, view, delete */
    function sub_category($para1 = '', $para2 = '',$para3='') {
        if (!$this->crud_model->admin_permission('sub_category')) {
            redirect(base_url() . 'admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'do_add') {
            $data['sub_category_name'] = $this->input->post('sub_category_name');
            $is_subcategory_name_unique = $this->crud_model->verify_if_unique('sub_category', 'sub_category_name = ' . $this->db->escape($data['sub_category_name']));
            if (is_array($is_subcategory_name_unique)) {
                echo "<h5>Sub-category Name in english already exist.<h5>";
                exit;
            }
            $data['sub_category_name_ar'] = $this->input->post('sub_category_name_ar');
            $is_subcategory_name_ar_unique = $this->crud_model->verify_if_unique('sub_category', 'sub_category_name_ar = ' . $this->db->escape($data['sub_category_name_ar']));
            if (is_array($is_subcategory_name_ar_unique)) {
                echo "<h5>Sub-category Name in arabic already exist.<h5>";
                exit;
            }
            
            $data['category'] = $this->input->post('category');
            if ($this->input->post('brand') == null) {
                $data['brand'] = '[]';
            } else {
                $data['brand'] = json_encode($this->input->post('brand'));
            }
            $this->db->insert('sub_category', $data);
            $id = $this->db->insert_id();
            $path = $_FILES['img']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data_banner['banner'] = 'sub_category_' . $id . '.' . $ext;
            $this->crud_model->file_up("img", "sub_category", $id, '', 'no', '.' . $ext);
            $this->db->where('sub_category_id', $id);
            $this->db->update('sub_category', $data_banner);
            recache();
        } else if ($para1 == 'edit') {
            $page_data['sub_category_data'] = $this->db->get_where('sub_category', array(
                        'sub_category_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/sub_category_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['sub_category_name'] = $this->input->post('sub_category_name');
            $is_subcategory_name_unique = $this->crud_model->verify_if_unique('sub_category', 'sub_category_name = ' . $this->db->escape($data['sub_category_name']) . ' And sub_category_id!=' . $this->db->escape($para2));
            if (is_array($is_category_name_unique)) {
                echo "<h5>Sub-category Name in english already exist.<h5>";
                exit;
            }
            
            $data['sub_category_name_ar'] = $this->input->post('sub_category_name_ar');
            $is_subcategory_name_ar_unique = $this->crud_model->verify_if_unique('sub_category', 'sub_category_name_ar = ' . $this->db->escape($data['sub_category_name_ar']) . ' And sub_category_id!=' . $this->db->escape($para2));
            if (is_array($is_subcategory_name_ar_unique)) {
                echo "<h5>Sub-category Name in arabic already exist.<h5>";
                exit;
            }
            
            $data['category'] = $this->input->post('category');
            if ($this->input->post('brand') == null) {
                $data['brand'] = '[]';
            } else {
                $data['brand'] = json_encode($this->input->post('brand'));
            }
            $this->db->where('sub_category_id', $para2);
            $this->db->update('sub_category', $data);
            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['banner'] = 'sub_category_' . $para2 . '.' . $ext;
                $image_id = $this->crud_model->file_up("img", "sub_category", $para2, '', 'no', '.' . $ext);
                $this->db->where('sub_category_id', $para2);
                $this->db->update('sub_category', $data_banner);
            }
            //$this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete') {
            unlink("uploads/sub_category_image/" . $this->crud_model->get_type_name_by_id('sub_category', $para2, 'banner'));
            $this->db->where('sub_category_id', $para2);
            $this->db->delete('sub_category');
            //$this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('sub_category_id', 'desc');
            $page_data['all_sub_category'] = $this->db->get('sub_category')->result_array();
            $this->load->view('back/admin/sub_category_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/sub_category_add');
        }  elseif ($para1 == 'subcategory_publish') {
            $subcategory_id = $para2;
            if ($para3 == 'true') {
                $data['digital'] = 'ok';
        } else {
                $data['digital'] = '0';
            }
            $this->db->where('sub_category_id', $subcategory_id);
            $this->db->update('sub_category', $data);
        } else {
            $page_data['page_name'] = "sub_category";
            $page_data['all_sub_category'] = $this->db->get('sub_category')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Product Brand add, edit, view, delete */
    function brand($para1 = '', $para2 = '',$para3='') {
        if (!$this->crud_model->admin_permission('brand')) {
            redirect(base_url() . 'admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'do_add') {
            $type = 'brand';
            $data['name'] = $this->input->post('name');
            $is_brand_name_unique = $this->crud_model->verify_if_unique('brand', 'name = ' . $this->db->escape($data['name']));
            if (is_array($is_brand_name_unique)) {
                echo "<h5>Brand Name in english already exist.<h5>";
                exit;
            }
           
            $this->db->insert('brand', $data);
            $id = $this->db->insert_id();
            $path = $_FILES['img']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data_banner['logo'] = 'brand_' . $id . '.' . $ext;
            $image_id = $this->crud_model->file_up("img", "brand", $id, '', 'no', '.' . $ext);
            $this->db->where('brand_id', $id);
            $this->db->update('brand', $data_banner);
            recache();
        } elseif ($para1 == "update") {
            $data['name'] = $this->input->post('name');
            $is_brand_name_unique = $this->crud_model->verify_if_unique('brand', 'name = ' . $this->db->escape($data['name']) . ' And brand_id!=' . $this->db->escape($para2));
            if (is_array($is_brand_name_unique)) {
                echo "<h5>Brand Name in english already exist.<h5>";
                exit;
            }
           
            $this->db->where('brand_id', $para2);
            $this->db->update('brand', $data);
            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_logo['logo'] = 'brand_' . $para2 . '.' . $ext;
                $image_id = $this->crud_model->file_up("img", "brand", $para2, '', 'no', '.' . $ext);
                $this->db->where('brand_id', $para2);
                $this->db->update('brand', $data_logo);
            }
            recache();
        } elseif ($para1 == 'delete') {
            unlink("uploads/brand_image/" . $this->crud_model->get_type_name_by_id('brand', $para2, 'logo'));
            $this->db->where('brand_id', $para2);
            $this->db->delete('brand');
           // $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'multi_delete') {
            $ids = explode('-', $para2);
            $this->crud_model->multi_delete('brand', $ids);
        } else if ($para1 == 'edit') {
            $page_data['brand_data'] = $this->db->get_where('brand', array(
                        'brand_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/brand_edit', $page_data);
        } elseif ($para1 == 'list') {
            $this->db->order_by('brand_id', 'desc');
            $page_data['all_brands'] = $this->db->get('brand')->result_array();
            $this->load->view('back/admin/brand_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/brand_add');
        }  elseif ($para1 == 'brand_publish') {
            $brand_id = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
        } else {
                $data['status'] = '0';
            }
            $this->db->where('brand_id', $brand_id);
            $this->db->update('brand', $data);
           
        } else {
            $page_data['page_name'] = "brand";
            $page_data['all_brands'] = $this->db->get('brand')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Product add, edit, view, delete, stock increase, decrease, discount, do_duplicate */
    function product($para1 = '', $para2 = '', $para3 = '') {
        if (!$this->crud_model->admin_permission('product')) {
            redirect(base_url() . 'admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'admin');
        }

        if ($para1 == 'do_add') {
            $options = array();
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            $product_code = $this->input->post('product_code');
            if (isset($product_code) && !empty($product_code)) {
                $condition = " del_flg='N' And product_code='" . $product_code . "'";
                $verify_product_code = $this->crud_model->verify_if_unique('product', $condition);
                if (is_array($verify_product_code)) {
                    echo 'Product code must be unique';
                    exit;
                }
            } else {
                echo 'Product code is mandatory';
                exit;
            }
            $data['product_code'] = $this->input->post('product_code');
            //added by sagar : START 2-may 

            $food_type = 'veg';
            //added by sagar : END 2-may 
            //added by dev --start
            $data['product_type'] = $this->input->post('product_type');
            $attribute_ids = json_encode(array());
            if (is_array($this->input->post('product_attribute')) && count($this->input->post('product_attribute')) > 0  && !empty($_POST['product_attribute'][0])) {
                $color_attribute_count = $this->db->query('Select count(attribute_id) as color_attribute_count From attribute where is_color = "ok" And attribute_id IN (' . implode(',', $this->input->post('product_attribute')) . ')');
                $color_attribute_count = $color_attribute_count->result_array();
                if (is_array($color_attribute_count) && count($color_attribute_count) > 0) {
                    if ($color_attribute_count[0]['color_attribute_count'] > 1) {
                        echo 'You can Select only one attribute of color type.';
                        exit;
                    }
                }
                $attribute_ids = json_encode($this->input->post('product_attribute'));
            } else {
                if ($this->input->post('product_type') == 'variation') {
                    echo 'Product Attributes are mandatory for "variation" type product.';
                    exit;
                }
            }
            if ($this->input->post('product_type') == 'simple') {
                $data['sale_price'] = $this->input->post('sale_price');
                $sku_code = $this->input->post('sku_code');
                if (isset($sku_code) && !empty($sku_code)) {
                    $condition = " sku_code='" . $sku_code . "'";
                    $verify_sku_code = $this->crud_model->verify_if_unique('variation', $condition);
                    if (is_array($verify_sku_code)) {
                        echo 'SKU code must be unique';
                        exit;
                    }
                } else {
                    echo 'SKU code is mandatory';
                    exit;
                }
            }
            
            $data['attribute_ids'] = $attribute_ids;
            //added by dev --End
            $data['title'] = $this->input->post('title');
            $data['category'] = $this->input->post('category');
            $data['supplier'] = $this->input->post( 'supplier' );
            $data['supplier_price'] = $this->input->post( 'supplier_price' );
            
            if(!empty($_POST['description'])){
            $data['description'] = $this->input->post('description');
            }else{
               $data['description']= ""; 
            }
            //added by sagar : START 16-08
            $data['title_ar'] = $this->input->post('title_ar');
            if(!empty($_POST['description_ar'])){
                $data['description_ar'] = $this->input->post('description_ar');
            }else{
                   $data['description_ar']= ""; 
            }
            //added by sagar : END 16-08
            
            $data['sub_category'] = $this->input->post('sub_category');
            //changed by dev --start
            //setting the On -air Code
            $category_data = $this->crud_model->get_data('category', 'category_id = ' . $this->db->escape($this->input->post('category')));

            $category_code = '';
            if (is_array($category_data)) {
                $category_code = $category_data[0]['category_code'];
            } else {
                echo 'Please select valid category';
                exit;
            }
//            $on_air_code = $this->input->post('product_code');
//            $data['SKU_code'] = $on_air_code;
            $data['SKU_code'] = $this->input->post('sku_code');
            $data['add_timestamp'] = time();
            $data['download'] = null;
            $data['featured'] = 'no';
            $data['status'] = 'yes';
            $data['rating_user'] = '[]';
            $data['unit'] = $this->input->post('unit');
            $data['sale_price'] = $this->input->post('sale_price');
            $data['discount'] = $this->input->post('discount');
            $data['purchase_price'] = $this->input->post('discounted_amount');
            $data['discount_type'] = 'percent'; //hardcoded value
            //added by sagar : START
            if($data['discount'] > 99 ){
                echo 'Discount value should not be greater than 99';
                exit;
            }
            if(!empty($data['purchase_price']) && $data['purchase_price'] > $data['sale_price']){
                echo 'Final selling price can not be greater than sale price';
                exit;
            }
            if(empty($data['purchase_price']) || $data['purchase_price'] == 0){
                echo 'Final selling price can not be zero';
                exit;
            }
        
            //added by sagar : END
            $data['unit_link'] = $this->input->post('unit');
            $data['created_by'] = $this->session->userdata('admin_id');
            $data['num_of_imgs'] = $num_of_imgs;
            $data['current_stock'] = $this->input->post('current_stock');
            $data['front_image'] = 0;
            $additional_fields['name'] = json_encode($this->input->post('ad_field_names'));
            $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
            $data['additional_fields'] = json_encode($additional_fields);
            $data['brand'] = $this->input->post('brand');
            $data['food_type'] = $food_type;
            //added by sagar : START ON 29-07
            $data['is_offer'] = $this->input->post('is_offer');
            //added by sagar : END ON 29-07
            $data['weight'] = $this->input->post('product_weight');
            $data['added_by'] = json_encode(array(
                'type' => 'admin',
                'id' => $this->session->userdata('admin_id')
            ));
            $similar_products_arr = $this->input->post('similar_product');
            $data['similar_products'] = implode(',', $similar_products_arr);

            
            //for offer validity : START 20-09-2019 
            if($data['is_offer'] == 'yes'){
                if(isset($_POST['offer_validity']) && !empty($_POST['offer_validity'])){
                    $data['is_offer']=$_POST['offer_validity'];
                }else{
                    echo 'Please select offer validity date.';
                    exit;
                }
            }
            //for offer validity : END 20-09-2019

            $data['options'] = json_encode($options);
            
            $this->db->insert('product', $data);
            
            $id = $this->db->insert_id();
            $this->crud_model->file_up("images", "product", $id, 'multi');
            if ($this->input->post('product_type') == 'simple' && !empty($id)) {
                $simple_variation = array(
                    'product_type' => 'simple',
                    'product_id' => $id,
                    'title' => $this->input->post('title'),
                    'sku_code' => $this->input->post('sku_code'),
                    'current_stock' => 0,
                    'sale_price' => (double) $this->input->post('sale_price'),
                    'supplier_price' => (double) $this->input->post('supplier_price'),
                    'purchase_price' => (double) $this->input->post('discounted_amount'),
                    'is_default' => 'yes',
                    'status' => 'Active',
                    'created_on' => date('Y-m-d H:i:s'),
                    'created_by' => $this->session->userdata('admin_id'),
                );
                $this->db->insert('variation', $simple_variation);
            }
            //added by dev --End
            recache();
        } else if ($para1 == "update_content") {
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            $num = $this->crud_model->get_type_name_by_id('product', $para2, 'num_of_imgs');
            $download = $this->crud_model->get_type_name_by_id('product', $para2, 'download');
            $data['num_of_imgs'] = $num + $num_of_imgs;
            $data['embed_link'] = trim($this->input->post('embed_link'));
            if(!empty($_POST['description'])){
            $data['description'] = $this->input->post('description');
            }else{
               $data['description']= ""; 
            }
            $this->crud_model->file_up("images", "product", $para2, 'multi');
            $this->db->where('product_id', $para2);
            $this->db->update('product', $data);
            recache();
        } else if ($para1 == "update") {
            
            $options = array();
            $simple_product_stock_data = array();
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            $num = $this->crud_model->get_type_name_by_id('product', $para2, 'num_of_imgs');
            $download = $this->crud_model->get_type_name_by_id('product', $para2, 'download');
            $product_code = $this->input->post('product_code');
            if (isset($product_code) && !empty($product_code)) {
                $condition = " del_flg='N' And product_code='" . $product_code . "' And product_id!='" . $para2 . "'";
                $verify_product_code = $this->crud_model->verify_if_unique('product', $condition);
                if (is_array($verify_product_code)) {
                    echo 'Product code must be unique';
                    exit;
                }
            } else {
                echo 'Product code is mandatory';
                exit;
            }
            $data['product_code'] = $product_code;

            $food_type = 'veg';
            $data['product_code'] = $this->input->post('product_code');
            $attribute_ids = json_encode(array());
            if (is_array($this->input->post('product_attribute')) && count($this->input->post('product_attribute')) > 0 && !empty($_POST['product_attribute'][0])) {
                $color_attribute_count = $this->db->query('Select count(attribute_id) as color_attribute_count From attribute where is_color = "ok" And attribute_id IN (' . implode(',', $this->input->post('product_attribute')) . ')');
                $color_attribute_count = $color_attribute_count->result_array();
                if (is_array($color_attribute_count) && count($color_attribute_count) > 0) {
                    if ($color_attribute_count[0]['color_attribute_count'] > 1) {
                        echo 'You can Select only one attribute of color type.';
                        exit;
                    }
                }
                $attribute_ids = json_encode($this->input->post('product_attribute'));
            } else {
                if ($this->input->post('product_type') == 'variation') {
                    echo 'Product Attributes are mandatory for "variation" type product.';
                    exit;
                }
            }
            if ($this->input->post('product_type') == 'simple') {
                $simple_product_stock_data = $this->crud_model->get_data('variation', 'product_type = "simple" And product_id = ' . $this->db->escape($para2));
                $data['sale_price'] = $this->input->post('sale_price');
                $sku_code = $this->input->post('sku_code');
                if (isset($sku_code) && !empty($sku_code)) {
                    $condition = " sku_code='" . $sku_code . "'";
                    if (is_array($simple_product_stock_data)) {
                        $condition .= ' And variation_id != ' . $this->db->escape($simple_product_stock_data[0]['variation_id']);
                    }
                    $verify_sku_code = $this->crud_model->verify_if_unique('variation', $condition);
                    if (is_array($verify_sku_code)) {
                        echo 'SKU code must be unique';
                        exit;
                    }
                } else {
                    echo 'SKU code is mandatory';
                    exit;
                }
            }
            //if($this->input->post('product_type') == 'variation'){
            $attributes_in_variation = $this->crud_model->get_data('attribute_mapping', ' product_id = ' . $this->db->escape($para2), 'DISTINCT attribute_id');
            $selected_attributes = json_decode($attribute_ids);
            if (is_array($attributes_in_variation)) {
                foreach ($attributes_in_variation as $avk => $avv) {
                    if (!in_array($avv['attribute_id'], $selected_attributes)) {
                        echo 'This Product already has variation entry for one of the removed attribute.';
                        exit;
                    }
                }
            }
            //}
            $data['attribute_ids'] = $attribute_ids;
            //added by dev --End
            $data['title'] = $this->input->post('title');
            $data['category'] = $this->input->post('category');
            $data['supplier'] = $this->input->post( 'supplier' );
            $data['supplier_price'] = $this->input->post( 'supplier_price' );
             
            if(!empty($_POST['description'])){
                $data['description'] = $this->input->post('description');
            }else{
                   $data['description']= ""; 
            }
            $data['sub_category'] = $this->input->post('sub_category');
            
            //added by sagar : START 16-08
             $data['title_ar'] = $this->input->post('title_ar');
            if(!empty($_POST['description_ar'])){
                $data['description_ar'] = $this->input->post('description_ar');
            }else{
                   $data['description_ar']= ""; 
            }
            //added by sagar : END 16-08
            
            
            //changed by dev --start
            //setting the On -air Code
            $category_data = $this->crud_model->get_data('category', 'category_id = ' . $this->db->escape($this->input->post('category')));
            $category_code = '';
            if (is_array($category_data)) {
                $category_code = $category_data[0]['category_code'];
            } else {
                echo 'Please select valid category';
                exit;
            }

            $data['SKU_code'] = $this->input->post('sku_code');
            $data['unit'] = $this->input->post('unit');
            $data['sale_price'] = $this->input->post('sale_price');
            $data['discount'] = $this->input->post('discount');
            $data['purchase_price'] = $this->input->post('discounted_amount');
            $data['discount_type'] = 'percent'; //hardcoded value
            if($data['discount'] > 99 ){
                echo 'Discount value should not be greater than 99';
                exit;
            }
            if(!empty($data['purchase_price']) && $data['purchase_price'] > $data['sale_price']){
                echo 'Final selling price can not be greater than sale price';
                exit;
            }
            if(empty($data['purchase_price']) || $data['purchase_price'] == 0){
                echo 'Final selling price can not be zero';
                exit;
            }

            $data['unit_link'] = $this->input->post('unit');
            $data['num_of_imgs'] = $num + $num_of_imgs;
            $data['front_image'] = 0;
            $additional_fields['name'] = json_encode($this->input->post('ad_field_names'));
            $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
            $data['additional_fields'] = json_encode($additional_fields);
            $data['brand'] = $this->input->post('brand');
            $data['updated_by'] = $this->session->userdata('admin_id');
            $data['food_type'] = $food_type;
            $data['is_offer'] = $this->input->post('is_offer');
            $data['weight'] = $this->input->post('product_weight');
            $data['options'] = json_encode($options);
            $similar_products_arr = $this->input->post('similar_product');
            $data['similar_products'] = implode(',', $similar_products_arr);

            if($data['is_offer'] == 'yes'){
                if(isset($_POST['offer_validity']) && !empty($_POST['offer_validity'])){
                    $data['offer_validity']=$_POST['offer_validity'];
                }else{
                    echo 'Please select offer validity date.';
                    exit;
                }
            }
            
            $this->crud_model->file_up("images", "product", $para2, 'multi');
            $this->db->where('product_id', $para2);
            $this->db->update('product', $data);
            //added by dev --Start
            if ($this->input->post('product_type') == 'simple') {
                $simple_variation = array(
                    'product_type' => 'simple',
                    'product_id' => $para2,
                    'title' => $this->input->post('title'),
                    'sku_code' => $this->input->post('sku_code'),
                    'sale_price' => (double) $this->input->post('sale_price'),
                    'supplier_price' => (double) $this->input->post('supplier_price'),
                    'purchase_price' => (double) $this->input->post('discounted_amount'),
                    'is_default' => 'yes',
                    'status' => 'Active'
                );
                if (is_array($simple_product_stock_data) && count($simple_product_stock_data) > 0) {
                    $simple_variation['updated_on'] = date('Y-m-d H:i:s');
                    $simple_variation['updated_by'] = $this->session->userdata('admin_id');
                    $this->db->where('variation_id', $simple_product_stock_data[0]['variation_id']);
                    $this->db->update('variation', $simple_variation);
                } else {
                    $simple_variation['created_on'] = date('Y-m-d H:i:s');
                    $simple_variation['created_by'] = $this->session->userdata('admin_id');
                    $this->db->insert('variation', $simple_variation);
                }
            }
            //added by dev --End
            recache();
        } else if ($para1 == 'edit') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                        'product_id' => $para2
                    ))->result_array();

            $page_data['attribute_data'] = $this->db->get_where('attribute', array(
                        'status' => 'ok'
                    ))->result_array();
            $similar_products = $this->db->select('similar_products')
                                            ->get_where('product', array('product_id' => $para2,'status' => 'ok'))
                                            ->row()
                                            ->similar_products;
            $page_data['similar_products'] = explode(',',$similar_products);
            $page_data['product_all'] = $this->db->select('product_id, title')
                                        ->get_where('product', array('status' => 'ok'))
                                        ->result_array();

            $page_data['simple_product'] = $this->db->get_where('variation', array(
                        'product_type' => 'simple',
                        'product_id' => $para2
                    ))->result_array();

//  
            $this->load->view('back/admin/product_edit', $page_data);
        } else if ($para1 == 'edit_content') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                        'product_id' => $para2
                    ))->result_array();
            $page_data['attribute_data'] = $this->db->get_where('attribute', array(
                        'status' => 'ok'
                    ))->result_array();
            $page_data['simple_product'] = $this->db->get_where('variation', array(
                        'product_type' => 'simple',
                        'product_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/product_content_edit', $page_data);
        } //added by mypcot team start
        else if ($para1 == 'duplicate') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                        'product_id' => $para2
                    ))->result_array();
            $page_data['attribute_data'] = $this->db->get_where('attribute', array(
                        'status' => 'ok'
                    ))->result_array();
            $page_data['simple_product'] = $this->db->get_where('variation', array(
                        'product_type' => 'simple',
                        'product_id' => $para2
                    ))->result_array();

            $this->load->view('back/admin/product_duplicate', $page_data);
        } else if ($para1 == 'edit_images') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                        'product_id' => $para2
                    ))->result_array();

            $images = $this->crud_model->file_view('product', $para2, '', '', 'thumb', 'src', 'multi', 'all');
            $page_data['images'] = $images;

            $this->load->view('back/admin/product_image_edit', $page_data);
        } else if ($para1 == 'update_image') {
            $images_from_db = $this->crud_model->file_view('product', $para2, '', '', 'thumb', 'src', 'multi', 'all');
            $ordering = array();
            if (count($_POST) > 1) {
                foreach ($_POST as $key => $val) {
                    $ordering[$key] = $val;
                }
                if (count($ordering) !== count(array_flip($ordering))) {
                    echo '<h5>Images should have unique order number, it seems few images have the same order number</h5>';
                    exit;
                }
                asort($ordering);

                $this->crud_model->file_move('product', $para2, '.jpg', 'multi');

                $k = 1;
                foreach ($ordering as $key => $val) {
                    $old_name_string = $key;
                    $old_name = implode('_', explode('_', $old_name_string, - 1));
                    $new_name = $old_name . '_' . $k;
                    $this->crud_model->file_rename_move('product', $para2, '.jpg', $old_name_string, $new_name);
                    $k ++;
                }
            }
        } //added by mypcot team end
        else if ($para1 == 'view') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                        'product_id' => $para2
                    ))->result_array();
        
            $page_data['variation_data'] = $this->db->get_where('variation', array(
                        'product_id' => $para2,
                        'product_type' => 'variation'
                    ))->result_array();
            $page_data['simple_variation_data'] = $this->db->get_where('variation', array(
                        'product_id' => $para2,
                        'product_type' => 'simple'
                    ))->result_array();
            $this->load->view('back/admin/product_view', $page_data);
        } elseif ($para1 == 'delete') {
            $this->crud_model->file_dlt('product', $para2, '.jpg', 'multi');
            $this->db->where('product_id', $para2);
            $this->db->delete('product');
            //$this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('product_id', 'desc');
            $this->db->where('download=', null);
            //added by ritesh for buyer thing : start
            if ($_SESSION['extra'] == 7) {
                $this->db->where('created_by=', $_SESSION['admin_id']);
            }
            //added by ritesh for buyer thing : end
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/admin/product_list', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit = $this->input->get('limit');
            $search = $this->input->get('search');
            $order = $this->input->get('order');
            $offset = $this->input->get('offset');
            $sort = $this->input->get('sort');
            $this->db->where('download=', null);
            //added by ritesh for buyer thing : start
//                if($_SESSION['extra'] == 7){
//                        $this->db->where('created_by=',$_SESSION['admin_id']);
//                   }
            //added by ritesh for buyer thing : end
            if ($search) {
                $this->db->group_start();
                $this->db->like('title', $search, 'both');
                $this->db->or_like('title_ar', $search, 'both');
                $this->db->or_like('product_code', $search, 'both');
                $this->db->or_like('SKU_code', $search, 'both');
                $this->db->group_end();
            }
            $total = $this->db->get('product')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'product_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            $this->db->where('download=', null);
            //added by ritesh for buyer thing : start
//                if($_SESSION['extra'] == 7){
//                        $this->db->where('created_by=',$_SESSION['admin_id']);
//                   }
            //added by ritesh for buyer thing : start
            if ($search) {
                $this->db->group_start();
                $this->db->like('title', $search, 'both');
                $this->db->or_like('title_ar', $search, 'both');
                $this->db->or_like('product_code', $search, 'both');
                $this->db->or_like('SKU_code', $search, 'both');
                $this->db->group_end();
            }
            $products = $this->db->get('product', $limit, $offset)->result_array();
            $product_permission = $this->crud_model->admin_permission('product');
            $product_add_permission = $this->crud_model->admin_permission('product_add');
            $product_edit_permission = $this->crud_model->admin_permission('product_edit');
            $product_delete_permission = $this->crud_model->admin_permission('product_delete');
            $product_variation_permission = $this->crud_model->admin_permission('product_variation');
            $product_view_permission = $this->crud_model->admin_permission('product_view');
            $product_content_edit_permission = $this->crud_model->admin_permission('product_content_edit');
            $stock_permission = $this->crud_model->admin_permission('stock');
            $stock_add_permission = $this->crud_model->admin_permission('stock_add');
            $stock_delete_permission = $this->crud_model->admin_permission('stock_edit');
            $data = array();
            foreach ($products as $row) {
                $res = array(
                    'image' => '',
                    'title' => '',
                    'product_code' => '',
                    'current_stock' => '',
                    'deal' => '',
                    'publish' => '',
                    'featured' => '',
                    'options' => '',
                    'price' => '',
                );
                $variation_id = '';
                $current_stock = '0';
                if ($row['product_type'] == 'simple') {
                    $variation_data = $this->crud_model->get_data('variation', ' product_id = ' . $this->db->escape($row['product_id']) . ' And product_type = "simple"', 'variation_id,current_stock');
                    if (is_array($variation_data) && count($variation_data) > 0) {
                        $variation_id = $variation_data[0]['variation_id'];
                        $current_stock = $variation_data[0]['current_stock'];
                    }
                } else {
                    $variation_data = $this->crud_model->get_data('variation', ' product_id = ' . $this->db->escape($row['product_id']) . ' And product_type = "variation"', 'sum(current_stock) as current_stock');
                    if (is_array($variation_data) && count($variation_data) > 0) {
                        $current_stock = $variation_data[0]['current_stock'];
                    }
                }
                $res['image'] = '<img class="img-sm" style="height:auto !important; border:1px solid #ddd;padding:2px; border-radius:2px !important;" src="' . $this->crud_model->file_view('product', $row['product_id'], '', '', 'thumb', 'src', 'multi', 'one') . '"  />';
                $res['title'] = $row['title'];
                $res['id'] = $row['product_id'];
                $res['product_code'] = $row['product_code'];
                $res['price'] = DEF_CURR . $row['sale_price'];
                //edited by ritesh as publish and all will be visible only if u have product edit enabled.
                if ($product_edit_permission) {
                    if ($row['status'] == 'ok') {
                        $res['publish'] = '<input id="pub_' . $row['product_id'] . '" class="sw1" type="checkbox" data-id="' . $row['product_id'] . '" checked />';
                    } else {
                        $res['publish'] = '<input id="pub_' . $row['product_id'] . '" class="sw1" type="checkbox" data-id="' . $row['product_id'] . '" />';
                    }
                    if ($row['deal'] == 'ok') {
                        $res['deal'] = '<input id="deal_' . $row['product_id'] . '" class="sw3" type="checkbox" data-id="' . $row['product_id'] . '" checked />';
                    } else {
                        $res['deal'] = '<input id="deal_' . $row['product_id'] . '" class="sw3" type="checkbox" data-id="' . $row['product_id'] . '" />';
                    }
                    if ($row['featured'] == 'yes') {
                        $res['featured'] = '<input id="fet_' . $row['product_id'] . '" class="sw2" type="checkbox" data-id="' . $row['product_id'] . '" checked />';
                    } else {
                        $res['featured'] = '<input id="fet_' . $row['product_id'] . '" class="sw2" type="checkbox" data-id="' . $row['product_id'] . '" />';
                    }
                }
                //add html for action
                //edited by ritesh to control the Action Flows : Start
                $action = '';
                if ($product_variation_permission) {
                    if ($row['product_type'] == 'variation') {
                        $action .= "<a class=\"btn btn-primary btn-xs btn-labeled fa fa-plus\" data-toggle=\"tooltip\" 
                                    onclick=\"ajax_set_full('manage_variation','" . translate('manage_variation') . "','" . translate('successfully_fetched!') . "','product_edit','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                        " . translate('Manage Variation') . "
                                </a>";
                    }
                }
                if ($product_view_permission) {
                    $action .= "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('view','" . translate('view_product') . "','" . translate('successfully_viewed!') . "','product_view','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                    " . translate('view') . "  </a>";
                }
                /*
                  if ($product_edit_permission) {
                  $action .= " <a class=\"btn btn-purple btn-xs btn-labeled fa fa-tag\" data-toggle=\"tooltip\"
                  onclick=\"ajax_modal('add_discount','" . translate('view_discount') . "','" . translate('viewing_discount!') . "','add_discount','" . $row['product_id'] . "')\" data-original-title=\"Edit\" data-container=\"body\">
                  " . translate('discount') . "
                  </a>";
                  }
                */
                if ( $stock_permission && $stock_add_permission ) {
                  if ( $row['product_type'] == 'simple' ) {
                  $action .= " <a class=\"btn btn-mint btn-xs btn-labeled fa fa-plus-square\" data-toggle=\"tooltip\"
                  onclick=\"ajax_modal('add_stock','" . translate( 'add_product_quantity' ) . "','" . translate( 'quantity_added!' ) . "','stock_add','" . $row['product_id'] . "/" . $variation_id . "')\" data-original-title=\"Edit\" data-container=\"body\">
                  " . translate( 'stock' ) . "
                  </a>";
                  }
                }
                if ( $stock_permission && $stock_delete_permission ) {
                  if ( $row['product_type'] == 'simple' ) {
                  $action .= " <a class=\"btn btn-dark btn-xs btn-labeled fa fa-minus-square\" data-toggle=\"tooltip\"
                  onclick=\"ajax_modal('destroy_stock','" . translate( 'reduce_product_quantity' ) . "','" . translate( 'quantity_reduced!' ) . "','destroy_stock','" . $row['product_id'] . "/" . $variation_id . "')\" data-original-title=\"Edit\" data-container=\"body\">
                  " . translate( 'stock' ) . "
                  </a>";
                  }
                }
              

                if ($product_edit_permission) {
                    $action .= " <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('edit','" . translate('edit_product') . "','" . translate('successfully_edited!') . "','product_edit','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                    " . translate('edit') . "
                            </a>";
                }
                /* commented by sagar : 2/4/2019
                  if ($product_delete_permission) {
                  $action .= " <a onclick=\"delete_confirm('" . $row['product_id'] . "','" . translate('really_want_to_delete_this?') . "')\"
                  class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                  " . translate('delete') . "
                  </a>";
                  }

                  if ($product_content_edit_permission) {
                  $action .= " <a class=\"btn btn-warning btn-xs btn-labeled fa fa-check-square\" data-toggle=\"tooltip\" style=\"color:black;\"
                  onclick=\"ajax_set_full('edit_content','" . translate('edit_product_content') . "','" . translate('successfully_edited!') . "','product_content_edit','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"Edit Content\" data-container=\"body\">
                  " . translate('edit_content') . "
                  </a>";
                  }
                  if ($product_content_edit_permission || $product_edit_permission) {
                  $action .= " <a class=\"btn btn-warning btn-xs btn-labeled fa fa-exchange\" data-toggle=\"tooltip\"   style=\"background-color: yellow;border-color: yellow;color: black\"
                  onclick=\"ajax_set_full('edit_images','" . translate('edit_images_number') . "','" . translate('successfully_edited!') . "','product_image_edit','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"Edit Images\" data-container=\"body\">
                  " . translate('order_images') . "
                  </a>";
                  }
                  if ($product_add_permission || $product_edit_permission) {
                  $action .= " <a class=\"btn btn-success btn-xs btn-labeled fa fa-clone\" style=\"background-color: teal;border-color: teal;\" data-toggle=\"tooltip\"
                  onclick=\"ajax_set_full('duplicate','" . translate('duplicate_product') . "','" . translate('successfully_added!') . "','product_duplicate','" . $row['product_id'] . "');proceed('to_list');\" data-original-title=\"Duplicate Product\" data-container=\"body\">
                  " . translate('Duplicate') . "
                  </a>";
                  }
                 */
                $res['options'] = $action;
                //added By Ritesh : Ends
                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );
            echo json_encode($result);
        } else if ($para1 == 'dlt_img') {
            $a = explode('_', $para2);
            $this->crud_model->file_dlt('product', $a[0], '.jpg', 'multi', $a[1]);
            recache();
        } elseif ($para1 == 'sub_by_cat') {
            echo $this->crud_model->select_html('sub_category', 'sub_category', 'sub_category_name', 'add', 'demo-chosen-select required', '', 'category', $para2, 'get_brnd');
        } elseif ($para1 == 'brand_by_sub') {
            $brands = json_decode($this->crud_model->get_type_name_by_id('sub_category', $para2, 'brand'), true);
            if (empty($brands)) {
                echo translate("No brands are available for this sub category");
            } else {
                echo $this->crud_model->select_html('brand', 'brand', 'name', 'add', 'demo-chosen-select required', '', 'brand_id', $brands, '', 'multi');
            }
        } elseif ($para1 == 'product_by_sub') {
            echo $this->crud_model->select_html('product', 'product', 'title', 'add', 'demo-chosen-select required', '', 'sub_category', $para2, 'get_pro_res');
        } elseif ($para1 == 'pur_by_pro') {
            echo $this->crud_model->get_type_name_by_id('product', $para2, 'purchase_price');
        } elseif ($para1 == 'add') {
            $page_data['attribute_data'] = $this->db->get_where('attribute', array(
                        'status' => 'ok'
                    ))->result_array();
            $page_data['product_data'] = $this->db->get_where('product', array(
                        'status' => 'ok'
                    ))->result_array();
            $this->load->view('back/admin/product_add', $page_data);
        } elseif ($para1 == 'add_stock') {
            $data['product'] = $para2;
            $data['variation'] = $para3;
            $this->load->view('back/admin/product_stock_add', $data);
        } elseif ($para1 == 'destroy_stock') {
            $data['product'] = $para2;
            $data['variation'] = $para3;
            $this->load->view('back/admin/product_stock_destroy', $data);
        } elseif ($para1 == 'stock_report') {
            $data['product'] = $para2;
            $this->load->view('back/admin/product_stock_report', $data);
        } elseif ($para1 == 'sale_report') {
            $data['product'] = $para2;
            $this->load->view('back/admin/product_sale_report', $data);
        } elseif ($para1 == 'add_discount') {
            $data['product'] = $para2;
            $this->load->view('back/admin/product_add_discount', $data);
        } elseif ($para1 == 'product_featured_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['featured'] = 'yes';
            } else {
                $data['featured'] = 'no';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'product_deal_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['deal'] = 'ok';
            } else {
                $data['deal'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'product_publish_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            //$this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'add_discount_set') {
            $product = $this->input->post('product');
            $data['discount'] = $this->input->post('discount');
            $data['discount_type'] = $this->input->post('discount_type');
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            //$this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'manage_variation') {
            $page_data = array();
            if (empty($para2) || !is_numeric($para2)) {
                if (isset($_SESSION['variation_product_id'])) {
                    $para2 = $_SESSION['variation_product_id'];
                }
            } else {
                $_SESSION['variation_product_id'] = $para2;
            }
            $page_data['product_details'] = $this->db->get_where('product', array('product_id' => $para2))->result_array();
            $page_data['product_variations'] = $this->db->get_where('variation', array(
                        'product_id' => $para2,
                        'product_type' => 'variation'
                    ))->result_array();
            $this->load->view('back/admin/manage_product_variation', $page_data);
           
        } elseif ($para1 == 'change_variation_status') {
            $variation = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'Active';
            } else {
                $data['status'] = 'In-active';
            }
            $this->db->where('variation_id', $variation);
            $this->db->update('variation', $data);
            $this->db->where('variation_id', $variation);
            $this->db->update('attribute_mapping', $data);
            recache();
        } elseif ($para1 == 'variationadd') {
            $page_data['product_details'] = $this->db->get_where('product', array('product_id' => $para2))->result_array();
            $attribute_ids = $page_data['product_details'][0]['attribute_ids'];
            $attribute_ids = json_decode($attribute_ids);
            $all_attribute = $this->crud_model->get_data('attribute', 'attribute_id IN (' . implode(',', $attribute_ids) . ') ');
            if (is_array($all_attribute)) {
                foreach ($all_attribute as $key => $val) {
                    $all_attribute[$key]['attr_value'] = $this->crud_model->get_data('attributevalue', 'attribute_id IN (' . $val['attribute_id'] . ') ');
                }
            }
            $page_data['all_attribute'] = $all_attribute;
            $this->load->view('back/admin/variation_add', $page_data);
        } elseif ($para1 == 'variationedit') {
            $page_data['product_details'] = $this->db->get_where('product', array('product_id' => $para2))->result_array();
            $attribute_ids = $page_data['product_details'][0]['attribute_ids'];
            $attribute_ids = json_decode($attribute_ids);
            $all_attribute = $this->crud_model->get_data('attribute', 'attribute_id IN (' . implode(',', $attribute_ids) . ') ');
            if (is_array($all_attribute)) {
                foreach ($all_attribute as $key => $val) {
                    $all_attribute[$key]['attr_value'] = $this->crud_model->get_data('attributevalue', 'attribute_id IN (' . $val['attribute_id'] . ') ');
                }
            }
            $page_data['all_attribute'] = $all_attribute;
            $variation = $this->crud_model->get_data('variation', 'product_type = "variation" And product_id = ' . $this->db->escape($para2) . ' And variation_id = ' . $this->db->escape($para3));
            $variation_value = $this->crud_model->get_data('attribute_mapping', 'variation_id = ' . $this->db->escape($para3));
            $page_data['variation'] = $variation;
            $variation_data_map = array();
            foreach ($variation_value as $vdk => $vdv) {
                $variation_data_map[$vdv['attribute_id']] = $vdv['attributevalue_id'];
            }
            $page_data['variation_data_map'] = $variation_data_map;
            $this->load->view('back/admin/variation_edit', $page_data);
        } elseif ($para1 == 'variation_doadd') {
       
            $check_if_unique_sku = $this->crud_model->get_data('variation', 'sku_code =  ' . $this->db->escape($_POST['sku_code']));
            if (is_array($check_if_unique_sku)) {
                echo 'SKU Code must be unique.';
                exit;
            }
            $page_data['product_details'] = $this->db->get_where('product', array('product_id' => $para2))->result_array();
            $attribute_ids = $page_data['product_details'][0]['attribute_ids'];
            $attribute_ids = json_decode($attribute_ids);
            $all_attribute = $this->crud_model->get_data('attribute', 'attribute_id IN (' . implode(',', $attribute_ids) . ') ');
            $variation = $this->crud_model->get_data('variation', 'product_type = "variation" And product_id = ' . $this->db->escape($para2));
            if (is_array($all_attribute)) {
                if (is_array($variation)) {
                    foreach ($variation as $vk => $vv) {
                        $variation_value = $this->crud_model->get_data('attribute_mapping', 'variation_id = ' . $this->db->escape($vv['variation_id']));
                        $variation_data_map = array();
                        foreach ($variation_value as $vdk => $vdv) {
                            $variation_data_map[$vdv['attribute_id']] = $vdv['attributevalue_id'];
                        }
                        $matched = false;
                        foreach ($all_attribute as $key => $val) {
                            if (isset($variation_data_map[$val['attribute_id']]) && $_POST['attr' . $val['attribute_id']] == $variation_data_map[$val['attribute_id']]) {
                                $matched = true;
                            } else {
                                $matched = false;
                                break;
                            }
                        }
                        if ($matched) {
                            echo 'Selected variation combination already exist';
                            exit;
                        }
                    }
                }
            }
            $data = array(
                'sku_code' => $_POST['sku_code'],
                'title' => $_POST['title'],
                'sale_price' => (double) $_POST['sale_price'],
                'supplier_price' => (double) $_POST['supplier_price'],
                'product_type' => 'variation',
                'product_id' => $para2,
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('admin_id'),
            );
            $this->db->insert('variation', $data);
            $variation_id = $this->db->insert_id();
            foreach ($all_attribute as $key => $val) {
                if (isset($_POST['attr' . $val['attribute_id']]) && !empty($_POST['attr' . $val['attribute_id']])) {
                    $variation_d = array(
                        'variation_id' => $variation_id,
                        'product_id' => $para2,
                        'attribute_id' => $val['attribute_id'],
                        'attributevalue_id' => $_POST['attr' . $val['attribute_id']],
                    );
                    $this->db->insert('attribute_mapping', $variation_d);
                }
            }
        } elseif ($para1 == 'variation_doedit') {
            $check_if_unique_sku = $this->crud_model->get_data('variation', 'sku_code =  ' . $this->db->escape($_POST['sku_code']) . ' And variation_id != ' . $this->db->escape($para3));
            if (is_array($check_if_unique_sku)) {
                echo 'SKU Code must be unique.';
                exit;
            }

            $page_data['product_details'] = $this->db->get_where('product', array('product_id' => $para2))->result_array();
            $attribute_ids = $page_data['product_details'][0]['attribute_ids'];
            $attribute_ids = json_decode($attribute_ids);
            $all_attribute = $this->crud_model->get_data('attribute', 'attribute_id IN (' . implode(',', $attribute_ids) . ') ');
            $variation = $this->crud_model->get_data('variation', 'product_type = "variation" And product_id = ' . $this->db->escape($para2) . ' And variation_id != ' . $this->db->escape($para3));

            if (is_array($all_attribute)) {
                if (is_array($variation)) {
                    foreach ($variation as $vk => $vv) {
                        $variation_value = $this->crud_model->get_data('attribute_mapping', 'variation_id = ' . $this->db->escape($vv['variation_id']));
                        $variation_data_map = array();
                        foreach ($variation_value as $vdk => $vdv) {
                            $variation_data_map[$vdv['attribute_id']] = $vdv['attributevalue_id'];
                        }
                        $matched = false;
                        foreach ($all_attribute as $key => $val) {
                            if (isset($variation_data_map[$val['attribute_id']]) && $_POST['attr' . $val['attribute_id']] == $variation_data_map[$val['attribute_id']]) {
                                $matched = true;
                            } else {
                                $matched = false;
                                break;
                            }
                        }
                        if ($matched) {
                            echo 'Selected variation combination already exist';
                            exit;
                        }
                    }
                }
            }

            $data = array(
                'sku_code' => $_POST['sku_code'],
                'title' => $_POST['title'],
                'sale_price' => (double) $_POST['sale_price'],
                'supplier_price' => (double) $_POST['supplier_price'],
                'product_type' => 'variation',
                'product_id' => $para2,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->userdata('admin_id'),
            );

            $this->db->where('variation_id', $para3);
            $this->db->update('variation', $data);
            $this->db->where('variation_id', $para3);
            $this->db->delete('attribute_mapping');
            

            $selected_variation = $this->crud_model->get_data('variation', 'product_type = "variation" And product_id = ' . $this->db->escape($para2) . ' And variation_id = ' . $this->db->escape($para3));
            foreach ($all_attribute as $key => $val) {
                if (isset($_POST['attr' . $val['attribute_id']]) && !empty($_POST['attr' . $val['attribute_id']])) {
                    $variation_d = array(
                        'variation_id' => $para3,
                        'product_id' => $para2,
                        'attribute_id' => $val['attribute_id'],
                        'attributevalue_id' => $_POST['attr' . $val['attribute_id']],
                        'status' => $selected_variation[0]['status']
                    );
                    $this->db->insert('attribute_mapping', $variation_d);
                }
            }
        } elseif ($para1 == 'set_as_default') {
            $admin_id = $this->session->userdata('admin_id');
            $curr_date = date('Y-m-d H:i:s');
            $data = array('is_default' => 'no', updated_by => $admin_id, updated_on => $curr_date);
            $this->db->where('product_id', $para2);
            $this->db->where('product_type', 'variation');
            $this->db->update('variation', $data);
            $data = array('is_default' => 'yes');
            $this->db->where('variation_id', $para3);
            $this->db->where('product_type', 'variation');
            $this->db->update('variation', $data);
            //add update in product table for showing the default variation price
            $variation_price = $this->db->get_where('variation', array('product_type' => 'variation', 'variation_id' => $para3))->row()->sale_price;
            $pdt_data = array('sale_price' => $variation_price);
            $this->db->where('product_id', $para2);
            $this->db->update('product', $pdt_data);
        } elseif ($para1 == 'deletevariation') {
            $this->db->where('variation_id', $para2);
            $this->db->where('product_type', 'variation');
            $this->db->delete('variation');
            $this->db->where('variation_id', $para2);
            $this->db->delete('attribute_mapping');
        } else {
            $page_data['page_name'] = "product";
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Product Stock add, edit, view, delete, stock increase, decrease, discount */

    function stock($para1 = '', $para2 = '', $param3 = '') {
        if (!$this->crud_model->admin_permission('stock')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings', '68', 'value') !== 'ok') {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {

            $variation_id = $this->input->post('variation');
            $product_id = $this->input->post('product');
            $data['type'] = 'add';
            $data['category'] = $this->input->post('category');
            $data['sub_category'] = $this->input->post('sub_category');
            $data['product'] = $this->input->post('product');
            $data['variation_id'] = $this->input->post('variation');
            $data['quantity'] = $this->input->post('quantity');
            $data['reason_note'] = $this->input->post('reason_note');
            $data['datetime'] = time();
            $data['added_by'] = $this->session->userdata('admin_id');

            $this->db->insert('stock', $data);

            $prev_quantity = $this->crud_model->get_type_name_by_id('variation', $data['variation_id'], 'current_stock');
            $data1['current_stock'] = $prev_quantity + $data['quantity'];
            $this->db->where('variation_id', $data['variation_id']);
            $this->db->where('product_id', $data['product']);
            $this->db->update('variation', $data1);

            recache();
        } else if ($para1 == 'do_destroy') {
            $variation_id = $this->input->post('variation');
            $product_id = $this->input->post('product');

            $data['type'] = 'destroy';
            $data['category'] = $this->input->post('category');
            $data['sub_category'] = $this->input->post('sub_category');
            $data['product'] = $this->input->post('product');
            $data['variation_id'] = $this->input->post('variation');
            $data['quantity'] = $this->input->post('quantity');
            $data['reason_note'] = $this->input->post('reason_note');
            $data['datetime'] = time();
            $data['added_by'] = $this->session->userdata('admin_id');
            $this->db->insert('stock', $data);
            $prev_quantity = $this->crud_model->get_type_name_by_id('variation', $data['variation_id'], 'current_stock');
            $current = $prev_quantity - $data['quantity'];
            if ($current <= 0) {
                $current = 0;
            }
            $data1['current_stock'] = $current;
            $this->db->where('variation_id', $data['variation_id']);
            $this->db->where('product_id', $data['product']);
            $this->db->update('variation', $data1);
            recache();
        } elseif ($para1 == 'delete') {
            $quantity = $this->crud_model->get_type_name_by_id('stock', $para2, 'quantity');
            $product = $this->crud_model->get_type_name_by_id('stock', $para2, 'product');
            $type = $this->crud_model->get_type_name_by_id('stock', $para2, 'type');
            $variation_id = $this->crud_model->get_type_name_by_id('stock', $para2, 'variation_id');
            if ($type == 'add') {
                //$this->crud_model->decrease_quantity($product, $quantity);
                $this->crud_model->decrease_variant_quantity($product, $quantity, $variation_id);
            } else if ($type == 'destroy') {
                //$this->crud_model->increase_quantity($product, $quantity);
                $this->crud_model->increase_variant_quantity($product, $quantity, $variation_id);
            }
            $this->db->where('stock_id', $para2);
            $this->db->delete('stock');
            recache();
        } elseif ($para1 == 'list') {
//			$this->db->order_by( 'stock_id', 'desc' );
//			$page_data['all_stock'] = $this->db->get( 'stock' )->result_array();
//			$this->load->view( 'back/admin/stock_list', $page_data );
            $this->load->view('back/admin/stock_list');
        } elseif ($para1 == 'list_data') {
            //pagination : for Product Stock START  : 24-01 
            $limit = $this->input->get('limit');
            $search = $this->input->get('search');
            $order = $this->input->get('order');
            $offset = $this->input->get('offset');
            $sort = $this->input->get('sort');
            if ($search) {
                $this->db->like('s.type', $search, 'both');
                $this->db->or_like('s.reason_note', $search, 'both');
                $this->db->or_like('p.title', $search, 'both');
            }
            $this->db->select('s.*,p.title as product_title, v.title as variation_title , v.product_type');
            $this->db->join('product as p', 's.product = p.product_id', 'left');
            $this->db->join('variation as v', 's.variation_id = v.variation_id', 'left');
            $total = $this->db->get('stock as s')->num_rows();

            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 's.stock_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            if ($search) {
                $this->db->like('s.type', $search, 'both');
                $this->db->or_like('s.reason_note', $search, 'both');
                $this->db->or_like('p.title', $search, 'both');
            }
            $this->db->select('s.*,p.title as product_title, v.title as variation_title , v.product_type');
            $this->db->join('product as p', 's.product = p.product_id', 'left');
            $this->db->join('variation as v', 's.variation_id = v.variation_id', 'left');
            $products = $this->db->get('stock as s', $limit, $offset)->result_array();

            $data = array();
            $stock_delete = $this->crud_model->admin_permission('stock_delete');

            foreach ($products as $row) {
                $res = array(
                    'no' => '',
                    'product_title' => '',
                    'entry_type' => '',
                    'quantity' => '',
                    'note' => '',
                    'options' => ''
                );

                $res['no'] = $row['stock_id'];
                $res['product_title'] = $row['product_title'];
                if($row['product_type'] == 'variation'){
                     $res['product_title'] .= ' : '.$row['variation_title'];
                }
                $res['entry_type'] = $row['type'];
                $res['quantity'] = $row['quantity'];
                $res['note'] = $row['reason_note'];

                $action = '';
                /* if ($stock_delete) {

                    if ($row['type'] == 'add') {
                        $action .= " <a onclick=\"delete_confirm('" . $row['stock_id'] . "','" . translate('added_quantity_will_be_reduced.') . "','" . translate('really_want_to_delete_this?') . "')\" 
                                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                                " . 'delete' . "
                                             </a>";
                    } else if ($row['type'] == 'destroy') {
                        $action .= " <a onclick=\"delete_confirm('" . $row['stock_id'] . "','" . translate('reduced_quantity_will_be_added.') . "','" . 'Really want to delete this?' . "')\" 
                                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                                " . 'delete' . "
                                             </a>";
                    }
                }

                $res['options'] = $action; */
                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );
            echo json_encode($result);
            //pagination : for Product Stock START  : 24-01 
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/stock_add');
        } elseif ($para1 == 'destroy') {
            $this->load->view('back/admin/stock_destroy');
        } elseif ($para1 == 'sub_by_cat') {
            echo $this->crud_model->select_html('sub_category', 'sub_category', 'sub_category_name', 'add', 'demo-chosen-select required', '', 'category', $para2, 'get_product');
        } elseif ($para1 == 'pro_by_sub') {
            echo $this->crud_model->select_html('product', 'product', 'title', 'add', 'demo-chosen-select required', '', 'sub_category', $para2, 'get_variations');
        } elseif ($para1 == 'var_by_pro') {
            echo $this->crud_model->select_html('variation', 'variation', 'sku_code|title', 'add', 'demo-chosen-select required', '', 'product_id', $para2, 'get_pro_res');
        } elseif ($para1 == 'pur_by_var') {
            echo $this->crud_model->get_type_name_by_id('variation', $para2, 'sale_price');
        } else {
            $page_data['page_name'] = "stock";
            $page_data['all_stock'] = $this->db->get('stock')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Managing sales by users */

    function sales($para1 = '', $para2 = '') {
        if (!$this->crud_model->admin_permission('sale') ||  $_SESSION['role_id'] == 9) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'delete') {
            $carted = $this->db->get_where('stock', array(
                        'sale_id' => $para2
                    ))->result_array();
            foreach ($carted as $row2) {
                $this->stock('delete', $row2['stock_id']);
            }
            $this->db->where('sale_id', $para2);
            $this->db->delete('sale');
        } elseif ($para1 == 'list') {
            $this->load->view('back/admin/sales_list');
        } elseif ($para1 == 'list_data') {
            //Added by sagar :: Pagination Code START  18-01 
            $limit = $this->input->get('limit');
            // $sale_id = ($this->input->get('sale_id')) ? $this->input->get('sale_id') : null;
            $search = $this->input->get('search');
            $order = $this->input->get('order');
            $offset = $this->input->get('offset');
            $sort = $this->input->get('sort');
            $admin = $this->db->get_where('admin', array(
                        'admin_id' => $_SESSION['admin_id']
                    ))->result_array();
          
          
            $this->db->order_by('s.sale_datetime', 'desc');
            
            // if(!empty($sale_id)){
            //     $this->db->like('s.sale_id', $sale_id, 'both');
            // }
            if($admin[0]['role'] == 4){
                $this->db->like( 's.assign_delivery_data', '"admin_id":"'.$_SESSION['admin_id'].'', 'both' );
            }

            if ($search) {
                $this->db->like('s.sale_code', $search, 'both');
                $this->db->or_like('shipping_address', '"first_name":"' . $search . '', 'both');
                $this->db->or_like('shipping_address', '"fourth_name":"' . $search . '', 'both');
                $this->db->or_like('s.shipping_address', '{"phone_number":"' . $search . '', 'both');
//                $this->db->or_like('payment_type', $search, 'both');
                $this->db->or_like('s.delivery_status', '"status":"' . $search . '', 'both');
                $this->db->or_like('s.payment_status', '"status":"' . $search . '', 'both');
                $this->db->or_like('s.delivery_date_timeslot', '[{"date":"' .  date('Y-m-d',strtotime($search)) . '', 'both');
            }
            
            $total = $this->db->get('sale as s')->num_rows();
        
            $admin = $this->db->get_where('admin', array(
                        'admin_id' => $_SESSION['admin_id']
                    ))->result_array();

            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'sale_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            
            if($admin[0]['role'] == 4){
                $this->db->like( 's.assign_delivery_data', '"admin_id":"'.$_SESSION['admin_id'].'', 'both' );
            }
            if ($search) {
                $this->db->like('s.sale_code', $search, 'both');
                $this->db->or_like('shipping_address', '"first_name":"' . $search . '', 'both');
                $this->db->or_like('shipping_address', '"fourth_name":"' . $search . '', 'both');
                $this->db->or_like('s.shipping_address', '{"phone_number":"' . $search . '', 'both');
//                $this->db->or_like('payment_type', $search, 'both');
                $this->db->or_like('s.delivery_status', '"status":"' . $search . '', 'both');
                $this->db->or_like('s.payment_status', '"status":"' . $search . '', 'both');
                $this->db->or_like('s.delivery_date_timeslot', '[{"date":"' . date('Y-m-d',strtotime($search)) . '', 'both');
            }

//            $this->db->join('user as u', 's.buyer = u.user_id', 'left');
            $products = $this->db->get('sale as s ', $limit, $offset)->result_array();


            $data = array();
            $login_type = $_SESSION['login_type'];
            $sales_permission = $this->crud_model->admin_permission('sale');
            $view_sale = $this->crud_model->admin_permission('view_sale');
            $sale_invoice_view = $this->crud_model->admin_permission('sale_invoice');
            $sale_cancel_order = $this->crud_model->admin_permission('sale_cancel_order');
            $Sales_Order_Status_Update = $this->crud_model->admin_permission('Sales_Order_Status_Update');
            $sale_assign_store = $this->crud_model->admin_permission('assign_store');
            $sale_assign_delivery = $this->crud_model->admin_permission('assign_delivery');
            $i = 0;

            foreach ($products as $row) {
                $payment_status = json_decode($row['payment_status'], true);
                $delivery_info = json_decode($row['delivery_status'], true);
                $delivery_status_code = $delivery_info['0']['status'];

                $user_choice = json_decode($row['user_choice'], true);
                $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
             
                if (is_array($payment_status) && isset($payment_status[0]['status']) && $payment_status[0]['status'] != 'paid' && $login_type == 'warehouse') {
                    continue;
                }

                $i++;
                $res = array(
                    'id' => '',
                    'sale_code' => '',
                    'shipping_address' => '',
                    'sale_datetime' => '',
                    'invoice_amount' => '',
                    'grand_total' => '',
                    'delivery_status' => '',
                    'payment_status' => '',
                    'delivery_date' => '',
                    'options' => ''
                );
                $res['sale_id'] = $row['sale_id'];
                $res['sale_code'] = '#' . $row['sale_code'];
                $shipping_details = json_decode($row['shipping_address'], true);
                $res['shipping_address'] = $shipping_details['first_name'];
                $res['shipping_address'] .= '<br>'.$shipping_details['phone_number'];

                $res['sale_datetime'] = date('d-m-Y', $row['sale_datetime']);
                $res['invoice_amount'] =  DEFAULT_CURRENCY_NAME  . get_converted_currency($row['invoice_amount'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                $res['grand_total'] =  DEFAULT_CURRENCY_NAME  . get_converted_currency($row['grand_total'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);

                $delivery_date_array = json_decode($row['delivery_date_timeslot'], true);
                $res['delivery_date'] =  date('d-m-Y',strtotime($delivery_date_array[0]['date']));

                $delivery_status = json_decode($row['delivery_status'], true);
                foreach ($delivery_status as $dev) {
                    $style = "background-color:maroon;";
                    if ($dev['status'] == 'pending') {
                        $style = "background-color:red;";
                    }
                    if ($dev['status'] == 'process') {
                        $style = "background-color:yellow;color:black;";
                    }
                    if ($dev['status'] == 'delivered') {
                        $style = "background-color:green;";
                    }
                    $stylee = "<div class=\"label\" style=\"$style\">" . $dev['status'] . "</div>";
                    $res['delivery_status'] = $stylee;
                }

                $payment_status = json_decode($row['payment_status'], true);
               
                foreach ($payment_status as $devv) {
                    $style = "background-color:maroon";
                    if ($devv['status'] == 'paid') {
                        $style = "background-color:green";
                    }
                    if ($devv['status'] == 'pending') {
                        $style = "background-color:red";
                    }
                    if ($devv['status'] == 'failed') {
                        $style = "background-color:orange";
                    }
                    $stylee = "<div class=\"label\" style=\"$style\">" . $devv['status'] . "</div>";
                    $res['payment_status'] = $stylee;
                }
                //add action 
                $action = '';
                if ($sales_permission && $view_sale) {
                    $action .= "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\" 
                                                    onclick=\"ajax_set_full('view_sale','" . 'Title' . "','". "','view_sale','" . $row['sale_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                                " . 'View Sale' . "  </a>";
                }
                if ( $sales_permission && $sale_cancel_order ) {
                    if ( $row['order_status'] != 'cancelled' ) {
                            /* $action .= "<a onclick=\"other_confirm('cancel_order','" . $row['sale_id'] . "','" . 'Really Want To Cancel This Order? Product Will Be Added Back To Stock.'. "','".'Order Cancelled Successfully'."')\" 
                                class=\"btn btn-dark btn-xs btn-labeled fa fa-ban\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    " . 'Cancel'  . "
                            </a>"; */ 
                            //with comment box
                        if( $payment_status[0]['status'] != 'failed' ) {
                            $action .= "  <a class=\"btn btn-dark btn-xs btn-labeled fa fa-ban\" data-toggle=\"tooltip\" 
                                           onclick=\"modal_on_confirm('cancel_view','" . 'Cancel Order' . "','" . 'Order Cancelled Successfully' . "','cancel_order','" . $row['sale_id'] . "','Really Want To Cancel This Order? Product Will Be Added Back To Stock.');\" data-original-title=\"Edit\" data-container=\"body\">
                                       " . 'Cancel' . "  </a>";
                        }
                            
                    }else{
                        $action .= '<label class="label label-danger">Cancelled</label>';
                    }
                }
                
                if ( $row['order_status'] != 'cancelled' && $sale_assign_store && $payment_status[0]['status'] != 'failed' ) {
                    $action .= "  <a class=\"btn btn-primary btn-xs btn-labeled fa fa-recycle\" data-toggle=\"tooltip\" 
                        onclick=\"ajax_set_full('assign_store','" . translate( 'assign_store' ) . "','" . translate( 'store_assigned_successfully!' ) . "','assign_store_data','" . $row['sale_id'] . "');proceed('to_list');\" data-original-title=\"assign_stores_for_sale\" data-container=\"body\">
                        " . translate( 'Assign Stores' ) . "  </a>";
                    
                   /* $action .= "  <a class=\"btn btn-primary btn-xs btn-labeled fa fa-recycle\" data-toggle=\"tooltip\" 
                        onclick=\"ajax_set_full('assign_store_by_order','" . translate( 'assign_store' ) . "','" . translate( 'store_assigned_successfully!' ) . "','assign_store_data','" . $row['sale_id'] . "');proceed('to_list');\" data-original-title=\"assign_stores_for_sale\" data-container=\"body\">
                        " . translate( 'Assign Stores' ) . "  </a>"; */
                }
                if ( $row['order_status'] != 'cancelled' && $sale_assign_delivery && $payment_status[0]['status'] != 'failed' ) {
                    
                    $action .= "  <a class=\"btn btn-warning btn-xs btn-labeled fa fa-user\" data-toggle=\"tooltip\" 
                    onclick=\"ajax_modal('assign_delivery','" . translate( 'assign_delivery' ) . "','" . translate( 'delivery_assigned_successfully!' ) . "','assign_delivery_data','" . $row['sale_id'] . "');\" data-original-title=\"store_update\" data-container=\"body\">
                    " . translate( 'Assign_delivery' ) . "  </a>";
                }
                
                if ($sales_permission && $sale_invoice_view) {
                    $action .= "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-file-text\" data-toggle=\"tooltip\" 
                                                    onclick=\"ajax_set_full('view','" . 'Title' . "','" . 'Successfully Edited!!' . "','sales_view','" . $row['sale_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                                " . 'Invoice' . "  </a>";
                }

                if ($sales_permission && $Sales_Order_Status_Update && ( $row['order_status'] != 'cancelled' ||  $payment_status[0]['status'] == 'failed' )) {
                    $action .= "  <a class=\"btn btn-success btn-xs btn-labeled fa fa-usd\" data-toggle=\"tooltip\" 
                                                    onclick=\"ajax_modal('delivery_payment','" . 'Delivery Payment' . "','" . 'Successfully Edited!!' . "','delivery_payment','" . $row['sale_id'] . "');\" data-original-title=\"Edit\" data-container=\"body\">
                                                " . 'Status' . "  </a>";
                }
                
                 $res['options'] = $action;
                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );
            echo json_encode($result);
            //Added by sagar :: Pagination Code END  18-01       
        } elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                        'sale_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/sales_view', $page_data);
        } elseif ($para1 == 'delivery_payment') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale_id'] = $para2;
            $page_data['payment_type'] = $this->db->get_where('sale', array(
                        'sale_id' => $para2
                    ))->row()->payment_type;
            $page_data['payment_details'] = $this->db->get_where('sale', array(
                        'sale_id' => $para2
                    ))->row()->payment_details;
            $delivery_status = json_decode($this->db->get_where('sale', array(
                        'sale_id' => $para2
                    ))->row()->delivery_status, true);
            foreach ($delivery_status as $row) {
                if (isset($row['admin'])) {
                    $page_data['delivery_status'] = $row['status'];
                    if (isset($row['comment'])) {
                        $page_data['comment'] = $row['comment'];
                    } else {
                        $page_data['comment'] = '';
                    }
                } else {
                    $page_data['delivery_status'] = '';
                }
            }
            $payment_status = json_decode($this->db->get_where('sale', array(
                        'sale_id' => $para2
                    ))->row()->payment_status, true);
            
            foreach ($payment_status as $row) {
                if (isset($row['admin'])) {
                    $page_data['payment_status'] = $row['status'];
                } else {
                    $page_data['payment_status'] = '';
                }
            }
            $page_data['stock_handled'] = $this->db->get_where('sale', array(
                      'sale_id' => $para2
                  ))->row()->stock_handled;
            $this->load->view('back/admin/sales_delivery_payment', $page_data);
        } elseif ($para1 == 'delivery_payment_set') {
           
            $old_delivery_status = 'pending';
            $old_index = $new_index = 0;
            $delivery_status_array = array('pending', 'process', 'delivered');
            $delivery_status = json_decode($this->db->get_where('sale', array(
                        'sale_id' => $para2
                    ))->row()->delivery_status, true);
            $current_delivery_status = $this->input->post('delivery_status');
            if (isset($delivery_status[0]) && isset($delivery_status[0]['status'])) {
                $old_delivery_status = $delivery_status[0]['status'];
            }
            $old_index = array_search($old_delivery_status, $delivery_status_array);
            $new_index = array_search($current_delivery_status, $delivery_status_array);
            $new_delivery_status = array();
            
            foreach ($delivery_status as $row) {
                if (isset($row['admin'])) {
                    $new_delivery_status[] = array(
                        'admin' => '',
                        'status' => $this->input->post('delivery_status'),
                        'comment' => $this->input->post('comment'),
                        'delivery_time' => date('Y-m-d H:i:s'),
                    );
                } else {
                    $new_delivery_status[] = array(
                        'vendor' => $row['vendor'],
                        'status' => $row['status'],
                        'comment' => $row['comment'],
                        'delivery_time' => $row['delivery_time']
                    );
                }
            }
          
            $payment_status = json_decode($this->db->get_where('sale', array(
                        'sale_id' => $para2
                    ))->row()->payment_status, true);
            $new_payment_status = array();
            foreach ($payment_status as $row) {
                if (isset($row['admin'])) {
                    $new_payment_status[] = array('admin' => '', 'status' => $this->input->post('payment_status'));
                } else {
                    $new_payment_status[] = array('vendor' => $row['vendor'], 'status' => $row['status']);
                }
            }
           
            //added by sagar : for stock handling            
            $data['payment_status'] = json_encode($new_payment_status);
            $data['payment_details'] = $this->input->post('payment_details');
            if($this->input->post('payment_status') == 'paid'){
                $data['payment_timestamp'] = time();
            }
            
            //SMS to user when status changed  from pending to process --START
            $sales_data =  $this->db->select('sale_code,shipping_address')->get_where('sale',array('sale_id'=>$para2))->result_array();
            $sale_code = $sales_data[0]['sale_code'];
            $shipping_data = json_decode($sales_data[0]['shipping_address'],true);
            $mobileLast9Digit = $shipping_data['phone_number'];
            if(strlen($shipping_data['phone_number']) > 9 ){
                $mobileLast9Digit = substr($shipping_data['phone_number'], -9);
            }
            $mobile_no_with_code = '249'.$mobileLast9Digit;
            if($old_delivery_status == 'pending' && $current_delivery_status == 'process'){
                $sms_type = 'delivery_status_proceed';
                $this->messaging_model->sms_delivery_status($sale_code,$mobile_no_with_code,$sms_type);
            }else if($old_delivery_status == 'process' && $current_delivery_status == 'delivered'){
                $sms_type = 'delivery_status_delivered';
                $this->messaging_model->sms_delivery_status($sale_code,$mobile_no_with_code,$sms_type);
            }
            //SMS to user when status changed  from pending to process -- END
            
            $data['delivery_status'] = json_encode($new_delivery_status);
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);

        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/sales_add');
        } elseif ($para1 == 'userData') {
            $array_user = $this->db->get_where('user', array(
                        'user_id' => $para2
                    ))->result_array();
            $firstname = $this->crud_model->input_html('first_name', 'text', 'first_name', 'username', 'form-control required', 'enter_first_name', $array_user[0]['username'], 'readonly', '', '', '');
            $lastname = $this->crud_model->input_html('last_name', 'text', 'last_name', 'surname', 'form-control required', 'enter_last_name', $array_user[0]['surname'], 'readonly', '', '', '');
            $email = $this->crud_model->input_html('phone', 'text', 'first_name', 'phone', 'form-control required', 'enter_mobile_number', $array_user[0]['phone'], 'readonly', '', '', '');
            $mobile = $this->crud_model->input_html('email', 'text', 'first_name', 'email', 'form-control', 'enter_email_id', $array_user[0]['email'], 'readonly', '', '', '');
            $final_paint = $firstname . $lastname . $email . $mobile;
            echo $final_paint;
        } elseif ($para1 == 'state') {
            echo $this->crud_model->select_html('state', 'state', 'name', 'add', 'demo-chosen-select required', '', 'country_id', $para2, 'get_city');
        } elseif ($para1 == 'city') {
            echo $this->crud_model->select_html('city', 'city', 'name', 'add', 'demo-chosen-select required', '', 'state_id', $para2, '');
        } elseif ($para1 == 'total') {
            echo $this->db->get('sale')->num_rows();
        } elseif ($para1 == 'view_sale') {
            $this->db->where('sale_id', $para2);
            $page_data['sale'] = $this->db->get_where('sale', array(
                        'sale_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/view_sale',$page_data);
        } elseif ($para1 == 'cancel_view') {
            $data['sale_id'] = $para2;
            $this->load->view('back/admin/sale_cancel_order',$data);
        } elseif ($para1 == 'cancel_order') {
            $sale_data = $this->crud_model->get_data('sale', 'sale_id = ' . $this->db->escape($para2));
            $comment =  trim($_POST['comment']);
	    $isUserMoveMoneyToWallet= false;
            if(empty($comment)){
                echo '<b>Please provide reason for cancel order.</b>';
                exit;
            }

	    $payment_status = json_decode($sale_data[0]['payment_status'], true);
            $user_choice = json_decode($sale_data[0]['user_choice'], true);
            $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];

            if (is_array($payment_status) && isset($payment_status[0]['status']) && $payment_status[0]['status'] == 'paid') {
                 $isUserMoveMoneyToWallet = true;
            }
	    
            
            if (is_array($sale_data) && isset($sale_data[0]['product_details'])) {
                $shipping_details = json_decode($sale_data[0]['shipping_address'], true);
                $product_details = json_decode($sale_data[0]['product_details'], true);
                foreach ($product_details as $key => $val) {
                    $current_stock = $this->crud_model->get_type_name_by_id('variation', $val['variation_id'], 'current_stock');
                    $stock_data['type'] = 'add';
                    $stock_data['product'] = $val['product_id'];
                    $stock_data['variation_id'] = $val['variation_id'];
                    $stock_data['category'] = $this->crud_model->get_type_name_by_id('product', $val['product_id'], 'category');
                    $stock_data['sub_category'] = $this->crud_model->get_type_name_by_id('product', $val['product_id'], 'sub_category');
                    $stock_data['quantity'] = $val['qty'];
                    $stock_data['total'] = 0;
                    $stock_data['reason_note'] = 'Cancelled by admin';
                    $stock_data['sale_id'] = $para2;
                    $stock_data['datetime'] = time();
                    $this->db->insert('stock', $stock_data);
                    $update_stock = array('current_stock' => ( $current_stock + $val['qty'] ));
                    $this->db->where('variation_id', $val['variation_id']);
                    $this->db->where('product_id', $val['product_id']);
                    $this->db->update('variation', $update_stock);
                }
                $payment_array_hardcoded[] = array(
                    'admin' => "",
                    'status' => 'cancelled',
                );
                        
                $delivery_status[] = array('admin' => '',
                    'status' => 'cancelled',
                    'comment' => 'cancel by admin',
                    'delivery_time' => date('Y-m-d H:i:s'),
                );
                $update_sale = array(
                            'order_status' => 'cancelled',
                            'order_cancel_comment' => $comment,
                            'payment_status' => json_encode($payment_array_hardcoded),
                            'delivery_status' => json_encode($delivery_status),

                );
                
                $this->db->where('sale_id', $para2);
                $this->db->update('sale', $update_sale);

		 //if  true refund money back to wallet -- added by ritesh : start
//                $isUserMoveMoneyToWallet = false;
                if($isUserMoveMoneyToWallet){
                    $vat_in_usd =  $sale_data[0]['vat'];
                    $sale_code =  $sale_data[0]['sale_code'];
                    $grand_total_in_usd =  $sale_data[0]['grand_total'];
                    $user_id =  $sale_data[0]['buyer'];
                    $grand_total_in_sdg = get_converted_currency($grand_total_in_usd,DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                    //Wallet insert part moved down as user current balance is required

                    $check_condition = ' user_id = '.$this->db->escape($user_id);
                    $db_wallet_balance =  $this->db->get_where('user',array('user_id'=>$user_id))->row()->wallet_balance;
                    $updated_wallet = $db_wallet_balance + $grand_total_in_sdg;
                    $updated_wallet = round($updated_wallet,2);
                    $update_wallet_balance =  array(
                        'wallet_balance' =>$updated_wallet,
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('user', $update_wallet_balance);

                    $walletData = array(
                            'user_id'=>$user_id,
                            'amount'=>$grand_total_in_sdg,
                            'type'=>'credit',
                            'reason'=> 'admin cancelled order #'.$sale_code,
                            'date_time'=>date('Y-m-d H:i:s'),
                            'sale_id' =>$para2,
                            'wallet_balance'=>$updated_wallet,
                            'admin_id'=>$this->session->userdata('admin_id'),
                    );
                   $this->db->insert('wallet', $walletData);

                }
                //if  true refund money back to wallet -- added by ritesh : end

		
                //SMS TO USER - CANCEL ORDER
                $userNumber = $shipping_details['phone_number'];
                $orders_id = $sale_data[0]['sale_code'];
                $this->messaging_model->sms_order_cancelled($orders_id,$userNumber);
                //SMS TO USER - CANCEL ORDER
         
            } 
        } elseif ($para1 == 'assign_store_by_order') {
            $data['sale_id'] = $para2;
            $condition =  'sale_id = '.$this->db->escape($para2);
            $data['sales_data'] =  $this->crud_model->get_data('sale',$condition);
            $data['supplier_stores']   = $this->db->get_where( 'supplier_store')->result_array();
            $this->load->view('back/admin/assign_store_order_view', $data);
        } elseif ($para1 == 'assign_store') {
            $data['sale_id'] = $para2;
            $condition =  'sale_id = '.$this->db->escape($para2);
            $data['sales_data'] =  $this->crud_model->get_data('sale',$condition);
            $this->load->view('back/admin/assign_store_view', $data);
        } elseif ($para1 == 'update_assign_stores_by_order') {
            $assign_store_data = array();
            $supplier_store_ids = array();
            if(isset($_POST['product_id']) && is_array($_POST['product_id']) && !empty($_POST['product_id'][0]) ){
                foreach($_POST['product_id'] as $key => $val){
                    
                    $product_ids_array =  explode('|',$val);
                    $variation_ids_array =  explode('|',$_POST['variation_id'][$key]);
                    foreach($product_ids_array  as $k => $v){
                        if(isset($_POST['stores']) && !empty($_POST['stores'])){
                            $assign_data['supplier_store_id'] = $_POST['stores'][$key];
                        }else{
                            $assign_data['supplier_store_id'] = 0;
                        }
                        
                        if(isset($_POST['supplier_id']) && !empty($_POST['supplier_id'])){
                            $assign_data['supplier_id'] = $_POST['supplier_id'][$key];
                        }else{
                            $assign_data['supplier_id'] = 0;
                        }
                        
                        $assign_data['product_id'] = $v;
                        $assign_data['variation_id'] = $variation_ids_array[$k];
                        
                        $assign_store_data[] = $assign_data;
                        
                        // UPDATE STORE_ID IN CART TABLE
                        array_push($supplier_store_ids,$_POST['stores'][$key]);
                        $cartUpdate = array( 'supplier_store_id'=>$_POST['stores'][$key]);
                        $this->db->where('sale_id', $para2);
                        $this->db->where('product_id', $v);
                        $this->db->where('variation_id', $variation_ids_array[$k]);
                        $this->db->update('cart', $cartUpdate);
                    }
                }
            }
       
            $imploded_supplier_store_ids = implode(',', $supplier_store_ids);
         
            $update_data =  array(
                'assign_stores_data' =>json_encode($assign_store_data),
                'assign_store_handled' => 'Yes',
                'supplier_store_ids' => $imploded_supplier_store_ids,
            );
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $update_data);
        } elseif ($para1 == 'update_assign_stores') {
            $assign_store_data = $supplier_store_ids = array();
            if(isset($_POST['stores']) && is_array($_POST['stores']) && !empty($_POST['stores'][0]) ){
                foreach($_POST['stores'] as $key => $val){
                    $assign_data['supplier_store_id'] = $val;
                    
                    if(isset($_POST['supplier_id']) && !empty($_POST['supplier_id'])){
                        $assign_data['supplier_id'] = $_POST['supplier_id'][$key];
                    }else{
                        $assign_data['supplier_id'] = 0;
                    }
                    if(isset($_POST['product_id']) && !empty($_POST['product_id'])){
                        $assign_data['product_id'] = $_POST['product_id'][$key];
                    }else{
                        $assign_data['product_id'] = 0;
                    }
                    if(isset($_POST['variation_id']) && !empty($_POST['variation_id'])){
                        $assign_data['variation_id'] = $_POST['variation_id'][$key];
                    }else{
                        $assign_data['variation_id'] = 0;
                    }
                    $assign_store_data[] = $assign_data;
                    //added by Ritesh
                    array_push($supplier_store_ids,$val);
                    $cartUpdate = array( 'supplier_store_id'=>$val);
                    $this->db->where('sale_id', $para2);
                    $this->db->where('product_id',  $assign_data['product_id']);
                    $this->db->where('variation_id', $assign_data['variation_id']);
                    $this->db->update('cart', $cartUpdate);
                }
            }
       
            $imploded_supplier_store_ids = implode(',', $supplier_store_ids);
         
            $update_data =  array(
                'assign_stores_data' =>json_encode($assign_store_data),
                'assign_store_handled' => 'Yes',
                'supplier_store_ids' => $imploded_supplier_store_ids,
            );            
	    $this->db->where('sale_id', $para2);
            $this->db->update('sale', $update_data);
          
        } elseif ($para1 == 'assign_delivery') {
            $data['sale_id'] = $para2;
            $condition =  'sale_id = '.$this->db->escape($para2);
            $data['sales_data'] = $sales_data =  $this->crud_model->get_data('sale',$condition);
            $shipping_data = json_decode($sales_data[0]['shipping_address'],true);
            $shipping_area = $shipping_data['area'];
            $area_id = $this->db->get_where('area',array('area_name_en'=>$shipping_area))->row()->area_id;
            if(!empty($area_id)){
                //OLD QUERY 
                //$data['delivery_team']  =  $this->db->get_where( 'admin', array('role'=>'4','area_id'=>$area_id,'status'=>'Active'))->result_array();
                $conditionN = " role = 4 And status='Active' " ;
                $conditionN .= " AND area_ids like '%\"".$area_id."\"%' " ;
                $data['delivery_team'] = $this->crud_model->get_data('admin',$conditionN);
               if(empty($data['delivery_team'] )){
                    $data['delivery_team']   = $this->db->get_where( 'admin', array('role'=>'4','status'=>'Active'))->result_array();
               }
            }else{
               $data['delivery_team']   = $this->db->get_where( 'admin', array('role'=>'4'))->result_array();
            }
            $this->load->view('back/admin/assign_delivery_view', $data);
        } elseif ($para1 == 'update_assign_delivery') {
//            $assign_store_info =  $this->db->get_where('sale',array('sale_id'=>$para2))->row()->assign_stores_data;
            $sales_data =  $this->db->select('sale_code,product_details,assign_stores_data,shipping_address,delivery_date_timeslot')->get_where('sale',array('sale_id'=>$para2))->result_array();
            $sale_code = $sales_data[0]['sale_code'];
            $assign_store_info = $sales_data[0]['assign_stores_data'];
            $delivery_timeslot = json_decode($sales_data[0]['delivery_date_timeslot'],true);
            $shipping_data = json_decode($sales_data[0]['shipping_address'],true);
            $shipping_coordinates = $shipping_data['langlat'];
            if(empty($assign_store_info)){
                echo 'Please go back and assign store to current sale entry.';
                exit;
            }
            $assign_delivery_array = array('admin_id','name','phone');
            $delivery_data =  array('','','');
            if(!empty($_POST['assigned_delivery'])){
                $delivery_data =  explode('|',$_POST['assigned_delivery']);
            }
            $assign_delivery_data = array_combine($assign_delivery_array, $delivery_data);
            
            //added by sagar : as same thing handle in cron function - 27-07-2020
            $new_delivery_status[] = array(
                                'admin' => $delivery_data[0],
                                'status' => 'process',
                                'comment' => 'pending to process by admin',
                                'delivery_time' => date('Y-m-d H:i:s'),
            );
            
            //admin_id added by sagar
            $update_data =  array(
                'assign_delivery_data' =>json_encode($assign_delivery_data),
                'admin_id' =>$delivery_data[0],
                'assign_delivery_handled' => 'Yes',
                'verification_counts' => 0,
                'delivery_status' =>json_encode($new_delivery_status), 
            );
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $update_data);
            
            //SMS trigger to delivery boy pending
            if(!empty($delivery_data[2])){
                $assign_store_info =  json_decode($assign_store_info,true);
                $supplier_store_id = $assign_store_info[0]['supplier_store_id'];
                $mobileLast9Digit = $delivery_data[2];
                if(strlen($delivery_data[2]) > 9 ){
                    $mobileLast9Digit = substr($delivery_data[2], -9);
                }
                $mobile_no_with_code = '249'.$mobileLast9Digit;
                if(!empty($delivery_timeslot[0]['date']) && $delivery_timeslot[0]['timeslot']){
                    $dateTimeslots = $delivery_timeslot[0]['date'];
                    $dateTimeslots .= ' '.$delivery_timeslot[0]['timeslot'];
                    $this->messaging_model->sms_delivery_pickup($sale_code,$shipping_coordinates ,$mobile_no_with_code,$dateTimeslots);
                }
            }
            //SMS trigger to delivery boy pending
            
            
            } else {
            $page_data['page_name'] = "sales";
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* User Management */

    function user($para1 = '', $para2 = '', $para3 = '') {
        if (!$this->crud_model->admin_permission('user')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'do_add') {
            $data['username'] = $this->input->post('name');
//            $data['surname'] = $this->input->post('last_name');
            $data['phone'] = $this->input->post('contact_number');
            $data['email'] = $this->input->post('email_address');
//            $data['user_type'] = $this->input->post('user_type');
            $data['wishlist'] = '[]';
            $data['created_by'] = $this->session->userdata('admin_id');
            $data['creation_date'] = time();
            if (!preg_match("/^[a-zA-Z ]*$/", $data['username'])) {
                echo "Only letters and white space allowed in First Name";
                exit;
            }
            if (!preg_match("/^[a-zA-Z ]*$/", $data['surname'])) {
                echo "Only letters and white space allowed in Last Name";
                exit;
            }
            if (strlen($data['email']) > 0) {
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    echo "<h5>Invalid Customers email format</h5>";
                    exit;
                }
                $is_email_unique = $this->crud_model->verify_if_unique('user', 'email = ' . $this->db->escape($data['email']));
                if (is_array($is_email_unique)) {
                    echo "<h5>Customers Email ID already exist.</h5>";
                    exit;
                }
            }
            $is_phone_unique = $this->crud_model->verify_if_unique('user', 'phone = ' . $this->db->escape($data['phone']));
            if (is_array($is_phone_unique)) {
                echo "Phone no. already exist.";
                exit;
            }
            $this->db->insert('user', $data);
        } else if ($para1 == 'edit') {
            $page_data['user_data'] = $this->db->get_where('user', array(
                        'user_id' => $para2
                    ))->result_array();
            $page_data['all_users'] = $this->db->get('user')->result_array();
            $this->load->view('back/admin/user_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['username'] = $this->input->post('first_name');
            $data['surname'] = $this->input->post('last_name');
            if (!preg_match("/^[a-zA-Z ]*$/", $data['username'])) {
                echo "Only letters and white space allowed in First Name";
                exit;
            }
            if (!preg_match("/^[a-zA-Z ]*$/", $data['surname'])) {
                echo "Only letters and white space allowed in Last Name";
                exit;
            }
            if (isset($_POST['change_number']) && $_POST['change_number'] == 'yes') {
                $data['phone'] = $this->input->post('contact_number');
            }
            if (isset($_POST['change_email']) && $_POST['change_email'] == 'yes') {
                $data['email'] = $this->input->post('email_address');
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    echo "Invalid email format";
                    exit;
                }
            }
            $data['updated_by'] = $this->session->userdata('admin_id');
            $is_email_unique = $this->crud_model->verify_if_unique('user', 'email = ' . $this->db->escape($data['email']) . ' And user_id !=' . $this->db->escape($para2));
            if (is_array($is_email_unique)) {
                echo "Email already exist.";
                exit;
            }
            $is_phone_unique = $this->crud_model->verify_if_unique('user', 'phone = ' . $this->db->escape($data['phone']) . ' And user_id !=' . $this->db->escape($para2));
            if (is_array($is_phone_unique)) {
                echo "Phone no. already exist.";
                exit;
            }
            $query = $this->db->query("Select * From user where user_id =" . $this->db->escape($para2));
            if ($query !== false && $query->num_rows() == 1) {
                $this->db->where('user_id', $para2);
                $this->db->update('user', $data);
            } else {
                echo 'Something is wrong';
                exit;
            }
        } elseif ($para1 == 'delete') {
            $this->db->where('user_id', $para2);
            $this->db->delete('user');
        } elseif ($para1 == 'list') {
            $this->load->view('back/admin/user_list');
        } elseif ($para1 == 'list_data') {
            //Pagination for Users List  : START
            $limit = $this->input->get('limit');
            $search = $this->input->get('search');
            $order = $this->input->get('order');
            $offset = $this->input->get('offset');
            $sort = $this->input->get('sort');
            $this->db->where('deleted_at IS NULL', null, false);
            if ($search) {
                $this->db->where("((u.first_name like '%".$search."%') or (u.fourth_name like '%".$search."%') or (u.email like '%".$search."%') or (u.phone like '%".$search."%'))");
            }
            
//          $this->db->select('u.*,IFNULL(ug.group_title,"NA")');
            $this->db->select('u.*');
            $total = $this->db->get('user as u')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'user_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            $this->db->where('deleted_at IS NULL', null, false);
            if ($search) {
                $this->db->where("((u.first_name like '%".$search."%') or (u.fourth_name like '%".$search."%') or (u.email like '%".$search."%') or (u.phone like '%".$search."%'))");
            }
            $this->db->select('u.*');
            $products = $this->db->get('user as u', $limit, $offset)->result_array();
            $data = array();
            $i = 0;

            $user_view = $this->crud_model->admin_permission('user_view');
            /*
            $user_add_wallet_balance = $this->crud_model->admin_permission('user_add_wallet_balance');
            $user_reduce_wallet_balance = $this->crud_model->admin_permission('user_reduce_wallet_balance');
            $user_wallet_type = $this->crud_model->admin_permission('user_wallet_type');
            */
            $user_add_wallet_balance = false;
            $user_reduce_wallet_balance = false;
            $user_wallet_type = false;
            foreach ($products as $row) {
                $i++;
                $res = array(
                    'no' => '',
                    'first_name' => '',
                    'second_name' => '',
                    'third_name' => '',
                    'fourth_name' => '',
                    'email' => '',
                    'status' => '',
                    'phone' => '',
                    'options' => ''
                );
                $res['no'] = $row['user_id'];
                $res['full_name'] = $row['first_name'] . ' ' .$row['fourth_name'];
                $res['first_name'] = $row['first_name'];
                $res['second_name'] = $row['second_name'];
                $res['third_name'] = $row['third_name'];
                $res['fourth_name'] = $row['fourth_name'];
                $res['email'] = $row['email'];
                $res['phone'] = $row['phone'];
                if ($row['status'] == 'Active') {
                    $res['status'] = '<input id="pub_' . $row['user_id'] . '" class="sw1" type="checkbox" data-id="' . $row['user_id'] . '" checked />';
                } else {
                    $res['status'] = '<input id="pub_' . $row['user_id'] . '" class="sw1" type="checkbox" data-id="' . $row['user_id'] . '" />';
                }
                //add html for action
                $action = '';
                if ($user_view) {
                    $action .= "  <a class=\"btn btn-mint btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_modal('view','" . 'View Profile' . "','" . 'Successfully Viewed!' . "','user_view','" . $row['user_id'] . "');\" data-original-title=\"View\" data-container=\"body\">
                                    " . 'Profile' . "  </a>";
                }
                
                if($user_wallet_type){
                $action .= "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-tags\" data-toggle=\"tooltip\" 
                    onclick=\"ajax_modal('user_wallet_type','" . translate( 'update_wallet_type' ) . "','" . translate( 'wallet_type_updated_successfully!' ) . "','wallet_type','" . $row['user_id'] . "');\" data-original-title=\"wallet_type\" data-container=\"body\">
                        " . translate( 'wallet_type' ) . "  </a>";
                } 
                
                if($user_add_wallet_balance){
                $action .= "  <a class=\"btn btn-purple btn-xs btn-labeled fa fa-plus\" data-toggle=\"tooltip\" 
                    onclick=\"ajax_modal('user_wallet_add','" . translate( 'add_wallet_balance' ) . "','" . translate( 'wallet_balance_added_successfully!' ) . "','wallet_balance_add','" . $row['user_id'] . "');\" data-original-title=\"wallet_balance_add\" data-container=\"body\">
                        " . translate( 'wallet_balance' ) . "  </a>";
                }  
                if($user_reduce_wallet_balance){
                $action .= "  <a class=\"btn btn-warning btn-xs btn-labeled fa fa-minus\" style=\"color:black;\" data-toggle=\"tooltip\" 
                    onclick=\"ajax_modal('user_wallet_destroy','" . translate( 'Reduce_wallet_balance' ) . "','" . translate( 'wallet_balance_destroy_successfully!' ) . "','wallet_balance_destroy','" . $row['user_id'] . "');\" data-original-title=\"wallet_balance_destroy\" data-container=\"body\">
                        " . translate( 'wallet_balance' ) . "  </a>";
                }
               /* if ($user_edit) {
                    $action .= " <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('edit','" . 'Edit User' . "','" . 'Successfully Edited!' . "','user_edit','" . $row['user_id'] . "');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                    " . 'Edit' . "
                                                    </a>";
                } */

               
		 /*
                if ($user_delete) {
                    $action .= " <a onclick=\"delete_confirm('" . $row['user_id'] . "','" . 'Really want to delete this?' . "')\" 
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    " . 'delete' . "
                                                     </a>";
                }*/
                $res['options'] = $action;
                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );

            echo json_encode($result);
            //Pagination for Users List  : End
        } elseif ($para1 == 'view') {
            $page_data['user_data'] = $this->db->get_where('user', array(
                        'user_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/user_view', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/user_add');
        } elseif ($para1 == 'addaddress') {
            $this->load->view('back/admin/address_add', array('user_id' => $para2));
        } elseif ($para1 == 'editaddress') {
            if (!empty($para2)) {
                $getdata = explode('--', $para2);
                if (isset($getdata[0]) && isset($getdata[1])) {
                    $this->load->view('back/admin/address_edit', array(
                        'user_id' => $getdata[0],
                        'address_id' => $getdata[1]
                    ));
                } else {
                    echo 'Something is wrong, Please try again later';
                }
            } else {
                echo 'Something is wrong, Please try again later';
            }
        } elseif ($para1 == 'state') {
            echo $this->crud_model->select_html('state', 'state', 'name', 'add', 'demo-chosen-select required', '', 'country_id', $para2, 'get_city');
        } elseif ($para1 == 'city') {
            echo $this->crud_model->select_html('city', 'city', 'name', 'add', 'demo-chosen-select required', '', 'state_id', $para2, '');
        } elseif ($para1 == 'do_address') {
            if (!empty($para2)) {
                $data = array(
                    'user_id' => $para2,
                    'address_1' => $this->input->post('address_1'),
                    'address_2' => $this->input->post('address_2'),
                    'country_id' => $this->input->post('country'),
                    'state_id' => $this->input->post('state'),
                    'city_id' => $this->input->post('city'),
                    'pincode' => $this->input->post('pincode'),
                    'delivery_instructions' => $this->input->post('delivery_instructions'),
                    'created_by' => $this->session->userdata('admin_id'),
                    'created_on' => date('Y-m-d H:i:s'),
                );
                if (isset($_POST['shipping_address']) && $_POST['shipping_address'] == 'yes') {
                    $data['default_address'] = 'ok';
                    $this->db->where('user_id', $para2);
                    $this->db->update('user_address', array('default_address' => ''));
                } else {
                    $data['default_address'] = '';
                }
                $this->db->insert('user_address', $data);
            } else {
                echo 'Something is wrong.';
            }
        } elseif ($para1 == 'do_address_edit') {
            if (!empty($para2) && !empty($para3)) {
                $data = array(
                    'user_id' => $para2,
                    'address_1' => $this->input->post('address_1'),
                    'address_2' => $this->input->post('address_2'),
                    'country_id' => $this->input->post('country'),
                    'state_id' => $this->input->post('state'),
                    'city_id' => $this->input->post('city'),
                    'pincode' => $this->input->post('pincode'),
                    'delivery_instructions' => $this->input->post('delivery_instructions'),
                    'updated_by' => $this->session->userdata('admin_id'),
                );
                if (isset($_POST['shipping_address']) && $_POST['shipping_address'] == 'yes') {
                    $data['default_address'] = 'ok';
                    $this->db->where('user_id', $para2);
                    $this->db->update('user_address', array('default_address' => ''));
                } else {
                    $data['default_address'] = '';
                }
                $this->db->where('user_id', $para2);
                $this->db->where('address_id', $para3);
                $this->db->update('user_address', $data);
            } else {
                echo 'Something is wrong.';
            }
        } elseif ( $para1 == 'approval_edit' ) {
                $user_id = $para2;
                $data['approval_status']       = $this->input->post( 'approval_status' );
                $data['approval_remark'] = $this->input->post( 'approval_remark' );
                if($_POST['approval_status'] == 'approved'){
                    $data['status']   = 'Active'; 
                }else{
                     $data['status']   = 'In-active'; 
                }
                $this->db->where( 'user_id', $user_id );
                $this->db->update( 'user', $data );
                if($data['approval_status'] == 'approved'){
                    $this->email_model->account_approval($user_id);
                }
        } elseif ( $para1 == 'add_credit_point' ) {
                $data['credit_amount'] = $this->input->post( 'credit_amount' );
                $data['user_id']       = $para2;
                $data['type']       = 'add';
                $data['remark']       = $this->input->post( 'remark' );
                $data['created_by']       =  $this->session->userdata('admin_id');
                $data['created_on']       =  date('Y-m-d H:i:s');
                $this->db->insert('user_credit', $data);
        } elseif ( $para1 == 'destroy_credit_point' ) {
                $data['credit_amount'] = $this->input->post( 'credit_amount' );
                $data['user_id']       = $para2;
                $data['type']       = 'destroy';
                $data['remark']       = $this->input->post( 'remark' );
                $data['created_by']       =  $this->session->userdata('admin_id');
                $data['created_on']       =  date('Y-m-d H:i:s');
                $this->db->insert('user_credit', $data);
        } elseif ( $para1 == 'add_wallet_balance' ) {
    
                $db_wallet_balance =  $this->db->get_where('user',array('user_id'=>$para2))->row()->wallet_balance;
                $input_balance = $this->input->post( 'wallet_balance' );
                $current_balance = $db_wallet_balance + $input_balance;
                $update_wallet_balance =  array(
                    'wallet_balance' =>$db_wallet_balance + $input_balance,
                );
                $this->db->where('user_id', $para2);
                $this->db->update('user', $update_wallet_balance);
                // Maintain entry in wallet
                $remark =  $this->input->post( 'remark' );
                if(empty(trim($remark))){
                    $remark = 'added by admin';
                }
                $data['amount'] = $this->input->post( 'wallet_balance' );
                $data['user_id'] = $para2;
                $data['type']     = 'credit';
                $data['reason']   = $remark;
                $data['date_time'] =  date('Y-m-d H:i:s');
                $data['wallet_balance']   = $current_balance;
                $data['admin_id']   = $this->session->userdata('admin_id');
                $this->db->insert('wallet', $data);
        } elseif ( $para1 == 'destroy_wallet_balance' ) {
                $db_wallet_balance =  $this->db->get_where('user',array('user_id'=>$para2))->row()->wallet_balance;
                $input_balance = $this->input->post( 'wallet_balance' );
                $current_balance = $db_wallet_balance - $input_balance;
                $update_wallet_balance =  array(
                    'wallet_balance' => $db_wallet_balance - $input_balance,
                );
                $this->db->where('user_id', $para2);
                $this->db->update('user', $update_wallet_balance);
                // Maintain entry in wallet
                $remark =  $this->input->post( 'remark' );
                if(empty(trim($remark))){
                    $remark = 'removed by admin';
                }
                $data['amount'] = $this->input->post( 'wallet_balance' );
                $data['user_id'] = $para2;
                $data['type']    = 'debit';
                $data['reason']  = $remark;
                $data['date_time'] =  date('Y-m-d H:i:s');
                $data['wallet_balance']   = $current_balance;
                $data['admin_id']   = $this->session->userdata('admin_id');
                $this->db->insert('wallet', $data);

        } elseif ( $para1 == 'user_credit_add' ) {
                $page_data['user_data']   = $this->db->get_where( 'user', array(
                        'user_id' => $para2
                ) )->result_array();
                $page_data['credit_limit_amount'] = $this->crud_model->getCurrentCreditLimit($para2);
                $this->load->view( 'back/admin/user_credit_point_add', $page_data );
        } elseif ( $para1 == 'user_credit_destroy' ) {
                $page_data['user_data']   = $this->db->get_where( 'user', array(
                        'user_id' => $para2
                ) )->result_array();
                $page_data['credit_limit_amount'] = $this->crud_model->getCurrentCreditLimit($para2);
                $this->load->view( 'back/admin/user_credit_point_destroy', $page_data );
        } elseif ( $para1 == 'user_wallet_add' ) {
                $page_data['user_data']   = $this->db->get_where( 'user', array(
                        'user_id' => $para2
                ) )->result_array();
                $page_data['wallet_amount'] = $this->crud_model->getCurrentWalletBalance($para2);
                $this->load->view( 'back/admin/user_wallet_add', $page_data );
        } elseif ( $para1 == 'user_wallet_destroy' ) {
                $page_data['user_data']   = $this->db->get_where( 'user', array(
                        'user_id' => $para2
                ) )->result_array();
                $page_data['wallet_amount'] = $this->crud_model->getCurrentWalletBalance($para2);
                $this->load->view( 'back/admin/user_wallet_destroy', $page_data );
        } elseif ( $para1 == 'user_card_update' ) {
            $page_data['user_data']   = $this->db->get_where( 'user', array(
                    'user_id' => $para2
            ) )->result_array();
            $page_data['credit_limit_amount'] = $this->crud_model->getCurrentCreditLimit($para2);
            $this->load->view( 'back/admin/user_update_card_no', $page_data );
        } elseif ( $para1 == 'user_wallet_type' ) {
            $page_data['user_wallet_type']   = $this->db->get_where( 'user', array(
                    'user_id' => $para2
            ) )->row()->wallet_type;
            $page_data['user_id'] =  $para2;
            $this->load->view( 'back/admin/user_wallet_type', $page_data );    
        } elseif ( $para1 == 'update_user_wallet_type' ) {
            $data['wallet_type']  =  $this->input->post( 'wallet_type' );
            $this->db->where('user_id', $para2);
            $this->db->update('user', $data);
        } elseif ($para1 == 'user_publish_set') {
            $user_id = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'Active';
                $data['access_token'] = '';
            } else {
                $data['status'] = 'In-active';
                $data['access_token'] = '';
            }
            $this->db->where('user_id', $user_id);
            $this->db->update('user', $data);   
        } else {
            $page_data['page_name'] = "user";
            $page_data['all_users'] = $this->db->get('user')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Administrator Management */

    function admins($para1 = '', $para2 = '', $para3  ='') {
        if (!$this->crud_model->admin_permission('admin')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'do_add') {
            $data['name'] = $this->input->post('name');
            $data['email'] = $email = $this->input->post('email');
            $password = $this->input->post('password');
            $data['password'] = sha1($password);
            $data['phone'] = $phone = $this->input->post('phone');
            $data['address'] = $this->input->post('address');
            $data['role'] = $this->input->post('role');
            $data['timestamp'] = time();
            $data['created_by'] = $_SESSION['admin_id'];
            if(empty( $data['role'])){
                echo "<h5>Select staff role<h5>";
                exit;
            }
            
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    echo "Invalid email format";
                    exit;
            }
            
            $is_email_unique = $this->crud_model->verify_if_unique('admin', 'email = ' . $this->db->escape($email));
            if (is_array($is_email_unique)) {
                echo 'Email ID Already Assigned to Another Admin User.<br>';
                exit;
            }
            
            if($data['role'] == 4){
                $data['user_type'] = $this->input->post('user_type');
                if (!(preg_match('/^[0-9]{10}+$/', $data['phone']))) {
                    echo ' Phone Number Length must be 10 digits<br>';
                    exit;
                }
                
                $is_phone_unique = $this->crud_model->verify_if_unique('admin', 'phone = ' . $this->db->escape($phone));
                if (is_array($is_phone_unique)) {
                    echo ' Phone Number Length must be 10 digits<br>';
                    exit;
                }

                if (empty($_POST['city'])) {
                    echo "<h5>Select City<h5>";
                    exit;
                }
                if ($this->input->post('area') == null) {
                    echo "<h5>Select Area<h5>";
                    exit;
                }
            }else{
                $data['user_type'] = '';
            }
            //added by sagar : FOR delivery purpose
            
            //staff with supplier store - START
            if($data['role'] == 9){
                $data['supplier_id'] = $this->input->post('supplier');
                $data['supplier_store_id'] = $this->input->post('supplier_store');
            }else{
                $data['supplier_id'] = 0;
                $data['supplier_store_id'] = 0;
            }
            //staff with supplier store - END
            
            $data['city_id'] =  $data['area_id'] = 0;
            if(!empty($_POST['city'])){
                $data['city_id'] = $_POST['city'];
            }
//            if(!empty($_POST['area'])){
//                $data['area_id'] = $_POST['area'];
//            }
            //added by sagar : FOR delivery purpose
            $data['area_ids'] = $this->input->post('area');
            if ($this->input->post('area') == null) {
                $data['area_ids'] = '[]';
            } else {
                $data['area_ids'] = json_encode($this->input->post('area'));
            }
            
            $this->db->insert('admin', $data);
            //commented by sagar
//            $this->email_model->account_opening('admin', $data['email'], $password);
        } else if ($para1 == 'edit') {
            $page_data['admin_data'] = $this->db->get_where('admin', array(
                        'admin_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/admin_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['name'] = $this->input->post('name');
            $password = $this->input->post('password');
            //added by sagar : START 
            if(isset($_POST['password_check']) && $_POST['password_check'] == 'yes'){
             $data['password'] = sha1($password);
            }
            //added by sagar : END 
            $data['phone'] =  $this->input->post('phone');
            $data['address'] = $this->input->post('address');
            $data['role'] = $this->input->post('role');
            
            
            if($data['role'] == 4){
                $data['user_type'] = $this->input->post('user_type');
                
                if (!(preg_match('/^[0-9]{10}+$/', $data['phone']))) {
                    echo ' Phone Number Length must be 10 digits<br>';
                    exit;
                }
                
                $is_phone_unique = $this->crud_model->verify_if_unique('admin', 'phone = ' . $this->db->escape($data['phone']) . ' And admin_id !=' . $this->db->escape($para2));
                if (is_array($is_phone_unique)) {
                    echo ' Phone Number Already Assigned to Another Admin User.<br>';
                    exit;
                }

                if (empty($_POST['city'])) {
                    echo "<h5>Select City<h5>";
                    exit;
                }
                if ($this->input->post('area') == null) {
                    echo "<h5>Select Area<h5>";
                    exit;
                }
                
            }else{
                $data['user_type'] = '';
            }
            //added by sagar : FOR delivery purpose
            
            //staff with supplier store - START
            if($data['role'] == 9){
                $data['supplier_id'] = $this->input->post('supplier');
                $data['supplier_store_id'] = $this->input->post('supplier_store');
            }else{
                $data['supplier_id'] = 0;
                $data['supplier_store_id'] = 0;
            }
            //staff with supplier store - END
            
            
            $data['city_id'] =  $data['area_id'] = 0;
            if(!empty($_POST['city'])){
                $data['city_id'] = $_POST['city'];
            }
//            if(!empty($_POST['area'])){
//                $data['area_id'] = $_POST['area'];
//            }
            //added by sagar : FOR delivery purpose
            $data['area_ids'] = $this->input->post('area');
            if ($this->input->post('area') == null) {
                $data['area_ids'] = '[]';
            } else {
                $data['area_ids'] = json_encode($this->input->post('area'));
            }
            //added by sagar - 29-06 - start
            $data['update_timestamp'] = time();
            $data['updated_by'] = $_SESSION['admin_id'];
            //added by sagar - 29-06 - end
            
            $this->db->where('admin_id', $para2);
            $this->db->update('admin', $data);
            //commented by sagar
//            $this->email_model->account_opening('admin', $data['email'], $password);
        } elseif ($para1 == 'delete') {
            $this->db->where('admin_id', $para2);
            $this->db->delete('admin');
        } elseif ($para1 == 'list') {
            $this->db->order_by('admin_id', 'desc');
            //change by sagar : -START 23-06-2020
            $login_role_id =  $_SESSION['role_id'];
            if($login_role_id == 1){
            $page_data['all_admins'] = $this->db->get('admin')->result_array();
            }else{
            $page_data['all_admins'] = $this->db->get_where('admin',array('role !='=> 1))->result_array();
            }
            //change by sagar : END 23-06-2020
            $page_data['user_role']  = $this->crud_model->get_type_name_by_id('role',$_SESSION['role_id']);
            $this->load->view('back/admin/admin_list', $page_data);
        } elseif ($para1 == 'view') {
            $page_data['admin_data'] = $this->db->get_where('admin', array(
                        'admin_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/admin_view', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/admin_add');
        } elseif ( $para1 == 'cityarea' ) {
            echo $this->crud_model->select_html( 'area', 'area', 'area_name_en', 'add', 'demo-cs-multiselect', '', 'city_id', $para2, 'other' );
        } elseif ( $para1 == 'suppStores' ) {
            echo $this->crud_model->select_html('supplier_store', 'supplier_store', 'store_name|store_number|store_address', 'add', 'demo-chosen-select required', '', 'supplier_id', $para2, 'other');
        } elseif ($para1 == 'admin_publish') {
            $admin_id = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'Active';
        } else {
                $data['status'] = 'In-active';
                $data['access_token'] = '';
            }
            $this->db->where('admin_id', $admin_id);
            $this->db->update('admin', $data);
        } else {
            $page_data['page_name'] = "admin";
            $page_data['all_admins'] = $this->db->get('admin')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Account Role Management */

    function role($para1 = '', $para2 = '') {
        if (!$this->crud_model->admin_permission('role')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'do_add') {
            
            $data['name'] = $this->input->post('name');
            $data['permission'] = json_encode($this->input->post('permission'));
            $data['description'] = $this->input->post('description');
            $this->db->insert('role', $data);
        } elseif ($para1 == "update") {
            $data['name'] = $this->input->post('name');
            $data['permission'] = json_encode($this->input->post('permission'));
            $data['description'] = $this->input->post('description');
            $this->db->where('role_id', $para2);
            $this->db->update('role', $data);
        } elseif ($para1 == 'delete') {
            $this->db->where('role_id', $para2);
            $this->db->delete('role');
        } elseif ($para1 == 'list') {
            $this->db->order_by('role_id', 'desc');
            $page_data['all_roles'] = $this->db->get('role')->result_array();
            $this->load->view('back/admin/role_list', $page_data);
        } elseif ($para1 == 'view') {
            $page_data['role_data'] = $this->db->get_where('role', array(
                        'role_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/role_view', $page_data);
        } elseif ($para1 == 'add') {
            //$page_data['all_permissions'] = $this->db->get('permission')->result_array();
            //added by ritesh for keeping permissions on track : start
            $page_data['all_permissions'] = $this->db->get_where('permission', array(
                        'to_be_considered' => 'Yes'
                    ))->result_array();
            //added by ritesh for keeping permissions on track : end
            $this->load->view('back/admin/role_add', $page_data);
        } else if ($para1 == 'edit') {
            //$page_data['all_permissions'] = $this->db->get('permission')->result_array();
            //added by ritesh for keeping permissions on track : start
            $page_data['all_permissions'] = $this->db->order_by('parent_status DESC , codename ASC ')->get_where('permission', array(
                        'to_be_considered' => 'Yes'
                    ))->result_array();
            //added by ritesh for keeping permissions on track : end
            $page_data['role_data'] = $this->db->get_where('role', array(
                        'role_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/role_edit', $page_data);
        } else {
            $page_data['page_name'] = "role";
            $page_data['all_roles'] = $this->db->get('role')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Checking if email exists*/
    function exists() {
        $email  = $this->input->post( 'email' );
        $admin  = $this->db->get( 'admin' )->result_array();
        $exists = 'no';
        foreach ( $admin as $row ) {
                if ( $row['email'] == $email ) {
                        $exists = 'yes';
                }
        }
        echo $exists;
    }

    function phoneExists() {
        $email  = $this->input->post( 'phone' );
        $admin  = $this->db->get( 'admin' )->result_array();
        $exists = 'no';
        foreach ( $admin as $row ) {
                if ( $row['phone'] == $email ) {
                        $exists = 'yes';
                }
        }
        echo $exists;
    }
	
    /* Login into Admin panel */

    function login($para1 = '') {

        if ($para1 == 'forget_form') {
            $page_data['control'] = 'admin';
            $this->load->view('back/forget_password', $page_data);
        } else if ($para1 == 'forget') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            if ($this->form_validation->run() == false) {
                echo validation_errors();
            } else {
                $query = $this->db->get_where('admin', array(
                    'email' => $this->input->post('email')
                ));
                if ($query->num_rows() > 0) {
                    $admin_id = $query->row()->admin_id;
                    $password = substr(hash('sha512', rand()), 0, 12);
                    $data['password'] = sha1($password);
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('admin', $data);
                  
                    if ($this->email_model->password_reset_email('admin', $admin_id, $password)) {
                        echo 'email_sent';
                    } else {
                        echo 'email_not_sent';
                    }
                } else {
                    echo 'email_nay';
                }
            }
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');
            if ($this->form_validation->run() == false) {
                echo validation_errors();
            } else {

                $login_data = $this->db->get_where('admin', array(
                    'email' => $this->input->post('email'),
                    'password' => sha1($this->input->post('password'))
                ));
             
                if ($login_data->num_rows() > 0) {

                    $login_type = $this->input->post('login_type');
                    foreach ($login_data->result_array() as $row) {
//                        if (( $row['role'] == 12 && $login_type == 'accounts' ) || ( $row['role'] == 11 && $login_type == 'scheduler' ) || ( $row['role'] == 10 && $login_type == 'callcenter' ) || ( $row['role'] == 7 && $login_type == 'buyer' ) || ( $row['role'] == 8 && $login_type == 'callcenter' ) || ( $row['role'] == 9 && $login_type == 'warehouse' ) || ( $row['role'] == 2 && $login_type == 'contenteditor' ) || ( $row['role'] == 1 && $login_type == 'admin' )) {
                            $this->session->set_userdata('login', 'yes');
                            $this->session->set_userdata('login_type', $login_type);
                            $this->session->set_userdata('admin_login', 'yes');
                            $this->session->set_userdata('admin_id', $row['admin_id']);
                            $this->session->set_userdata('admin_name', $row['name']);
                            $this->session->set_userdata('title', 'admin');
                            //added by ritesh : start to check for buyer
                            $this->session->set_userdata('extra', $row['role']);
                            $this->session->set_userdata('role_id', $row['role']);
                            //added by ritesh : end to check for buyer
                            $this->session->set_userdata('mapped_supplier_id', $row['supplier_id']);
                            $this->session->set_userdata('mapped_store_id', $row['supplier_store_id']);
                            echo 'lets_login';
//                        } else {
//                            echo 'login_failed';
//                        }
                    }
                } else {
                    echo 'login_failed';
                }
            }
        }
    }

    /* Loging out from Admin panel */

    function logout() {
        $redirect_to = 'admin';
        if (isset($_SESSION['login_type'])) {
            $redirect_to = $_SESSION['login_type'];
        }
        $this->session->sess_destroy();
        redirect(base_url() . '' . $redirect_to, 'refresh');
    }

    /* Checking Login Stat */

    function is_logged() {
        if ($this->session->userdata('admin_login') == 'yes') {
            echo 'yah!good';
        } else {
            echo 'nope!bad';
        }
    }
    
    /* Manage Admin Settings */
    function manage_admin($para1 = "") {
        if ($this->session->userdata('admin_login') != 'yes') {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'update_password') {
            $user_data['password'] = $this->input->post('password');
            $account_data = $this->db->get_where('admin', array(
                        'admin_id' => $this->session->userdata('admin_id')
                    ))->result_array();
            foreach ($account_data as $row) {
                if (sha1($user_data['password']) == $row['password']) {
                    if ($this->input->post('password1') == $this->input->post('password2')) {
                        $data['password'] = sha1($this->input->post('password1'));
                        $this->db->where('admin_id', $this->session->userdata('admin_id'));
                        $this->db->update('admin', $data);
                        echo 'updated';
                    }
                } else {
                    echo 'pass_prb';
                }
            }
        } else if ($para1 == 'update_profile') {
            $this->db->where('admin_id', $this->session->userdata('admin_id'));
            $this->db->update('admin', array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone')
            ));
        } else {
            $page_data['page_name'] = "manage_admin";
            $this->load->view('back/index', $page_data);
        }
    }

    function default_images($para1 = "", $para2 = "") {
        if (!$this->crud_model->admin_permission('default_images')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == "set_images") {
            move_uploaded_file($_FILES[$para2]['tmp_name'], 'uploads/' . $para2 . '/default.jpg');
            recache();
        }
        $page_data['default_list'] = array(
            'product_image',
            'digital_logo_image',
            'category_image',
            'sub_category_image',
            'brand_image',
            'blog_image',
            'banner_image',
            'user_image',
            'vendor_logo_image',
            'vendor_banner_image',
            'membership_image',
            'slides_image'
        );
        $page_data['page_name'] = "default_images";
        $this->load->view('back/index', $page_data);
    }

    //added by ritesh : start
    function searchProducts() {
        if (isset($_GET['enteredValue']) && !empty($_GET['enteredValue'])) {
            $productsFound = $this->crud_model->search_products($_GET['enteredValue']);
            if (is_array($productsFound)) {
                $response = array();
                foreach ($productsFound as $key => $val) {
                    $response[] = array(
                        'label' => $val['title'],
                        'value' => $val['product_id'],
                    );
                }
                if (count($productsFound) >= 10) {
                    $response[] = array(
                        'label' => 'Load More Products',
                        'value' => 'Load More Products',
                    );
                }
                echo json_encode($response);
            } else {
                $response[] = array(
                    'label' => 'No results found for ' . $_GET['enteredValue'],
                    'value' => 'No results found for ' . $_GET['enteredValue'],
                    'link' => base_url()
                );
                echo json_encode($response);
            }
        }
    }

    function searchProductsselect2() {
        if (isset($_GET['enteredValue']) && !empty($_GET['enteredValue'])) {
            $productsFound = $this->crud_model->call_center_search_products($_GET['enteredValue']);
            $response = array('results' => array(), 'pagination' => array("more" => false));
            if (is_array($productsFound)) {
                foreach ($productsFound as $key => $val) {
                    $response['results'][] = array(
                        'text' => $val['title'],
                        'id' => $val['product_id'],
                    );
                }
            }
            echo json_encode($response);
        }
    }

    function addRow($counter = 0) {
        $res['counter'] = $counter;
        $getprdtdetails = array();
        if (isset($_POST['productId']) && !empty($_POST['productId'])) {
            $getprdtdetails = $this->db->get_where('product', array(
                        'product_id' => $_POST['productId']
                    ))->result_array();
            $res['prdt'] = $getprdtdetails;
        }
        $view = $this->load->view('back/admin/salesRow', $res, true);
        echo json_encode(array('status' => 'success', 'body' => $view));
        exit();
    }

    function fetchCustomerAddress() {
        $getCustomerDetails = array();
        if (isset($_POST['custId']) && !empty($_POST['custId'])) {
            $getCustomerDetails = $this->crud_model->get_customer_address($_POST['custId']);
            $res['my_addresses'] = $getCustomerDetails;
        }

        $view = $this->load->view('back/admin/addressesRow', $res, true);
        echo json_encode(array('status' => 'success', 'body' => $view));
        exit();
    }

    //added by ritesh for cart table update : start
    private function cart_update_after_checkout($sale_id, $cart_array) {
        $update_cart = array('sale_id' => $sale_id);
        if (count($cart_array) > 0 && $sale_id > 0) {
            $this->db->where('sale_id', '0');
            $this->db->where_in('cart_id', $cart_array);
            $this->db->update('cart', $update_cart);
        }
    }

    //added by ritesh for cart table update : end

    function map_product_Row($counter = 0) {
        $res['counter'] = $counter;
        $getprdtdetails = array();

        if (isset($_POST['productId']) && !empty($_POST['productId'])) {
            $getprdtdetails = $this->db->get_where('product', array(
                        'product_id' => $_POST['productId']
                    ))->result_array();
            $res['prdt'] = $getprdtdetails;
            $res['prod_type'] = $getprdtdetails[0]['product_type'];
        }

        $view = $this->load->view('back/admin/map_product_Row', $res, true);
        echo json_encode(array('status' => 'success', 'body' => $view));
        exit();
    }

    function searchCustomerselect2() {
        if (isset($_GET['enteredValue']) && !empty($_GET['enteredValue'])) {

            $customerFound = $this->crud_model->searchCustomersDropDown($_GET['enteredValue']);
            $response = array('results' => array(), 'pagination' => array("more" => false));
            if (is_array($customerFound)) {
                foreach ($customerFound as $key => $val) {
                    $response['results'][] = array(
                        'text' => $val['username'] . ' ' . $val['surname'] . ' ' . $val['email'] . ' ' . $val['phone'],
                        'id' => $val['user_id'],
                    );
                }
            }
            echo json_encode($response);
        }
    }

    function searchVendorselect2() {
        if (isset($_GET['enteredValue']) && !empty($_GET['enteredValue'])) {
            $customerFound = $this->crud_model->searchVendorDropDown($_GET['enteredValue']);
            $response = array('results' => array(), 'pagination' => array("more" => false));
            if (is_array($customerFound)) {
                foreach ($customerFound as $key => $val) {
                    $response['results'][] = array(
                        'text' => $val['name'] . ' - ' . $val['store_name'] . ' - ' . $val['phone'],
                        'id' => $val['vendor_id'],
                    );
                }
            }
            echo json_encode($response);
        }
    }

    function slides($para1 = '', $para2 = '', $para3 = '') {
        if (!$this->crud_model->admin_permission('slides')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'do_add') {
            $data['uploaded_by'] = 'admin';
            $data['status'] = 'ok';
            $data['slides_lang'] = $this->input->post('slides_lang');
            $data['added_by'] = json_encode(array(
                'type' => 'admin',
                'id' => $this->session->userdata('admin_id')
            ));
            $this->db->insert('slides', $data);
            $id = $this->db->insert_id();
            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['button_link'] = 'slides_' . $id . '.' . $ext;
                $this->crud_model->file_up("img", "slides", $id, '', 'no', '.' . $ext);
                $this->db->where('slides_id', $id);
                $this->db->update('slides', $data_banner);
            }
            recache();
        } elseif ($para1 == "update") {
            $data['uploaded_by'] = 'admin';
            $data['slides_lang'] = $this->input->post('slides_lang');
            $data['added_by'] = json_encode(array(
                'type' => 'admin',
                'id' => $this->session->userdata('admin_id')
            ));
            
            $this->db->where('slides_id', $para2);
            $this->db->update('slides', $data);
            $this->crud_model->file_up("img", "slides", $para2, '', '', '.jpg');
            if ($_FILES['img']['name'] !== '') {
                $path = $_FILES['img']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_banner['button_link'] = 'slides_' . $para2 . '.' . $ext;
                $this->crud_model->file_up("img", "slides", $para2, '', 'no', '.' . $ext);
                $this->db->where('slides_id', $para2);
                $this->db->update('slides', $data_banner);
            }
            recache();
        } elseif ($para1 == 'delete') {
            $this->crud_model->file_dlt('slides', $para2, '.jpg');
            $this->db->where('slides_id', $para2);
            $this->db->delete('slides');
            recache();
        } elseif ($para1 == 'multi_delete') {
            $ids = explode('-', $param2);
            $this->crud_model->multi_delete('slides', $ids);
        } else if ($para1 == 'edit') {
            $page_data['slides_data'] = $this->db->get_where('slides', array(
                        'slides_id' => $para2
                    ))->result_array();
            $this->load->view('back/admin/slides_edit', $page_data);
        } elseif ($para1 == 'list') {
            $this->db->order_by('slides_id', 'desc');
            $this->db->where('uploaded_by', 'admin');
            $page_data['all_slidess'] = $this->db->get('slides')->result_array();
            $this->load->view('back/admin/slides_list', $page_data);
        } elseif ($para1 == 'slide_publish_set') {
            $slides_id = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('slides_id', $slides_id);
            $this->db->update('slides', $data);
            recache();
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/slides_add');
        } else {
            $page_data['page_name'] = "slides";
            $page_data['all_slidess'] = $this->db->get('slides')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function collection($para1 = '', $para2 = '', $para3 = '') {
        if (!$this->crud_model->admin_permission('collection')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'do_add') {
            $options = array();
            $data['title'] = $this->input->post('title');
            $data['title_ar'] = $this->input->post('title_ar');
            $data['display_in_columns'] = $this->input->post('display_in_columns');
            $data['visible_on_home_page'] = $this->input->post('visible_on_home_page');
            $data['visible_on_search_page'] = $this->input->post('visible_on_search_page');
            $data['is_scrollable'] = $this->input->post('is_scrollable');
            $data['order_by_collection'] = $this->input->post('order_by_collection');
            $data['type'] = $this->input->post('collection_type');
            $is_collection_name_unique = $this->crud_model->verify_if_unique('collection', 'title = ' . $this->db->escape($data['title']));
            if (is_array($is_collection_name_unique)) {
                echo "<h5>Collection Title already exist.<h5>";
                exit;
            }
            if (empty($_FILES['img']['name']) && $this->input->post('collection_type') =='single') {
                echo "<h5>For Single Type Image Upload is Mandatory<h5>";
                exit;
            }

            if (empty($_FILES['img_file']['name']) && $this->input->post('collection_type') =='multiple') {
                echo "<h5>For Multiple Type Image Upload is Mandatory<h5>";
                exit;
            }

            if (isset($_POST['is_offer'])) {
                $data['is_offer'] = $_POST['is_offer'];
            }
            $data['status'] = 'ok';
            $product_for_collection = json_encode(array());
            if (is_array($_POST['product_for_collection'])) {
                $product_for_collection = json_encode($_POST['product_for_collection']);
            }
            $data['product_for_collection'] = $product_for_collection;
            
            $brand_for_collection = json_encode(array());
            if (is_array($_POST['brand_for_collection'])) {
                $brand_for_collection = json_encode($_POST['brand_for_collection']);
            }
            $data['brand_for_collection'] = $brand_for_collection;

//                $data['added_by']           = json_encode(array('type'=>'admin','id'=>$this->session->userdata('admin_id')));
            $data['added_by'] = $this->session->userdata('admin_id');
            $data['added_on'] = date('Y-m-d h:i:s');

            $this->db->insert('collection', $data);
            $id = $this->db->insert_id();

            if (isset($_POST['is_offer']) && $_POST['is_offer'] == 'yes') {
                if ($_FILES['img']['name'] !== '') {
                    // echo "<pre>";
                    // print_r($_FILES);
                    // echo "</pre>";
                    // die;
                    $path = $_FILES['img']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $data_banner['collection_image'] = 'collection_' . $id . '.' . $ext;
                    $this->crud_model->file_up("img", "collection", $id, '', 'no', '.' . $ext);
                    $this->db->where('collection_id', $id);
                    $this->db->update('collection', $data_banner);
                }
            }
            $extDetForMulti = $this->db->select('extra_details')->get_where('collection', ['collection_id' => $id])->row('extra_details');
            $extra_details = json_decode($extDetForMulti, true);

            if (!empty($extra_details)) {
                foreach ($extra_details['extra_details'] as $index => $val) {
                    $old_extra_ids[] = $val['extra_id'] ?? '';
                }
                $get_removable_ids = array_diff($old_extra_ids, $this->input->post('extra_id'));
            
                foreach ($extra_details['extra_details'] as $index => $val) {
                    if (isset($val['extra_id']) && in_array($val['extra_id'], $get_removable_ids)) {
                        unset($extra_details['extra_details'][$index]);
                        $file_path = 'uploads/collection_image/' . $val['img_path'];
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }
                }
            }
            $extra_details['extra_details'] = array_values($extra_details['extra_details'] ?? []);

            $uniqueKey = 0;            
            // If an image is uploaded, the following code will be executed
            if ($this->input->post('collection_type') == "multiple") {
                foreach ($this->input->post('img_name') as $key => $value) {
                    if (isset($extra_details['extra_details'][$key])) {
                        // Update existing record if it exists in the database
                        $extra_details['extra_details'][$key]['img_name'] = $this->input->post('img_name')[$uniqueKey];
            
                        // If image update
                        if (isset($_FILES['img_file']['tmp_name'][$uniqueKey]) && $_FILES['img_file']['error'][$uniqueKey] === 0) {
                            $file_path = 'uploads/collection_image/' . $extra_details['extra_details'][$key]['img_path'];
                            if (file_exists($file_path)) {
                                unlink($file_path);
                            }
                            $fileTmpName = $_FILES['img_file']['tmp_name'][$uniqueKey];
                            $destinationPath = 'uploads/collection_image/collection_' . $id . '_' . $uniqueKey . '.jpg';

                            if (move_uploaded_file($fileTmpName, $destinationPath)) {
                                $extra_details['extra_details'][$key]['img_path'] = 'collection_' . $id . '_' . $uniqueKey . '.jpg';
                            } else {
                                $extra_details['extra_details'][$key]['img_path'] = '';
                            }
                        }
                        $extra_details['extra_details'][$key]['img_clickable'] = ($this->input->post('img_clickable')[$uniqueKey] == '1') ? 'Yes' : 'No';
                        $extra_details['extra_details'][$key]['extra_id'] = $id . '_' . $uniqueKey;
                    } else {
                        // Add a new image record
                        if (isset($_FILES['img_file']['tmp_name'][$uniqueKey])) {
                            $fileTmpName = $_FILES['img_file']['tmp_name'][$uniqueKey];
                            $destinationPath = 'uploads/collection_image/collection_' . $id . '_' . $uniqueKey . '.jpg';
                            if (move_uploaded_file($fileTmpName, $destinationPath)) {
                                $imgPath = 'collection_' . $id . '_' . $uniqueKey . '.jpg';
                            } else {
                                $imgPath = '';
                            }
                        } else {
                            $imgPath = '';
                        }
                        
                        $extra_details['extra_details'][] = [
                            'img_name'      => $this->input->post('img_name')[$uniqueKey] ?? '',
                            'img_path'      => $imgPath,
                            'img_clickable' => (isset($this->input->post('img_clickable')[$uniqueKey]) && $this->input->post('img_clickable')[$uniqueKey] == '1') ? 'Yes' : 'No',
                            'extra_id'      => $id . '_' . $uniqueKey,
                        ];
                        
                    }
                    $uniqueKey++;
                }
                $final_val = $extra_details['extra_details'];
            }
                        
            
            // if category and sub category are not empty, the following code will be executed
            if (!empty($this->input->post('category'))) 
            {
                $final_val = array();
            
                $filtered_array = array_diff($this->input->post('category'), array(null));
                if(!empty($this->input->post('sub_category'))){
                    $form_subcat_ids = array_unique(array_filter($this->input->post('sub_category')));
                }else{
                    $form_subcat_ids = array();
                }
            
                foreach ($filtered_array as $value) {
                    $results = $this->db->select('sub_category_name, sub_category_id')
                        ->from('sub_category')
                        ->where('category', $value)
                        ->get()
                        ->result_array();
                    
                    $cat_name = $this->db->select('category_name')
                        ->from('category')
                        ->where('category_id', $value)
                        ->get()
                        ->row()
                        ->category_name;
                        
            
                    $ext_new_data = array();
                    $ext_new_data['category_id'] = $value;
                    $ext_new_data['category_name'] = $cat_name;
                    
                    foreach ($results as $row) {
                        
                        if (in_array($row['sub_category_id'], $form_subcat_ids)) {
                            $ext_new_data['sub_category'][] = array(
                                'sub_category_id' => $row['sub_category_id'],
                                'sub_category_name' => $row['sub_category_name']
                            );
                        }
                    }
            
                    $final_val[] = $ext_new_data;
                }
            }

            $extra_details_main = [
                'type' => $this->input->post('collection_type'),
                'extra_details' => $final_val,
            ];
        
            if (!empty($extra_details_main)) {
                $data = [
                    'extra_details' => ($this->input->post('collection_type') == "multiple" || $this->input->post('collection_type') == "category") ? json_encode($extra_details_main) : null
                ];
        
                $this->db->where('collection_id', $id)
                            ->update('collection', $data);
            }

        } else if ($para1 == "update") {
            $options = array();
            $data['title'] = $this->input->post('title');
            $data['title_ar'] = $this->input->post('title_ar');
            $data['display_in_columns'] = $this->input->post('display_in_columns');
            $data['visible_on_home_page'] = $this->input->post('visible_on_home_page');
            $data['visible_on_search_page'] = $this->input->post('visible_on_search_page');
            $data['is_scrollable'] = $this->input->post('is_scrollable');
//                $data['order_by_collection']              = $this->input->post('order_by_collection');
            $data['order_by_collection'] = $this->input->post('order_by_collection');
            $is_collection_name_unique = $this->crud_model->verify_if_unique('collection', 'title = ' . $this->db->escape($data['title']) . ' And collection_id !=' . $this->db->escape($para2));
            if (is_array($is_collection_name_unique)) {
                echo "<h5>Collection Title already exist.<h5>";
                exit;
            }

//                if(isset($_POST['is_offer']) && $_POST['is_offer'] == 'yes'){
//                 $data['is_offer']        = $_POST['is_offer'];
//                }else{
//                  $data['is_offer'] = 'no';  
//                  $data['collection_drive_id'] = '';  
//                }
            $product_for_collection = json_encode(array());
            if (is_array($_POST['product_for_collection'])) {
                $product_for_collection = json_encode($_POST['product_for_collection']);
            }
          
            $data['product_for_collection'] = $product_for_collection;

            $brand_for_collection = json_encode(array());
            if (is_array($_POST['brand_for_collection'])) {
                $brand_for_collection = json_encode($_POST['brand_for_collection']);
            }
            $data['brand_for_collection'] = $brand_for_collection;
            
            $this->db->where('collection_id', $para2);
            $this->db->update('collection', $data);
            if(is_array($_FILES)){  //added by sagar : if image NOT present 
                if (!empty($_FILES['img']['name'])) {
                    $path = $_FILES['img']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $data_banner['collection_image'] = 'collection_' . $para2 . '.' . $ext;
                    $this->crud_model->file_up("img", "collection", $para2, '', 'no', '.' . $ext);
                    $this->db->where('collection_id', $para2);
                    $this->db->update('collection', $data_banner);
                }
            }
            $extDetForMulti = $this->db->select('extra_details')->get_where('collection', ['collection_id' => $para2])->row('extra_details');
            $extra_details = json_decode($extDetForMulti, true);

            if (!empty($extra_details)) {
                foreach ($extra_details['extra_details'] as $index => $val) {
                    $old_extra_ids[] = $val['extra_id'] ?? '';
                }
                $get_removable_ids = array_diff($old_extra_ids, $this->input->post('extra_id'));
            
                foreach ($extra_details['extra_details'] as $index => $val) {
                    if (isset($val['extra_id']) && in_array($val['extra_id'], $get_removable_ids)) {
                        unset($extra_details['extra_details'][$index]);
                        $file_path = 'uploads/collection_image/' . $val['img_path'];
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }
                }
            }
            $extra_details['extra_details'] = array_values($extra_details['extra_details'] ?? []);
            

            $uniqueKey = 0;
            
            // If an image is uploaded, the following code will be executed
            if ($this->input->post('collection_type') == "multiple") {
                foreach ($this->input->post('img_name') as $key => $value) {
                    if (isset($extra_details['extra_details'][$key])) {
                        // Update existing record if it exists in the database
                        $extra_details['extra_details'][$key]['img_name'] = $this->input->post('img_name')[$uniqueKey];
            
                        // If image update
                        if (isset($_FILES['img_file']['tmp_name'][$uniqueKey]) && $_FILES['img_file']['error'][$uniqueKey] === 0) {
                            $file_path = 'uploads/collection_image/' . $extra_details['extra_details'][$key]['img_path'];
                            if (file_exists($file_path)) {
                                unlink($file_path);
                            }
                            $fileTmpName = $_FILES['img_file']['tmp_name'][$uniqueKey];
                            $destinationPath = 'uploads/collection_image/collection_' . $para2 . '_' . $uniqueKey . '.jpg';

                            if (move_uploaded_file($fileTmpName, $destinationPath)) {
                                $extra_details['extra_details'][$key]['img_path'] = 'collection_' . $para2 . '_' . $uniqueKey . '.jpg';
                            } else {
                                $extra_details['extra_details'][$key]['img_path'] = '';
                            }
                        }
                        $extra_details['extra_details'][$key]['img_clickable'] = ($this->input->post('img_clickable')[$uniqueKey] == '1') ? 'Yes' : 'No';
                        $extra_details['extra_details'][$key]['extra_id'] = $para2 . '_' . $uniqueKey;
                    } else {
                        // Add a new image record
                        if (isset($_FILES['img_file']['tmp_name'][$uniqueKey])) {
                            $fileTmpName = $_FILES['img_file']['tmp_name'][$uniqueKey];
                            $destinationPath = 'uploads/collection_image/collection_' . $para2 . '_' . $uniqueKey . '.jpg';
                            if (move_uploaded_file($fileTmpName, $destinationPath)) {
                                $imgPath = 'collection_' . $para2 . '_' . $uniqueKey . '.jpg';
                            } else {
                                $imgPath = '';
                            }
                        } else {
                            $imgPath = '';
                        }
                        
                        $extra_details['extra_details'][] = [
                            'img_name'      => $this->input->post('img_name')[$uniqueKey] ?? '',
                            'img_path'      => $imgPath,
                            'img_clickable' => (isset($this->input->post('img_clickable')[$uniqueKey]) && $this->input->post('img_clickable')[$uniqueKey] == '1') ? 'Yes' : 'No',
                            'extra_id'      => $para2 . '_' . $uniqueKey,
                        ];
                        
                    }
                    $uniqueKey++;
                }
                $final_val = $extra_details['extra_details'];
            }
            
            
            // if category and sub category are not empty, the following code will be executed
            if (!empty($this->input->post('category'))) 
            {
                $final_val = array();
            
                $filtered_array = array_diff($this->input->post('category'), array(null));
                if(!empty($this->input->post('sub_category'))){
                    $form_subcat_ids = array_unique(array_filter($this->input->post('sub_category')));
                }else{
                    $form_subcat_ids = array();
                }
            
                foreach ($filtered_array as $value) {
                    $results = $this->db->select('sub_category_name, sub_category_id')
                        ->from('sub_category')
                        ->where('category', $value)
                        ->get()
                        ->result_array();
                    
                    $cat_name = $this->db->select('category_name')
                        ->from('category')
                        ->where('category_id', $value)
                        ->get()
                        ->row()
                        ->category_name;
                        
            
                    $ext_new_data = array();
                    $ext_new_data['category_id'] = $value;
                    $ext_new_data['category_name'] = $cat_name;
                    
                    foreach ($results as $row) {
                        
                        if (in_array($row['sub_category_id'], $form_subcat_ids)) {
                            $ext_new_data['sub_category'][] = array(
                                'sub_category_id' => $row['sub_category_id'],
                                'sub_category_name' => $row['sub_category_name']
                            );
                        }
                    }
            
                    $final_val[] = $ext_new_data;
                }
            }

            $extra_details_main = [
                'type' => $this->input->post('collection_type'),
                'extra_details' => $final_val,
            ];
        
            if (!empty($extra_details_main)) {
                $data = [
                    'extra_details' => ($this->input->post('collection_type') == "multiple" || $this->input->post('collection_type') == "category") ? json_encode($extra_details_main) : null
                ];
        
                $this->db->where('collection_id', $para2)
                            ->update('collection', $data);
            }    
        } else if ($para1 == 'edit') {
            $page_data['product_data'] = $this->db->get_where('collection', array(
                        'collection_id' => $para2
                    ))->result_array();
            $page_data['main_product_data'] = $this->db->get_where('product', array(
                        'status' => 'ok'
                    ))->result_array();
            $page_data['main_brand_data'] = $this->db->get_where('brand', array(
                        'status' => 'ok'
                    ))->result_array();
                    
            $this->db->select('*');
            $this->db->from('collection');
            $this->db->where('collection_id', $para2);
            $query = $this->db->get();
            $data = $query->row();
            
            $extData = json_decode($data->extra_details, true);

            $multiple=array();
            //read data for multiple type 
            if (!empty($extData) && $data->type == "multiple"){
                foreach($extData['extra_details'] as $key => $value){
                    $customKey['img_names'] = $value['img_name'];           
                    $customKey['img_path'] = $value['img_path'];           
                    $customKey['img_links'] = "";           
                    $customKey['img_clickables'] = $value['img_clickable'];           
                    $customKey['extra_id'] = $value['extra_id'];           
                    array_push($multiple,$customKey);
                }
            }
            $page_data['collection_type_multiple'] = $multiple;

            if (!empty($extData) && $data->type == "category"){
                function transformArray($inputArray) {
                    $outputArray = [
                        'type' => $inputArray['type'],
                        'extra_details' => [],
                    ];
                
                    foreach ($inputArray['extra_details'] as $item) {
                        $subCategories = array_map(function ($sub) {
                            return $sub['sub_category_id'];
                        }, $item['sub_category']);
                
                        $outputArray['extra_details'][] = [
                            'category_id' => $item['category_id'],
                            // 'sub_category' => implode(',', $subCategories),
                            'sub_category' => $subCategories,
                        ];
                    }
                
                    return $outputArray;
                }    
                $outputArray = transformArray($extData);
                $page_data['collection_type_category'] = $outputArray; 
                    
                // $data->sub_category_array = explode(',', $outputArray['extra_details']['sub_category']);  
            }
            $this->db->select('*');
            $this->db->from('category');
            $category_data = $this->db->get()->result_array();
            $page_data['category_all'] = $category_data;

            $this->db->select('*');
            $this->db->from('sub_category');
            $sub_category_data = $this->db->get()->result_array();
            $page_data['sub_category_all'] = $sub_category_data;
            $page_data['dropdownStatus'] = array('No','Yes');
        
            $this->load->view('back/admin/collection_edit', $page_data);
        } elseif ($para1 == 'delete') {
            $this->crud_model->file_dlt('collection', $para2, '.jpg', 'multi');
            $this->db->where('collection_id', $para2);
            $this->db->delete('collection');
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('collection_id', 'desc');
            $page_data['all_product'] = $this->db->get('collection')->result_array();
            $this->load->view('back/admin/collection_list', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit = $this->input->get('limit');
            $search = $this->input->get('search');
            $order = $this->input->get('order');
            $offset = $this->input->get('offset');
            $sort = $this->input->get('sort');
            if ($search) {
                $this->db->like('title', $search, 'both');
            }

            $total = $this->db->get('collection')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'collection_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            if ($search) {
                $this->db->like('title', $search, 'both');
            }

            $products = $this->db->get('collection', $limit, $offset)->result_array();
            $collection_edit = $this->crud_model->admin_permission('collection_edit');
          
            $data = array();
            foreach ($products as $row) {
                $res = array(
                    'image' => '',
                    'title' => '',
                    'type' => '',
                    'status' => '',
                    'options' => ''
                );

                if ($row['collection_image'] && file_exists('uploads/collection_image/' . $row['collection_image'])) {
                    $res['image'] = '<img class="img-sm" style="height:auto !important; border:1px solid #ddd;padding:2px; border-radius:2px !important;" src="' . base_url() . 'uploads/collection_image/' . $row['collection_image'] . '"  />';
                } else {
                    $res['image'] = '<img class="img-sm" style="height:auto !important; border:1px solid #ddd;padding:2px; border-radius:2px !important;" src="' . base_url() . 'uploads/default.jpg"  />';
                }
                $res['title'] = $row['title'];
                $res['type'] = $row['type'];

                if ($row['status'] == 'ok') {
                    $res['status'] = '<input id="pub_' . $row['collection_id'] . '" class="sw1" type="checkbox" data-id="' . $row['collection_id'] . '" checked />';
                } else {
                    $res['status'] = '<input id="pub_' . $row['collection_id'] . '" class="sw1" type="checkbox" data-id="' . $row['collection_id'] . '" />';
                }



                //add html for action
                if($collection_edit) {
                $res['options'] = "  
                                    <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" 
                                        onclick=\"ajax_set_full('edit','" . translate('edit_product') . "','" . translate('successfully_edited!') . "','product_edit','" . $row['collection_id'] . "');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                        " . translate('edit') . "
                                    </a> ";
                }
                /*
                  "<a onclick=\"delete_confirm('".$row['collection_id']."','".translate('really_want_to_delete_this?')."')\"
                  class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                  ".translate('delete')."
                  </a>";
                 */
                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );

            echo json_encode($result);
        } else if ($para1 == 'dlt_img') {
            $a = explode('_', $para2);

            $this->crud_model->file_dlt('collection', $a[0], '.jpg', 'multi', $a[1]);
            recache();
        } elseif ($para1 == 'add') {
            $page_data = array();
            $page_data['product_data'] = $this->db->get_where('product', array(
                        'status' => 'ok'
                    ))->result_array();
            $page_data['brand_data'] = $this->db->get_where('brand', array(
                        'status' => 'ok'
                    ))->result_array();
            $page_data['category_data'] = $this->db->get('category')->result_array();
            $this->load->view('back/admin/collection_add', $page_data);
        } elseif ($para1 == 'product_publish_set') {
            $product = $para2;

            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('collection_id', $product);
            $this->db->update('collection', $data);
            recache();
        } else {
            $page_data['page_name'] = "collection";
            $page_data['all_product'] = $this->db->get('collection')->result_array();

            $this->load->view('back/index', $page_data);
        }
    }
    function get_subcat_ids() {
        $data = array();
        $category_id = $this->input->post('category_id');

        if (!empty($category_id)) {
            $data = $this->db->get_where('sub_category', array('category' => $category_id))->result_array();
        }

        echo json_encode($data);
    }
    function newsletter($para1 = "", $para2 = "", $para3 = "") {
        if ($this->session->userdata('admin_login') != 'yes') {
            redirect(base_url() . 'index.php/admin');
        }
        if (!$this->crud_model->admin_permission('newsletter')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'delete') {
            $this->db->where('newsletter_id', $para2);
            $this->db->delete('newsletter');
        } elseif ($para1 == 'list') {
            $this->db->order_by('newsletter_id', 'desc');
            $page_data['newsletter'] = $this->db->get('newsletter')->result_array();
            $this->load->view('back/admin/newsletter_list', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit = $this->input->get('limit');
            $search = $this->input->get('search');
            $order = $this->input->get('order');
            $offset = $this->input->get('offset');
            $sort = $this->input->get('sort');
            $total = $this->db->get('newsletter')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'newsletter_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            if ($search) {
                $this->db->or_like('newsletter_email', $search, 'both');
            }

            $products = $this->db->get('newsletter', $limit, $offset)->result_array();
            $newsletter_delete = $this->crud_model->admin_permission('newsletter');
            $data = array();
            foreach ($products as $row) {
                $res = array(
                    'no' => '',
                    'email' => '',
                    'date' => '',
                    'options' => ''
                );
                $res['no'] = $row['newsletter_id'];
                $res['email'] = $row['newsletter_email'];
                $res['date'] = date('d M,Y h:i:s', strtotime($row['created_on']));

                //add html for action
                $action = '';
                if($newsletter_delete){
                $action .= " <a onclick=\"delete_confirm('" . $row['newsletter_id'] . "','" . translate('really_want_to_delete_this?') . "')\" 
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    " . translate('delete') . "
                                                     </a>";
                }
                $res['options'] = $action;
                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );

            echo json_encode($result);
        } else {
            $page_data['page_name'] = "newsletter";
            $page_data['newsletter'] = $this->db->get('newsletter')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    
    function coupon($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->admin_permission('coupon')) {
            redirect(base_url() . 'index.php/admin');
        }
        if ($para1 == 'do_add') {
            $is_coupon_title_unique = $this->crud_model->verify_if_unique('coupon', 'title = ' . $this->db->escape($this->input->post('title')));
            if (is_array($is_coupon_title_unique)) {
                echo "<h5>Coupon Title already exist.<h5>";
                exit;
            }
            $is_coupon_code_unique = $this->crud_model->verify_if_unique('coupon', 'code = ' . $this->db->escape($this->input->post('code')));
            if (is_array($is_coupon_code_unique)) {
                echo "<h5>Coupon Title already exist.<h5>";
                exit;
            }
           
            $data['title'] = $this->input->post('title');
            $data['code'] = $this->input->post('code');
            $data['start_date'] =  $this->input->post('start_from');
            $data['discount_type'] = 'percent'; //$this->input->post('discount_type');
            $data['discount_value'] = $this->input->post('discount_value');
            $data['till'] = $this->input->post('till');
            $data['status'] = $this->input->post('status');
            $data['added_by'] = json_encode(array('type'=>'admin','id'=>$this->session->userdata('admin_id')));
            $this->db->insert('coupon', $data);
        } else if ($para1 == 'edit') {
            $page_data['coupon_data'] = $this->db->get_where('coupon', array(
                'coupon_id' => $para2
            ))->result_array();
            $this->load->view('back/admin/coupon_edit', $page_data);
        } elseif ($para1 == "update") {
            $is_coupon_title_unique = $this->crud_model->verify_if_unique('coupon', 'title = ' . $this->db->escape($this->input->post('title')). ' AND coupon_id != '.  $this->db->escape($para2));
            if (is_array($is_coupon_title_unique)) {
                echo "<h5>Coupon Title already exist.<h5>";
                exit;
            }
            $is_coupon_code_unique = $this->crud_model->verify_if_unique('coupon', 'code = ' . $this->db->escape($this->input->post('code')). ' AND coupon_id != '.  $this->db->escape($para2));
            if (is_array($is_coupon_code_unique)) {
                echo "<h5>Coupon Title already exist.<h5>";
                exit;
            }
            $data['title'] = $this->input->post('title');
            $data['code'] = $this->input->post('code');
            $data['start_date'] =  $this->input->post('start_from');
            $data['discount_type'] = 'percent';//$this->input->post('discount_type');
            $data['discount_value'] = $this->input->post('discount_value');
            $data['till'] = $this->input->post('till');
            $data['status'] = $this->input->post('status');
            $this->db->where('coupon_id', $para2);
            $this->db->update('coupon', $data);
          
        } elseif ($para1 == 'delete') {
            $this->db->where('coupon_id', $para2);
            $this->db->delete('coupon');
        } elseif ($para1 == 'list') {
            $this->db->order_by('coupon_id', 'desc');
            $page_data['all_coupons'] = $this->db->get('coupon')->result_array();
            $this->load->view('back/admin/coupon_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/admin/coupon_add');
        } elseif ($para1 == 'publish_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'Active';
            } else {
                $data['status'] = 'In-active';
            }
            $this->db->where('coupon_id', $product);
            $this->db->update('coupon', $data);
        } else {
            $page_data['page_name']      = "coupon";
            $page_data['all_coupons'] = $this->db->get('coupon')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    function contact_us($para1 = "", $para2 = "") {
        if ($this->session->userdata('admin_login') != 'yes') {
            redirect(base_url() . 'index.php/admin');
        }
        if (!$this->crud_model->admin_permission('enquiry')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'delete') {
            $this->db->where('enquiry_id', $para2);
            $this->db->delete('enquiry');
        } elseif ($para1 == 'list') {
            $this->db->order_by('enquiry_id', 'desc');
            $page_data['enquiry'] = $this->db->get('enquiry')->result_array();
            $this->load->view('back/admin/enquiry_list', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit = $this->input->get('limit');
            $search = $this->input->get('search');
            $order = $this->input->get('order');
            $offset = $this->input->get('offset');
            $sort = $this->input->get('sort');
            $total = $this->db->get('enquiry')->num_rows();
            $this->db->limit($limit);

            if ($sort == '') {
                $sort = 'enquiry_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);

            if ($search) {
                $this->db->or_like('email', $search);
                $this->db->or_like('mobile', $search);
                $this->db->or_like('name', $search);
            }
            $products = $this->db->get('enquiry', $limit, $offset)->result_array();
            $delete_enquiry = $this->crud_model->admin_permission('enquiry_delete');
            $data = array();

            foreach ($products as $row) {
                $res = array(
                    'name' => '',
                    'email' => '',
                    'mobile' => '',
                    'msg' => '',
                    'date' => '',
                    'options' => ''
                );
                $res['name'] = $row['name'];
                $res['email'] = $row['email'];
                $res['mobile'] = $row['mobile'];
                $res['msg'] = $row['msg'];
                $res['date'] = date('d M,Y h:i:s', strtotime($row['created_on']));

                //add html for action
                $action = '';

                if ($delete_enquiry) {
                    $action .= " <a onclick=\"delete_confirm('" . $row['enquiry_id'] . "','" . translate('really_want_to_delete_this?') . "')\" 
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    " . translate('delete') . "
                                                     </a>";
                }
                $res['options'] = $action;
                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );
            echo json_encode($result);
        } else {
            $page_data['page_name'] = "enquiry";
            $page_data['enquiry'] = $this->db->get('enquiry')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    
    function enquiry_bk($para1 = "", $para2 = "", $para3 = "") {
        if ($this->session->userdata('admin_login') != 'yes') {
            redirect(base_url() . 'index.php/admin');
        }
        if (!$this->crud_model->admin_permission('enquiry')) {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'delete') {
            $this->db->where('enquiry_id', $para2);
            $this->db->delete('enquiry');
        } elseif ($para1 == 'list') {
            $this->db->order_by('enquiry_id', 'desc');
            $page_data['enquiry'] = $this->db->get('enquiry')->result_array();
            $this->load->view('back/admin/enquiry_list', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit = $this->input->get('limit');
            $search = $this->input->get('search');
            $order = $this->input->get('order');
            $offset = $this->input->get('offset');
            $sort = $this->input->get('sort');
            $total = $this->db->get('enquiry')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'enquiry_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            if ($search) {
                $this->db->or_like('email', $search, 'both');
                $this->db->or_like('mobile', $search, 'both');
                $this->db->or_like('name', $search, 'both');
            }
            
            $products = $this->db->get('enquiry', $limit, $offset)->result_array();
            $delete_enquiry = $this->crud_model->admin_permission('enquiry_delete');
            $data = array();
            foreach ($products as $row) {
                $res = array(
                    'name' => '',
                    'email' => '',
                    'mobile' => '',
                    'msg' => '',
                    'date' => '',
                    'options' => ''
                );

                $res['name'] = $row['name'];
                $res['email'] = $row['email'];
                $res['mobile'] = $row['mobile'];
                $res['msg'] = $row['msg'];
                $res['date'] = date('d M,Y h:i:s', strtotime($row['created_on']));

                //add html for action
                $action = '';
                if($delete_enquiry){
                $action .= " <a onclick=\"delete_confirm('" . $row['enquiry_id'] . "','" . translate('really_want_to_delete_this?') . "')\" 
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    " . translate('delete') . "
                                                     </a>";
                }
                $res['options'] = $action;
                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );

            echo json_encode($result);
        } else {
            $page_data['page_name'] = "enquiry";
            $page_data['enquiry'] = $this->db->get('enquiry')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    
    /*
    function groupCustomer($para1 = '', $para2 = '', $para3 = '') {

        if ($para1 == 'do_add') {
            $options = array();
            $data['group_title'] = $this->input->post('group_title');
            $data['group_discount'] = $this->input->post('group_discount');
            $data['offer_validity'] = $this->input->post('offer_validity');
            $is_group_title_unique = $this->crud_model->verify_if_unique('user_group', 'group_title = ' . $this->db->escape($data['group_title']));
            if (is_array($is_group_title_unique)) {
                echo "<h5>Group Title already exist.<h5>";
                exit;
            }
            $products_in_group = json_encode(array());
            if (is_array($_POST['products_in_group'])) {
                $products_in_group = json_encode($_POST['products_in_group']);
            }
            $data['products_in_group'] = $products_in_group;
            $data['status'] = 'Active';
            $data['created_by'] = $this->session->userdata('admin_id');
            $data['created_on'] = date('Y-m-d h:i:s');
            $this->db->insert('user_group', $data);
            $id = $this->db->insert_id();
            if (isset($_POST['users_in_group']) && is_array($_POST['users_in_group'])) {
                    $user_multi_ids = $_POST['users_in_group'];
                    $data_user_group['user_group_id'] = $id;
                    $this->db->where_in('user_id', $user_multi_ids);
                    $this->db->update('user', $data_user_group);
            }
            
        } else if ($para1 == "update") {
            //firstly updating users 'user_group_id' as 0 and then assign group_id to new users 
            $data_user['user_group_id'] = 0;
            $this->db->where('user_group_id', $para2);
            $this->db->update('user', $data_user);

            $data['group_title'] = $this->input->post('group_title');
            $data['group_discount'] = $this->input->post('group_discount');
            $data['offer_validity'] = $this->input->post('offer_validity');
            $is_group_title_unique = $this->crud_model->verify_if_unique('user_group', 'group_title = ' . $this->db->escape($data['group_title']) .' AND user_group_id != '.  $this->db->escape($para2));
            if (is_array($is_group_title_unique)) {
                echo "<h5>Group Title already exist.<h5>";
                exit;
            }
            $products_in_group = json_encode(array());
            if (is_array($_POST['products_in_group'])) {
                $products_in_group = json_encode($_POST['products_in_group']);
            }
            $data['products_in_group'] = $products_in_group;
            $this->db->where('user_group_id', $para2);
            $this->db->update('user_group', $data);
            //updating user_group_id of customers 
            if (isset($_POST['users_in_group']) && is_array($_POST['users_in_group'])) {
                $user_multi_ids = $_POST['users_in_group'];
                $data_user_group['user_group_id'] = $para2;
                $this->db->where_in('user_id', $user_multi_ids);
                $this->db->update('user', $data_user_group);
            }
        

        } else if ($para1 == 'edit') {
            $page_data['data'] = $this->db->get_where('user_group', array(
                        'user_group_id' => $para2
                    ))->result_array();
            $page_data['product_data'] = $this->db->get_where('product', array(
                        'status' => 'ok'
                    ))->result_array();
            
            
            //Allowing only those B2B customer who are not in Other Group
            $multi_ids =  array('0',$para2);
            $condition = "status = 'Active' AND approval_status = 'approved' AND user_type=''b2b'" ;
            $page_data['customer_data'] = $this->db->where_in( 'user_group_id', $multi_ids )->get_where('user', array(
                        'user_type'=>'b2b','status'=>'Active','approval_status'=>'approved'
                    ))->result_array();
            $this->load->view('back/admin/group_customer_edit', $page_data);
        } elseif ($para1 == 'list') {
            $this->load->view('back/admin/group_customer_list', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit = $this->input->get('limit');
            $search = $this->input->get('search');
            $order = $this->input->get('order');
            $offset = $this->input->get('offset');
            $sort = $this->input->get('sort');
            if ($search) {
                $this->db->like('group_title', $search, 'both');
            }

            $total = $this->db->get('user_group')->num_rows();
            $this->db->limit($limit);
            if ($sort == '') {
                $sort = 'user_group_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort, $order);
            if ($search) {
                $this->db->like('title', $search, 'both');
            }

            $products = $this->db->get('user_group', $limit, $offset)->result_array();
            $data = array();
            foreach ($products as $row) {
                $res = array(
                    'title' => '',
                    'status' => '',
                    'users_in_group' => '',
                    'products_in_group' => '',
                    'options' => ''
                );

                $res['title'] = $row['group_title'];

                if ($row['status'] == 'ok') {
                    $res['status'] = '<input id="pub_' . $row['user_group_id'] . '" class="sw1" type="checkbox" data-id="' . $row['user_group_id'] . '" checked />';
                } else {
                    $res['status'] = '<input id="pub_' . $row['user_group_id'] . '" class="sw1" type="checkbox" data-id="' . $row['user_group_id'] . '" />';
                }
                
                $products_in_group=json_decode($row['products_in_group'],true);
                $products = "";
                 foreach ($products_in_group as $row1) {
                    $products .= '<label class="label label-primary " style="margin-right: 5px;">'.$this->crud_model->get_type_name_by_id('product',$row1,'title').'</label>';
                    $res['products_in_group'] = $products;
                }
                
                
                //add html for action
                $res['options'] = "  
                                    <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" 
                                        onclick=\"ajax_set_full('edit','" . translate('edit_group') . "','" . translate('successfully_edited!') . "','group_edit','" . $row['user_group_id'] . "');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                        " . translate('edit') . "
                                    </a> ";
                $data[] = $res;
            }
            $result = array(
                'total' => $total,
                'rows' => $data
            );

            echo json_encode($result);
        } elseif ($para1 == 'add') {
            $page_data = array();
            $page_data['customer_data'] = $this->db->get_where('user', array(
                        'status' => 'Active','approval_status'=>'approved',
                        'user_type'=>'b2b','user_group_id'=>'0'
                    ))->result_array();
            $page_data['product_data'] = $this->db->get_where('product', array(
                        'status' => 'ok'
                    ))->result_array();
            $this->load->view('back/admin/group_customer_add', $page_data);
        } elseif ($para1 == 'product_publish_set') {
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('user_group_id', $para2);
            $this->db->update('user_group', $data);
            recache();
        } else {
            $page_data['page_name'] = "group_customer";
            $page_data['all_product'] = $this->db->get('user_group')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    */ 
    function updateProductsPrice( $para1 = '', $para2 = '' ) {
            if ( ! $this->crud_model->admin_permission( 'update_products_price' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
           if($para1 == 'download'){
                 $data = $this->crud_model->getProductExportData();
                 $this->load->library('Excel');
                 //Create new PHPExcel object
                 $objPHPExcel = new PHPExcel();
                 //Set properties
                 $objPHPExcel->getProperties()->setCreator("MypcotInfotech")
                 ->setLastModifiedBy("admin")
                 ->setTitle("Office 2007 XLSX Document")
                 ->setSubject("Office 2007 XLSX product List Doc")
                 ->setDescription("Admin panel product variation details")
                 ->setKeywords("office 2007 mypcot trolley php")
                 ->setCategory("Export Excel");

                 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true); 
                 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');

                 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);


                 // Rename sheet
                 $objPHPExcel->getActiveSheet()->setTitle('Product_Price_Details');
                 // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                 $objPHPExcel->setActiveSheetIndex(0);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("A1", 'UniqueNo', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("B1", 'ProductName_En', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("C1", 'ProductName_Ar', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("D1", 'Unit', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("E1", 'SalePrice', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("F1", 'SupplierSalePrice', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("G1", 'SupplierName', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("H1", 'CategoryName', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("I1", 'SubCategoryName', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("J1", 'BrandName', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("K1", 'Product Code', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("L1", 'SKU Code', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("M1", 'Publish', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("N1", 'Is New Product', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->getStyle("A1:N1")->getFont()->setBold(true);
                 $objPHPExcel->getActiveSheet()->getStyle("A1:N1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                 $objPHPExcel->getActiveSheet()->getStyle("A1:N1")->getFill()->getStartColor()->setRGB('FFFF00');
                 $objPHPExcel->getActiveSheet()->getStyle("A1:N1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                 //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
                 //$objPHPExcel->getActiveSheet()->getStyle('A1:C20')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
                 $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                  if(is_array($data) && !empty($data) && count($data) > 0)
                    {
                         for($j=2,$k=0 ; $k<count($data); $k++){
                             $product_variation_title = $data[$k]['title'];
                             $product_variation_title_ar = $data[$k]['title_ar'];
                             if(!empty($data[$k]['variation_title'])){
                                 $product_variation_title .= "(".$data[$k]['variation_title'].")";
                             }
                             $publish = 'no';
                             if($data[$k]['status'] == 'ok'){
                                 $publish = 'yes';
                             }
                             $isNewProd = "no";
                             if($data[$k]['featured'] == 'yes'){
                                 $isNewProd = 'yes';
                             }
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j", $data[$k]['variation_id'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j", $product_variation_title );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j", $product_variation_title_ar );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j", $data[$k]['weight']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j", $data[$k]['variation_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j", $data[$k]['variation_supplier_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j", $data[$k]['supplier_name'] );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j", $data[$k]['category_name'] );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j", $data[$k]['sub_category_name'] );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j", $data[$k]['brand_name'] );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j", $data[$k]['product_code'] );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j", $data[$k]['sku_code'] );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j", $publish );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j", $isNewProd);
                            $j++;
                         }
                        
                    }
                 // Redirect output to a client's web browser (Excel5)
                 header('Content-Type: application/vnd.ms-excel');
                 header('Content-Disposition: attachment;filename="ProductPriceDetails.xls"');
                 header('Cache-Control: max-age=0');
                 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                 $objWriter->save('php://output');
           }else if($para1 == 'saveproductprice'){
                ini_set('memory_limit', '-1');
		if(isset($_FILES['importproductexcel']['name']) && !empty($_FILES['importproductexcel']['name']))
  		{
			$name = $_FILES['importproductexcel']['name'];
			$names = explode(".", $name);
			$size = $_FILES['importproductexcel']['size'];
			$max_file_size = 1024*1024*2;		// 2 MB
                      
			if ((end($names)=="xls" || end($names)=="XLS" || end($names) == "csv"  || end($names) == "CSV"))
			{
                                //Load the excel library
				$this->load->library('excel');
				
				//Read file from path
				$objPHPExcel = PHPExcel_IOFactory::load($_FILES['importproductexcel']['tmp_name']);
				
				//Get only the Cell Collection
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,false,true);
                                if(is_array($sheetData)){
                                    if(!( isset($sheetData[1]['A']) &&$sheetData[1]['A']=='UniqueNo' 
                                            && isset($sheetData[1]['E']) && $sheetData[1]['E']=='SalePrice'
                                            && isset($sheetData[1]['F']) && $sheetData[1]['F']=='SupplierSalePrice'
                                            )){
      
                                                 echo ' Unsupported file format.<br> Correct Order is :: UniqueNo,SalePrice,SupplierSalePrice<br>';
                                                 echo '<a href="'. base_url().'admin/updateProductsPrice">Go Back</a>';
                                                 exit;
                                            }
                                }else{
                                    echo ' Unsupported file format only XLS / CSV files allowed.<br><br>';
                                     echo '<a href="'. base_url().'admin/updateProductsPrice">Go Back</a>';
                                    exit;
                                }
                                
				for($i = 2; $i < count($sheetData)+1; $i++)
                                {
                                    $variation_id = $sheetData[$i]['A'];
                                    $product_id =  $this->db->get_where('variation',array('variation_id'=>$variation_id))->row()->product_id;
                                    $data_array['sale_price']=$sheetData[$i]['E'];
                                    $data_array['supplier_price']=$sheetData[$i]['F'];
                                    //Variation price update
                                    $this->db->where('variation_id', $variation_id);
                                    $this->db->update('variation', $data_array);
                                    //Product price update
                                    $this->db->where('product_id', $product_id);
                                    $this->db->update('product', $data_array);
                                    echo 'Row '.$i.' is Product Price Updated Successfully.<br>';
                                }
                                echo '<a href="'. base_url().'admin/updateProductsPrice">Go Back</a>';
			}
			else 
			{
				echo ' Unsupported file format.<br><br>';
                                echo '<a href="'. base_url().'admin/updateProductsPrice">Go Back</a>';
                                exit;
			}
		}
		else{
			echo ' Please Select File.<br><br>';
                        echo '<a href="'. base_url().'admin/updateProductsPrice">Go Back</a>';
                        exit;
		}
		exit;
               
           }else{
            $page_data['page_name'] = "excel_for_product_price_update";
            $this->load->view( 'back/index', $page_data );
           }
    }
    
    function updateProductsDiscount( $para1 = '', $para2 = '' ) {
            if ( ! $this->crud_model->admin_permission( 'update_products_discount' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
           if($para1 == 'download'){
                 $data = $this->crud_model->getProductExportDiscountData(); //$this->db->select('product_id,title,discount,b2b_discount,is_offer,offer_validity')->get('product')->result_array();
                 $this->load->library('Excel');
                 //Create new PHPExcel object
                 $objPHPExcel = new PHPExcel();
                 //Set properties
                 $objPHPExcel->getProperties()->setCreator("MypcotInfotech")
                 ->setLastModifiedBy("admin")
                 ->setTitle("Office 2007 XLSX Document")
                 ->setSubject("Office 2007 XLSX product List Doc")
                 ->setDescription("Admin panel product variation details")
                 ->setKeywords("office 2007 mypcot trolley php")
                 ->setCategory("Export Excel");

                 //$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

                 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//                 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true); 
                 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');

                 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);


                 // Rename sheet
                 $objPHPExcel->getActiveSheet()->setTitle('Product_Discount_Details');
                 // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                 $objPHPExcel->setActiveSheetIndex(0);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("A1", 'UniqueNo', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("B1", 'ProductName', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("C1", 'Discount(%)', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("D1", 'Is Offer(yes|no)', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("E1", 'Offer Validity', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("F1", 'SupplierName', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("G1", 'CategoryName', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("H1", 'SubCategoryName', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("I1", 'BrandName', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("J1", 'SKU Code', PHPExcel_Cell_DataType::TYPE_STRING);

                 $objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold(true);
                 $objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                 $objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFill()->getStartColor()->setRGB('FFFF00');
                 $objPHPExcel->getActiveSheet()->getStyle("A1:j1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                 //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
                 //$objPHPExcel->getActiveSheet()->getStyle('A1:C20')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
                 $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                  if(is_array($data) && !empty($data) && count($data) > 0)
                    {
                         for($j=2,$k=0 ; $k<count($data); $k++){
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j", $data[$k]['product_id'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j", $data[$k]['title'] );
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j", $data[$k]['discount'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j", $data[$k]['is_offer']);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j", $data[$k]['supplier_name']);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j", $data[$k]['category_name']);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j", $data[$k]['sub_category_name']);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j", $data[$k]['brand_name']);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j", $data[$k]['SKU_code']);
                                $offer_Validity = "";
                                if(!empty($data[$k]['offer_validity']) && $data[$k]['offer_validity'] != '0000-00-00'){
                                    $offer_Validity= date('m/d/Y',strtotime($data[$k]['offer_validity']));
                                }
                                if(!empty($offer_Validity)){
                                    $t_date   = PHPExcel_Shared_Date::FormattedPHPToExcel(date('Y', strtotime($offer_Validity)), date('m', strtotime($offer_Validity)), date('d', strtotime($offer_Validity)));
                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j", $t_date);
                                    $objPHPExcel->getActiveSheet()->getStyle("E$j")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14); 
                                }

                                $j++;
                         }
                        
                    }
                 // Redirect output to a client's web browser (Excel5)
                 header('Content-Type: application/vnd.ms-excel');
                 header('Content-Disposition: attachment;filename="ProductDiscountDetails.xls"');
                 header('Cache-Control: max-age=0');
                 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                 $objWriter->save('php://output');
           }else if($para1 == 'saveproductdiscount'){
                ini_set('memory_limit', '-1');
		if(isset($_FILES['importproductexcel']['name']) && !empty($_FILES['importproductexcel']['name']))
  		{
			$name = $_FILES['importproductexcel']['name'];
			$names = explode(".", $name);
			$size = $_FILES['importproductexcel']['size'];
			$max_file_size = 1024*1024*2;		// 2 MB
			if ((end($names)=="xls" || end($names)=="XLS" || end($names) == "csv"  || end($names) == "CSV"))
			{
                                //Load the excel library
				$this->load->library('excel');
				
				//Read file from path
				$objPHPExcel = PHPExcel_IOFactory::load($_FILES['importproductexcel']['tmp_name']);
				//Get only the Cell Collection
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,false,true);
                               
                                if(is_array($sheetData)){
                                    if(!( isset($sheetData[1]['A']) &&$sheetData[1]['A']=='UniqueNo' 
                                            && isset($sheetData[1]['B']) && $sheetData[1]['B']=='ProductName'
                                            && isset($sheetData[1]['C']) && $sheetData[1]['C']=='Discount(%)'
                                            && isset($sheetData[1]['D']) && $sheetData[1]['D']=='Is Offer(yes|no)'
                                            && isset($sheetData[1]['E']) && $sheetData[1]['E']=='Offer Validity'
                                            )){
      
                                                 echo ' Unsupported file format.<br> Correct Order is :: UniqueNo,ProductName,Discount(%),Is Offer(yes|no),Offer Validity<br>';
                                                 echo '<a href="'. base_url().'admin/updateProductsDiscount">Go Back</a>';
                                                 exit;
                                            }
                                }else{
                                    echo ' Unsupported file format only XLS / CSV files allowed.<br><br>';
                                    echo '<a href="'. base_url().'admin/updateProductsDiscount">Go Back</a>';
                                    exit;
                                }
                                
				for($i = 2; $i < count($sheetData)+1; $i++)
                                {
                                    $product_id = $sheetData[$i]['A'];
                                    $data_array['discount']=$sheetData[$i]['C'];
                                    $data_array['is_offer']=$sheetData[$i]['D'];
                                    $offer_validity = "0000-00-00";
                                    if(!empty($sheetData[ $i ]['E'])){
                                        $offer_validity= date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($sheetData[ $i ]['E']));
                                    }
                                    $data_array['offer_validity'] = $offer_validity;
                                    $this->db->where('product_id', $product_id);
                                    $this->db->update('product', $data_array);
                                    echo 'Row '.$i.' is Product Discount Updated Successfully.<br>';
                                }
                                 echo '<a href="'. base_url().'admin/updateProductsDiscount">Go Back</a>';
			}
			else 
			{
				echo ' Unsupported file format.<br><br>';
                                 echo '<a href="'. base_url().'admin/updateProductsDiscount">Go Back</a>';
                                exit;
			}
		}
		else{
			echo ' Please Select File.<br><br>';
                         echo '<a href="'. base_url().'admin/updateProductsDiscount">Go Back</a>';
                        exit;
		}
		exit;
               
           }else{
            $page_data['page_name'] = "excel_for_product_discount_update";
            $this->load->view( 'back/index', $page_data );
           }
    }
    

    /* Suppliers added for particular product : start by ritesh*/
	function supplier( $para1 = '', $para2 = '', $para3 = '' ) {
		if ( ! $this->crud_model->admin_permission( 'supplier' ) ) {
			redirect( base_url() . 'index.php/admin' );
		}
		if ( $this->crud_model->get_type_name_by_id( 'general_settings', '68', 'value' ) !== 'ok' ) {
			redirect( base_url() . 'index.php/admin' );
		}
		if ( $para1 == 'do_add' ) {
			$options = array();
			$email_address = $this->input->post( 'email_address' );
			if ( ! filter_var( $_POST['email_address'], FILTER_VALIDATE_EMAIL ) ) {
				echo "Invalid email format";
				exit;
			}
			if ( ! ( preg_match( '/^[0-9]{10}+$/', $_POST['mobile_number'] ) ) ) {
				echo 'Mobile Number must be numeric and can be only 10 digits';
				exit;
			}
			$data = array();
			$data['supplier_name'] = $this->input->post( 'supplier_name' );
			$data['address']       = $this->input->post( 'address' );
			$data['payment_terms'] = $this->input->post( 'payment_terms' );
			$data['mobile_number'] = $this->input->post( 'mobile_number' );
			$data['email_address'] = $this->input->post( 'email_address' );
			$data['created_on']    = date( 'Y-m-d H:i:s' );
			$data['add_timestamp'] = time();
			$data['created_by']    = $this->session->userdata( 'admin_id' );
			$data['status']        = 'ok';
			$data['company_name']          = $this->input->post( 'company_name' );
			$data['billing_address']       = $this->input->post( 'billing_address' );
			$data['bank_details']          = $this->input->post( 'bank_details' );
			$data['other_details']   = $this->input->post( 'other_details' );
			// $data['added_by']           = json_encode(array('type'=>'admin','id'=>$this->session->userdata('admin_id')));
			$this->db->insert( 'supplier', $data );
			$id = $this->db->insert_id();
			recache();
		} else if ( $para1 == "update" ) {
			$options = array();
			if ( ! filter_var( $_POST['email_address'], FILTER_VALIDATE_EMAIL ) ) {
				echo "Invalid email format";
				exit;
			}
			$data = array();
			$data['supplier_name'] = $this->input->post( 'supplier_name' );
			$data['address']       = rtrim( $this->input->post( 'address' ) );
			$data['payment_terms'] = $this->input->post( 'payment_terms' );
			$data['mobile_number'] = $this->input->post( 'mobile_number' );
			$data['email_address'] = $this->input->post( 'email_address' );
			$data['updated_on']    = date( 'Y-m-d H:i:s' );
			$data['updated_by']    = $this->session->userdata( 'admin_id' );
			$data['status']        = 'ok';
			$data['company_name']          = $this->input->post( 'company_name' );
			$data['billing_address']       = $this->input->post( 'billing_address' );
			$data['bank_details']          = $this->input->post( 'bank_details' );
			$data['other_details']   = $this->input->post( 'other_details' );
			// $data['added_by']           = json_encode(array('type'=>'admin','id'=>$this->session->userdata('admin_id')));
			$this->db->where( 'supplier_id', $para2 );
			$this->db->update( 'supplier', $data );
			recache();
		} else if ( $para1 == 'edit' ) {
			$page_data['product_data'] = $this->db->get_where( 'supplier', array(
				'supplier_id' => $para2
			) )->result_array();
			$this->load->view( 'back/admin/supplier_edit', $page_data );
		} else if ( $para1 == 'view' ) {
			$page_data['product_data'] = $this->db->get_where( 'supplier', array(
				'supplier_id' => $para2
			) )->result_array();
			$this->load->view( 'back/admin/supplier_view', $page_data );
		} elseif ( $para1 == 'delete' ) {
			$this->crud_model->file_dlt( 'supplier', $para2, '.jpg', 'multi' );
			$this->db->where( 'supplier_id', $para2 );
			$this->db->delete( 'supplier' );
			recache();
		} elseif ( $para1 == 'list' ) {
			//list is called on the first Listing Screen
			$this->db->order_by( 'supplier_id', 'desc' );
			$this->db->where( 'status=', 'ok' );
			$page_data['all_product'] = $this->db->get( 'supplier' )->result_array();
			$this->load->view( 'back/admin/supplier_list', $page_data );
		} elseif ( $para1 == 'list_data' ) {
			$limit  = $this->input->get( 'limit' );
			$search = $this->input->get( 'search' );
			$order  = $this->input->get( 'order' );
			$offset = $this->input->get( 'offset' );
			$sort   = $this->input->get( 'sort' );
			if ( $search ) {
				$this->db->or_like( 'supplier_name', $search, 'both' );
				$this->db->or_like( 'mobile_number', $search, 'both' );
				$this->db->or_like( 'email_address', $search, 'both' );
				$this->db->or_like( 'company_name', $search, 'both' );
			}
			$this->db->where( 'status=', 'ok' );
			$total = $this->db->get( 'supplier' )->num_rows();
			$this->db->limit( $limit );
			if ( $sort == '' ) {
				$sort  = 'supplier_id';
				$order = 'DESC';
			}
			$this->db->order_by( $sort, $order );
			if ( $search ) {
				$this->db->or_like( 'supplier_name', $search, 'both' );
				$this->db->or_like( 'mobile_number', $search, 'both' );
				$this->db->or_like( 'email_address', $search, 'both' );
				$this->db->or_like( 'company_name', $search, 'both' );
			}
			$this->db->where( 'status=', 'ok' );
			$products = $this->db->get( 'supplier', $limit, $offset )->result_array();
                       
			$data = array();
			foreach ( $products as $row ) {
				$res = array(
					'supplier_name' => '',
					'mobile_number' => '',
					'email_address' => '',
					'company_name'  => '',
					'options'       => ''
				);
				$res['supplier_id'] = $row['supplier_id'];
				$res['supplier_name'] = $row['supplier_name'];
				$res['mobile_number'] = $row['mobile_number'];
				$res['email_address'] = $row['email_address'];
				$res['company_name']  = $row['company_name'];
				//add html for action
				$action = '';
                                
                                if ( $this->crud_model->admin_permission( 'supplier_store' ) ) {
                                $action .= "<a class=\"btn btn-purple btn-xs btn-labeled fa fa-plus\" data-toggle=\"tooltip\" 
                                            onclick=\"ajax_set_full('manage_store','" . translate('manage_store') . "','" . translate('successfully_fetched!') . "','product_edit','" . $row['supplier_id'] . "');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                                " . translate('Manage Store') . "
                                        </a>";
                                }
				if ( $this->crud_model->admin_permission( 'supplier_view' ) ) {
					$action .= "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('view','" . translate( 'view_supplier' ) . "','" . translate( 'successfully_viewed!' ) . "','supplier_view','" . $row['supplier_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                    " . translate( 'view' ) . "  </a>";
				}
				if ( $this->crud_model->admin_permission( 'supplier_edit' ) ) {
					$action .= " <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" 
                                onclick=\"ajax_set_full('edit','" . translate( 'edit_supplier' ) . "','" . translate( 'successfully_edited!' ) . "','supplier_edit','" . $row['supplier_id'] . "');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                    " . translate( 'edit' ) . "
                            </a>";
				}
                                
				/*if ( $this->crud_model->admin_permission( 'supplier_delete' ) ) {
					$action .= " <a onclick=\"delete_confirm('" . $row['supplier_id'] . "','" . translate( 'really_want_to_delete_this?' ) . "')\" 
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    " . translate( 'delete' ) . "
                            </a>";
				}*/
				$res['options'] = $action;
				$data[] = $res;
			}
			$result = array(
				'total' => $total,
				'rows'  => $data
			);
			echo json_encode( $result );
		} elseif ( $para1 == 'add' ) {
			$this->load->view( 'back/admin/supplier_add' );
                }elseif ($para1 == 'manage_store') {
                    $page_data = array();
                    if (empty($para2) || !is_numeric($para2)) {
                        if (isset($_SESSION['supplier_store'])) {
                            $para2 = $_SESSION['supplier_store'];
                        }
                    } else {
                        $_SESSION['supplier_store'] = $para2;
                    }
                    
                    $page_data['supplier_details'] = $this->db->get_where('supplier', array('supplier_id' => $para2))->result_array();
                    $page_data['supplier_store'] = $this->db->get_where('supplier_store', array(
                                'supplier_id' => $para2,
                            ))->result_array();
                    $this->load->view('back/admin/manage_supplier_stores', $page_data);

                } elseif ($para1 == 'change_store_status') {
                    $store_id = $para2;
                    if ($para3 == 'true') {
                        $data['status'] = 'Active';
                    } else {
                        $data['status'] = 'In-active';
                    }
                    $this->db->where('supplier_store_id', $store_id);
                    $this->db->update('supplier_store', $data);
                } elseif ($para1 == 'storeadd') {
                    $page_data['supplier_id'] = $para2;
                    $this->load->view('back/admin/store_add', $page_data);
                } elseif ($para1 == 'storeedit') {
                    $page_data['supplier_id'] = $para2;
                    //changed by sagar  : START
                    $page_data['supplier_store_data'] = $this->db->get_where('supplier_store',array('supplier_id'=>$para2,'supplier_store_id'=>$para3))->row_array();
                    //changed by sagar  : END
                    $this->load->view('back/admin/store_edit', $page_data);
                } elseif ($para1 == 'store_doadd') {
                    $check_if_unique_store_name = $this->crud_model->get_data('supplier_store', 'store_name =  ' . $this->db->escape($_POST['store_name']));
                    if (is_array($check_if_unique_store_name)) {
                        echo '<h5>Store name must be unique.</h5>';
                        exit;
                    }
                    $check_if_unique_store_number = $this->crud_model->get_data('supplier_store', 'store_number =  ' . $this->db->escape($_POST['store_number']));
                    if (is_array($check_if_unique_store_number)) {
                        echo '<h5>Store number must be unique.<h5>';
                        exit;
                    }

 		    $city_id= 0;
                    if(!empty($_POST['city'])){
                        $city_id = $_POST['city'];
                    }
                     $area_ids = "";
                    if(isset($_POST['area']) && !empty($_POST['area'])){
                        $area_ids  = implode(',',$_POST['area']);
                        $area_ids  = ','.$area_ids.',';
                    }
                    $data = array(
                        'store_name' => $_POST['store_name'],
                        'store_number' => $_POST['store_number'],
                        'supplier_id' => $para2,
                        'city_id'=>$city_id,
                        'area_ids'=>$area_ids,
                        'store_address' =>  $_POST['store_address'],
                        'created_on' => date('Y-m-d H:i:s'),
                        'created_by' => $this->session->userdata('admin_id'),
                    );
                    $this->db->insert('supplier_store', $data);
                    $store_id = $this->db->insert_id();
                    
                } elseif ($para1 == 'store_doedit') {
                    $check_if_unique_store_name = $this->crud_model->get_data('supplier_store', 'store_name =  ' . $this->db->escape($_POST['store_name']) . ' And supplier_store_id != ' . $this->db->escape($para3));
                    if (is_array($check_if_unique_store_name)) {
                        echo '<h5>Store name must be unique.<h5>';
                        exit;
                    }
                    $check_if_unique_store_number = $this->crud_model->get_data('supplier_store', 'store_number =  ' . $this->db->escape($_POST['store_number']) . ' And supplier_store_id != ' . $this->db->escape($para3));
                    if (is_array($check_if_unique_store_number)) {
                        echo '<h5>Store number must be unique.</h5>';
                        exit;
                    }
                    
                    $city_id= 0;
                    if(!empty($_POST['city'])){
                        $city_id = $_POST['city'];
                    }
                     $area_ids = "";
                    if(isset($_POST['area']) && !empty($_POST['area'])){
                        $area_ids  = implode(',',$_POST['area']);
                        $area_ids  = ','.$area_ids.',';
                    }               
                    $data = array(
                        'store_name' => $_POST['store_name'],
                        'store_number' => $_POST['store_number'],
                        'supplier_id' => $para2,
                        'store_address' =>  $_POST['store_address'],
                        'city_id'=>$city_id,
                        'area_ids'=>$area_ids,
                        'updated_on' => date('Y-m-d H:i:s'),
                        'updated_by' => $this->session->userdata('admin_id'),
                    );
                    $this->db->where('supplier_store_id', $para3);
                    $this->db->update('supplier_store', $data);
                } elseif ( $para1 == 'cityarea' ) {
                     echo $this->crud_model->select_html( 'area', 'area', 'area_name_en', 'add', 'demo-cs-multiselect', '', 'city_id', $para2, 'other' );    
		}else {
			$page_data['page_name']   = "supplier";
			$page_data['all_product'] = $this->db->get( 'supplier' )->result_array();
			$this->load->view( 'back/index', $page_data );
		}
	}

        function daySaleReport(){
            if ( ! $this->crud_model->admin_permission( 'day_sale_report' )  ) {
			redirect( base_url() . 'index.php/admin' );
            }
            $page_data['page_name'] = "report_day_sale";
            $this->load->view( 'back/index', $page_data );
        }

        function exportDaySale(){
            if ( ! $this->crud_model->admin_permission( 'day_sale_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $daterange              = date( 'm/d/Y' ) . ' - ' . date( 'm/d/Y' );
            if ( ! empty( $_POST['daterange'] ) ) {
                    $daterange = $_POST['daterange'];
            }
        
            $payment_status = $delivery_status = "";
            if(isset($_POST['payment_status'])){
                $payment_status = $_POST['payment_status'];
            }
            
            if(isset($_POST['delivery_status'])){
                $delivery_status = $_POST['delivery_status'];
            }
        
            $category_id= "";
            if(isset($_POST['category'])){
                $category_id = $_POST['category'];
            }
           
            $data = $this->crud_model->getDayRangeSalesData($daterange,$payment_status,$delivery_status,$category_id);
      
            $this->load->library('Excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("Mypcot Infotech")
            ->setLastModifiedBy($_SESSION['admin_name'])
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Doc")
            ->setDescription("")
            ->setKeywords("office 2007 ")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('50');

            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
      

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Sale Report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
          
            $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 16,
                            ));


            $date = explode( ' - ', $daterange );
            $start_time = strtotime($date[0]);
            $end_time = strtotime($date[1]);
            $Titleheading ='Trolley Sale Report ('.date('d/m/Y',$start_time);
            if(date('d/m/Y',$start_time)  != date('d/m/Y',$end_time)){
                $Titleheading .= ' - '.date('d/m/Y',$end_time);
            }
            $Titleheading .=')';

        //PRINTING HEADING ON EXCEL FILE   : START
            $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:S1")->getStyle("A1:S1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:S1")->getStyle("A1:S1")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );
          
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'SALE ID', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'SALE CODE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'USER DETAILS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'PRODUCT NAME_EN', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'PRODUCT NAME_AR', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'UNIT', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'PRODUCT CODE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'SKU_CODE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'CATEGORY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'PRODUCT PURCHASE QUANTITY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'DELIVERY STATUS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L2", 'PAYMENT STATUS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M2", 'SALE DATETIME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N2", 'CITY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("O2", 'AREA', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("P2", 'STORE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("Q2", 'BRAND', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("R2", 'UNIT PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("S2", 'TOTAL UNIT PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("T2", 'DELIVERY DATE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("U2", 'DELIVERY TIMESLOT', PHPExcel_Cell_DataType::TYPE_STRING);

            $objPHPExcel->getActiveSheet()->getStyle("A2:U2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A2:U2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A2:U2")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A2:U2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->getStyle("A2:U2")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                            );
                      
                                                                                                                                                                      
             if(is_array($data) && !empty($data) && count($data) > 0)
            {
                //PRINTING DATA ON SHEET
                $lastval=0;
                for($j=3,$k=0 ; $k<count($data); $k++){
                   
                    $sale_code = $data[$k]['sale_code'];
                    $sale_id = $data[$k]['sale_id'];
                    $date =  date('d M,Y h:i A',$data[$k]['sale_datetime']);
                    $order_status = $data[$k]['order_status'];
                    $userDetails = "Name : ".$data[$k]['first_name'];
                    //FROM SALES --
                    $shipping_address = json_decode($data[$k]['shipping_address'],true);
                    $userDetails .= PHP_EOL."Phone : ".$shipping_address['phone_number'];
                    $location = explode(',',$shipping_address['langlat']);
                    if(isset($location[0]) && isset($location[1]) && !empty($location[0]) && !empty($location[1])){
                    $userDetails .= PHP_EOL."Location : ".$location[1]. ','.$location[0];
                    }
                    //FROM SALES --
//                    $userDetails .= PHP_EOL."Phone : ".$data[$k]['phone'];
                    $delivery_status_array = json_decode($data[$k]['delivery_status'],true);
                    $payment_status_array = json_decode($data[$k]['payment_status'],true);
                    $delivery_status = $delivery_status_array[0]['status'];
                    $payment_status = $payment_status_array[0]['status'];
                    
                    
                    $delivery_date_timeslot = json_decode($data[$k]['delivery_date_timeslot'],true);
                    $delivery_date =  (isset($delivery_date_timeslot[0]['date'])) ? $delivery_date_timeslot[0]['date'] : "";
                    $delivery_timeslot =  (isset($delivery_date_timeslot[0]['timeslot'])) ? $delivery_date_timeslot[0]['timeslot'] : "";
                    
                    if($order_status == 'cancelled'){
                        $delivery_status = 'cancelled';
                        $payment_status = 'cancelled';
                    }

                    $product_details = json_decode($data[$k]['product_details'],true);
                    $assign_stores_data = json_decode($data[$k]['assign_stores_data'],true);
                    $prdt_count = count($product_details);
                    //CONVERSION RATE FROM SALE ENTRY 
                    $user_choice = json_decode($data[$k]['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                    //CONVERSION RATE FROM SALE ENTRY 
                    
                    $product_details = array_values($product_details);;
                    foreach ($product_details as $Pkey => $Pvalue){
                        $qty = $Pvalue['qty'];
                        $name = $Pvalue['name'];
                        $name_ar = $Pvalue['name_ar'];
                        $brand = $Pvalue['brand'];
                        $unit = $Pvalue['weight'];
                        
                        $unitPrice = get_converted_currency($Pvalue['price'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                        $color ='';
                        $options=json_decode($Pvalue['option'],true);
                        if(is_array($options['color']) && !empty($options['color']['title']) && !empty($options['color']['value'])){
                            $color = 'color : '.$options['color']['title'] . ' | ';
                        }
                        if($Pvalue['product_type'] == 'variation'){
                            array_shift($options);
                            foreach($options as $key => $val){
                                if(!empty($val['title']) && !empty($val['value'])){
                                    $color .= $val['title'].' : '. $val['value'] . ' | ';
                                }
                            }
                        }
                       
                        $color= rtrim($color, ' | ' );
                        if($color != ''){
                            $name = $name .' - '.$color;
                        }
                        
                        $category_name =  $this->crud_model->get_type_name_by_id('category', $Pvalue['category'], 'category_name');
                        $sku_code = $this->crud_model->get_type_name_by_id('variation', $Pvalue['variation_id'], 'sku_code');
                        $product_code = $this->crud_model->get_type_name_by_id('product', $Pvalue['product_id'], 'product_code');
                        $city = $shipping_address['city'];
                        $area = $shipping_address['area'];
                        $store_name = "";
                        if(is_array($assign_stores_data)){
                            $store_id = $assign_stores_data[$Pkey]['supplier_store_id'];
                            $store_name = $this->crud_model->get_type_name_by_id('supplier_store', $store_id, 'store_name');
                        }
                        
                        $totalunitPrice = $Pvalue['price'] * $qty ; 
                        $totalunitPrice = get_converted_currency($totalunitPrice,DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                        
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$sale_id);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$sale_code);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",$userDetails);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j", $name);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j", $name_ar);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j", $unit);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j", $product_code);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j", $sku_code);
                        $objPHPExcel->getActiveSheet()->getStyle("C$j")->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j",$category_name);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j",$qty);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j",$delivery_status);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j",$payment_status);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j",$date);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j",$city);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("O$j",$area);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("P$j",$store_name);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("Q$j",$brand);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("R$j",$unitPrice);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("S$j",$totalunitPrice);
			$objPHPExcel->getActiveSheet()->setCellValueExplicit("T$j",$delivery_date);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("U$j",$delivery_timeslot);
                        $j++;
                        $lastval=$j;
                    }
                }
            }
            else{
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("D3", 'NO DATA FOUND');
            }
          
                 

            $file_name = 'Trolley Sale Report';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
        
        function supplierSaleReport($para1='',$para2=''){
            if ( ! $this->crud_model->admin_permission( 'supplier_sale_report' )  ) {
			redirect( base_url() . 'index.php/admin' );
            }
            if ( $para1 == 'var_by_pro' ) {
                echo $this->crud_model->select_html( 'variation', 'variation', 'sku_code|title', 'add', 'demo-chosen-select required', '', 'product_id', $para2, 'other' );
                
            }elseif ( $para1 == 'supplier_product' ) {
                echo $this->crud_model->select_html('product','product','title','add','demo-chosen-select required','','supplier',$para2,'get_variations');
            }else{
                
                $page_data['page_name'] = "report_supplier_sale";
                $page_data['suppliers'] =  $this->db->get_where('supplier',array())->result_array();
                $this->load->view( 'back/index', $page_data );
            }
        }
        
        function exportSupplierDaySale(){
            if ( ! $this->crud_model->admin_permission( 'supplier_sale_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $daterange              = date( 'm/d/Y' ) . ' - ' . date( 'm/d/Y' );
            if ( ! empty( $_POST['daterange'] ) ) {
                    $daterange = $_POST['daterange'];
            }
            $supplier_id = $product_id = $variation_id = "";
            if(empty($_POST['supplier'])){
                echo ' Please select supplier.<br><br>';
                echo '<a href="'. base_url().'admin/supplierSaleReport">Go Back</a>';
                exit;
            }
            $supplier_id= $_POST['supplier'];
            
            if(!empty($_POST['product'])){
                $product_id = $_POST['product'];
            }
            if(!empty($_POST['variation'])){
                $variation_id = $_POST['variation'];
            }
           
            $data = $this->crud_model->getDayRangeSupplierSalesData($daterange,$supplier_id,$product_id,$variation_id);
           
            $this->load->library('Excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("Mypcot Infotech")
            ->setLastModifiedBy($_SESSION['admin_name'])
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Doc")
            ->setDescription("")
            ->setKeywords("office 2007 ")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('50');

            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            
      

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Supplier Sale Report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
          
            $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 16,
                            ));


            $date = explode( ' - ', $daterange );
            $start_time = strtotime($date[0]);
            $end_time = strtotime($date[1]);
            $Titleheading ='Trolley Supplier Sale Report ('.date('d/m/Y',$start_time);
            if(date('d/m/Y',$start_time)  != date('d/m/Y',$end_time)){
                $Titleheading .= ' - '.date('d/m/Y',$end_time);
            }
            $Titleheading .=')';

        //PRINTING HEADING ON EXCEL FILE   : START
            $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:N1")->getStyle("A1:N1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:N1")->getStyle("A1:N1")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );
          
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'SALE ID', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'SALE CODE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'PRODUCT NAME (EN)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'PRODUCT NAME (AR)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'UNIT', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'PRODUCT CODE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'SKU CODE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'STORE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'PRODUCT PURCHASE QUANTITY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'SUPPLIER NAME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'SUPPLIER SALLING PRICE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L2", 'SUPPLIER AMOUNT (TOTAL UNIT PRICE)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M2", 'PAYMENT STATUS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N2", 'SALE DATETIME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                            );
                      
                                                                                                                                                                      
             if(is_array($data) && !empty($data) && count($data) > 0)
            {
                //PRINTING DATA ON SHEET
                $lastval=0;
                $total_supplier_amount = 0;
                for($j=3,$k=0 ; $k<count($data); $k++){
                    $sale_code = $data[$k]['sale_code'];
                    $sale_id = $data[$k]['sale_id'];
//                    $date =  date('d M,Y h:i A',$data[$k]['sale_datetime']);
                    $date =  date('Y-m-d',$data[$k]['sale_datetime']);
                    $userDetails = "Name : ".$data[$k]['username']." ".$data[$k]['surname'];
                    $userDetails .= PHP_EOL."Phone : ".$data[$k]['phone'];
                    $delivery_status_array = json_decode($data[$k]['delivery_status'],true);
                    $payment_status_array = json_decode($data[$k]['payment_status'],true);
                    $delivery_status = $delivery_status_array[0]['status'];
                    $payment_status = $payment_status_array[0]['status'];

                    //CONVERSION RATE FROM SALE ENTRY 
                    //$user_choice = json_decode($data[$k]['user_choice'], true);
                    //$sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                    //CONVERSION RATE FROM SALE ENTRY 

                    $product_details = json_decode($data[$k]['product_details'],true);
                    $prdt_count = count($product_details);
                    $supplier_ids = explode(',',$data[$k]['supplier_ids']);
                    $supplier_store_ids = explode(',',$data[$k]['supplier_store_ids']);
                
                    foreach ($product_details as $Pkey => $Pvalue){
                    
                        if(!empty($supplier_id ) && $supplier_id  != $Pvalue['supplier']){
                            continue;
                        }
                        if(!empty($product_id ) && $product_id  != $Pvalue['product_id']){
                            continue;
                        }
                        
                        if(!empty($variation_id ) && $variation_id  != $Pvalue['variation_id']){
                            continue;
                        }
                        
                        $qty = $Pvalue['qty'];
                        $name = $Pvalue['name'];
                        $name_ar = $Pvalue['name_ar'];
                        
                        $color ='';
                        $options=json_decode($Pvalue['option'],true);
                        if(is_array($options['color']) && !empty($options['color']['title']) && !empty($options['color']['value'])){
                            $color = 'color : '.$options['color']['title'] . ' | ';
                        }
                        if($Pvalue['product_type'] == 'variation'){
                            array_shift($options);
                            foreach($options as $key => $val){
                                if(!empty($val['title']) && !empty($val['value'])){
                                    $color .= $val['title'].' : '. $val['value'] . ' | ';
                                }
                            }
                        }
                        
                        $color= rtrim($color, ' | ' );
                        if($color != ''){
                            $name = $name .' - '.$color;
                        }
                        
                        $supplier_id = $Pvalue['supplier'];
                        $supplier_name =  $this->crud_model->get_type_name_by_id('supplier', $supplier_id, 'supplier_name');
                        $supplier_price =  round($Pvalue['supplier_price'],3);
                        $total_supplier_amount =  round($qty * $supplier_price,3);
                        $final_total_supplier_amount += round($total_supplier_amount,3);
                        
                        $storeIdKey =  array_search($Pvalue['supplier'],$supplier_ids);
                        $store_id = 0;
                        $store_name = "";
                        if(is_numeric($storeIdKey) && isset($supplier_store_ids[$storeIdKey]) && !empty($supplier_store_ids[$storeIdKey])){
                            $store_id = $supplier_store_ids[$storeIdKey];
                            $store_name =  $this->crud_model->get_type_name_by_id('supplier_store', $store_id, 'store_name');
                        }
                        $unit = $Pvalue['weight'];
                        $product_code =  $this->crud_model->get_type_name_by_id('product', $Pvalue['product_id'], 'product_code');
                        $sku_code =  $this->crud_model->get_type_name_by_id('variation', $Pvalue['variation_id'], 'sku_code');
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$sale_id);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$sale_code);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",$name);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j",$name_ar);
                        $objPHPExcel->getActiveSheet()->getStyle("C$j")->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getStyle("D$j")->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",$unit);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j",$product_code);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j",$sku_code);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j",$store_name);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j",$qty);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j",$supplier_name);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j",$supplier_price);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j",$total_supplier_amount);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j",$payment_status);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j",$date);
                        $j++;
                        $lastval=$j;
                    }
                }
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$lastval",'Total Amount To be Paid');
                $objPHPExcel->getActiveSheet()->getStyle("K$lastval")->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$lastval",$final_total_supplier_amount);
            }
            else{
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("D3", 'NO DATA FOUND');
            }
          
                 

            $file_name = 'Trolley Supplier Sale Report';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
        
        function stockReport($para1='',$para2=''){
            if ( ! $this->crud_model->admin_permission( 'product_stock_report' )  ) {
			redirect( base_url() . 'index.php/admin' );
            }
            if ( $para1 == 'var_by_pro' ) {
                echo $this->crud_model->select_html( 'variation', 'variation', 'sku_code|title', 'add', 'demo-chosen-select required', '', 'product_id', $para2, 'other' );
            }else{
                $page_data['page_name'] = "report_for_stock";
                $this->load->view( 'back/index', $page_data );
            }
        }
        
        function exportStockReport(){
            if ( ! $this->crud_model->admin_permission( 'product_stock_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $product_id = "";
            $variation_id = "";
            if(!empty($_POST['product'])){
                $product_id = $_POST['product'];
            }
            if(!empty($_POST['variation'])){
                $variation_id = $_POST['variation'];
            }
            $data = $this->crud_model->getStockReportData($product_id,$variation_id);
            
            $this->load->library('Excel');
          
            //Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            //Set properties
            $objPHPExcel->getProperties()->setCreator("MypcotInfotech")
            ->setLastModifiedBy("admin")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX product List Doc")
            ->setDescription("Admin panel product stock details")
            ->setKeywords("office 2007 mypcot trolley php")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');
            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('30');

            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);


            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Product Stock Report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A1", 'UniqueNo', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B1", 'Product Name (EN)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C1", 'Product Name (AR)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D1", 'Unit', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E1", 'Product Code', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F1", 'SKU Code', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G1", 'Category Name', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H1", 'Supplier Price ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I1", 'App Sale Price ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J1", 'Supplier Price ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K1", 'App Sale Price ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L1", 'In Stock', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M1", 'Total Sold', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N1", 'Total Stock', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("O1", 'Total In Stock Value ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("P1", 'Total In Stock Value ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle("A1:P1")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A1:P1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A1:P1")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A1:P1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//            $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
            //$objPHPExcel->getActiveSheet()->getStyle('A1:C20')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
            $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
             if(is_array($data) && !empty($data) && count($data) > 0)
               {
                    for($j=2,$k=0 ; $k<count($data); $k++){
                        $product_variation_title = $data[$k]['title'];
                        if(!empty($data[$k]['variation_title'])){
                            $product_variation_title .= "(".$data[$k]['variation_title'].")";
                    }
                        $product_variation_title_ar = $data[$k]['title_ar'];
                        
                        $category_name = $data[$k]['category_name'];
                        $total_value = $data[$k]['current_stock'] + $data[$k]['sold_count'];
//                        $total_stock_value = ($total_value * $data[$k]['variation_price']);
                        $total_In_stock_value = ($data[$k]['current_stock'] * $data[$k]['variation_price']);
                        $currency_code = DEFAULT_CURRENCY;
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j", $data[$k]['variation_id'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j", $product_variation_title );
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j", $product_variation_title_ar );
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j", $data[$k]['weight'] );
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j", $data[$k]['product_code'] );
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j", $data[$k]['sku_code'] );
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j", $category_name );
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j", $data[$k]['variation_supplier_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j", $data[$k]['variation_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j", get_converted_currency($data[$k]['variation_supplier_price'],$currency_code),PHPExcel_Cell_DataType::TYPE_NUMERIC);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j", get_converted_currency($data[$k]['variation_price'],$currency_code),PHPExcel_Cell_DataType::TYPE_NUMERIC);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j", $data[$k]['current_stock'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j", $data[$k]['sold_count'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j", $total_value,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("O$j", $total_In_stock_value,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("P$j", get_converted_currency($total_In_stock_value,$currency_code),PHPExcel_Cell_DataType::TYPE_NUMERIC);
                       $j++;
                    }

               }
            // Redirect output to a client's web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="trolleystockreport.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            
        }
        
        function timeslots($para1 = '', $para2 = '', $para3  = '') {
            if ( ! $this->crud_model->admin_permission( 'timeslots' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            if ($para1 == 'do_add') {
                $data = array();
        
                if (!isset($_POST['days'])) {
                    echo '<h5> Please Check At least one day from list.</h5>';
                    exit;
                }
                if (!isset($_POST['start_time'])) {
                    echo '<h5> Please Provide Start Time </h5>';
                    exit;
                }

                if (isset($_POST['start_time']) && is_array($_POST['start_time'])) {
                    foreach ($_POST['days'] as $dk => $dv) {
                        foreach ($_POST['start_time'] as $key => $val) {
                            $data['day'] = $dv;
                            if (isset($key) && $val != null) {
                                $data['start_time'] = $val;
                            } else {
                                echo '<h5>Please provide start time</h5>';
                                exit;
                            }

                            if (isset($_POST['end_time'][$key]) && $_POST['end_time'][$key] != null) {
                                $data['end_time'] = $_POST['end_time'][$key];
                            } else {
                                echo '<h5>Please provide end time</h5>';
                                exit;
                            }
                            
                            if (isset($_POST['order_limit'][$key]) && $_POST['order_limit'][$key] != null) {
                                $data['order_limit'] = $_POST['order_limit'][$key];
                            } else {
                                echo '<h5>Please provide order limit for timeslot</h5>';
                                exit;
                            }
                            $data['created_by'] = $_SESSION['admin_id'];
    //                                $data_array[] = $data; 
                            $this->db->insert('timeslots', $data);
                        }
                    }
                }
            } else if ($para1 == 'edit') {
                $data = array();
                $data['timeslots_id'] = $para2;
                $this->load->view( 'back/admin/timeslots_edit',$data );
            } elseif ($para1 == "update") {
                $data = array();
                $data['start_time'] = $_POST['start_time'];
                $data['end_time'] = $_POST['end_time'];
                $data['order_limit'] = $_POST['order_limit'];

                $is_same_time_for_day_exist = $this->crud_model->verify_if_unique('timeslots', 'start_time = ' . $this->db->escape($data['start_time']) . 
                                                                ' AND end_time = ' . $this->db->escape($data['end_time']) . 
                                                                ' AND day = ' . $this->db->escape($_POST['day']) . 
                                                                ' And timeslots_id!=' . $this->db->escape($_POST['timeslots_id']));
                if (is_array($is_same_time_for_day_exist)) {
                    echo "<h5>Timeslot entry already exist for same day.<h5>";
                    exit;
                }

                $data['updated_by'] = $_SESSION['admin_id'];
                $data['updated_on'] = date('Y-m-d H:i:s');
                $this->db->where('timeslots_id', $_POST['timeslots_id']);
                $this->db->update('timeslots', $data);
            } elseif ( $para1 == 'timeslots_publish' ) {
                $timeslots = $para2;
                $data = array();
                if ( $para3 == 'true' ) {
                        $data['status'] = 'ok';
                } else {
                        $data['status'] = '0';
                }
                $this->db->where( 'timeslots_id', $timeslots );
                $this->db->update( 'timeslots', $data );
            }elseif ($para1 == 'delete') {
                $this->db->where('timeslots_id', $para2);
                $this->db->delete('timeslots');
                $this->crud_model->set_category_data(0);
                recache();
            } elseif ($para1 == 'list') {
                $page_data = array();
                $this->load->view('back/admin/timeslots_list', $page_data);
            } elseif ($para1 == 'add') {
                $this->load->view('back/admin/timeslots_add');
            } else {
                $page_data['page_name'] = "timeslots";
                $page_data['all_timeslots'] = $this->db->get('timeslots')->result_array();
                $this->load->view('back/index', $page_data);
            }
        }
    
        function city($para1 = '', $para2 = '', $para3 = '')
        {
            if ( ! $this->crud_model->admin_permission( 'city' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            if ($para1 == 'do_add') {
                $data['city_name_en'] = $this->input->post('city_name_en');
                $data['city_name_ar'] = $this->input->post('city_name_ar');

                $is_city_name_en_unique = $this->crud_model->verify_if_unique('city', 'city_name_en = ' . $this->db->escape($data['city_name_en']));
                
		if (is_array($is_city_name_en_unique)) {
                    echo "<h5>City Name in english already exist.<h5>";
                    exit;
                }
                $is_city_name_ar_unique = $this->crud_model->verify_if_unique('city', 'city_name_ar = ' . $this->db->escape($data['city_name_ar']));
                if (is_array($is_city_name_ar_unique)) {
                    echo "<h5>City Name in arabic already exist.<h5>";
                    exit;
                }
                $data['status'] = 'ok';
                $data['created_by'] = $_SESSION['admin_id'];
                $data['created_on'] = date('Y-m-d H:i:s');
                $this->db->insert('city', $data);

            } else if ($para1 == 'edit') {
                $page_data['city_data'] = $this->db->get_where('city', array(
                    'city_id' => $para2
                ))->result_array();
                $this->load->view('back/admin/city_edit', $page_data);
            } elseif ($para1 == "update") {
                $data['city_name_en'] = $this->input->post('city_name_en');
                $data['city_name_ar'] = $this->input->post('city_name_ar');

                $is_city_name_en_unique = $this->crud_model->verify_if_unique('city', 'city_name_en = ' . $this->db->escape($data['city_name_en']) .' And city_id !=' . $this->db->escape($para2));
                if (is_array($is_city_name_en_unique)) {
                    echo "<h5>City name in english already exist.<h5>";
                    exit;
                }
                $is_city_name_ar_unique = $this->crud_model->verify_if_unique('city', 'city_name_ar = ' . $this->db->escape($data['city_name_ar']) .' And city_id !=' . $this->db->escape($para2));
                if (is_array($is_city_name_ar_unique)) {
                    echo "<h5>City name in arabic already exist.<h5>";
                    exit;
                }

                $data['updated_by'] = $_SESSION['admin_id'];
                $data['updated_on'] = date('Y-m-d H:i:s');
                $this->db->where('city_id', $para2);
                $this->db->update('city', $data);

            } elseif ($para1 == 'list') {
                $this->db->order_by('city_id', 'desc');
                $page_data['all_data'] = $this->db->get('city')->result_array();
                $this->load->view('back/admin/city_list', $page_data);
            } elseif ($para1 == 'add') {
                $this->load->view('back/admin/city_add');
            } elseif ($para1 == 'publish_set') {
                $city_id = $para2;
                if ($para3 == 'true') {
                    $data['status'] = 'ok';
                } else {
                    $data['status'] = '0';
                }
                $this->db->where('city_id', $city_id);
                $this->db->update('city', $data);
            }elseif($para1 == 'city_report'){ 
                 $page_data['page_name'] = "excel_for_city";
                 $this->load->view( 'back/index', $page_data );
                
            }else {
                $page_data['page_name']      = "city";
                $page_data['all_data'] = $this->db->get('city')->result_array();
                $this->load->view('back/index', $page_data);
            }
        }

        function area($para1 = '', $para2 = '', $para3 = '')
        {
            if ( ! $this->crud_model->admin_permission( 'area' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            if ($para1 == 'do_add') {
                $data['area_name_en'] = $this->input->post('area_name_en');
                $data['area_name_ar'] = $this->input->post('area_name_ar');
                $is_area_name_en_unique = $this->crud_model->verify_if_unique('area', 'area_name_en = ' . $this->db->escape($data['area_name_en']));
                if (is_array($is_area_name_en_unique)) {
                    echo "<h5>Area Name in english already exist.<h5>";
                    exit;
                }
                $is_area_name_ar_unique = $this->crud_model->verify_if_unique('area', 'area_name_ar = ' . $this->db->escape($data['area_name_ar']));
                if (is_array($is_area_name_ar_unique)) {
                    echo "<h5>Area Name in arabic already exist.<h5>";
                    exit;
                }
                $data['delivery_charge'] =  $this->input->post('delivery_charge');
//                if($data['delivery_charge'] > 99 ){
//                    echo '<h5>Delivery charge should not be greater than 99<h5>';
//                    exit;
//                }
                $data['city_id'] =  $this->input->post('city_id');
                $data['status'] = 'ok';
                $data['created_by'] = $_SESSION['admin_id'];
                $data['created_on'] = date('Y-m-d H:i:s');
                $this->db->insert('area', $data);

            } else if ($para1 == 'edit') {
                $page_data['area_data'] = $this->db->get_where('area', array(
                    'area_id' => $para2
                ))->result_array();
                $this->load->view('back/admin/area_edit', $page_data);
            } elseif ($para1 == "update") {
                $data['area_name_en'] = $this->input->post('area_name_en');
                $data['area_name_ar'] = $this->input->post('area_name_ar');

                $is_area_name_en_unique = $this->crud_model->verify_if_unique('area', 'area_name_en = ' . $this->db->escape($data['area_name_en']) .' And area_id !=' . $this->db->escape($para2));
                if (is_array($is_area_name_en_unique)) {
                    echo "<h5>Area name in english already exist.<h5>";
                    exit;
                }
                $is_area_name_ar_unique = $this->crud_model->verify_if_unique('area', 'area_name_ar = ' . $this->db->escape($data['area_name_ar']) .' And area_id !=' . $this->db->escape($para2));
                if (is_array($is_area_name_ar_unique)) {
                    echo "<h5>Area name in arabic already exist.<h5>";
                    exit;
                }
                $data['delivery_charge'] =  $this->input->post('delivery_charge');
//                if($data['delivery_charge'] > 99 ){
//                    echo '<h5>Delivery charge should not be greater than 99<h5>';
//                    exit;
//                }
                $data['city_id'] =  $this->input->post('city_id');
                
                $data['updated_by'] = $_SESSION['admin_id'];
                $data['updated_on'] = date('Y-m-d H:i:s');
                $this->db->where('area_id', $para2);
                $this->db->update('area', $data);

            } elseif ($para1 == 'list') {
                $this->db->order_by('area_id', 'desc');
                $page_data['all_data'] = $this->db->get('area')->result_array();
                $this->load->view('back/admin/area_list', $page_data);
            } elseif ($para1 == 'add') {
                $this->load->view('back/admin/area_add');
            } elseif ($para1 == 'publish_set') {
                $area_id = $para2;
                if ($para3 == 'true') {
                    $data['status'] = 'ok';
                } else {
                    $data['status'] = '0';
                }
                $this->db->where('area_id', $area_id);
                $this->db->update('area', $data);
            } else {
                $page_data['page_name']      = "area";
                $page_data['all_data'] = $this->db->get('area')->result_array();
                $this->load->view('back/index', $page_data);
            }
        }

        function importTrolleyBalance( $para1 = '', $para2 = '' ) {
            if ( ! $this->crud_model->admin_permission( 'user_add_wallet_balance' ) &&  ! $this->crud_model->admin_permission( 'user_reduce_wallet_balance' ) ) {
                    redirect( base_url() . 'index.php/admin' );
            }
            if($para1 == 'download'){
                 $data = $this->crud_model->getUserWalletDetails();
                 $this->load->library('Excel');
                 //Create new PHPExcel object
                 $objPHPExcel = new PHPExcel();
                 //Set properties
                 $objPHPExcel->getProperties()->setCreator("MypcotInfotech")
                 ->setLastModifiedBy("admin")
                 ->setTitle("Office 2007 XLSX Document")
                 ->setSubject("Office 2007 XLSX product List Doc")
                 ->setDescription("Admin panel user wallet details")
                 ->setKeywords("office 2007 mypcot trolley php")
                 ->setCategory("Export Excel");

                 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true); 
                 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');

                 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);


                 // Rename sheet
                 $objPHPExcel->getActiveSheet()->setTitle('Trolley_wallet_details');
                 // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                 $objPHPExcel->setActiveSheetIndex(0);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("A1", 'UniqueNo', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("B1", 'Wallet No', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("C1", 'Wallet Balance', PHPExcel_Cell_DataType::TYPE_STRING);
                 $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getFont()->setBold(true);
                 $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                 $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getFill()->getStartColor()->setRGB('FFFF00');
                 $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                 //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
                 //$objPHPExcel->getActiveSheet()->getStyle('A1:C20')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
                 $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                  if(is_array($data) && !empty($data) && count($data) > 0)
                    {
                         for($j=2,$k=0 ; $k<count($data); $k++){
                             $user_id = $data[$k]['user_id'];
                             $wallet_no = $data[$k]['wallet_no'];
                             $wallet_balance = $data[$k]['wallet_balance'];

                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j", $user_id,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j", $wallet_no );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j", $wallet_balance ,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            $j++;
                         }

                    }
                 // Redirect output to a client's web browser (Excel5)
                 header('Content-Type: application/vnd.ms-excel');
                 header('Content-Disposition: attachment;filename="TrolleyWalletDetails.xls"');
                 header('Cache-Control: max-age=0');
                 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                 $objWriter->save('php://output');
           }else if($para1 == 'saveTrolleyBalance'){
                ini_set('memory_limit', '-1');
                if(isset($_FILES['importproductexcel']['name']) && !empty($_FILES['importproductexcel']['name']))
                {
                        $name = $_FILES['importproductexcel']['name'];
                        $names = explode(".", $name);
                        $size = $_FILES['importproductexcel']['size'];
                        $max_file_size = 1024*1024*2;		// 2 MB

                        if ((end($names)=="xls" || end($names)=="XLS" || end($names) == "csv"  || end($names) == "CSV"))
                        {
                                //Load the excel library
                                $this->load->library('excel');

                                //Read file from path
                                $objPHPExcel = PHPExcel_IOFactory::load($_FILES['importproductexcel']['tmp_name']);

                                //Get only the Cell Collection
                                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,false,true);
                                if(is_array($sheetData)){
                                    if(!( isset($sheetData[1]['A']) &&$sheetData[1]['A']=='UniqueNo' 
                                            && isset($sheetData[1]['B']) && $sheetData[1]['B']=='Wallet No'
                                            && isset($sheetData[1]['C']) && $sheetData[1]['C']=='Wallet Balance'
                                            )){

                                                 echo ' Unsupported file format.<br> Correct Order is :: UniqueNo,Wallet No,Wallet Balance<br>';
                                                 echo '<a href="'. base_url().'admin/importTrolleyBalance">Go Back</a>';
                                                 exit;
                                            }
                                }else{
                                    echo ' Unsupported file format only XLS / CSV files allowed.<br><br>';
                                     echo '<a href="'. base_url().'admin/importTrolleyBalance">Go Back</a>';
                                    exit;
                                }

                                for($i = 2; $i < count($sheetData)+1; $i++)
                                {
                                    if (!is_numeric($sheetData[$i]['C'])) {
                                        echo ' ERROR On Row ' . $i . ' : Invalid wallet balance provided.<br>';
                                        continue;
                                    }
                                    $user_id = $sheetData[$i]['A'];
                                    $db_wallet_balance =  $this->db->get_where('user',array('user_id'=>$user_id))->row()->wallet_balance;
                                    $wallet_no = $sheetData[$i]['B'];
                                    $import_wallet_balance = $sheetData[$i]['C'];
                                    if($import_wallet_balance <= 0){
                                        continue;
                                    }
                                    
                                    $wallet_balance = $db_wallet_balance + $import_wallet_balance;
                                    $data_array['wallet_balance']=$wallet_balance;
                                    $this->db->where('user_id', $user_id);
                                    $result = $this->db->update('user', $data_array);
                                    if($result){
                                        $wallet_data =  array(
                                            'user_id'=>$user_id,
                                            'amount'=>$import_wallet_balance,
                                            'type'=>'credit',
                                            'reason' =>'added by trolley admin',
                                            'date_time'=>date('Y-m-d H:i:s'),
                                            'wallet_balance'=>$wallet_balance,
                                            'admin_id'=>$this->session->userdata('admin_id'),
                                        );
                                        $this->db->insert( 'wallet', $wallet_data );
                                    }
                                    echo 'Row '.$i.' is Wallet Balance Updated Successfully.<br>';
                                }
                                echo '<a href="'. base_url().'admin/importTrolleyBalance">Go Back</a>';
                        }
                        else 
                        {
                                echo ' Unsupported file format.<br><br>';
                                echo '<a href="'. base_url().'admin/importTrolleyBalance">Go Back</a>';
                                exit;
                        }
                }
                else{
                        echo ' Please Select File.<br><br>';
                        echo '<a href="'. base_url().'admin/importTrolleyBalance">Go Back</a>';
                        exit;
                }
                exit;

           }else{
            $page_data['page_name'] = "excel_for_trolley_wallet";
            $this->load->view( 'back/index', $page_data );
           }
        }
        
        function customerReport($para1='',$para2=''){
            if ( ! $this->crud_model->admin_permission( 'customer_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $page_data['page_name'] = "report_for_customer";
            $this->load->view( 'back/index', $page_data );
        }
    
        function exportCustomerReport(){
            $user_type = "";
            $user_status = "";
            if(!empty($_POST['user_type'])){
                $user_type = $_POST['user_type'];
            }
            if(!empty($_POST['user_status'])){
                $user_status = $_POST['user_status'];
            }
            $data = $this->crud_model->getCustomerReportData($user_type,$user_status);
            $this->load->library('Excel');

            //Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            //Set properties
            $objPHPExcel->getProperties()->setCreator("MypcotInfotech")
            ->setLastModifiedBy("admin")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX customer List Doc")
            ->setDescription("Admin panel customer details")
            ->setKeywords("office 2007 mypcot trolley php")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Customer List Report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A1", 'Name', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B1", 'Email', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C1", 'Phone No', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D1", 'Sex', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E1", 'City', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F1", 'Area', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G1", 'Job Type', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H1", 'Social Status', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I1", 'Address Location', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J1", 'Wallet Number', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K1", 'Current Wallet Balance', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L1", 'Wallet Type', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M1", 'Status', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
            //$objPHPExcel->getActiveSheet()->getStyle('A1:C20')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
            $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
             if(is_array($data) && !empty($data) && count($data) > 0)
               {
                    for($j=2,$k=0 ; $k<count($data); $k++){
                       $username =  $data[$k]['first_name'];
                       if(!empty($data[$k]['fourth_name'])){
                           $username .= ' '.$data[$k]['fourth_name'];
                       }
                       $phone_no = '249'.$data[$k]['phone'];
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$username);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j", $data[$k]['email'] );
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j", $phone_no);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j", $data[$k]['sex']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j", $data[$k]['city_name_en']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j", $data[$k]['area_name_en']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j", $data[$k]['job_type']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j", $data[$k]['social_status']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j", $data[$k]['langlat']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j", $data[$k]['wallet_no']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j", $data[$k]['wallet_balance']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j", $data[$k]['wallet_type']);
		       $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j", $data[$k]['status']);
                       $j++;
                    }

               }
            // Redirect output to a client's web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="trolley_CustomerReport.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');

        }
        
        function deliveryReport(){
            if ( ! $this->crud_model->admin_permission( 'delivery_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $page_data['page_name'] = "report_for_delivery";
            $this->load->view( 'back/index', $page_data );
        }
        
        function exportDeliveryReport(){
            if ( ! $this->crud_model->admin_permission( 'delivery_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $delivery_date = date('Y-m-d');
            if (!empty($_POST['delivery_date'] ) ) {
                  $delivery_date = $_POST['delivery_date'];
            }
        
            $payment_status = "";
            if(isset($_POST['payment_status'])){
                $payment_status = $_POST['payment_status'];
            }
            $data = $this->crud_model->getDeliverySalesData($delivery_date,$payment_status);
          
            $this->load->library('Excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("Mypcot Infotech")
            ->setLastModifiedBy($_SESSION['admin_name'])
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Doc")
            ->setDescription("trolley_delivery_report")
            ->setKeywords("office 2007 ")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('50');

            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            
	    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
            
      

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Delivery Report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
          
            $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 16,
                            ));

            $Titleheading ='Trolley Delivery Report ('.$delivery_date.')';

        //PRINTING HEADING ON EXCEL FILE   : START
            $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:Q1")->getStyle("A1:Q1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:Q1")->getStyle("A1:Q1")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );
          
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'SALE ID', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'SALE CODE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'USER DETAILS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'No.OF ITEMS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'CITY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'AREA', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'SUPPLIER', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'STORE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'DELIVERY BOY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'DELIVERY DATE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'DELIVERY TIMESLOT', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L2", 'PAYMENT STATUS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M2", 'SALE DATETIME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N2", 'GRAND TOTAL(SDG)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("O2", 'PAYMENT TYPE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("P2", 'PAYMENT CURRENCY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("Q2", 'DELIVERY FEES (SDG)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle("A2:Q2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A2:Q2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A2:Q2")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A2:Q2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->getStyle("A2:Q2")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                            );                                                                                                                                                      
             if(is_array($data) && !empty($data) && count($data) > 0)
            {
                //PRINTING DATA ON SHEET
                $lastval=0;
                for($j=3,$k=0 ; $k<count($data); $k++){
                    $sale_code = $data[$k]['sale_code'];
                    $sale_id = $data[$k]['sale_id'];
                    //CONVERSION RATE FROM SALE ENTRY 
                    $user_choice = json_decode($data[$k]['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                    $payment_currency_code =  $user_choice[0]['currency_code'];
                    $payment_currency =  ($payment_currency_code == '1') ? 'EGP' : 'USD';
                    //CONVERSION RATE FROM SALE ENTRY 
                    $payment_type = $data[$k]['payment_type'];
                    
                    $grand_total = get_converted_currency($data[$k]['grand_total'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                    $delivery_fees_in_doller =  $data[$k]['delivery_charge'];
                    $delivery_fees_in_sdg = get_converted_currency($delivery_fees_in_doller,DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                    
                    $date =  date('d M,Y h:i A',$data[$k]['sale_datetime']);
                    $userDetails = "Name : ".$data[$k]['first_name'];
                    //FROM SALES --
                    $shipping_address = json_decode($data[$k]['shipping_address'],true);
                    $userDetails .= PHP_EOL."Phone : ".$shipping_address['phone_number'];
                    $location = explode(',',$shipping_address['langlat']);
                    if(isset($location[0]) && isset($location[1]) && !empty($location[0]) && !empty($location[1])){
                    $userDetails .= PHP_EOL."Location : ".$location[1]. ','.$location[0];
                    }
                    $city = $shipping_address['city'];
                    $area = $shipping_address['area'];
                    //FROM SALES --
                    
                    $delivery_status_array = json_decode($data[$k]['delivery_status'],true);
                    $payment_status_array = json_decode($data[$k]['payment_status'],true);
                    $delivery_status = $delivery_status_array[0]['status'];
                    $payment_status = $payment_status_array[0]['status'];
                    
                    $delivery_date_timeslot = json_decode($data[$k]['delivery_date_timeslot'],true);
                    $delivery_date =  (isset($delivery_date_timeslot[0]['date'])) ? $delivery_date_timeslot[0]['date'] : "";
                    $delivery_timeslot =  (isset($delivery_date_timeslot[0]['timeslot'])) ? $delivery_date_timeslot[0]['timeslot'] : "";
                    
                    $product_details = json_decode($data[$k]['product_details'],true);
                    $prdt_count = count($product_details);
                    //SUPPLIER AND SUPPLIER STORES
                    $supplier_ids = array_column($product_details,'supplier');
                    $suppliers = $this->db->select('supplier_name')->where_in( 'supplier_id', $supplier_ids )->get( 'supplier' )->result_array();
                    $supplier_names = implode(',',array_column($suppliers,'supplier_name'));
                    $assign_store_details = json_decode($data[$k]['assign_stores_data'],true);
                    $store_names = "";
                    if(is_array($assign_store_details[0])){
                        $supplier_store_ids = array_column($assign_store_details,'supplier_store_id');
                        $stores = $this->db->select('store_name')->where_in( 'supplier_store_id',$supplier_store_ids  )->get( 'supplier_store' )->result_array();
                        $store_names = implode(',',array_column($stores,'store_name'));
                        }
                    //SUPPLIER AND SUPPLIER STORES
                    $delivery_boy_details = json_decode($data[$k]['assign_delivery_data'],true);
                    $delivery_boy = "";
                    if(is_array($delivery_boy_details) && !empty($delivery_boy_details['name'])){
                        $delivery_boy = $delivery_boy_details['name'];
                                }
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$sale_id);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$sale_code);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",$userDetails);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j", $prdt_count);
                        $objPHPExcel->getActiveSheet()->getStyle("C$j")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",$city);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j",$area);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j",$supplier_names);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j",$store_names);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j",$delivery_boy);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j",$delivery_date);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j",$delivery_timeslot);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j",$payment_status);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j",$date);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j",$grand_total);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("O$j",$payment_type);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("P$j",$payment_currency);
		    $objPHPExcel->getActiveSheet()->setCellValueExplicit("Q$j",$delivery_fees_in_sdg);
                    
                        $j++;
                        $lastval=$j;
                    
                    }
                }
            else{
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("D3", 'NO DATA FOUND');
            }
          
                 

            $file_name = 'Trolley Delivery Report';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
        
        //added by sagar : service charge flow START 14-04-20
        function service_charge($para1 = '', $para2 = '', $para3 = '')
        {
            if ( ! $this->crud_model->admin_permission( 'service_charge' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            if ($para1 == 'do_add') {
                $data['payment_mode'] = $payment_mode = $this->input->post('payment_mode');
                $data['group_name'] =  $group_name = $this->input->post('group_name');
                    $exploded_group = explode(',',$group_name);
                    foreach($exploded_group as $groups){
                        $chechGroupExist = $this->db->like('group_name', $groups, 'both')->get_where('service_charge',array('payment_mode'=>$data['payment_mode']))->row_array();
                        if (is_array($chechGroupExist)) {
                            echo "<h5>Group Name $groups is already exist for payment mode : $payment_mode . <h5>";
                            exit;
                        }
                    }
                $data['service_fees'] =  $this->input->post('service_fees');
                if($data['service_fees'] > 99 ){
                    echo '<h5>Service fees should not be greater than 99<h5>';
                    exit;
                }
                $imploded_card_no = $this->input->post('card_no');
                $exploded_card_no = explode(',',$imploded_card_no);
                if(count($exploded_group) != count($exploded_card_no) ){
                    echo "<h5>Total no. of group name are NOT matching with total no. of card no<h5>";
                    exit;
                }
                foreach($exploded_card_no as $card_no){
                      //changed by sagar -- FOR wallet and card sevice charge  - START - 18-09
                        if( strlen($card_no) !== 6 && $payment_mode == 'Online card'){
                            echo "<h5>Every card number entry must have 6 initial digits.<h5>";
                            exit;
                        }

                        if( strlen($card_no) !== 2 && $payment_mode == 'Online wallet'){
                            echo "<h5>Every wallet number entry must have 2 initial digits.<h5>";
                            exit;
                        }
                        //changed by sagar -- FOR wallet and card sevice charge  - END - 18-09

                    $checkCardNumberExist = $this->db->like('card_no', $card_no, 'both')->get_where('service_charge',array('payment_mode' => $payment_mode))->row_array();
                    if (is_array($checkCardNumberExist)) {
                        echo "<h5> card $card_no is already exist for another record. <h5>";
                        exit;
                    }
                }
                $data['card_no'] = $imploded_card_no;
                $data['created_by'] = $_SESSION['admin_id'];
                $data['created_on'] = date('Y-m-d H:i:s');
                $this->db->insert('service_charge', $data);
            } else if ($para1 == 'edit') {
                $page_data['data'] = $this->db->get_where('service_charge', array(
                    'service_charge_id' => $para2
                ))->result_array();
   
                $this->load->view('back/admin/service_charge_edit', $page_data);
            } elseif ($para1 == "update") {
                if($_POST['service_fees'] > 99 ){
                    echo '<h5>Service fees should not be greater than 99<h5>';
                    exit;
                }
                if($para2 <= 4){
                    $data['service_fees'] =  $this->input->post('service_fees');
                }else{
                    $data['payment_mode'] = $payment_mode =  $this->input->post('payment_mode');
                    $data['group_name'] =  $group_name = $this->input->post('group_name');
                    $exploded_group = explode(',',$group_name);
                    foreach($exploded_group as $groups){
                        $chechGroupExist = $this->db->like('group_name', $groups, 'both')->get_where('service_charge',array('payment_mode'=>$data['payment_mode'],'service_charge_id != '=>$para2))->row_array();
                        if (is_array($chechGroupExist)) {
                            echo "<h5>Group Name $groups is already exist for payment mode : $payment_mode . <h5>";
                            exit;
                        }
                    }
                    $data['service_fees'] =  $this->input->post('service_fees');
                    
                    $imploded_card_no = $this->input->post('card_no');
                    $exploded_card_no = explode(',',$imploded_card_no);
                    if(count($exploded_group) != count($exploded_card_no) ){
                        echo "<h5>Total no. of group name are NOT matching with total no. of card no<h5>";
                        exit;
                    }
                    foreach($exploded_card_no as $card_no){
                        //changed by sagar -- FOR wallet and card sevice charge  - START - 18-09
                        if( strlen($card_no) !== 6 && $payment_mode == 'Online card'){
                            echo "<h5>Every card number entry must have 6 initial digits.<h5>";
                            exit;
                        }
                        
                        if( strlen($card_no) !== 2 && $payment_mode == 'Online wallet'){
                            echo "<h5>Every wallet number entry must have 2 initial digits.<h5>";
                            exit;
                        }
                        //changed by sagar -- FOR wallet and card sevice charge  - END - 18-09
                        
                        $checkCardNumberExist = $this->db->like('card_no', $card_no, 'both')->get_where('service_charge',array('service_charge_id != '=>$para2,'payment_mode' => $payment_mode))->row_array();
                        if (is_array($checkCardNumberExist)) {
                            echo "<h5>card $card_no is already exist for another record. <h5>";
                            exit;
                        }
                    }
                    $data['card_no'] = $imploded_card_no;
                }
                $data['updated_by'] = $_SESSION['admin_id'];
                $data['updated_on'] = date('Y-m-d H:i:s');
                $this->db->where('service_charge_id', $para2);
                $this->db->update('service_charge', $data);

            } elseif ($para1 == 'list') {
                $this->db->order_by('service_charge_id', 'asc');
                $page_data['all_data'] = $this->db->get('service_charge')->result_array();
                $this->load->view('back/admin/service_charge_list', $page_data);
            } elseif ($para1 == 'add') {
                $this->load->view('back/admin/service_charge_add');
            } elseif ($para1 == 'publish_set') {
                $area_id = $para2;
                if ($para3 == 'true') {
                    $data['status'] = 'Active';
                } else {
                    $data['status'] = 'In-active';
                }
                $this->db->where('service_charge_id', $area_id);
                $this->db->update('service_charge', $data);
            } else {
                $page_data['page_name']      = "service_charge";
                $page_data['all_data'] = $this->db->get('service_charge')->result_array();
                $this->load->view('back/index', $page_data);
            }
        }
        //added by sagar : service charge flow END 14-04-20
    
        function productSaleReport($para1 = '', $para2 = ''){
        if ( ! $this->crud_model->admin_permission( 'bill_of_qty_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            if ( $para1 == 'fetchTimeslots' ) {
                $date= $_POST['date'];
                $timeslotDay =  strtolower(date("l", strtotime($date)));
                $data =  $this->crud_model->getTimeSlots($timeslotDay);

                $option = '';
                if(!empty($data)){
                        foreach($data as $key => $val ) { 
                            $values =  $val['startTime'] . " - ". $val['endTime'];
                            $sel = "";
                            $option .= '<option value="'.$values.'" '.$sel.'>'.$values.'</option>';
                        }
                }
                echo json_encode(array("status"=>"success","option"=>$option));
    //            echo $this->crud_model->select_html( 'timeslots', 'timeslot_id', 'start_time|end_time', 'add', 'demo-chosen-select required', '', 'day', $timeslotDay, 'other' );
            }else{
                $page_data['page_name'] = "report_for_product_sale";
                $this->load->view( 'back/index', $page_data );
            }
        }

    function exportProductSaleReport(){
        if ( ! $this->crud_model->admin_permission( 'bill_of_qty_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $sale_date = date('Y-m-d');
        if (!empty($_POST['sale_date'] ) ) {
              $sale_date = $_POST['sale_date'];
            }

            if(!empty($_POST['timeslot'])){
                $timeslot = $_POST['timeslot'];
            }
            $data = $this->crud_model->getBillOfQtyReportData($sale_date,$timeslot);

            $this->load->library('Excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("Mypcot Infotech")
            ->setLastModifiedBy("admin")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Doc")
        ->setDescription("trolley_bill_of_qty_report")
            ->setKeywords("office 2007 ")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);



            // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Bill Of qty Report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 16,
                            ));

        $Titleheading ='Trolley Bill Of Quantity Report ('.$sale_date.')';

        //PRINTING HEADING ON EXCEL FILE   : START
            $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:O1")->getStyle("A1:O1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:O1")->getStyle("A1:O1")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );

            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'PRODUCT NAME (EN)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'PRODUCT NAME (AR)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'SKU CODE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'PRODUCT CODE', PHPExcel_Cell_DataType::TYPE_STRING);
//        $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'STORE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'UNIT PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'CATEGORY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'SUBCATEGORY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'BRAND', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'UNIT', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'TOTAL QTY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'TOTAL UNIT PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L2", 'SALE DATETIME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M2", 'DELIVERY DATE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N2", 'DELIVERY TIMESLOT', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("O2", 'SUPPLIER NAME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle("A2:O2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A2:O2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A2:O2")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A2:O2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->getStyle("A2:O2")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                            );


            if(is_array($data) && !empty($data) && count($data) > 0)
            {
                //PRINTING DATA ON SHEET
                $lastval=0;
                for($j=3,$k=0 ; $k<count($data); $k++){
    //                $saledate =  date('d M,Y h:i A',$data[$k]['sale_datetime']);
                    $saledate =  date('Y-m-d',strtotime($data[$k]['created_on']));
                    $delivery_date_timeslot = json_decode($data[$k]['delivery_date_timeslot'],true);
                    $delivery_date =  (isset($delivery_date_timeslot[0]['date'])) ? $delivery_date_timeslot[0]['date'] : "";
                    $delivery_timeslot =  (isset($delivery_date_timeslot[0]['timeslot'])) ? $delivery_date_timeslot[0]['timeslot'] : "";

                    //CONVERSION RATE FROM SALE ENTRY 
                    $user_choice = json_decode($data[$k]['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                    $unit_price = get_converted_currency($data[$k]['unit_price'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                    //CONVERSION RATE FROM SALE ENTRY 

                    $cart_product_data = json_decode($data[$k]['cart_product_data'],true);
                    $unit_weight = $cart_product_data['weight'];
                
                
                    $total_unit_price =  ($data[$k]['sum_of_qty'] * $data[$k]['unit_price']);
                    $total_unit_price = get_converted_currency($total_unit_price,DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                
                
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$data[$k]['product_name_en']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$data[$k]['product_name_ar']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",$data[$k]['sku_code']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j",$data[$k]['product_code']);
//                $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",$data[$k]['store_name']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",$unit_price );
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j",$data[$k]['category_name']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j",$data[$k]['sub_category_name']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j",$data[$k]['brand_name']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j",$unit_weight);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j",$data[$k]['sum_of_qty']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j",$total_unit_price);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j",$saledate);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j",$delivery_date);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j",$delivery_timeslot);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("O$j",$data[$k]['supplier_name']);
                    $j++;
                    $lastval=$j;

                }
            }
            else{
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("D3", 'NO DATA FOUND');
            }



        $file_name = 'Trolley Bill of Qty Report';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }

        function updateProductsStock( $para1 = '', $para2 = '' ) {
        if ( ! $this->crud_model->admin_permission( 'update_products_stock' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            if($para1 == 'download'){
                $data = $this->crud_model->getProductStockExportData();
                $this->load->library('Excel');
                //Create new PHPExcel object
                $objPHPExcel = new PHPExcel();
                //Set properties
                $objPHPExcel->getProperties()->setCreator("MypcotInfotech")
                ->setLastModifiedBy("admin")
                ->setTitle("Office 2007 XLSX Document")
                ->setSubject("Office 2007 XLSX product stock Doc")
                ->setDescription("Admin panel product stock details")
                ->setKeywords("office 2007 mypcot trolley php")
                ->setCategory("Export Excel");

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true); 
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');

                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);


                // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Product_Stock_Update');
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("A1", 'UniqueNo', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("B1", 'ProductName', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("C1", 'In Stock', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D1", 'Type (1-add|2-destroy)', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("E1", 'Quantity', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("F1", 'CategoryName', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("G1", 'SubCategoryName', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("H1", 'BrandName', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("I1", 'SKU Code', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFill()->getStartColor()->setRGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
                //$objPHPExcel->getActiveSheet()->getStyle('A1:C20')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
                $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                 if(is_array($data) && !empty($data) && count($data) > 0)
                   {
                        for($j=2,$k=0 ; $k<count($data); $k++){
                            $product_variation_title = $data[$k]['title'];
                            if(!empty($data[$k]['variation_title'])){
                                $product_variation_title .= "(".$data[$k]['variation_title'].")";
                            }

                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j", $data[$k]['variation_id'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j", $product_variation_title );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j", $data[$k]['current_stock']  );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j", "");
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j", 0);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j", $data[$k]['category_name'] );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j", $data[$k]['sub_category_name'] );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j", $data[$k]['brand_name'] );
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j", $data[$k]['sku_code'] );
                           $j++;
                        }

                   }
                // Redirect output to a client's web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="ProductStockUpdate.xls"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
            }else if($para1 == 'updateStock'){
                ini_set('memory_limit', '-1');
                if(isset($_FILES['importproductexcel']['name']) && !empty($_FILES['importproductexcel']['name']))
                {
                        $name = $_FILES['importproductexcel']['name'];
                        $names = explode(".", $name);
                        $size = $_FILES['importproductexcel']['size'];
                        $max_file_size = 1024*1024*2;		// 2 MB

                        if ((end($names)=="xls" || end($names)=="XLS" || end($names) == "csv"  || end($names) == "CSV"))
                        {
                                //Load the excel library
                                $this->load->library('excel');

                                //Read file from path
                                $objPHPExcel = PHPExcel_IOFactory::load($_FILES['importproductexcel']['tmp_name']);
                                //Get only the Cell Collection
                                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,false,true);
                                if(is_array($sheetData)){
                                    if(!( isset($sheetData[1]['A']) &&$sheetData[1]['A']=='UniqueNo' 
                                            && isset($sheetData[1]['B']) && $sheetData[1]['B']=='ProductName'
                                            && isset($sheetData[1]['C']) && $sheetData[1]['C']=='In Stock'
                                        && isset($sheetData[1]['D']) && $sheetData[1]['D']=='Type (1-add|2-destroy)'
                                            && isset($sheetData[1]['E']) && $sheetData[1]['E']=='Quantity'
                                            )){

                                             echo ' Unsupported file format.<br> Correct Order is :: UniqueNo,ProductName,In Stock,Type (1-add|2-destroy),Quantity<br>';
                                                 echo '<a href="'. base_url().'admin/updateProductsStock">Go Back</a>';
                                                 exit;
                                            }
                                }else{
                                    echo ' Unsupported file format only XLS / CSV files allowed.<br><br>';
                                     echo '<a href="'. base_url().'admin/updateProductsStock">Go Back</a>';
                                    exit;
                                }

                                for($i = 2; $i < count($sheetData)+1; $i++)
                                {
                                    $variation_id = $sheetData[$i]['A'];
                                    $type = trim($sheetData[$i]['D']);
                                    $quantity = trim($sheetData[$i]['E']);
                                    if (empty($variation_id)) {
                                        echo ' ERROR On Row ' . $i . ' : UniqueNo is NOT provided.<br>';
                                        continue;
                                    }
                                    if (empty($type)) {
                                        echo ' ERROR On Row ' . $i . ' : Type is NOT provided.<br>';
                                        continue;
                                    }
                                if($type != '1' && $type != '2'){
                                    echo ' ERROR On Row ' . $i . ' : Invalid Type Entered. Enter 1 for add OR 2 for destroy<br>';
                                        continue;
                                    }
                                    $current_stock =  $this->db->get_where('variation',array('variation_id'=>$variation_id))->row()->current_stock;
                                    $product_id =  $this->db->get_where('variation',array('variation_id'=>$variation_id))->row()->product_id;
                                    if($type == 'destroy' && $quantity > $current_stock){
                                        echo ' ERROR On Row ' . $i . ' : Quantity exceeds In-stock values to destroy.<br>';
                                        continue;
                                    }
                                    if(!empty($quantity)){
                                        $type = ($type == 1) ? 'add' : 'destroy';
                                 
                                        //STOCK INSERT : START
                                        $stock_data['product'] = $product_id;
                                        $stock_data['variation_id'] = $variation_id;
                                        $stock_data['category'] = $this->crud_model->get_type_name_by_id('product', $product_id, 'category');
                                        $stock_data['sub_category'] = $this->crud_model->get_type_name_by_id('product', $product_id, 'sub_category');
                                        $stock_data['quantity'] = $quantity;
                                        $stock_data['total'] = 0;
                                        $stock_data['reason_note'] = "by admin : quantity $quantity - $type";
                                        $stock_data['sale_id'] = 0;
                                        $stock_data['datetime'] = time();
                                        $stock_data['type'] = $type;
                                        $this->db->insert('stock', $stock_data);
                                        //STOCK INSERT : END

                                        if($type == 'add'){
                                            $newStock = $current_stock + $quantity;
                                        }else{
                                            $newStock = $current_stock - $quantity;
                                        }
                                        $data_array['current_stock']=$newStock;
                                        //Variation Current stock update
                                        $this->db->where('variation_id', $variation_id);
                                        $this->db->update('variation', $data_array);
                                        echo 'Row '.$i.' : Product Stock Updated Successfully.<br>';
                                    }else{
                                        echo 'Row '.$i.' : No change found<br>';
                                    }

                                }
                                echo '<a href="'. base_url().'admin/updateProductsStock">Go Back</a>';
                        }
                        else 
                        {
                                echo ' Unsupported file format.<br><br>';
                                echo '<a href="'. base_url().'admin/updateProductsStock">Go Back</a>';
                                exit;
                        }
                }
                else{
                        echo ' Please Select File.<br><br>';
                        echo '<a href="'. base_url().'admin/updateProductsStock">Go Back</a>';
                        exit;
                }
                exit;

            }else{
             $page_data['page_name'] = "excel_for_product_stock_update";
             $this->load->view( 'back/index', $page_data );
            }
        }
       
    function billQtyReport($para1 = '', $para2 = ''){
        if ( ! $this->crud_model->admin_permission( 'bill_of_qty_date_range_report' )  ) {
            redirect( base_url() . 'index.php/admin' );
        }
        $page_data['page_name'] = "report_bill_of_qty";
        $page_data['suppliers'] =  $this->db->get_where('supplier',array())->result_array();
        $this->load->view( 'back/index', $page_data );
    }
        
    function exportBillQtyReport(){
        if ( ! $this->crud_model->admin_permission( 'bill_of_qty_date_range_report' )  ) {
            redirect( base_url() . 'index.php/admin' );
        }
        $daterange              = date( 'm/d/Y' ) . ' - ' . date( 'm/d/Y' );
        if ( ! empty( $_POST['daterange'] ) ) {
                $daterange = $_POST['daterange'];
        }
        $supplier_id = 0;
        if(!empty($_POST['supplier'])){
           $supplier_id = $_POST['supplier'];
        }
        $data = $this->crud_model->getBillOfQtyRangeData($daterange,$supplier_id);
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Mypcot Infotech")
        ->setLastModifiedBy("admin")
        ->setTitle("Office 2007 XLSX Document")
        ->setSubject("Office 2007 XLSX Doc")
        ->setDescription("trolley_bill_of_qty_date_range_report")
        ->setKeywords("office 2007 ")
        ->setCategory("Export Excel");

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);



        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Bill of qty date range report');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $styleArray = array(
                        'font'  => array(
                            'bold'  => true,
                            'size'  => 16,
                        ));
        
        $date = explode( ' - ', $daterange );
        $start_time = strtotime($date[0]);
        $end_time = strtotime($date[1]);
        $Titleheading ='Trolley Bill Of Quantity Date Range Report ('.date('d/m/Y',$start_time);
        if(date('d/m/Y',$start_time)  != date('d/m/Y',$end_time)){
            $Titleheading .= ' - '.date('d/m/Y',$end_time);
        }
        $Titleheading .=')';

    //PRINTING HEADING ON EXCEL FILE   : START
        $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->mergeCells("A1:O1")->getStyle("A1:O1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $objPHPExcel->getActiveSheet()->mergeCells("A1:O1")->getStyle("A1:O1")->getAlignment()->applyFromArray(
                                                                                        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                    );

        $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'PRODUCT NAME (EN)', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'PRODUCT NAME (AR)', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'SKU CODE', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'PRODUCT CODE', PHPExcel_Cell_DataType::TYPE_STRING);
//        $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'STORE', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'UNIT PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'CATEGORY', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'SUBCATEGORY', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'BRAND', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'UNIT', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'TOTAL QTY', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'TOTAL UNIT PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("L2", 'SALE DATETIME', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("M2", 'DELIVERY DATE', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("N2", 'DELIVERY TIMESLOT', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("O2", 'SUPPLIER NAME', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->getStyle("A2:O2")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A2:O2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle("A2:O2")->getFill()->getStartColor()->setRGB('FFFF00');
        $objPHPExcel->getActiveSheet()->getStyle("A2:O2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $objPHPExcel->getActiveSheet()->getStyle("A2:O2")->getAlignment()->applyFromArray(
                                                                                        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );


        if(is_array($data) && !empty($data) && count($data) > 0)
        {
            //PRINTING DATA ON SHEET
            $lastval=0;
            for($j=3,$k=0 ; $k<count($data); $k++){
//                $saledate =  date('d M,Y h:i A',$data[$k]['sale_datetime']);
                $saledate =  date('Y-m-d',strtotime($data[$k]['created_on']));
                $delivery_date_timeslot = json_decode($data[$k]['delivery_date_timeslot'],true);
                $delivery_date =  (isset($delivery_date_timeslot[0]['date'])) ? $delivery_date_timeslot[0]['date'] : "";
                $delivery_timeslot =  (isset($delivery_date_timeslot[0]['timeslot'])) ? $delivery_date_timeslot[0]['timeslot'] : "";
                
                //CONVERSION RATE FROM SALE ENTRY 
                $user_choice = json_decode($data[$k]['user_choice'], true);
                $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                $unit_price = get_converted_currency($data[$k]['unit_price'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                //CONVERSION RATE FROM SALE ENTRY 
                
                $cart_product_data = json_decode($data[$k]['cart_product_data'],true);
                $unit_weight = $cart_product_data['weight'];
                
                $total_unit_price = ($data[$k]['unit_price'] * $data[$k]['sum_of_qty']);
                $total_unit_price = get_converted_currency($total_unit_price,DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$data[$k]['product_name_en']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$data[$k]['product_name_ar']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",$data[$k]['sku_code']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j",$data[$k]['product_code']);
//                $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",$data[$k]['store_name']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",$unit_price );
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j",$data[$k]['category_name']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j",$data[$k]['sub_category_name']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j",$data[$k]['brand_name']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j",$unit_weight);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j",$data[$k]['sum_of_qty']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j",$total_unit_price);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j",$saledate);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j",$delivery_date);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j",$delivery_timeslot);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("O$j",$data[$k]['supplier_name']);
                $j++;
                $lastval=$j;

            }
        }
        else{
             $objPHPExcel->getActiveSheet()->setCellValueExplicit("D3", 'NO DATA FOUND');
        }



        $file_name = 'Trolley_bill_of_qty_date_range_report';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$file_name.'.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    
    function billQtyDateRangeStoreReport($para1 = '', $para2 = ''){
        if ( ! $this->crud_model->admin_permission( 'bill_of_qty_date_range_by_store_report' )  ) {
            redirect( base_url() . 'index.php/admin' );
        }
        if( $para1 == 'suppStores' ) {
            echo $this->crud_model->select_html('supplier_store', 'supplier_store', 'store_name|store_number|store_address', 'add', 'demo-chosen-select required', '', 'supplier_id', $para2, 'other');
        }else{
            $page_data['page_name'] = "report_bill_of_qty_date_range_store";
            $page_data['suppliers'] =  $this->db->get_where('supplier',array())->result_array();
            $this->load->view( 'back/index', $page_data );
        }
    }

    function exportBillQtyDateRangeStoreReport(){
        if ( ! $this->crud_model->admin_permission( 'bill_of_qty_date_range_by_store_report' )  ) {
            redirect( base_url() . 'index.php/admin' );
        }
        $daterange              = date( 'm/d/Y' ) . ' - ' . date( 'm/d/Y' );
        if ( ! empty( $_POST['daterange'] ) ) {
                $daterange = $_POST['daterange'];
        }
        $supplier_id = 0;
        if(!empty($_POST['supplier'])){
           $supplier_id = $_POST['supplier'];
        }

        $supplier_store_id = 0;
        if(!empty($_POST['supplier_store'])){
           $supplier_store_id = $_POST['supplier_store'];
        }
        $data = $this->crud_model->getBillOfQtyDateRangeStoreData($daterange,$supplier_id,$supplier_store_id);
      //echo '<pre>';
      //  print_r($this->db->last_query());
      //  print_r('|||||||||||||||||||||||');
      //  print_r($data);
      //  print_r('//////////////////////////////////');
      //  print_r(is_array($data) && !empty($data) && count($data) > 0);
      //   print_r('-----------------------------');
      //  print_r(count($data) > 0);
      //  exit();
        $this->load->library('Excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Mypcot Infotech")
        ->setLastModifiedBy("admin")
        ->setTitle("Office 2007 XLSX Document")
        ->setSubject("Office 2007 XLSX Doc")
        ->setDescription("trolley_bill_of_qty_date_range_store_report")
        ->setKeywords("office 2007 ")
        ->setCategory("Export Excel");

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);



        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('BOQ_date_range_by_store_report');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $styleArray = array(
                        'font'  => array(
                            'bold'  => true,
                            'size'  => 16,
                        ));
        
        $date = explode( ' - ', $daterange );
        $start_time = strtotime($date[0]);
        $end_time = strtotime($date[1]);
        $Titleheading ='Trolley Bill Of Quantity Date Range by store Report ('.date('d/m/Y',$start_time);
        if(date('d/m/Y',$start_time)  != date('d/m/Y',$end_time)){
            $Titleheading .= ' - '.date('d/m/Y',$end_time);
        }
        $Titleheading .=')';

    //PRINTING HEADING ON EXCEL FILE   : START
        $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->mergeCells("A1:P1")->getStyle("A1:P1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $objPHPExcel->getActiveSheet()->mergeCells("A1:P1")->getStyle("A1:P1")->getAlignment()->applyFromArray(
                                                                                        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                    );

        $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'PRODUCT NAME (EN)', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'PRODUCT NAME (AR)', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'SKU CODE', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'PRODUCT CODE', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'STORE', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'UNIT PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'CATEGORY', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'SUBCATEGORY', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'BRAND', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'UNIT', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'TOTAL QTY', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("L2", 'TOTAL UNIT PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("M2", 'SALE DATETIME', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("N2", 'DELIVERY DATE', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("O2", 'DELIVERY TIMESLOT', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("P2", 'SUPPLIER NAME', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->getStyle("A2:P2")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A2:P2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle("A2:P2")->getFill()->getStartColor()->setRGB('FFFF00');
        $objPHPExcel->getActiveSheet()->getStyle("A2:P2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $objPHPExcel->getActiveSheet()->getStyle("A2:P2")->getAlignment()->applyFromArray(
                                                                                        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );


        if(is_array($data) && !empty($data) && count($data) > 0)
        {
            //PRINTING DATA ON SHEET
            $lastval=0;
            for($j=3,$k=0 ; $k<count($data); $k++){
//                $saledate =  date('d M,Y h:i A',$data[$k]['sale_datetime']);
                $saledate =  date('Y-m-d',strtotime($data[$k]['created_on']));
                $delivery_date_timeslot = json_decode($data[$k]['delivery_date_timeslot'],true);
                $delivery_date =  (isset($delivery_date_timeslot[0]['date'])) ? $delivery_date_timeslot[0]['date'] : "";
                $delivery_timeslot =  (isset($delivery_date_timeslot[0]['timeslot'])) ? $delivery_date_timeslot[0]['timeslot'] : "";
                
                //CONVERSION RATE FROM SALE ENTRY 
                $user_choice = json_decode($data[$k]['user_choice'], true);
                $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                $unit_price = get_converted_currency($data[$k]['unit_price'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                //CONVERSION RATE FROM SALE ENTRY 
                
                $cart_product_data = json_decode($data[$k]['cart_product_data'],true);
                $unit_weight = $cart_product_data['weight'];
                
                $total_unit_price = ($data[$k]['unit_price'] * $data[$k]['sum_of_qty']);
                $total_unit_price = get_converted_currency($total_unit_price,DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$data[$k]['product_name_en']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$data[$k]['product_name_ar']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",$data[$k]['sku_code']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j",$data[$k]['product_code']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",$data[$k]['store_name']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j",$unit_price );
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j",$data[$k]['category_name']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j",$data[$k]['sub_category_name']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j",$data[$k]['brand_name']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j",$unit_weight);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j",$data[$k]['sum_of_qty']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j",$total_unit_price);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j",$saledate);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j",$delivery_date);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("O$j",$delivery_timeslot);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("P$j",$data[$k]['supplier_name']);
                $j++;
                $lastval=$j;

            }
        }
        else{
             $objPHPExcel->getActiveSheet()->setCellValueExplicit("D3", 'NO DATA FOUND');
        }



        $file_name = 'Trolley_bill_of_qty_date_range_by_store_report';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$file_name.'.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
   

    function cityReport($para1='',$para2=''){
        if ( ! $this->crud_model->admin_permission( 'report' )  ) {
            redirect( base_url() . 'index.php/admin' );
}
        $page_data['page_name'] = "excel_for_city";
        $this->load->view( 'back/index', $page_data );
    }

        function exportCityData(){
            if ( ! $this->crud_model->admin_permission( 'city_report' )  &&  
                    ! $this->crud_model->admin_permission( 'import_delivery_boy' ) && 
                    ! $this->crud_model->admin_permission( 'update_delivery_boy' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $data = $this->db->get('city')->result_array();
            $this->load->library('Excel');
            //Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            //Set properties
            $objPHPExcel->getProperties()->setCreator("MypcotInfotech")
            ->setLastModifiedBy("admin")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX city List Doc")
            ->setDescription("Admin panel city details")
            ->setKeywords("office 2007 mypcot trolley php")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('City List Report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A1", 'CITY ID', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B1", 'CITY NAME (EN)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C1", 'CITY NAME (AR)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D1", 'STATUS', PHPExcel_Cell_DataType::TYPE_STRING);
           
            $objPHPExcel->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A1:D1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A1:D1")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A1:D1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
            //$objPHPExcel->getActiveSheet()->getStyle('A1:C20')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
             if(is_array($data) && !empty($data) && count($data) > 0)
               {
                    for($j=2,$k=0 ; $k<count($data); $k++){
                        if($data[$k]['status'] == 'ok'){
                            $status =  'Active';
                        }else{
                            $status = 'In-active';
                        }
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j", $data[$k]['city_id'] );
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j", $data[$k]['city_name_en']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j", $data[$k]['city_name_ar']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j", $status);
                       $j++;
                    }

               }
            // Redirect output to a client's web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="trolley_CityReport.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');

        }
        
        private function downloadAreaRepot(){
                $this->db->select('a.*,c.city_name_en,c.city_name_ar');
                $this->db->join('city as c', 'c.city_id = a.city_id');
                $data = $this->db->get('area as a')->result_array();
                $this->load->library('Excel');
                //Create new PHPExcel object
                $objPHPExcel = new PHPExcel();
                //Set properties
                $objPHPExcel->getProperties()->setCreator("MypcotInfotech")
                ->setLastModifiedBy("admin")
                ->setTitle("Office 2007 XLSX Document")
                ->setSubject("Office 2007 XLSX city List Doc")
                ->setDescription("Admin panel city details")
                ->setKeywords("office 2007 mypcot trolley php")
                ->setCategory("Export Excel");

                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

                // Rename sheet
                $objPHPExcel->getActiveSheet()->setTitle('Area List Report');
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("A1", 'AREA ID', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("B1", 'AREA NAME (EN)', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("C1", 'AREA NAME (AR)', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("D1", 'CITY ID', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("E1", 'STATUS (1-Active|2-In-active)', PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("F1", 'DELIVERY CHARGE ($)', PHPExcel_Cell_DataType::TYPE_STRING);

                $objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFill()->getStartColor()->setRGB('FFFF00');
                $objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
                //$objPHPExcel->getActiveSheet()->getStyle('A1:C20')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
                $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
                if(is_array($data) && !empty($data) && count($data) > 0)
                   {
                        for($j=2,$k=0 ; $k<count($data); $k++){
                            if($data[$k]['status'] == 'ok'){
//                                $status =  'Active';
                                $status =  '1';
                            }else{
//                                $status = 'In-active';
                                $status = '2';
                            }
                           $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j", $data[$k]['area_id'] );
                           $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j", $data[$k]['area_name_en']);
                           $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j", $data[$k]['area_name_ar']);
                           $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j", $data[$k]['city_id']);
                           $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j", $status);
                           $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j", $data[$k]['delivery_charge']);
                           $j++;
                        }

                   }
                // Redirect output to a client's web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="trolley_AreaReport.xls"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
        }
                 
        function areaReport($para1='',$para2=''){
            if ( ! $this->crud_model->admin_permission( 'area_report' ) ) {
                redirect( base_url() . 'index.php/admin' );
            }
            if($para1 == 'download'){
                //Moving this part in other funcion
                $this->downloadAreaRepot();
            }else if($para1 == 'saveChanges'){
                ini_set('memory_limit', '-1');
		if(isset($_FILES['importproductexcel']['name']) && !empty($_FILES['importproductexcel']['name']))
  		{
			$name = $_FILES['importproductexcel']['name'];
			$names = explode(".", $name);
			$size = $_FILES['importproductexcel']['size'];
			$max_file_size = 1024*1024*2;		// 2 MB
			if ((end($names)=="xls" || end($names)=="XLS" || end($names) == "csv"  || end($names) == "CSV"))
			{
                                //Load the excel library
				$this->load->library('excel');
				
				//Read file from path
				$objPHPExcel = PHPExcel_IOFactory::load($_FILES['importproductexcel']['tmp_name']);
				
				//Get only the Cell Collection
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,false,true);
                             
                                if(is_array($sheetData)){
                                    if(!( isset($sheetData[1]['A']) &&$sheetData[1]['A']=='AREA ID' 
                                            && isset($sheetData[1]['B']) && $sheetData[1]['B']=='AREA NAME (EN)'
                                            && isset($sheetData[1]['C']) && $sheetData[1]['C']=='AREA NAME (AR)'
                                            && isset($sheetData[1]['D']) && $sheetData[1]['D']=='CITY ID'
                                            && isset($sheetData[1]['E']) && $sheetData[1]['E']=='STATUS (1-Active|2-In-active)'
                                            && isset($sheetData[1]['F']) && $sheetData[1]['F']=='DELIVERY CHARGE ($)'
                                            )){
      
                                                 echo ' Unsupported file format.<br> Correct Order is :: AREA ID,AREA NAME (EN),AREA NAME (AR),CITY ID,STATUS (1-Active|2-In-active),DELIVERY CHARGE ($)<br>';
                                                 echo '<a href="'. base_url().'admin/areaReport">Go Back</a>';
                                                 exit;
                                            }
                                }else{
                                    echo ' Unsupported file format only XLS / CSV files allowed.<br><br>';
                                     echo '<a href="'. base_url().'admin/areaReport">Go Back</a>';
                                    exit;
                                }
                               
				for($i = 2; $i < count($sheetData)+1; $i++)
                                {
                                    $area_id = $sheetData[$i]['A'];
                                    $area_status = $sheetData[$i]['E'];
                                    $delivery_charge = $sheetData[$i]['F'];
                                    if(empty($area_status)){
                                        echo ' ERROR On Row ' . $i . ' : Status NOT Provided.<br>';
                                        continue;
                                    }
                                    if(empty($delivery_charge)){
                                        $delivery_charge = 0;
                                    }
                                    if($delivery_charge < 0){
                                        echo ' ERROR On Row ' . $i . ' : Delivery Charge should NOT be less than zero. <br>';
                                        continue;
                                    }
                                    if($area_status != '1' && $area_status != '2'){
                                        echo ' ERROR On Row ' . $i . ' : Invalid Status Entered. Enter 1 for Active OR 2 for In-active<br>';
                                        continue;
                                    }
                                    $area_status = ($area_status == 1) ? 'ok' : '0';
                                    $data_array = array(
                                        'status' => $area_status,
                                        'delivery_charge' => $delivery_charge
                                    );
                                    $this->db->where('area_id', $area_id);
                                    $this->db->update('area', $data_array);
                                    echo 'Row '.$i.' :  Area Updated Successfully.<br>';
                                }
                                echo '<a href="'. base_url().'admin/areaReport">Go Back</a>';
			}
			else 
			{
				echo ' Unsupported file format.<br><br>';
                                echo '<a href="'. base_url().'admin/areaReport">Go Back</a>';
                                exit;
			}
		}
		else{
			echo ' Please Select File.<br><br>';
                        echo '<a href="'. base_url().'admin/areaReport">Go Back</a>';
                        exit;
		}
		exit;
               
            }else{
                $page_data['page_name'] = "excel_for_area";
                $this->load->view( 'back/index', $page_data );
            }
        }
    
        function importDeliveryboy( $para1 = '', $para2 = '' ) {
            if ( ! $this->crud_model->admin_permission( 'import_delivery_boy' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            if($para1 == 'download'){
                $file= 'delivery_boy_sample.xlsx';
                $filepath = VIEWPATH."/" . $file;
                if(file_exists($filepath)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($filepath));
                    flush(); // Flush system output buffer
                    readfile($filepath);
                    die();
                }
            }elseif($para1 == 'downloadArea'){
                 $this->downloadAreaRepot();
            }elseif($para1 == 'saveDeliveryData'){
                ini_set('memory_limit', '-1');
		if(isset($_FILES['importproductexcel']['name']) && !empty($_FILES['importproductexcel']['name']))
  		{
			$name = $_FILES['importproductexcel']['name'];
			$names = explode(".", $name);
			$size = $_FILES['importproductexcel']['size'];
			$max_file_size = 1024*1024*2;		// 2 MB
                       
			if ((end($names)=="xls" || end($names)=="XLS" || end($names)=="xlsx" || end($names)=="XLSX" ||  end($names) == "csv"  || end($names) == "CSV"))
			{
                                //Load the excel library
				$this->load->library('excel');
				
				//Read file from path
				$objPHPExcel = PHPExcel_IOFactory::load($_FILES['importproductexcel']['tmp_name']);
				//Get only the Cell Collection
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,false,true);
                                if(is_array($sheetData)){
                                    if(!( isset($sheetData[1]['A']) &&$sheetData[1]['A']=='NAME' 
                                            && isset($sheetData[1]['B']) && $sheetData[1]['B']=='EMAIL'
                                            && isset($sheetData[1]['C']) && $sheetData[1]['C']=='PASSWORD'
                                            && isset($sheetData[1]['D']) && $sheetData[1]['D']=='PHONE'
                                            && isset($sheetData[1]['E']) && $sheetData[1]['E']=='ADDRESS'
                                            && isset($sheetData[1]['F']) && $sheetData[1]['F']=='CITY ID'
                                            && isset($sheetData[1]['G']) && $sheetData[1]['G']=='AREA IDS'
                                            && isset($sheetData[1]['H']) && $sheetData[1]['H']=='USER TYPE (1-Internal |2-External)'
                                            )){
      
                                                 echo ' Unsupported file format.<br> Correct Order is :: NAME,EMAIL,PASSWORD,PHONE,ADDRESS,CITY ID,AREA IDS,USER TYPE (1-Internal |2-External)<br>';
                                                 echo '<a href="'. base_url().'admin/importDeliveryboy">Go Back</a>';
                                                 exit;
                                            }
                                }else{
                                    echo ' Unsupported file format only XLS / CSV files allowed.<br><br>';
                                    echo '<a href="'. base_url().'admin/importDeliveryboy">Go Back</a>';
                                    exit;
                                }
                                echo " IMPORT DATA STATUS  ::<br> ";
				for($i = 2; $i < count($sheetData)+1; $i++)
                                {
                                    $name = trim($sheetData[$i]['A']);
                                    $email = $sheetData[$i]['B'];
                                    $password = trim($sheetData[$i]['C']);
                                    $phone = trim($sheetData[$i]['D']);
                                    $address = trim($sheetData[$i]['E']);
                                    $city_id = trim($sheetData[$i]['F']);
                                    $area_ids = trim($sheetData[$i]['G']);
                                    $userType = trim($sheetData[$i]['H']);
                                    
                                    if (empty($name)) {
                                        echo ' ERROR On Row ' . $i . ' : Name is NOT provided.<br>';
                                        continue;
                                    }
                                    if (empty($email)) {
                                        echo ' ERROR On Row ' . $i . ' : Email ID is NOT provided.<br>';
                                        continue;
                                    }
                                    if (empty($password)) {
                                        echo ' ERROR On Row ' . $i . ' : Password is NOT provided.<br>';
                                        continue;
                                    }
                                    if (empty($phone)) {
                                        echo ' ERROR On Row ' . $i . ' : Phone Number is NOT provided.<br>';
                                        continue;
                                    }
                                    if (empty($userType)) {
                                        echo ' ERROR On Row ' . $i . ' : User Type is NOT provided.<br>';
                                        continue;
                                    }
                                  
                                    
                                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                        echo ' ERROR On Row ' . $i . ' : Invalid Email ID Entered<br>';
                                        continue;
                                    }
                                    
                                    if (!(preg_match('/^[0-9]{10}+$/', $phone))) {
                                        echo ' ERROR On Row ' . $i . ' : Invalid Phone Number Entered.(Length must be 10 digits)<br>';
                                        continue;
                                    }
                                    
                                    $is_email_unique = $this->crud_model->verify_if_unique('admin', 'email = ' . $this->db->escape($email));
                                    if (is_array($is_email_unique)) {
                                        echo ' ERROR On Row ' . $i . ' : Email ID Already Exist.<br>';
                                        continue;
                                    }
                                    
                                    $is_phone_unique = $this->crud_model->verify_if_unique('admin', 'phone = ' . $this->db->escape($phone));
                                    if (is_array($is_phone_unique)) {
                                        echo ' ERROR On Row ' . $i . ' : Phone Number Already Exist.<br>';
                                        continue;
                                    }
                                    
                                    if ($userType != '1' && $userType != '2' ) {
                                        echo ' ERROR On Row ' . $i . ' : Invalid User Type Entered. Enter 1 for Internal OR 2 for External<br>';
                                        continue;
                                    }
                                    
                                    $userType = ($userType == 1) ? 'Internal' : 'External';
                                    
                                    if(!empty($area_ids)){
                                        $areas =  explode(',',$area_ids);
                                        $areas = array_filter($areas);
                                        $area_ids = json_encode($areas);
                                    }else{
                                        $area_ids = '[]';
                                    }
                                    
                                    if(empty($city_id)){
                                       $city_id = 0;
                                    }
                                    
                                    $data_array = array(
                                        'name'=>$name,
                                        'phone'=>$phone,
                                        'email'=>$email,
                                        'address'=>$address,
                                        'role'=>4,
                                        'password'=>sha1($password),
                                        'city_id'=>$city_id,
                                        'area_ids'=>$area_ids,
                                        'user_type'=>$userType,
                                        'timestamp'=>time(),
                                        'created_by'=>$_SESSION['admin_id'],
                                    );
                                    $this->db->insert( 'admin', $data_array );
                                    echo 'Row '.$i.' : Delivery Boy Created Successfully.<br>';
                                }
                                echo '<a href="'. base_url().'admin/importDeliveryboy">Go Back</a>';
			}
			else 
			{
				echo ' Unsupported file format.<br><br>';
                                 echo '<a href="'. base_url().'admin/importDeliveryboy">Go Back</a>';
                                exit;
			}
		}
		else{
			echo ' Please Select File.<br><br>';
                         echo '<a href="'. base_url().'admin/importDeliveryboy">Go Back</a>';
                        exit;
		}
		exit;
               
           }else{
            $page_data['page_name'] = "excel_for_delivery_boy";
            $this->load->view( 'back/index', $page_data );
           }
    }
    
    function updateDeliveryboyInfo( $para1 = '', $para2 = '' ) {
        if ( ! $this->crud_model->admin_permission( 'update_delivery_boy' )  ) {
            redirect( base_url() . 'index.php/admin' );
        }
        if($para1 == 'download'){
            $data = $this->db->order_by('admin_id','ASC')->get_where('admin',array('role'=>4))->result_array();
        
            $this->load->library('Excel');
            //Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            //Set properties
            $objPHPExcel->getProperties()->setCreator("MypcotInfotech")
            ->setLastModifiedBy("admin")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Delivery boy Doc")
            ->setDescription("Admin panel delivery boy details")
            ->setKeywords("office 2007 mypcot trolley php")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');

            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);


            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Update_deliveryboy_info');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A1", 'UniqueNo', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B1", 'Name', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C1", 'Phone', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D1", 'Address', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E1", 'City Id', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F1", 'Area Ids', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G1", 'User Type (1-Internal|2-External)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle("A1:G1")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A1:G1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A1:G1")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A1:G1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
            //$objPHPExcel->getActiveSheet()->getStyle('A1:C20')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
             if(is_array($data) && !empty($data) && count($data) > 0)
               {
                    for($j=2,$k=0 ; $k<count($data); $k++){
                        
                        $area_ids_array =  json_decode($data[$k]['area_ids'],true);
                        $area_ids = implode(',',$area_ids_array);
                        $userType =  ($data[$k]['user_type'] == 'Internal') ? '1' : '2';
                        
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j", $data[$k]['admin_id'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j", $data[$k]['name'] );
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j", $data[$k]['phone']);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j", $data[$k]['address'] );
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j", $data[$k]['city_id'] );
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j", $area_ids);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j", $userType );
                       $j++;
                    }

               }
            // Redirect output to a client's web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="updateDeliveryInfo.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');

        }elseif($para1 == 'downloadArea'){
            $this->downloadAreaRepot();
        }else if($para1 == 'updateInfo'){
            ini_set('memory_limit', '-1');
            if(isset($_FILES['importproductexcel']['name']) && !empty($_FILES['importproductexcel']['name']))
            {
                    $name = $_FILES['importproductexcel']['name'];
                    $names = explode(".", $name);
                    $size = $_FILES['importproductexcel']['size'];
                    $max_file_size = 1024*1024*2;		// 2 MB

                    if ((end($names)=="xls" || end($names)=="XLS" || end($names) == "csv"  || end($names) == "CSV"))
                    {
                            //Load the excel library
                            $this->load->library('excel');

                            //Read file from path
                            $objPHPExcel = PHPExcel_IOFactory::load($_FILES['importproductexcel']['tmp_name']);
                            //Get only the Cell Collection
                            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,false,true);
                            if(is_array($sheetData)){
                                if(!( isset($sheetData[1]['A']) &&$sheetData[1]['A']=='UniqueNo' 
                                        && isset($sheetData[1]['B']) && $sheetData[1]['B']=='Name'
                                        && isset($sheetData[1]['C']) && $sheetData[1]['C']=='Phone'
                                        && isset($sheetData[1]['D']) && $sheetData[1]['D']=='Address'
                                        && isset($sheetData[1]['E']) && $sheetData[1]['E']=='City Id'
                                        && isset($sheetData[1]['F']) && $sheetData[1]['F']=='Area Ids'
                                        && isset($sheetData[1]['G']) && $sheetData[1]['G']=='User Type (1-Internal|2-External)'
                                        )){

                                             echo ' Unsupported file format.<br> Correct Order is :: UniqueNo,Name,Phone,Address,City Id,Area Ids,User Type (1-Internal|2-External)<br>';
                                             echo '<a href="'. base_url().'admin/updateDeliveryboyInfo">Go Back</a>';
                                             exit;
                                        }
                            }else{
                                echo ' Unsupported file format only XLS / CSV files allowed.<br><br>';
                                 echo '<a href="'. base_url().'admin/updateDeliveryboyInfo">Go Back</a>';
                                exit;
                            }
                          
                            for($i = 2; $i < count($sheetData)+1; $i++)
                            {
                                $admin_id = $sheetData[$i]['A'];
                                $name = trim($sheetData[$i]['B']);
                                $phone = trim($sheetData[$i]['C']);
                                $address = trim($sheetData[$i]['D']);
                                $city_id = trim($sheetData[$i]['E']);
                                $area_ids = trim($sheetData[$i]['F']);
                                $user_type = trim($sheetData[$i]['G']);
                                
                                if (empty($admin_id)) {
                                    echo ' ERROR On Row ' . $i . ' : UniqueNo is NOT provided.<br>';
                                    continue;
                                }
                                
                                if (empty($name)) {
                                    echo ' ERROR On Row ' . $i . ' : Name is NOT provided.<br>';
                                    continue;
                                }
                                
                                if (empty($phone)) {
                                    echo ' ERROR On Row ' . $i . ' : Phone is NOT provided.<br>';
                                    continue;
                                }
                                 
//                                if (!(preg_match('/^[0-9]{10}+$/', $phone))) {
//                                    echo ' ERROR On Row ' . $i . ' : Invalid Phone Number Entered.(Length must be 10 digits)<br>';
//                                    continue;
//                                }
  
                                $is_email_unique = $this->crud_model->verify_if_unique('admin', 'email = ' . $this->db->escape($email). ' And admin_id !=' . $this->db->escape($admin_id));
                                if (is_array($is_email_unique)) {
                                    echo ' ERROR On Row ' . $i . ' : Email ID Already Exist.<br>';
                                    continue;
                                }

                                $is_phone_unique = $this->crud_model->verify_if_unique('admin', 'phone = ' . $this->db->escape($phone) . ' And admin_id !=' . $this->db->escape($admin_id));
                                if (is_array($is_phone_unique)) {
                                    echo ' ERROR On Row ' . $i . ' : Phone Number Already Exist.<br>';
                                    continue;
                                }

                                if ($user_type != 1 && $user_type != 2 ) {
                                    echo ' ERROR On Row ' . $i . ' : Invalid User Type Entered. Enter 1 for Internal OR 2 for External<br>';
                                    continue;
                                }

                                $user_type = ($user_type == 1) ? 'Internal' : 'External';

                                if(!empty($area_ids)){
                                    $areas =  explode(',',$area_ids);
                                    $areas = array_filter($areas);
                                    $area_ids = json_encode($areas);
                                }else{
                                    $area_ids = '[]';
                                }

                                if(empty($city_id)){
                                   $city_id = 0;
                                }
                                
                                $data_array = array(
                                    'name'=>$name,
                                    'phone'=>$phone,
                                    'address'=>$address,
                                    'city_id'=>$city_id,
                                    'area_ids'=>$area_ids,
                                    'user_type'=>$user_type,
                                    //added by sagar
                                    'update_timestamp'=>time(),
                                    'updated_by' => $_SESSION['admin_id'],
                                );
                      
                                $this->db->where('admin_id', $admin_id);
                                $this->db->update('admin', $data_array);
                                echo 'Row '.$i.' : Delivery boy info Updated Successfully.<br>';
                     
                            }
                            echo '<a href="'. base_url().'admin/updateDeliveryboyInfo">Go Back</a>';
                    }
                    else 
                    {
                            echo ' Unsupported file format.<br><br>';
                            echo '<a href="'. base_url().'admin/updateDeliveryboyInfo">Go Back</a>';
                            exit;
                    }
            }
            else{
                    echo ' Please Select File.<br><br>';
                    echo '<a href="'. base_url().'admin/updateDeliveryboyInfo">Go Back</a>';
                    exit;
            }
            exit;

        }else{
         $page_data['page_name'] = "excel_for_update_delivery_boy";
         $this->load->view( 'back/index', $page_data );
        }
    }
    
    function enquiries( $para1 = "", $para2 = "", $para3 = "" ) {
            if ( ! $this->crud_model->admin_permission( 'enquiry' ) ) {
                    redirect( base_url() . 'index.php/admin' );
            }
            if ( $para1 == 'delete' ) {
                    $this->db->where( 'ticket_id', $para2 );
                    $this->db->delete( 'ticket' );
            } elseif ( $para1 == 'list' ) {
                    $this->load->view( 'back/admin/ticket_list', $page_data );
            }elseif( $para1 == 'list_data'){ 

                    $limit  = $this->input->get( 'limit' );
                    $search = $this->input->get( 'search' );
                    $order  = $this->input->get( 'order' );
                    $offset = $this->input->get( 'offset' );
                    $sort   = $this->input->get( 'sort' );

                    $admin = $this->db->get_where( 'admin', array(
                            'admin_id' => $_SESSION['admin_id']
                    ) )->result_array();

                    if ( $search ) {
                            $this->db->or_like( 'subject', $search, 'both' );
//                                $this->db->or_like( 'from_where', '"id":"'.$search.'', 'both' );
                    }
               
                    $total = $this->db->get( 'ticket' )->num_rows();

                    $this->db->limit( $limit );
                    if ( $sort == '' ) {
                            $sort  = 'ticket_id';
                            $order = 'DESC';
                    }
                    $this->db->order_by( $sort, $order );
                    if ( $search ) {
                            $this->db->or_like( 'subject', $search, 'both' );
//                                $this->db->or_like( 'from_where', '"id":"'.$search.'', 'both' );
                    }
               
                    $products = $this->db->get( 'ticket', $limit, $offset )->result_array();
                    $data = array();
                    foreach ( $products as $row ) {
                            $res = array(
                                'no'        => '',
                                'from'      => '',
                                'name'   => '',
                                'mobile'   => '',
                                'email'   => '',
                                'subject'   => '',
                                'date'      => '',
                                'status'      => '',
                                'assign_to' => '',
                                'options'   => ''
                            );
                            $res['no'] = $row['ticket_id'];

                            $from = json_decode($row['from_where'],true);
                            if($from['type'] == 'user'){
                                $res['from']= "  <a class=\"btn btn-mint btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\" 
                                                    onclick=\"ajax_modal('view_user','" . translate( 'view_profile' ) . "','" . translate( 'successfully_viewed!' ) . "','user_view','" . $from['id'] . "');\" data-original-title=\"View\" data-container=\"body\">
                                                    " . $this->db->get_where('user',array('user_id'=>$from['id']))->row()->first_name . "  
                                                 </a>";
                            }else{
                                $res['from'] = 'admin';
                            }

                            $res['name']  = $row['name'];
                            $res['mobile']  = $row['mobile'];
                            $res['email']  = $row['email'];
                            $res['subject']  = $row['subject'];
                            $num = $this->crud_model->ticket_unread_messages($row['ticket_id'],'admin');
                            if($num > 0){
                                 $res['subject'] .= ' <span class="btn btn-mint btn-xs btn-labeled " style="margin:2px; margin-left:10px;"> New( '.$num.' ) </span>';
                            }

                            $res['date']  = date('d M,Y h:i:s',$row['time']);
                            $res['status'] = $row['view_status'] == 'Closed' ? $row['view_status'] : ' Opened';
                            //added by sagar : START 25-feb  
                            if ( $admin[0]['role'] == 1 ) {
                                $res['assign_to'] = $this->crud_model->get_type_name_by_id( 'admin', $row['telecaller_id'], 'name' );
                            }
                            //added by sagar : END 25-feb  
                            
                            $enquiry_view = $this->crud_model->admin_permission('enquiry_view');
                            $enquiry_delete = $this->crud_model->admin_permission('enquiry_delete');
                            $view_status = $this->crud_model->admin_permission('view_status');

                            //add html for action
                            $action = '';
                            
                            if($enquiry_view) { 
                            $action .= "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\" 
                                            onclick=\"ajax_set_full('view','" . translate( 'view_contact_ticket' ) . "','" . translate( 'successfully_viewed!' ) . "','contact_ticket_view','" . $row['ticket_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                            " . translate( 'view_enquiry' ) . "  
                                         </a>";
                            }
                            
                            if ($view_status) {
                                $action .= "  <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\" 
                                                        onclick=\"ajax_modal('view_status','" . translate('edit_status') . "','" . translate('successfully_Changed!') . "','view_status','" . $row['ticket_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                                        " . translate('change_status') . "  
                                                     </a>";
                            }
                            if ( $enquiry_delete || $admin[0]['role'] == 1 ) {
                            $action .= " <a onclick=\"delete_confirm('" . $row['ticket_id'] . "','" . translate( 'really_want_to_delete_this?' ) . "')\" 
                                            class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                            " . translate( 'delete' ) . "
                                        </a>";
                            }

                            $res['options'] = $action;
                            $data[] = $res;
                    }
                    $result = array(
                            'total' => $total,
                            'rows'  => $data
                    );
                    echo json_encode( $result );

            }elseif ( $para1 == 'reply' ) {
                    $data['message']     = $this->input->post( 'reply' );
                    $data['time']        = time();
                    $data['from_where']  = json_encode( array( 'type' => 'admin', 'id' => '' ) );
                    $data['to_where']    = $this->db->get_where( 'ticket_message', array( 'ticket_id' => $para2 ) )->row()->from_where;
                    $data['ticket_id']   = $para2;
                    $data['view_status'] = json_encode( array( 'user_show' => 'no', 'admin_show' => 'ok' ) );
                    $data['subject']     = $this->db->get_where( 'ticket_message', array( 'ticket_id' => $para2 ) )->row()->subject;
                    // uncomment below code to unlock files upload by admin side -- start 
                    // if ($_FILES["images"]['name'][0] == '') {
                        //     $num_of_files = 0;
                        // } else {
                            //     $num_of_files = count($_FILES["images"]['name']);
                            // }
                            
                    // $data['num_of_files'] = $num_of_files;
                    // uncomment above code to unlock files upload by admin side -- end 
                    $this->db->insert( 'ticket_message', $data );
                    // uncomment below code to unlock files upload by admin side -- start
                    // $last_id = $this->db->insert_id();
                    // foreach ($_FILES['images']['name'] as $index => $uploadedFile) {
                    //     if (!empty($_FILES['images']['name'][$index])) {
                    //         $image_extension = strtolower(pathinfo($_FILES['images']['name'][$index], PATHINFO_EXTENSION));
                    //         // allowed extension jpg, jpeg, png
                    //         if (in_array($image_extension, ALLOWED_EXTENSIONS_FOR_ENQUIRY)) {
                    //             $image_path = 'uploads/enquiries_docs/enquiries_' . $last_id . '_' .$index.'.' . $image_extension;
                    //             // Delete previous image if it exists
                    //             $previous_image_files = glob('uploads/enquiries_docs/enquiries_' . $last_id . '_' .$index.'.*');
                    //             foreach ($previous_image_files as $previous_image_file) {
                    //                 unlink($previous_image_file);
                    //             }
                    //             // Move the uploaded image to the desired path
                    //             move_uploaded_file($_FILES['images']['tmp_name'][$index], $image_path);
                    //         } else {
                    //             echo "File upload is not supported for this type";
                    //             exit;
                    //         }
                    //     }
                    // }
                    // uncomment above code to unlock files upload by admin side -- end
            } elseif ( $para1 == 'view' ) {
                    $page_data['message_data'] = $this->db->get_where( 'ticket', array(
                            'ticket_id' => $para2
                    ) )->result_array();
                    $this->crud_model->ticket_message_viewed( $para2, 'admin' );
                    $page_data['tic'] = $para2;
                    $this->load->view( 'back/admin/ticket_view', $page_data );
            } else if ( $para1 == 'view_user' ) {
                    $page_data['user_data'] = $this->db->get_where( 'user', array(
                            'user_id' => $para2
                    ) )->result_array();
                    $this->load->view( 'back/admin/user_view', $page_data );
            } elseif ( $para1 == 'reply_form' ) {
                    $page_data['message_data'] = $this->db->get_where( 'ticket', array(
                            'ticket_id' => $para2
                    ) )->result_array();
                    $this->load->view( 'back/admin/ticket_reply', $page_data );
            } elseif ( $para1 == 'view_status' ) {
                    $page_data['message_data'] = $this->db->get_where( 'ticket', array(
                            'ticket_id' => $para2
                    ) )->result_array();
                    $this->load->view( 'back/admin/view_status', $page_data);
            }elseif ( $para1 == 'status_form' ) {
                $view_status  = $this->input->post( 'view_status' );
                $data = array( 'view_status' => $view_status);
                $this->db->where('ticket_id', $para2);
                $this->db->update('ticket', $data);
            } else {
                    $page_data['page_name'] = "ticket";
                    $page_data['tickets']   = $this->db->get( 'ticket' )->result_array();
                    $this->load->view( 'back/index', $page_data );
            }
	}
        
        function storeSales($para1 = '', $para2 = '') {
            if (!$this->crud_model->admin_permission('sale')) {
                redirect(base_url() . 'index.php/admin');
            }
            if ($_SESSION['role_id'] != 9) {
                redirect(base_url() . 'index.php/admin');
            }
            if ($para1 == 'delete') {
                $carted = $this->db->get_where('stock', array(
                            'sale_id' => $para2
                        ))->result_array();
                foreach ($carted as $row2) {
                    $this->stock('delete', $row2['stock_id']);
                }
                $this->db->where('sale_id', $para2);
                $this->db->delete('sale');
            } elseif ($para1 == 'list') {
                $this->load->view('back/admin/storeSales_list');
            } elseif ($para1 == 'list_data') {
                //Added by sagar :: Pagination Code START  18-01 
                $limit = $this->input->get('limit');
                $search = $this->input->get('search');
                $order = $this->input->get('order');
                $offset = $this->input->get('offset');
                $sort = $this->input->get('sort');
                $admin = $this->db->get_where('admin', array(
                            'admin_id' => $_SESSION['admin_id']
                        ))->result_array();


                $this->db->order_by('sale_datetime', 'desc');
                
                //if($admin[0]['role'] == 9){
                $this->db->like( 'supplier_store_ids', ''.$_SESSION['mapped_store_id'].'', 'both' );
                $this->db->not_like( 'delivery_status', '"status":"delivered"', 'both' );
                $this->db->where('order_status != ','cancelled');
                //}

                if ($search) {
                    $this->db->like('sale_code', $search, 'both');
                }


                $total = $this->db->get('sale')->num_rows();
            
                $admin = $this->db->get_where('admin', array(
                            'admin_id' => $_SESSION['admin_id']
                        ))->result_array();

                $this->db->limit($limit);
                if ($sort == '') {
                    $sort = 'sale_id';
                    $order = 'DESC';
                }
                $this->db->order_by($sort, $order);

                //if($admin[0]['role'] == 9){
                    $this->db->like( 'supplier_store_ids', ''.$_SESSION['mapped_store_id'].'', 'both' );
                    $this->db->not_like( 'delivery_status', '"status":"delivered"', 'both' );
                    $this->db->where('order_status != ','cancelled');
                //}
                if ($search) {
                    $this->db->like('sale_code', $search, 'both');
                }



                $products = $this->db->get('sale', $limit, $offset)->result_array();

                $data = array();
                $login_type = $_SESSION['login_type'];
                $sales_permission = $this->crud_model->admin_permission('sale');
                $sale_invoice_view = $this->crud_model->admin_permission('sale_invoice');
                $sale_cancel_order = $this->crud_model->admin_permission('sale_cancel_order');
                $Sales_Order_Status_Update = $this->crud_model->admin_permission('Sales_Order_Status_Update');
                $sale_assign_store = $this->crud_model->admin_permission('assign_store');
                $sale_assign_delivery = $this->crud_model->admin_permission('assign_delivery');
                $i = 0;

                foreach ($products as $row) {
                    $user_choice = json_decode($row['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];

                    $i++;
                    $res = array(
                        'id' => '',
                        'sale_code' => '',
                        'sale_datetime' => '',
                        'options' => ''
                    );
                    
                    $res['sale_id'] = $row['sale_id'];
                    $res['sale_code'] = '#' . $row['sale_code'];
                    $res['sale_datetime'] = date('d-m-Y', $row['sale_datetime']);

                   
                    //add action 
                    $action = '';
                    if ($sales_permission && $sale_invoice_view) {
                        $action .= "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-file-text\" data-toggle=\"tooltip\" 
                                                        onclick=\"ajax_set_full('view','" . 'Title' . "','" . 'Successfully Edited!!' . "','sales_view','" . $row['sale_id'] . "');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                                    " . 'Invoice' . "  </a>";
                    }

                    $res['options'] = $action;
                    $data[] = $res;
                }
                $result = array(
                    'total' => $total,
                    'rows' => $data
                );
                echo json_encode($result);
                //Added by sagar :: Pagination Code END  18-01       
            } elseif ($para1 == 'view') {
                $data['viewed'] = 'ok';
                $this->db->where('sale_id', $para2);
                $this->db->update('sale', $data);
                $page_data['sale'] = $this->db->get_where('sale', array(
                            'sale_id' => $para2
                        ))->result_array();
                $page_data['mapped_supplier_id']=  $_SESSION['mapped_supplier_id'];
                $this->load->view('back/admin/storeSales_view', $page_data);
                
            } elseif ($para1 == 'total') {
                echo $this->db->get('sale')->num_rows();
            } else {
                $page_data['page_name'] = "storeSales";
                $page_data['all_categories'] = $this->db->get('sale')->result_array();
                $this->load->view('back/index', $page_data);
            }
        }
        
        function billQtyStoreReport($para1 = '', $para2 = ''){
            if (!$this->crud_model->admin_permission('bill_of_qty_store_report') ) {
                redirect(base_url() . 'index.php/admin');
            }
            if ($_SESSION['role_id'] != 9) {
                redirect(base_url() . 'index.php/admin');
            }
            $page_data['page_name'] = "report_bill_of_qty_by_store";
            $this->load->view( 'back/index', $page_data );
        }

        function exportBillQtyStoreReport(){
            if (!$this->crud_model->admin_permission('bill_of_qty_store_report') ) {
                redirect(base_url() . 'index.php/admin');
            }
            if ( $_SESSION['role_id'] != 9 ) {
                redirect(base_url() . 'index.php/admin');
            }
            
            $daterange              = date( 'm/d/Y' ) . ' - ' . date( 'm/d/Y' );
            if ( ! empty( $_POST['daterange'] ) ) {
                    $daterange = $_POST['daterange'];
            }
            
            $supplier_id = $_SESSION['mapped_supplier_id'];
            $supplier_store_id = $_SESSION['mapped_store_id'];
            $data = $this->crud_model->getBillOfQtyStoreData($daterange,$supplier_id,$supplier_store_id);
            
            $this->load->library('Excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("Mypcot Infotech")
            ->setLastModifiedBy("admin")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Doc")
            ->setDescription("trolley_bill_of_qty_store_report")
            ->setKeywords("office 2007 ")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);



            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Bill of qty store report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 16,
                            ));

            $date = explode( ' - ', $daterange );
            $start_time = strtotime($date[0]);
            $end_time = strtotime($date[1]);
            $Titleheading ='Trolley Bill Of Quantity Store Report ('.date('d/m/Y',$start_time);
            if(date('d/m/Y',$start_time)  != date('d/m/Y',$end_time)){
                $Titleheading .= ' - '.date('d/m/Y',$end_time);
            }
            $Titleheading .=')';

        //PRINTING HEADING ON EXCEL FILE   : START
            $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:P1")->getStyle("A1:P1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:P1")->getStyle("A1:P1")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );

            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'PRODUCT NAME (EN)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'PRODUCT NAME (AR)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'PRODUCT CODE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'SKU CODE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'UNIT PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'CATEGORY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'SUBCATEGORY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'BRAND', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'UNIT', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'TOTAL QTY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'TOTAL UNIT PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L2", 'SALE DATETIME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M2", 'DELIVERY DATE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N2", 'DELIVERY TIMESLOT', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("O2", 'SUPPLIER NAME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("P2", 'STORE NAME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle("A2:P2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A2:P2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A2:P2")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A2:P2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->getStyle("A2:P2")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                            );


            if(is_array($data) && !empty($data) && count($data) > 0)
            {
                //PRINTING DATA ON SHEET
                $lastval=0;
                for($j=3,$k=0 ; $k<count($data); $k++){
    //                $saledate =  date('d M,Y h:i A',$data[$k]['sale_datetime']);
                    $saledate =  date('Y-m-d',strtotime($data[$k]['created_on']));
                    $delivery_date_timeslot = json_decode($data[$k]['delivery_date_timeslot'],true);
                    $delivery_date =  (isset($delivery_date_timeslot[0]['date'])) ? $delivery_date_timeslot[0]['date'] : "";
                    $delivery_timeslot =  (isset($delivery_date_timeslot[0]['timeslot'])) ? $delivery_date_timeslot[0]['timeslot'] : "";

                    //CONVERSION RATE FROM SALE ENTRY 
                    $user_choice = json_decode($data[$k]['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                    $unit_price = get_converted_currency($data[$k]['unit_price'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                    //CONVERSION RATE FROM SALE ENTRY 

                    $cart_product_data = json_decode($data[$k]['cart_product_data'],true);
                    $unit_weight = $cart_product_data['weight'];
                    
                    $total_unit_price = ($data[$k]['unit_price'] * $data[$k]['sum_of_qty']);
                    $total_unit_price = get_converted_currency($total_unit_price,DEFAULT_CURRENCY,$sale_currency_conversion_rate);

                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$data[$k]['product_name_en']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$data[$k]['product_name_ar']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",$data[$k]['product_code']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j",$data[$k]['sku_code']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",$unit_price );
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j",$data[$k]['category_name']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j",$data[$k]['sub_category_name']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j",$data[$k]['brand_name']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j",$unit_weight);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j",$data[$k]['sum_of_qty']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j",$total_unit_price);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j",$saledate);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j",$delivery_date);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j",$delivery_timeslot);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("O$j",$data[$k]['supplier_name']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("P$j",$data[$k]['store_name']);
                    $j++;
                    $lastval=$j;

                }
            }
            else{
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("D3", 'NO DATA FOUND');
            }



            $file_name = 'Trolley_bill_of_qty_store_report';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
        
        function totalOrderReport($para1 = '', $para2 = ''){
	    if ( ! $this->crud_model->admin_permission( 'day_sale_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $page_data['page_name'] = "report_total_orders";
            $this->load->view( 'back/index', $page_data );
        }
        
        function exportTotalOrderReport(){
            if ( ! $this->crud_model->admin_permission( 'day_sale_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $daterange              = date( 'm/d/Y' ) . ' - ' . date( 'm/d/Y' );
            if ( ! empty( $_POST['daterange'] ) ) {
                    $daterange = $_POST['daterange'];
            }
           
            $data = $this->crud_model->getTotalOrdersReportData($daterange);

            $this->load->library('Excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("Mypcot Infotech")
            ->setLastModifiedBy("admin")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Doc")
            ->setDescription("trolley_total_order_date_range_report")
            ->setKeywords("office 2007 ")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('50');
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('total orders date range report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 16,
                            ));

            $date = explode( ' - ', $daterange );
            $start_time = strtotime($date[0]);
            $end_time = strtotime($date[1]);
            $Titleheading ='Trolley Total Orders Date Range Report ('.date('d/m/Y',$start_time);
            if(date('d/m/Y',$start_time)  != date('d/m/Y',$end_time)){
                $Titleheading .= ' - '.date('d/m/Y',$end_time);
            }
            $Titleheading .=')';

        //PRINTING HEADING ON EXCEL FILE   : START
            $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:T1")->getStyle("A1:T1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:T1")->getStyle("A1:T1")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );

            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'SALE ID', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'SALE CODE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'USER DETAILS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'NO. OF ITEMS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'PAYMENT CURRENCY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'TOTAL PRICE ($)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'TOTAL PRICE (SDG)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'TOTAL AMOUNT OF E-PAYMENT ($)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'DELIVERY STATUS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'PAYMENT STATUS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'PAYMENT TYPE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L2", 'SALE DATETIME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M2", 'COUNTRY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N2", 'CITY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("O2", 'AREA', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("P2", 'SUPPLIER', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("Q2", 'STORE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("R2", 'ORDER CANCEL COMMENT', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("S2", 'DELIVERY BOY REMARK', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("T2", 'DATE OF LAST ORDER', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle("A2:T2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A2:T2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A2:T2")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A2:T2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->getStyle("A2:T2")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                            );


            if(is_array($data) && !empty($data) && count($data) > 0)
            {
                //PRINTING DATA ON SHEET
                $lastval=0;
                for($j=3,$k=0 ; $k<count($data); $k++){
                    
                    $saleId =  $data[$k]['sale_id'];
                    $saledate =  date('Y-m-d H:i:s',$data[$k]['sale_datetime']);
                    $display_currency = DEFAULT_CURRENCY_NAME;
                    //CONVERSION RATE FROM SALE ENTRY 
                    $user_choice = json_decode($data[$k]['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                    $unit_price = get_converted_currency($data[$k]['unit_price'],DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                    //CONVERSION RATE FROM SALE ENTRY 
                    
                    
                    $userSex =  $data[$k]['sex'];
                    $userDetails = "Name : ".$data[$k]['first_name']."(".$userSex.")";
                    //FROM SALES --
                    $shipping_address = json_decode($data[$k]['shipping_address'],true);
                    $userDetails .= PHP_EOL."Phone : ".$shipping_address['phone_number'];
                    $location = explode(',',$shipping_address['langlat']);
                    if(isset($location[0]) && isset($location[1]) && !empty($location[0]) && !empty($location[1])){
                    $userDetails .= PHP_EOL."Location : ".$location[1]. ','.$location[0];
                    }
                    $city = $shipping_address['city']; 
                    $area = $shipping_address['area']; 
                    //FROM SALES --
                    
                    $delivery_status_array = json_decode($data[$k]['delivery_status'],true);
                    $payment_status_array = json_decode($data[$k]['payment_status'],true);
                    $delivery_status = $delivery_status_array[0]['status'];
                    //added by sagar -- delivery boy comment
                    $delivery_comment = $delivery_status_array[0]['comment'];
                    if(strcasecmp($delivery_comment, 'pending to process by auto cron') == 0){
                        $delivery_comment = "";
                    }
                    $payment_status = $payment_status_array[0]['status'];
                    
                    $user_choice_array = json_decode($data[$k]['user_choice'],true);
                    $currency_code = $user_choice_array[0]['currency_code'];
                    if($currency_code == 1){
                        $display_currency = 'USD';
                    }
                    
                    $product_details = json_decode($data[$k]['product_details'],true);
                    $prdt_count = count($product_details);
                    
                    $supplier_ids = explode(',',$data[$k]['supplier_ids']);
                    $supplier_store_ids = explode(',',$data[$k]['supplier_store_ids']);
                    $store_name = "";
                    $supplier_name = "";
                    if(is_array($supplier_ids) && !empty($supplier_ids[0])){
                        foreach($supplier_ids as $kk => $vv){
                            $supplier_name .=  $this->crud_model->get_type_name_by_id('supplier', $vv, 'supplier_name') .',';
                            if(is_array($supplier_store_ids) && !empty($supplier_store_ids[0])){
                                $store_name .= $this->crud_model->get_type_name_by_id('supplier_store', $supplier_store_ids[$kk], 'store_name').',';
                            }
                        }
                    }
                    $supplier_name = rtrim($supplier_name, ',');
                    $store_name = rtrim($store_name, ',');
                    
                    $total_order_price_in_doller = $data[$k]['grand_total'];
                    $total_order_price_in_sdg = get_converted_currency($total_order_price_in_doller,DEFAULT_CURRENCY,$sale_currency_conversion_rate);

                    //Last order date
                    $lastOrderDate = $this->crud_model->customerLastOrderDate($data[$k]['buyer'],$saleId);
                    
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$data[$k]['sale_id']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$data[$k]['sale_code']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",$userDetails);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j",$prdt_count);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",$display_currency);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j",$total_order_price_in_doller);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j",$total_order_price_in_sdg);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j","");
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j",$delivery_status);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j",$payment_status);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j",$data[$k]['payment_type']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j",$saledate);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j","");
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j",$city);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("O$j",$area);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("P$j",$supplier_name);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("Q$j",$store_name);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("R$j",$data[$k]['order_cancel_comment']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("S$j",$delivery_comment);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("T$j",$lastOrderDate);
                    $j++;
                    $lastval=$j;

                }
            }
            else{
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("D3", 'NO DATA FOUND');
            }



            $file_name = 'Trolley_total_orders_date_range_report';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
        
        function financialReport($para1 = '', $para2 = ''){
	if ( ! $this->crud_model->admin_permission( 'financial_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $page_data['page_name'] = "report_for_financial";
            $this->load->view( 'back/index', $page_data );
        }
        
        function exportFinancialReport(){
            if ( ! $this->crud_model->admin_permission( 'financial_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $daterange              = date( 'm/d/Y' ) . ' - ' . date( 'm/d/Y' );
            if ( ! empty( $_POST['daterange'] ) ) {
                    $daterange = $_POST['daterange'];
            }
           
            $data = $this->crud_model->getFinancialReportData($daterange);
            
            $this->load->library('Excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("Mypcot Infotech")
            ->setLastModifiedBy("admin")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Doc")
            ->setDescription("trolley_financial_date_range_report")
            ->setKeywords("office 2007 ")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('financial date range report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 16,
                            ));

            $date = explode( ' - ', $daterange );
            $start_time = strtotime($date[0]);
            $end_time = strtotime($date[1]);
            $Titleheading ='Trolley Financial Date Range Report ('.date('d/m/Y',$start_time);
            if(date('d/m/Y',$start_time)  != date('d/m/Y',$end_time)){
                $Titleheading .= ' - '.date('d/m/Y',$end_time);
            }
            $Titleheading .=')';

        //PRINTING HEADING ON EXCEL FILE   : START
            $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:S1")->getStyle("A1:S1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:S1")->getStyle("A1:S1")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );

            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'ORDER ID', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'CUSTOMER NAME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'PHONE NUMBER', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'NO. OF ITEMS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'ITEMS PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'SERVICE FEES ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'DELIVERY FEES ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'TOTAL ORDER AMOUNT ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'PAYMENT CURRENCY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'PAYMENT TYPE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'PAYMENT STATUS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L2", 'ORDER STATUS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M2", 'SALE DATETIME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N2", 'COUNTRY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("O2", 'CITY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("P2", 'AREA', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("Q2", 'DELIVERY DATE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("R2", 'DELIVERY TIMESLOT', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("S2", 'DELIVERY BOY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle("A2:S2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A2:S2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A2:S2")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A2:S2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->getStyle("A2:S2")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                            );


            if(is_array($data) && !empty($data) && count($data) > 0)
            {
                //PRINTING DATA ON SHEET
                $lastval=0;
                for($j=3,$k=0 ; $k<count($data); $k++){
                    $saledate =  date('Y-m-d',$data[$k]['sale_datetime']);
                    //CONVERSION RATE FROM SALE ENTRY 
                    $user_choice = json_decode($data[$k]['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
//                    $unit_price = get_converted_currency($data[$k]['unit_price'],2,$sale_currency_conversion_rate);
                    //CONVERSION RATE FROM SALE ENTRY 
                    
                    //FROM SALES --
                    $customer_name = $data[$k]['first_name'];
                    $shipping_address = json_decode($data[$k]['shipping_address'],true);
                    $customer_phone = $shipping_address['phone_number'];
                    $city = $shipping_address['city']; 
                    $area = $shipping_address['area']; 
                    //FROM SALES --
                    
                    $delivery_status_array = json_decode($data[$k]['delivery_status'],true);
                    $payment_status_array = json_decode($data[$k]['payment_status'],true);
                    $delivery_status = $delivery_status_array[0]['status'];
                    $payment_status = $payment_status_array[0]['status'];

                    $product_details = json_decode($data[$k]['product_details'],true);
                    $prdt_count = count($product_details);
                    
                    $service_fees_in_doller =  $data[$k]['vat'];
                    $service_fees_in_sdg = get_converted_currency($service_fees_in_doller,DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                    $delivery_fees_in_doller =  $data[$k]['delivery_charge'];
                    $delivery_fees_in_sdg = get_converted_currency($delivery_fees_in_doller,DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                    
                    $total_order_price_in_doller = $data[$k]['grand_total'];
                    $total_order_price_in_sdg = get_converted_currency($total_order_price_in_doller,DEFAULT_CURRENCY,$sale_currency_conversion_rate);

                    $subtotal_amount_in_doller =  $total_order_price_in_doller - $service_fees_in_doller - $delivery_fees_in_doller;
                    $subtotal_amount_in_sdg = get_converted_currency($subtotal_amount_in_doller,DEFAULT_CURRENCY,$sale_currency_conversion_rate);
                    
                    $delivery_date_timeslot = json_decode($data[$k]['delivery_date_timeslot'],true);
                    $delivery_date =  (isset($delivery_date_timeslot[0]['date'])) ? $delivery_date_timeslot[0]['date'] : "";
                    $delivery_timeslot =  (isset($delivery_date_timeslot[0]['timeslot'])) ? $delivery_date_timeslot[0]['timeslot'] : "";
                    
                    $delivery_boy_details = json_decode($data[$k]['assign_delivery_data'],true);
                    $delivery_boy = "";
                    if(is_array($delivery_boy_details) && !empty($delivery_boy_details['name'])){
                        $delivery_boy = $delivery_boy_details['name'];
                    }
                    
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$data[$k]['sale_code']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$customer_name);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",$customer_phone);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j",$prdt_count,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",$subtotal_amount_in_sdg,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j",$service_fees_in_sdg,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j",$delivery_fees_in_sdg,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j",$total_order_price_in_sdg,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j",DEFAULT_CURRENCY_NAME);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j",$data[$k]['payment_type']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j",$payment_status);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j",$delivery_status);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j",$saledate);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j","");
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("O$j",$city);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("P$j",$area);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("Q$j",$delivery_date);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("R$j",$delivery_timeslot);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("S$j",$delivery_boy);
                    $j++;
                    $lastval=$j;

                }
            }
            else{
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("D3", 'NO DATA FOUND');
            }



            $file_name = 'Trolley_financial_date_range_report';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
        
        function userOrdersReport($para1 = '', $para2 = ''){
	if ( ! $this->crud_model->admin_permission( 'customer_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $page_data['page_name'] = "report_for_user_orders";
            $this->load->view( 'back/index', $page_data );
        }
        
        function exportUserOrdersReport(){
	    if ( ! $this->crud_model->admin_permission( 'customer_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $daterange              = date( 'm/d/Y' ) . ' - ' . date( 'm/d/Y' );
            if ( ! empty( $_POST['daterange'] ) ) {
                    $daterange = $_POST['daterange'];
            }
           
            $data = $this->crud_model->getUserOrdersReportData($daterange);
         
            $this->load->library('Excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("Mypcot Infotech")
            ->setLastModifiedBy("admin")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Doc")
            ->setDescription("trolley_financial_date_range_report")
            ->setKeywords("office 2007 ")
            ->setCategory("Export Excel");

            
            $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('50');
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('User orders date range report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 16,
                            ));

            $date = explode( ' - ', $daterange );
            $start_time = strtotime($date[0]);
            $end_time = strtotime($date[1]);
            $Titleheading ='Trolley Customer Orders Date Range Report ('.date('d/m/Y',$start_time);
            if(date('d/m/Y',$start_time)  != date('d/m/Y',$end_time)){
                $Titleheading .= ' - '.date('d/m/Y',$end_time);
            }
            $Titleheading .=')';

        //PRINTING HEADING ON EXCEL FILE   : START
            $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:N1")->getStyle("A1:N1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:N1")->getStyle("A1:N1")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );

            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'USER DETAILS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'NO. OF ORDERS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'NO. OF ITEMS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'PAYMENT CURRENCY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'TOTAL PRICE ($)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'TOTAL PRICE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'TOTAL AMOUNT OF E-PAYMENT ($)', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'NO. OF DELIVERED ORDERS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'NO. OF CANCELLED ORDERS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'NO. OF PENDING ORDERS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'NO. OF PROCESS ORDERS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L2", 'COUNTRY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M2", 'CITY', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N2", 'AREA', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->getStyle("A2:N2")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                            );


            if(is_array($data) && !empty($data) && count($data) > 0)
            {
                //PRINTING DATA ON SHEET
                $lastval=0;
                for($j=3,$k=0 ; $k<count($data); $k++){
               
                    $userDetails = "Name : ".$data[$k]['first_name'];
                    $userDetails .= PHP_EOL."Phone : ".$data[$k]['phone'];
                   
                    $sale_product_details = $this->crud_model->get_data( 'sale' ,' sale_id  IN (  ' . $data[$k]['sale_ids'] . ' ) ','sale_id,product_details' );
                    $no_of_items = 0;
                    foreach($sale_product_details as $kk => $vv){
                        $product_details = json_decode($vv['product_details'],true);
                        $prdt_count = count($product_details);
                        $no_of_items += $prdt_count;
                    }
                    
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$userDetails);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$data[$k]['no_of_orders']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",$no_of_items);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j",DEFAULT_CURRENCY_NAME);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",$data[$k]['amount_usd']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j",$data[$k]['amount_sdg']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j","");
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j",$data[$k]['delivered_orders']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j",$data[$k]['cancelled_orders']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j",$data[$k]['pending_orders']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j",$data[$k]['process_orders']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j","");
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j",$data[$k]['city_name_en']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j",$data[$k]['area_name_en']);
                    $j++;
                    $lastval=$j;

                }
            }
            else{
                 $objPHPExcel->getActiveSheet()->setCellValueExplicit("D3", 'NO DATA FOUND');
            }



            $file_name = 'Trolley_customer_orders_date_range_report';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }
        
        //added by rushikesh - 24-07-2020 - START
        //customer delivery address report controller start:by rushikesh
        function customerAddressReport($para1='',$para2=''){
            if ( ! $this->crud_model->admin_permission( 'customer_delivery_address_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $page_data['page_name'] = "customer_delivery_address_report";
            $this->load->view( 'back/index', $page_data );
        }
    
        function exportCustomerAddressReport(){
            $user_type = "";
            $user_status = "";
            if(!empty($_POST['user_type'])){
                $user_type = $_POST['user_type'];
            }
            if(!empty($_POST['user_status'])){
                $user_status = $_POST['user_status'];
            }
            $daterange              = date( 'm/d/Y' ) . ' - ' . date( 'm/d/Y' );
            if ( ! empty( $_POST['daterange'] ) ) {
                    $daterange = $_POST['daterange'];
            }
           
            
            
            $data = $this->crud_model->getCustomerAddressData($daterange,$user_type,$user_status);

            $this->load->library('Excel');

            //Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            //Set properties
            $objPHPExcel->getProperties()->setCreator("MypcotInfotech")
            ->setLastModifiedBy("admin")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX customer List Doc")
            ->setDescription("Admin panel customer details")
            ->setKeywords("office 2007 mypcot trolley php")
            ->setCategory("Export Excel");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('30');
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('30');
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('20');            
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('30');            
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('20');            
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth('20');            
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth('20');            
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth('20');            
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth('20');
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth('20');
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth('20');
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth('20');
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth('20');
            
            $objPHPExcel->setActiveSheetIndex(0);

            $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 16,
                            ));

            $date = explode( ' - ', $daterange );
            $start_time = strtotime($date[0]);
            $end_time = strtotime($date[1]);
            $Titleheading ='Trolley Customer Delivery Address Date Range Report ('.date('d/m/Y',$start_time);
            if(date('d/m/Y',$start_time)  != date('d/m/Y',$end_time)){
                $Titleheading .= ' - '.date('d/m/Y',$end_time);
            }
            $Titleheading .=')';

        //PRINTING HEADING ON EXCEL FILE   : START
            $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:T1")->getStyle("A1:T1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:T1")->getStyle("A1:T1")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );
            $objPHPExcel->getActiveSheet()->setTitle('Address Report');
            
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'Name', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'Del Title', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'Del Phone No', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'Del City', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'Del Area', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'Del Location', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'Reg Phone No', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'Reg email', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'Reg City', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'Reg Area', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'Job Type', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L2", 'Social Status', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M2", 'Registration Date', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N2", 'Registration Day', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("O2", 'Wallet Number', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("P2", 'Current Wallet Balance', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("Q2", 'Wallet Type', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("R2", 'Total Orders', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("S2", 'Last Order Month', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("T2", 'Last Order Date', PHPExcel_Cell_DataType::TYPE_STRING);
            
            $objPHPExcel->getActiveSheet()->getStyle("A2:T2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A2:T2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A2:T2")->getFill()->getStartColor()->setRGB('FFFF00');
            //$objPHPExcel->getActiveSheet()->getStyle("A1:T1")->getFill()->getStartColor()->setRGB('#ff8000');
            
            $objPHPExcel->getActiveSheet()->getStyle("A2:T2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle("A2:T2")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            
            $objPHPExcel->getActiveSheet()->getStyle('A2:T2')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
             if(is_array($data) && !empty($data) && count($data) > 0)
               {
                    for($j=3,$k=0 ; $k<count($data); $k++){

            $customeraddress = $this->crud_model->getCustomerAddress($data[$k]['user_id']);
                      
                    $finalAddress=array();
                    $i=0;
                 if(is_array($customeraddress) && !empty($customeraddress[0])) {
                    foreach($customeraddress as $key => $value) {
                        $tempArr = array (
                           'title' => $value['title'],
                           'number' => $value['number'],
                           'city_name_en' => $value['city_name_en'],
                           'area_name_en' => $value['area_name_en'],
                           'langlat' => $value['langlat'],
                        );
                        array_push($finalAddress,$tempArr);
                    }
                } 

                        $username =  $data[$k]['first_name'];
                        if(!empty($data[$k]['fourth_name'])){
                           $username .= ' '.$data[$k]['fourth_name'];
                        }

                       $creation_date=date('d/m/Y',$data[$k]['creation_date']);
                       $creation_day=date('l',$data[$k]['creation_date']);
                       $phone_no = '249'.$data[$k]['phone'];
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$username);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j", $phone_no);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j", $data[$k]['email']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j", $data[$k]['city_name_en']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j", $data[$k]['area_name_en']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j", $data[$k]['job_type']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$j", $data[$k]['social_status']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$j",$creation_date);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$j",$creation_day);
                       //$objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j", $data[$k]['langlat']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("O$j", $data[$k]['wallet_no']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("P$j", $data[$k]['wallet_balance']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("Q$j", $data[$k]['wallet_type']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("R$j",$data[$k]['no_of_orders'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("S$j",$data[$k]['last_order_month']);
                       $objPHPExcel->getActiveSheet()->setCellValueExplicit("T$j",$data[$k]['last_order_date']);
                         $addindex= $j; 
                  
                         if(is_array($finalAddress)){
                            foreach ($finalAddress as $value) {

                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$addindex",$username);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$addindex",$value['title']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$addindex",$value['number']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$addindex",$value['city_name_en']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$addindex",$value['area_name_en']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$addindex",$value['langlat']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$addindex", $phone_no);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$addindex", $data[$k]['email']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$addindex", $data[$k]['city_name_en']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$addindex", $data[$k]['area_name_en']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$addindex", $data[$k]['job_type']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L$addindex", $data[$k]['social_status']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M$addindex",$creation_date);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N$addindex",$creation_day);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("O$addindex", $data[$k]['wallet_no']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("P$addindex", $data[$k]['wallet_balance']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("Q$addindex", $data[$k]['wallet_type']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("R$addindex",$data[$k]['no_of_orders'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("S$addindex",$data[$k]['last_order_month']);
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("T$addindex",$data[$k]['last_order_date']);
                            $addindex++;
                            }
                        }else{
                        }
                        if(is_array($finalAddress) && count($finalAddress) > 0){
                            $addindex =  $addindex-1;
                        }
                    $j =  $addindex;
                       
                    $j++;
                    } 
               }
            // Redirect output to a client's web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Trolley_Customer_Delivery_Address_Date_Range_Report.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
        //end

        //total_order_revenue_report controller start:by rushikesh
        function totalRevenueReport($para1 = '', $para2 = ''){
            if ( ! $this->crud_model->admin_permission( 'total_order_revenue_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $page_data['page_name'] = "total_order_revenue_report";
            $this->load->view( 'back/index', $page_data );
        }
        
        function exportTotalRevenueReport(){
            if ( ! $this->crud_model->admin_permission( 'total_order_revenue_report' )  ) {
                redirect( base_url() . 'index.php/admin' );
            }
            $daterange              = date( 'm/d/Y' ) . ' - ' . date( 'm/d/Y' );
            if ( ! empty( $_POST['daterange'] ) ) {
                    $daterange = $_POST['daterange'];
            }
           


             $data = $this->crud_model->getTotalRevenueReportData($daterange);

            $this->load->library('Excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("Mypcot Infotech")
            ->setLastModifiedBy("admin")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Doc")
            ->setDescription("trolley_revenue_date_range_report")
            ->setKeywords("office 2007 ")
            ->setCategory("Export Excel");

            
            $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            //$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('40');
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Revenue report');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 16,
                            ));

            $date = explode( ' - ', $daterange );
            $start_time = strtotime($date[0]);
            $end_time = strtotime($date[1]);
            $Titleheading ='Trolley Total Orders Revenue Date Range Report ('.date('d/m/Y',$start_time);
            if(date('d/m/Y',$start_time)  != date('d/m/Y',$end_time)){
                $Titleheading .= ' - '.date('d/m/Y',$end_time);
            }
            $Titleheading .=')';

        //PRINTING HEADING ON EXCEL FILE   : START
            $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:G1")->getStyle("A1:G1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->mergeCells("A1:G1")->getStyle("A1:G1")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );

            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'DATE', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'DAY NAME', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'NO. OF ORDERS', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'NO. OF ORDERS %', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'REVENUE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'REVENUE ('.DEFAULT_CURRENCY_NAME.')', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'REVENUE %', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle("A2:G2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A2:G2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A2:G2")->getFill()->getStartColor()->setRGB('FFFF00');
            $objPHPExcel->getActiveSheet()->getStyle("A2:G2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $objPHPExcel->getActiveSheet()->getStyle("A2:G2")->getAlignment()->applyFromArray(
                                                                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                            );

            $date_range = explode( ' - ', $_POST['daterange'] );
            $end= new DateTime($date_range[1]);
            $end->modify('+1 day');
               $period = new DatePeriod(
                    new DateTime($date_range[0]),
                    new DateInterval('P1D'),
                    $end
               );

            $dateRange = array();      
            foreach ($period as $key => $value) {
                $date['date']=$value->format('d/m/Y'); 
            $date['day']=$value->format('l'); 
                array_push($dateRange,$date);           

            }
            


            $order_percentage= 0;
            $revenue_percentage= 0;
            if(is_array($data) && !empty($data)){
                for($k=0 ; $k<count($data); $k++){
                    $order_percentage += $data[$k]['no_of_orders'];
                    $revenue_percentage += $data[$k]['amount_sdg'];
                }
            }


            $finaldata= array_merge_recursive($dateRange,$data);

            for($j=3, $k=0; $k< count($dateRange); $k++){
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$dateRange[$k]['date']);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$dateRange[$k]['day']); 
                
                $key = array_search($dateRange[$k]['date'], array_column($data, 'date'));

                if($key!== false){
                    $no_of_orders =  $data[$key]['no_of_orders'];
                    $order_percent = ( $data[$key]['no_of_orders']/ $order_percentage ) * 100;
                    $amount_usd =  $data[$key]['amount_usd'];
                    $amount_sdg =  $data[$key]['amount_sdg'];
                    $revenue_percent = ( $data[$key]['amount_sdg'] / $revenue_percentage ) * 100;
                    
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",$data[$key]['no_of_orders'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j",number_format((float)$order_percent, 2, '.', ''),PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",number_format((float)$data[$key]['amount_usd'], 2, '.', ''),PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j",number_format((float)$data[$key]['amount_sdg'], 2, '.', ''),PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j",number_format((float)$revenue_percent, 2, '.', ''),PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    
                    $j++;
                }else{
                    
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j",0,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j",0,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j",0,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j",0,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j",0,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    
                    $j++;
                }
            }    


            $file_name = 'Trolley_Total_Orders_Revenue_Date_Range_Report';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;

        }
        //added by rushikesh - 24-07-2020 - END
        
        //added by sagar : FOR product code update - START
        function updateProductCodes( $para1 = '', $para2 = '' ) {
           if($para1 == 'download'){
               /* Either sample file or existing product code in excel file should be export - not yet done
                $file= 'sampleFile.xlsx';
                $filepath = VIEWPATH."/" . $file;
                if(file_exists($filepath)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($filepath));
                    flush(); // Flush system output buffer
                    readfile($filepath);
                    die();
                }
            */
           }else if($para1 == 'saveProductCode'){
                ini_set('memory_limit', '-1');
		if(isset($_FILES['importproductexcel']['name']) && !empty($_FILES['importproductexcel']['name']))
  		{
			$name = $_FILES['importproductexcel']['name'];
			$names = explode(".", $name);
			$size = $_FILES['importproductexcel']['size'];
			$max_file_size = 1024*1024*2;		// 2 MB
                      
			if ((end($names)=="xls" || end($names)=="XLS" || end($names) == "csv"  || end($names) == "CSV" ||  end($names) == "xlsx"  || end($names) == "XLSX"))
			{
                                //Load the excel library
				$this->load->library('excel');
				
				//Read file from path
				$objPHPExcel = PHPExcel_IOFactory::load($_FILES['importproductexcel']['tmp_name']);
				
				//Get only the Cell Collection
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,false,true);
                                if(is_array($sheetData)){
                                    if(!( isset($sheetData[1]['A']) &&$sheetData[1]['A']=='UniqueNo' 
                                            && isset($sheetData[1]['D']) && $sheetData[1]['D']=='Product Code'
                                            && isset($sheetData[1]['E']) && $sheetData[1]['E']=='SKU Code'
                                            && isset($sheetData[1]['F']) && $sheetData[1]['F']=='is modified?'
                                            )){
      
                                                 echo ' Unsupported file format.<br> Correct Order is :: UniqueNo,Product Code,SKU Code,is modified?<br>';
                                                 echo '<a href="'. base_url().'admin/updateProductCodes">Go Back</a>';
                                                 exit;
                                            }
                                }else{
                                    echo ' Unsupported file format only XLS / CSV files allowed.<br><br>';
                                     echo '<a href="'. base_url().'admin/updateProductCodes">Go Back</a>';
                                    exit;
                                }
                                
				for($i = 2; $i < count($sheetData)+1; $i++)
                                {
                                        
                                    $variation_id = $sheetData[$i]['A'];
                                    $product_id =  $this->db->get_where('variation',array('variation_id'=>$variation_id))->row()->product_id;
                                    $product_data_array['product_code'] = $sheetData[$i]['D'];
                                    $product_data_array['SKU_code']= $data_array['sku_code'] = $sheetData[$i]['E'];
                                    
                                    $is_modified =  $sheetData[$i]['F'];
                                    
                                    $is_modified = ($is_modified == 'YES') ? 'YES' : 'NO';
                                    
                                    if (empty($variation_id)) {
                                        echo ' ERROR On Row ' . $i . ' : Unique Id is NOT provided.<br>';
                                        continue;
                                    }
                                    if (empty($product_data_array['product_code'])) {
                                        echo ' ERROR On Row ' . $i . ' : Product Code is NOT provided.<br>';
                                        continue;
                                    }
                                    if (empty($product_data_array['SKU_code'])) {
                                        echo ' ERROR On Row ' . $i . ' : SKU Code is NOT provided.<br>';
                                        continue;
                                    }
                                    
                                    $is_exist_product_code = $this->crud_model->verify_if_unique('product', 'product_code = ' . $this->db->escape($product_data_array['product_code']).' And product_id !=' . $this->db->escape($product_id));
                                    if (is_array($is_exist_product_code)) {
                                        echo ' ERROR On Row ' . $i . ' : Product Code Already Exist.<br>';
                                        continue;
                                    }
                                    
                                    $is_exist_sku_code = $this->crud_model->verify_if_unique('product', 'SKU_code = ' . $this->db->escape($product_data_array['SKU_code']).' And product_id !=' . $this->db->escape($product_id));
                                    if (is_array($is_exist_sku_code)) {
                                        echo ' ERROR On Row ' . $i . ' : SKU Code Already Exist.<br>';
                                        continue;
                                    }
                                    
                                    if($is_modified == 'YES') {
                                        //Variation  update
                                        $this->db->where('variation_id', $variation_id);
                                        $this->db->update('variation', $data_array);
                                        //Product  update
                                        $this->db->where('product_id', $product_id);
                                        $this->db->update('product', $product_data_array);
                                     
                                        echo 'Row '.$i.' is Product Codes Updated Successfully.<br>';
                                    }else{
                                        echo 'Row '.$i.' : No change found<br>';
                                    }
                                }
                                echo '<a href="'. base_url().'admin/updateProductCodes">Go Back</a>';
			}
			else 
			{
				echo ' Unsupported file format.<br><br>';
                                echo '<a href="'. base_url().'admin/updateProductCodes">Go Back</a>';
                                exit;
			}
		}
		else{
			echo ' Please Select File.<br><br>';
                        echo '<a href="'. base_url().'admin/updateProductCodes">Go Back</a>';
                        exit;
		}
		exit;
               
           }else{
            $page_data['page_name'] = "excel_for_product_code_update";
            $this->load->view( 'back/index', $page_data );
           }
    }
     //added by sagar : FOR product code update - END
        
    //added by sagar : Customer Wallet Report  - START - 01-08-2020
    function customerWalletReport($para1='',$para2=''){
        if ( ! $this->crud_model->admin_permission( 'customer_wallet_report' )  ) { 
            redirect( base_url() . 'index.php/admin' );
        }
        $page_data['page_name'] = "customer_wallet_report";
        $this->load->view( 'back/index', $page_data );
    }
        
    function exportCustomerWalletReport(){
        $daterange              = date( 'm/d/Y' ) . ' - ' . date( 'm/d/Y' );
        if ( ! empty( $_POST['daterange'] ) ) {
                $daterange = $_POST['daterange'];
        }
        $user_id = "";
        if(!empty($_POST['user'])){
            $user_id = $_POST['user'];
        }

        $data = $this->crud_model->getCustomerWalletData($daterange,$user_id);
      
        $this->load->library('Excel');

        //Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        //Set properties
        $objPHPExcel->getProperties()->setCreator("MypcotInfotech")
        ->setLastModifiedBy("admin")
        ->setTitle("Office 2007 XLSX Document")
        ->setSubject("Office 2007 XLSX customer wallet List Doc")
        ->setDescription("Admin panel customer wallet details")
        ->setKeywords("office 2007 mypcot trolley php")
        ->setCategory("Export Excel");

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true); 

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Customer Wallet report');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $styleArray = array(
                        'font'  => array(
                            'bold'  => true,
                            'size'  => 16,
                        ));

        $date = explode( ' - ', $daterange );
        $start_time = strtotime($date[0]);
        $end_time = strtotime($date[1]);
        $Titleheading ='Trolley Customer Wallet Date Range Report ('.date('d/m/Y',$start_time);
        if(date('d/m/Y',$start_time)  != date('d/m/Y',$end_time)){
            $Titleheading .= ' - '.date('d/m/Y',$end_time);
        }
        $Titleheading .=')';

        //PRINTING HEADING ON EXCEL FILE   : START
        $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($Titleheading);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->mergeCells("A1:K1")->getStyle("A1:K1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $objPHPExcel->getActiveSheet()->mergeCells("A1:K1")->getStyle("A1:K1")->getAlignment()->applyFromArray(
                                                                                        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                                                                                        );
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("A2", 'Customer ID', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("B2", 'Customer Name', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("C2", 'Wallet Number', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("D2", 'Wallet Type', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("E2", 'Amount', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("F2", 'Wallet Balance', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("G2", 'Type', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("H2", 'Date', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("I2", 'Remark', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("J2", 'Admin ID', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("K2", 'Admin Name', PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->getStyle("A2:K2")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A2:K2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle("A2:K2")->getFill()->getStartColor()->setRGB('FFFF00');
        $objPHPExcel->getActiveSheet()->getStyle("A2:K2")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
        //$objPHPExcel->getActiveSheet()->getStyle('A1:C20')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
         if(is_array($data) && !empty($data) && count($data) > 0)
           {
                for($j=3,$k=0 ; $k<count($data); $k++){
                   $username =  $data[$k]['first_name'];
                   if(!empty($data[$k]['fourth_name'])){
                       $username .= ' '.$data[$k]['fourth_name'];
                   }
                   
                   $objPHPExcel->getActiveSheet()->setCellValueExplicit("A$j",$data[$k]['user_id'],PHPExcel_Cell_DataType::TYPE_NUMERIC );
                   $objPHPExcel->getActiveSheet()->setCellValueExplicit("B$j",$username);
                   $objPHPExcel->getActiveSheet()->setCellValueExplicit("C$j", $data[$k]['wallet_no'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                   $objPHPExcel->getActiveSheet()->setCellValueExplicit("D$j", $data[$k]['wallet_type']);
                   $objPHPExcel->getActiveSheet()->setCellValueExplicit("E$j", $data[$k]['amount'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                   $objPHPExcel->getActiveSheet()->setCellValueExplicit("F$j", $data[$k]['wallet_balance'],PHPExcel_Cell_DataType::TYPE_NUMERIC );
                   $objPHPExcel->getActiveSheet()->setCellValueExplicit("G$j", $data[$k]['type']);
                   $objPHPExcel->getActiveSheet()->setCellValueExplicit("H$j", date('d-m-Y H:i:s',strtotime($data[$k]['date_time'])));
                   $objPHPExcel->getActiveSheet()->setCellValueExplicit("I$j", $data[$k]['reason']);
                   $objPHPExcel->getActiveSheet()->setCellValueExplicit("J$j", $data[$k]['admin_id'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                   $objPHPExcel->getActiveSheet()->setCellValueExplicit("K$j", $data[$k]['name']);
                   $j++;
                }

           }else{
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("E3", 'NO DATA FOUND');
           }
           $file_name = 'trolley_Customer_wallet_report';
            // Redirect output to a client's web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$file_name.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');

        }
        //added by sagar : Customer Wallet Report  - END - 01-08-2020
        
        function demo (){
            $text = "ماما";
//            $text = "dfsdf";
            $is_arabic = preg_match('/\p{Arabic}/u', $text);
            echo "<pre>###";
            print_r($is_arabic);
            print_r(utf8_encode_deep($text));
            exit;
        }

    // Added by arjun 13-06-2023
    public function deleteMultipleProducts()
    {
        $product_id = $_POST['product_id']; // Get the product IDs to delete
        $sale_id = $_POST['sale_id']; // Get the sale ID

        // Call the cancelProducts() method of the CRUD model to delete the products
        $result = $this->crud_model->cancelProducts($product_id, $sale_id);
        echo json_encode($result);
    }
    // Ended by arjun 13-06-2023
        
    // Added by arjun 13-06-2023
    public function suggested($para1 = '', $para2 = '', $para3 = ''){
            if ( ! $this->crud_model->admin_permission( 'suggested' ) ) {
                    redirect( base_url() . 'index.php/admin' );
            }
            if ( $para1 == 'list' ) {
                    $page_data['page_name'] = "suggested";

                $this->load->view( 'back/admin/suggested_list', $page_data );
            }elseif( $para1 == 'list_data'){ 
                    $limit  = $this->input->get( 'limit' );
                    $search = $this->input->get( 'search' );
                    $order  = $this->input->get( 'order' );
                    $offset = $this->input->get( 'offset' );
                    $sort   = $this->input->get( 'sort' );
               
                    $total = $this->db->get( 'suggested_products' )->num_rows();

                    $this->db->limit( $limit );
                    if ( $sort == '' ) {
                            $sort  = 'id';
                            $order = 'DESC';
                    }
                    $this->db->order_by( $sort, $order );
                    if ( $search ) {
                        $this->db->group_start();
                        $this->db->or_like( 'user.first_name', $search, 'both' );
                        $this->db->or_like('suggested_products.product_name', $search, 'both');
                        $this->db->group_end();
                    }
               
                    $products = $this->db->select('suggested_products.*, user.first_name,user.fourth_name')
                    ->from('suggested_products')    
                    ->join('user', 'user.user_id = suggested_products.from')
                    ->get()->result_array();

                    $data = array();
                    foreach ( $products as $row ) {
                            $res = array(
                                'no'        => '',
                                'from'      => '',
                                'product'   => '',
                                'options'   => ''
                            );
                           
                            $res['no'] = $row['id'];
                            $res['from']  = $row['first_name'].' '.$row['fourth_name'];
                            $res['product']  = $row['product_name'];                            
                            $data[] = $res;
                    }
                    $result = array(
                            'total' => $total,
                            'rows'  => $data
                    );
                    echo json_encode( $result );

            }else {
                    $page_data['page_name'] = "suggested";
                    // $page_data['tickets']   = $this->db->get( 'ticket' )->result_array();
                    $this->load->view( 'back/index', $page_data );
            }
    

    }

}

/* End of file welcome.php */
