<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://a17lab.com
 * @since      1.0.0
 *
 * @package    Wpstickybar
 * @subpackage Wpstickybar/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpstickybar
 * @subpackage Wpstickybar/admin
 * @author     A17Lab <contact@a17lab.com>
 */
class Wpstickybar_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $plugin_settings_tabs = array();
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
			$this->plugin_settings_tabs['general'] = 'General';
			$this->plugin_settings_tabs['stats'] = 'Stats';
	}

	private $page_hooks = [];

	protected $api_url = 'https://wpstickybar.com/sendfeedback.php';

	protected $namespace = 'wpstickybar/v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = '/uninstall-feedback';

	/**
		* Register the Settings page.
		*
		* @since    1.0.0
		*/
	function wpstickybar_admin_menu()
	{
		$hook = add_menu_page('WPStickyBar', 'WP Sticky Bar', 'manage_options', 'wpstickybars', array($this, 'display_plugin_list_banner_page'), 'dashicons-admin-post', '25.8');
    $this->page_hooks[] = $hook; // Store the page hook
	}
	function wpstickybar_sub_menu(){
		$this->page_hooks[] = add_submenu_page('wpstickybars', 'Manage Banners', 'Manage Banners', 'manage_options', 'wpstickybars', array($this, 'display_plugin_list_banner_page'));
		$this->page_hooks[] = add_submenu_page('wpstickybars','Statistics','Statistics','manage_options','statistics-banner', array($this, 'display_plugin_admin_statistics_banner'));
		$this->page_hooks[] = add_submenu_page('wpstickybars','Feedback','Feedback','manage_options','feedback-banner', array($this, 'display_plugin_admin_feedback_banner'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpstickybar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpstickybar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if (in_array($hook, $this->page_hooks)) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpstickybar-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'stylesheet', WPSTICKYBAR_PLUGIN_URL . 'build/style.css', array(), $this->version, 'all' );		
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpstickybar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpstickybar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		 wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpstickybar-admin.js', array( 'jquery' ), $this->version, false );
		 wp_enqueue_script( $this->plugin_name.'-react', WPSTICKYBAR_PLUGIN_URL . 'build/index.js', array('wp-element'), $this->version, true );
	}

	/**
	 * Callback function for the admin settings page.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_list_banner_page(){	

		$view = '';
		if (isset($_GET['view'])) $view = sanitize_key($_GET['view']);
		if ($view == "addnew" || $view == 'edit') {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wpstickybar-admin-add-edit-banner.php';
		} else {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wpstickybar-admin-list-banner.php';
		}			
	}		

	public function display_plugin_admin_statistics_banner(){	
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wpstickybar-admin-statistics.php';
	}

	public function display_plugin_admin_feedback_banner(){	
		// wp_redirect( 'https://wpstickybar.com/contact' );
		// exit;
	}
	
	public function redirect_feedback_page() {
    if (isset($_GET['page']) && $_GET['page'] == 'feedback-banner') {
			wp_redirect('https://wpstickybar.com/contact');
			exit;
		}
	}	

	function wpstickybar_create_data(WP_REST_Request $request) {

		global $wpdb;
    $tableprefix = $wpdb->prefix;
    $stickybar_banner = $tableprefix . 'stickybar_banner';
    $stickybar_stats = $tableprefix . 'stickybar_stats';

    
    // $data now contains your form data.
    $data = $request->get_json_params();
		
		// Sanitize form data 
    $form_data = [];
    foreach ($data as $key => $value) {
        if ($key == 'promotionContent') {
            $form_data[$key] = wp_kses_post($value);
        } else {
            $form_data[$key] = sanitize_text_field($value);
        }
    }

		$enable_timmer = $form_data['isEnableCountdown'];
    // Save the data to the database using your own logic.

		$height_value = 'auto';  // default value
		if (isset($form_data['height']) && $form_data['height'] !== 'Auto' && !empty($form_data['height'])) {
				$height_value = $form_data['height'];
		}

		$optionsArray = array(
			
			'layout' => '100',
			'width' => '1024px',
			'height' => $height_value,
			'gradient_background' => $form_data['backgroundGradientColors'],
			'backgroundGradientColors' => $form_data['backgroundGradientColors'],
			'backgroundType' => $form_data['backgroundType'],
			'buttonColor' => $form_data['buttonColor'],
			'promotionContent' => $form_data['promotionContent'],
			'promotionColor' => $form_data['promotionColor'],
			'promotionFontSize' => $form_data['promotionFontSize'],
			'buttonText' => $form_data['buttonText'],
			'buttonUrl' => $form_data['buttonUrl'],			
			'backgroundColor' => $form_data['backgroundColor'],
			'clockColor' => $form_data['clockColor'],
			'clockNumberColor' => $form_data['clockNumberColor'],
			'clockBgColor' => $form_data['clockBgColor'],
			'isEnableCountdown' => $enable_timmer,
			'isEnableButton' => $form_data['isEnableButton'],
			'countdownType' => $form_data['countdownType'],
			'relativeDateDay' => $form_data['relativeDateDay'],
			'relativeDateHour' => $form_data['relativeDateHour'],
			'relativeDateMinute' => $form_data['relativeDateMinute'],
			'relativeDateSecond' => $form_data['relativeDateSecond'],
			'staticDateTime' => $form_data['staticDateTime'],
			'hideOnPages' => $form_data['hideOnPages'],
			'topPadding' => $form_data['topPadding'],
		);

		// Convert the options array to JSON
		$otherOptionsJSON = json_encode($optionsArray);

		$aryDataInsert = array(
			'title' => $form_data['name'],
			'promotion_text' => $form_data['promotionContent'],
			'button_text' => $form_data['buttonText'],
			'button_url' => $form_data['buttonUrl'],
			// 'background_img' => $background_img,
			'text_color' => $form_data['promotionColor'],
			'background_color' => $form_data['backgroundColor'],
			'text_time_color' => $form_data['clockColor'],
			'number_time_color' => $form_data['clockNumberColor'],
			'background_time_color' => $form_data['clockBgColor'],
			'enable_timmer' => $enable_timmer,
			'date_type' => $form_data['countdownType'],
			'relative_day' => $form_data['relativeDateDay'],
			'relative_hour' => $form_data['relativeDateHour'],					
			'relative_minutes' => $form_data['relativeDateMinute'],
			'relative_seconds' => $form_data['relativeDateSecond'],
			// 'banner_position' => $form_data['position-settings'],
			'static_date' => $form_data['staticDateTime'],
			'display_banner' => $form_data['display_banner'],
			'on_mac' => 1,
			'on_mobile' => 1,
			'on_page' => 1,
			'on_post' => 1,
			'other_options' => $otherOptionsJSON // Save the background gradient options as JSON
		);

		// Check if 'created' column exists
    $row = $wpdb->get_row("SELECT * FROM $stickybar_banner LIMIT 1");
		if (isset($row->created)) {
			$aryDataInsert['created'] = current_time('Y-m-d H:i:s');
			$aryDataInsert['updated'] = current_time('Y-m-d H:i:s');
		}
		
		// disable all banners with the same banner_position
		$wpdb->update(
			$stickybar_banner,
			array('display_banner' => false),
			array('display_banner' => $form_data['display_banner'])
		);

		    // insert banner data into database
		$wpdb->insert(
			$stickybar_banner,
			$aryDataInsert
		);
	
		$banner_id = $wpdb->insert_id;

		// insert banner stats into database
		$wpdb->insert(
				$stickybar_stats,
				array(
						'banner_id' => $banner_id,
						'the_time' => current_time('mysql', true)
				)
		);

    // Return a response.
    return new WP_REST_Response($form_data, 200);
	}

	function wpstickybar_update_data(WP_REST_Request $request) {
    global $wpdb;
    $tableprefix = $wpdb->prefix;
    $stickybar_banner = $tableprefix . 'stickybar_banner';

    // $data now contains your form data.
    $data = $request->get_json_params();
    $id = $request['id'];
    
    // The rest of your sanitization and update logic goes here.

    // Get the form data.
    $data = $request->get_json_params();

    // Validate and sanitize the banner ID.
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        return new WP_Error('invalid_banner_id', 'Invalid banner ID.', array('status' => 400));
    }
    $banner_id = (int) $data['id'];

    // Sanitize form data.
    $form_data = [];
    foreach ($data as $key => $value) {
        if ($key == 'promotionContent') {
            $form_data[$key] = wp_kses_post($value);
        } else {
            $form_data[$key] = sanitize_text_field($value);
        }
    }

		$height_value = 'auto';  // default value
		if (isset($form_data['height']) && $form_data['height'] !== 'Auto' && !empty($form_data['height'])) {
				$height_value = $form_data['height'];
		}

		$enable_timmer = $form_data['isEnableCountdown'];
		
    // Prepare your options array here as you did before.
    $optionsArray = array(
			'layout' => '100',
			'width' => '1024px',
			'height' => $height_value,
			'gradient_background' => $form_data['backgroundGradientColors'],
			'backgroundGradientColors' => $form_data['backgroundGradientColors'],
			'backgroundType' => $form_data['backgroundType'],
			'buttonColor' => $form_data['buttonColor'],
			'promotionContent' => $form_data['promotionContent'],
			'promotionColor' => $form_data['promotionColor'],
			'promotionFontSize' => $form_data['promotionFontSize'],
			'buttonText' => $form_data['buttonText'],
			'buttonUrl' => $form_data['buttonUrl'],
			'backgroundColor' => $form_data['backgroundColor'],
			'clockColor' => $form_data['clockColor'],
			'clockNumberColor' => $form_data['clockNumberColor'],
			'clockBgColor' => $form_data['clockBgColor'],
			'isEnableCountdown' => $enable_timmer,
			'isEnableButton' => $form_data['isEnableButton'],
			'countdownType' => $form_data['countdownType'],
			'relativeDateDay' => $form_data['relativeDateDay'],
			'relativeDateHour' => $form_data['relativeDateHour'],
			'relativeDateMinute' => $form_data['relativeDateMinute'],
			'relativeDateSecond' => $form_data['relativeDateSecond'],
			'staticDateTime' => $form_data['staticDateTime'],
			'hideOnPages' => $form_data['hideOnPages'],
			'topPadding' => $form_data['topPadding'],
		);

    // Convert the options array to JSON.
    $otherOptionsJSON = json_encode($optionsArray);

		$aryDataUpdate = array(
			// your data to be updated goes here
			'other_options' => $otherOptionsJSON,
			'title' => $form_data['name'],
			'display_banner' => $form_data['display_banner'],
			'static_date' => $form_data['staticDateTime'],
			'text_color' => $form_data['promotionColor'],
			'background_color' => $form_data['backgroundColor'],
			'text_time_color' => $form_data['clockColor'],
			'number_time_color' => $form_data['clockNumberColor'],
			'background_time_color' => $form_data['clockBgColor'],
			'enable_timmer' => $enable_timmer,
			'date_type' => $form_data['countdownType'],
			'relative_day' => $form_data['relativeDateDay'],
			'relative_hour' => $form_data['relativeDateHour'],					
			'relative_minutes' => $form_data['relativeDateMinute'],
			'relative_seconds' => $form_data['relativeDateSecond'],
			'promotion_text' => $form_data['promotionContent'],
			'button_text' => $form_data['buttonText'],
			'button_url' => $form_data['buttonUrl'],
		);

		// Check if 'created' column exists
    $row = $wpdb->get_row("SELECT * FROM $stickybar_banner LIMIT 1");
		if (isset($row->created)) {
			$aryDataUpdate['updated'] = current_time('Y-m-d H:i:s');
		}

		$wpdb->update(
			$stickybar_banner,
			array('display_banner' => false),
			array('display_banner' => $form_data['display_banner'])
		);

    // Now update the data in the database.
    $update_result = $wpdb->update(
        $stickybar_banner,
        $aryDataUpdate,
        array('banner_id' => $banner_id)  // Where clause.
    );

    if (false === $update_result) {
        // There was an error.
				// $last_error = $wpdb->last_error;
				// var_dump($last_error);
        return new WP_REST_Response('Update failed.', 500);
    } else {
        // No error, return a success response.
        return new WP_REST_Response('Banner updated successfully.', 200);
    }
	}

	function wpstickybar_get_data(WP_REST_Request $request) {
    global $wpdb;
    $tableprefix = $wpdb->prefix;
    $stickybar_banner = $tableprefix . 'stickybar_banner';

    // Assuming the banner id is passed in request
    $banner_id = $request->get_param('id');
    $banner_id = sanitize_text_field($banner_id);

    $banner = $wpdb->get_row( "SELECT * FROM $stickybar_banner WHERE banner_id = $banner_id" );

    // Check if banner exists
    if(!$banner) {
        return new WP_REST_Response(array('error' => 'Banner not found'), 404);
    }

    // Decode the other_options from JSON
    $otherOptionsArray = json_decode($banner->other_options, true);

    // Add 'display_banner' to the response data
    $otherOptionsArray['display_banner'] = $banner->display_banner;
		$otherOptionsArray['name'] = $banner->title;
		$otherOptionsArray['id'] = $banner->banner_id;
		$otherOptionsArray['created'] = $banner->updated;
		$otherOptionsArray['updated'] = $banner->updated;

    // Return the data
    return new WP_REST_Response($otherOptionsArray, 200);
	}

	
	function wpstickybar_delete_data(WP_REST_Request $request) {

    global $wpdb;
    $tableprefix = $wpdb->prefix;
    $stickybar_banner = $tableprefix . 'stickybar_banner';

    // Sanitize and validate the ID.
    $id = absint( $request->get_param('id') );

    if ( empty( $id ) ) {
        return new WP_Error( 'invalid_banner_id', 'Invalid banner ID', array( 'status' => 400 ) );
    }

    // Delete the banner
    $result = $wpdb->delete( $stickybar_banner, array( 'banner_id' => $id ) );

    if ( false === $result ) {
        return new WP_Error( 'db_delete_error', 'Could not delete the banner.', array( 'status' => 500 ) );
    } elseif ( 0 === $result ) {
        return new WP_Error( 'no_banner_found', 'No banner found with the provided ID.', array( 'status' => 404 ) );
    }

    // If everything went well, return the ID of the banner that was deleted.
    return new WP_REST_Response( $id, 200 );
	}

	function wpstickybar_list_banners(WP_REST_Request $request) {
    global $wpdb;
    $tableprefix = $wpdb->prefix;
    $stickybar_banner = $tableprefix . 'stickybar_banner';

    $banners = $wpdb->get_results("SELECT * FROM $stickybar_banner");

    $response_data = [];
    foreach ($banners as $banner) {
        $otherOptionsArray = json_decode($banner->other_options, true);
        $otherOptionsArray['display_banner'] = $banner->display_banner;
        $otherOptionsArray['name'] = $banner->title;
        $otherOptionsArray['id'] = $banner->banner_id;
        $otherOptionsArray['created'] = $banner->updated;
        $otherOptionsArray['updated'] = $banner->updated;
        $response_data[] = $otherOptionsArray;
    }

    return new WP_REST_Response([
			"data" => $response_data,
			"total" => count($response_data)
		], 200);
		
	}


	function wpstickybar_register_rest_route() {		

		register_rest_route('wpstickybar/v1', 'banner/get/(?P<id>\d+)', array(
			'methods' => 'GET',
			'callback' => array( $this, 'wpstickybar_get_data' ),
			'args' => array(
					'id' => array(
							'validate_callback' => function($param, $request, $key) {
									return is_numeric($param);
							}
					),
			),
		));

		register_rest_route('wpstickybar/v1', 'banner/update/(?P<id>\d+)', array(
			'methods' => 'POST',
			'callback' => array( $this, 'wpstickybar_update_data' ),
			'args' => array(
					'id' => array(
							'validate_callback' => function($param, $request, $key) {
									return is_numeric( $param );
							}
					),
			),
		));

    register_rest_route('wpstickybar/v1', 'banner/create', array(
        'methods' => 'POST',
        'callback' => array( $this, 'wpstickybar_create_data' ),
    ));

		register_rest_route('wpstickybar/v1', 'uninstall-feedback', array(
			'methods' => 'POST',
			'callback' => array( $this, 'wpstickybar_uninstall_feedback' ),
		));
		

		register_rest_route('wpstickybar/v1', 'banner/delete/(?P<id>\d+)', array(
			'methods' => 'DELETE',
			'callback' => array( $this, 'wpstickybar_delete_data' ),
			'args' => array(
					'id' => array(
							'validate_callback' => function($param, $request, $key) {
									return is_numeric($param);
							}
					),
			),
		));

		register_rest_route('wpstickybar/v1', 'banner/list', array(
			'methods' => 'GET',
			'callback' => array( $this, 'wpstickybar_list_banners' ),			
		));

	}

	function new_banner_insert_input(){
    global $wpdb;
    $tableprefix = $wpdb->prefix;
    $stickybar_banner = $tableprefix . 'stickybar_banner';
    $stickybar_stats = $tableprefix . 'stickybar_stats';

    $form_data = array_map('sanitize_text_field', $_POST); // sanitize form data

    // set default values for optional fields
    $background_img = isset($form_data['background-image']) ? $form_data['background-image'] : '';
    $enable_timmer = isset($form_data['enable-timmer']);
    $display_banner = isset($form_data['disable-banner']);
    $on_mac = isset($form_data['display-on-mac']);
    $on_mobile = isset($form_data['display-on-mobile']);
    $on_page = isset($form_data['display-on-page']);
    $on_post = isset($form_data['display-on-post']);

		// disable all banners with the same banner_position
		$wpdb->update(
			$stickybar_banner,
			array('display_banner' => false),
			array('banner_position' => $form_data['position-settings'])
		);
		
		// Retrieve gradient options from the form data
    $gradientOptionsArray = isset($form_data['gradient-options']) ? $form_data['gradient-options'] : '';

    // Retrieve the background type from the form data
    $backgroundType = isset($form_data['background-type']) ? $form_data['background-type'] : '';

    // Create the options array with the gradient and background type
    $optionsArray = array(
        'gradient_background' => $gradientOptionsArray,
        'background_type' => $backgroundType
    );

    // Convert the options array to JSON
    $otherOptionsJSON = json_encode($optionsArray);

    // insert banner data into database
    $wpdb->insert(
        $stickybar_banner,
        array(
            'title' => $form_data['title'],
            'promotion_text' => $form_data['promotion_text'],
            'button_text' => $form_data['button-text'],
            'button_url' => $form_data['button-url'],
            'background_img' => $background_img,
            'text_color' => $form_data['text-color'],
            'background_color' => $form_data['background-banner-color'],
            'text_time_color' => $form_data['text-timmer-color'],
            'number_time_color' => $form_data['number-timmer-color'],
            'background_time_color' => $form_data['background-timmer-color'],
            'enable_timmer' => $enable_timmer,
            'date_type' => $form_data['date-type'],
            'relative_day' => $form_data['relative-day'],
            'relative_hour' => $form_data['relative-hour'],
						'banner_position' => $form_data['position-settings'],
            'relative_minutes' => $form_data['relative-minute'],
            'relative_seconds' => $form_data['relative-second'],
            'static_date' => $form_data['static-date'],
            'display_banner' => true,
            'seconds_before' => $form_data['seconds-before'],
            'other_options' => $otherOptionsJSON // Save the background gradient options as JSON
        )
    );

    $banner_id = $wpdb->insert_id;

    // insert banner stats into database
    $wpdb->insert(
        $stickybar_stats,
        array(
            'banner_id' => $banner_id,
            'the_time' => current_time('mysql', true)
        )
    );

    wp_redirect(admin_url('admin.php?page=wpstickybars')); // redirect after insert
    exit;
}

public function array_map_recursive($callback, $array) {
	$result = array();
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$result[$key] = $this->array_map_recursive($callback, $value);
		} else {
			$result[$key] = call_user_func($callback, $value);
		}
	}
	return $result;
}

function wpstickybar_admin_notice() {
  $notice = '<div class="notice notice-success is-dismissible">';
  $notice .= '<p>' . __( 'Thank you for using WpStickyBar!', 'wpstickybar' ) . '</p>';
	$notice .= '<p>' . __( 'If you like this plugin, please rate us on WordPress.org.', 'wpstickybar' ) . '</p>';
	$notice .= '<div class="notices-link" style="margin-bottom:10px">';
  $notice .= '<a class="button button-primary ratting-btn" style="margin-right:10px" href="https://wordpress.org/plugins/wpstickybar-sticky-bar-sticky-header/#reviews" target="_blank">' . __( 'Rate us', 'wpstickybar' ) . '</a>';
  $notice .= '<a class="button" style="margin-right:10px" href="' . esc_url( add_query_arg( 'wpstickybar_dismiss', 'true' ) ) . '">' . __( 'Maybe later', 'wpstickybar' ) . '</a>';
  $notice .= '<a class="button" href="' . esc_url( add_query_arg( 'wpstickybar_nag', 'false' ) ) . '">' . __( 'Don\'t display this notification again', 'wpstickybar' ) . '</a>';
	$notice .= '</div>';
  $notice .= '</div>';

  return $notice;
}

function wpstickybar_display_admin_notice() {
	
  // Check if the user has dismissed the notice
  if ( ! empty( $_GET['wpstickybar_dismiss'] ) && 'true' == $_GET['wpstickybar_dismiss'] ) {
    return;
  }

  // Check if the user has opted out of the notice
  if ( ! empty( $_GET['wpstickybar_nag'] ) && 'false' == $_GET['wpstickybar_nag'] ) {
    update_option( 'wpstickybar_nag', 'no' );
    return;
  }

  // Check if the user has previously dismissed the notice
  if ( 'no' == get_option( 'wpstickybar_nag' ) ) {
    return;
  }

  // Display the notice
  echo $this->wpstickybar_admin_notice();
}

function delete_banner_callback()
{
	global $wpdb;
	global $tableprefix;
	$tableprefix = $wpdb->prefix;
	$stickybar_banner = $tableprefix . 'stickybar_banner';

	$meta = $_POST['update'];
	$meta = base64_decode($meta);
	$meta = esc_attr($meta);
	$banner_id_stat = $_POST['banner_id'];
	$wpdb->query("DELETE FROM `{$stickybar_banner}` WHERE `banner_id` = $banner_id_stat ");
}

function edit_banner_update_input() {
	global $wpdb;
	$tableprefix = $wpdb->prefix;
	$stickybar_banner = $tableprefix . 'stickybar_banner';

	$form_data = $this->array_map_recursive('wp_kses_post', $_POST);
	
	// set default values for optional fields
	$background_img = isset($form_data['background-image']) ? $form_data['background-image'] : '';
	$enable_timmer = isset($form_data['enable-timmer']);
	$display_banner = isset($form_data['disable-banner']);
	$on_mac = isset($form_data['display-on-mac']);
	$on_mobile = isset($form_data['display-on-mobile']);
	$on_page = isset($form_data['display-on-page']);
	$on_post = isset($form_data['display-on-post']);

	$banner_id = absint($_GET['id']);

	// disable all banners with the same banner_position
	$wpdb->update(
			$stickybar_banner,
			array('display_banner' => false),
			array('banner_position' => $form_data['position-settings'])
	);

	// Retrieve existing other_options from the database
	$existingOtherOptions = $wpdb->get_var(
		$wpdb->prepare(
				"SELECT other_options FROM $stickybar_banner WHERE banner_id = %d",
				$banner_id
		)
	);

	// Decode the existing other_options JSON
	$existingOptionsArray = json_decode($existingOtherOptions, true);

	// Retrieve gradient options from the form data
	$gradientOptionsArray = isset($form_data['gradient-options']) ? $form_data['gradient-options'] : '';

	// Retrieve the background type from the form data
	$backgroundType = isset($form_data['background-type']) ? $form_data['background-type'] : '';

	// Update the options array with the gradient and background type
	$existingOptionsArray['gradient_background'] = $gradientOptionsArray;
	$existingOptionsArray['background_type'] = $backgroundType;

	// Convert the options array to JSON
	$otherOptionsJSON = json_encode($existingOptionsArray);

	// update banner data in database
	$wpdb->update(
			$stickybar_banner,
			array(
					'title' => $form_data['title'],
					'promotion_text' => $form_data['promotion_text'],
					'button_text' => $form_data['button-text'],
					'button_url' => $form_data['button-url'],
					'background_img' => $background_img,
					'text_color' => $form_data['text-color'],
					'background_color' => $form_data['background-banner-color'],
					'text_time_color' => $form_data['text-timmer-color'],
					'number_time_color' => $form_data['number-timmer-color'],
					'background_time_color' => $form_data['background-timmer-color'],
					'enable_timmer' => $enable_timmer,
					'date_type' => $form_data['date-type'],
					'relative_day' => $form_data['relative-day'],
					'relative_hour' => $form_data['relative-hour'],
					'relative_minutes' => $form_data['relative-minute'],
					'relative_seconds' => $form_data['relative-second'],
					'static_date' => $form_data['static-date'],
					'display_banner' => $display_banner,
					'banner_position' => $form_data['position-settings'],
					'seconds_before' => $form_data['seconds-before'],
					'on_mac' => $on_mac,
					'on_mobile' => $on_mobile,
					'on_page' => $on_page,
					'on_post' => $on_post,
					'other_options' => $otherOptionsJSON // Save the background gradient options as JSON
			),
			array('banner_id' => $banner_id),
			array(
					'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
					'%d', '%s', '%d', '%d', '%d', '%d', '%s', '%d', '%s', '%d',
					'%d', '%d', '%d', '%d', '%s'
			),
			array('%d')
	);
}


	/**
	 * Renders Settings Tabs
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	function wpsticky_render_tabs() {
		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : 'general';

		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->sb_bar . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
		}
		echo '</h2>';
	}

	public function wpstickybar_attach_feedback_modal() {
		global $pagenow;
		if ( 'plugins.php' !== $pagenow ) {
			return;
		}
		$reasons = $this->get_uninstall_reasons();
		?>
		<div class="wpstickybar-modal" id="wpstickybar-modal">
			<div class="wpstickybar-modal-wrap">
				<div class="wpstickybar-modal-header">
					<h3><?php echo esc_html__( 'Quick Feedback', 'wpstickybar' ); ?></h3>
					<button type="button" class="wpstickybar-modal-close"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M0.572899 0.00327209C0.459691 0.00320032 0.349006 0.036716 0.254854 0.0995771C0.160701 0.162438 0.0873146 0.251818 0.0439819 0.356405C0.000649228 0.460992 -0.0106814 0.576084 0.0114242 0.687113C0.0335299 0.798142 0.0880779 0.900118 0.168164 0.980132L4.18928 5L0.168164 9.01987C0.0604905 9.12754 0 9.27358 0 9.42585C0 9.57812 0.0604905 9.72416 0.168164 9.83184C0.275838 9.93951 0.421875 10 0.574148 10C0.726422 10 0.872459 9.93951 0.980133 9.83184L5.00125 5.81197L9.02237 9.83184C9.13023 9.93836 9.2755 9.99844 9.4271 9.99923C9.5023 9.99958 9.57681 9.98497 9.6463 9.95623C9.71579 9.92749 9.77886 9.8852 9.83184 9.83184C9.93924 9.72402 9.99955 9.57804 9.99955 9.42585C9.99955 9.27367 9.93924 9.12768 9.83184 9.01987L5.81072 5L9.83184 0.980132C9.88515 0.926818 9.92744 0.863524 9.9563 0.793865C9.98515 0.724206 10 0.649547 10 0.574148C10 0.49875 9.98515 0.42409 9.9563 0.354431C9.92744 0.284772 9.88515 0.221479 9.83184 0.168164C9.77852 0.114849 9.71523 0.072558 9.64557 0.0437044C9.57591 0.0148507 9.50125 0 9.42585 0C9.35045 0 9.27579 0.0148507 9.20614 0.0437044C9.13648 0.072558 9.07318 0.114849 9.01987 0.168164L4.99813 4.19053L0.976385 0.170662C0.868901 0.0635642 0.723383 0.00338113 0.57165 0.00327209H0.572899Z" fill="#ffffff"/> </svg></button>
				</div>
				<div class="wpstickybar-modal-body">
					<h4 class="wpstickybar-feedback-caption"><?php echo esc_html__( 'If you have a moment, please let us know why you are deactivating the WpStickyBar plugin.', 'wpstickybar' ); ?></h4>
					<ul class="wpstickybar-feedback-reasons-list">
						<?php
						foreach ( $reasons as $reason ) :
							?>
							<li>
								<div class="wpstickybar-feedback-form-group">
									<label class="wpstickybar-feedback-label"><input type="radio" name="selected-reason" value="<?php echo esc_attr( $reason['id'] ); ?>" class="wpstickybar-feedback-input-radio"><?php echo esc_html( $reason['text'] ); ?></label>
									<?php
										$fields = ( isset( $reason['fields'] ) && is_array( $reason['fields'] ) ) ? $reason['fields'] : array();
									if ( empty( $fields ) ) {
										continue;
									}
									?>
									<div class="wpstickybar-feedback-form-fields">
										<?php

										foreach ( $fields as $field ) :
											$field_type        = isset( $field['type'] ) ? $field['type'] : 'text';
											$field_placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
											$field_key         = isset( $reason['id'] ) ? $reason['id'] : '';
											$field_name        = $field_key . '-' . $field_type;
											if ( 'textarea' === $field_type ) :
												?>
												<textarea rows="3" cols="45" class="wpstickybar-feedback-input-field" name="<?php echo esc_attr( $field_name ); ?>" placeholder="<?php echo esc_attr( $field_placeholder ); ?>"></textarea>
												<?php
											else :
												?>
												<input class="wpstickybar-feedback-input-field" type="text" name="<?php echo esc_attr( $field_name ); ?>" placeholder="<?php echo esc_attr( $field_placeholder ); ?>" >
												<?php
											endif;
											endforeach;
										?>
									</div>
								</div>
							</li>

						<?php endforeach; ?>
					</ul>					
				</div>
				<div class="wpstickybar-modal-footer">
					<button class="button-primary wpstickybar-modal-submit">
						<?php echo esc_html__( 'Submit & Deactivate', 'wpstickybar' ); ?>
					</button>
					<button class="button-secondary wpstickybar-modal-skip">
						<?php echo esc_html__( 'Skip & Deactivate', 'wpstickybar' ); ?>
					</button>
				</div>
			</div>
		</div>

		<style type="text/css">
			.wpstickybar-modal {
				position: fixed;
				z-index: 99999;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				background: rgba(0, 0, 0, 0.5);
				display: none;
			}

			.wpstickybar-modal.modal-active {
				display: flex;
				align-items: center;
				justify-content: center;
			}

			.wpstickybar-modal-wrap {
				width: 600px;
				position: relative;
				background: #fff;
			}

			.wpstickybar-modal-header {
				background: linear-gradient(336.94deg,#1A7FBB -111.69%,#26238D 196.34%);
				padding: 12px 20px;
			}

			.wpstickybar-modal-header h3 {
				display: inline-block;
				color: #fff;
				line-height: 150%;
				margin: 0;
			}

			.wpstickybar-modal-body {
				font-size: 14px;
				line-height: 2.4em;
				padding: 5px 30px 20px 30px;
				box-sizing: border-box;
			}

			.wpstickybar-modal-body h3 {
				font-size: 15px;
			}

			.wpstickybar-modal-body .input-text,
			.wpstickybar-modal-body {
				width: 100%;
			}

			.wpstickybar-modal-body .wpstickybar-feedback-input {
				margin-top: 5px;
				margin-left: 20px;
			}

			.wpstickybar-modal-footer {
				padding: 0px 20px 15px 20px;
				display: flex;
			}

			.cky-button-left {
				float: left;
			}

			.cky-button-right {
				float: right;
			}

			.cky-sub-reasons {
				display: none;
				padding-left: 20px;
				padding-top: 10px;
				padding-bottom: 4px;
			}

			.cky-uninstall-feedback-privacy-policy {
				text-align: left;
				font-size: 12px;
				line-height: 14px;
				margin-top: 20px;
				font-style: italic;
			}

			.cky-uninstall-feedback-privacy-policy a {
				font-size: 11px;
				color: #1863DC;
				text-decoration-color: #99c3d7;
			}

			.cky-goto-support {
				color: #1863DC;
				text-decoration: none;
				display: flex;
				align-items: center;
				margin-left: 15px;
			}

			.wpstickybar-modal-footer .wpstickybar-modal-submit {
				background-color: #1863DC;
				border-color: #1863DC;
				color: #FFFFFF;
			}

			.wpstickybar-modal-footer .wpstickybar-modal-skip {
				font-size: 12px;
				color: #a4afb7;
				background: none;
				border: none;
				margin-left: auto;
			}

			.wpstickybar-modal-close {
				background: transparent;
				border: none;
				color: #fff;
				float: right;
				font-size: 18px;
				font-weight: lighter;
				cursor: pointer;
			}

			.wpstickybar-feedback-caption {
				font-weight: bold;
				font-size: 15px;
				color: #27283C;
				line-height: 1.5;
			}

			input[type="radio"].wpstickybar-feedback-input-radio {
				margin: 0 10px 0 0;
				box-shadow: none;
			}
			.wpstickybar-feedback-reasons-list li {
				line-height: 1.9;
			}
			.wpstickybar-feedback-label {
				font-size: 13px;
			}
			.wpstickybar-modal .wpstickybar-feedback-input-field {
				width: 98%;
				display: flex;
				padding: 5px;
				-webkit-box-shadow: none;
				box-shadow: none;
				font-size: 13px;
			}
			.wpstickybar-modal input[type="text"].wpstickybar-feedback-input-field:focus {
				-webkit-box-shadow: none;
				box-shadow: none;
			}
			.wpstickybar-feedback-form-fields {
				margin: 10px 0 0 25px;
				display: none;
			}
		</style>

		<script type="text/javascript">
			(function($) {
				$(function() {
					const modal = $('#wpstickybar-modal');
					let deactivateLink = '';
					$('a#deactivate-wpstickybar-sticky-bar-sticky-header').click(function(e) {
						e.preventDefault();
						modal.addClass('modal-active');
						deactivateLink = $(this).attr('href');
						modal.find('a.dont-bother-me').attr('href', deactivateLink).css('float', 'right');
					});

					modal.on('click', '.wpstickybar-modal-skip', function(e) {
						e.preventDefault();
						modal.removeClass('modal-active');
						window.location.href = deactivateLink;
					});

					modal.on('click', '.wpstickybar-modal-close', function(e) {
						e.preventDefault();
						modal.removeClass('modal-active');
					});
					modal.on('click', 'input[type="radio"]', function() {
						$('.wpstickybar-feedback-form-fields').hide();
						const $parent =   $(this).closest('.wpstickybar-feedback-form-group');
						if( !$parent ) return;
						const $fields = $parent.find('.wpstickybar-feedback-form-fields');
						if( !$fields ) return;
						const $input = $fields.find('.wpstickybar-feedback-input-field');
						$input && $fields.show(),$input.focus();
					});
					modal.on('click', '.wpstickybar-modal-submit', function(e) {
						e.preventDefault();
						const button = $(this);
						if (button.hasClass('disabled')) {
							return;
						}
						const $radio = $('input[type="radio"]:checked', modal);
						const $parent =   $radio && $radio.closest('.wpstickybar-feedback-form-group');
						if( !$parent ) {
							window.location.href = deactivateLink;
							return;
						}
						const $input = $parent.find('.wpstickybar-feedback-input-field');
						$.ajax({
							url: "<?php echo esc_url_raw( rest_url() . $this->namespace . $this->rest_base ); ?>",
							type: 'POST',
							data: {
								reason_id: (0 === $radio.length) ? 'none' : $radio.val(),
								reason_text: (0 === $radio.length) ? 'none' : $radio.closest('label').text(),
								reason_info: (0 !== $input.length) ? $input.val().trim() : ''
							},
							beforeSend: function(xhr) {
								button.addClass('disabled');
								button.text('Processing...');
								xhr.setRequestHeader( 'X-WP-Nonce', '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>');
							},
							complete: function() {								
								window.location.href = deactivateLink;
							}
						});
					});
				});
			}(jQuery));
		</script>
		<?php
	}

	private function get_uninstall_reasons() {

		$reasons = array(
			array(
				'id'     => 'setup-difficult',
				'text'   => __( 'Setup is too difficult/ Lack of documentation', 'wpstickybar' ),
				'fields' => array(
					array(
						'type'        => 'textarea',
						'placeholder' => __(
							'Describe the challenges that you faced while using our plugin',
							'wpstickybar'
						),
					),
				),
			),
			array(
				'id'     => 'not-have-that-feature',
				'text'   => __( 'The plugin is great, but I need specific feature that you don\'t support', 'wpstickybar' ),
				'fields' => array(
					array(
						'type'        => 'textarea',
						'placeholder' => __( 'Could you tell us more about that feature?', 'wpstickybar' ),
					),
				),
			),
			array(
				'id'     => 'other',
				'text'   => __( 'Other', 'wpstickybar' ),
				'fields' => array(
					array(
						'type'        => 'textarea',
						'placeholder' => __( 'Please share the reason', 'wpstickybar' ),
					),
				),
			),
		);

		return $reasons;
	}

	public function wpstickybar_uninstall_feedback( $request ) {
		global $wpdb;
		if ( ! isset( $request['reason_id'] ) ) {
			wp_send_json_error();
		}
		$data = array(
			'reason_slug'    => sanitize_text_field( wp_unslash( $request['reason_id'] ) ),
			'reason_detail'  => ! empty( $request['reason_text'] ) ? sanitize_text_field( wp_unslash( $request['reason_text'] ) ) : null,
			'content'       => ! empty( $request['reason_info'] ) ? sanitize_text_field( wp_unslash( $request['reason_info'] ) ) : null,
		);

		$response = wp_remote_post(
			$this->api_url,
			array(
				'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => false,
				'body'        => wp_json_encode( $data ),
				'cookies'     => array(),
			)
		);
		
		wp_send_json_success();
	}

}
