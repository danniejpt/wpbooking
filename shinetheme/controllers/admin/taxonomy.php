<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (!class_exists('WPBooking_Admin_Taxonomy_Controller')) {
	class WPBooking_Admin_Taxonomy_Controller extends WPBooking_Controller
	{
		static $_inst;

		protected $_option_name = 'wpbooking_taxonomies';

		function __construct()
		{
			parent::__construct();

			if (WPBooking_Input::post('wpbooking_create_taxonomy')) {
				add_action('init', array($this, '_wpbooking_create_taxonomy'));
			}
			if (WPBooking_Input::get('action') == 'wpbooking_delete_taxonomy') {
				add_action('init', array($this, '_delete_taxonomy'));
			}
			add_action('admin_menu', array($this, '_add_taxonomy_page'));

			add_action('init', array($this, '_register_taxonomy'));

			add_action('wp_ajax_wpbooking_add_term',array($this,'_add_term'));

			add_action('wp_ajax_wpbooking_add_extra_service',array($this,'_ajax_add_extra_service'));



            $attr_list = $this->get_taxonomies();
            $attr_list['wpbooking_amenity'] = array();
            $attr_list['wb_hotel_room_facilities'] = array();
            if (!empty($attr_list)) {
                foreach ($attr_list as $key => $value) {
                    add_filter('manage_edit-' . $key . '_columns', array(
                        $this,
                        'product_cat_columns'
                    ));
                    add_filter('manage_' . $key . '_custom_column', array(
                        $this,
                        'product_cat_column'
                    ), 10, 3);
                }
            }
            $this->add_meta_field();

		}
        function add_meta_field()
        {
            if (is_admin()) {
                $attr_list = $this->get_taxonomies();
                $attr_list['wpbooking_amenity'] = array();
                $attr_list['wb_hotel_room_facilities'] = array();
                $pages = array();
                if (!empty($attr_list)) {
                    foreach ($attr_list as $key => $value) {
                        $pages[] = $key;
                    }
                }
                $config = array(
                    'id' => 'wpbooking_extra_infomation', // meta box id, unique per meta box
                    'title' => esc_html__('Extra Information', 'wpbooking'), // meta box title
                    'pages' => $pages, // taxonomy name, accept categories, post_tag and custom taxonomies
                    'context' => 'normal', // where the meta box appear: normal (default), advanced, side; optional
                    'fields' => array(), // list of meta fields (can be added by field arrays)
                    'local_images' => false, // Use local or hosted images (meta box images for add/remove)
                    'use_with_theme' => false //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
                );
                $my_meta = new Tax_Meta_Class($config);
                $my_meta->addText('wpbooking_icon', array(
                    'name' => esc_html__('Icon Picker', 'wpbooking'),
                    'desc' => ''
                ));
                $my_meta->Finish();
            }
        }
        function product_cat_columns($columns)
        {
            $new_columns         = array();
            if(!empty($columns['cb'])){
                $new_columns['cb']   = $columns['cb'];
                $new_columns['icon'] = esc_html__('Icon', 'wpbooking');

                unset($columns['cb']);
            }
            return array_merge($new_columns, $columns);
        }
        function product_cat_column($columns, $column, $id)
        {
            if ($column == 'icon') {
                $icon = get_tax_meta($id, 'wpbooking_icon');
                $columns .= '<i style="font-size:24px" class="'.wpbooking_handle_icon($icon).'"></i>';
            }
            return $columns;
        }
		/**
		 * Ajax create new extra service item for
		 *
		 * @since 1.0
		 * @author dungdt
		 */
		function _ajax_add_extra_service()
		{
			$res=array('status'=>0);
			if(current_user_can('manage_options') and $service_type=WPBooking_Input::post('service_type') and $service_name=WPBooking_Input::post('service_name')){
				$tax='wpbooking_extra_service';
				$parent_term = term_exists( $service_name, $tax ); // array is returned if taxonomy is given
				$args = array();
				$service_desc=WPBooking_Input::post('service_desc');
				if(!empty($service_desc)){
					$args['description'] = $service_desc;
				}
				if($parent_term){
                    $check=get_term_meta($parent_term['term_id'],'service_type',true);
					if(!$check){
						$res['status']=1;
                        update_term_meta($parent_term['term_id'],'service_type',$service_type);
					}else{
						$res['message']=esc_html__('Term exists','wpbooking');
					}
				}else{
					$q=wp_insert_term($service_name,$tax,$args);
					if(!is_wp_error($q)){
						$res['status']=1;
						$res['data']=array(
							'term_id'=>$q['term_id'],
							'title'=>$service_name);
                        update_term_meta($q['term_id'],'service_type',$service_type);
					}
				}

			}

			echo json_encode($res);
			die;
		}

		/**
		 * Ajax Add Term for metabox
		 *
		 * @since 1.0
		 * @author dungdt
		 *
		 */
		function _add_term()
		{
			$res=array(
				'status'=>0
			);

			if(current_user_can('manage_options') and $term_name=WPBooking_Input::post('term_name') and $tax=WPBooking_Input::post('taxonomy')){

				$parent_term = term_exists( $term_name, $tax ); // array is returned if taxonomy is given
				if($parent_term){
				    $res['status']=1;
                    $res['data']=array(
                        'term_id'=>$parent_term['term_id'],
                        'name'=>$term_name);
				}else{
					$q=wp_insert_term($term_name,$tax);
					if(!is_wp_error($q)){
						$res['status']=1;
						$res['data']=array(
							'term_id'=>$q['term_id'],
							'name'=>$term_name);

						// Extra Fields
						$other_data_raw=WPBooking_Input::post('other_data');
						parse_str(urldecode($other_data_raw),$other_data);

						$fields=array('icon');
						if(!empty($other_data['_add_term']) and is_array($other_data['_add_term'])){
							foreach($fields as $field){
								if(array_key_exists($field,$other_data['_add_term'])){

                                    update_term_meta($q['term_id'],$field,$other_data['_add_term'][$field]);

									switch($field){
										case "icon":
											$res['extra_fields'][$field]=wpbooking_icon_class_handler($other_data['_add_term'][$field]);
											break;
										default:
											$res['extra_fields'][$field]=$other_data['_add_term'][$field];
											break;
									}
								}
							}
						}

					}
				}

			}

			echo json_encode($res);
			die;
		}

		function _register_taxonomy()
		{
			$all = $this->get_taxonomies();
			if (!empty($all) and is_array($all)) {
				foreach ($all as $key => $value) {
					$labels = array(
						'name' => $value['label'],
						'add_new_item'      => sprintf(esc_html__('Add New %s','wpbooking'),$value['label']),
						'new_item_name'     => sprintf(esc_html__('New %s Name','wpbooking'),$value['label']),
					);

					$args = array(
						'hierarchical'       => TRUE,
						'labels'             => $labels,
						'show_ui'            => TRUE,
						'show_tagcloud' 	 => FALSE,
						'show_admin_column'  => FALSE,
						'query_var'          => TRUE,
                        'meta_box_cb'=>false,
						'rewrite'            => array('slug' => $value['slug']),
					);

					register_taxonomy($value['name'], array('wpbooking_service'), $args);

					$hide=apply_filters('wpbooking_hide_taxonomy_select_box',true);
					$hide=apply_filters('wpbooking_hide_taxonomy_select_box_'.$value['name'],$hide);
					if($hide)
					WPBooking_Assets::add_css("#".$value['name'].'div{display:none!important}');
				}
			}

		}

		function _delete_taxonomy()
		{
			if ($tax_name = WPBooking_Input::get('tax_name')) {
				$all = $this->get_taxonomies();
				unset($all[$tax_name]);
				update_option($this->_option_name, $all);
                wpbooking_set_admin_message(sprintf('<p>%s</p>',esc_html__('Delete Success','wpbooking')), 'success');

			} else {
                wpbooking_set_admin_message(sprintf('<p>%s</p>',esc_html__('Please select a Taxonomy','wpbooking')), 'error');
			}
			wp_redirect($this->get_page_url());
			die;
		}
		
		function _wpbooking_create_taxonomy()
		{
			$error = FALSE;
			$validate = TRUE;

			check_admin_referer('wpbooking_create_taxonomy');
			$taxonomy_label = stripslashes(WPBooking_Input::post('taxonomy_label'));
			$taxonomy_slug = stripslashes(WPBooking_Input::post('taxonomy_slug'));// for rewrite url

			$action = WPBooking_Input::post('taxonomy_name') ? 'edit' : 'add';

			if ($action == 'add') {
				$taxonomy_name = mb_strtolower($taxonomy_label);
				$taxonomy_name = $this->convert_vi_to_en($taxonomy_name);
				$taxonomy_name = 'wb_tax_' . str_replace('-', '_', $taxonomy_name);
			} else {
				$taxonomy_name = WPBooking_Input::post('taxonomy_name');
			}

			// Forbidden attribute names
			// http://codex.wordpress.org/Function_Reference/register_taxonomy#Reserved_Terms
			$reserved_terms = array(
				'attachment',
				'attachment_id',
				'author',
				'author_name',
				'calendar',
				'cat',
				'category',
				'category__and',
				'category__in',
				'category__not_in',
				'category_name',
				'comments_per_page',
				'comments_popup',
				'cpage',
				'day',
				'debug',
				'error',
				'exact',
				'feed',
				'hour',
				'link_category',
				'm',
				'minute',
				'monthnum',
				'more',
				'name',
				'nav_menu',
				'nopaging',
				'offset',
				'order',
				'orderby',
				'p',
				'page',
				'page_id',
				'paged',
				'pagename',
				'pb',
				'perm',
				'post',
				'post__in',
				'post__not_in',
				'post_format',
				'post_mime_type',
				'post_status',
				'post_tag',
				'post_type',
				'posts',
				'posts_per_archive_page',
				'posts_per_page',
				'preview',
				'robots',
				's',
				'search',
				'second',
				'sentence',
				'showposts',
				'static',
				'subpost',
				'subpost_id',
				'tag',
				'tag__and',
				'tag__in',
				'tag__not_in',
				'tag_id',
				'tag_slug__and',
				'tag_slug__in',
				'taxonomy',
				'tb',
				'term',
				'type',
				'w',
				'withcomments',
				'withoutcomments',
				'year',
				'wpbooking_service_type'
			);


			if (!$taxonomy_slug) {
				$taxonomy_slug = mb_strtolower($taxonomy_label);
                $taxonomy_slug = $this->convert_vi_to_en($taxonomy_slug);
				$taxonomy_slug = sanitize_title_with_dashes(stripslashes($taxonomy_slug));
			}

			// Error checking
			if (!$taxonomy_label || !$taxonomy_name) {
				$error = esc_html__('Please provide a taxonomy name', 'wpbooking');
			} elseif (strlen($taxonomy_name) >= 35) {
				$error = sprintf(esc_html__('Slug "%s" is too long (35 characters max). Shorten it, please.', 'wpbooking'), ($taxonomy_name));
			} elseif (in_array($taxonomy_name, $reserved_terms)) {
				$error = sprintf(esc_html__('Slug "%s" is not allowed because it is a reserved term. Change it, please.', 'wpbooking'), ($taxonomy_name));
			} else {
				
				$taxonomy_exists = taxonomy_exists($taxonomy_name);
				
				if ('add' === $action && $taxonomy_exists) {
					$error = sprintf(esc_html__('Slug "%s" is already in use. Change it, please.', 'wpbooking'), sanitize_title($taxonomy_name));
				}
			}
			if ($error) {
				$validate = FALSE;
				wpbooking_set_admin_message($error, 'error');
			}
			
			$validate = apply_filters('wpbooking_admin_save_taxonomy_validate', $validate);

			if (!$validate) return;

			$all = $this->get_taxonomies();
			if (!is_array($all)) $all = array();
			$all[$taxonomy_name] = array(
				'label'        => $taxonomy_label,
				'name'         => $taxonomy_name,
				'hierarchical' => 1,
				'service_type' => WPBooking_Input::post('taxonomy_service_type'),
				'slug'         => $taxonomy_slug
			);
			update_option($this->_option_name, $all);
			flush_rewrite_rules();
			if ($action == 'add') {
				wpbooking_set_admin_message(sprintf('<p>%s</p>',esc_html__('Create Successfully','wpbooking')), 'success');
			} else {
                wpbooking_set_admin_message(sprintf('<p>%s</p>',esc_html__('Save Successfully','wpbooking')), 'success');
			}
		}

		function _show_taxonomy_page()
		{
			$tax = $this->get_taxonomies();
			if (WPBooking_Input::get('action') == 'wpbooking_edit_taxonomy') {
				$single = WPBooking_Input::get('taxonomy_name');
				echo ($this->admin_load_view('taxonomy/edit', array('row' => $tax[$single])));

				return;
			}


			echo ($this->admin_load_view('taxonomy/index', array(
				'rows'     => $tax,
				'page_url' => $this->get_page_url()
			)));
		}

		function get_taxonomies()
		{
			return get_option($this->_option_name);
		}

		function _add_taxonomy_page()
		{
			$menu_page = $this->get_menu_page();
			add_submenu_page(
				$menu_page['parent_slug'],
				$menu_page['page_title'],
				$menu_page['menu_title'],
				$menu_page['capability'],
				$menu_page['menu_slug'],
				$menu_page['function']
			);

		}

		function get_menu_page()
		{
			$menu_page = WPBooking()->get_menu_page();
			$page = array(
				'parent_slug' => $menu_page['menu_slug'],
				'page_title'  => esc_html__('Taxonomies', 'wpbooking'),
				'menu_title'  => esc_html__('Taxonomies', 'wpbooking'),
				'capability'  => 'manage_options',
				'menu_slug'   => 'wpbooking_page_taxonomy',
				'function'    => array($this, '_show_taxonomy_page')
			);

			return apply_filters('wpbooking_setting_menu_args', $page);
		}

		function get_page_url()
		{
			$menu_page = $this->get_menu_page();

			return esc_url(add_query_arg(
				array(
					'page' => $menu_page['menu_slug']
				)
				, admin_url('admin.php')
			));
		}
		function set_tax_service_type(){

		}

        /**
         * Get all tax by service type
         *
         * @since 1.0
         * @author dungdt
         *
         * @param string|bool $service_type
         * @return array
         */
		function get_tax_service_type($service_type=false)
		{
            $all=$this->get_taxonomies();
            if(!empty($all) and $service_type){
                $res=array();

                foreach($all as $key=>$v){
                    if(!empty($v['service_type']) and in_array($service_type,$v['service_type'])){
                        $res[]=$v;
                    }
                }

                return $res;
            }
		}

        /**
         * Convert Title to EN
         *
         * @since 1.0
         * @author dungdt
         *
         * @param $str
         * @return mixed
         */
        function convert_vi_to_en($str) {
            $str = preg_replace( '/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/' , 'a' , $str );
            $str = preg_replace( '/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/' , 'e' , $str );
            $str = preg_replace( '/(ì|í|ị|ỉ|ĩ)/' , 'i' , $str );
            $str = preg_replace( '/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/' , 'o' , $str );
            $str = preg_replace( '/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/' , 'u' , $str );
            $str = preg_replace( '/(ỳ|ý|ỵ|ỷ|ỹ)/' , 'y' , $str );
            $str = preg_replace( '/(đ)/' , 'd' , $str );
            $str = preg_replace( '/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/' , 'A' , $str );
            $str = preg_replace( '/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/' , 'E' , $str );
            $str = preg_replace( '/(Ì|Í|Ị|Ỉ|Ĩ)/' , 'I' , $str );
            $str = preg_replace( '/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/' , 'O' , $str );
            $str = preg_replace( '/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/' , 'U' , $str );
            $str = preg_replace( '/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/' , 'Y' , $str );
            $str = preg_replace( '/(Đ)/' , 'D' , $str );

            $str = str_replace( ' ' , '-' , $str ); // Replaces all spaces with hyphens.
            $str = preg_replace( '/[^A-Za-z0-9\-]/' , '' , $str ); // Removes special chars.

            $str = preg_replace( '/-+/' , '-' , $str ); // Replaces multiple hyphens with single one.

            return $str;
        }
		static function inst()
		{
			if (!self::$_inst) {
				self::$_inst = new self();
			}

			return self::$_inst;
		}


	}

	WPBooking_Admin_Taxonomy_Controller::inst();
}