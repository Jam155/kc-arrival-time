<?php
/*
 *
 * Plugin Name: Booking Arrival Time
 *
 */


class KC_Arrival_Time {

	function __construct() {

		$this->add_admin_dependencies();
		$this->add_actions();
	}

	private function add_admin_dependencies() {


		//add_action( 'admin_enqueue_scripts', array( __CLASS__, 'add_scripts_and_styles') );
		//add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_scripts_and_styles') );

	}

	public static function add_scripts_and_styles( $hook ) {

		global $post;
		if ( $_GET['page'] !== 'create_booking' && $post->post_type !== 'shop_order' && !is_checkout() ) {
			return;
		}

		wp_enqueue_script( 'timepicker', plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.min.js', array('jquery'), '',  true);
		wp_enqueue_style('timepicker', plugin_dir_url( __FILE__ ) . 'css/timepicker.css' );

		if(is_admin())
			wp_enqueue_script( 'arrivaltime-main', plugin_dir_url( __FILE__ ) . 'js/arrivaltime-main.js', array('jquery', 'timepicker'), '',  true);


	}

	private function add_actions() {

		add_action('woocommerce_bookings_create_booking_page_add_order_item', array( __CLASS__, 'kc_save_arrival_time'));
		add_action('add_meta_boxes', array(__CLASS__, 'kc_arrival_time_metabox'), 100);
		add_action( 'save_post', array(__CLASS__, 'kc_arrival_time_metabox_save') );

		add_action( 'woocommerce_checkout_before_customer_details', array(__CLASS__, 'kc_add_arrival_time_field'), 20 );
		add_action( 'woocommerce_checkout_update_order_meta',  array(__CLASS__, 'kc_arrival_time_metabox_save') );

	}


	public function kc_save_arrival_time($order_id) {

		if(isset($_POST['arrival-time']) && $_POST['arrival-time'] !== '') {

			update_post_meta($order_id, 'arrival_time', $_POST['arrival-time']);

		}

	}


	public function kc_arrival_time_metabox() {
		add_meta_box('kc-arrival-time-metabox', 'Arrival Time', array( __CLASS__, 'kc_arrival_time_metabox_callback') ,'shop_order', 'side');
	}

	public function kc_arrival_time_metabox_callback() {

		$arrival_time = get_post_meta(get_the_ID(), 'arrival_time', true);
		?>
		<div>
			<label>Arrival Time:</label><br><br>
			<input type="text" name="arrival-time" value="<?php echo $arrival_time ? $arrival_time : ''; ?>" />
		</div>
		<?php
	}


	public function kc_arrival_time_metabox_save($post_id) {
		if ( ! get_post_type( $post_id ) == 'shop_order' ) {
			return;
		}

		//Check save status
		$is_autosave    = wp_is_post_autosave( $post_id );
		$is_revision    = wp_is_post_revision( $post_id );

		//Exit script depending on save status
		if ( $is_autosave || $is_revision  ) {
			return;
		}

		if(isset($_POST['arrival-time']) && $_POST['arrival-time'] != '') {

			update_post_meta($post_id, 'arrival_time', $_POST['arrival-time']);

		}
	}

	public function kc_add_arrival_time_field() {

		include (plugin_dir_path(__FILE__) . '/html/frontend-form.php');

	}
	

}

$KC_Arrival_Time = new KC_Arrival_Time();