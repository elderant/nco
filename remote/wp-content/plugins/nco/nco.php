<?php
/*
Plugin Name: nco
Description: Functions and modifications to match site requirements
Version:     1.0
Author:      Sebastian Guerrero
*/

//include( plugin_dir_path( __FILE__ ) . 'includes/admin.php' );

// Script hooks.
add_action( 'wp_enqueue_scripts', 'nco_scripts' );
add_action( 'admin_enqueue_scripts', 'nco_admin_scripts' );
add_filter( 'wc_get_template', 'nco_wc_get_template', 11, 5 );

function nco_scripts () {
	wp_enqueue_script ( 'nco-js', plugins_url('/js/script.js', __FILE__), array('jquery'),  rand(111,9999), 'all' );
	wp_enqueue_style ( 'nco',  plugins_url('/css/main.css', __FILE__), array(),  rand(111,9999), 'all' );

	wp_localize_script( 'nco-js', 'ajax_params', array('ajax_url' => admin_url( 'admin-ajax.php' )));

	wp_enqueue_script('jquery-validate', 'https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js', array('jquery'), '1.10.0',	true);
}

function nco_admin_scripts () {
// 	wp_enqueue_script ( 'nco-js-admin', plugins_url('/js/admin.js', __FILE__), array('jquery'),  rand(111,9999), 'all' );
 	wp_enqueue_style ( 'main-admin',  plugins_url('/css/admin.css', __FILE__), array(),  rand(111,9999), 'all' );

// 	wp_localize_script( 'nco-js-admin', 'ajax_params', array('ajax_url' => admin_url( 'admin-ajax.php' )));
}

/************************************************************/
/********************* Helper functions *********************/
/************************************************************/

function nco_load_template($template, $folder = '') {
	// first check if this is the page where you want to render your own template
	// if ( !is_page($the_page_you_want)) {
		// return $template;
	// }

	// get the actual file name, like single.php or page.php
	$filename = basename($template);
	if(!empty($folder) && strpos($folder, '/') !== 0) {
		$folder = '/' . $folder;
	}

	// build a path for the filename in a folder named for our plugin "fisherman" in the theme folder
	$custom_template = sprintf('%s/%s%s/%s', get_stylesheet_directory(), 'nco', $folder, $filename);

	// if the template is found, awesome! return it. that's what we'll use.
	if ( is_file($custom_template) ) {
		return $custom_template;
	}

	// otherwise, build a path for the filename in a folder named "templates" in our plugin folder
	$custom_template = nco_file_build_path(plugin_dir_path( __FILE__ ), 'templates', $folder, $filename);
	//$custom_template = sprintf('%stemplates%s/%s', plugin_dir_path( __FILE__ ), $folder, $filename);

	// found? return our plugin's default template
	if ( is_file($custom_template) ) {
		return $custom_template;
	}

	// otherwise, build a path for the filename in a folder named "templates" in our plugin folder
	$custom_template = sprintf('%stemplates/%s', plugin_dir_path( __FILE__ ), $filename);

	// found? return our plugin's default template
	if ( is_file($custom_template) ) {
		return $custom_template;
	}

	return $template;
}

function nco_file_build_path($plugin, $template_folder, $folder, $filename) {
  return $plugin . DIRECTORY_SEPARATOR .
          $template_folder . DIRECTORY_SEPARATOR .
          $folder . DIRECTORY_SEPARATOR .
          $filename;
}

function nco_wc_get_template( $located, $template_name, $args, $template_path, $default_path ) {
	$plugin_template_path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/woocommerce/' . $template_name;

	if ( file_exists( $plugin_template_path ) ) {
		$located = $plugin_template_path;
	}

	return $located;
}

/************************************************************/
/********************** NCO Insurance ***********************/
/************************************************************/

function nco_get_insurance_form_html () {
  $template_url = nco_load_template('activation.php', 'insurance');
	load_template($template_url, true);
}
add_shortcode( 'nco_insurance_activation_form', 'nco_get_insurance_form_html' );

// Define activation handlers
add_action( 'admin_post_nopriv_nco_insurance_activation', 'nco_insurance_activation_handler' );
add_action( 'admin_post_nco_insurance_activation', 'nco_insurance_activation_handler' );

function nco_insurance_activation_handler() {
	if ( isset( $_POST['action'] ) && strcasecmp($_POST['action'], 'nco_insurance_activation') == 0 ) {
		// retreive post values.
		$first_name = $_POST['insurance_first_name'];
		$last_name = $_POST['insurance_last_name'];
		$email = $_POST['insurance_email'];
		$shop = $_POST['insurance_shop'];
		$invoice = $_POST['insurance_invoice_number'];
		$device = $_POST['insurance_device'];
		$serial = $_POST['insurance_insurance_serial'];
		$code = $_POST['insurance_activation_code'];
		$code = sanitize_text_field( $code );
		
		// validate values.
		if(empty($first_name)) {
			$nco_activation_error['insurance_first_name'] = __('Este campo es requerido.', 'nco');
			$error = true;
		}
		if(empty($last_name)) {
			$nco_activation_error['insurance_last_name'] = __('Este campo es requerido.', 'nco');
			$error = true;
		}
		if(empty($email)) {
			$nco_activation_error['insurance_email'] = __('Este campo es requerido.', 'nco');
			$error = true;
		}
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$nco_activation_error['insurance_email'] = __('Ingrese un correo electrónico valido.', 'nco');
			$error = true;
		}
		if(empty($shop)) {
			$nco_activation_error['insurance_shop'] = __('Este campo es requerido.', 'nco');
			$error = true;
		}
		if(empty($invoice)) {
			$nco_activation_error['insurance_invoice_number'] = __('Este campo es requerido.', 'nco');
			$error = true;
		}
		if(empty($device)) {
			$nco_activation_error['insurance_device'] = __('Este campo es requerido.', 'nco');
			$error = true;
		}
		if(empty($serial)) {
			$nco_activation_error['insurance_insurance_serial'] = __('Este campo es requerido.', 'nco');
			$error = true;
		}
	
		$valid_code = nco_validate_insurance_code_activation( $code );
		$nco_activation_error = array();
		$error = false;
		if(!$valid_code) {
			$nco_activation_error['insurance_activation_code'] = __('El codigo de activacion escrito no pudo ser validado', 'nco');
			$error = true;
		}

		
		if($error) {
			set_transient( 'nco_activation_error', $nco_activation_error, MINUTE_IN_SECONDS );	
			wp_redirect('/nco-siempre-seguro/');
		}

		//sanitize values to add to database
		$first_name = sanitize_text_field($_POST['insurance_first_name']);
		$last_name = sanitize_text_field($_POST['insurance_last_name']);
		$shop = sanitize_text_field($_POST['insurance_shop']);
		$invoice = sanitize_text_field($_POST['insurance_invoice_number']);
		$device = sanitize_text_field($_POST['insurance_device']);
		$serial = sanitize_text_field($_POST['insurance_insurance_serial']);
		$email = sanitize_email($email);

		//create custom post
		$post = array(
			'post_type' => 'nco_active_code',
			'post_title' => $code,
			'post_status' => 'publish',
			'post_author' => 1,
			'post_slug' => $code,
		);
		$post_id = wp_insert_post($post);
		if ($post_id) {
			add_post_meta( $post_id, 'first_name', $first_name, true);
			add_post_meta( $post_id, 'last_name', $last_name, true);
			add_post_meta( $post_id, 'email', $email, true);
			add_post_meta( $post_id, 'shop', $shop, true);
			add_post_meta( $post_id, 'invoice', $invoice, true);
			add_post_meta( $post_id, 'device', $device, true);
			add_post_meta( $post_id, 'serial', $serial, true);
			add_post_meta( $post_id, 'claim count', 0, true);
		}
	}  

	error_log('code activation successful, returning true');
	set_transient( 'nco_activation_success', true, MINUTE_IN_SECONDS );	
	wp_redirect('/nco-siempre-seguro/');
}

function nco_validate_insurance_code_activation($code) {
  
  return true;
}

/*Custom Post type start*/
function nco_post_type_active_codes() {
	$supports = array(
		'title', // post title
		'editor', // post content
		'author', // post author
		'custom-fields', // custom fields
	);
	$labels = array(
		'name' => _x('Codigos Activos', 'plural'),
		'singular_name' => _x('Codigo activo', 'singular'),
		'menu_name' => _x('Codigos Activos', 'admin menu'),
		'name_admin_bar' => _x('codigos actios', 'admin bar'),
		'add_new' => _x('Agregar Codigo', 'add new'),
		'add_new_item' => __('Agregar nuevo codigo'),
		'new_item' => __('Nuevo codigo', 'nco'),
		'edit_item' => __('Editar codigo', 'nco'),
		'view_item' => __('Ver codigo', 'nco'),
		'all_items' => __('Todos los codigos', 'nco'),
		'search_items' => __('Buscar codigos', 'nco'),
		'not_found' => __('No codigos encontrados.', 'nco'),
	);
	$args = array(
		'supports' => $supports,
		'labels' => $labels,
		'public' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'nco_codes'),
		'has_archive' => true,
		'hierarchical' => false,
		'capabilities' => array(
			'create_posts' => 'do_not_allow',
		),
		'map_meta_cap' => true,
	);
	register_post_type('nco_active_code', $args);
}
add_action('init', 'nco_post_type_active_codes');



function nco_get_educacion_filter_html () {
	global $wp_query;

	// Category taxonomies query.
	// 
	$parent_ids = array(129, 138, 144);
	$args = array(
    'taxonomy' => 'portfolio-category',
		'hide_empty' => false,
		'include'	=> $parent_ids,
	);

	// The Term Query
	$term_query = new WP_Term_Query( $args );

	// foreach($term_query -> get_terms() as $term) {
	// 	$term_children = get_term_children( $term, $taxonomy );
	// 	if(count($term_children) > 0 ) {
	// 		$term -> child_terms = nco_get_children_taxonomies($term, $term_children);
	// 	}
	// }

	$wp_query -> query_vars['nco_args']['term_query'] = $term_query;
	$wp_query -> query_vars['nco_args']['term_id'] = '';
	$wp_query -> query_vars['nco_args']['category_args'] = array();

	// $template_url = nco_load_template('filter.php', 'educacion');
	// load_template($template_url, true);
}
	
//add_shortcode( 'nco_educacion_filter', 'nco_get_educacion_filter_html' );
