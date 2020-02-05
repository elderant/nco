<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

add_action( 'plugins_loaded', function () {
	NcoClaimPasswordPages::get_instance();
} );

class NcoClaimPasswordPages {

	// class instance
	static $instance;

	// Pasword WP_List_Table object
	public $password_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
    // add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
    add_action( 'admin_menu', [ $this, 'plugin_submenu' ] );
  }
  
  public static function set_screen( $status, $option, $value ) {
    return $value;
  }
  
  public function plugin_menu() {
    // $hook = add_menu_page(
    //   __('Contraseñas','nco'),
    //   __('Contraseñas','nco'),
    //   'manage_options',
    //   'nco_shop_pass',
    //   [ $this, 'plugin_settings_page' ]
    // );
  
    // add_action( "load-$hook", [ $this, 'screen_option' ] );
  }

  public function plugin_submenu() {
    $hook = add_submenu_page(
      'edit.php?post_type=nco_active_code',
      __('Contraseñas','nco'),
      __('Contraseñas','nco'),
      'manage_options',
      'nco_claim_password',
      [ $this, 'plugin_settings_page' ]
    );

    add_action( "load-$hook", [ $this, 'screen_option' ] );

    $add_hook = add_submenu_page(
      'edit.php?post_type=nco_active_code',
      __('Agregar contraseña','nco'),
      __('Agregar contraseña','nco'),
      'manage_options',
      'nco_add_claim_password',
      [ $this, 'plugin_create_page' ]
    );
  }

  /**
  * Screen options
  */
  public function screen_option() {

    $option = 'per_page';
    $args   = [
      'label'   => 'Pass',
      'default' => 5,
      'option'  => 'passwords_per_page'
    ];

    add_screen_option( $option, $args );

    $this->password_obj = new NcoPasswordList();
  }

  /**
  * Plugin settings page
  */
  public function plugin_settings_page() {
    global $wp_query;
    $wp_query -> query_vars['nco_admin_args']['password_obj'] = $this->password_obj;
    
    $template_url = nco_load_template('password-list.php', 'admin');
    load_template($template_url, true);
  }

  /**
   * Plugin settings page
   */
  public function plugin_create_page() {
    global $wp_query;
    $wp_query -> query_vars['nco_admin_args']['name'] = null;

    $template_url = nco_load_template('password-create.php', 'admin');
    load_template($template_url, true);
  }

  /** Singleton instance */
  public static function get_instance() {
    if ( ! isset( self::$instance ) ) {
      self::$instance = new self();
    }

    return self::$instance;
  }
}// End NcoPasswordAdmin Class


class NcoPasswordList extends WP_List_Table {

	/** Class constructor */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Contraseña', 'nco' ), //singular name of the listed records
			'plural'   => __( 'Contraseñas', 'nco' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );
  }
  
  /**
   * Retrieve shop code data from the database
   *
   * @param int $per_page
   * @param int $page_number
   *
   * @return mixed
   */
  public static function get_pass( $per_page = 5, $page_number = 1 ) {
    return get_option( 'claim_passwords', array() );
    
    // global $wpdb;

    // $sql = "SELECT * FROM {$wpdb->prefix}users";

    // if ( ! empty( $_REQUEST['orderby'] ) ) {
    //   $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
    //   $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
    // }

    // $sql .= " LIMIT $per_page";
    // $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
    // $result = $wpdb->get_results( $sql, 'ARRAY_A' );

    // return $result;
  }
  
  /**
   * Delete a customer record.
   *
   * @param int $id customer ID
   */
  public static function delete_pass( $name ) {
    $claim_passwords = get_option( 'claim_passwords', array() );
    
    foreach($claim_passwords as $index => $password ) {
      if(strcasecmp($password['name'], $name) == 0) {
        unset($claim_passwords[$index]);
        update_option( 'claim_passwords', $claim_passwords );
        break;
      }
    }
    // global $wpdb;

    // $wpdb->delete(
    //   "{$wpdb->prefix}users",
    //   [ 'ID' => $id ],
    //   [ '%d' ]
    // );
  }

  /**
   * Returns the count of records in the database.
   *
   * @return null|string
   */
  public static function record_count() {
    return count(get_option( 'claim_passwords', array() ));


    // global $wpdb;
    // $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}users";
    // return $wpdb->get_var( $sql );
  }

  /**
   * Method for name column
   *
   * @param array $item an array of DB data
   *
   * @return string
   */
  function column_name( $item ) {
    // create a nonce
    $delete_nonce = wp_create_nonce( 'nco_delete_password' );
    
    $title = '<strong>' . $item['name'] . '</strong>';

    $actions = [
      'delete' => sprintf( '<a href="' . get_admin_url() . 'edit.php?post_type=nco_active_code&page=nco_claim_password&action=%s&password=%s&_wpnonce=%s">Delete</a>', 
        'delete_password', 
        $item['name'], 
        $delete_nonce )
    ];

    return $title . $this->row_actions( $actions );
  }

  /**
   * Render a column when no column specific method exists.
   *
   * @param array $item
   * @param string $column_name
   *
   * @return mixed
   */
  public function column_default( $item, $column_name ) {
    switch ( $column_name ) {
      case 'name':
        return $item[ $column_name ];
      default:
        return print_r( $item, true ); //Show the whole array for troubleshooting purposes
    }
  }

  /**
   * Render the bulk edit checkbox
   *
   * @param array $item
   *
   * @return string
   */
  function column_cb( $item ) {
    return sprintf(
      '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['name']
    );
  }

  /**
   *  Associative array of columns
   *
   * @return array
   */
  function get_columns() {
    $columns = [
      'cb'      => '<input type="checkbox" />',
      'name'    => __( 'Nombre', 'nco' ),
    ];

    return $columns;
  }

  /**
   * Columns to make sortable.
   *
   * @return array
   */
  public function get_sortable_columns() {
    $sortable_columns = array(
      'name' => array( 'name', true ),
    );

    return $sortable_columns;
  }

  /**
   * Returns an associative array containing the bulk action
   *
   * @return array
   */
  public function get_bulk_actions() {
    $actions = [
      'bulk-delete' => 'Delete',
    ];

    return $actions;
  }

  /**
   * Handles data query and filter, sorting, and pagination.
   */
  public function prepare_items() {

    $this->_column_headers = $this->get_column_info();

    /** Process bulk action */
    $this->process_bulk_action();

    $per_page     = $this->get_items_per_page( 'passwords_per_page', 10 );
    $current_page = $this->get_pagenum();
    $total_items  = self::record_count();

    $this->set_pagination_args( [
      'total_items' => $total_items, //WE have to calculate the total number of items
      'per_page'    => $per_page //WE have to determine how many items to show on a page
    ] );

    $this->items = self::get_pass( $per_page, $current_page );
  }

  public function process_bulk_action() {
    //Detect when a bulk action is being triggered...
    if ( 'delete_password' === $this -> current_action() ) {
      // In our file that handles the request, verify the nonce.
      $nonce = esc_attr( $_REQUEST['_wpnonce'] );
  
      if ( ! wp_verify_nonce( $nonce, 'nco_delete_password' ) ) {
        die( 'Something went wrong please contact your web developer' );
      }
      else {
        self::delete_pass( $_GET['password'] );
  
        wp_redirect(get_admin_url(null, '/admin.php?page=nco_claim_password'));
        exit;
      }
  
    }
  
    // If the delete bulk action is triggered
    if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
         || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
    ) {
      
      $delete_ids = esc_sql( $_POST['bulk-delete'] );
      // loop over the array of record IDs and delete them
      foreach ( $delete_ids as $id ) {
        self::delete_pass( $id );
      }
      
      wp_redirect(get_admin_url(null, '/admin.php?page=nco_claim_password'));
      exit;
    }
  }

  protected function get_primary_column_name(){
    return 'name';
  }
}// end NcoPasswordList class

// shop code form handlers
add_action( 'admin_post_nopriv_update_claim_password', 'nco_update_password_handler' );
add_action( 'admin_post_update_claim_password', 'nco_update_password_handler' );
function nco_update_password_handler () {
  if ( isset( $_POST['action'] ) && strcasecmp($_POST['action'], 'update_claim_password') == 0 ) {
    $name = $_POST['name'];
    $claim_password_name = $_POST['claim_password_name'];
    $claim_password_hashed = password_hash($_POST['claim_password_base'], PASSWORD_DEFAULT);
    
    if (empty($name)) {
      $claim_passwords = get_option( 'claim_passwords', array() );
      array_push($claim_passwords, array('name' => $claim_password_name, 'password' => $claim_password_hashed));
      update_option( 'claim_passwords', $claim_passwords );
    }
    else {
      //TODO Edit
    }
    wp_redirect(get_admin_url(null, '/admin.php?page=nco_claim_password'));
  }
}