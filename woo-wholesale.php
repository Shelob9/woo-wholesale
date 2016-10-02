<?php

/**
 Plugin Name: Wholesale orders for WooCommerce
 */


/**
 * Class JoshWooWholesale
 *
 * The smelly god class
 */
class JoshWooWholesale {

	/**
	 * @var \josh\ww\settings
	 */
	protected static $settings;

	protected static $slug = 'jww';


	protected static $admin_role = 'manage_options';

	/**
	 * @var \josh\ww\role\roles
	 */
	protected static $roles;

	public static function admin(){
		if( is_admin() && ( ! defined( 'DOING_AJAX') || false == DOING_AJAX ) ){
			self::setup_roles();
			$page = new \josh\ww\admin\page( self::get_settings(), self::$roles, self::view_dir() );
			$menu = new \josh\ww\admin\menu( $page );
			$menu->init();

		}

	}

	public static  function autoloader(){
		spl_autoload_register(function ($class) {
			$prefix = 'josh\\ww\\';
			$base_dir = __DIR__ . '/classes/';
			$len = strlen($prefix);
			if (strncmp($prefix, $class, $len) !== 0) {
				return;
			}
			$relative_class = substr($class, $len);
			$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
			if (file_exists($file)) {
				require $file;
			}

		});
	}

	public static function rewrite(){
		add_rewrite_rule('^wholesale', 'index.php');
		add_rewrite_tag('%wholesale%', '([^&]+)');

	}

	public static function the_content( $content ){
		if( self::is_page() ){
			$view = new \josh\ww\order\form( self::view_dir(), self::collect(), new \josh\ww\user( self::get_settings() ) );
			$form = $view->display();
			if( ! empty( $form ) ){
				$content = $form;
			}

		}

		return $content;
	}

	public static function route(){
		if( self::is_page() ){
			add_filter( 'the_content', [ 'JoshWooWholesale', 'the_content' ] );

		}

		if( is_checkout() ){
			self::setup_cart();
		}



	}

	public static function is_page(){
		return isset( $_GET[ 'wholesale' ] );
	}

	protected static function collect(){
		$args = apply_filters( 'jww_product_query_args', [] );
		$collection =  new \josh\ww\data\collection( $args );
		return $collection;
	}

	public static function route_add_to_cart(){
		if( isset( $_POST[ 'jww-add-cart' ] ) && ( current_user_can( 'jww_wholesale' ) || current_user_can( 'manage_options' ) ) ){
			if( \josh\ww\order\validate::check_nonce( $_POST[ 'jww-add-cart' ] ) ){
				if( isset( $_POST[ 'jww-amount' ], $_POST[ 'jww-product' ] ) && is_array( $_POST[ 'jww-amount' ] ) && is_array(  $_POST[ 'jww-product' ] )  ){
					$products = \josh\ww\order\validate::collect(  $_POST[ 'jww-amount' ], $_POST[ 'jww-product' ] );
					if( ! empty( $products ) && is_array( $products ) ){
						new \josh\ww\order\process( $products, self::generate_user()   );
					}
				}
			}

		}
	}

	protected static function generate_user(){
		return new \josh\ww\user( self::get_settings() );
	}


	public static function admin_save(){
		if( isset( $_POST[ 'jww-admin-save' ] ) ){
			if ( ! current_user_can( self::$admin_role ) || ! wp_verify_nonce( $_POST[ 'jww-admin-save' ], 'jww-admin-form' )  ){
				wp_die( __( 'You do not have permission for this request', 'jww' ) );
			}
			$error = 1;
			$message = __( 'Settings Could Not Be Saved', 'jww' );

			$data = \josh\ww\admin\validate::collect( $_POST[ 'discount' ], $_POST[ 'batch_size' ] );
			if( ! empty($data ) ) {
				$save = new \josh\ww\admin\save( self::get_settings(), $data, self::$slug );
				if ( $save->save() ) {
					$error   = 0;
					$message = __( 'Settings Saved', 'jww' );
				}

			}

			$url = add_query_arg( [
				'page' => self::$slug,
				'post_type' => 'product',
				'message' => urlencode_deep( $message ),
				'error' => $error
				], admin_url( 'edit.php' ) );

			wp_redirect( $url );
			exit;


		}
	}


	public static function get_settings(){
		if( empty( self::$settings ) ){
			self::settings_init();
		}

		return self::$settings;
	}

	protected static function setup_cart(){

		$discount = \josh\ww\session::get_discount();
		$products = \josh\ww\session::get_products();
		if( ! empty( $products ) && 0 < intval( $discount ) ){
			new \josh\ww\order\filter( $products, self::generate_user() );
		}

	}

	public static function checkout_discounts( $cart_object ) {
		if ( current_user_can( 'jww_wholesale' ) || current_user_can( 'manage_options' ) ) {
			$discount = \josh\ww\session::get_discount();
			foreach ( $cart_object->cart_contents as $key => $value ) {
				$value['data']->price = $value['data']->price * ( 100 - $discount ) / 100;
			}
			add_action( 'wp_enqueue_scripts', [ 'JoshWooWholesale', 'dequeue_woocommerce_cart_fragments' ], 11 );
		}
	}
	public static function dequeue_woocommerce_cart_fragments() {
		wp_dequeue_script('wc-cart-fragments');
	}

	public static function setup_roles(){
		if( null !== self::$roles ){
			return;
		}
		global $wp_roles;
		self::$roles = new \josh\ww\role\roles( $wp_roles );

		$creator = new \josh\ww\role\create( $wp_roles  );
		$creator->init( self::$roles );

	}

	protected static function settings_init(){
		$_saved = get_option( self::$slug );
		if( is_object( $saved = json_decode( $_saved ))) {
			if( ! isset( $saved->discounts ) ){
				$saved->discounts = [];
			}

			if( ! isset( $saved->batch_size ) ){
				$saved->batch_size = [];

			}

			$saved->discounts = (array) $saved->discounts;
			$saved->batch_size = (array) $saved->batch_size;

			self::$settings = new \josh\ww\settings( self::$roles );
			foreach ( self::$roles->get_roles() as $role ){
				if( isset( $saved->discounts[ $role->name ] ) ){
					self::$settings->set_discount( $role->name, $saved->discounts[ $role->name ] );

				}

				if( isset( $saved->batch_size[ $role->name ] ) ){
					self::$settings->set_batch_size( $role->name, $saved->batch_size[ $role->name ] );

				}

			}

		}else{
			self::$settings = new \josh\ww\settings( self::$roles );
		}

		self::$roles->add_from_settings( self::$settings );

	}

	public static function view_dir(){
		return __DIR__ . '/views';
	}




}


/**
 * Make plugin go if WP_Session class exists
 *
 * @see https://github.com/ericmann/wp-session-manager
 *
 * @uses "plugins_loaded"
 */
function joshwoowholesale_init(){
	if (  class_exists( 'WP_Session' ) ) {
		add_action( 'init', [ 'JoshWooWholesale', 'setup_roles' ], 5 );
		add_action( 'init', [ 'JoshWooWholesale', 'route_add_to_cart' ] );

		add_action( 'template_redirect', [ 'JoshWooWholesale', 'route' ] );
		add_action( 'init', [ 'JoshWooWholesale', 'rewrite' ] );
		add_action( 'plugins_loaded', [ 'JoshWooWholesale', 'autoloader' ] );

		add_action( 'admin_init', [ 'JoshWooWholesale', 'admin_save' ] );
		add_action( 'init', [ 'JoshWooWholesale', 'admin' ], 3 );

		add_action( 'woocommerce_before_calculate_totals', [ 'JoshWooWholesale', 'checkout_discounts' ], 1 );
	}

}

add_action( 'plugins_loaded',  'joshwoowholesale_init', 0 );

