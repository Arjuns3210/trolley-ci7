<?php

class Apis_models1 extends CI_Model {

    function insertData($tbl_name, $data_array, $sendid = NULL) {
        $this->db->insert($tbl_name, $data_array);
        $result_id = $this->db->insert_id();
        if ($sendid == 1) {

            return $result_id;
        }
    }

    function insert_ignore($tbl_name,$columns ,$values ,$sendid = NULL){
        $query = $this->db->query(" INSERT IGNORE INTO $tbl_name ( $columns ) values ( $values ) ") ;
        if ($query !== FALSE ) {
            return $this->db->insert_id();
        } else {
            return  false;
        }
    }

    function getData($select, $table, $condition = null, $group_by = null, $order_by = null) {

        $this->db->select($select);
        $this->db->from($table . ' as i');
        if ($condition != null)
            $this->db->where("($condition)");
        if ($group_by != null)
            $this->db->group_by($group_by);
        if ($order_by != null) {
            $this->db->order_by("$order_by");
        }
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    function getProfile($select, $table, $condition = null, $group_by = null, $order_by = null) {

        $this->db->select($select);
        $this->db->from($table);
        // Joins the necessary tables
        $this->db->join('sale', 'admin.admin_id = sale.admin_id', 'LEFT');
        $this->db->join('city', 'admin.city_id = city.city_id', 'LEFT');
        $this->db->join('area', 'admin.area_id = area.area_id', 'LEFT');
        if ($condition != null)
            $this->db->where("($condition)");
        if ($group_by != null)
            $this->db->group_by($group_by);
        if ($order_by != null) {
            $this->db->order_by("$order_by");
        }
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }    

    //added by Arjun : start
    function get_type_name_by_primary($type, $type_col_name = '', $type_id = '', $field = 'name') {
        if ($type_id != '') {
            $l = $this->db->get_where($type, array(
                $type_col_name => $type_id
            ));
            $n = $l->num_rows();
            if ($n > 0) {
                return $l->row()->$field;
            }
        }
    }
    //added by arjun : END

    //$type is tablename , $type_id is primary key value , $field is column name in that table
    function get_type_name_by_id($type, $type_id = '', $field = 'name') {
        if ($type_id != '') {
            $l = $this->db->get_where($type, array(
                $type . '_id' => $type_id
            ));
            $n = $l->num_rows();
            if ($n > 0) {
                return $l->row()->$field;
            }
        }
    }

    function updateRecord($table, $datar, $condition) {
        $this->db->where("($condition)");
        $this->db->update($table, $datar);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return true;
        }
    }

    function delrecords_truncate($tbl_name) {

        $this->db->truncate($tbl_name);
    }

    function delrecord($tbl_name, $condition) {
        $this->db->where("($condition)");
        $this->db->delete($tbl_name);


        return true;
    }

    function validate($username, $password) {
        $query = $this->db->query('select * from user
                                            where  ( email = ' . $this->db->escape($username) . '
                                            OR    phone = ' . $this->db->escape($username) . ' )
                                            and   password = ' . $this->db->escape($password) . '
                                    ');
      
        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function getproducts_original($res) {

        $query = $this->db->query($res);
        if ($query !== FALSE && $query->num_rows() > 0) {

            return $query->result_array();
        } else {

            return false;
        }
    }

    function getlabels3() {


        $query = $this->db->query('
                                        select *
                                         from labels lb left join label_value lbv 
                                         on lb.label_id = lbv.label_id
                                        where language_id = 1
                                        
                                    ');

        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function allcategories() {
        $query = $this->db->query('
                                       select  c.category_id as id ,c.category_name as name ,c.banner as image , c.banner as icon,0 as parent_id
                                               ,count(p.product_id) as total_products
                                       from category c
                                       Left join product p on (c.category_id = p.category  and  p.status="ok" ) 
                                       group by c.category_id
                                       order by name
                                    ');

        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function getSubCategoriesProducts($category_id) {
        $current_date = date('Y-m-d H:i:s');
        $query = $this->db->query('
                                             SELECT sc.sub_category_name as name ,sc.sub_category_id as id,  sc.category as parent_id , sc.subcategory_drive_id as image, 
                                                   sc.subcategory_drive_id as icon,count(p.product_id) as total_products
                                             FROM  sub_category  sc  
                                             Left Join  category c on (c.category_id = sc.category)
                                             Left join product p on (sc.sub_category_id = p.sub_category and   p.status = "ok") 
                                             where c.category_id = ' . $this->db->escape($category_id) . '
                                             group by sc.sub_category_id   
                                    ');

        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function getProducts($categories_id = null, $subcategories_id = null, $brand_id = null, $product_id = null, $collection_product_ids = null, $related_subcategory_product = '',$is_offer_product='', $is_new_product='', $limit = 10, $page = 0, $sort_by = 'p.title', $sort_by_order = 'asc', $min = null, $max = null, $condition = "1=1 ") {
        $offset = $limit * $page;
        $price_range_condition = '';
        $time_range_condition = '';

//        $current_date = date('Y-m-d H:i:s');
//        $start_date = '0000-00-00 00:00:00';
//        $condition .=' And p.live_from <= '.$this->db->escape($current_date);
//        $condition .=' And p.live_from > '.$this->db->escape($start_date);
        $condition .= ' And p.status="ok" ';
        if ($categories_id != null && !empty($categories_id)) {
            $condition .= ' And p.category=' . $this->db->escape($categories_id);
        }
        if ($subcategories_id != null && !empty($subcategories_id)) {
            $condition .= ' And p.sub_category=' . $this->db->escape($subcategories_id);
        }
        if ($brand_id != null && !empty($brand_id)) {
//               $condition .= ' And p.brand='.$this->db->escape($brand_id);
            $condition .= ' AND p.brand IN (' . $brand_id . ')';
        }
        if ($collection_product_ids != null && !empty($collection_product_ids)) {
            $condition .= ' AND p.product_id IN (' . $collection_product_ids . ')';
        }
        if ($product_id != null) {
            $condition .= ' And p.product_id=' . $this->db->escape($product_id);
        }
        if ($related_subcategory_product != '') {
            $condition .= ' AND p.product_id IN (' . $related_subcategory_product . ')';
        }
        //is_offer part :START
        if ($is_offer_product != '') {
            $condition .= ' AND p.is_offer = ' . $this->db->escape($is_offer_product);
        }
        //is_offer part :END
    
        //is_new product part :START
        if ($is_new_product != '') {
            $condition .= ' AND p.featured = ' . $this->db->escape($is_new_product);
        }
        //is_new product part :END
        if ($min != null && $max != null) {
            $condition .= ' And p.sale_price  BETWEEN ' . $min . ' AND ' . $max;
        } elseif ($min == null && $max != null) {
            $condition .= ' And p.sale_price <= ' . $max;
        } elseif ($min != null && $max == null) {
            $condition .= ' And p.sale_price  >= ' . $min;
        }
        $query1 = $this->db->query(
                'Select  p.*,c.category_name,c.banner as category_image,c.data_brands as category_brands,'
                . 's.sub_category_name,s.sub_category_id,s.banner as sub_category_image,s.brand as subcategory_brands'
                . ',IFNULL(b.name,"NA") as brand_name,b.brand_id,b.logo as brand_logo '
                . 'From product p 
                                    Left Join brand  b ON (p.brand = b.brand_id)
                                    Left Join category c ON (p.category = c.category_id)
                                    Left Join sub_category s ON (p.sub_category = s.sub_category_id)'
                . ' where ' . $condition
                . ' Order by ' . $sort_by . ' ' . $sort_by_order . '
                            Limit ' . $offset . ', ' . $limit);
        
        $query2 = $this->db->query(
                'Select  p.*,c.category_name,c.banner as category_image,c.data_brands as category_brands,'
                . 's.sub_category_name,s.sub_category_id,s.banner as sub_category_image,s.brand as subcategory_brands'
                . ',IFNULL(b.name,"NA") as brand_name,b.brand_id,b.logo as brand_logo '
                . 'From product p 
                                    Left Join brand  b ON (p.brand = b.brand_id)
                                    Left Join category c ON (p.category = c.category_id)
                                    Left Join sub_category s ON (p.sub_category = s.sub_category_id)'
                . ' where ' . $condition
                . ' Order by ' . $sort_by . ' ' . $sort_by_order);

        $total_rows = 0;

        if ($query1 !== FALSE && $query1->num_rows() > 0) {
            if ($query2->num_rows() > 0) {
                $total_rows = $query2->num_rows();
            }
            return array($query1->result_array(), $total_rows);
        } else {
            return array(array(), 0);
        }
    }

    // FILE_VIEW
    function file_view($type, $id, $width = '100', $height = '100', $thumb = 'no', $src = 'no', $multi = '', $multi_num = '', $ext = '.jpg')
    {
        if ($multi == '') {
            if (file_exists('uploads/' . $type . '_image/' . $type . '_' . $id . $ext)) {
                if ($thumb == 'no') {
                    $srcl = base_url() . 'uploads/' . $type . '_image/' . $type . '_' . $id . $ext;
                } elseif ($thumb == 'thumb') {
                    $srcl = base_url() . 'uploads/' . $type . '_image/' . $type . '_' . $id . '_thumb' . $ext;
                }

                if ($src == 'no') {
                        return '<img src="' . $srcl . '?d='.time().'" height="' . $height . '" width="' . $width . '" />';
                } elseif ($src == 'src') {
                        return $srcl.'?d='.refreshedImage();
                }
            }
                            else{
                                    return base_url() . 'uploads/'. $type.'_image/default.jpg';
                            }

        } else if ($multi == 'multi') {
                $num    = $this->crud_model->get_type_name_by_id($type, $id, 'num_of_imgs');
            //$num = 2;
                $i      = 0;
                $p      = 0;
                $q      = 0;
            $return = array();
            while ($p < $num) {
                $i++;
                if (file_exists('uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $i . $ext)) {
                    if ($thumb == 'no') {
                        $srcl = base_url() . 'uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $i . $ext;
                    } elseif ($thumb == 'thumb') {
                        $srcl = base_url() . 'uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $i . '_thumb' . $ext;
                    }

                    if ($src == 'no') {
                            $return[] = '<img src="' . $srcl . '?d='.time().'" height="' . $height . '" width="' . $width . '" />';
                    } elseif ($src == 'src') {
                        $return[] = $srcl;
                    }
                    $p++;
                } else {
                    $q++;
                    if ($q == 10) {
                        break;
                    }
                }

            }
            if (!empty($return)) {
                if ($multi_num == 'one') {
                        return $return[0].'?d='.refreshedImage();
                } else if ($multi_num == 'all') {
                    return $return;
                } else {
                    $n = $multi_num - 1;
                    unset($return[$n]);
                    return $return;
                }
            } else {
                if ($multi_num == 'one') {
                        return base_url() . 'uploads/'. $type.'_image/default.jpg';
                } else if ($multi_num == 'all') {
                            return array(base_url() . 'uploads/'. $type.'_image/default.jpg');
                } else {
                            return array(base_url() . 'uploads/'. $type.'_image/default.jpg');
                }
            }
        }
    }

    //GETTING PRODUCT PRICE CALCULATING DISCOUNT
    function get_product_price($product_id, $price = '', $discount = '', $discount_type = '') {
        //added by ritesh : start
        $number = 0;
        //added by ritesh : end
        if (!(isset($price) && !empty($price)))
            $price = $this->get_type_name_by_id('product', $product_id, 'sale_price');
        if (!(isset($discount) && !empty($discount)))
            $discount = $this->get_type_name_by_id('product', $product_id, 'discount');
        if (!(isset($discount_type) && !empty($discount_type)))
            $discount_type = $this->get_type_name_by_id('product', $product_id, 'discount_type');

        // New changes after cart storage in db by Dev -- Start
        $number = $price;
        if ($discount_type == 'amount') {
            if ($discount > 0 && $discount < (double) $price) {
                $number = ($price - $discount);
            }
        }
        if ($discount_type == 'percent') {
            if ($discount > 0 && $discount < 100) {
                $number = ($price - ($discount * $price / 100));
            }
        }
	$number = round($number,3);
        // New changes after cart storage in db by Dev -- END
        return number_format((float) $number, 3, '.', '');
    }

    function get_discount_amount($product_id, $price = '', $discount = '', $discount_type = '') {

        $number = 0;
        if (!(isset($price) && !empty($price)))
            $price = $this->get_type_name_by_id('product', $product_id, 'sale_price');
        if (!(isset($discount) && !empty($discount)))
            $discount = $this->get_type_name_by_id('product', $product_id, 'discount');
        if (!(isset($discount_type) && !empty($discount_type)))
            $discount_type = $this->get_type_name_by_id('product', $product_id, 'discount_type');

        if ($discount_type == 'amount') {
            $number = $discount;
        }
        if ($discount_type == 'percent') {
            $number = (float) $discount * (float) $price / 100;
        }
	$number = round($number,3);
        return number_format((float) $number, 3, '.', '');
    }

    function get_shipping_cost($product_id, $price = '', $shipping = '', $shipping_cost_type = '') {
        if (!(isset($price) && !empty($price)))
            $price = $this->get_type_name_by_id('product', $product_id, 'sale_price');
        if (!(isset($shipping) && !empty($shipping)))
            $shipping = $this->get_type_name_by_id('product', $product_id, 'shipping_cost');
        if (!(isset($shipping_cost_type) && !empty($shipping_cost_type)))
            $shipping_cost_type = $this->get_type_name_by_id('business_settings', '3', 'value');

        if ($shipping_cost_type == 'product_wise') {
            if ($shipping == '') {
                return 0;
            } else {
                return ($shipping);
            }
        }
        if ($shipping_cost_type == 'fixed') {
            return 0;
        }
    }

    function get_product_tax($product_id, $price = '', $tax = '', $tax_type = '') {
        if (!(isset($price) && !empty($price)))
            $price = $this->get_type_name_by_id('product', $product_id, 'sale_price');
        if (!(isset($tax) && !empty($tax)))
            $tax = $this->get_type_name_by_id('product', $product_id, 'tax');
        if (!(isset($tax_type) && !empty($tax_type)))
            $tax_type = $this->get_type_name_by_id('product', $product_id, 'tax_type');

        if ($tax_type == 'amount') {
            if ($tax == '') {
                return 0;
            } else {
                if ($tax > 0) {
                    return $tax;
                } else {
                    return 0;
                }
            }
        }
        if ($tax_type == 'percent') {
            if ($tax == '') {
                $tax = 0;
            }
            if ($tax > 0 && $tax < 100) {
                return ($tax * $price / 100);
            } else {
                return 0;
            }
        }
    }

    function getProductVariations($product_id = 0, $variation_id = 0,$lang="ar") {
        $condition = '1=1 ';
        $condition .= ' And p.status="ok" ';
        $condition .= ' And v.status="Active" ';
        if (isset($product_id) && $product_id > 0) {
            $condition .= ' And p.product_id=' . $this->db->escape($product_id);
        }
        if (isset($variation_id) && $variation_id > 0) {
            $condition .= ' And v.variation_id=' . $this->db->escape($variation_id);
        }

        $product_title = " ,p.title_ar as title";
        if($lang == 'en'){
            $product_title = " ,p.title";
        }
        $product_variation_title =  ",p.title as product_name_en, p.title_ar as product_name_ar,v.title as variation_title_en, v.title_ar as variation_title_ar ";
        $sql = $this->db->query("SELECT p.product_id,p.product_type,p.product_code,p.title,p.sale_price,p.purchase_price,p.b2b_sale_price,p.color,p.options,p.weight,p.brand,p.category,p.sub_category,
                                    p.num_of_imgs,p.discount,p.b2b_discount,p.discount_type,p.unit,p.b2b_unit,p.tax,p.tax_type,p.shipping_cost,
                                    v.variation_id,v.current_stock as variation_stock,
                                    v.title as variation_title,v.sale_price as variation_price,v.b2b_sale_price as b2b_variation_price,v.purchase_price as variation_purchase_price,v.is_default,v.supplier_price,p.supplier
                                    $product_title $product_variation_title
                                    FROM product p
                                    JOIN  variation v ON (p.product_id=v.product_id And p.product_type = v.product_type)
                                    where " . $condition);

        if ($sql !== FALSE && $sql->num_rows() >= 1) {
            return $sql->result_array();
        } else {
            return false;
        }
    }

    //get complete attributes : End
    //variation 
    function getVariationAttributeMapping($product_id = 0, $variation_id = 0, $condition = "1=1 ") {

        //chnaged by Umang -- start 24 June
        $condition .= ' And p.status="ok" And v.status="Active" ';
        //chnaged by Umang -- end 24 June
        if (isset($product_id) && $product_id > 0) {
            $condition .= ' And p.product_id=' . $this->db->escape($product_id);
            $condition .= ' And v.product_id=' . $this->db->escape($product_id);
        }
        if (isset($variation_id) && $variation_id > 0) {
            $condition .= ' And v.variation_id=' . $this->db->escape($variation_id);
            $condition .= ' And a.variation_id=' . $this->db->escape($variation_id);
        }

        $sql = $this->db->query("SELECT p.product_id,p.product_type,p.title,p.sale_price,p.b2b_sale_price,p.num_of_imgs,p.discount,p.b2b_discount,p.discount_type,
                                    v.variation_id, v.current_stock as variation_stock,v.title as varaiation_title,v.sale_price as variation_price,v.sale_price as sale_price, v.purchase_price as purchase_price,v.b2b_sale_price as b2b_variation_price,v.is_default,
                                    group_concat(a.attribute_id  ORDER BY a.attribute_id) as group_attribute_id,
                                    group_concat(a.attributevalue_id ORDER BY a.attribute_id) as group_attributevalue_id
                                    FROM product p 
                                    JOIN variation v ON (p.product_id=v.product_id And p.product_type = v.product_type) 
                                    LEFT JOIN attribute_mapping a ON a.variation_id = v.variation_id " .
                " WHERE " . $condition .
                " group by v.variation_id  order by is_default");


        if ($sql !== FALSE && $sql->num_rows() >= 1) {
            return $sql->result_array();
        } else {
            return false;
        }
    }

    function get_attmapp_data($product_id = 0, $condition = ' status="Active" ') {

        $condition .= ' AND status = "Active"';
        if (isset($product_id) && $product_id > 0) {
            $condition .= ' And product_id=' . $this->db->escape($product_id);
        }

        $query = $this->db->query(' SELECT product_id, 
                                            group_concat(DISTINCT attribute_id ORDER BY attribute_id) as group_attribute_id, 
                                            group_concat(DISTINCT attributevalue_id ORDER BY attribute_id) as group_attributevalue_id 
                                            from attribute_mapping 
                                            where ' . $condition . '
                                            group by product_id');
       

        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function total_wished($product_id) {
        $num = 0;
        $users = $this->db->get('user')->result_array();
        foreach ($users as $row) {
            $wishlist = json_decode($row['wishlist']);
            if (is_array($wishlist)) {
                if (in_array($product_id, $wishlist)) {
                    $num++;
                }
            }
        }
        return $num;
    }

    function most_wished() {
        $result = array();
        $condition = '1=1 ';
//        $current_date = date('Y-m-d H:i:s');
//        $start_date = '0000-00-00 00:00:00';
//           $condition .=' And live_from <= '.$this->db->escape($current_date);
//           $condition .=' And live_from > '.$this->db->escape($start_date);
        $condition .= ' And status="ok" ';

        $this->db->where('(' . $condition . ')');
        $product = $this->db->get('product')->result_array();

        foreach ($product as $row) {
            $wish_count = $this->total_wished($row['product_id']);
            if (isset($wish_count) && !empty($wish_count) && $wish_count > 0) {
                $result[] = array(
                    'title' => $row['title'],
                    'wish_num' => $this->total_wished($row['product_id']),
                    'product_id' => $row['product_id']
                );
            }
        }
        if (!function_exists('compare_lastname')) {

            function compare_lastname($a, $b) {
                return strnatcmp($b['wish_num'], $a['wish_num']);
            }

        }
        usort($result, 'compare_lastname');
        return $result;
    }

    //DECREASEING VARIANT QUANTITY
    function decrease_variant_quantity($product_id, $quantity, $variant_id = '') {
        $prev_quantity = $this->apis_models1->get_type_name_by_id('variation', $variant_id, 'current_stock');
        $data1['current_stock'] = $prev_quantity - $quantity;
        if ($data1['current_stock'] < 0) {
            $data1['current_stock'] = 0;
        }
        $this->db->where('product_id', $product_id);
        $this->db->where('variation_id', $variant_id);
        $this->db->update('variation', $data1);
    }

    function get_variation_stocks_maxcount($product_id = 0, $variation_id = 0) {
        $condition = " v.status='Active' ";
        if (isset($product_id) && $product_id > 0) {
            $condition .= ' And v.product_id=' . $this->db->escape($product_id);
        }
        if (isset($variation_id) && $variation_id > 0) {
            $condition .= ' And v.variation_id=' . $this->db->escape($variation_id);
        }

        $sql = $this->db->query("SELECT max(v.current_stock) as current_stock FROM 
                                       variation v JOIN product p ON (p.product_id=v.product_id And p.product_type = v.product_type)
                                       where " . $condition);
        if ($sql !== FALSE && $sql->num_rows() >= 1) {
            return $sql->row()->current_stock;
        } else {
            return 0;
        }
    }

    function exists_in_table($table, $field, $val) {
        $ret = '';
        $res = $this->db->get($table)->result_array();
        foreach ($res as $row) {
            if ($row[$field] == $val) {
                $ret = $row[$table . '_id'];
            }
        }
        if ($ret == '') {
            return false;
        } else {
            return $ret;
        }
    }

    function verify_if_unique($table_name, $condition) {

        $sql = $this->db->query("Select * from " . $table_name . " where " . $condition);

        if ($sql !== FALSE && $sql->num_rows() >= 1) {
            return $sql->result_array();
        } else {
            return false;
        }
    }

    function getProductDetails($product_id = null, $categories_id = null, $subcategories_id = null, $brand_id = null, $condition = "1=1 ") {
        $price_range_condition = '';

        $time_range_condition = '';
//           $current_date = date('Y-m-d H:i:s');
//           $start_date = '0000-00-00 00:00:00';
//           $condition .=' And p.live_from <= '.$this->db->escape($current_date);
//           $condition .=' And p.live_from > '.$this->db->escape($start_date);
        $condition = " 1=1 ";
        $condition .= ' And p.status="ok" ';
        if ($categories_id != null && !empty($categories_id)) {
            $condition .= ' And p.category=' . $this->db->escape($categories_id);
        }
        if ($subcategories_id != null && !empty($subcategories_id)) {
            $condition .= ' And p.sub_category=' . $this->db->escape($subcategories_id);
        }
        if ($brand_id != null && !empty($brand_id)) {
            $condition .= ' And p.brand=' . $this->db->escape($brand_id);
        }
        if ($product_id != null) {
            $condition .= ' And p.product_id=' . $this->db->escape($product_id);
        }


        $sql = $this->db->query(
                'Select  p.*,c.category_name,c.banner as category_image,c.data_brands as category_brands,'
                . 's.sub_category_name,s.banner as sub_category_image,s.brand as subcategory_brands'
                . ',b.name as brand_name,b.logo as brand_logo '
                . 'From product p 
                                    Left Join category c ON (p.category = c.category_id)
                                    Left Join sub_category s ON (p.sub_category = s.sub_category_id)
                                    Left Join brand  b ON (p.brand = b.brand_id)'
                . ' where ' . $condition
        );

        if ($sql !== FALSE && $sql->num_rows() >= 1) {
            return $sql->result_array();
        } else {
            return false;
        }
    }

    function getProductsData($collection_product_ids = "",$limit = 20, $page = 0, $sort_by = 'p.product_id', $sort_by_order = 'DESC') {
        $offset = $limit * $page;
        $condition = " 1=1 ";
        $condition .= ' And p.status="ok" ';
        if(!empty($collection_product_ids)){
            $condition .= ' AND p.product_id IN (' . $collection_product_ids . ')';
        }
        $query = $this->db->query(
                'Select  p.*,c.category_name,c.banner as category_image,c.data_brands as category_brands,'
                . 's.sub_category_name,s.banner as sub_category_image,s.brand as subcategory_brands'
                . ',b.name as brand_name,b.logo as brand_logo '
                . 'From product p 
                                    Left Join brand  b ON (p.brand = b.brand_id)
                                    Left Join category c ON (p.category = c.category_id)
                                    Left Join sub_category s ON (p.sub_category = s.sub_category_id)'
                . ' where ' . $condition
                . ' Order by ' . $sort_by . ' ' . $sort_by_order);

        $total_rows = 0;
        if ($query !== FALSE && $query->num_rows() > 0) {
            if ($query->num_rows() > 0) {
                $total_rows = $query->num_rows();
            }
            return array($query->result_array(), $total_rows);
        } else {
            return array(array(), 0);
        }
    }

    function fetchAllFilters($category_id = null, $subcategory_id = null,$lang="ar",$is_featured =null) {
        $condition = " 1=1  AND c.digital = 'ok' ";
        if ($category_id != null && !empty($category_id)) {
            $condition .= ' And c.category_id=' . $this->db->escape($category_id);
        }
        if ($subcategory_id != null && !empty($subcategory_id)) {
            $condition .= ' And s.sub_category_id=' . $this->db->escape($subcategory_id);
        }
        if ($is_featured != null && !empty($is_featured)) {
            $condition .= ' And c.is_featured=' . $this->db->escape($is_featured);
        }
        
        $selected = " c.category_name ,c.category_id, c.is_featured ,c.banner as category_banner,s.sub_category_name ,s.sub_category_id ,s.banner as sub_category_banner,s.brand";
        $orderBy = " c.category_name asc , s.sub_category_name asc  ";
        
	if($lang=='ar'){
            $selected = " c.category_name_ar as category_name ,c.category_id,  c.is_featured , c.banner as category_banner,s.sub_category_name_ar as  sub_category_name ,s.sub_category_id ,s.banner as sub_category_banner,s.brand";
	    $orderBy = " c.category_name_ar asc , s.sub_category_name_ar asc  ";
        }
 
        $query = $this->db->query(" SELECT $selected
                                    FROM category c
                                    left join sub_category s on (c.category_id = s.category and s.digital = 'ok')
                                    where  $condition 
                                    order by $orderBy ");
  
        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function fetchProductsReview($product_id, $variation_id, $limit = 10, $page = 0) {
        $offset = $limit * $page;
        $query1 = $this->db->query('SELECT u.username,u.surname,r.title,r.rating_number, r.comment,r.created_on
                                        from ratings r
                                        join user u on (u.user_id = r.user_id)
                                        join product p on(p.product_id = r.product_id)
                                        where r.product_id = ' . $product_id . ' and r.variation_id = ' . $variation_id . '
                                        order by r.created_on DESC
                                        Limit ' . $offset . ', ' . $limit);
        $query2 = $this->db->query('SELECT u.username,u.surname,r.title,r.rating_number, r.comment,r.created_on
                                        from ratings r
                                        join user u on (u.user_id = r.user_id)
                                        join product p on(p.product_id = r.product_id)
                                        where r.product_id = ' . $product_id . ' and r.variation_id = ' . $variation_id . '
                                        order by r.created_on DESC'
        );

        $total_rows = 0;
        if ($query1 !== FALSE && $query1->num_rows() > 0) {
            if ($query2->num_rows() > 0) {
                $total_rows = $query2->num_rows();
            }
            return array($query1->result_array(), $total_rows);
        } else {
            return array(array(), 0);
        }
    }

    function getRatingCount($product_id, $variation_id) {
        $query = $this->db->query('SELECT rating_number ,count(rating_number) as total_count 
                                    from ratings r
                                    where product_id = ' . $product_id . ' and variation_id = ' . $variation_id . '
                                    group by rating_number
                                    order by rating_number desc');

        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function getRelatedProduct($product_id) {
        $query = $this->db->query('SELECT product_id 
                                    from product 
                                    where sub_category = ( SELECT sub_category FROM product WHERE product_id = ' . $product_id . ' ) and product_id != ' . $product_id . '
                                    ');

        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function fetchAllBrands($brand_id = null, $type = null, $limit = 20, $page = 0) {
        $condition = '1=1';
        if ($brand_id != null && !empty($brand_id)) {
            $condition .= ' AND  brand_id=' . $this->db->escape($brand_id);
        }
        $offset = $limit * $page;
        $page_limit = '';
        if ($type == 'page') {
            $page_limit = " Limit $offset,$limit";
        }

        $query1 = $this->db->query('SELECT brand_id,name,brand_drive_id,logo
                                      from brand 
                                      where ' . $condition . '
                                      order by name'
                . $page_limit
        );

        $query2 = $this->db->query('SELECT brand_id,name,brand_drive_id,logo
                                      from brand 
                                      where ' . $condition . '
                                      order by name'
        );
        $total_rows = 0;
        if ($query1 !== FALSE && $query1->num_rows() > 0) {
            if ($query2->num_rows() > 0) {
                $total_rows = $query2->num_rows();
            }
            return array($query1->result_array(), $total_rows);
        } else {
            return array(array(), 0);
        }
    }

    function fetchAllStores($store_id = null, $limit = 3, $page = 0) {
        $condition = '1=1';
        if ($store_id != null && !empty($store_id)) {
            $condition .= ' AND  store_master_id=' . $this->db->escape($store_id);
        }
        $offset = $limit * $page;
        $page_limit = " Limit $offset,$limit";

        $query1 = $this->db->query('SELECT s.*
                                      from store_master s
                                      where ' . $condition . '
                                      order by store_master_id DESC'
                . $page_limit
        );

        $query2 = $this->db->query('SELECT s.*
                                      from store_master s
                                      where ' . $condition . '
                                      order by store_master_id DESC'
        );
        $total_rows = 0;
        if ($query1 !== FALSE && $query1->num_rows() > 0) {
            if ($query2->num_rows() > 0) {
                $total_rows = $query2->num_rows();
            }
            return array($query1->result_array(), $total_rows);
        } else {
            return array(array(), 0);
        }
    }
    
    function getUserGroupDetails($user_id = null){
        $query = $this->db->query("SELECT u.user_id,u.user_group_id,ug.products_in_group,ug.group_discount,ug.offer_validity 
                                    from user  u
                                    left join user_group ug ON  (u.user_group_id = ug.user_group_id)
                                    where u.user_id = $user_id 
                                    ");
        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function getorderdata($customers_id=0,$limit=5,$page = 0, $status = 'all', $time = 'all')
    {
        $condition = "";

        switch ($status) {
            case 'pending':
                $condition .= " AND order_status = 'processed' AND delivery_status NOT LIKE '%\"status\":\"delivered\"%'";
                break;
            case 'delivered':
                $condition .= " AND delivery_status LIKE '%\"status\":\"delivered\"%'";
                break;
            // case 'process':
            // case 'processed':
            //     $condition .= " AND order_status LIKE '%" . $status . "%'";
            //     break;
            case 'cancel':
            case 'cancelled':
                $condition .= " AND order_status LIKE '%" . $status . "%'";
                break;
            default:
                $condition = "";
                break;
        }
        if ($time != 'all') {
            $condition .= " AND sale_datetime >= '" . strtotime(date('Y-m-d H:i:s', strtotime("-{$time}"))) . "'";
        }
        $offset = $limit*$page;
        $query = $this->db->query('
                            select *
                            from  sale
                            where buyer = '.$this->db->escape($customers_id).'
                            ' . $condition . '
                            order by sale_id DESC
                            Limit '.$offset.', '.$limit.'
                        ');

        if($query!==FALSE  &&  $query->num_rows()>0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function appPageData(){
        $query = $this->db->query(" SELECT if(i.type = 'about_us_en' || i.type='terms_conditions_en' || i.type='privacy_policy_en' ,'English','Arabic') as language , type  , value
                                    FROM general_settings i WHERE i.Type In ('about_us_en','about_us_ar','terms_conditions_en','terms_conditions_ar', 'privacy_policy_en', 'privacy_policy_ar') 
                                    ");
        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    //added by sagar : START ON 10-10-2019
    function appFeaturedData($lang='ar'){
        $column = "('instant_delivery_title_en' , 'instant_delivery_description_en','store_pickup_title_en','store_pickup_description_en') ";
        if($lang == 'ar'){
             $column = "('instant_delivery_title_ar' , 'instant_delivery_description_ar','store_pickup_title_ar','store_pickup_description_ar') ";
        }
        $query = $this->db->query(" SELECT type,value
                                    FROM general_settings i WHERE i.Type In $column 
                                     order by general_settings_id ASC   
                                    ");
        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    //added by sagar : END ON 10-10-2019
    
    function appContactusData($curreny_code='1'){
        $query = $this->db->query(" SELECT  type  , value
                                    FROM general_settings i WHERE i.Type In ('address','contact_phone','contact_email','product_fixed_tax','free_delivery_amount','free_delivery_amount_ar','min_order_amount','min_order_amount_ar') 
                                    ");
        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    
    function getCoupondata($code){
        $current_date = date('Y-m-d H:i:s'); 
        $this->db->select('i.coupon_id,discount_type,discount_value,title,code');
        $this->db->from('coupon as i');
        $this->db->where('i.code',$code);
        $this->db->where('i.status','Active');
        $this->db->where('i.start_date <=',$current_date);
        $this->db->where('i.till >=',$current_date);
        $query = $this -> db -> get();
        if($query -> num_rows() == 1)
        {
          return $query->row_array();
        }
        else
        {
          return false;
        }
     }
     
      function getReorderCartProducts($variation_id = null, $limit = 100, $page = 0, $sort_by = 'p.title', $sort_by_order = 'asc', $condition = "1=1 ") {
        $offset = $limit * $page;
        $condition .= ' And p.status="ok" AND v.status="Active" ';
        if ($variation_id != null && !empty($variation_id)) {
            $condition .= ' AND v.variation_id IN (' . $variation_id . ')';
        }
        $query1 = $this->db->query(
                'Select  p.*,v.variation_id,c.category_name,c.banner as category_image,c.data_brands as category_brands,'
                . 's.sub_category_name,s.sub_category_id,s.banner as sub_category_image,s.brand as subcategory_brands'
                . ',IFNULL(b.name,"NA") as brand_name,b.brand_id,b.logo as brand_logo '
                . 'From product p 
                                    Left join variation v ON (p.product_id = v.product_id)
                                    Left Join brand  b ON (p.brand = b.brand_id)
                                    Left Join category c ON (p.category = c.category_id)
                                    Left Join sub_category s ON (p.sub_category = s.sub_category_id)'
                . ' where ' . $condition
                . ' Order by ' . $sort_by . ' ' . $sort_by_order . '
                            Limit ' . $offset . ', ' . $limit);
   
        $query2 = $this->db->query(
                'Select  p.*,v.variation_id,c.category_name,c.banner as category_image,c.data_brands as category_brands,'
                . 's.sub_category_name,s.sub_category_id,s.banner as sub_category_image,s.brand as subcategory_brands'
                . ',IFNULL(b.name,"NA") as brand_name,b.brand_id,b.logo as brand_logo '
                . 'From product p 
                                    Left join variation v ON (p.product_id = v.product_id)
                                    Left Join brand  b ON (p.brand = b.brand_id)
                                    Left Join category c ON (p.category = c.category_id)
                                    Left Join sub_category s ON (p.sub_category = s.sub_category_id)'
                . ' where ' . $condition
                . ' Order by ' . $sort_by . ' ' . $sort_by_order);

        $total_rows = 0;

        if ($query1 !== FALSE && $query1->num_rows() > 0) {
            if ($query2->num_rows() > 0) {
                $total_rows = $query2->num_rows();
            }
            return array($query1->result_array(), $total_rows);
        } else {
            return array(array(), 0);
        }
    }
    //added by sagar : START 
     function getCardValidity($card_condition){
        $cardValidity = '';
        $cardDataExist = $this->apis_models1->getData( 'user_card_id,card_number,card_validity', 'user_card', $card_condition );
        if ( is_array( $cardDataExist ) ) {
                $cardValidity = $cardDataExist['0']['card_validity'];
        }
        return $cardValidity;
    }
 
    //Auto generated wallet number :: NOT IN USE
     function geneateWalletNumber(){
        $unique_wallet_series = UNIQUE_WALLET_NUMBER_SERIES;
        $lastWalletNoInDB = $this->db->select_max('wallet_no')->get('user')->row()->wallet_no;
        $newWalletNumber = 0;
        if(empty($lastWalletNoInDB)){
            $newWalletNumber = $unique_wallet_series + 1;
        }else{
            $newWalletNumber = $lastWalletNoInDB + 1;
        }
        return $newWalletNumber;
    }
   
    function getDeliveryTimeSlots($timeslotDay="",$timeslotNextDay=""){
        $currentTime = $addedCurrentTime = date('H:i:s');
        //additional time added in current time 
        $timeslot_minute =  $this->db->get_where('general_settings',array('type' => 'timeslot_minute'))->row()->value;
        if(!empty($timeslot_minute)){
            $addedCurrentTime = date('H:i:s',strtotime($currentTime . " +$timeslot_minute minutes"));
        }
        
        if($addedCurrentTime < $currentTime){
            //it means date has changed no need to consider current date
            $condition = " status = 'ok' ";
            $condition .=  " AND  (day = ". $this->db->escape($timeslotNextDay);
            $condition .=  " ) " ;
        }else{
            $condition = " status = 'ok' ";
            $condition .=  " AND ( day = ". $this->db->escape($timeslotDay);
            $condition .=  " OR  day = ". $this->db->escape($timeslotNextDay) . " )";
        }

        $query = $this->db->query(" SELECT timeslots_id,day,start_time,end_time
                                    FROM timeslots 
                                    where $condition
                                    order by day, start_time    
                                    ");
     
        if ($query !== FALSE && $query->num_rows() > 0) {
            $return_data = array();
            $result = $query->result_array();

            foreach ($result as $key => $value) {
                // if (count($return_data) >= MAX_DELIVERY_DATE) {
                //     break;
                // }
                if (in_array($value['day'], array($timeslotDay,$timeslotNextDay)) ) {
                    array_push($return_data, $value);
                }
            }

            return $return_data;
        } else {
            return FALSE;
}
    }

    function getDeliveryCharge($city_id = 0, $area_id = 0){
        $this->db->select('i.delivery_charge');
        $this->db->from('area as i');
        $this->db->where('i.city_id',$city_id);
        $this->db->where('i.area_id',$area_id);
        $this->db->where('i.status','ok');
        $query = $this->db->get();
        if($query -> num_rows() == 1)
        {
          return $query->row()->delivery_charge;
        }
        else
        {
          return false;
        }
    }
   
    function checkDeliveryAddress($address_id = 0, $user_id = 0){
        $condition = " 1=1 ";
        $condition .=  " AND ua.address_id = ". $this->db->escape($address_id);
        $condition .=  " AND ua.user_id  =  ". $this->db->escape($user_id);
        
        $query = $this->db->query("   SELECT ua.user_id,u.phone,u.email,u.first_name,u.fourth_name,u.wallet_type,ua.address_id,ua.title,ua.number,ua.address_1,ua.address_2,ua.landmark,c.city_id,c.city_name_en,c.city_name_ar,a.area_id,a.area_name_en,a.area_name_ar FROM `user_address` ua
                                        join user as u on (u.user_id = ua.user_id)
                                        join city as c on (ua.city_id = c.city_id)
                                        join area as a on (ua.area_id =  a.area_id)
                                        where $condition
                                    ");
        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function fetchedLimitedData($table_name, $condition = "1=1 ", $order = '', $order_by = '', $limit = 10, $page = 0) {
        $order_condition = '';
        $offset = $limit * $page;
        if (isset($order) && !empty($order)) {
            $order_condition = 'ORDER BY ' . $order;
            if (isset($order_by) && !empty($order_by)) {
                $order_condition .= ' ' . $order_by;
            } else {
                $order_condition .= ' ASC';
            }
        }
        $sql = $this->db->query("Select * from "
                . "$table_name "
                . "where "
                . "$condition "
                . "$order_condition"
                . " LIMIT $offset,$limit");

        if ($sql !== FALSE && $sql->num_rows() >= 1) {
            return $sql->result_array();
        } else {
            return false;
        }
    }

    function getSalesCountForTimeslots($deliveryDate='',$timeslots_id=''){
//      $DeliveryCondition = ' s.timeslots_id = '. $this->db->escape($timeslots_id);
//      $DeliveryCondition .= ' AND  s.delivery_date_timeslot Like '.'\'%[{"date":"'.$deliveryDate.'"%\''; 
        $this->db->select(' count(sale_id) as total_sales ');
        $this->db->like('delivery_date_timeslot', '"date":"' . $deliveryDate . '"', 'both');
        $this->db->where('timeslots_id',$timeslots_id);
        $query = $this->db->get('sale');
        if($query -> num_rows() == 1){
            return $query->row()->total_sales;
        }else{
            return 0;
        }
    }

    function ticket_unread_messages( $ticket_id, $user_type ) {
        $count = 0;
        if ( $ticket_id !== 'all' ) {
                $msgs = $this->db->get_where( 'ticket_message', array( 'ticket_id' => $ticket_id ) )->result_array();
        } else if ( $ticket_id == 'all' ) {
                $msgs = $this->db->get( 'ticket_message' )->result_array();
        }
        foreach ( $msgs as $row ) {
                $status = json_decode( $row['view_status'], true );
                foreach ( $status as $type => $row1 ) {
                        if ( $type == $user_type . '_show' ) {
                                if ( $row1 == 'no' ) {
                                        $count ++;
                                }
                        }
                }
        }
        return $count;
    }
    
    function ticket_message_viewed( $ticket_id, $user_type ) {
        $msgs = $this->db->get_where( 'ticket_message', array( 'ticket_id' => $ticket_id ) )->result_array();
        foreach ( $msgs as $row ) {
                $status     = json_decode( $row['view_status'], true );
                $new_status = array();
                foreach ( $status as $type => $row1 ) {
                        if ( $type == $user_type . '_show' ) {
                                $new_status[ $type ] = 'ok';
                        } else {
                                $new_status[ $type ] = $row1;
                        }
                }
                $view_status = json_encode( $new_status );
                $this->db->where( 'ticket_message_id', $row['ticket_message_id'] );
                $this->db->update( 'ticket_message', array(
                        'view_status' => $view_status
                ) );

        }

    }

    
      // Added by satesh 30_04_2020 start
    function deliveryBoyValidate($username, $password) {
        $query = $this->db->query('select * from admin
                                            where  ( phone = ' . $this->db->escape($username) . ' )
                                            and   password = ' . $this->db->escape($password) . '
                                            and role="4"   
                                    ');
     
        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }        
    // Added by satesh 30_04_2020 end
    
     // Added by satesh 30_04_2020 Start
    function getDeliveryBoyOrderdata($condition='',$limit=10 ,$page = 0, $sortBy='sale_id', $orderBy='DESC')
    {
        $offset = $limit * $page;
        $query = $this->db->query('
            SELECT s.*,c.wallet_type
            FROM sale AS s
            JOIN user AS c ON (c.user_id = s.buyer)
            WHERE '.$condition.'
            ORDER BY '.$sortBy.' '.$orderBy.'
            LIMIT '.$offset.', '.$limit.'
        ');

        $query1 = $this->db->query('
            SELECT s.*,c.wallet_type
            FROM sale s
            JOIN user c ON (c.user_id = s.buyer)
            WHERE '.$condition.'
        ');

        if ($query !== FALSE && $query->num_rows() > 0) {
            $totcount = $query1->num_rows();

            return array("query_result" => $query->result_array(), "totalRecords" => $totcount);
        } else {
            return false;
        }
    }

    function getDeliveryBoyRevenuedata($condition='',$limit=10 ,$page = 0, $sortBy='sale_id', $orderBy='DESC')
    {
        $offset = $limit * $page;
        $query = $this->db->query('
            SELECT *
            FROM sale
            WHERE '.$condition.'
            ORDER BY '.$sortBy.' '.$orderBy.'
            LIMIT '.$offset.', '.$limit.'
        ');
        // from  sale where admin_id=16 group by admin_id
        $query1 = $this->db->query("
            SELECT
                COUNT(sale_id) AS total_orders,
                SUM(delivery_charge) AS charge_in_egp,
                SUM(ROUND((CAST(JSON_UNQUOTE(JSON_EXTRACT(user_choice, '$[0].currency_conversion')) AS UNSIGNED)) * delivery_charge, 2)) AS charge_in_usd
            FROM sale
            WHERE $condition
        ");

        if ($query !== FALSE && $query->num_rows() > 0)
        {
            $totcount = $query1->num_rows();

            return array("query_result" => $query->result_array(), "requiredCounts" => $query1->result_array());
        } else {
            return false;
        }
    }

    function getDeliveryBoyDistinctTimeslots($condition='')
    {
     $query = $this->db->query('
                                    select  distinct(delivery_date_timeslot),count(sale_id) as assigned_count
                                    from  sale
                                    where '.$condition.'
                                    group by delivery_date_timeslot
                                    order by sale_id DESC 
                                ');
   
     if($query!==FALSE  &&  $query->num_rows()>0)
        {
          return $query->result_array();
        }else{
           
            return false;
        } 
    }

    function getDeliveryBoyRevenueCounts($condition='')
    {
     //  from  sale where admin_id=16 group by admin_id
        $query = $this->db->query("
            SELECT
                COUNT(sale_id) AS total_orders,
                IFNULL(SUM(delivery_charge), 0) AS charge_in_egp,
                IFNULL(
                    SUM(ROUND((CAST(JSON_UNQUOTE(JSON_EXTRACT(user_choice, '$[0].currency_conversion')) AS UNSIGNED)) * delivery_charge, 2)),
                    0
                ) AS charge_in_usd
            FROM
                sale
            WHERE
                $condition
        ");
     
     if($query!==FALSE  &&  $query->num_rows()>0)
        {
//          $totcount = $query1->num_rows();
          return array("requiredCounts" => $query->result_array());
        }else{
           
            return false;
        } 
    }
    
    function getUserAddress($address_id = 0, $user_id = 0){
        $condition = " 1=1 ";
        $condition .=  " AND ua.address_id = ". $this->db->escape($address_id);
        $condition .=  " AND ua.user_id  =  ". $this->db->escape($user_id);

        $query = $this->db->query("SELECT ua.user_id, ua.address_id, ua.title, ua.number, ua.langlat,ua.address_1,ua.address_2,ua.landmark,ua.pincode, c.city_id, c.city_name_en, c.city_name_ar, a.area_id, a.area_name_en, a.area_name_ar
                                FROM `user_address` ua
                                JOIN city AS c ON ua.city_id = c.city_id
                                JOIN area AS a ON ua.area_id = a.area_id
                                WHERE $condition");

        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    function getTimeSlotData($day_number) {
        $query = $this->db->query("SELECT *
FROM timeslots
WHERE status = 'ok' AND day = DAYNAME(DATE_ADD(CURDATE(), INTERVAL $day_number DAY))
");

        if ($query !== FALSE && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
    // Added by satesh 30_04_2020 End

}
