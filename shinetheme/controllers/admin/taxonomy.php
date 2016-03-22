<?php
/**
 * Created by PhpStorm.
 * User: Dungdt
 * Date: 3/15/2016
 * Time: 2:47 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (!class_exists('Traveler_Admin_Taxonomy_Controller')) {
	class Traveler_Admin_Taxonomy_Controller extends Traveler_Controller
	{

		static $_inst;

		protected $_option_name = 'traveler_taxonomies';

		function __construct()
		{
			parent::__construct();

			if (Traveler_Input::post('traveler_create_taxonomy')) {
				add_action('init', array($this, '_traveler_create_taxonomy'));
			}
			if (Traveler_Input::get('action') == 'traveler_delete_taxonomy') {
				add_action('init', array($this, '_delete_taxonomy'));
			}
			add_action('admin_menu', array($this, '_add_taxonomy_page'));

			add_action('init', array($this, '_register_taxonomy'));
		}

		function _register_taxonomy()
		{
			$all = $this->get_taxonomies();
			if (!empty($all) and is_array($all)) {
				foreach ($all as $key => $value) {
					$labels = array(
						'name' => $value['label'],
					);

					$args = array(
						'hierarchical'       => TRUE,
						'labels'             => $labels,
						'show_ui'            => TRUE,
						'show_tagcloud' 	 => FALSE,
						'show_admin_column'  => FALSE,
						'query_var'          => TRUE,
						'rewrite'            => array('slug' => $value['slug']),
					);

					register_taxonomy($value['name'], array('traveler_service'), $args);

					$hide=apply_filters('traveler_hide_taxonomy_select_box',true);
					$hide=apply_filters('traveler_hide_taxonomy_select_box_'.$value['name'],$hide);
					if($hide)
					Traveler_Assets::add_css("#".$value['name'].'div{display:none!important}');
				}
			}

		}

		function _delete_taxonomy()
		{
			if ($tax_name = Traveler_Input::get('tax_name')) {
				$all = $this->get_taxonomies();
				unset($all[$tax_name]);
				update_option($this->_option_name, $all);
				traveler_set_admin_message(__('Delete success', 'traveler-booking'), 'success');

			} else {
				traveler_set_admin_message(__('Please select an Taxonomy', 'traveler-booking'), 'error');
			}
			wp_redirect($this->get_page_url());
			die;
		}
		
		function _traveler_create_taxonomy()
		{
			$error = FALSE;
			$validate = TRUE;

			check_admin_referer('traveler_create_taxonomy');
			$taxonomy_label = stripslashes(Traveler_Input::post('taxonomy_label'));
			$taxonomy_slug = stripslashes(Traveler_Input::post('taxonomy_slug'));// for rewrite url

			$action = Traveler_Input::post('taxonomy_name') ? 'edit' : 'add';

			if ($action == 'add') {
				$taxonomy_name = mb_strtolower($taxonomy_label);
				$taxonomy_name = sanitize_title_with_dashes(stripslashes($taxonomy_name));
				$taxonomy_name = 'traveler_' . str_replace('-', '_', $taxonomy_name);
			} else {
				$taxonomy_name = Traveler_Input::post('taxonomy_name');
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
				'traveler_service_type'
			);


			if (!$taxonomy_slug) {
				$taxonomy_slug = mb_strtolower($taxonomy_label);
				$taxonomy_slug = sanitize_title_with_dashes(stripslashes($taxonomy_slug));
			}

			// Error checking
			if (!$taxonomy_label || !$taxonomy_name) {
				$error = __('Please, provide an taxonomy name .', 'traveler-booking');
			} elseif (strlen($taxonomy_name) >= 28) {
				$error = sprintf(__('Slug "%s" is too long (28 characters max). Shorten it, please.', 'traveler-booking'), ($taxonomy_name));
			} elseif (in_array($taxonomy_name, $reserved_terms)) {
				$error = sprintf(__('Slug "%s" is not allowed because it is a reserved term. Change it, please.', 'traveler-booking'), ($taxonomy_name));
			} else {
				
				$taxonomy_exists = taxonomy_exists($taxonomy_name);
				
				if ('add' === $action && $taxonomy_exists) {
					$error = sprintf(__('Slug "%s" is already in use. Change it, please.', 'traveler-booking'), sanitize_title($taxonomy_name));
				}
			}
			if ($error) {
				$validate = FALSE;
				traveler_set_admin_message($error, 'error');
			}
			
			$validate = apply_filters('traveler_admin_save_taxonomy_validate', $validate);

			if (!$validate) return;

			$all = $this->get_taxonomies();
			if (!is_array($all)) $all = array();
			$all[$taxonomy_name] = array(
				'label'        => $taxonomy_label,
				'name'         => $taxonomy_name,
				'hierarchical' => 1,
				'service_type' => Traveler_Input::post('taxonomy_service_type'),
				'slug'         => $taxonomy_slug
			);
			update_option($this->_option_name, $all);
			flush_rewrite_rules();
			if ($action == 'add') {
				traveler_set_admin_message('Create Success', 'success');
			} else {
				traveler_set_admin_message('Saved Success', 'success');
			}
		}

		function _show_taxonomy_page()
		{
			$tax = $this->get_taxonomies();
			if (Traveler_Input::get('action') == 'traveler_edit_taxonomy') {
				$single = Traveler_Input::get('taxonomy_name');
				echo $this->admin_load_view('taxonomy/edit', array('row' => $tax[$single]));

				return;
			}


			echo $this->admin_load_view('taxonomy/index', array(
				'rows'     => $tax,
				'page_url' => $this->get_page_url()
			));
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
			$menu_page = Traveler()->get_menu_page();
			$page = array(
				'parent_slug' => $menu_page['menu_slug'],
				'page_title'  => __('Taxonomies', 'traveler-booking'),
				'menu_title'  => __('Taxonomies', 'traveler-booking'),
				'capability'  => 'manage_options',
				'menu_slug'   => 'traveler_booking_page_taxonomy',
				'function'    => array($this, '_show_taxonomy_page')
			);

			return apply_filters('traveler_setting_menu_args', $page);
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

		static function inst()
		{
			if (!self::$_inst) {
				self::$_inst = new self();
			}

			return self::$_inst;
		}


	}

	Traveler_Admin_Taxonomy_Controller::inst();
}