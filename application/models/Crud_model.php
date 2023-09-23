<?php
if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );
}

class Crud_model extends CI_Model {

        function __construct() {
		parent::__construct();
	}

	function clear_cache() {
		$this->output->set_header( 'Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0' );
		$this->output->set_header( 'Pragma: no-cache' );
	}

	//added by dev -- Start
	function get_data( $table, $condition = '1=1', $select = '*' ) {
		$query = $this->db->query( 'Select ' . $select . ' From ' . $table . ' Where ' . $condition );
		if ( $query !== false && $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	function get_total_call_order( $product_id, $start_time ) {
		$query = $this->db->query( 'Select count(c.cart_id) as total_call_sale
                                   From cart as c
                                   Left Join sale as s on(s.sale_id = c.sale_id)
                                   Where c.product_id = ' . $this->db->escape( $product_id ) . ' And s.order_from = "telecaller" And s.sale_datetime >= ' . strtotime( date( 'Y-m-d ' . $start_time ) ) . '
                                   
                                    ' );
		if ( $query !== false && $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	function get_total_call_order_paid( $product_id, $start_time ) {
		$query = $this->db->query( 'Select count(c.cart_id) as total_call_sale_paid
                                   From cart as c
                                   Left Join sale as s on(s.sale_id = c.sale_id)
                                   Where c.product_id = ' . $this->db->escape( $product_id ) . ' And s.payment_status Like \'%,"status":"paid"}%\' And s.order_from = "telecaller" And s.sale_datetime >= ' . strtotime( date( 'Y-m-d ' . $start_time ) ) . '
                                   
                                    ' );
		if ( $query !== false && $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	function get_sales_data( $product_id, $start_time ) {
		$query = $this->db->query( 'Select s.*
                                   From cart as c
                                   Left Join sale as s on(s.sale_id = c.sale_id)
                                   Where c.product_id = ' . $this->db->escape( $product_id ) . '  And s.sale_datetime >= ' . strtotime( date( 'Y-m-d ' . $start_time ) ) . '
                                   
                                    ' );
		if ( $query !== false && $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	function get_total_sales_data( $product_id, $start_time ) {
		$query = $this->db->query( 'Select count(c.cart_id) as total_sales
                                   From cart as c
                                   Left Join sale as s on(s.sale_id = c.sale_id)
                                   Where c.product_id = ' . $this->db->escape( $product_id ) . '  And s.sale_datetime >= ' . strtotime( date( 'Y-m-d ' . $start_time ) ) . '
                                   
                                    ' );
		if ( $query !== false && $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	function get_top_seller( $product_id, $start_time ) {
		$query = $this->db->query( 'Select count(c.sale_id) as total_sale,s.created_by,a.name
                                   From cart as c
                                   Left Join sale as s on(s.sale_id = c.sale_id)
                                   Left Join admin as a on(a.admin_id = s.created_by)
                                   Where c.product_id = ' . $this->db->escape( $product_id ) . ' And s.order_from = "telecaller" And s.sale_datetime >= ' . strtotime( date( 'Y-m-d ' . $start_time ) ) . '
                                   Group by s.created_by
                                    ' );
		if ( $query !== false && $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	function get_total_order( $product_id, $start_time ) {
		$query = $this->db->query( 'Select count(c.cart_id) as total_sale
                                   From cart as c
                                   Left Join sale as s on(s.sale_id = c.sale_id)
                                   Where c.product_id = ' . $this->db->escape( $product_id ) . '  And s.sale_datetime >= ' . strtotime( date( 'Y-m-d ' . $start_time ) ) . '
                                    ' );
		if ( $query !== false && $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	//added by dev -- End

	/////////GET NAME BY TABLE NAME AND ID/////////////
	function get_type_name_by_id( $type, $type_id = '', $field = 'name' ) {
		if ( $type_id != '' ) {
			$l = $this->db->get_where( $type, array(
				$type . '_id' => $type_id
			) );
			$n = $l->num_rows();
			if ( $n > 0 ) {
				return $l->row()->$field;
			}
		}
	}

	function get_settings_value( $type, $type_name = '', $field = 'value' ) {
		if ( $type_name != '' ) {
			return $this->db->get_where( $type, array( 'type' => $type_name ) )->row()->$field;
		}
	}

	/////////Filter One/////////////
	function filter_one( $table, $type, $value ) {
		$this->db->select( '*' );
		$this->db->from( $table );
		$this->db->where( $type, $value );

		return $this->db->get()->result_array();
	}

	// FILE_UPLOAD
	function img_thumb( $type, $id, $ext = '.jpg', $width = '100', $height = '100' ) {
		$this->load->library( 'image_lib' );
		ini_set( "memory_limit", "-1" );

		$config1['image_library']  = 'gd2';
		$config1['create_thumb']   = true;
		$config1['maintain_ratio'] = true;
		$config1['width']          = $width;
		$config1['height']         = $height;
		$config1['source_image']   = 'uploads/' . $type . '_image/' . $type . '_' . $id  . $ext;
			
                $this->image_lib->initialize( $config1 );
		$this->image_lib->resize();
		$this->image_lib->clear();
	}

	// FILE_UPLOAD
	function file_up( $name, $type, $id, $multi = '', $no_thumb = '', $ext = '.jpg' ) {
		if ( $multi == '' ) {
			move_uploaded_file( $_FILES[ $name ]['tmp_name'], 'uploads/' . $type . '_image/' . $type . '_' . $id . $ext );
			if ( $no_thumb == '' ) {
				$this->crud_model->img_thumb( $type, $id, $ext );
			}
		} elseif ( $multi == 'multi' ) {
			$ib = 1;
	               
			foreach ( $_FILES[ $name ]['name'] as $i => $row ) {
				$ib = $this->file_exist_ret( $type, $id, $ib );
				move_uploaded_file( $_FILES[ $name ]['tmp_name'][ $i ], 'uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $ib . $ext );
				if ( $no_thumb == '' ) {
					$this->crud_model->img_thumb( $type, $id . '_' . $ib, $ext );
				}
			}
		}
	}



	function file_up_docs( $name, $type, $id, $multi = '', $ext = '.pdf' ) {
		$config           = array();
		$upload_file_flag = false;

		if ( isset( $_FILES ) && isset( $_FILES[ $name ]["tmp_name"] ) && ! empty( $_FILES[ $name ]["tmp_name"] ) ) {
			$upload_file_flag = true;

			//$config['upload_path'] = DOC_ROOT_FRONT."/images/logos/";
			$config['max_size']      = '5000';
			$config['allowed_types'] = 'jpg|pdf|docx';
			//$config['file_name']     = md5(uniqid("100_ID", true));
		}
		$this->load->library( 'upload', $config );

		if ( $multi == '' ) {
			move_uploaded_file( $_FILES[ $name ]['tmp_name'], 'uploads/' . $type . '_docs/' . $type . '_' . $id . $ext );

		} elseif ( $multi == 'multi' ) {
			$ib = 1;
			foreach ( $_FILES[ $name ]['name'] as $i => $row ) {
				$ib = $this->file_exist_ret( $type, $id, $ib );
				move_uploaded_file( $_FILES[ $name ]['tmp_name'][ $i ], 'uploads/' . $type . '_docs/' . $type . '_' . $id . '_' . $ib . $ext );

			}
		}
	}

	// FILE_UPLOAD : EXT :: FILE EXISTS
	function file_exist_ret( $type, $id, $ib, $ext = '.jpg' ) {
		if ( file_exists( 'uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $ib . $ext ) ) {
			$ib = $ib + 1;
			$ib = $this->file_exist_ret( $type, $id, $ib );

			return $ib;
		} else {
			return $ib;
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
                        return '<img src="' . $srcl . '?dummy='.time().'" height="' . $height . '" width="' . $width . '" />';
                    } elseif ($src == 'src') {
                        return $srcl.'?dummy='.time();
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
                            $return[] = '<img src="' . $srcl . '?dummy='.time().'" height="' . $height . '" width="' . $width . '" />';
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
                        return $return[0].'?dummy='.time();
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

        
        //added by mypcot team : start
            function check_for_thumb($type, $id, $ext = '.jpg'){
                 if (! file_exists("uploads/" . $type . "_image_live/" . $type . "_" . $id . "_thumb" . $ext)) {
                         $this->crud_model->img_thumb($type, $id, $ext);
                    }
            }

            function file_move($type, $id, $ext = '.jpg', $multi = '', $m_sin = ''){
                if ($multi == '') {
                    if (file_exists('uploads/' . $type . '_image/' . $type . '_' . $id . $ext)) {
                        rename("uploads/" . $type . "_image/" . $type . "_" . $id . $ext , "uploads/" . $type . "_image_temp/" . $type . "_" . $id . $ext);
                    }
                    if (file_exists("uploads/" . $type . "_image/" . $type . "_" . $id . "_thumb" . $ext)) {
                        rename("uploads/" . $type . "_image/" . $type . "_" . $id . $ext , "uploads/" . $type . "_image_temp/" . $type . "_" . $id . $ext);
                    }

                } else if ($multi == 'multi') {
                    $num = $this->crud_model->get_type_name_by_id($type, $id, 'num_of_imgs');
                    if ($m_sin == '') {
                        $i = 0;
                        $p = 0;
                        while ($p < $num) {
                            $i++;
                            if (file_exists('uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $i . $ext)) {
                                rename("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $i . $ext , "uploads/" . $type . "_image_temp/" . $type . "_" . $id . '_' . $i . $ext);
                                $p++;
                                $data['num_of_imgs'] = $num - $p;
                                $this->db->where($type . '_id', $id);
                                $this->db->update($type, $data);
                            }

                            if (file_exists("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $i . "_thumb" . $ext)) {
                                rename("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $i . "_thumb" . $ext , "uploads/" . $type . "_image_temp/" . $type . "_" . $id . '_' . $i . "_thumb" . $ext);
                            }
                            if ($i > 50) {
                                break;
                            }

                        }
                    } else {
                        if (file_exists('uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $m_sin . $ext)) {
                            rename("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $m_sin . $ext , "uploads/" . $type . "_image_temp/" . $type . "_" . $id . '_' . $m_sin . $ext);
                        }
                        if (file_exists("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $m_sin . "_thumb" . $ext)) {
                            rename("uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $m_sin . "_thumb" . $ext , "uploads/" . $type . "_image_temp/" . $type . "_" . $id . '_' . $m_sin . "_thumb" . $ext);
                        }
                        $data['num_of_imgs'] = $num - 1;
                        $this->db->where($type . '_id', $id);
                        $this->db->update($type, $data);
                    }
                }
            }


            function file_rename_move($type, $id, $ext = '.jpg', $oldname = '', $newname = ''){
                    if (file_exists('uploads/' . $type . '_image/' . $newname. $ext)) {
                        unlink("uploads/" . $type . "_image/" . $newname . $ext);
                    }
                    if (file_exists("uploads/" . $type . "_image/" . $newname . "_thumb" . $ext)) {
                        unlink("uploads/" . $type . "_image/" . $newname . "_thumb" . $ext);
                    }

                    copy(realpath("uploads/" . $type . "_image_temp/" . $oldname . $ext) , "uploads/" . $type . "_image_final_temp/" .$newname . $ext);
                    copy(realpath("uploads/" . $type . "_image_temp/" . $oldname ."_thumb" . $ext ), "uploads/" . $type . "_image_final_temp/" .$newname ."_thumb" . $ext);
                    rename("uploads/" . $type . "_image_final_temp/" .$newname . $ext, "uploads/" . $type . "_image/" .$newname . $ext);
                    rename("uploads/" . $type . "_image_final_temp/" .$newname ."_thumb" . $ext, "uploads/" . $type . "_image/" .$newname ."_thumb" . $ext);
                    unlink("uploads/" . $type . "_image_temp/" . $oldname . $ext);
                    unlink("uploads/" . $type . "_image_temp/" . $oldname  ."_thumb" . $ext);
                    $num = $this->crud_model->get_type_name_by_id($type, $id, 'num_of_imgs');
                    $data['num_of_imgs'] = $num + 1;
                    $this->db->where($type . '_id', $id);
                    $this->db->update($type, $data);
            }

            //added by mypcot team :: end

    // function delete_image($tbl_name,$condition){
    // 			echo "<pre>";
    // 			print_r($condition);
    			
    // 	$image_data = $this->db->get_where( $tbl_name, $condition)->result_array();

    // 				echo "<pre>";
    // 				print_r($image_data);
    // 				exit();

    // }

	// FILE_VIEW
	function file_dlt( $type, $id, $ext = '.jpg', $multi = '', $m_sin = '' ) {
		if ( $multi == '' ) {
			if ( file_exists( 'uploads/' . $type . '_image/' . $type . '_' . $id . $ext ) ) {
				unlink( "uploads/" . $type . "_image/" . $type . "_" . $id . $ext );
			}
			if ( file_exists( "uploads/" . $type . "_image/" . $type . "_" . $id . "_thumb" . $ext ) ) {
				unlink( "uploads/" . $type . "_image/" . $type . "_" . $id . "_thumb" . $ext );
			}

		} else if ( $multi == 'multi' ) {
			$num = $this->crud_model->get_type_name_by_id( $type, $id, 'num_of_imgs' );
			if ( $m_sin == '' ) {
				$i = 0;
				$p = 0;
				while ( $p < $num ) {
					$i ++;
					if ( file_exists( 'uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $i . $ext ) ) {
						unlink( "uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $i . $ext );
						$p ++;
						$data['num_of_imgs'] = $num - 1;
						$this->db->where( $type . '_id', $id );
						$this->db->update( $type, $data );
					}

					if ( file_exists( "uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $i . "_thumb" . $ext ) ) {
						unlink( "uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $i . "_thumb" . $ext );
					}
					if ( $i > 50 ) {
						break;
					}
				}
			} else {
				if ( file_exists( 'uploads/' . $type . '_image/' . $type . '_' . $id . '_' . $m_sin . $ext ) ) {
					unlink( "uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $m_sin . $ext );
				}
				if ( file_exists( "uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $m_sin . "_thumb" . $ext ) ) {
					unlink( "uploads/" . $type . "_image/" . $type . "_" . $id . '_' . $m_sin . "_thumb" . $ext );
				}
				$data['num_of_imgs'] = $num - 1;
				$this->db->where( $type . '_id', $id );
				$this->db->update( $type, $data );
			}
		}
	}

	//DELETE MULTIPLE ITEMS
	function multi_delete( $type, $ids_array ) {
		foreach ( $ids_array as $row ) {
			$this->file_dlt( $type, $row );
			$this->db->where( $type . '_id', $row );
			$this->db->delete( $type );
		}
	}

	//DELETE SINGLE ITEM
	function single_delete( $type, $id ) {
		$this->file_dlt( $type, $id );
		$this->db->where( $type . '_id', $id );
		$this->db->delete( $type );
	}

	//GET PRODUCT LINK
	//Changed by Dawpro start:
	function product_link( $product_id, $quick = '' ) {
//		if ( $quick == 'quick' ) {
//			return base_url() . 'index.php/home/quick_view/' . $product_id;
//		} else {
		$name = url_title( $this->crud_model->get_type_name_by_id( 'product', $product_id, 'title' ) );

		return base_url() . 'index.php/home/product_view/' . $product_id . '/' . $name;
//		}
	}
	//Changed by Dawpro end;

	//GET PRODUCT LINK
	function blog_link( $blog_id ) {
		$name = url_title( $this->crud_model->get_type_name_by_id( 'blog', $blog_id, 'title' ) );

		return base_url() . 'index.php/home/blog_view/' . $blog_id . '/' . $name;
	}

	//GET PRODUCT LINK
	function vendor_link( $vendor_id ) {
		$name = url_title( $this->crud_model->get_type_name_by_id( 'vendor', $vendor_id, 'display_name' ) );

		return base_url() . 'index.php/home/vendor_profile/' . $vendor_id . '/' . $name;
	}

	/////////GET CHOICE TITLE////////
	function choice_title_by_name( $product, $name ) {
		$return  = '';
		$options = json_encode( $this->get_type_name_by_id( 'product', $product_id, 'options' ), true );
		foreach ( $options as $row ) {
			if ( $row['name'] == $name ) {
				$return = $row['title'];
			}
		}

		return $return;
	}

	/////////SELECT HTML/////////////
	function select_html( $from, $name, $field, $type, $class, $e_match = '', $condition = '', $c_match = '', $onchange = '', $condition_type = 'single' ) {
//            echo "<pre>";
//            echo("from|".$from).'<br>';
//            echo("name|".$name).'<br>';
//            echo("field|".$field).'<br>';
//            echo("type|".$type).'<br>';
//            echo("class|".$class).'<br>';
//            echo("e_match|".$e_match).'<br>';
//            echo("condition|".$condition).'<br>';
//            echo("c_match|".$c_match).'<br>';
//            echo("onchange|".$onchange).'<br>';
//            echo("condition_type|".$condition_type).'<br>';
            
            
            $return = '';
		$other  = '';
		$multi  = 'no';
		$phrase = 'Choose a ' . $name;
            
                
		if ( $class == 'demo-cs-multiselect' ) {
			$other = 'multiple';
			$name  = $name . '[]';
			if ( $type == 'edit' ) {
				$e_match = json_decode( $e_match );
				if ( $e_match == null ) {
					$e_match = array();
				}
				$multi = 'yes';
			}
		}
		$return = '<select name="' . $name . '" onChange="' . $onchange . '(this.value,this)" class="' . $class . '" ' . $other . '  data-placeholder="' . $phrase . '" tabindex="2" data-hide-disabled="true" >';
		
                
                if ( ! is_array( $from ) ) {
                   
			if ( $condition == '' ) {
				$all = $this->db->get( $from )->result_array();
			} else if ( $condition !== '' ) {
				if ( $condition_type == 'single' ) {
					$all = $this->db->get_where( $from, array(
						$condition => $c_match
					) )->result_array();
				} else if ( $condition_type == 'multi' ) {
					$this->db->where_in( $condition, $c_match );
					$all = $this->db->get( $from )->result_array();
				}
			}

			$return .= '<option value="">Choose one</option>';

			$display_name = '';
			foreach ( $all as $row ):

				//added by ritesh : start
				$display_name = '';
				if ( strpos( $field, '|' ) !== false ) {
					$field_name = explode( '|', $field );
					foreach ( $field_name as $key => $value ) {
						$display_name .= $row[ $value ] . ' ';
					}
				} else {
					$display_name = $row[ $field ];
				}
				//added by Ritesh : end

				if ( $type == 'add' ) {
					$return .= '<option value="' . $row[ $from . '_id' ] . '">' . $display_name . '</option>';
				} else if ( $type == 'edit' ) {
					$return .= '<option value="' . $row[ $from . '_id' ] . '" ';
					if ( $multi == 'no' ) {
						if ( $row[ $from . '_id' ] == $e_match ) {
							$return .= 'selected=."selected"';
						}
					} else if ( $multi == 'yes' ) {
						if ( in_array( $row[ $from . '_id' ], $e_match ) ) {
							$return .= 'selected=."selected"';
						}
					}
					$return .= '>' . $display_name . '</option>';
				}
			endforeach;
		} else {
			$all    = $from;
			$return .= '<option value="">Choose one</option>';
			foreach ( $all as $row ):
				if ( $type == 'add' ) {
					$return .= '<option value="' . $row . '">';
					if ( $condition == '' ) {
						$return .= ucfirst( str_replace( '_', ' ', $row ) );
					} else {
						$return .= $this->crud_model->get_type_name_by_id( $condition, $row, $c_match );
					}
					$return .= '</option>';
				} else if ( $type == 'edit' ) {
					$return .= '<option value="' . $row . '" ';
					if ( $row == $e_match ) {
						$return .= 'selected=."selected"';
					}
					$return .= '>';

					if ( $condition == '' ) {
						$return .= ucfirst( str_replace( '_', ' ', $row ) );
					} else {
						$return .= $this->crud_model->get_type_name_by_id( $condition, $row, $c_match );
					}

					$return .= '</option>';
				}
			endforeach;
		}
		$return .= '</select>';

		return $return;
	}

	//CHECK IF PRODUCT EXISTS IN TABLE
	function exists_in_table( $table, $field, $val ) {
		$ret = '';
		$res = $this->db->get( $table )->result_array();
		foreach ( $res as $row ) {
			if ( $row[ $field ] == $val ) {
				$ret = $row[ $table . '_id' ];
			}
		}
		if ( $ret == '' ) {
			return false;
		} else {
			return $ret;
		}

	}


	//aDDED BY rITESH : stART
	function input_html( $label, $type, $id = '', $name = '', $class = '', $placeholder = '', $value = '', $option = '', $hidden = '', $hiddenName = '', $hiddenValue = '' ) {
		$return = '';

		if ( isset( $label ) && isset( $type ) ) {
			$return .= ' <div class="form-group btm_border">';
			$return .= '     <label class="col-sm-4 control-label"';
			$return .= '        for="' . $name . '">';
			$return .= translate( $label ) . '</label>';
			$return .= ' <div class="col-sm-6">';
			$return .= ' <input type="' . $type . '" name="' . $name . '" id="' . $id . '" placeholder="' . $placeholder . '" class="' . $class . '"';
			if ( isset( $value ) && ! empty( $value ) ) {
				$return .= ' value="' . $value . '" ';
			}
			if ( isset( $option ) && ! empty( $option ) ) {
				$return .= $option;
			}

			$return .= ' > ';
			if ( isset( $hidden ) && $hidden == 'hidden' && ! empty( $hiddenValue ) && ! empty( $hiddenName ) ) {
				$return .= ' <input type="' . $hidden . '" name="' . $hiddenName . '" id="' . $hiddenName . '" value="' . $hiddenValue . '" >';

			}
			$return .= '    </div>';
			$return .= '</div>';

		}

		return $return;
	}
	//ADDED by ritesh : end

	//FORM FIELDS
	function form_fields( $array ) {
		$return = '';
		foreach ( $array as $row ) {
			$return .= '<div class="form-group">';
			$return .= '    <label class="col-sm-4 control-label" for="demo-hor-inputpass">' . $row . '</label>';
			$return .= '    <div class="col-sm-6">';
			$return .= '       <input type="text" name="ad_field_values[]" id="demo-hor-inputpass" class="form-control">';
			$return .= '       <input type="hidden" name="ad_field_names[]" value="' . $row . '" >';
			$return .= '    </div>';
			$return .= '</div>';
		}

		return $return;
	}

	// PAGINATION
	function pagination( $type, $per, $link, $f_o, $f_c, $other, $current, $seg = '3', $ord = 'desc' ) {
		$t   = explode( '#', $other );
		$t_o = $t[0];
		$t_c = $t[1];
		$c   = explode( '#', $current );
		$c_o = $c[0];
		$c_c = $c[1];

		$this->load->library( 'pagination' );
		$this->db->order_by( $type . '_id', $ord );
		$config['total_rows']  = $this->db->count_all_results( $type );
		$config['base_url']    = base_url() . $link;
		$config['per_page']    = $per;
		$config['uri_segment'] = $seg;

		$config['first_link']      = '&laquo;';
		$config['first_tag_open']  = $t_o;
		$config['first_tag_close'] = $t_c;

		$config['last_link']      = '&raquo;';
		$config['last_tag_open']  = $t_o;
		$config['last_tag_close'] = $t_c;

		$config['prev_link']      = '&lsaquo;';
		$config['prev_tag_open']  = $t_o;
		$config['prev_tag_close'] = $t_c;

		$config['next_link']      = '&rsaquo;';
		$config['next_tag_open']  = $t_o;
		$config['next_tag_close'] = $t_c;

		$config['full_tag_open']  = $f_o;
		$config['full_tag_close'] = $f_c;

		$config['cur_tag_open']  = $c_o;
		$config['cur_tag_close'] = $c_c;

		$config['num_tag_open']  = $t_o;
		$config['num_tag_close'] = $t_c;
		$this->pagination->initialize( $config );

		$this->db->order_by( $type . '_id', $ord );

		return $this->db->get( $type, $config['per_page'], $this->uri->segment( $seg ) )->result_array();
	}

	//IF PRODUCT ADDED TO CART
	function is_added_to_cart( $product_id, $set = '', $op = '' ) {
		$carted = $this->cart->contents();
		//var_dump($carted);
		if ( count( $carted ) > 0 ) {

			foreach ( $carted as $items ) {
//				log_message("ERROR",print_r($items,1));
//				log_message("ERROR",print_r($product_id,1));
				if ( $items['variation_id'] == $product_id ) {

					if ( $set == '' ) {
						return true;
					} else {
						if ( $set == 'option' ) {
							$option = json_decode( $items[ $set ], true );
							if ( ! empty( $option ) && isset( $option[ $op ] ) ) {
								return $option[ $op ]['value'];
							} else {
								return false;
							}
						} else {
							return $items[ $set ];
						}
					}
				}
			}
		} else {
			return false;
		}
	}

	//TOTALING OF CART ITEMS BY TYPE
	function cart_total_it( $type ) {
		$carted = $this->cart->contents();
		$ret    = 0;
		if ( count( $carted ) > 0 ) {
			foreach ( $carted as $items ) {
				$ret += $items[ $type ] * $items['qty'];
			}

			return $ret;
		} else {
			return false;
		}
	}


	//SALE WISE TOTAL BY TYPE
	function db_sale_total_it( $sale_id, $type ) {
		$carted = json_decode( $this->db->get_where( 'sale', array(
			'sale_id' => $sale_id
		) )->row()->product_details, true );
		$ret    = 0;
		if ( count( $carted ) > 0 ) {
			foreach ( $carted as $items ) {
				$ret += $items[ $type ] * $items['qty'];
			}

			return $ret;
		} else {
			return false;
		}
	}


	//GETTING ADDITIONAL FIELDS FOR PRODUCT ADD
	function get_additional_fields( $product_id ) {
		$additional_fields = $this->crud_model->get_type_name_by_id( 'product', $product_id, 'additional_fields' );
		$ab                = json_decode( $additional_fields, true );
		$name              = json_decode( $ab['name'] );
		$value             = json_decode( $ab['value'] );
		$final             = array();
		if ( ! empty( $name ) ) {
			foreach ( $name as $n => $row ) {
				$final[] = array(
					'name'  => $row,
					'value' => $value[ $n ]
				);
			}
		}

		return $final;
	}

	//DECREASEING PRODUCT QUANTITY
	function decrease_quantity( $product_id, $quantity, $sale_id = '' ) {
		$prev_quantity          = $this->crud_model->get_type_name_by_id( 'product', $product_id, 'current_stock' );
		$data1['current_stock'] = $prev_quantity - $quantity;
		if ( $data1['current_stock'] < 0 ) {
			$data1['current_stock'] = 0;
		}
		$this->db->where( 'product_id', $product_id );
		$this->db->update( 'product', $data1 );
	}

	//DECREASEING VARIANT QUANTITY
	function decrease_variant_quantity( $product_id, $quantity, $variant_id = '' ) {
		$prev_quantity          = $this->crud_model->get_type_name_by_id( 'variation', $variant_id, 'current_stock' );
		$data1['current_stock'] = $prev_quantity - $quantity;
		if ( $data1['current_stock'] < 0 ) {
			$data1['current_stock'] = 0;
		}
		$this->db->where( 'product_id', $product_id );
		$this->db->where( 'variation_id', $variant_id );
		$this->db->update( 'variation', $data1 );
	}

	//INCREASEING PRODUCT QUANTITY
	function increase_quantity( $product_id, $quantity, $sale_id = '' ) {
		$prev_quantity          = $this->crud_model->get_type_name_by_id( 'product', $product_id, 'current_stock' );
		$data1['current_stock'] = $prev_quantity + $quantity;
		if ( $data1['current_stock'] < 0 ) {
			$data1['current_stock'] = 0;
		}
		$this->db->where( 'product_id', $product_id );
		$this->db->update( 'product', $data1 );
	}

//INCREASEING VARIANT QUANTITY
	function increase_variant_quantity( $product_id, $quantity, $variant_id = '' ) {
		$prev_quantity          = $this->crud_model->get_type_name_by_id( 'variation', $variant_id, 'current_stock' );
		$data1['current_stock'] = $prev_quantity + $quantity;
		if ( $data1['current_stock'] < 0 ) {
			$data1['current_stock'] = 0;
		}
		$this->db->where( 'product_id', $product_id );
		$this->db->where( 'variation_id', $variant_id );
		$this->db->update( 'variation', $data1 );
	}

	//IF PRODUCT IS IN SALE
	function product_in_sale( $sale_id, $product_id, $field ) {
		$return          = '';
		$product_details = json_decode( $this->get_type_name_by_id( 'sale', $sale_id, 'product_details' ), true );
		foreach ( $product_details as $row ) {
			if ( $row['id'] == $product_id ) {
				$return = $row[ $field ];
			}
		}
		if ( $return == '' ) {
			return false;
		} else {
			return $return;
		}
	}

	//GETTING IDS OF A TABLE FILTERING SPECIFIC TYPE OF VALUE RANGE
	function ids_between_values( $table, $value_type, $up_val, $down_val ) {
		$this->db->order_by( $table . '_id', 'desc' );

		return $this->db->get_where( $table, array(
			$value_type . ' <=' => $up_val,
			$value_type . ' >=' => $down_val
		) )->result_array();
	}

	//DAYS START-END TIMESTAMP
	function date_timestamp( $date, $type ) {
		$date = explode( '-', $date );
		$d    = $date[2];
		$m    = $date[1];
		$y    = $date[0];
		if ( $type == 'start' ) {
			return mktime( 0, 0, 0, $m, $d, $y );
		}
		if ( $type == 'end' ) {
			return mktime( 0, 0, 0, $m, $d + 1, $y );
		}
	}

	//GETTING STOCK REPORT
	function stock_report( $product_id ) {
		$report = array();
		$start  = $this->get_type_name_by_id( 'product', $product_id, 'add_timestamp' );
		$end    = time();
		$stock  = 0;

		$diff = 86400;
		$days = array();
		while ( $end > $start ) {
			$date       = date( 'Y-m-d', $start );
			$start      += $diff;
			$dstart     = $this->date_timestamp( $date, 'start' );
			$dend       = $this->date_timestamp( $date, 'end' );
			$all_stocks = $this->ids_between_values( 'stock', 'datetime', $dend, $dstart );

			$all_stocks = array_reverse( $all_stocks );

			foreach ( $all_stocks as $row ) {
				if ( $row['product'] == $product_id ) {
					if ( $row['type'] == 'add' ) {
						$stock += $row['quantity'];
					} else if ( $row['type'] == 'destroy' ) {
						$stock -= $row['quantity'];
					}
				}
			}
			$report[] = array(
				'date'  => $date,
				'stock' => $stock
			);
		}

		//return array_reverse($report);
		return $report;
	}


	//GETTING VARIATION STOCK REPORT
	function variation_stock_report( $variation_id, $product_id ) {
		$report     = array();
		$start_date = $this->get_type_name_by_id( 'variation', $variation_id, 'created_on' );
		$start      = strtotime( $start_date );
		$end        = time();
		$stock      = 0;
		$diff       = 86400;
		$days       = array();
		while ( $end > $start && $start > 0 ) {
			$date       = date( 'Y-m-d', $start );
			$start      += $diff;
			$dstart     = $this->date_timestamp( $date, 'start' );
			$dend       = $this->date_timestamp( $date, 'end' );
			$all_stocks = $this->ids_between_values( 'stock', 'datetime', $dend, $dstart );

			$all_stocks = array_reverse( $all_stocks );
			foreach ( $all_stocks as $row ) {
				if ( $row['variation_id'] == $variation_id ) {
					if ( $row['type'] == 'add' ) {
						$stock += $row['quantity'];
					} else if ( $row['type'] == 'destroy' ) {
						$stock -= $row['quantity'];
					}
				}
			}
			$report[] = array(
				'date'  => $date,
				'stock' => $stock
			);
		}

		//return array_reverse($report);
		return $report;
	}

	//GETTING ALL SALE DATES
	function all_sale_date( $product_id ) {
		$dates = array();
		$sales = $this->db->get( 'sale' )->result_array();
		foreach ( $sales as $i => $row ) {
			if ( $this->product_in_sale( $row['sale_id'], $product_id, 'id' ) ) {
				$date = $this->get_type_name_by_id( 'sale', $row['sale_id'], 'sale_datetime' );
				$date = date( 'Y-m-d', $date );
				if ( ! in_array( $date, $dates ) ) {
					array_push( $dates, $date );
				}
			}
		}

		return $dates;
	}

	//GETTING ALL SALE DATES
	function all_sale_date_n( $product_id ) {
		$dates      = array();
		$first_date = '';
		$sales      = $this->db->get( 'sale' )->result_array();
		foreach ( $sales as $i => $row ) {
			if ( $this->session->userdata( 'title' ) !== 'vendor' || $this->is_sale_of_vendor( $row['sale_id'], $this->session->userdata( 'vendor_id' ) ) ) {
				if ( $this->product_in_sale( $row['sale_id'], $product_id, 'id' ) ) {
					$first_date = $this->get_type_name_by_id( 'sale', $row['sale_id'], 'sale_datetime' );
					break;
				}
			}
		}
		if ( $first_date !== '' ) {
			$current = $first_date;
			$last    = time();
			while ( $current <= $last ) {
				$dates[] = date( 'Y-m-d', $current );
				$current = strtotime( '+1 day', $current );
			}
		}

		return $dates;

	}

	//GETTING SALE DETAILS BY PRODUCT DAYS
	function sale_details_by_product_date( $product_id, $date, $type ) {

		$return   = 0;
		$up_val   = $this->date_timestamp( $date, 'end' );
		$down_val = $this->date_timestamp( $date, 'start' );
		$sales    = $this->ids_between_values( 'sale', 'sale_datetime', $up_val, $down_val );
		foreach ( $sales as $i => $row ) {
			if ( $a = $this->product_in_sale( $row['sale_id'], $product_id, $type ) ) {
				$return += $a;
			}
		}

		return $return;
	}

	//GETTING TOTAL OF A VALUE TYPE IN SALES
	function total_sale( $product_id, $field = 'qty' ) {
		$return = 0;
		$sales  = $this->db->get( 'sale' )->result_array();
		foreach ( $sales as $row ) {
			if ( $a = $this->product_in_sale( $row['sale_id'], $product_id, $field ) ) {
				$return += $a;
			}
		}

		return $return;
	}

	//GETTING MOST SOLD PRODUCTS
	function most_sold_products() {
		$result  = array();
		$product = $this->db->get( 'product' )->result_array();
		foreach ( $product as $row ) {
			$result[] = array(
				'id'   => $row['product_id'],
				'sale' => $this->total_sale( $row['product_id'] )
			);
		}
		if ( ! function_exists( 'compare_lastname' ) ) {
			function compare_lastname( $a, $b ) {
				return strnatcmp( $b['sale'], $a['sale'] );
			}
		}

		usort( $result, 'compare_lastname' );

		return $result;

	}


	//GETTING BOOTSTRAP COLUMN VALUE
	function boot( $num ) {
		return ( 12 / $num );
	}

	//GETTING LIMITING CHARECTER
	function limit_chars( $string, $char_limit ) {
		$length = 0;
		$return = array();
		$words  = explode( " ", $string );
		foreach ( $words as $row ) {
			$length += strlen( $row );
			$length += 1;
			if ( $length < $char_limit ) {
				array_push( $return, $row );
			} else {
				array_push( $return, '...' );
				break;
			}
		}

		return implode( " ", $return );
	}

	//GETTING LOGO BY TYPE
	function logo( $type ) {
		$logo = $this->db->get_where( 'ui_settings', array(
			'type' => $type
		) )->row()->value;

//		return base_url() . 'uploads/logo_image/logo_' . $logo . '.png';
		return base_url() . 'uploads/logo_image/logo_' . $logo . '.jpg';
	}

	//GETTING PRODUCT PRICE CALCULATING DISCOUNT edited by ritesh : start
	function get_product_price( $product_id, $price = '', $discount = '', $discount_type = '' ) {
		//added by ritesh : start
		$number = 0;

		if ( ! ( isset( $price ) && ! empty( $price ) ) ) {
			$price = $this->get_type_name_by_id( 'product', $product_id, 'sale_price' );
		}
		if ( ! ( isset( $discount ) && ! empty( $discount ) ) ) {
			$discount = $this->get_type_name_by_id( 'product', $product_id, 'discount' );
		}
		if ( ! ( isset( $discount_type ) && ! empty( $discount_type ) ) ) {
			$discount_type = $this->get_type_name_by_id( 'product', $product_id, 'discount_type' );
		}
		//added by ritesh : end
		// Old
//        if ($discount_type == 'amount') {
//            $number = ($price - $discount);
//        }
//        if ($discount_type == 'percent') {
//            $number = ($price - ($discount * $price / 100));
//        }
//
		// New changes after cart storage in db by Dev -- Start
		$number = $price;
		if ( $discount_type == 'amount' ) {
			if ( $discount > 0 && $discount < (double) $price ) {
				$number = ( $price - $discount );
			}
		}
		if ( $discount_type == 'percent' ) {
			if ( $discount > 0 && $discount < 100 ) {
				$number = ( $price - ( $discount * $price / 100 ) );
			}

		}

		// New changes after cart storage in db by Dev -- END
		return number_format( (float) $number, 2, '.', '' );
	}
	//edited by ritesh : end

	//added by ritesh : start
	//GETTING PRODUCT PRICE CALCULATING DISCOUNT edited by ritesh : start
	function get_variant_product_price( $product_id, $variant_id, $price = '', $discount = '', $discount_type = '' ) {
		//added by ritesh : start
		$number = $price = 0;
		if ( ! ( isset( $price ) && ! empty( $price ) ) ) {
			if ( isset( $variant_id ) && ! empty( $variant_id ) ) {
				$price = $this->get_type_name_by_id( 'variation', $variant_id, 'sale_price' );
			} elseif ( isset( $product_id ) && ! empty( $product_id ) ) {
				$price = $this->get_type_name_by_id( 'product', $product_id, 'sale_price' );
			}
		}
		if ( ! ( isset( $discount ) && ! empty( $discount ) ) ) {
			$discount = $this->get_type_name_by_id( 'product', $product_id, 'discount' );
		}
		if ( ! ( isset( $discount_type ) && ! empty( $discount_type ) ) ) {
			$discount_type = $this->get_type_name_by_id( 'product', $product_id, 'discount_type' );
		}

		$number = $price;
		if ( $discount_type == 'amount' ) {
			if ( $discount > 0 && $discount < (double) $price ) {
				$number = ( $price - $discount );
			}
		}
		if ( $discount_type == 'percent' ) {
			if ( $discount > 0 && $discount < 100 ) {
				$number = ( $price - ( $discount * $price / 100 ) );
			}

		}

		return number_format( (float) $number, 2, '.', '' );
	}
	//added by ritesh : end


	//added by ritesh : start

	//GETTING  DISCOUNT AMOUNT
	function get_discount_amount( $product_id, $price = '', $discount = '', $discount_type = '' ) {

		$number = 0;
		if ( ! ( isset( $price ) && ! empty( $price ) ) ) {
			$price = $this->get_type_name_by_id( 'product', $product_id, 'sale_price' );
		}
		if ( ! ( isset( $discount ) && ! empty( $discount ) ) ) {
			$discount = $this->get_type_name_by_id( 'product', $product_id, 'discount' );
		}
		if ( ! ( isset( $discount_type ) && ! empty( $discount_type ) ) ) {
			$discount_type = $this->get_type_name_by_id( 'product', $product_id, 'discount_type' );
		}

		if ( $discount_type == 'amount' ) {
			$number = $discount;
		}
		if ( $discount_type == 'percent' ) {
			$number = $discount * $price / 100;
		}

		return number_format( (float) $number, 2, '.', '' );
	}
	//added by ritesh : end


	//GETTING SHIPPING COST
	function get_shipping_cost( $product_id, $price = '', $shipping = '' ) {
		if ( ! ( isset( $price ) && ! empty( $price ) ) ) {
			$price = $this->get_type_name_by_id( 'product', $product_id, 'sale_price' );
		}
		if ( ! ( isset( $shipping ) && ! empty( $shipping ) ) ) {
			$shipping = $this->get_type_name_by_id( 'product', $product_id, 'shipping_cost' );
		}
		$shipping_cost_type = $this->get_type_name_by_id( 'business_settings', '3', 'value' );
		if ( $shipping_cost_type == 'product_wise' ) {
			if ( $shipping == '' ) {
				return 0;
			} else {
				return ( $shipping );
			}
		}
		if ( $shipping_cost_type == 'fixed' ) {
			return 0;
		}
	}

	//GETTING PRODUCT TAX
	function get_product_tax( $product_id, $price = '', $tax = '', $tax_type = '' ) {
		if ( ! ( isset( $price ) && ! empty( $price ) ) ) {
			$price = $this->get_type_name_by_id( 'product', $product_id, 'sale_price' );
		}
		if ( ! ( isset( $tax ) && ! empty( $tax ) ) ) {
			$tax = $this->get_type_name_by_id( 'product', $product_id, 'tax' );
		}
		if ( ! ( isset( $tax_type ) && ! empty( $tax_type ) ) ) {
			$tax_type = $this->get_type_name_by_id( 'product', $product_id, 'tax_type' );
		}
//       OLD
//         if ($tax_type == 'amount') {
//            if($tax == ''){
//                return 0;
//            } else {
//                return $tax;
//            }
//        }
//        if ($tax_type == 'percent') {
//            if($tax == ''){
//                $tax = 0;
//            }
//            return ($tax * $price / 100);
//        }
		// New changes after cart storage in db by Dev -- Start
		if ( $tax_type == 'amount' ) {
			if ( $tax == '' ) {
				return 0;
			} else {
				if ( $tax > 0 ) {
					return $tax;
				} else {
					return 0;
				}
			}
		}
		if ( $tax_type == 'percent' ) {
			if ( $tax == '' ) {
				$tax = 0;
			}
			if ( $tax > 0 && $tax < 100 ) {
				return ( $tax * $price / 100 );
			} else {
				return 0;
			}
		}
		// New changes after cart storage in db by Dev -- Start
	}

	//GETTING MONTH'S TOTAL BY TYPE
	function month_total( $type, $filter1 = '', $filter_val1 = '', $filter2 = '', $filter_val2 = '', $notmatch = '', $notmatch_val = '' ) {
		$ago = time() - ( 86400 * 30 );
		$a   = 0;
		if ( $type == 'sale' ) {
			$result = $this->db->get_where( 'sale', array(
				'sale_datetime >= ' => $ago,
				'sale_datetime <= ' => time()
			) )->result_array();
			foreach ( $result as $row ) {
				if ( $this->session->userdata( 'title' ) == 'admin' ) {
					if ( $this->sale_payment_status( $row['sale_id'], 'admin' ) == 'fully_paid' ) {
						//make version for vendor
						$res_cat = $this->db->get_where( 'product', array(
							'category' => $filter_val1
						) )->result_array();
						foreach ( $res_cat as $row1 ) {
							if ( $p = $this->product_in_sale( $row['sale_id'], $row1['product_id'], 'subtotal' ) ) {
								$a += $p;
							}
						}
					}
				}
				if ( $this->session->userdata( 'title' ) == 'vendor' ) {
					if ( $this->sale_payment_status( $row['sale_id'], 'vendor', $this->session->userdata( 'vendor_id' ) ) == 'fully_paid' ) {
						//make version for vendor
						$res_cat = $this->db->get_where( 'product', array(
							'category' => $filter_val1
						) )->result_array();
						foreach ( $res_cat as $row1 ) {
							if ( $p = $this->vendor_share_in_sale( $row['sale_id'], $this->session->userdata( 'vendor_id' ), 'paid' ) ) {
								$p = $p['total'];
								$a += $p;
							}
						}
					}
				}
			}
		} else if ( $type == 'stock' ) {
			if ( $this->session->userdata( 'title' ) == 'admin' ) {
				$this->db->get_where( 'added_by', json_encode( array(
					'type' => 'vendor',
					'id'   => $this->session->userdata( 'vendor_id' )
				) ) );
				$this->db->get_where( 'datetime >= ', $ago );
				$this->db->get_where( 'datetime <= ', time() );
				$result = $this->db->get( 'stock' )->result_array();
				foreach ( $result as $row ) {
					if ( $row[ $filter2 ] == $filter_val2 ) {
						if ( $row[ $filter1 ] == $filter_val1 ) {
							if ( $notmatch == '' ) {
								$a += $row['total'];
							} else {
								if ( $row[ $notmatch ] !== $notmatch_val ) {
									$a += $row['total'];
								}
							}
						}
					}
				}
			}
			if ( $this->session->userdata( 'title' ) == 'vendor' ) {
				$result = $this->db->get_where( 'stock', array(
					'datetime >= ' => $ago,
					'datetime <= ' => time()
				) )->result_array();
				foreach ( $result as $row ) {
					if ( $row[ $filter2 ] == $filter_val2 ) {
						if ( $row[ $filter1 ] == $filter_val1 ) {
							if ( $notmatch == '' ) {
								$a += $row['total'];
							} else {
								if ( $row[ $notmatch ] !== $notmatch_val ) {
									$a += $row['total'];
								}
							}
						}
					}
				}
			}
		}

		return $a;
	}


	//GETTING MONTH'S TOTAL BY TYPE
	function month_total_sale( $type, $filter1 = '', $filter_val1 = '', $filter2 = '', $filter_val2 = '', $notmatch = '', $notmatch_val = '' ) {
		$ago       = time() - ( 86400 * 30 );
		$a         = 0;
		$condition = "1=1 ";
		$condition .= " And sale_datetime >=" . $this->db->escape( $ago );
		$condition .= " And sale_datetime <=" . $this->db->escape( time() );
		if ( isset( $filter2 ) && ! empty( $filter2 ) && isset( $filter_val2 ) && ! empty( $filter_val2 ) ) {
			$condition .= " And " . $filter2 . "=" . $this->db->escape( $filter_val2 );
		}


		if ( $type == 'sale' ) {
			//$result = $this->db->get_where('sale',$condition)->result_array();
			$result_query = $this->db->query( "SELECT * FROM sale WHERE " . $condition );

			$result = array();
			if ( $result_query !== false && $result_query->num_rows() >= 1 ) {
				$result  = $result_query->result_array();
				$res_cat = $this->db->get_where( 'product', array( 'category' => $filter_val1 ) )->result_array();
				foreach ( $result as $row ) {
					if ( $this->session->userdata( 'title' ) == 'admin' ) {
						if ( $this->sale_payment_status( $row['sale_id'], 'admin' ) == 'fully_paid' ) {
							foreach ( $res_cat as $row1 ) {
								if ( $p = $this->product_in_sale( $row['sale_id'], $row1['product_id'], 'subtotal' ) ) {
									$a += $p;
								}
							}
						}
					}
				}
			}
		}

		return $a;
	}

	function email_invoice( $sale_id ) {
		$email                = $this->get_type_name_by_id( 'user', $this->get_type_name_by_id( 'sale', $sale_id, 'buyer' ), 'email' );
		$sale_code            = $this->get_sale_code( $sale_id );
		$from                 = $this->db->get_where( 'general_settings', array(
			'type' => 'system_email'
		) )->row()->value;
		$from_name            = $this->db->get_where( 'general_settings', array(
			'type' => 'system_name'
		) )->row()->value;
		$page_data['sale_id'] = $sale_id;
		$text                 = $this->load->view( 'front/shopping_cart/invoice_email', $page_data, true );
		$this->email_model->do_email( $from, $from_name, $email, $sale_code, $text );
		$admins = $this->db->get_where( 'admin', array( 'role' => '1' ) )->result_array();
		foreach ( $admins as $row ) {
			$this->email_model->do_email( $from, $from_name, $row['email'], $sale_code, $text );
		}
	}

	//GETTING VENDOR PERMISSION
	function vendor_permission( $codename ) {
		if ( $this->session->userdata( 'vendor_login' ) !== 'yes' ) {
			return false;
		} else {
			return true;
		}
	}

	function is_added_by( $type, $id, $user_id, $user_type = 'vendor' ) {
		$added_by = json_decode( $this->db->get_where( $type, array( $type . '_id' => $id ) )->row()->added_by, true );
		if ( $user_type == 'admin' ) {
			$user_id = $added_by['id'];
		}
		$this->benchmark->mark_time();
		if ( $added_by['type'] == $user_type && $added_by['id'] == $user_id ) {
			return true;
		} else {
			return false;
		}
	}


	//added by ritesh : start
	function product_box_sku_code( $product_id, $with_link = '' ) {
		$product_code_by = $this->db->get_where( 'product', array( 'product_id' => $product_id ) )->row()->product_code;


		if ( $with_link == '' ) {
			return $product_code_by;
		} else if ( $with_link == 'with_link' ) {
			//return '<a href="'.base_url().'">'.$name.'</a>';
			$name       = url_title( $this->crud_model->get_type_name_by_id( 'product', $product_id, 'title' ) );
			$formed_url = '<a href="' . base_url() . 'index.php/home/product_view/' . $product_id . '/' . $name . '">' . $product_code_by . '</a>';

			return $formed_url;
		}
	}

	//added by ritesh : end


	//SALE WISE TOTAL BY TYPE
	function product_by( $product_id, $with_link = '' ) {
		$added_by = json_decode( $this->db->get_where( 'product', array( 'product_id' => $product_id ) )->row()->added_by, true );
		if ( $added_by['type'] == 'admin' ) {
			$name = $this->db->get_where( 'general_settings', array( 'type' => 'system_name' ) )->row()->value;
			if ( $with_link == '' ) {
				return $name;
			} else if ( $with_link == 'with_link' ) {
				return '<a href="' . base_url() . '">' . $name . '</a>';
			}
		} else if ( $added_by['type'] == 'vendor' ) {
			$name = $this->db->get_where( 'vendor', array( 'vendor_id' => $added_by['id'] ) )->row()->display_name;
			if ( $with_link == '' ) {
				return $name;
			} else if ( $with_link == 'with_link' ) {
				return '<a href="' . $this->vendor_link( $added_by['id'] ) . '">' . $name . '</a>';
			}
		}
	}

	//SALE WISE TOTAL BY TYPE
	function provider_detail( $type, $id = '', $with_link = '' ) {
		if ( $type == 'admin' ) {
			$name = $this->db->get_where( 'general_settings', array( 'type' => 'system_name' ) )->row()->value;
			if ( $with_link == '' ) {
				return $name;
			} else if ( $with_link == 'with_link' ) {
				return '<a href="' . base_url() . '">' . $name . '</a>';
			}
		} else if ( $type == 'vendor' ) {
			$name = $this->db->get_where( 'vendor', array( 'vendor_id' => $id ) )->row()->display_name;
			if ( $with_link == '' ) {
				return $name;
			} else if ( $with_link == 'with_link' ) {
				return '<a href="' . $this->vendor_link( $id ) . '">' . $name . '</a>';
			}
		}
	}

	function is_sale_of_vendor( $sale_id, $vendor_id ) {
		$return          = array();
		$product_details = json_decode( $this->get_type_name_by_id( 'sale', $sale_id, 'product_details' ), true );
		foreach ( $product_details as $row ) {
			if ( $this->is_added_by( 'product', $row['id'], $vendor_id ) ) {
				$return[] = $row['id'];
			}
		}
		if ( empty( $return ) ) {
			return false;
		} else {
			return $return;
		}
	}

	function is_admin_in_sale( $sale_id ) {
		$return          = array();
		$product_details = json_decode( $this->get_type_name_by_id( 'sale', $sale_id, 'product_details' ), true );
		foreach ( $product_details as $row ) {
			if ( $this->is_added_by( 'product', $row['id'], 0, 'admin' ) ) {
				$return[] = $row['id'];
			}
		}
		if ( empty( $return ) ) {
			return false;
		} else {
			return $return;
		}
	}

	function vendors_in_sale( $sale_id ) {
		$vendors = $this->db->get( 'vendor' )->result_array();
		$return  = array();
		foreach ( $vendors as $row ) {
			if ( $this->is_sale_of_vendor( $sale_id, $row['vendor_id'] ) ) {
				$return[] = $row['vendor_id'];
			}
		}

		return $return;
	}

	function vendor_share_in_sale( $sale_id, $vendor_id, $pay = '', $pay_type = '' ) {
		$product_price = 0;
		$tax           = 0;
		$shipping      = 0;
		$total         = 0;
		if ( $pay == 'paid' ) {
			$pay = 'fully_paid';
		}
		if ( $this->sale_payment_status( $sale_id, 'vendor', $vendor_id ) == $pay || $pay == '' ) {
			if ( $this->db->get_where( 'sale', array( 'sale_id' => $sale_id ) )->row()->payment_type == $pay_type || $pay_type == '' ) {
				if ( $products = $this->is_sale_of_vendor( $sale_id, $vendor_id ) ) {
					$products_in_sale = json_decode( $this->get_type_name_by_id( 'sale', $sale_id, 'product_details' ), true );
					foreach ( $products_in_sale as $row ) {
						if ( in_array( $row['id'], $products ) ) {
							$product_price += $row['subtotal'];
							$tax           += $row['tax'];
							$shipping      += $row['shipping'];
							$total         += $row['subtotal'] + $row['tax'] + $row['shipping'];
						}
					}
				}
			}
		}

		return array( 'price' => $product_price, 'tax' => $tax, 'shipping' => $shipping, 'total' => $total );
	}

	function vendor_share_total( $vendor_id, $pay = '', $pay_type = '' ) {
		$product_price = 0;
		$tax           = 0;
		$shipping      = 0;
		$total         = 0;
		$sales         = $this->db->get( 'sale' )->result_array();
		foreach ( $sales as $row ) {
			$share         = $this->vendor_share_in_sale( $row['sale_id'], $vendor_id, $pay, $pay_type );
			$product_price += $share['price'];
			$tax           += $share['tax'];
			$shipping      += $share['shipping'];
			$total         += $share['price'] + $share['tax'] + $share['shipping'];
		}

		return array( 'price' => $product_price, 'tax' => $tax, 'shipping' => $shipping, 'total' => $total );
	}

	function paid_to_vendor( $vendor_id ) {
		$total          = 0;
		$vendor_invoice = $this->db->get_where( 'vendor_invoice', array(
			'vendor_id' => $vendor_id,
			'status'    => 'paid'
		) )->result_array();
		foreach ( $vendor_invoice as $row ) {
			$total += $row['amount'];
		}

		return $total;
	}

	function sale_payment_status( $sale_id, $type = '', $id = '' ) {
		$payment_status = json_decode( $this->db->get_where( 'sale', array(
			'sale_id' => $sale_id
		) )->row()->payment_status, true );
		$paid           = '';
		$unpaid         = '';
		foreach ( $payment_status as $row ) {
			if ( $type == '' ) {
				if ( $row['status'] == 'paid' ) {
					$paid = 'yes';
				}
				if ( $row['status'] == 'due' ) {
					$unpaid = 'yes';
				}
				if ( $row['status'] == 'failed' ) {
					$unpaid = 'failed';
				}
			} else {
				if ( isset( $row[ $type ] ) ) {
					if ( $type == 'vendor' ) {
						if ( $row[ $type ] == $id ) {
							if ( $row['status'] == 'paid' ) {
								$paid = 'yes';
							}
							if ( $row['status'] == 'due' ) {
								$unpaid = 'yes';
							}
						}
					} else if ( $type == 'admin' ) {
						if ( $row['status'] == 'paid' ) {
							$paid = 'yes';
						}
						if ( $row['status'] == 'due' ) {
							$unpaid = 'yes';
						}
						if ( $row['status'] == 'failed' ) {
							$unpaid = 'failed';
						}
					}
				}
			}
		}
		if ( $unpaid == 'failed' ) {
			return 'Failed';
		} else if ( $paid == 'yes' && $unpaid == '' ) {
			return 'fully_paid';
		} else if ( $paid == 'yes' && $unpaid == 'yes' ) {
			return 'partially_paid';
		} else if ( $paid == '' && $unpaid == 'yes' ) {
			return 'due';
		}
		if ( $paid == '' && $unpaid == '' ) {
//			return 'due';
			return 'Pending';
		}
	}

	function get_brands( $type, $id, $vendor = '' ) {
		if ( $type == 'category' ) {
			if ( $id !== '0' ) {
				$this->db->where( 'category', $id );
			}
			$sub_cats = $this->db->get( 'sub_category' )->result_array();
		} else if ( $type == 'sub_category' ) {
			$sub_cats = array( array( 'sub_category_id' => $id ) );
		}
		$brands = array();

		foreach ( $sub_cats as $row ) {
			$n_brands = json_decode( $this->db->get_where( 'sub_category', array( 'sub_category_id' => $row['sub_category_id'] ) )->row()->brand );
			foreach ( $n_brands as $n ) {
				if ( $vendor !== '' ) {
					if ( $this->is_brand_of_vendor( $n, $vendor ) ) {
						$na       = $n;
						$na       .= ':::';
						$brn_data = $this->db->get_where( 'brand', array( 'brand_id' => $n ) );
						if ( $brn_data->num_rows() > 0 ) {
							$na .= $brn_data->row()->name;
							array_push( $brands, $na );
						}
					}
				} else {
					$na       = $n;
					$na       .= ':::';
					$brn_data = $this->db->get_where( 'brand', array( 'brand_id' => $n ) );
					if ( $brn_data->num_rows() > 0 ) {
						$na .= $brn_data->row()->name;
						array_push( $brands, $na );
					}
				}
			}
		}

		//print_r(array_unique($brands));
		return array_unique( $brands );
	}


	function sub_details_by_cat( $cat = '' ) {
		$subs   = $this->db->get_where( 'sub_category', array( 'category' => $cat ) )->result_array();
		$result = array();
		foreach ( $subs as $row ) {
			$result[] = array(
				'sub_id'   => $row['sub_category_id'],
				'sub_name' => str_replace( "'", ' ', $row['sub_category_name'] ),
				'min'      => round( $this->crud_model->get_range_lvl( 'sub_category', $row['sub_category_id'], "min" ) ),
				'max'      => round( $this->crud_model->get_range_lvl( 'sub_category', $row['sub_category_id'], "max" ) ),
				'brands'   => str_replace( "'", ' ', join( ';;;;;;', $this->get_brands( 'sub_category', $row['sub_category_id'] ) ) )
			);
		}

		return json_encode( $result );
	}

	function get_vendors_by( $type, $id ) {
		$this->db->where( 'status', 'approved' );
		$vendors = $this->db->get( 'vendor' )->result_array();
		$result  = array();
		foreach ( $vendors as $row ) {
			if ( $type == 'category' ) {
				if ( $id == '0' ) {
					$result[] = $row['vendor_id'] . ':::' . $row['display_name'];
				} else {
					if ( $this->is_category_of_vendor( $id, $row['vendor_id'] ) ) {
						$result[] = $row['vendor_id'] . ':::' . $row['display_name'];
					}
				}
			}
			if ( $type == 'sub_category' ) {
				if ( $this->is_sub_cat_of_vendor( $id, $row['vendor_id'] ) ) {
					$result[] = $row['vendor_id'] . ':::' . $row['display_name'];
				}
			}
		}

		return $result;
	}

	function is_category_of_vendor( $category, $vendor_id ) {
		$product = $this->db->get_where( 'product', array( 'category' => $category ) )->result_array();
		$p       = 'no';
		foreach ( $product as $row ) {
			if ( $this->is_added_by( 'product', $row['product_id'], $vendor_id, 'vendor' ) ) {
				$p = 'yes';
			}
		}

		$this->config->cache_query();
		if ( $p == 'yes' ) {
			return true;
		} else {
			return false;
		}
	}

	function vendor_categories( $vendor ) {
		$categories = $this->db->get( 'category' )->result_array();
		$result     = array();
		foreach ( $categories as $row ) {
			if ( $this->is_category_of_vendor( $row['category_id'], $vendor ) ) {
				$result[] = $row['category_id'];
			}
		}

		return $result;
	}

	function is_sub_cat_of_vendor( $sub_cat, $vendor_id ) {
		$product = $this->db->get_where( 'product', array( 'sub_category' => $sub_cat ) )->result_array();
		$p       = 'no';
		foreach ( $product as $row ) {
			if ( $this->is_added_by( 'product', $row['product_id'], $vendor_id, 'vendor' ) ) {
				$p = 'yes';
			}
		}
		if ( $p == 'yes' ) {
			return true;
		} else {
			return false;
		}
	}

	function vendor_sub_categories( $vendor, $category ) {
		$sub_categories = $this->db->get_where( 'sub_category', array( 'category' => $category ) )->result_array();
		$result         = array();
		foreach ( $sub_categories as $row ) {
			if ( $this->is_sub_cat_of_vendor( $row['sub_category_id'], $vendor ) ) {
				$result[] = $row['sub_category_id'];
			}
		}

		return $result;
	}

	function vendor_products_by_sub( $vendor, $sub_category ) {
		$products = $this->db->get_where( 'product', array( 'sub_category' => $sub_category ) )->result_array();
		$result   = array();
		foreach ( $products as $row ) {
			if ( $this->is_added_by( 'product', $row['product_id'], $vendor, 'vendor' ) ) {
				$result[] = $row['product_id'];
			}
		}

		return $result;
	}

	function is_brand_of_vendor( $brand, $vendor_id ) {
		$product = $this->db->get_where( 'product', array( 'brand' => $brand ) )->result_array();
		$p       = 'no';
		foreach ( $product as $row ) {
			if ( $this->is_added_by( 'product', $row['product_id'], $vendor_id, 'vendor' ) ) {
				$p = 'yes';
			}
		}
		if ( $p == 'yes' ) {
			return true;
		} else {
			return false;
		}
	}

	function can_add_product( $vendor ) {
		$membership = $this->db->get_where( 'vendor', array( 'vendor_id' => $vendor ) )->row()->membership;
		$expire     = $this->db->get_where( 'vendor', array( 'vendor_id' => $vendor ) )->row()->member_expire_timestamp;
		$already    = $this->db->get_where( 'product', array(
			'added_by' => '{"type":"vendor","id":"' . $vendor . '"}',
			'status'   => 'ok'
		) )->num_rows();
		if ( $membership == '0' ) {
			$max = $this->db->get_where( 'general_settings', array( 'type' => 'default_member_product_limit' ) )->row()->value;
		} else {
			$max = $this->db->get_where( 'membership', array( 'membership_id' => $membership ) )->row()->product_limit;
		}

		if ( $expire > time() || $membership == '0' ) {
			if ( $max <= $already ) {
				return false;
			} else if ( $max > $already ) {
				return true;
			}
		} else {
			return false;
		}
	}

	function is_publishable( $product_id ) {
		//maximum product + membership change
		$product_data = $this->db->get_where( 'product', array( 'product_id' => $product_id ) );
		if ( $product_data->row()->status !== 'ok' ) {
			return false;
		}
		$physical_product_activation = $this->db->get_where( 'general_settings', array( 'type' => 'physical_product_activation' ) )->row()->value;
		$digital_product_activation  = $this->db->get_where( 'general_settings', array( 'type' => 'digital_product_activation' ) )->row()->value;

		if ( $product_data->row()->download == null ) {
			if ( $physical_product_activation !== 'ok' ) {
				return false;
			}
		} else if ( $product_data->row()->download == 'ok' ) {
			if ( $digital_product_activation !== 'ok' ) {
				return false;
			}
		}

		$by = json_decode( $product_data->row()->added_by, true );
		if ( $by['type'] == 'admin' ) {
			return true;
		} else if ( $by['type'] == 'vendor' ) {
			$vendor_status = $this->db->get_where( 'vendor', array( 'vendor_id' => $by['id'] ) )->row()->status;
			$vendor_system = $this->db->get_where( 'general_settings', array( 'type' => 'vendor_system' ) )->row()->value;
			if ( $vendor_system !== 'ok' ) {
				return false;
			}
			if ( $vendor_status == 'approved' ) {
				return true;
			} else {
				return false;
			}
		}
	}

	function is_publishable_count( $type, $id, $vendor_id = '' ) {
		$i = 0;
		if ( $vendor_id !== '' ) {
			$this->db->where( 'added_by', json_encode( array( 'type' => 'vendor', 'id' => $vendor_id ) ) );
		}
		//added by ritesh for handling the count on categories page as per live date : start
		$current_date = date( 'Y-m-d H:i:s' );
		$this->db->where( 'live_from <= ', $current_date );
		$this->db->where( 'live_from > ', '0000-00-00 00:00:00' );
		$this->db->where( "( live_to is NULL OR live_to >'$current_date') AND 1=", 1 );
		//added by ritesh : end

		$products = $this->db->get_where( 'product', array( $type => $id ) )->result_array();
		foreach ( $products as $row ) {
			if ( $this->is_publishable( $row['product_id'] ) ) {
				$i ++;
			}
		}

		return $i;
	}


	function set_product_publishability( $vendor, $except = '' ) {
		$membership = $this->db->get_where( 'vendor', array( 'vendor_id' => $vendor ) )->row()->membership;
		$this->db->order_by( 'product_id', 'desc' );
		$approved_products = $this->db->get_where( 'product', array(
			'added_by' => '{"type":"vendor","id":"' . $vendor . '"}',
			'status'   => 'ok'
		) );
		$already           = $approved_products->num_rows();
		if ( $membership == '0' ) {
			$max = $this->db->get_where( 'general_settings', array( 'type' => 'default_member_product_limit' ) )->row()->value;
		} else {
			$max = $this->db->get_where( 'membership', array( 'membership_id' => $membership ) )->row()->product_limit;
		}
		if ( $max <= $already ) {
			$approved_products = $approved_products->result_array();
			$i                 = 0;
			foreach ( $approved_products as $row ) {
				$i ++;
				if ( $row['product_id'] !== $except ) {
					if ( $i < $max ) {
						$data['status'] = 'ok';
					} else {
						$data['status'] = '0';
					}
					$this->db->where( 'product_id', $row['product_id'] );
					$this->db->update( 'product', $data );
				}
			}
		}
	}

	function check_vendor_mambership() {
		//interval loop check for end membership + email terminsation
		$vendors = $this->db->get( 'vendor' )->result_array();
		foreach ( $vendors as $row ) {
			if ( $row['membership'] !== '0' ) {
				if ( $row['member_expire_timestamp'] < time() ) {
					$data['membership'] = '0';
					$this->db->where( 'vendor_id', $row['vendor_id'] );
					$this->db->update( 'vendor', $data );
					$this->set_product_publishability( $row['vendor_id'] );
					$this->email_model->membership_upgrade_email( $row['vendor_id'] );
				}
			}
		}
	}

	function upgrade_membership( $vendor, $membership ) {
		$vendor_cur      = $this->db->get_where( 'vendor', array( 'vendor_id' => $vendor ) );
		$cur_membership  = $vendor_cur->row()->membership;
		$cur_expire      = $vendor_cur->row()->member_expire_timestamp;
		$membership_spec = $this->db->get_where( 'membership', array( 'membership_id' => $membership ) );
		$timespan        = $membership_spec->row()->timespan;
		//$new_expire       = $cur_expire+($timespan*24*60*60);
		$new_expire                      = time() + ( $timespan * 24 * 60 * 60 );
		$data['member_expire_timestamp'] = $new_expire;
		$data['membership']              = $membership;
		$this->db->where( 'vendor_id', $vendor );
		$this->db->update( 'vendor', $data );
		$this->email_model->membership_upgrade_email( $vendor );
	}

	//GETTING ADMIN PERMISSION
	function admin_permission( $codename ) {

		if ( $this->session->userdata( 'admin_login' ) != 'yes' ) {
			return false;
		}
		$admin_id = $this->session->userdata( 'admin_id' );
		$admin    = $this->db->get_where( 'admin', array(
			'admin_id' => $admin_id
		) )->row();
		$this->benchmark->mark_time();
		$permission = $this->db->get_where( 'permission', array(
			'codename' => $codename
		) )->row()->permission_id;
		if ( $admin->role == 1 ) {
			return true;
		} else {
			$role             = $admin->role;
			$role_permissions = json_decode( $this->crud_model->get_type_name_by_id( 'role', $role, 'permission' ) );
			if ( in_array( $permission, $role_permissions ) ) {
				return true;
			} else {
				return false;
			}
		}/**/
	}


	//GETTING USER TOTAL
	function user_total( $last_days = 0 ) {
		if ( $last_days == 0 ) {
			$time = 0;
		} else {
			$time = time() - ( 24 * 60 * 60 * $last_days );
		}
		$sales  = $this->db->get_where( 'sale', array(
			'buyer' => $this->session->userdata( 'user_id' )
			/*'payment_status' => 'paid',
            'sale_datetime >=' => $time*/
		) )->result_array();
		$return = 0;
		foreach ( $sales as $row ) {
			if ( $row['sale_datetime'] >= $time ) {
				$payment_status = json_decode( $row['payment_status'], true );
				foreach ( $payment_status as $payment ) {
					if ( $payment['status'] == 'paid' ) {
						$return += $row['grand_total'];
					}
				}
			}

		}

		return number_format( (float) $return, 2, '.', '' );
	}


	//GETTING NUMBER OF WISHED PRODUCTS BY CURRENT USER
	function user_wished() {
		$user = $this->session->userdata( 'user_id' );

		return count( json_decode( $this->get_type_name_by_id( 'user', $user, 'wishlist' ) ) );
	}

	//ADDING PRODUCT TO WISHLIST
	function add_wish( $product_id ) {
		$user = $this->session->userdata( 'user_id' );
		if ( $this->get_type_name_by_id( 'user', $user, 'wishlist' ) !== 'null' ) {
			$wished = json_decode( $this->get_type_name_by_id( 'user', $user, 'wishlist' ) );
		} else {
			$wished = array();
		}
		if ( $this->is_wished( $product_id ) == 'no' ) {
			array_push( $wished, $product_id );
			$this->db->where( 'user_id', $user );
			$this->db->update( 'user', array(
				'wishlist' => json_encode( $wished )
			) );
		}
	}

	//REMOVING PRODUCT FROM WISHLIST
	function remove_wish( $product_id ) {
		$user = $this->session->userdata( 'user_id' );
		if ( $this->get_type_name_by_id( 'user', $user, 'wishlist' ) !== 'null' ) {
			$wished = json_decode( $this->get_type_name_by_id( 'user', $user, 'wishlist' ) );
		} else {
			$wished = array();
		}
		$wished_new = array();
		foreach ( $wished as $row ) {
			if ( $row !== $product_id ) {
				$wished_new[] = $row;
			}
		}
		$this->db->where( 'user_id', $user );
		$this->db->update( 'user', array(
			'wishlist' => json_encode( $wished_new )
		) );
	}


	//NUMBER OF WISHED PRODUCTS
	function wished_num() {
		$user = $this->session->userdata( 'user_id' );
		if ( $this->get_type_name_by_id( 'user', $user, 'wishlist' ) !== '' ) {
			return count( json_decode( $this->get_type_name_by_id( 'user', $user, 'wishlist' ) ) );
		} else {
			return 0;
		}
	}


	//IF PRODUCT IS ADDED TO CURRENT USER'S WISHLIST
	function is_wished( $product_id ) {
		if ( $this->session->userdata( 'user_login' ) == 'yes' ) {
			$user = $this->session->userdata( 'user_id' );
			//$wished = array('0');
			if ( $this->get_type_name_by_id( 'user', $user, 'wishlist' ) !== '' ) {
				$wished = json_decode( $this->get_type_name_by_id( 'user', $user, 'wishlist' ) );
			} else {
				$wished = array(
					'0'
				);
			}
			if ( in_array( $product_id, $wished ) ) {
				return 'yes';
			} else {
				return 'no';
			}
		} else {
			return 'no';
		}
	}

	//GETTING TOTAL WISHED PRODUCTT BY USER
	function total_wished( $product_id ) {
		$num   = 0;
		$users = $this->db->get( 'user' )->result_array();
		foreach ( $users as $row ) {
			$wishlist = json_decode( $row['wishlist'] );
			if ( is_array( $wishlist ) ) {
				if ( in_array( $product_id, $wishlist ) ) {
					$num ++;
				}
			}

		}

		return $num;
	}

	//GETTING MOST WISHED PRODUCTS
	function most_wished() {
		$result  = array();
		$product = $this->db->get( 'product' )->result_array();
		foreach ( $product as $row ) {
			$result[] = array(
				'title'    => $row['title'],
				'wish_num' => $this->total_wished( $row['product_id'] ),
				'id'       => $row['product_id']
			);
		}
		if ( ! function_exists( 'compare_lastname' ) ) {
			function compare_lastname( $a, $b ) {
				return strnatcmp( $b['wish_num'], $a['wish_num'] );
			}
		}
		usort( $result, 'compare_lastname' );

		return $result;
	}

	//RATING chnaged by ritesh : start
	function rating( $product_id, $rating_total = '', $rating_num = '' ) {
		$total = $rating_total;
		$num   = $rating_num;

		if ( ! ( isset( $rating_total ) && ! empty( $rating_total ) ) ) {
			$total = $this->get_type_name_by_id( 'product', $product_id, 'rating_total' );
		}
		if ( ! ( isset( $rating_num ) && ! empty( $rating_num ) ) ) {
			$num = $this->get_type_name_by_id( 'product', $product_id, 'rating_num' );
		}

		if ( $num > 0 ) {
			$number = $total / $num;

			return number_format( (float) $number, 2, '.', '' );
		} else {
			return 0;
		}
	}

	//changed by ritesh : end

	function vendor_rating( $id ) {
		$this->db->where( 'added_by', json_encode( array( 'type' => 'vendor', 'id' => $id ) ) );
		$products = $this->db->get( 'product' )->result_array();
		$num      = 0;
		$total    = 0;
		foreach ( $products as $row ) {
			if ( $this->is_publishable( $row['product_id'] ) ) {
				$num   += $row['rating_num'];
				$total += $row['rating_total'];
			}
		}
		if ( $num > 0 ) {
			$number = $total / $num;

			return number_format( (float) $number, 2, '.', '' );
		} else {
			return 0;
		}
	}

	//IF CURRENT USER RATED THE PRODUCT
	function is_rated( $product_id ) {
		if ( $this->session->userdata( 'user_login' ) == 'yes' ) {
			$user = $this->session->userdata( 'user_id' );
			if ( $this->get_type_name_by_id( 'product', $product_id, 'rating_user' ) !== '' ) {
				$rating_user = json_decode( $this->get_type_name_by_id( 'product', $product_id, 'rating_user' ) );
			} else {
				$rating_user = array(
					'0'
				);
			}
			if ( in_array( $user, $rating_user ) ) {
				return 'yes';
			} else {
				return 'no';
			}
		} else {
			return 'no';
		}
	}

	//SETTING RATING
	function set_rating( $product_id, $rating ) {
		if ( $this->is_rated( $product_id ) == 'yes' ) {
			return 'no';
		}

		$total = $this->get_type_name_by_id( 'product', $product_id, 'rating_total' );
		$num   = $this->get_type_name_by_id( 'product', $product_id, 'rating_num' );
		$user  = $this->session->userdata( 'user_id' );
		$total = $total + $rating;
		$num   = $num + 1;

		$rating_user = json_decode( $this->get_type_name_by_id( 'product', $product_id, 'rating_user' ) );
		if ( ! is_array( $rating_user ) ) {
			$rating_user = array();
		}
		array_push( $rating_user, $user );

		$this->db->where( 'product_id', $product_id );
		$this->db->update( 'product', array(
			'rating_user' => json_encode( $rating_user )
		) );
		$this->db->where( 'product_id', $product_id );
		$this->db->update( 'product', array(
			'rating_total' => $total
		) );
		$this->db->where( 'product_id', $product_id );
		$this->db->update( 'product', array(
			'rating_num' => $num
		) );

		return 'yes';
	}


	//GETTING IP DATA OF PEOPLE BROWING THE SYSTEM
	function ip_data() {
		if ( ! $this->input->is_ajax_request() ) {
			$this->session->set_userdata( 'timestamp', time() );
			$user_data = $this->session->userdata( 'surfer_info' );
			$ip        = $_SERVER['REMOTE_ADDR'];
			if ( ! $user_data ) {
				if ( $_SERVER['HTTP_HOST'] !== 'localhost' ) {
					$ip_data = file_get_contents( "http://ip-api.com/json/" . $ip );
					$this->session->set_userdata( 'surfer_info', $ip_data );
				}
			}
		}
	}


	//GETTING TOTAL PURCHASE
	function total_purchase( $user_id ) {
		$return = 0;
		$sales  = $this->db->get( 'sale' )->result_array();
		foreach ( $sales as $row ) {
			if ( $row['buyer'] == $user_id ) {
				$return += $row['grand_total'];
			}
		}

		return $this->cart->format_number( $return );
	}


	function seo_stat( $type = '' ) {
		try {
			$url      = base_url();
			$seostats = new \SEOstats\SEOstats;
			if ( $seostats->setUrl( $url ) ) {

				if ( $type == 'facebook' ) {
					return SEOstats\Services\Social::getFacebookShares();
				} elseif ( $type == 'gplus' ) {
					return SEOstats\Services\Social::getGooglePlusShares();
				} elseif ( $type == 'twitter' ) {
					return SEOstats\Services\Social::getTwitterShares();
				} elseif ( $type == 'linkedin' ) {
					return SEOstats\Services\Social::getLinkedInShares();
				} elseif ( $type == 'pinterest' ) {
					return SEOstats\Services\Social::getPinterestShares();
				} elseif ( $type == 'alexa_global' ) {
					return SEOstats\Services\Alexa::getGlobalRank();
				} elseif ( $type == 'alexa_country' ) {
					return SEOstats\Services\Alexa::getCountryRank();
				} elseif ( $type == 'alexa_bounce' ) {
					return SEOstats\Services\Alexa::getTrafficGraph( 5 );
				} elseif ( $type == 'alexa_time' ) {
					return SEOstats\Services\Alexa::getTrafficGraph( 4 );
				} elseif ( $type == 'alexa_traffic' ) {
					return SEOstats\Services\Alexa::getTrafficGraph( 1 );
				} elseif ( $type == 'alexa_pageviews' ) {
					return SEOstats\Services\Alexa::getTrafficGraph( 2 );
				} elseif ( $type == 'google_siteindex' ) {
					return SEOstats\Services\Google::getSiteindexTotal();
				} elseif ( $type == 'google_back' ) {
					return SEOstats\Services\Google::getBacklinksTotal();
				} elseif ( $type == 'search_graph_1' ) {
					return SEOstats\Services\SemRush::getDomainGraph( 1 );
				} elseif ( $type == 'search_graph_2' ) {
					return SEOstats\Services\SemRush::getDomainGraph( 2 );
				}

			}
		} catch ( \Exception $e ) {
			echo 'Caught SEOstatsException: ' . $e->getMessage();
		}
	}


	//ADDING PRODUCT TO WISHLIST
	function add_compare( $product_id ) {
		if ( $this->session->userdata( 'compare' ) == '' || $this->session->userdata( 'compare' ) == 'null' ) {
			$this->session->set_userdata( 'compare', '[]' );
		}
		$compared = json_decode( $this->session->userdata( 'compare' ), true );
		if ( $this->is_compared( $product_id ) == 'no' ) {
			array_push( $compared, $product_id );
			$compared = json_encode( $compared );
			//echo $this->compare_category_num($product_id);
			if ( $this->compare_category_num( $product_id ) <= 3 ) {
				$this->session->set_userdata( 'compare', $compared );
				echo 'done';
			} else {
				echo 'cat_full';
			}
		} else {
			echo 'already';
		}
	}

	function compare_category_num( $product_id ) {
		if ( $this->session->userdata( 'compare' ) == '' || $this->session->userdata( 'compare' ) == 'null' ) {
			$this->session->set_userdata( 'compare', '[]' );
		}
		$compared = json_decode( $this->session->userdata( 'compare' ), true );
		$category = $this->db->get_where( 'product', array( 'product_id' => $product_id ) )->row()->category;
		$i        = 0;
		foreach ( $compared as $row ) {
			$n_cat = $this->db->get_where( 'product', array( 'product_id' => $row ) )->row()->category;
			if ( $n_cat == $category ) {
				$i ++;
			}
		}

		return $i;
	}

	//REMOVING PRODUCT FROM WISHLIST
	function remove_compare( $product_id ) {
		$compared = json_decode( $this->session->userdata( 'compare' ), true );
		$new      = array();
		foreach ( $compared as $row ) {
			if ( $row !== $product_id ) {
				$new[] = $product_id;
			}
		}
		$compared = json_encode( $new );
		$this->session->set_userdata( 'compare', $compared );
	}


	//NUMBER OF WISHED PRODUCTS
	function compared_num() {
		return count( json_decode( $this->session->userdata( 'compare' ), true ) );
	}


	//IF PRODUCT IS ADDED TO CURRENT USER'S WISHLIST
	function is_compared( $product_id ) {
		//echo $this->session->userdata('compare');
		if ( $this->session->userdata( 'compare' ) == '' || $this->session->userdata( 'compare' ) == 'null' ) {
			$this->session->set_userdata( 'compare', '[]' );
		}
		$compared = json_decode( $this->session->userdata( 'compare' ), true );
		foreach ( $compared as $row ) {
			if ( $row == $product_id ) {
				return 'yes';
			}
		}

		return 'no';
	}

	//IF PRODUCT IS ADDED TO CURRENT USER'S WISHLIST
	function compared_shower() {
		if ( $this->session->userdata( 'compare' ) == '' ) {
			$this->session->set_userdata( 'compare', '[]' );
		}
		$compared = json_decode( $this->session->userdata( 'compare' ), true );
		$cats     = array();
		$products = array();
		$result   = array();
		foreach ( $compared as $row ) {
			//added by ritesh for handling the product compare  as per live date : start
			$current_date = date( 'Y-m-d H:i:s' );
			$this->db->where( 'live_from <= ', $current_date );
			$this->db->where( 'live_from > ', '0000-00-00 00:00:00' );
			$this->db->where( "( live_to is NULL OR live_to >'$current_date') AND 1=", 1 );
			//added by ritesh : end

			$cat        = $this->db->get_where( 'product', array( 'product_id' => $row ) )->row()->category;
			$cats[]     = $cat;
			$products[] = array( 'c' => $cat, 'p' => $row );
		}

		$cats = array_unique( $cats );
		foreach ( $cats as $row ) {
			$ps = array();
			foreach ( $products as $r ) {
				if ( $r['c'] == $row ) {
					$ps[] = $r['p'];
				}
			}
			$result[] = array( 'category' => $row, 'products' => $ps );
		}

		return $result;
	}


	/* FUNCTION: Price Range Load by AJAX*/
	function get_range_lvl( $by = "", $id = "", $type = "" ) {
		$physical = $this->crud_model->get_settings_value( 'general_settings', 'physical_product_activation' );
		$digital  = $this->crud_model->get_settings_value( 'general_settings', 'digital_product_activation' );
		$vendor   = $this->crud_model->get_settings_value( 'general_settings', 'vendor_system' );
		if ( $type == "min" ) {
			$set = 'asc';
		} elseif ( $type == "max" ) {
			$set = 'desc';
		}

		//added by ritesh for handling the price slider  as per live date : start
		$current_date = date( 'Y-m-d H:i:s' );
		$this->db->where( 'live_from <= ', $current_date );
		$this->db->where( 'live_from > ', '0000-00-00 00:00:00' );
		$this->db->where( "( live_to is NULL OR live_to >'$current_date') AND 1=", 1 );
		//added by ritesh : end
		$this->db->limit( 1 );
		if ( $physical == 'ok' && $digital !== 'ok' ) {
			$this->db->where( 'download', null );
		}
		if ( $physical !== 'ok' && $digital == 'ok' ) {
			$this->db->where( 'download', 'ok' );
		}
		$this->db->order_by( 'sale_price', $set );
		if ( count( $a = $this->db->get_where( 'product', array(
				$by      => $id,
				'status' => 'ok'
			) )->result_array() ) > 0 ) {
			foreach ( $a as $r ) {
				return $r['sale_price'];
			}
		} else {
			return 0;
		}
	}

	/* FUNCTION: Regarding Digital*/
	function is_digital( $id ) {
		if ( $this->db->get_where( 'product', array( 'product_id' => $id ) )->row()->download == 'ok' ) {
			return true;
		} else {
			return false;
		}
	}

	function download_product( $id ) {
		if ( $this->can_download( $id ) ) {
			$this->load->helper( 'download' );
			$name   = $this->db->get_where( 'product', array( 'product_id' => $id ) )->row()->download_name;
			$folder = $this->db->get_where( 'general_settings', array( 'type' => 'file_folder' ) )->row()->value;
			$link   = 'uploads/file_products/' . $folder . '/' . $name;
			force_download( $link, null );
			echo 'ok';
		} else {
			echo 'not';
		}
	}

	function digital_to_customer( $sale_id ) {
		$carted    = json_decode( $this->db->get_where( 'sale', array(
			'sale_id' => $sale_id
		) )->row()->product_details, true );
		$user      = $this->db->get_where( 'sale', array(
			'sale_id' => $sale_id
		) )->row()->buyer;
		$downloads = $this->db->get_where( 'user', array(
			'user_id' => $user
		) )->row()->downloads;
		$result    = array();
		foreach ( $carted as $row ) {
			if ( $this->is_digital( $row['id'] ) ) {
				$result[] = array( 'sale' => $sale_id, 'product' => $row['id'] );
			}
		}
		if ( $downloads !== '' ) {
			$downloads = json_decode( $downloads, true );
		} else if ( $downloads == '' ) {
			$downloads = json_decode( '[]', true );
		}
		$data['downloads'] = json_encode( array_merge( $downloads, $result ) );
		$this->db->where( 'user_id', $user );
		$this->db->update( 'user', $data );
	}

	function download_count( $product ) {
		$users = $this->db->get( 'user' )->result_array();
		$i     = 0;
		foreach ( $users as $row ) {
			$downloads = json_decode( $row['downloads'], true );
			$ids       = array();
			foreach ( $downloads as $row ) {
				$ids[] = $row['product'];
			}
			if ( in_array( $product, $ids ) ) {
				$i ++;
			}
		}

		return $i;
	}

	function can_download( $product ) {
		if ( $this->session->userdata( 'admin_login' ) == 'yes' ) {
			return true;
		}
		if ( $this->session->userdata( 'vendor_login' ) == 'yes' ) {
			if ( $this->is_added_by( 'product', $product, $this->session->userdata( 'vendor_id' ), 'vendor' ) ) {
				return true;
			} else {
				return false;
			}
		}
		if ( $this->session->userdata( 'user_login' ) == 'yes' ) {
			$user      = $this->session->userdata( 'user_id' );
			$downloads = $this->db->get_where( 'user', array(
				'user_id' => $user
			) )->row()->downloads;
			$ok        = 'no';
			if ( $downloads !== '' ) {
				$downloads = json_decode( $downloads, true );
			} else if ( $downloads == '' ) {
				$downloads = json_decode( '[]', true );
			}
			//print_r($downloads);
			foreach ( $downloads as $row ) {
				if ( $row['product'] == $product ) {
					$by     = json_decode( $this->db->get_where( 'product', array(
						'product_id' => $product
					) )->row()->added_by, true );
					$type   = $by['type'];
					$id     = $by['id'];
					$status = json_decode( $this->db->get_where( 'sale', array(
						'sale_id' => $row['sale']
					) )->row()->payment_status, true );
					$fs     = '';
					foreach ( $status as $t ) {
						if ( $type == 'vendor' ) {
							if ( $t[ $type ] == $id ) {
								$fs = $t['status'];
							}
						} else if ( $type == 'admin' ) {
							$fs = $t['status'];
						}
					}
					//echo $fs;
					if ( $fs == 'paid' ) {
						$ok = 'yes';
					}
				}
			}
			if ( $ok == 'yes' ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
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


	//GET PRODUCT LINK
	function category_link( $cat = '', $scat = '', $brand = '' ) {
		$cat_name   = 'all-category';
		$scat_name  = 'all-subcategory';
		$brand_name = 'all-brand';

		if ( $cat !== '' ) {
			$cat_name = $this->crud_model->get_type_name_by_id( 'category', $cat, 'category_name' );
		}
		if ( $scat !== '' ) {
			$scat_name = $this->crud_model->get_type_name_by_id( 'sub_category', $scat, 'sub_category_name' );
		}
		if ( $brand !== '' ) {
			$brand_name = $this->crud_model->get_type_name_by_id( 'brand', $brand, 'name' );
		}

		$url = url_title( $cat . '-' . $cat_name . '-' . $scat . '-' . $scat_name . '-' . $brand . '-' . $brand_name );

		return base_url() . 'index.php/home/' . $url;
	}

	function product_list_set( $speciality, $limit, $id = '' ) {
		$physical_product_activation = $this->crud_model->get_settings_value( 'general_settings', 'physical_product_activation' );
		$digital_product_activation  = $this->crud_model->get_settings_value( 'general_settings', 'digital_product_activation' );
		$vendor_system_activation    = $this->crud_model->get_settings_value( 'general_settings', 'vendor_system' );

		if ( $vendor_system_activation == 'ok' ) {
			$approved_vendors = $this->db->get_where( 'vendor', array( 'status' => 'approved' ) )->result_array();
			foreach ( $approved_vendors as $row ) {
				$vendors[] = '{"type":"vendor","id":"' . $row['vendor_id'] . '"}';
			}
		}

		$admins = $this->db->get( 'admin' )->result_array();
		foreach ( $admins as $row ) {
			$vendors[] = '{"type":"admin","id":"' . $row['admin_id'] . '"}';
		}
		$result   = array();
		$category = $this->get_type_name_by_id( 'product', $id, 'category' );
		//$this->db->select('product_id');
		$this->db->where( 'status', 'ok' );
		$this->db->limit( $limit );
		$this->db->where_in( 'added_by', $vendors );


		//added by ritesh for handling the products  as per live date : start
		$current_date = date( 'Y-m-d H:i:s' );
		$this->db->where( 'live_from <= ', $current_date );
		$this->db->where( 'live_from > ', '0000-00-00 00:00:00' );
		$this->db->where( "( live_to is NULL OR live_to >'$current_date') AND 1=", 1 );
		//added by ritesh : end

		if ( $physical_product_activation == 'ok' && $digital_product_activation !== 'ok' ) {
			$this->db->where( 'download', null );
		} else if ( $physical_product_activation !== 'ok' && $digital_product_activation == 'ok' ) {
			$this->db->where( 'download', 'ok' );
		} else if ( $physical_product_activation !== 'ok' && $digital_product_activation !== 'ok' ) {
			$this->db->where( 'product_id', '' );
		}

		if ( $speciality == 'most_viewed' ) {
			$this->db->order_by( 'number_of_view', 'desc' );
		}

		if ( $speciality == 'recently_viewed' ) {
			$this->db->order_by( 'last_viewed', 'desc' );
		}

		if ( $speciality == 'featured' ) {
			$this->db->where( 'featured', 'ok' );
		}

		if ( $speciality == 'vendor_featured' ) {
			$this->db->where( 'featured', 'ok' );
			$this->db->where( 'added_by', json_encode( array( 'type' => 'vendor', 'id' => $id ) ) );
		}

		if ( $speciality == 'related' ) {
			$this->db->where( 'product_id!=', $id );
			$this->db->where( 'category', $category );
		}

		if ( $speciality == 'sub_category' ) {
			$this->db->where( 'sub_category', $id );
		}

		if ( $speciality == 'deal' ) {
			$this->db->where( 'deal', 'ok' );
		}

		$this->db->order_by( 'product_id', 'desc' );
		$res = $this->db->get( 'product' )->result_array();

		return $res;
		/*
		$i = 0;
		foreach($res as $row){
			if($this->is_publishable($row['product_id'])){
				$i++;
				if($i <= $limit){
					$result[] = $row['product_id'];
				}
			}
		}

		if(empty($result)){
			$result = array(0);
		}
		$this->db->where_in('product_id',$result);
		if($speciality == 'most_viewed'){
			$this->db->order_by('number_of_view','desc');
		}

		if($speciality == 'recently_viewed'){
			$this->db->order_by('last_viewed','desc');
		}
		$this->db->order_by('product_id','desc');
		return $this->db->get('product')->result_array();
		*/
	}

	function if_publishable_category( $cat_id ) {
		$category_data               = $this->db->get_where( 'category', array( 'category_id' => $cat_id ) );
		$physical_product_activation = $this->db->get_where( 'general_settings', array( 'type' => 'physical_product_activation' ) )->row()->value;
		$digital_product_activation  = $this->db->get_where( 'general_settings', array( 'type' => 'digital_product_activation' ) )->row()->value;

		if ( $category_data->row()->digital == '' ) {
			if ( $physical_product_activation !== 'ok' ) {
				return false;
			}
		} else if ( $category_data->row()->digital == 'ok' ) {
			if ( $digital_product_activation !== 'ok' ) {
				return false;
			}
		}

		//Maybe check if they have products

		return true;
	}

	function if_publishable_subcategory( $id ) {
		$sub_category_data           = $this->db->get_where( 'sub_category', array( 'sub_category_id' => $id ) );
		$physical_product_activation = $this->db->get_where( 'general_settings', array( 'type' => 'physical_product_activation' ) )->row()->value;
		$digital_product_activation  = $this->db->get_where( 'general_settings', array( 'type' => 'digital_product_activation' ) )->row()->value;

		if ( $sub_category_data->row()->digital == '' ) {
			if ( $physical_product_activation !== 'ok' ) {
				return false;
			}
		} else if ( $sub_category_data->row()->digital == 'ok' ) {
			if ( $digital_product_activation !== 'ok' ) {
				return false;
			}
		}

		return true;
	}

	/*<span
	class="arrow search_cat search_cat_click"
	data-cat="1"
	data-min="13150"
	data-max="4800000"
	data-brands= "41:::Chevrolet-40:::Ford-39:::Nissan-38:::Audi-44:::Hyundai-45:::BMW-46:::Marcedes-Benz-47:::Mitsubishi-51:::Toyota-52:::Honda-54:::Volvo-50:::Lamborghini-55:::Porsche-48:::Suzuki-56:::Dunlop-57:::Yamaha"
	data-vendors="1:::Lavinia Mckee-3:::Tom"
>
	*/

	/*
	data-brands= "41:::Chevrolet-40:::Ford-39:::Nissan-38:::Audi-44:::Hyundai-45:::BMW-46:::Marcedes-Benz-47:::Mitsubishi-51:::Toyota-52:::Honda-54:::Volvo-50:::Lamborghini-55:::Porsche-48:::Suzuki-56:::Dunlop-57:::Yamaha"
	data-subdets= "[{&quot;sub_id&quot;:&quot;1&quot;,&quot;sub_name&quot;:&quot;Car&quot;,&quot;min&quot;:0,&quot;max&quot;:0,&quot;brands&quot;:&quot;41:::Chevrolet-40:::Ford-39:::Nissan-38:::Audi-44:::Hyundai-45:::BMW-46:::Marcedes-Benz-47:::Mitsubishi-51:::Toyota-52:::Honda-54:::Volvo&quot;},{&quot;sub_id&quot;:&quot;2&quot;,&quot;sub_name&quot;:&quot;Racing Car&quot;,&quot;min&quot;:145000,&quot;max&quot;:145000,&quot;brands&quot;:&quot;41:::Chevrolet-40:::Ford-39:::Nissan-38:::Audi-45:::BMW-46:::Marcedes-Benz-47:::Mitsubishi-50:::Lamborghini-51:::Toyota-52:::Honda-54:::Volvo-55:::Porsche&quot;},{&quot;sub_id&quot;:&quot;3&quot;,&quot;sub_name&quot;:&quot;Luxury SUV&quot;,&quot;min&quot;:46545,&quot;max&quot;:140825,&quot;brands&quot;:&quot;41:::Chevrolet-40:::Ford-39:::Nissan-45:::BMW-47:::Mitsubishi-51:::Toyota-54:::Volvo&quot;},{&quot;sub_id&quot;:&quot;5&quot;,&quot;sub_name&quot;:&quot;Chopper Bike&quot;,&quot;min&quot;:13150,&quot;max&quot;:79560,&quot;brands&quot;:&quot;39:::Nissan-45:::BMW-48:::Suzuki-52:::Honda-56:::Dunlop-57:::Yamaha&quot;},{&quot;sub_id&quot;:&quot;6&quot;,&quot;sub_name&quot;:&quot;Racing Bike&quot;,&quot;min&quot;:35000,&quot;max&quot;:48000,&quot;brands&quot;:&quot;45:::BMW-52:::Honda-57:::Yamaha&quot;},{&quot;sub_id&quot;:&quot;63&quot;,&quot;sub_name&quot;:&quot;Private Air&quot;,&quot;min&quot;:775000,&quot;max&quot;:4800000,&quot;brands&quot;:&quot;40:::Ford-39:::Nissan-38:::Audi-46:::Marcedes-Benz-47:::Mitsubishi-55:::Porsche&quot;}]">
	*/

	function set_category_data( $cat_id ) {
		if ( $cat_id !== '' && $cat_id !== 0 ) {
			$this->db->where( 'category_id', $cat_id );
		}
		$categories = $this->db->get( 'category' )->result_array();
		foreach ( $categories as $row ) {
			$data['data_brands']  = join( ';;;;;;', $this->get_brands( 'category', $row['category_id'] ) );
			$data['data_vendors'] = join( ';;;;;;', $this->get_vendors_by( 'category', $row['category_id'] ) );
			$data['data_subdets'] = $this->sub_details_by_cat( $row['category_id'] );
			$this->db->where( 'category_id', $row['category_id'] );
			$this->db->update( 'category', $data );
		}

		$data1['value'] = join( ';;;;;;', $this->get_brands( 'category', '0' ) );
		$this->db->where( 'type', 'data_all_brands' );
		$this->db->update( 'general_settings', $data1 );

		$data2['value'] = join( ';;;;;;', $this->get_vendors_by( 'category', '0' ) );
		$this->db->where( 'type', 'data_all_vendors' );
		$this->db->update( 'general_settings', $data2 );
	}


	//added by ritesh : start
	function verify_if_unique( $table_name, $condition ) {

		$sql = $this->db->query( "Select * from " . $table_name . " where " . $condition );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
                    return $sql->result_array();
                        
		} else {
			return false;
		}

	}

	function get_customer( $table_name = 'user', $user_id = 0, $other_details = '' ) {
		$condition = "1=1 ";
		if ( isset( $user_id ) && $user_id > 0 ) {
			$condition .= "  And user_id ='" . $user_id . "' ";
		}
		if ( isset( $other_details ) && ! empty( $other_details ) ) {
			$condition .= "  And (user_id='" . $user_id . "' ";
			$condition .= "  OR username like '%" . $user_id . "%' ";
			$condition .= "  OR surname like '%" . $user_id . "%' ";
			$condition .= "  OR email like '%" . $user_id . "%' ";
			$condition .= "  OR phone like '%" . $user_id . "%') ";
		}

		$sql = $this->db->query( "Select * from " . $table_name . " where " . $condition . " order by user_id asc" );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}

	}

	function get_customer_address( $user_id = 0, $address_id = 0, $page = '', $limit = '' ) {
		$condition = "1=1 ";
		// $limit_by = " LIMIT 5";
		$limit_by = "";
		if ( isset( $user_id ) && $user_id > 0 ) {
			$condition .= "  And u.user_id  ='" . $user_id . "' ";
		} else {
			return false;
		}
		if ( isset( $address_id ) && $address_id > 0 ) {
			$condition .= "  And u.address_id  ='" . $address_id . "' ";
			$limit_by  = " LIMIT 1";
		}
		if ( isset( $page ) && is_numeric( $page ) ) {
			$limit_by = " LIMIT  " . $page;
			if ( isset( $limit ) && is_numeric( $limit ) ) {
				$limit_by .= "," . $limit;
			}
		} else {
			if ( isset( $limit ) && is_numeric( $limit ) ) {
				$limit_by .= " LIMIT " . $limit;
			}
		}


		$sql = $this->db->query( 'Select u.*,c.name as country_name,s.name as state_name,cit.name as city_name 
                                      From user_address u
                                      Left Join user r ON (u.user_id = r.user_id)
                                      Left Join country c ON (c.country_id = u.country_id)
                                      Left Join state s ON (s.state_id = u.state_id)
                                      Left Join city cit ON (cit.city_id = u.city_id)
                                      where ' . $condition . $limit_by );
               
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}

	}


	function get_scheduled_videos_details( $video_schedule_id = 0, $product_id = 0 ) {

		$condition = "1=1 ";
		if ( isset( $video_schedule_id ) && $video_schedule_id > 0 ) {
			$condition .= "  And u.video_schedule_id  ='" . $video_schedule_id . "' ";
		}
		if ( isset( $product_id ) && $product_id > 0 ) {
			$condition .= "  And u.product  ='" . $product_id . "' ";
		}


		$sql = $this->db->query( 'Select u.*,r.sale_price,r.product_id,r.sku_code,r.product_code,r.title as product_name,s.supplier_name,c.category_name
                                      From video_schedule u
                                      Left Join product r ON (u.product = r.product_id)
                                      Left Join supplier s ON (r.supplier = s.supplier_id)
                                      Left Join category c ON (r.category = c.category_id)
                                      where ' . $condition . ' order by scheduled_date desc, start_time asc' );


		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}

	}


	function search_products( $userValue = null, $limit = 10, $page = 0 ) {
		$condition    = '1=1 And status="ok" ';
		$cond         = ' ';
		$current_date = date( 'Y-m-d H:i:s' );
		$condition    .= " And live_from <= '" . $current_date . "' ";
		$condition    .= " And live_from > '0000-00-00 00:00:00' ";
		$condition    .= "( live_to is NULL OR live_to >'$current_date') AND 1=";
		if ( isset( $userValue ) && ! empty( $userValue ) ) {

			$cond            = ' And ( ';
			$exploded_values = explode( " ", $userValue );
			if ( is_array( $exploded_values ) ) {

				for ( $i = 0; $i < count( $exploded_values ); $i = $i + 1 ) {
					$cond .= '  title like \'%' . $exploded_values[ $i ] . '%\' OR product_code like \'%' . $exploded_values[ $i ] . '%\'  OR sku_code like \'%' . $exploded_values[ $i ] . '%\'  OR';
				}
				$cond = rtrim( $cond, 'OR' );
				$cond .= ' ) ';

			} else {
				$cond = ' title like \'%' . $userValue . '%\' OR product_code like \'%' . $userValue . '%\' OR sku_code like \'%' . $userValue . '%\' ) ';
			}
		}

		$condition .= $cond;
		$sql       = $this->db->query( "Select * from product Where " . $condition );
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}

	function call_center_search_products( $userValue = null, $limit = 10, $page = 0 ) {
		$condition = '1=1 And status="ok" ';
		$cond      = ' ';
//		$current_date = date( 'Y-m-d H:i:s' ) ;
//		$condition .= " And live_from <= '" . $current_date . "' ";
//		$condition .= " And live_from > '0000-00-00 00:00:00' ";
//		$condition .=  "( live_to is NULL OR live_to >'$current_date') AND 1=";
		if ( isset( $userValue ) && ! empty( $userValue ) ) {

			$cond            = ' And ( ';
			$exploded_values = explode( " ", $userValue );
			if ( is_array( $exploded_values ) ) {

				for ( $i = 0; $i < count( $exploded_values ); $i = $i + 1 ) {
					$cond .= '  title like \'%' . $exploded_values[ $i ] . '%\' OR product_code like \'%' . $exploded_values[ $i ] . '%\'  OR sku_code like \'%' . $exploded_values[ $i ] . '%\'  OR';
				}
				$cond = rtrim( $cond, 'OR' );
				$cond .= ' ) ';

			} else {
				$cond = ' title like \'%' . $userValue . '%\' OR product_code like \'%' . $userValue . '%\' OR sku_code like \'%' . $userValue . '%\' ) ';
			}
		}

		$condition .= $cond;
		$sql       = $this->db->query( "Select * from product Where " . $condition );
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	function fetch_live_products( $from_date = '0000-00-00 00:00:00', $category_id = 0, $subcategory_id = 0, $product_id = 0, $sort_by = '', $order_by = 'ASC' ) {
		$current_date = date( 'Y-m-d H:i:s' );
		$condition    = "1=1 ";
		$ordered_by   = '';
		//if(isset($from_date) && !empty($from_date) ){
		$condition .= "  And live_from  <='" . $current_date . "' ";
		// }
		$condition .= "AND ( live_to is NULL OR live_to >'$current_date')";
		if ( isset( $from_date ) && ! empty( $from_date ) ) {
			$condition .= "  And live_from  >'" . $from_date . "' ";
		}

		if ( isset( $category_id ) && $category_id > 0 ) {
			$condition .= "  And category  ='" . $category_id . "' ";
		}
		if ( isset( $subcategory_id ) && $subcategory_id > 0 ) {
			$condition .= "  And sub_category  ='" . $subcategory_id . "' ";
		}
		if ( isset( $product_id ) && $product_id > 0 ) {
			$condition .= "  And product_id  ='" . $product_id . "' ";
		}
		if ( ! empty( $sort_by ) && isset( $sort_by ) ) {
			$ordered_by = ' ' . $sort_by . ' ' . $order_by;
		}


		$sql = $this->db->query( 'Select *
                                      From product
                                      where ' . $condition . ' ' . $ordered_by );


		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}


	}


	//added by ritesh for fetching the curtrent live product  as per live date : start
	function product_on_air_now( $limit = '1' ) {
		$condition = '1=1 And p.status="ok" ';

//                Select p.*
//                From product p
//                LEFT JOIN video_schedule v ON (v.product = p.product_id)
//                where 1=1 And p.status="ok"  And
//                v.scheduled_date='2018-03-10' And
//                CAST(v.start_time AS TIME) <=CAST('20:10:02' AS TIME) And
//                CAST(v.end_time AS TIME) >=CAST('22:15:02' AS TIME)
//                order by  p.product_id asc   LIMIT 1;

		$current_date = date( 'Y-m-d' );
		$current_time = date( 'H:i:s' );


		$condition .= " And v.scheduled_date='" . $current_date . "'";
		$condition .= " And CAST(v.start_time AS TIME) <= CAST('" . $current_time . "' AS TIME) ";
		$condition .= " And CAST(v.end_time AS TIME) >= CAST('" . $current_time . "' AS TIME) ";
		$condition .= " And p.live_from <= '" . date( 'Y-m-d' ) . "' ";
		$condition .= " And p.live_from > '0000-00-00 00:00:00' "; //Expiry date
		$condition .= " AND ( live_to is NULL OR live_to >'$current_date') ";
		$condition .= " And p.status = 'ok' ";


		$ordered_by = ' order by  p.product_id asc  ';
		$limit_by   = 'LIMIT ' . $limit;

		//added by ritesh : end

		$sql = $this->db->query( 'Select p.*,v.start_time
                                      From product p
                                      LEFT JOIN video_schedule v ON (v.product = p.product_id)
                                      where ' . $condition . ' ' . $ordered_by . ' ' . $limit_by );


//                   echo '<pre>';
//                   print_r($this->db->last_query());
//                   exit();
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	function product_on_air_slot( $product_id ) {
		$condition = '1=1 And p.status="ok" ';

//                Select p.*
//                From product p
//                LEFT JOIN video_schedule v ON (v.product = p.product_id)
//                where 1=1 And p.status="ok"  And
//                v.scheduled_date='2018-03-10' And
//                CAST(v.start_time AS TIME) <=CAST('20:10:02' AS TIME) And
//                CAST(v.end_time AS TIME) >=CAST('22:15:02' AS TIME)
//                order by  p.product_id asc   LIMIT 1;

		$current_date = date( 'Y-m-d' );
		$current_time = date( 'H:i:s' );


		$condition .= " And v.scheduled_date='" . $current_date . "'";
		$condition .= " And ((v.start_time >= '" . date( 'H:00:00' ) . "'";
		$condition .= " And v.end_time <= '" . date( 'H:00:00', strtotime('+1 hour') ) . "') OR (v.start_time <= '" . date( 'H:00:00' ) . "' And v.end_time <= '" . date( 'H:00:00', strtotime('+1 hour') ) . "' ) ";
		$condition .= " OR (v.start_time <= '" . date( 'H:00:00', strtotime('+1 hour') ) . "' And v.end_time >= '" . date( 'H:00:00', strtotime('+1 hour') ) . "') ";
		$condition .= " OR (v.start_time <= '" . date( 'H:00:00' ) . "' And v.end_time >= '" . date( 'H:00:00', strtotime('+1 hour') ) . "') ";
		$condition .= " ) ";
		$condition .= " And p.product_id !='" . $product_id . "'";
//                    $condition .= " And p.live_from <= '".date('Y-m-d')."' ";
//                    $condition .= " And p.live_from > '0000-00-00 00:00:00' ";
		$condition .= " And p.status = 'ok' ";

		$ordered_by = ' order by  v.start_time asc  ';
		$limit_by   = '';
//                    $limit_by = 'LIMIT '. $limit;

		//added by ritesh : end
		$sql = $this->db->query( 'Select p.*
                                      From product p
                                      LEFT JOIN video_schedule v ON (v.product = p.product_id)
                                      where ' . $condition . ' ' . $ordered_by . ' ' . $limit_by );


//                   echo '<pre>';
//                   print_r($this->db->last_query());
//                   exit();
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	//added by dev start
	function product_on_air_schedule( $limit = '4', $start_time ) {
		$condition = '1=1 And p.status="ok" ';

//                Select p.*
//                From product p
//                LEFT JOIN video_schedule v ON (v.product = p.product_id)
//                where 1=1 And p.status="ok"  And
//                v.scheduled_date='2018-03-10' And
//                CAST(v.start_time AS TIME) <=CAST('20:10:02' AS TIME) And
//                CAST(v.end_time AS TIME) >=CAST('22:15:02' AS TIME)
//                order by  p.product_id asc   LIMIT 1;

		$current_date    = date( 'Y-m-d' );
		$start_time_here = date( 'H:i:s', strtotime( $start_time ) );


		$condition .= " And v.scheduled_date='" . $current_date . "'";
		//$condition .= " And v.video_slot='".$slot_id."'";
		$condition .= " And CAST(v.start_time AS TIME) >= CAST('" . $start_time_here . "' AS TIME) ";
//                    $condition .= " And p.product_id !='".$product_id."'";
		$condition .= " And p.live_from <= '" . date( 'Y-m-d' ) . "' ";
		$condition .= " And p.live_from > '0000-00-00 00:00:00' ";
		$condition .= " And p.status = 'ok' ";

		$ordered_by = ' order by  v.start_time asc  ';
		$limit_by   = 'LIMIT ' . $limit;

		//added by ritesh : end

		$sql = $this->db->query( 'Select p.*,v.start_time
                                      From product p
                                      LEFT JOIN video_schedule v ON (v.product = p.product_id)
                                      where ' . $condition . ' ' . $ordered_by . ' ' . $limit_by );


//                   echo '<pre>';
//                   print_r($this->db->last_query());
//                   exit();
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}

	//added by dev end


	function getCountryData( $country_id = 226 ) {
		$sql = $this->db->query( "Select country_id,name as country_name from country where  status='Active' "
		                         . "And country_id=" . $this->db->escape( $country_id ) );
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}

	function getStateData( $state_id = 0, $country_id = 226 ) {
		$sql = $this->db->query( "Select state_id,name as state_name from state where status='Active' "
		                         . "And country_id=" . $this->db->escape( $country_id ) );
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}

	function getCityData( $state_id = 0, $country_id = 226 ) {
		$sql = $this->db->query( "Select city_id,name as city_name from city where status='Active' "
		                         . " And state_id=" . $this->db->escape( $state_id )
		                         . " And country_id=" . $this->db->escape( $country_id ) );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	//added by ritesh for product on live details : end

	//added by ritesh : end

	function getCompleteAttributeDetails( $product_id = 0 ) {
		$condition = '1=1 ';
		if ( isset( $product_id ) && $product_id > 0 ) {
			$condition = ' And p.product_id=' . $this->db->escape( $product_id );
		}


		$sql = $this->db->query( "SELECT p.product_id,p.product_type,p.title,p.sale_price,p.color,p.options,
                                    p.num_of_imgs,v.variation_id,v.current_stock as variation_stock,
                                    v.title as varaiation_title,v.sale_price as variation_price,v.is_default 

                                    FROM product p
                                    JOIN  variation v ON (p.product_id=v.product_id And p.product_type = v.product_type)
                                    JOIN attribute_mapping a ON a.variation_id = v.variation_id
                                    " );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	function getProductVariations( $product_id = 0, $variation_id = 0 ) {
		$condition = '1=1 ';
		$condition .= ' And p.status="ok" ';
		$condition .= ' And v.status="Active" ';
		if ( isset( $product_id ) && $product_id > 0 ) {
			$condition .= ' And p.product_id=' . $this->db->escape( $product_id );
		}
		if ( isset( $variation_id ) && $variation_id > 0 ) {
			$condition .= ' And v.variation_id=' . $this->db->escape( $variation_id );
		}

		$sql = $this->db->query( "SELECT p.product_id,p.product_type,p.title,p.sale_price,p.purchase_price,p.color,p.options,
                                    p.num_of_imgs,p.discount,p.discount_type,p.unit,p.tax,p.tax_type,p.shipping_cost,
                                    v.variation_id,v.current_stock as variation_stock,v.sku_code,
                                    v.title as varaiation_title,v.sale_price as variation_price,v.purchase_price as variation_purchase_price,v.is_default 
                
                                    FROM product p
                                    JOIN  variation v ON (p.product_id=v.product_id And p.product_type = v.product_type)
                                    where " . $condition );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	function getVariationAttributeMapping( $product_id = 0, $variation_id = 0 ) {
		$condition = ' v.status="Active" And p.status="ok" ';
		if ( isset( $product_id ) && $product_id > 0 ) {
			$condition .= ' And p.product_id=' . $this->db->escape( $product_id );
			$condition .= ' And v.product_id=' . $this->db->escape( $product_id );
		}
		if ( isset( $variation_id ) && $variation_id > 0 ) {
			$condition .= ' And v.variation_id=' . $this->db->escape( $variation_id );
			$condition .= ' And a.variation_id=' . $this->db->escape( $variation_id );
		}

		//using group concat here


//             SELECT p.product_id,p.product_type,p.title,p.sale_price,p.color,p.options,p.num_of_imgs,v.variation_id,
//            v.current_stock as variation_stock,v.title as varaiation_title,v.sale_price as variation_price,
//            v.is_default,group_concat(a.attribute_id) as group_attribute_id,group_concat(a.attributevalue_id) as group_attributevalue_id
//            FROM product p JOIN variation v ON (p.product_id=v.product_id And p.product_type = v.product_type)
//            JOIN attribute_mapping a ON a.variation_id = v.variation_id WHERE p.product_id='105'
//            group by v.variation_id


		$sql = $this->db->query( "SELECT p.product_id,p.product_type,p.title,p.sale_price,p.num_of_imgs,p.discount,p.discount_type,p.unit,
                                    p.discount as discount_amount,v.variation_id, v.current_stock as variation_stock,v.title as varaiation_title,v.sale_price as variation_price,v.sale_price as variation_discount_price,v.is_default,
                                    group_concat(a.attribute_id ORDER BY a.attribute_id SEPARATOR '|') as group_attribute_id,
                                    group_concat(a.attributevalue_id ORDER BY a.attribute_id SEPARATOR '|') as group_attributevalue_id
                                    FROM product p 
                                    JOIN variation v ON (p.product_id=v.product_id And p.product_type = v.product_type) 
                                    LEFT JOIN attribute_mapping a ON a.variation_id = v.variation_id " .
		                         " WHERE " . $condition .
		                         " group by v.variation_id "
		                         . "order by v.variation_id asc" );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	function getVariationAttributeValueMapping( $product_id = 0, $variation_id = 0 ) {
		$condition = ' v.status="Active" And p.status="ok" ';
		if ( isset( $product_id ) && $product_id > 0 ) {
			$condition .= ' And p.product_id=' . $this->db->escape( $product_id );
			$condition .= ' And v.product_id=' . $this->db->escape( $product_id );
		}
		if ( isset( $variation_id ) && $variation_id > 0 ) {
			$condition .= ' And v.variation_id=' . $this->db->escape( $variation_id );
			$condition .= ' And a.variation_id=' . $this->db->escape( $variation_id );
		}

		$sql = $this->db->query( "SELECT p.product_id,p.product_type,p.title,p.sale_price,p.num_of_imgs,p.discount,p.discount_type,p.unit,
                                    p.discount as discount_amount,p.tax,p.tax_type,p.shipping_cost,v.sku_code,
                                    v.variation_id, v.current_stock as variation_stock,v.title as varaiation_title,v.sale_price as variation_price,v.sale_price as variation_discount_price,v.is_default,
                                    group_concat(a.attribute_id ORDER BY a.attribute_id SEPARATOR '|') as group_attribute_id,
                                    group_concat(a.attributevalue_id ORDER BY a.attribute_id SEPARATOR '|') as group_attributevalue_id,
                                    group_concat(at.attribute_name ORDER BY at.attribute_id SEPARATOR '|') as group_attribute_name,
                                    group_concat(av.value ORDER BY a.attribute_id SEPARATOR '|') as group_att_value
                                    FROM product p 
                                    JOIN variation v ON (p.product_id=v.product_id And p.product_type = v.product_type) 
                                    LEFT JOIN attribute_mapping a ON a.variation_id = v.variation_id 
                                    LEFT JOIN attribute at ON (at.attribute_id = a.attribute_id)  
                                    LEFT JOIN attributevalue av ON (av.attributevalue_id = a.attributevalue_id) " .

		                         " WHERE " . $condition .
		                         " group by v.variation_id "
		                         . " order by v.variation_id asc" );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	function getDefaultVariationAttributeValueMapping( $product_id = 0, $variation_id = 0 ) {
		$condition = ' v.status="Active" And p.status="ok" ';
		if ( isset( $product_id ) && $product_id > 0 ) {
			$condition .= ' And p.product_id=' . $this->db->escape( $product_id );
			$condition .= ' And v.product_id=' . $this->db->escape( $product_id );
		}
		if ( isset( $variation_id ) && $variation_id > 0 ) {
			$condition .= ' And v.variation_id=' . $this->db->escape( $variation_id );
			$condition .= ' And a.variation_id=' . $this->db->escape( $variation_id );
		}

		$sql = $this->db->query( "SELECT p.product_id,p.product_type,p.title,p.sale_price,p.num_of_imgs,p.discount,p.discount_type,p.unit,
                                    p.discount as discount_amount,p.tax,p.tax_type,p.shipping_cost,v.sku_code,
                                    v.variation_id, v.current_stock as variation_stock,v.title as varaiation_title,v.sale_price as variation_price,v.sale_price as variation_discount_price,v.is_default,
                                    group_concat(a.attribute_id ORDER BY a.attribute_id SEPARATOR '|') as group_attribute_id,
                                    group_concat(a.attributevalue_id ORDER BY a.attribute_id SEPARATOR '|') as group_attributevalue_id,
                                    group_concat(at.attribute_name ORDER BY at.attribute_id SEPARATOR '|') as group_attribute_name,
                                    group_concat(av.value ORDER BY a.attribute_id SEPARATOR '|') as group_att_value
                                    FROM product p 
                                    JOIN variation v ON (p.product_id=v.product_id And p.product_type = v.product_type And v.is_default='yes') 
                                    LEFT JOIN attribute_mapping a ON a.variation_id = v.variation_id 
                                    LEFT JOIN attribute at ON (at.attribute_id = a.attribute_id)  
                                    LEFT JOIN attributevalue av ON (av.attributevalue_id = a.attributevalue_id) " .

		                         " WHERE " . $condition .
		                         " group by v.variation_id "
		                         . " order by v.variation_id asc" );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	function getAttributeValues( $condition = '1=1 ', $attribute_id = 0, $attributevalue_id = 0 ) {
		if ( isset( $attribute_id ) && $attribute_id > 0 ) {
			$condition .= ' And atv.attribute_id=' . $this->db->escape( $attribute_id );
		}
		if ( isset( $attributevalue_id ) && $attributevalue_id > 0 ) {
			$condition .= ' And atv.attributevalue_id=' . $this->db->escape( $attributevalue_id );
		}


		$sql = $this->db->query( "SELECT atv.*,att.attribute_name,status FROM attributevalue atv
                                     JOIN attribute att ON (att.attribute_id = atv.attribute_id)
                                    where " . $condition .
		                         "order by atv.attribute_id asc " );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	function getDistinctAttributes( $product_id = 0, $variation_id = 0, $attribute_id = 0, $attributevalue_id = 0, $condition = ' status="Active"  ' ) {
		if ( isset( $product_id ) && $product_id > 0 ) {
			$condition .= ' And product_id=' . $this->db->escape( $product_id );
		}
		if ( isset( $variation_id ) && $variation_id > 0 ) {
			$condition .= ' And variation_id=' . $this->db->escape( $variation_id );
		}


		if ( isset( $attribute_id ) && $attribute_id > 0 ) {
			$condition .= ' And attribute_id=' . $this->db->escape( $attribute_id );
		}
		if ( isset( $attributevalue_id ) && $attributevalue_id > 0 ) {
			$condition .= ' And attributevalue_id=' . $this->db->escape( $attributevalue_id );
		}


		$sql = $this->db->query( "SELECT distinct(attributevalue_id),attribute_id FROM attribute_mapping
                                    where " . $condition );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	function getProductDetails( $product_id = null, $categories_id = null, $subcategories_id = null, $brand_id = null, $condition = "1=1 " ) {
		$price_range_condition = '';

		$time_range_condition = '';
		$current_date         = date( 'Y-m-d H:i:s' );
		$start_date           = '0000-00-00 00:00:00';

		$condition .= ' And p.live_from <= ' . $this->db->escape( $current_date );
		$condition .= ' And p.live_from > ' . $this->db->escape( $start_date );
		$condition .= ' And status="ok" ';
		if ( $categories_id != null && ! empty( $categories_id ) ) {
			$condition .= ' And p.category=' . $this->db->escape( $categories_id );
		}
		if ( $subcategories_id != null && ! empty( $subcategories_id ) ) {
			$condition .= ' And p.sub_category=' . $this->db->escape( $subcategories_id );
		}
		if ( $brand_id != null && ! empty( $brand_id ) ) {
			$condition .= ' And p.brand=' . $this->db->escape( $brand_id );
		}
		if ( $product_id != null ) {
			$condition .= ' And p.product_id=' . $this->db->escape( $product_id );
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

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}


	function get_variation_stocks_maxcount( $product_id = 0, $variation_id = 0 ) {
		$condition = " v.status='Active' ";
		if ( isset( $product_id ) && $product_id > 0 ) {
			$condition .= ' And v.product_id=' . $this->db->escape( $product_id );
		}
		if ( isset( $variation_id ) && $variation_id > 0 ) {
			$condition .= ' And v.variation_id=' . $this->db->escape( $variation_id );
		}

		$sql = $this->db->query( "SELECT max(v.current_stock) as current_stock FROM 
                                       variation v JOIN product p ON (p.product_id=v.product_id And p.product_type = v.product_type)
                                       where " . $condition );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->row()->current_stock;
		} else {
			return 0;
		}

	}


	function getProductForBoxes( $product_id = 0, $category_id = 0, $sub_category_id = 0, $brand_id = 0, $variation_id = 0 ) {
		$condition    = ' v.status="Active" And p.status="ok" ';
		$current_date = date( 'Y-m-d H:i:s' );
		$condition    .= ' And p.live_from <= ' . $this->db->escape( $current_date );
		$condition    .= ' And p.live_from > ' . $this->db->escape( $start_date );
		if ( $categories_id != null && ! empty( $categories_id ) ) {
			$condition .= ' And p.category=' . $this->db->escape( $categories_id );
		}
		if ( $subcategories_id != null && ! empty( $subcategories_id ) ) {
			$condition .= ' And p.sub_category=' . $this->db->escape( $subcategories_id );
		}
		if ( $brand_id != null && ! empty( $brand_id ) ) {
			$condition .= ' And p.brand=' . $this->db->escape( $brand_id );
		}
		if ( $product_id != null ) {
			$condition .= ' And p.product_id=' . $this->db->escape( $product_id );
			$condition .= ' And v.product_id=' . $this->db->escape( $product_id );
		}
		if ( $variation_id != null ) {
			$condition .= ' And p.variation_id=' . $this->db->escape( $variation_id );
		}


		$sql = $this->db->query( "SELECT p.product_id,p.product_type,p.title,p.sale_price,
                                    p.discount,p.discount_type,p.tax,p.tax_type,p.unit,
                                    p.discount as discount_amount,v.variation_id, 
                                    v.current_stock as variation_stock,
                                    v.title as varaiation_title,v.sale_price as variation_price,
                                    v.sale_price as variation_discount_price,
                                    v.is_default
                                    FROM product p 
                                    JOIN variation v ON (p.product_id=v.product_id And p.product_type = v.product_type And v.is_default='yes') " .
		                         " WHERE " . $condition .
		                         " group by p.product_id" );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}

	//added by ritesh for warehouse login check : start
	//GETTING WAREHOUSE PERMISSION
	function warehouse_permission( $codename ) {
		if ( $this->session->userdata( 'warehouse_login' ) !== 'yes' ) {
			return false;
		} else {
			return true;
		}
	}
	//added by ritesh for warehouse login check : end

	//added by ritesh for production dashboard : start
	function get_categorywise_sale( $category_id = 0, $time_to_millis = 0 ) {
		$condition = $sale_condition = "1=1 ";

		// $condition .= ' And s.payment_status Like \'%,"status":"paid"}%\ ';

		$current_date = date( 'Y-m-d H:i:s' );
		$start_date   = '0000-00-00 00:00:00';

		$condition .= ' And p.live_from <= ' . $this->db->escape( $current_date );
		$condition .= ' And p.live_from > ' . $this->db->escape( $start_date );
		$condition .= ' And p.status="ok" ';

		if ( $category_id != null && ! empty( $category_id ) && $category_id > 0 ) {
			$condition .= ' And p.category=' . $this->db->escape( $category_id );
		}
		if ( $time_to_millis != null && ! empty( $time_to_millis ) && $time_to_millis > 0 ) {
			$passed_date    = date( 'Y-m-d H:i:s', $time_to_millis );
			$sale_condition .= ' And s.sale_datetime >=' . $this->db->escape( $time_to_millis );
			$condition      .= ' And c.created_on >=' . $this->db->escape( $passed_date );
		}

//                OLD Query Not Working
//                select s.order_from,sum(s.grand_total) as total_sale,group_concat(s.sale_id) as sale_group_id
//                FROM sale s
//                JOIN cart c ON (c.sale_id = s.sale_id)
//                JOIN product p ON (p.product_id=c.product_id And p.product_type = c.product_type And p.category=1) WHERE 1=1  And
//                p.live_from <= '2018-04-15 06:41:44' And p.live_from > '0000-00-00 00:00:00' And p.status="ok"  And p.category='1' And s.sale_datetime >=1523750400 And c.created_on >='2018-04-15 00:00:00' group by s.order_from


//                NEW QUERY
//                select s.order_from,sum(s.grand_total) as total_sale,group_concat(s.sale_id) as sale_group_id from sale s where s.sale_id IN (
//                select c.sale_id from cart c
//                JOIN product p ON (p.product_id=c.product_id And p.product_type = c.product_type And p.category=1)
//                        WHERE 1=1  And p.live_from <= '2018-04-15 06:41:44' And p.live_from > '0000-00-00 00:00:00'
//                        And p.status="ok" "
//                            . " And p.category='1'
//                        And c.created_on >='2018-04-15 00:00:00'
//                ) And s.sale_datetime >=1523750400  group by s.order_from ;


		$sql = $this->db->query( "select s.order_from,sum(s.grand_total) as total_sale,group_concat(DISTINCT s.sale_id) as sale_group_id
                                            FROM sale s WHERE s.sale_id IN (
                                            Select DISTINCT c.sale_id from cart c 
                                            JOIN product p ON (p.product_id=c.product_id And p.product_type = c.product_type And p.category=$category_id)" .
		                         " WHERE " . $condition
		                         . ") And " . $sale_condition . " group by s.order_from " );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}

	}

	/* Added by Dawpro start: */
	function get_stock_summary( $dateRange, $type = 'total' ) {
		$condition = $sale_condition = "1=1 ";
//SELECT * FROM cart c, sale s WHERE c.sale_id = s.sale_id and s.payment_status like '%status":"paid"%'
		//payment_timestamp
		//sale_datetime
		$sql = false;
		if ( ! empty( $dateRange ) ) {
			$date_range = explode( ' - ', $dateRange );
			if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
				$sale_start_time = strtotime( date( 'Y-m-d 00:00:00', strtotime( $date_range[0] ) ) );
				$sale_end_time   = strtotime( date( 'Y-m-d 23:59:59', strtotime( $date_range[1] ) ) );
				//SELECT * FROM cart c, sale s WHERE c.sale_id = s.sale_id and s.payment_status like '%status":"paid"%'
				$q1 = "select c.product_id, p.title as title, SUM(c.qty) as total from cart c LEFT JOIN sale s ON s.sale_id = c.sale_id LEFT JOIN product p ON p.product_id = c.product_id WHERE p.title != '' AND s.order_status = 'processed' AND ( s.payment_status like '%status\":\"paid\"%' OR s.payment_status like '%status\":\"due\"%' ) AND s.sale_datetime >= $sale_start_time AND s.sale_datetime <= $sale_end_time GROUP BY p.title";
				$q2 = "select c.product_id, SUM(c.qty) as total from cart c LEFT JOIN sale s ON s.sale_id = c.sale_id LEFT JOIN product p ON p.product_id = c.product_id WHERE p.title != '' AND s.order_status = 'processed' AND s.payment_status like '%status\":\"due\"%' AND s.sale_datetime >= $sale_start_time AND s.sale_datetime <= $sale_end_time GROUP BY p.title";
				$q3 = "select c.product_id, SUM(c.qty) as total from cart c LEFT JOIN sale s ON s.sale_id = c.sale_id LEFT JOIN product p ON p.product_id = c.product_id WHERE p.title != '' AND s.order_status = 'processed' AND s.payment_status like '%status\":\"paid\"%' AND s.sale_datetime >= $sale_start_time AND s.sale_datetime <= $sale_end_time GROUP BY p.title";

				$q = "SELECT a.title, SUM(a.total) as total, SUM(b.total) as unpaid, SUM(c.total) as paid FROM ($q1) as a LEFT JOIN ($q2) as b ON b.product_id = a.product_id LEFT JOIN ($q3) as c ON a.product_id = c.product_id GROUP BY title ORDER BY title";
				$sql = $this->db->query( $q );
			}
		}


		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}

	}

	/* Added by Dawpro end; */
	function get_categorywise_out_sale( $category_id = 0, $time_to_millis = 0, $sale_id_list = '' ) {
		$condition = "1=1 ";
		// $condition .= ' And s.payment_status Like \'%,"status":"paid"}%\ ';

		$current_date = date( 'Y-m-d H:i:s' );
		$start_date   = '0000-00-00 00:00:00';

		$condition .= ' And p.live_from <= ' . $this->db->escape( $current_date );
		$condition .= ' And p.live_from > ' . $this->db->escape( $start_date );
		$condition .= ' And p.status="ok" ';

		if ( $category_id != null && ! empty( $category_id ) && $category_id > 0 ) {
			//$condition .= ' And p.category!='.$this->db->escape($category_id);
			$condition .= ' And p.category!=' . $this->db->escape( $category_id );
		}
		if ( $time_to_millis != null && ! empty( $time_to_millis ) && $time_to_millis > 0 ) {
			$passed_date = date( 'Y-m-d H:i:s', $time_to_millis );
			$condition   .= ' And s.sale_datetime >=' . $this->db->escape( $time_to_millis );
			$condition   .= ' And c.created_on >=' . $this->db->escape( $passed_date );
		}

		if ( isset( $sale_id_list ) && ! empty( $sale_id_list ) ) {
			$condition .= ' And c.sale_id IN (' . $sale_id_list . ') ';
		}
		$sql = $this->db->query( "select s.order_from,sum(c.final_amount) as discarded_sale
                                            FROM sale s
                                            JOIN cart c ON (c.sale_id = s.sale_id) 
                                            JOIN product p ON (p.product_id=c.product_id And p.product_type = c.product_type And p.category!=$category_id)" .
		                         " WHERE " . $condition
		                         . " group by s.order_from " );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}
	//added by ritesh for production dashboard : end
	// added by dev for Supplier Report -- Start
	function get_supplier_sale( $sale_time_period, $supplier_id = null, $product_ids = array() ) {

		$condition = '1=1 ';
		$admin_id  = $this->session->userdata( 'admin_id' );

//		if ( $_SESSION['login_type'] != 'admin' ) {
//			$condition .= ' And p.created_by = ' . $this->db->escape( $admin_id ) . ' ';
//		}

		//if(!empty($supplier_id)){
		$condition .= ' And p.supplier = ' . $this->db->escape( $supplier_id );
		//}
		if ( count( $product_ids ) > 0 ) {
			$condition .= ' And c.product_id IN (' . implode( ',', $product_ids ) . ')';
		}
		$sale_start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
		$sale_end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
		if ( ! empty( $sale_time_period ) ) {
			$date_range = explode( ' - ', $sale_time_period );
			if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
				$sale_start_time = strtotime( date( 'Y-m-d 00:00:00', strtotime( $date_range[0] ) ) );
				$sale_end_time   = strtotime( date( 'Y-m-d 23:59:59', strtotime( $date_range[1] ) ) );
			}
		}

		$condition .= ' And s.payment_status Like \'%,"status":"paid"}%\' ';
		$query     = $this->db->query(
			'Select count(c.cart_id) as total_sale,p.title as product_name,sup.supplier_name
                                   From product as p 
                                   Left Join cart as c on(p.product_id = c.product_id)
                                   Left Join sale as s on(s.sale_id = c.sale_id )
                                   Left Join supplier as sup on(p.supplier = sup.supplier_id)
                                   Where ' . $condition . '  And s.sale_datetime >= ' . $sale_start_time . ' And s.sale_datetime <= ' . $sale_end_time . '
                                   Group By c.product_id
                                ' );
		if ( $query !== false && $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	// added by dev for Supplier Report -- End


	//added by Ritesh for sample warehouse : start
	function getSampleWareHouseStocksSata( $sampleproduct_id = 0 ) {
		$condition = "1=1 ";
		if ( $sampleproduct_id != null && ! empty( $sampleproduct_id ) && $sampleproduct_id > 0 ) {
			//$condition .= ' And p.category!='.$this->db->escape($category_id);
			$condition .= ' And sp.sampleproduct_id=' . $this->db->escape( $sampleproduct_id );
		}


		$sql = $this->db->query( "SELECT group_concat(sm.status) as samples_status,sp.* "
		                         . "FROM sampleproduct sp JOIN samples_move sm ON sp.sampleproduct_id=sm.sampleproduct_id" .
		                         " WHERE " . $condition
		                         . " group by sp.sampleproduct_id " );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}

	function samples_count( $time = '', $status = '' ) {
		$condition    = " 1=1 ";
		$count_status = 0;
		if ( $status != null && ! empty( $status ) ) {
			$condition .= ' And sm.status=' . $this->db->escape( $status );
		} else {
			return 0;
		}

		//SELECT * from samples_move sm JOIN sampleproduct sp ON (sp.sampleproduct_id = sm.sampleproduct_id AND sp.returned_status='No') where 1=1 And sm.status='out';
		$sql = $this->db->query( "SELECT * from samples_move sm "
		                         . "JOIN sampleproduct sp ON (sp.sampleproduct_id = sm.sampleproduct_id AND sp.returned_status='No')"
		                         . " where " . $condition );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			$resulting_rows = $sql->result_array();

			$count_status = $this->getSampleCount( $resulting_rows, $time, $status );

			return $count_status;
		} else {
			return 0;
		}
	}


	function getSampleCount( $resulting_rows = array(), $time = '', $status = '' ) {
		$count = 0;
		foreach ( $resulting_rows as $row ) {
			$movement_history = json_decode( $row['movement_history'], true );
			if ( isset( $movement_history ) && ! empty( $movement_history ) && $movement_history !== null ) {
				$dbstatus    = array_values( array_slice( $movement_history, - 1 ) )[0]['status'];
				$dbtimestamp = array_values( array_slice( $movement_history, - 1 ) )[0]['time'];
				//            $array = array_slice($array, -1);
				//            $lastEl = array_pop($array);

				if ( $status == $dbstatus && $dbtimestamp >= $time ) {
					$count ++;
				}
			}
		}

		return $count;
	}
	//added by ritesh for sample warehouse : end

	// added by dev for scheduling front end :  start

	function get_scheduled_videos_details_front_end( $video_schedule_id = 0, $product_id = 0 ) {

		$condition = "1=1 ";

		// $condition   .= "  And u.scheduled_date  >='".date('Y-m-d')."' ";

		$sql = $this->db->query( 'Select u.*,CONCAT(u.scheduled_date," ",u.start_time) as product_live_start,CONCAT(u.scheduled_date," ",u.end_time) as product_live_end, r.product_id,r.sku_code,r.product_code,r.title as product_name,s.supplier_name,c.category_name
                                      From video_schedule u
                                      Left Join product r ON (u.product = r.product_id)
                                      Left Join supplier s ON (r.supplier = s.supplier_id)
                                      Left Join category c ON (r.category = c.category_id)
                                      where ' . $condition . ' order by scheduled_date  desc' );


		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}

	}

	// added by dev for scheduling front end :  end

	function get_sale_code( $sale_id, $generate = false ,$store_master_id ) {
                //username replace with storecode (add storecode)
             //MG00 22082019 1 -> storecode date sale_id
		if ( $generate ) {
			$sale_details = $this->db->get_where( 'sale', array( 'sale_id' => $sale_id ) )->result_array()[0];
                        $store_code = 'TES';
                        if(!empty($store_master_id) && $store_master_id < 10){
                           $store_code .= '0'.$store_master_id;
                        }else if($store_master_id >= 10 ){
                           $store_code .= $store_master_id;
                        }else{
                           $store_code .= '00';  
                        }
                        $code  = $store_code . date( 'dmY', $sale_details['sale_datetime'] ) . $sale_id;
		} else {
			$sale_details = $this->db->get_where( 'sale', array( 'sale_id' => $sale_id ) )->result_array()[0];
                        $store_code = 'TES';
                        if(!empty($store_master_id) && $store_master_id < 10){
                           $store_code .= '0'.$store_master_id;
                        }else if($store_master_id >= 10 ){
                           $store_code .= $store_master_id;
                        }else{
                           $store_code .= '00';  
                        }
                        $code  = $store_code . date( 'dmY', $sale_details['sale_datetime'] ) . $sale_id;
		}

		return $code;
	}

	//GETTING TOTAL SHIPPING COST
	function get_total_shipping_cost( $total ) {
//		$total = intval( str_replace( 'R', '', $total ) );
//		if ( $total >= 500 ) {
//			return 0.00;
//		} else {
			return 100.00;
//		}

	}

	function getStartAndEndDate( $week, $year ) {

		$time      = strtotime( "1 January $year", time() );
		$day       = date( 'w', $time );
		$time      += ( ( 7 * $week ) + 1 - $day ) * 24 * 3600;
		$return[0] = date( 'd M Y', $time );
		$time      += 6 * 24 * 3600;
		$return[1] = date( 'd M Y', $time );

		return $return;
	}

	function get_schedule_export_data( $from, $to ) {
		$from = date( 'Y-m-d', strtotime( $from ) );
		$to   = date( 'Y-m-d', strtotime( $to ) );

		$condition = "1=1 AND u.scheduled_date >= '$from' AND u.scheduled_date <= '$to' ";
		$sql       = $this->db->query( 'Select u.*,r.sale_price,r.product_id,r.sku_code,r.product_code,r.title as product_name,s.supplier_name,c.category_name
                                      From video_schedule u
                                      Left Join product r ON (u.product = r.product_id)
                                      Left Join supplier s ON (r.supplier = s.supplier_id)
                                      Left Join category c ON (r.category = c.category_id)
                                      where ' . $condition . ' order by scheduled_date asc, start_time asc' );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			$all_records  = $sql->result_array();


			$shift_evening = [
				1 => [],
				2 => [],
				3 => [],
				4 => [],
				5 => [],
				6 => [],
			];

			//Split into HOURS
			$first_hour = '09:00';
			foreach ( $all_records as $row ) {
				//$shift_evening[] = $row;
				if ( empty( $shift_evening[1] ) ) {
					$first_hour = $row['start_time'];
				}
				//in first hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +1 hour' ) ) {
					$shift_evening[1][] = $row;
				}
				//in second hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +1 hour' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +2 hours' ) ) {
					$shift_evening[2][] = $row;
				}
				//in third hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +2 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +3 hours' ) ) {
					$shift_evening[3][] = $row;
				}

				//in first hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +3 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +4 hour' ) ) {
					$shift_evening[4][] = $row;
				}
				//in second hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +4 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +5 hours' ) ) {
					$shift_evening[5][] = $row;
				}
				//in third hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +5 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +6 hours' ) ) {
					$shift_evening[6][] = $row;
				}
			}
			$pagedRecords = array_values( $shift_evening );

			//Split by day for the week
			/*
			foreach ( $all_records as $row ) {
				if ( isset( $pagedRecords[ $row['scheduled_date'] ] ) ) {
					$pagedRecords[ $row['scheduled_date'] ][] = $row;
				} else {
					$pagedRecords[ $row['scheduled_date'] ] = [ $row ];
				}
			}*/
			return $pagedRecords;
		} else {
			return false;
		}

	}

	function getDistinctAttributeNames( $product_id ) {
		$condition = ' am.status="Active"  AND am.attributevalue_id = v.attributevalue_id AND v.attribute_id = a.attribute_id';
		$condition .= ' And product_id=' . $this->db->escape( $product_id );
		$q         = "SELECT distinct(am.attributevalue_id), a.attribute_name, v.value FROM attribute_mapping am, attributevalue v, attribute a where " . $condition;
		$sql       = $this->db->query( $q );
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}

	function in_which_hour() {
//		$hours
	}

	function get_pal_export_data( $day ) {
		$from = date( 'Y-m-d', strtotime( $day ) );
		$to   = date( 'Y-m-d', strtotime( $day ) );

		$condition = " u.scheduled_date >= '$from' AND u.scheduled_date <= '$to' ";
		$sql       = $this->db->query( 'Select u.*,sp.description as pal_descr ,r.sale_price,r.product_id,u.pal_description,u.pal_details,r.sku_code,r.product_code,r.title as product_name,s.supplier_name, c.category_name
                                      From video_schedule u
                                      Left Join product r ON (u.product = r.product_id)
                                      Left Join supplier s ON (r.supplier = s.supplier_id)
                                      Left Join category c ON (r.category = c.category_id)
                                      Left Join supplier_products sp ON (sp.product_id = r.product_id)
                                      where ' . $condition . ' order by scheduled_date asc, start_time asc' );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			$all_records = $sql->result_array();

			$shift_evening = [
				1 => [],
				2 => [],
				3 => [],
				4 => [],
				5 => [],
				6 => [],
			];

			//Split into HOURS
			$first_hour = '09:00';
			foreach ( $all_records as $row ) {
				//$shift_evening[] = $row;
				if ( empty( $shift_evening[1] ) ) {
					$first_hour = $row['start_time'];
				}
				//in first hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +1 hour' ) ) {
					$shift_evening[1][] = $row;
				}
				//in second hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +1 hour' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +2 hours' ) ) {
					$shift_evening[2][] = $row;
				}
				//in third hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +2 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +3 hours' ) ) {
					$shift_evening[3][] = $row;
				}

				//in first hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +3 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +4 hour' ) ) {
					$shift_evening[4][] = $row;
				}
				//in second hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +4 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +5 hours' ) ) {
					$shift_evening[5][] = $row;
				}
				//in third hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +5 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +6 hours' ) ) {
					$shift_evening[6][] = $row;
				}
			}
			$pagedRecords = array_values( $shift_evening );

			return $pagedRecords;
		} else {
			return false;
		}

	}

	function products_on_air_for_the_hour( $day ) {
		$from = date( 'Y-m-d', strtotime( $day ) );
		$to   = date( 'Y-m-d', strtotime( $day ) );

		$condition = " u.scheduled_date >= '$from' AND u.scheduled_date <= '$to' ";
		$sql       = $this->db->query( 'Select u.*,r.sale_price,r.product_id,u.pal_description,u.pal_details,r.sku_code,r.product_code,r.title as product_name,s.supplier_name,c.category_name
                                      From video_schedule u
                                      Left Join product r ON (u.product = r.product_id)
                                      Left Join supplier s ON (r.supplier = s.supplier_id)
                                      Left Join category c ON (r.category = c.category_id)
                                      where ' . $condition . ' order by scheduled_date asc, start_time asc' );


		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			$all_records = $sql->result_array();

			$shift_evening = [
				1 => [],
				2 => [],
				3 => [],
				4 => [],
				5 => [],
				6 => [],
			];

			//Split into HOURS
			$first_hour = '09:00';
			foreach ( $all_records as $row ) {
				//$shift_evening[] = $row;
				if ( empty( $shift_evening[1] ) ) {
					$first_hour = $row['start_time'];
				}
				//in first hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +1 hour' ) ) {
					$shift_evening[1][] = $row;
				}
				//in second hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +1 hour' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +2 hours' ) ) {
					$shift_evening[2][] = $row;
				}
				//in third hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +2 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +3 hours' ) ) {
					$shift_evening[3][] = $row;
				}

				//in first hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +3 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +4 hour' ) ) {
					$shift_evening[4][] = $row;
				}
				//in second hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +4 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +5 hours' ) ) {
					$shift_evening[5][] = $row;
				}
				//in third hour ?
				if ( strtotime( $row['start_time'] ) >= strtotime( $first_hour . ' +5 hours' ) && strtotime( $row['start_time'] ) < strtotime( $first_hour . ' +6 hours' ) ) {
					$shift_evening[6][] = $row;
				}
			}
			$pagedRecords = array_values( $shift_evening );

			if ( sizeof( $pagedRecords ) > 0 ) {
				return $pagedRecords;
			} else {
				return false;
			}
		}
	}

	function get_product_attribute_options( $product_id ) {
		$atts    = $this->getDistinctAttributeNames( $product_id );
		$attData = [];
		if ( sizeof( $atts ) > 0 ) {
			foreach ( $atts as $a ) {
				if ( isset( $attData[ $a['attribute_name'] ] ) ) {
					$attData[ $a['attribute_name'] ][] = $a['value'];
				} else {
					$attData[ $a['attribute_name'] ] = [ $a['value'] ];
				}
			}
		}


		$str = "";
		if ( sizeof( $attData ) > 0 ) {
			foreach ( $attData as $k => $v ) {
				$str .= $k . ': ';
				$str .= implode( ' | ', $v );
				$str .= ';<br/>';
			}

		}

		return substr( $str, 0, sizeof( $str ) - 6 );

	}

	//Currently UNPAID AND PAID!
	//And s.payment_status Like '%,"status":"paid"}%'
	function dash_get_product_total_paid( $product_id, $start_date_time, $end_date_time='' ) {
		if ($end_date_time == ''){
			$query = $this->db->query( 'Select SUM(s.grand_total) as summary_total, SUM(c.qty) as quantity_total
                                   From cart as c
                                   Left Join sale as s on(s.sale_id = c.sale_id)
                                   Where c.product_id = ' . $this->db->escape( $product_id ) . ' And s.sale_datetime >= ' . strtotime( $start_date_time ) );
		} else {

				$query = $this->db->query( 'Select SUM(s.grand_total) as summary_total, SUM(c.qty) as quantity_total
                                   From cart as c
                                   Left Join sale as s on(s.sale_id = c.sale_id)
                                   Where c.product_id = ' . $this->db->escape( $product_id ) . ' And s.sale_datetime >= ' . strtotime( $start_date_time ) .' AND s.sale_datetime <= '.strtotime($end_date_time));
		}

		if ( $query !== false && $query->num_rows() > 0 ) {
			return $query->result_array()[0];
		} else {
			return 0;
		}
	}

	function get_total_sales_data_for_period( $product_id, $start_time , $end_time) {
		if (!is_int($start_time)){
			$start_time = strtotime($start_time);
		}
		if (!is_int($end_time)){
			$end_time = strtotime($end_time);
		}
		$query = $this->db->query( 'Select count(c.cart_id) as total_sales
                                   From cart as c
                                   Left Join sale as s on(s.sale_id = c.sale_id)
                                   Where c.product_id = ' . $this->db->escape( $product_id ) . '  And s.sale_datetime >= ' . $start_time . ' And s.sale_datetime <= ' . $end_time . '
                                    ' );
		if ( $query !== false && $query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	/* Added by Dawpro end; */
        
        //Added by Sagar :  Start DISPATCH DATA OF SALES
        function get_table_Data($select='*',$table,$condition,$order_field,$order_by=''){
                if(isset($table) && !empty($table)){

                    if(!(isset($order_field) && !empty($order_field))){
                        $order_field = $table.'_id';
                    }
                    if(!(isset($order_by) && !empty($order_by))){
                        $order_by='DESC';
                    }
                    $this -> db -> select($select);
                    $this -> db -> from($table);
                    if(isset($condition) && !empty($condition)){
                    $this -> db -> where("($condition)");
                    }
                    $this -> db -> order_by($order_field,$order_by);
                    $query = $this -> db -> get();

                    if($query -> num_rows() >= 1)
                    {
                            return $query->result_array();
                    }
                    else
                    {
                            return false;
                    }

                }else{
			return false;
		}
            
        }
        
        function getProductStockData(){
            $query = $this->db->query('
                                SELECT p.title, v.title as var_title, v.variation_id, if(SUM(c.qty)  is NULL,0,SUM(c.qty)) as sold_quantity, v.current_stock as stock_in_hand
                                        FROM variation as v
                                        left join product as p  on p.product_id = v.product_id
                                        LEFT JOIN  cart as c  ON (v.variation_id = c.variation_id AND  
                                                                      c.sale_id>0 AND 
                                                                      c.sale_id IN (select sale_id from sale ss where ss.payment_status Like \'%,"status":"paid"}%\' ))

                                        GROUP BY v.variation_id
                                        order by v.variation_id
                                ');
           
                     
            if ( $query !== false && $query->num_rows() > 0 ) {
                    return $query->result_array();
            } else {
                    return 0;
            }
        }
        //Added By Sagar :  End


	function get_DSTV_schedule_export_data( $from, $to ) {
		$from = date( 'Y-m-d 00:00:00', strtotime( $from ) );
		$to   = date( 'Y-m-d 23:59:59', strtotime( $to ) );

		$condition = "1=1 AND u.scheduled_date >= '$from' AND u.scheduled_date <= '$to' ";
		$sql       = $this->db->query( 'Select u.*,r.sale_price,r.product_id,r.sku_code,r.product_code,r.title as product_name,s.supplier_name,c.category_name, c.category_id
                                      From video_schedule u
                                      Left Join product r ON (u.product = r.product_id)
                                      Left Join supplier s ON (r.supplier = s.supplier_id)
                                      Left Join category c ON (r.category = c.category_id)
                                      where ' . $condition . ' order by scheduled_date asc, start_time asc' );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}

	}
        
        function searchCustomersDropDown( $userValue = null, $limit = 10, $page = 0 ) {
		$condition = '1=1 ';
		$cond      = ' ';
		if ( isset( $userValue ) && ! empty( $userValue ) ) {
			$cond            = ' And ( ';
			$exploded_values = explode( " ", $userValue );
			if ( is_array( $exploded_values ) ) {
                            
				for ( $i = 0; $i < count( $exploded_values ); $i = $i + 1 ) {
					$cond .= '  username like  \'' . $exploded_values[$i] . '%\' OR surname like \'' . $exploded_values[ $i ] . '%\'  OR phone like \'' . $exploded_values[ $i ] . '%\'  OR email like \'' . $exploded_values[ $i ] . '%\'  OR';
                                }
				$cond = rtrim( $cond, 'OR' );
				$cond .= ' ) ';

			} else {
				$cond = ' username like \'%' . $userValue . '%\' OR surname like \'%' . $userValue . '%\' OR  phone like \'%' . $userValue . '%\' OR email like \'%' . $userValue . '%\' ) ';
			}
		}
                
		$condition .= $cond;
		$sql       = $this->db->query( "Select * from user Where " . $condition );
               
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}
        
        function searchVendorDropDown( $userValue = null, $limit = 10, $page = 0 ) {
		$condition = '1=1 ';
		$cond      = ' ';
		if ( isset( $userValue ) && ! empty( $userValue ) ) {
			$cond            = ' And ( ';
			$exploded_values = explode( " ", $userValue );
			if ( is_array( $exploded_values ) ) {
                            
				for ( $i = 0; $i < count( $exploded_values ); $i = $i + 1 ) {
					$cond .= '  name like  \'' . $exploded_values[$i] . '%\' OR phone like \'' . $exploded_values[ $i ] . '%\'  OR email like \'' . $exploded_values[ $i ] . '%\'  OR store_name like \'' . $exploded_values[ $i ] . '%\'  OR';
                                }
				$cond = rtrim( $cond, 'OR' );
				$cond .= ' ) ';
//                                $cond .= ' AND '.$condd ;

			} else {
				$cond = ' name like \'%' . $userValue . '%\' OR phone like \'%' . $userValue . '%\' OR  email like \'%' . $userValue . '%\' OR store_name like \'%' . $userValue . '%\' ) ';
//                                $cond .= ' AND '.$condd ;
			}
		}
                
		$condition .= $cond;
		$sql       = $this->db->query( "Select * from vendor where " . $condition );
               
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}
        
         
        //added by sagar : for credit and loyalty part ON 29-07
        function getCurrentCreditLimit($user_id = null){
            $condition = " 1=1 ";
            if($user_id != null){
                $condition .= " AND user_id = ".$this->db->escape($user_id);
            }
            $query = $this->db->query("SELECT user_id,
                                                SUM(CASE WHEN type = 'add' THEN credit_amount else 0  END) AS add_amount,
                                                SUM(CASE WHEN type = 'destroy' THEN credit_amount else 0 END) AS destroy_amount
                                           FROM user_credit  where $condition
                                           GROUP BY user_id
                                        ");

            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }
        
        function getCurrentLoyaltyPoint($user_id = null){
            $condition = " 1=1 ";
            if($user_id != null){
                $condition .= " AND user_id = ".$this->db->escape($user_id);
            }
            $query = $this->db->query("SELECT user_id,
                                                SUM(CASE WHEN type = 'add' THEN loyalty_point else 0 END) AS add_amount,
                                                SUM(CASE WHEN type = 'destroy' THEN loyalty_point else 0 END) AS destroy_amount
                                           FROM user_loyalty  where $condition
                                           GROUP BY user_id
                                        ");
        
          
            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }
         //added by sagar : for credit and loyalty part ON 29-07
        
        function getProductExportData(){
            $query = $this->db->query("
                                       SELECT p.product_id,p.title,p.title_ar,p.product_code,p.product_type,p.weight,p.unit,p.b2b_unit,p.status,p.featured,
                                              p.sale_price,p.discount,p.is_offer,
                                              p.is_galaxy_choice,v.sale_price as variation_price ,v.variation_id,v.supplier_price as variation_supplier_price,
                                              if(p.product_type = 'variation',v.title,'') as variation_title,
                                              s.supplier_name,c.category_name,b.name as brand_name,sc.sub_category_name,v.sku_code
                                       FROM variation v 
                                       left join product p on(p.product_id = v.product_id)
                                       left join supplier s on(p.supplier = s.supplier_id)
                                       left join category c on (p.category = c.category_id)
                                       left join sub_category sc on (p.sub_category = sc.sub_category_id)
                                       left join brand b on (p.brand =b.brand_id)
                                    ");
        
          
            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }
        
        function getProductExportDiscountData(){
            $query = $this->db->query("
                                       SELECT p.product_id,p.title,p.discount,p.is_offer,p.offer_validity,
                                              s.supplier_name,c.category_name,b.name as brand_name,sc.sub_category_name,p.SKU_code
                                       FROM product p
                                       left join supplier s on(p.supplier = s.supplier_id)
                                       left join category c on (p.category = c.category_id)
                                       left join sub_category sc on (p.sub_category = sc.sub_category_id)
                                       left join brand b on (p.brand =b.brand_id)
                                    ");
        
          
            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }
        
        function getProductStockExportData(){
            $query = $this->db->query("
                                       SELECT p.product_id,p.title,p.title_ar,p.product_code,p.product_type,
                                              v.variation_id,if(p.product_type = 'variation',v.title,'') as variation_title,v.current_stock,
                                              c.category_name,b.name as brand_name,sc.sub_category_name,v.sku_code
                                       FROM variation v 
                                       left join product p on(p.product_id = v.product_id)
                                       left join category c on (p.category = c.category_id)
                                       left join sub_category sc on (p.sub_category = sc.sub_category_id)
                                       left join brand b on (p.brand =b.brand_id)
                                    ");
        
          
            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }
        
        //for ForgotPassword
        function checkFPWDflagInDB($email,$randomKey){
            $query = $this->db->query('Select * From user where fpwd_flag=\'Active\' && email='.$this->db->escape($email).' && fpwd_key='.$this->db->escape($randomKey));
              if($query->num_rows() ==1){
                  return $query->result_array();
            }else{
                return false;
            }
        } 
     
        //added by sagar : 
        function getDayRangeSalesData($sale_time_period,$payment_status="",$delivery_status="" , $category_id = ""){
            $sale_start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
            $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
            if ( ! empty( $sale_time_period ) ) {
                $date_range = explode( ' - ', $sale_time_period );
                if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
                        $sale_start_time = strtotime( date( 'Y-m-d 00:00:00', strtotime( $date_range[0] ) ) );
                        $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59', strtotime( $date_range[1] ) ) );
                }
            }


            $paymentStatusCondition = "  AND  1=1 ";
            if(!empty($payment_status)){
                $paymentStatusCondition = '  AND  s.payment_status Like '.'\'%,"status":"'.$payment_status.'"}%\'';  //1122
            }
            $deliveryStatusCondition = "  AND  1=1 ";
            if(!empty($delivery_status)){
                $deliveryStatusCondition = '  AND  s.delivery_status Like '.'\'%,"status":"'.$delivery_status.'",%\'';  //1122
            }

            $categoryCondition =  " And 1=1 ";
            if(!empty($category_id)){
               $categoryCondition = '  AND  s.product_details Like '.'\'%"category":"'.$category_id.'"%\''; 
            }
            
//            s.payment_status Like \'%,"status":"paid"}%\' 
            $query = $this->db->query(' Select s.*,u.first_name,u.email,u.phone
                                        from sale s 
                                        Left Join user as u on (s.buyer = u.user_id )
                                        Where  
                                        s.sale_datetime >= ' . $sale_start_time . ' And s.sale_datetime <= ' . $sale_end_time . 
                                          $paymentStatusCondition . $deliveryStatusCondition . $categoryCondition .
                                        'order by s.sale_id DESC');

            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
            }
        }

        function getDayRangeSupplierSalesData($sale_time_period,$supplier_id,$product_id="",$variation_id =""){
            $sale_start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
            $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
            if ( ! empty( $sale_time_period ) ) {
                $date_range = explode( ' - ', $sale_time_period );
                if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
                        $sale_start_time = strtotime( date( 'Y-m-d 00:00:00', strtotime( $date_range[0] ) ) );
                        $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59', strtotime( $date_range[1] ) ) );
                }
            }

            $supplierCondition =  " And 1=1 ";
            if(!empty($supplier_id)){
               $supplierCondition = '  AND  s.product_details Like '.'\'%"supplier":"'.$supplier_id.'"%\''; 
            }
         
            $productCondition = "  AND  1=1 ";
            if(!empty($product_id)){
                $productCondition = '  AND  s.product_details Like '.'\'%"product_id":"'.$product_id.'"%\'';  //1122
            }
            $variationCondition = "  AND  1=1 ";
            if(!empty($variation_id)){
                 $variationCondition = '  AND  s.product_details Like '.'\'%"variation_id":"'.$variation_id.'"%\'';  //1122
            }
            
//             s.payment_status Like \'%,"status":"paid"}%\'  
            $query = $this->db->query(' Select s.*, u.first_name,u.email,u.phone
                                        from sale s 
                                        Left Join user as u on (s.buyer = u.user_id )
                                        Where  
                                        s.order_status != "cancelled" AND 
                                        s.sale_datetime >= ' . $sale_start_time . ' And s.sale_datetime <= ' . $sale_end_time . 
                                          $supplierCondition . $productCondition .  $variationCondition .
                                        'order by s.sale_id DESC');
                              
            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
            }
        }
     
        //added by sagar 
        function getStockReportData($product_id='',$variation_id=''){
            $condition = " 1=1 ";
            if(!empty($product_id)){
                $condition .=  " AND  v.product_id = ". $this->db->escape($product_id); 
            }
            if(!empty($variation_id)){
                 $condition .= " AND  v.variation_id = ". $this->db->escape($variation_id); 
            }

            $query = $this->db->query("
                                       SELECT p.product_id,p.title,p.title_ar,p.product_code,p.weight,p.product_type,p.category,c.category_name,
                                              p.sale_price,v.sale_price as variation_price ,v.variation_id,v.supplier_price as variation_supplier_price,
                                              if(p.product_type = 'variation',v.title,'') as variation_title,v.current_stock,count(sale_id) as sold_count,v.sku_code
                                       FROM variation v 
                                       left join product p on(p.product_id = v.product_id)
                                       left join category c on (p.category = c.category_id)
                                       left join cart crt on (crt.variation_id = v.variation_id and sale_id != 0 )
                                       Where $condition 
                                         group by v.variation_id   
                                    ");

            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }

        function getUserWalletDetails(){
            $query = $this->db->query("
                                       SELECT u.user_id,u.phone,u.email,u.first_name,u.second_name,u.third_name,u.fourth_name,u.wallet_no,u.wallet_balance
                                       FROM user u
                                       order by user_id ASC
                                    ");
            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }

        function getCurrentWalletBalance($user_id = null){
            $condition = " 1=1 ";
            if($user_id != null){
                $condition .= " AND user_id = ".$this->db->escape($user_id);
}
            $query = $this->db->query("SELECT user_id,
                                                SUM(CASE WHEN type = 'credit' THEN amount else 0 END) AS add_amount,
                                                SUM(CASE WHEN type = 'debit' THEN amount else 0 END) AS destroy_amount
                                           FROM wallet  where $condition
                                           GROUP BY user_id
                                        ");


            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }
        
        function getCustomerReportData($user_type='',$user_status=''){
            $condition = " 1=1 ";
            if(!empty($user_type)){
                $condition .=  " AND  u.user_type = ". $this->db->escape($user_type); 
            }
            if(!empty($user_status)){
                 $condition .= " AND  u.approval_status = ". $this->db->escape($user_status); 
            }
            
            $query = $this->db->query("
                                       SELECT u.*,c.city_name_en,c.city_name_ar,a.area_name_en,a.area_name_ar
                                       From user as u
                                       left join city as c ON (c.city_id  =  u.city_id)
                                       left join area as a ON (a.area_id  =  u.area_id)
                                       Where $condition 
                                    ");
        
          
            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }
        
        function getDeliverySalesData($delivery_date,$payment_status=""){
            $deliveryDate = date( 'Y-m-d');
            if ( ! empty( $delivery_date ) ) {
                $deliveryDate = $delivery_date;
            }
            $DeliveryCondition = " 1=1 ";
            $DeliveryCondition .= " AND  s.order_status != 'cancelled' ";
            $DeliveryCondition .= ' AND  s.delivery_date_timeslot Like '.'\'%[{"date":"'.$deliveryDate.'"%\''; 
            
            $paymentStatusCondition = "  AND  1=1 ";
            if(!empty($payment_status)){
                $paymentStatusCondition = '  AND  s.payment_status Like '.'\'%,"status":"'.$payment_status.'"}%\'';
            }
//          
            $query = $this->db->query(' Select s.*,u.first_name,u.email,u.phone
                                        from sale s 
                                        Left Join user as u on (s.buyer = u.user_id )
                                        Where  '. $DeliveryCondition . $paymentStatusCondition .
                                        'order by s.sale_id DESC');
            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
            }
        }
        
       
        function getAreaSpecificDeliveryBoy($area_name=""){
            $area_id = $this->db->get_where('area',array('area_name_en'=>$area_name))->row()->area_id;
            $area_condition = "  AND  1=1 ";
            if(!empty($area_id)){
                $area_condition = '  AND  a.area_id = '. $this->db->escape($area_id);
            }
            $query = $this->db->query(' Select a.*
                                        from admin as a  Where a.role = 4 '. $area_condition . '
                                        INTERSECT
                                        Select b.*
                                        from admin as b  Where b.role = 4 
                                    ');
            echo "<pre>";
            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
            }
        }
        
        function getTimeSlots($timeslotDay=""){
            $currentTime = date('H:i:s');
            $condition = "  1=1 ";
            $condition .=  " AND  day = ". $this->db->escape($timeslotDay);
     
            $query = $this->db->query(" SELECT timeslots_id,day,start_time,end_time,TIME_FORMAT(start_time, '%h:%i %p') as startTime,TIME_FORMAT(end_time,'%h:%i %p') as endTime
                                        FROM timeslots 
                                        where $condition
                                        order by day, start_time    
                                        ");

            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }
        
        function getBillOfQtyReportData($sale_date,$timeslot){
            $sale_start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
            $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
            if (isset( $sale_date ) && ! empty( $sale_date ) ) {
                    $sale_start_time = strtotime( date( 'Y-m-d 00:00:00', strtotime( $sale_date ) ) );
                    $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59', strtotime( $sale_date ) ) );
            }
         
            $deliveryDateCondition = ' AND  s.delivery_date_timeslot Like '.'\'%[{"date":"'.$sale_date.'"%\'';
         
            $deliveryTimeSlotCondition = " AND 1=1 ";
            if(!empty($timeslot)){
                $deliveryTimeSlotCondition = ' AND  s.delivery_date_timeslot Like '.'\'%,"timeslot":"'.$timeslot.'"%\'';
            }
            
            $query = $this->db->query(' Select p.title as product_name_en,p.title_ar as product_name_ar , p.product_code, v.sku_code, c.price as unit_price, cc.category_name,sc.sub_category_name,b.name as brand_name ,sum(c.qty) as sum_of_qty,c.created_on,s.delivery_date_timeslot
                                        , GROUP_CONCAT(c.sale_id) as sale_ids,s.user_choice,c.product_data as cart_product_data ,c.supplier,sup.supplier_name,c.supplier_store_id,ss.store_name
                                        from  cart as c
                                        join product as p on (c.product_id = p.product_id)
                                        join variation as v on (c.variation_id = v.variation_id and c.sale_id != 0)
                                        join category as cc on (cc.category_id = p.category)
                                        join sub_category as sc on (sc.sub_category_id = p.sub_category)
                                        join brand as b on (b.brand_id = p.brand)
                                        join sale as s on (s.sale_id =  c.sale_id)
                                        join supplier as sup on (c.supplier =  sup.supplier_id)
                                        left join supplier_store  as ss  ON (c. supplier_store_id =  ss.supplier_store_id)
                                        Where  
                                        s.delivery_status NOT  Like \'%,"status":"delivered"%\'  AND s.order_status != "cancelled"  AND  s.payment_status NOT Like \'%,"status":"failed"}%\' 
                                         ' . $deliveryDateCondition . $deliveryTimeSlotCondition .
                                        ' group by  c.variation_id,s.delivery_date_timeslot order by c.created_on DESC');
              
                                        // removed  3rd group by part -- c.supplier_store_id
              
            /*    commented as based on delivery date                     
            $query = $this->db->query(' Select p.title as product_name_en,p.title_ar as product_name_ar , v.sku_code, c.price as unit_price, cc.category_name,sc.sub_category_name,b.name as brand_name ,sum(c.qty) as sum_of_qty,c.created_on,s.delivery_date_timeslot
                                        , GROUP_CONCAT(c.sale_id) as sale_ids,s.user_choice
                                        from  cart as c
                                        join product as p on (c.product_id = p.product_id)
                                        join variation as v on (c.variation_id = v.variation_id and c.sale_id != 0)
                                        join category as cc on (cc.category_id = p.category)
                                        join sub_category as sc on (sc.sub_category_id = p.sub_category)
                                        join brand as b on (b.brand_id = p.brand)
                                        join sale as s on (s.sale_id =  c.sale_id)
                                        Where  
                                        s.delivery_status NOT  Like \'%,"status":"delivered"%\'  AND s.order_status != "cancelled" AND 
                                        s.sale_datetime >= ' . $sale_start_time . ' And s.sale_datetime <= ' . $sale_end_time . 
                                         $deliveryTimeSlotCondition .
                                        ' group by  c.variation_id,s.delivery_date_timeslot order by c.created_on DESC');
              */  
                                      
            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
            }
        }
        
	//updated by Ritesh to fetch all orders except cancelled delivery status
        function getBillOfQtyRangeData($delivery_date_period,$supplier_id = 0 ){
            if ( ! empty( $delivery_date_period ) ) {
                $date_range = explode( ' - ', $delivery_date_period );
                if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
                        $start_date =  date( 'Y-m-d', strtotime( $date_range[0] ) );
                        $end_date   =  date( 'Y-m-d', strtotime( $date_range[1] ) );
                }
            }
        
            $deliveryDateCondition = " AND ( ";
            while (strtotime($start_date) <= strtotime($end_date)) {
                $delivery_date = date ("Y-m-d",strtotime($start_date));
                $deliveryDateCondition .= ' s.delivery_date_timeslot Like '.'\'%[{"date":"'.$delivery_date.'"%\'';
                $deliveryDateCondition .= ' OR ';
                $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
            }
            $deliveryDateCondition = rtrim($deliveryDateCondition, ' OR ');
            $deliveryDateCondition .= ' ) ';
            
            $supplierCondition =  " And 1=1 ";
            if(!empty($supplier_id)){
//               $supplierCondition = '  AND  s.product_details Like '.'\'%"supplier":"'.$supplier_id.'"%\''; 
               $supplierCondition = '  AND  c.supplier = '. $this->db->escape($supplier_id); 
            }
         

            $query = $this->db->query(' Select p.title as product_name_en,p.title_ar as product_name_ar ,p.product_code, v.sku_code, c.price as unit_price, cc.category_name,sc.sub_category_name,b.name as brand_name ,sum(c.qty) as sum_of_qty,c.created_on,s.delivery_date_timeslot
                                        , GROUP_CONCAT(c.sale_id) as sale_ids,s.user_choice,c.product_data as cart_product_data,c.supplier,sup.supplier_name,c.supplier_store_id,ss.store_name
                                        from  cart as c
                                        join product as p on (c.product_id = p.product_id)
                                        join variation as v on (c.variation_id = v.variation_id and c.sale_id != 0)
                                        join category as cc on (cc.category_id = p.category)
                                        join sub_category as sc on (sc.sub_category_id = p.sub_category)
                                        join brand as b on (b.brand_id = p.brand)
                                        join sale as s on (s.sale_id =  c.sale_id)
                                        join supplier as sup on (c.supplier =  sup.supplier_id)
                                        left join supplier_store  as ss  ON (c. supplier_store_id =  ss.supplier_store_id)
                                        Where  
                                        s.order_status != "" 
                                         ' . $deliveryDateCondition . $supplierCondition . $supplierStoreCondition .
                                        ' group by  c.variation_id order by c.created_on DESC');
              
            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
            }
        }
        
        function getBillOfQtyDateRangeStoreData($delivery_date_period,$supplier_id = 0, $supplier_store_id = 0){
            if ( ! empty( $delivery_date_period ) ) {
                $date_range = explode( ' - ', $delivery_date_period );
                if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
                        $start_date =  date( 'Y-m-d', strtotime( $date_range[0] ) );
                        $end_date   =  date( 'Y-m-d', strtotime( $date_range[1] ) );
                }
            }
         
            $deliveryDateCondition = " AND ( ";
            while (strtotime($start_date) <= strtotime($end_date)) {
                $delivery_date = date ("Y-m-d",strtotime($start_date));
                $deliveryDateCondition .= ' s.delivery_date_timeslot Like '.'\'%[{"date":"'.$delivery_date.'"%\'';
                $deliveryDateCondition .= ' OR ';
                $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
            }
            $deliveryDateCondition = rtrim($deliveryDateCondition, ' OR ');
            $deliveryDateCondition .= ' ) ';
            
            $supplierCondition =  " And 1=1 ";
            if(!empty($supplier_id)){
//               $supplierCondition = '  AND  s.product_details Like '.'\'%"supplier":"'.$supplier_id.'"%\''; 
               $supplierCondition = '  AND  c.supplier = '. $this->db->escape($supplier_id); 
            }
         
            $supplierStoreCondition =  " And 1=1 ";
            if(!empty($supplier_store_id)){
               $supplierStoreCondition = '  AND  c.supplier_store_id = '. $this->db->escape($supplier_store_id); 
            }


            $query = $this->db->query(' Select p.title as product_name_en,p.title_ar as product_name_ar ,p.product_code, v.sku_code, c.price as unit_price, cc.category_name,sc.sub_category_name,b.name as brand_name ,sum(c.qty) as sum_of_qty,c.created_on,s.delivery_date_timeslot
                                        , GROUP_CONCAT(c.sale_id) as sale_ids,s.user_choice,c.product_data as cart_product_data,c.supplier,sup.supplier_name,c.supplier_store_id,ss.store_name
                                        from  cart as c
                                        join product as p on (c.product_id = p.product_id)
                                        join variation as v on (c.variation_id = v.variation_id and c.sale_id != 0)
                                        join category as cc on (cc.category_id = p.category)
                                        join sub_category as sc on (sc.sub_category_id = p.sub_category)
                                        join brand as b on (b.brand_id = p.brand)
                                        join sale as s on (s.sale_id =  c.sale_id)
                                        join supplier as sup on (c.supplier =  sup.supplier_id)
                                        left join supplier_store  as ss  ON (c. supplier_store_id =  ss.supplier_store_id)
                                        Where  
                                        s.delivery_status NOT  Like \'%,"status":"delivered"%\'  AND s.order_status != "cancelled"  AND  s.payment_status NOT Like \'%,"status":"failed"}%\'
                                         ' . $deliveryDateCondition . $supplierCondition . $supplierStoreCondition .
                                        ' group by  c.variation_id,c.supplier_store_id order by c.created_on DESC');
              
            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
            }
        }
        
        function getBillOfQtyStoreData($delivery_date_period,$supplier_id = 0,$supplier_store_id = 0){
            if ( ! empty( $delivery_date_period ) ) {
                $date_range = explode( ' - ', $delivery_date_period );
                if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
                        $start_date =  date( 'Y-m-d', strtotime( $date_range[0] ) );
                        $end_date   =  date( 'Y-m-d', strtotime( $date_range[1] ) );
                }
            }
         
            $deliveryDateCondition = " AND ( ";
            while (strtotime($start_date) <= strtotime($end_date)) {
                $delivery_date = date ("Y-m-d",strtotime($start_date));
                $deliveryDateCondition .= ' s.delivery_date_timeslot Like '.'\'%[{"date":"'.$delivery_date.'"%\'';
                $deliveryDateCondition .= ' OR ';
                $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
            }
            $deliveryDateCondition = rtrim($deliveryDateCondition, ' OR ');
            $deliveryDateCondition .= ' ) ';
            
            $supplierCondition =  " And 1=1 ";
            if(!empty($supplier_id) && !empty($supplier_store_id)){
               $supplierCondition = '  AND  s.product_details Like '.'\'%"supplier":"'.$supplier_id.'"%\''; 
               $supplierCondition .= '  AND  c.supplier_store_id = '. $this->db->escape($supplier_store_id); 
            }
         

            $query = $this->db->query(' Select p.title as product_name_en,p.title_ar as product_name_ar , p.product_code,v.sku_code, c.price as unit_price, cc.category_name,sc.sub_category_name,b.name as brand_name ,sum(c.qty) as sum_of_qty,c.created_on,s.delivery_date_timeslot
                                        , GROUP_CONCAT(c.sale_id) as sale_ids, GROUP_CONCAT(c.cart_id) as cart_ids,s.user_choice,c.product_data as cart_product_data,sup.supplier_name,supss.store_name
                                        from  cart as c
                                        join product as p on (c.product_id = p.product_id)
                                        join variation as v on (c.variation_id = v.variation_id and c.sale_id != 0)
                                        join category as cc on (cc.category_id = p.category)
                                        join sub_category as sc on (sc.sub_category_id = p.sub_category)
                                        join brand as b on (b.brand_id = p.brand)
                                        join sale as s on (s.sale_id =  c.sale_id)
                                        join supplier as sup on (c.supplier =  sup.supplier_id)
                                        join supplier_store as supss on (c.supplier_store_id =  supss.supplier_store_id)
                                        Where  
                                        s.delivery_status NOT  Like \'%,"status":"delivered"%\'  AND s.order_status != "cancelled"  AND  s.payment_status NOT Like \'%,"status":"failed"}%\'
                                         ' . $deliveryDateCondition . $supplierCondition .
                                        ' group by  c.variation_id  order by c.created_on DESC');
              
            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
            }
        }
        
        
        //FOR DASHBOARD COUNTS :: START
        function fetchDashBoardCount($date_range = 'no' , $payment_status = "", $delivery_status = "" , $order_status = "",$delivery_date="",$isTodaysPaid='no',$isTodaysDelivered='no'){
            $sale_start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
            $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
     
            $paymentStatusCondition = "  AND  1=1 ";
            if(!empty($payment_status)){
                $paymentStatusCondition = '  AND  s.payment_status Like '.'\'%,"status":"'.$payment_status.'"}%\'';
            }

            $deliveryStatusCondition = "  AND  1=1 ";
            if(!empty($delivery_status)){
                $deliveryStatusCondition = '  AND  s.delivery_status Like '.'\'%,"status":"'.$delivery_status.'",%\'';  //1122
            }
            $orderStatusCondition = " AND 1=1 ";
            if(!empty($order_status)){
                $orderStatusCondition .=  " AND  s.order_status = ". $this->db->escape($order_status); 
            }

            $isCountForToday = "  AND  1=1 ";
            if(!empty($date_range) && $date_range == 'yes'){
                $isCountForToday = '  AND  s.sale_datetime >= ' . $sale_start_time . ' And s.sale_datetime <= ' . $sale_end_time ;  //1122
            }

            $deliveryDateCondition = "  AND  1=1 ";
            if(!empty($delivery_date)){
                $deliveryDateCondition .= ' AND  s.delivery_date_timeslot Like '.'\'%[{"date":"'.$delivery_date.'"%\''; 
                $deliveryDateCondition .= '  AND   s.order_status != "cancelled" ';
            }

            $todaysPaidOrders = "  AND  1=1 ";
            if(!empty($isTodaysPaid) && $isTodaysPaid == 'yes'){
                $todaysPaidOrders = '  AND  s.payment_timestamp >= ' . $sale_start_time . ' And s.payment_timestamp <= ' . $sale_end_time ;  //1122
            }

            $todaysDeliveryOrCancelCondition = "  AND  1=1 ";
            if(!empty($isTodaysDelivered) && $isTodaysDelivered == 'yes'){
                $delivery_today = date('Y-m-d');
                $todaysDeliveryOrCancelCondition = '  AND  s.delivery_status Like '.'\'%,"delivery_time":"'.$delivery_today.'%\'';  //1122
            }
//            
            $query = $this->db->query(' Select * from sale as s where 1=1 '
                                        . $paymentStatusCondition . $deliveryStatusCondition 
                                        .  $isCountForToday . $orderStatusCondition 
                                        . $deliveryDateCondition . $todaysPaidOrders 
                                        . $todaysDeliveryOrCancelCondition .
                                        ' order by s.sale_id DESC');
                                           
            if($query->num_rows() > 0){
                return $query->num_rows(); 
            }else{
                return 0;
            }
        }
        
        function dashboardAmount($payment_status = "",$totalRevenue = 'no'){
            $sale_start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
            $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
            
            $paymentStatusCondition = "  1=1 ";
            if(!empty($payment_status) && $payment_status == 'paid'){
                $paymentStatusCondition .= '  AND  s.payment_status Like '.'\'%,"status":"'.$payment_status.'"}%\'';
                $paymentStatusCondition .= '  AND  s.payment_timestamp >= ' . $sale_start_time . ' And s.payment_timestamp <= ' . $sale_end_time ;  //1122
            }else if(!empty($payment_status) && $payment_status == 'pending') {
                $paymentStatusCondition  .= '  AND  s.payment_status  Like '.'\'%,"status":"'.$payment_status.'"}%\'';
                //$paymentStatusCondition  .= '  AND  s.sale_datetime >= ' . $sale_start_time . ' And s.sale_datetime <= ' . $sale_end_time ;  //1122
                $todayDate = date('Y-m-d');
                $paymentStatusCondition .= ' AND  s.delivery_date_timeslot Like '.'\'%[{"date":"'.$todayDate.'"%\'';
            }
      
            $totalRevenueCondition =  " AND 1=1 ";
            if($totalRevenue == 'yes'){
                $totalRevenueCondition =  ' AND s.order_status != "cancelled" ';
            }
      
            $query = $this->db->query(' Select sum(grand_total) as total_amount
                                        from sale s 
                                        Where  '. $paymentStatusCondition . $totalRevenueCondition .
                                        ' order by s.sale_id DESC');
                        
            if($query->num_rows() > 0){
                $total_amount =  $query->row()->total_amount;
                $total_amount_in_sdg = get_converted_currency($total_amount,DEFAULT_CURRENCY);
                return $total_amount_in_sdg; 
            }else{
                return 0;
            }
        }
        
        //FOR DASHBOARD COUNTS :: END
        
       function dashboardUserPercentage(){
       
            $query = $this->db->query(' SELECT  sex, count(sex) as total_record ,
                                        ROUND(count(sex) * 100 / (SELECT count(sex) AS s FROM user ),2) AS `total_percentage`
                                        FROM user
                                        group by sex
                                        order by sex DESC
                                       ' );
        
            if($query->num_rows() > 0){
                $data = $query->result_array(); 
                $percentagesValues = array_column($data,'total_percentage');
                return $percentagesValues;
            }else{
                $falseValues= array(0,0,0);
                return $falseValues;
            }
        }
     
       
        function getSupplierStoreForCron($city_id = 0 , $area_id = 0 , $supplier_ids = 0) {
                $conditionN  = " supplier_id IN (". $supplier_ids. ")";
                $conditionN .= " AND city_id = ".$city_id ;
                $conditionN .= " AND area_ids like '%,".$area_id.",%' " ;
		$sql = $this->db->query( " SELECT GROUP_CONCAT(supplier_store_id ORDER BY supplier_store_id ) as supplier_store_ids ,supplier_id 
                                           from supplier_store where $conditionN
		                           group by supplier_id " );
        
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}
     
        function getSupplierSaleAreaSpecific($city_id = 0 , $area_id = 0 , $supplier_id = 0) {
                $conditionN  = " supplier_store_ids != '' " ;
                $conditionN  .= " AND city_id = ".$city_id ;
                $conditionN .= " AND area_id = ".$area_id ;
                $conditionN .= " AND supplier_ids like '%".$supplier_id."%' " ;
		$sql = $this->db->query( " SELECT sale_id as last_sale_id,supplier_store_ids as last_assigned_store_ids
                                           from sale  where $conditionN
		                           order by sale_id DESC 
                                           LIMIT 1
                                           ");
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
                }
	}

        function getSupplierStoreForRoundRobin( $supplier_store_id = '', $delivery_date='') {
                $conditionN  = $dateConditionN = " 1=1 " ;
                if(isset($supplier_store_id) && !empty($supplier_store_id)){
                    $conditionN = " s.supplier_store_id IN (".$supplier_store_id.") ";
                }
                if(isset($delivery_date) && !empty($delivery_date)){
                    $dateConditionN = " and c.delivery_date=".$this->db->escape($delivery_date);
                }
                
		$sql = $this->db->query( " 
                                        SELECT s.supplier_store_id ,IFNULL(count(distinct c.sale_id), 0) as count,IFNULL(max(c.sale_id), 0) as last_sale_count
                                        FROM supplier_store s
                                        LEFT JOIN cart c  ON (s.supplier_store_id=c.supplier_store_id and s.status='Active' and c.supplier_store_id!=0 and c.sale_id!=0 and $dateConditionN)
                                        where $conditionN
                                        group by s.supplier_store_id
                                        ORDER BY count  ASC
                                           ");
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}

        function getDeliverySaleAreaSpecific($city_id = 0 , $area_id = 0 ) {
                $conditionN  = " admin_id != 0 " ;
                $conditionN  .= " AND city_id = ".$city_id ;
                $conditionN .= " AND area_id = ".$area_id ;
		$sql = $this->db->query( " SELECT sale_id as last_sale_id,admin_id as last_assigned_admin_id
                                           from sale  where $conditionN
		                           order by sale_id DESC 
                                           LIMIT 1
                                           ");
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}

        function getDeliveryBoyForRoundRobin( $city_id = '',$area_id = '', $delivery_date='') {
                $dateConditionN = " 1=1 " ;
                $conditionN = " a.role = 4 AND a.status='Active' AND a.assign_orders='yes' ";
                if(isset($city_id) && !empty($city_id)){
                    $conditionN .= " AND a.city_id=".$this->db->escape($city_id);
                }
                if(isset($area_id) && !empty($area_id)){
                    $conditionN .= ' AND a.area_ids LIKE "%'.$area_id.'%"';
                }
                if(isset($delivery_date) && !empty($delivery_date)){
                    $current_date = '"date":"'.$delivery_date.'"';
                    $dateConditionN  = " s.delivery_date_timeslot like '%".$current_date."%' ";
                }
                $conditionN .= "AND a.admin_id IN (SELECT p.admin_id FROM admin p WHERE p.role=4 and json_search(p.area_ids, 'one', $area_id) IS NOT NULL)";
                
		$sql = $this->db->query( " 
                                        Select a.admin_id,a.name,a.phone,a.email,a.status,IFNULL(count(distinct s.sale_id), 0) as count,IFNULL(max(s.sale_id), 0) as last_sale_id
                                        FROM admin a
                                        LEFT JOIN sale s  ON (a.admin_id=s.admin_id and $dateConditionN)
                                        where $conditionN
                                        group by  a.admin_id
                                        ORDER BY count  ASC
                                           ");
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}
        

        function getTotalOrdersReportData($sale_time_period){
            $sale_start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
            $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
            if ( ! empty( $sale_time_period ) ) {
                $date_range = explode( ' - ', $sale_time_period );
                if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
                        $sale_start_time = strtotime( date( 'Y-m-d 00:00:00', strtotime( $date_range[0] ) ) );
                        $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59', strtotime( $date_range[1] ) ) );
                }
            }

//            s.payment_status Like \'%,"status":"paid"}%\' 
            $query = $this->db->query(' Select s.*,u.first_name,u.email,u.phone,u.sex
                                        from sale s 
                                        Left Join user as u on (s.buyer = u.user_id )
                                        Where  1=1 AND 
                                        s.sale_datetime >= ' . $sale_start_time . ' And s.sale_datetime <= ' . $sale_end_time . 
                                        ' order by s.sale_id DESC');
                           
            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
}
        }

        function getFinancialReportData($sale_time_period){
            $sale_start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
            $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
            if ( ! empty( $sale_time_period ) ) {
                $date_range = explode( ' - ', $sale_time_period );
                if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
                        $sale_start_time = strtotime( date( 'Y-m-d 00:00:00', strtotime( $date_range[0] ) ) );
                        $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59', strtotime( $date_range[1] ) ) );
                }
            }

//            s.payment_status Like \'%,"status":"paid"}%\' 
            $query = $this->db->query(' Select s.*,u.first_name,u.email,u.phone
                                        from sale s 
                                        Left Join user as u on (s.buyer = u.user_id )
                                        Where  1=1 AND 
                                        s.sale_datetime >= ' . $sale_start_time . ' And s.sale_datetime <= ' . $sale_end_time . 
                                        ' order by s.sale_id DESC');

            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
            }
        }

        function getUserOrdersReportData($sale_time_period){
            $sale_start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
            $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
            if ( ! empty( $sale_time_period ) ) {
                $date_range = explode( ' - ', $sale_time_period );
                if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
                        $sale_start_time = strtotime( date( 'Y-m-d 00:00:00', strtotime( $date_range[0] ) ) );
                        $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59', strtotime( $date_range[1] ) ) );
                }
            }

            $query = $this->db->query(' SELECT u.user_id,u.first_name,u.phone,cc.city_name_en,cc.city_name_ar,aa.area_name_en,aa.area_name_ar,count(s.sale_id) as no_of_orders, GROUP_CONCAT(s.sale_id) as sale_ids,sum(s.grand_total) as amount_usd,
                                        sum(grand_total*substring_index(substring_index(s.user_choice, \',"currency_conversion":"\', -1), \'"}]\', 1)) as amount_sdg,
                                        SUM(case when substring_index(substring_index(s.delivery_status, \',"status":"\', -1), \'","comment":\', 1) = \'delivered\' then 1 else 0 end) as delivered_orders,
                                        SUM(case when substring_index(substring_index(s.delivery_status, \',"status":"\', -1), \'","comment":\', 1) = \'pending\' then 1 else 0 end) as pending_orders,
                                        SUM(case when substring_index(substring_index(s.delivery_status, \',"status":"\', -1), \'","comment":\', 1) = \'cancelled\' then 1 else 0 end) as cancelled_orders,
                                        SUM(case when substring_index(substring_index(s.delivery_status, \',"status":"\', -1), \'","comment":\', 1) = \'process\' then 1 else 0 end) as process_orders
                                        FROM user as u
                                        Left Join sale as s ON (u.user_id = s.buyer)
                                        LEFT Join city as cc ON (u.city_id =  cc.city_id)
                                        LEFT Join area as aa ON (u.area_id =  aa.area_id)
                                        where s.sale_datetime >= ' . $sale_start_time . ' And s.sale_datetime <= ' . $sale_end_time . 
                                        ' Group by u.user_id
                                          order by s.sale_id DESC' );

            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
            }
        }
        
     
        //added by ritesh : start
        function getDeliveryBoyCityData( $city_id = 0 ) {
                $condition = " 1=1 and status='ok' ";
                if(isset($city_id) && $city_id>0){
                    $condition .= ' And city_id IN ('.$city_id.') ';
}

		$sql = $this->db->query( "Select city_id,city_name_en,city_name_ar from city where $condition " );
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}

	function getDeliveryBoyAreaData( $area_id = 0, $city_id = 0 ) {
               $condition = " 1=1 and status='ok' ";
                if(isset($city_id) && $city_id>0){
                    $condition .= ' And city_id IN ('.$city_id.') ';
                }

                if(isset($area_id) && !empty($area_id)){
                    $condition .= ' And area_id IN ('.$area_id.') ';
                }



                $sql = $this->db->query( "Select area_id,city_id,area_name_en,area_name_ar  from area where $condition " );

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}
        
         function getSupplierData( $supplier_id  = 0 ) {
                $condition = " 1=1 and status='ok' ";
                if(isset($supplier_id) && $supplier_id>0){
                    $condition .= ' And supplier_id IN ('.$supplier_id.') ';
                }
           
		$sql = $this->db->query( "Select supplier_id,mobile_number,supplier_name,address,unique_code,email_address,company_name from supplier where $condition " );
		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}
        
        function getSupplierStoreData($supplier_store_id='',$supplier_id='',$area_id = '', $city_id = '') {
               $condition = " 1=1 and st.status='Active' ";
                if(isset($supplier_store_id) && !empty($supplier_store_id)){
                    $condition .= ' And st.supplier_store_id IN ('.$supplier_store_id.') ';
                }
                
                if(isset($supplier_id) && !empty($supplier_id)){
                    $condition .= ' And st.supplier_id IN ('.$supplier_id.') ';
                }
                
                if(isset($city_id) && !empty($area_id)){
                    $condition .= ' And st.city_id IN ('.$city_id.') ';
                }
                
                if(isset($area_id) && !empty($area_id)){
                    $explode_into_array = explode($area_id,',');
                    $condition .= ' And (';
                    foreach($explode_into_array as $value){
                         $areacondition .= ' st.area_ids like %,'.$value.',% OR';
                    }
                    $areacondition = trim($areacondition, "OR");
                    $condition .= ' $areacondition ) ';
                }
           
            
            
                $sql = $this->db->query( "Select st.supplier_store_id,st.supplier_id,s.supplier_name,st.store_name,st.store_number,st.store_address,st.city_id  "
                                            . "from supplier_store st JOIN supplier s ON (s.supplier_id = st.supplier_id) where $condition " );
                 

		if ( $sql !== false && $sql->num_rows() >= 1 ) {
			return $sql->result_array();
		} else {
			return false;
		}
	}

        //added by rushikesh - 24-07-2020 - END
        //customer delivery address report model start:by rushikesh
        function getCustomerAddressData($time_period,$user_type='',$user_status=''){
            $start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
            $end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
            if ( ! empty( $time_period ) ) {
                $date_range = explode( ' - ', $time_period );
                if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
                        $start_time = strtotime( date( 'Y-m-d 00:00:00', strtotime( $date_range[0] ) ) );
                        $end_time   = strtotime( date( 'Y-m-d 23:59:59', strtotime( $date_range[1] ) ) );
                }
            }
            
            $condition = " AND 1=1 ";
            if(!empty($user_type)){
                $condition .=  " AND  u.user_type = ". $this->db->escape($user_type); 
            }
            if(!empty($user_status)){
                 $condition .= " AND  u.approval_status = ". $this->db->escape($user_status); 
            }
        
            $query = $this->db->query('
                                       SELECT u.*,FROM_UNIXTIME(creation_date) as date,c.city_name_en,c.city_name_ar,a.area_name_en,a.area_name_ar,count(s.sale_id) as no_of_orders,FROM_UNIXTIME(MAX(sale_datetime),"%d/%m/%Y") as last_order_date,MAX(FROM_UNIXTIME(s.sale_datetime,"%M")) as last_order_month
                                        From user as u 
                                        left join city as c ON (c.city_id = u.city_id) 
                                        left join area as a ON (a.area_id = u.area_id) 
                                        Left Join sale as s ON (u.user_id = s.buyer)
                                        Where u.creation_date >= ' . $start_time . ' And u.creation_date <= ' . $end_time . $condition.
                                       'Group by u.user_id order by u.user_id');
     
            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }



        function getCustomerAddress($user){
            $query = $this->db->query("
                                       SELECT u.user_id,ua.user_id,ua.title,ua.langlat,ua.number,ua.city_id,ua.area_id,c.city_name_en,c.city_name_ar,a.area_name_en,a.area_name_ar from user_address as ua
                                        left JOIN user as u ON(u.user_id=ua.user_id)
                                        left JOIN city as c ON(c.city_id=ua.city_id)
                                        left JOIN area as a ON(a.area_id=ua.area_id)
                                        where u.user_id = $user 
                                    ");


            if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }
        //end

        //total_order_revenue_report model start:by rushikesh
        function getTotalRevenueReportData($sale_time_period){
            $sale_start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
            $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
            if ( ! empty( $sale_time_period ) ) {
                $date_range = explode( ' - ', $sale_time_period );
                if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
                        $sale_start_time = strtotime( date( 'Y-m-d 00:00:00', strtotime( $date_range[0] ) ) );
                        $sale_end_time   = strtotime( date( 'Y-m-d 23:59:59', strtotime( $date_range[1] ) ) );
                }
            }
            
            $query = $this->db->query(' SELECT count(s.sale_id) as no_of_orders,sum(s.grand_total) as amount_usd,
                                        from_unixtime(s.sale_datetime, "%d/%m/%Y") as date,from_unixtime(s.sale_datetime, "%W") as day,
                                        sum(grand_total*substring_index(substring_index(s.user_choice, \',"currency_conversion":"\', -1), \'"}]\', 1)) as amount_sdg
                                        FROM sale as s
                                        where s.sale_datetime >= ' . $sale_start_time . ' And s.sale_datetime <= ' . $sale_end_time . 
                                        ' Group by date' );
                                   
            if($query->num_rows() > 0){
                return $query->result_array(); 
            }else{
                return false;
            }
        }
        //end
        //added by rushikesh - 24-07-2020 - END

        //added by sagar - 01-08-2020 - START
        function getCustomerWalletData($time_period,$user_id = ''){
            $start_time = strtotime( date( 'Y-m-d 00:00:00' ) );
            $end_time   = strtotime( date( 'Y-m-d 23:59:59' ) );
            if ( ! empty( $time_period ) ) {
                $date_range = explode( ' - ', $time_period );
                if ( isset( $date_range[0] ) && ! empty( $date_range[0] ) && isset( $date_range[1] ) && ! empty( $date_range[1] ) ) {
                        $start_time = ( date( 'Y-m-d 00:00:00', strtotime( $date_range[0] ) ) );
                        $end_time   = ( date( 'Y-m-d 23:59:59', strtotime( $date_range[1] ) ) );
                }
            }
        
            $condition = " AND 1=1 ";
            if(!empty($user_id)){
                $condition .=  " AND  w.user_id = ". $this->db->escape($user_id); 
            }
     
            $query = $this->db->query('
                                       SELECT  u.first_name,u.fourth_name,u.wallet_no,u.wallet_type,w.*,a.name
                                        From wallet as w
                                        left join user as u  ON (w.user_id = u.user_id) 
                                        left join admin as a ON (a.admin_id = w.admin_id) 
                                        Where w.date_time >= "' . $start_time . '" And w.date_time <= "' . $end_time . '" '. $condition.
                                       ' order by w.user_id, w.wallet_id Desc ');


                               if ($query !== FALSE && $query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }
        //added by sagar - 01-08-2020 - END

        //added by sagar - 28-09-2020 - START
        function customerLastOrderDate($customer_id = 0 , $sale_id = 0){
//             %H:%i:%s -- add this if want time
            $query = $this->db->query("
                                        SELECT sale_id, sale_datetime,from_unixtime(sale_datetime, '%Y-%m-%d') as saledate 
                                        FROM `sale` where buyer = $customer_id  and sale_id < $sale_id 
                                        AND order_status  != 'cancelled'    
                                        ORDER BY `sale_id`  DESC limit 1
                                    ");

            if ($query !== FALSE && $query->num_rows() == 1) {
                $data =  $query->row_array();
                return $data['saledate'];
            } else {
                return '0000-00-00';
            }
        }
        //added by sagar - 28-09-2020 - END

        //added by Arjun - 13-06-2023 Start
        function cancelProducts($product_ids,$sale_id){
        	$saledata = $this->db->get_where('sale', array(
				'sale_id' . ' =' => $sale_id,
			) )->result_array();

			$price = $saledata[0]['invoice_amount'];
			$productDetails = json_decode($saledata[0]['product_details'],true);
			$keys = array_keys($productDetails);

			$product_price = array();
			$i = 0;
			$final_price = $price;

			// Iterate through the product details
			foreach ($productDetails as $row) {
			    // Check if the product_id is in the list of product_ids to be cancelled
			    if (in_array($row['product_id'], $product_ids)) {
			        // Update the status of the product to 'cancelled'
			        $productDetails[$keys[$i]]['status'] = 'cancelled';
			        $product_price[] = $row['price'] * $row['qty'];
			    }
			    $i++;
			}

			// Calculate the final price by subtracting the sum of cancelled product prices from the original grand total
			$final_price -= array_sum($product_price);

			// Convert the updated product details back to JSON format
			$jsonData = json_encode($productDetails);

			// Update the product details and grand total in the sale table
			$data = array(
			    'product_details' => $jsonData,
			    'invoice_amount' => $final_price
			);
			$this->db->where('sale_id', $sale_id);
			$this->db->update('sale', $data);

			$lastSale = $this->db->get_where('sale', array(
				'sale_id' . ' =' => $sale_id,
			) )->result_array();
			$productDetail = json_decode($lastSale[0]['product_details'],true);
			$payment_status = json_decode($lastSale[0]['payment_status'],true);
			$delivery_status = json_decode($lastSale[0]['delivery_status'],true);
			$statusCount =0;
			$AllCancelled = 0;

			foreach($productDetail as $key => $prod) {
				if(!array_key_exists('status', $prod)) {
					$statusCount++;
					$AllCancelled++;

				}
			}

			if($statusCount == 0) {
				$payment_status[0]['status'] = 'cancelled';
				$delivery_status[0]['status'] = 'cancelled';
				$order_status = 'cancelled';

				$new_payment_status = json_encode($payment_status);
				$new_delivery_status = json_encode($delivery_status);

				$new_data = array(
				    'payment_status' => $new_payment_status,
				    'delivery_status' => $new_delivery_status,
				    'order_status' => $order_status
				);

				$this->db->where('sale_id', $sale_id);
				$this->db->update('sale', $new_data);

			}

			if ($this->db->affected_rows() > 0) {
				$data = array(
					'message' => "Deleted Selected Items",
					'orderCount' => $AllCancelled
				);
			    return $data;
			} else {
			    return "Failed";
			}
        }
        //ended by Arjun - 13-06-2023

}



