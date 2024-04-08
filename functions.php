<?php
<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '2.7.1' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'classic-editor.css' );

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

/**
 * If Elementor is installed and active, we can load the Elementor-specific Settings & Features
*/

// Allow active/inactive via the Experiments
require get_template_directory() . '/includes/elementor-functions.php';

/**
 * Include customizer registration functions
*/
function hello_register_customizer_functions() {
	if ( is_customize_preview() ) {
		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_register_customizer_functions' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

function maximum_api_filter($query_params) {
   $query_params['per_page']['maximum'] = 10000;
   $query_params['per_page']['default'] = 500;
   return $query_params;
}
add_filter('rest_product_collection_params', 'maximum_api_filter', 10, 1 );


function cfl_startSession() {
    if(!session_id()) {
        session_start();
    }
}

add_action('init', 'cfl_startSession', 1);
/**
* register fields Validating.
*/
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {

      if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
             $validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
      }
      if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
             $validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
      }
      // if ( isset( $_POST['c_gender'] ) && empty( $_POST['c_gender'] ) ) {
      //        $validation_errors->add( 'c_gender_error', __( '<strong>Error</strong>: Gender is required!.', 'woocommerce' ) );
      // }
      if ( isset( $_POST['date_birth'] ) && empty( $_POST['date_birth'] ) ) {
             $validation_errors->add( 'date_birth_error', __( '<strong>Error</strong>: Date of birth is required!.', 'woocommerce' ) );
      }
      
         return $validation_errors;
}
add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

/**
* Register save extra fields.
*/
function wooc_save_extra_register_fields( $customer_id ) {
   
   if ( isset( $_POST['billing_phone'] ) ) {
      update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
   }
   if ( isset( $_POST['billing_first_name'] ) ) {
      update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
      // update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
   }
   if ( isset( $_POST['billing_last_name'] ) ) {
   	update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
      // update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
   }
   if ( isset( $_POST['c_gender'] ) ) {
   	update_user_meta( $customer_id, 'c_gender', sanitize_text_field( $_POST['c_gender'] ) );
   }
   if ( isset( $_POST['date_birth'] ) ) {
   	update_user_meta( $customer_id, 'date_birth', sanitize_text_field( $_POST['date_birth'] ) );
   }
}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );

/**
 * WooCommerce User Registration Shortcode
*/
   
add_shortcode('registration_form_fun', 'cfl_registration_form');
     
function cfl_registration_form() {
	ob_start();
	if ( is_user_logged_in() ){
   		return '<p>You are already registered</p>';
   	}else{
   		// print_r($_SESSION);
   		//if($_SESSION['came_from'] == 'login'){
   		if($_SESSION['came_from'] != 'login'){
   		echo '<div class="checkout_register" style="display:none">'; }
	   		do_action( 'woocommerce_before_customer_login_form' );
			$html = wc_get_template_html( 'myaccount/form-login.php' );
			$dom = new DOMDocument();
			$dom->encoding = 'utf-8';
			$dom->loadHTML( utf8_decode( $html ) );
			$xpath = new DOMXPath( $dom );
			$form = $xpath->query( '//form[contains(@class,"register")]' );
			$form = $form->item( 0 );
			echo $dom->saveXML( $form );
		if($_SESSION['came_from'] != 'login'){
			echo '</div>';	
		}
		//}
   	} ?>
	<?php 
	if($_SESSION['came_from'] != 'login'){
		// print_r($_SESSION);
	?>
	<a href="<?php echo site_url('register');?>"  id="cfl_member">Checkout as my CL member</a>
	<hr>
	<a href=""  id="guest_checkout">Checkout as guest</a>
	<?php } 
	return ob_get_clean();
}

/**
 * WooCommerce User Login Shortcode
*/
  
add_shortcode( 'cfl_login_form_fun', 'cfl_login_form_fun' );
  
function cfl_login_form_fun() {
   ob_start();
	?>
   	
	<?php
	do_action( 'woocommerce_before_customer_login_form' ); 
 
	?>
	<form class="woocommerce-form woocommerce-form-login login" method="post">

				
			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?> &nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : $_SESSION['lr_email']; ?> " /><?php // @codingStandardsIgnoreLine ?>
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
			</p>


			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
				</label>
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
			</p>
			<p class="woocommerce-LostPassword lost_password">
				<a href="<?php echo site_url('lost-password') ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>
	<hr>
	<?php 
	// print_r($_SESSION);
	if($_SESSION['came_from'] != 'login'){
	?>
	<a href=""  id="guest_checkout">Checkout as guest</a>
	<?php } ?>	
   	<?php do_action( 'woocommerce_after_customer_login_form' );

   return ob_get_clean();
   		
}

/**
 * Redirect Login/Registration to My Account
*/
 
add_action( 'template_redirect', 'cfl_redirect_login_registration_if_logged_in' );
 
function cfl_redirect_login_registration_if_logged_in() {
	global $post;
	if(is_user_logged_in()){
		if(is_page() && is_user_logged_in() && (has_shortcode($post->post_content, 'registration_form_fun' ) || has_shortcode($post->post_content, 'cfl_login_form_fun' ) ) || has_shortcode($post->post_content, 'login_continew' )  ){
			if($_SESSION['came_from'] == 'checkout'){
				wp_safe_redirect( site_url( 'checkout') );
				unset($_SESSION['came_from']);
				exit;
			}
			wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
        // exit;
		}
	}else{
		if(is_account_page()){
			wp_safe_redirect( site_url( 'login' ) );
		}	
	}
    
    
}

add_shortcode('login_continew','cfl_login_continew');
function cfl_login_continew(){ 
	ob_start();
	$came_from = "login";
	if(isset($_SESSION['came_from'])){
		$came_from = $_SESSION['came_from'];
		unset($_SESSION['came_from']); 
	}
	?>

	<div class="sign_form">
   	<label>Email address</label>
    	<div class="sign_form_input">
      	<input type="email" placeholder="Email" name="login_email" value="" required />
      		<small style="display: none;"> </small>
    	</div>
    	<input type="hidden" name="came_from" value="<?php echo $came_from ?>">
    	<button type="submit" id="login_continew" >Continue</button>
	</div>

<?php return ob_get_clean();
}

add_action('wp_head','login_continew_btn');
function login_continew_btn(){

	?>
	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script type="text/javascript">

		jQuery(document).on('click','#login_continew',function(){
			var email = jQuery('[name="login_email"]').val();
			var came_from = jQuery('[name="came_from"]').val();
			
			if(email == ''){
				jQuery('.sign_form_input small').show().text('Email is required');
				return false;
			}
			else{
				jQuery('.sign_form_input small').hide().text();
				
			}
			if(!isValidEmailAddress(email))
         {
         	jQuery('.sign_form_input small').show().text('Enter valid email');
         	return false;
         }else{
         	jQuery('.sign_form_input small').hide().text();
         		$.ajax({
		        		type: "GET",
		        		dataType: 'json',
		        		url: "<?php echo admin_url('admin-ajax.php'); ?>",
		        		data: {
		            	action: 'email_exists_check',
		            	email: email,
		            	came_from : came_from
		        		},
        				success: function(data) {
        				
        				if (data.result === true) {
            				window.location.href = '/sign-up';
                		} else {
                			window.location.href = '/register';
            			}
      	  			}
    				});
         	return false;
         }	
			
		});
		function isValidEmailAddress(emailAddress) {
    		var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    		return pattern.test(emailAddress);
		}
		
	</script>
	<?php if(!is_user_logged_in()){ ?>
		<script type="text/javascript">
			jQuery(document).on('click','#custom_btn_checkout',function(){
				$.ajax({
	        		type: "POST",
	        		dataType: 'json',
	        		url: "<?php echo admin_url('admin-ajax.php'); ?>",
	        		data: {
	            	action: 'btn_checkout',
	            	btncheck: 'btncheck'
	        		},
					success: function(data) {
						window.location.href = '/checkout-login';
	        		}
	    		});
    			return false;
			});
			jQuery(document).on('click','#guest_checkout',function(){
				$.ajax({
	        		type: "POST",
	        		dataType: 'json',
	        		url: "<?php echo admin_url('admin-ajax.php'); ?>",
	        		data: {
	            	action: 'guest_checkout',
	            	guest_checkout: 'guest_checkout'
	        		},
					success: function(data) {
						window.location.href = '/checkout';
	        		}
	    		});
    			return false;
			});
			jQuery(document).on('click','#cfl_member',function(){
				$(".checkout_register").show();
				$(this).hide();
				$("#guest_checkout").hide();
				$("hr").hide();
				// guest_checkout
    			return false;
			});
			
				
		</script>
	<?php } ?>
	<script type="text/javascript">
		jQuery(document).on('click','.open-ship-sidebar',function(){
			var ex_add = $("input[name='extra_ship_address']").val();
			if(ex_add == 'extra_ship_address' ){
			}else{	
				$.ajax({
	        		type: "GET",
	        		dataType: 'json',
	        		url: "<?php echo admin_url('admin-ajax.php'); ?>",
	        		data: {
	            	action: 'get_shipping_data',
	            	shipping: 'shipping'
	        		},
     				success: function(data) {

     					$("#shipping_first_name").val(data.shipping_first_name);
     					$("#shipping_last_name").val(data.shipping_last_name);
     					$("#shipping_country").val(data.shipping_country);
     					$("#shipping_address_1").val(data.shipping_address_1);
     					$("#shipping_address_2").val(data.shipping_address_2);
     					$("#shipping_postcode").val(data.shipping_postcode);
     					$("#shipping_city").val(data.shipping_city);
     					$("#shipping_state").val(data.shipping_state).trigger("change");
        			}
    			});
    		}	
		});

		jQuery(document).on('click','.open_extra_checkout_sidebar',function(){
			var ex_add = $("input[name='extra_ship_address']").val();
			
			$.ajax({
	        		type: "post",
	        		dataType: 'json',
	        		url: "<?php echo admin_url('admin-ajax.php'); ?>",
	        		data: {
	            	action: 'get_ship_add'
	            	
	        		},
     				success: function(data) {

     				}
    			});
    			
		});

		jQuery(document).on('click','.checkout_billing_edit',function(){
			$.ajax({
	        		type: "post",
	        		dataType: 'json',
	        		url: "<?php echo admin_url('admin-ajax.php'); ?>",
	        		data: {
	            	action: 'get_bill_add'
	            	
	        		},
     				success: function(data) {

     				}
    			});
    			
		});

		jQuery(document).on('click','.open-ship-sidebar-edit a',function(){
			var data_id = $(this).attr('data-id');

			$.ajax({
	        		type: "GET",
	        		dataType: 'json',
	        		url: "<?php echo admin_url('admin-ajax.php'); ?>",
	        		data: {
	            	action: 'extra_shipping_edit_data',
	            	data_id: data_id
	        		},
     				success: function(data) {
     					// console.log(data);
     					$("#shipping_extra_form #shipping_first_name_field").after().append("<input type='hidden' id='extra_ship_id' name='extra_ship_id' value='"+data[0].id+"'>");
     					$("#shipping_extra_form #shipping_first_name").val(data[0].firstname);
     					$("#shipping_extra_form #shipping_last_name").val(data[0].lastname);
     					$("#shipping_extra_form #shipping_country").val(data[0].shipping_country);
     					$("#shipping_extra_form #shipping_address_1").val(data[0].shipping_address_1);
     					$("#shipping_extra_form #shipping_address_2").val(data[0].shipping_address_2);
     					$("#shipping_extra_form #shipping_postcode").val(data[0].shipping_postcode);
     					$("#shipping_extra_form #shipping_city").val(data[0].shipping_city);
     					$("#shipping_extra_form #shipping_state").val(data[0].shipping_state).trigger("change");
     					
        			}
    			});

		});
		jQuery(document).on('click','#extra_add',function(){
			var data_id = $(this).attr('data-id');

			$.ajax({
	        		type: "GET",
	        		dataType: 'json',
	        		url: "<?php echo admin_url('admin-ajax.php'); ?>",
	        		data: {
	            	action: 'extra_shipping_delete',
	            	data_id: data_id
	        		},
     				success: function(data) {
     					// console.log(data);
     					if(data == 1){
     						location.reload();
     					}
     					
     					
        			}
    			});

		});

		jQuery(document).on('click','.checkout_ship_add',function(){
			var data_id = $(this).attr('data-id');
			
			$.ajax({
		        		type: "POST",
		        		dataType: 'json',
		        		url: "<?php echo admin_url('admin-ajax.php'); ?>",
		        		data: {
		            	action: 'checkout_change_address',
		            	data_id: data_id
		        		},
	     				success: function(data) {
	     					if(data){
	     						$(".woocommerce-shipping-fields #shipping_first_name").val(data.shipping_first_name);
     							$(".woocommerce-shipping-fields #shipping_last_name").val(data.shipping_last_name);
     							$(".woocommerce-shipping-fields #shipping_country").val(data.shipping_country);
		     					$(".woocommerce-shipping-fields #shipping_address_1").val(data.shipping_address_1);
		     					$(".woocommerce-shipping-fields #shipping_address_2").val(data.shipping_address_2);
		     					$(".woocommerce-shipping-fields #shipping_postcode").val(data.shipping_postcode);
		     					$(".woocommerce-shipping-fields #shipping_city").val(data.shipping_city);
		     					$(".woocommerce-shipping-fields #shipping_state").val(data.shipping_state).trigger("change");
		     					$(".ship_extra_addr_checkout_main .ship_close_sider").trigger("click");

		     					$(".ship_name").html(data.shipping_first_name+' '+data.shipping_last_name+'<br>');
		     					$(".ship_addr").html(data.shipping_address_1+' '+data.shipping_address_2+'<br>');
		     					if(data.shipping_country == 'DE')
		     					{
		     						var country = 'Germany';
		     					}
		     					else{
		     						var country = data.shipping_country;
		     					}
		     					$(".ship_post_city").html(data.shipping_postcode+''+data.shipping_city+'<br>'+country);
	     					}
	     				}
	    			});
			

		});
			
			
	</script>

<?php }


// get_bill_add
add_action('wp_ajax_get_ship_add', 'get_bill_add');
add_action('wp_ajax_nopriv_get_ship_add', 'get_bill_add');

function get_bill_add(){
	$_SESSION['bill_redirect'] = 'bill_red';	
}
add_action('wp_ajax_get_ship_add', 'get_ship_add');
add_action('wp_ajax_nopriv_get_ship_add', 'get_ship_add');

function get_ship_add(){
	$_SESSION['ship_redirect'] = 'ship_red';	
}

add_action('wp_ajax_checkout_change_address', 'checkout_change_address');
add_action('wp_ajax_nopriv_checkout_change_address', 'checkout_change_address');

function checkout_change_address()
{
	global $wpdb;
	$tablename= $wpdb->prefix.'custom_shipping_address';
	
	$dataid = $_POST['data_id'];
	$result = $wpdb->get_results ( "SELECT * FROM $tablename WHERE `id` = $dataid ");

	if(!empty($dataid)){
		
		$data = array(
			'shipping_first_name' 	=> $result[0]->firstname,
			'shipping_last_name' 	=> $result[0]->lastname,
			'shipping_country' 		=> $result[0]->shipping_country,
			'shipping_address_1' 	=> $result[0]->shipping_address_1,
			'shipping_address_2' 	=> $result[0]->shipping_address_2,
			'shipping_postcode' 		=> $result[0]->shipping_postcode,
			'shipping_state' 			=> $result[0]->shipping_state,
			'shipping_city' 			=> $result[0]->shipping_city
		);	
	
	}
	if(empty($dataid)){
		$data = array(
			'shipping_first_name' => get_user_meta(get_current_user_id(),'shipping_first_name',true),
			'shipping_last_name' => get_user_meta(get_current_user_id(),'shipping_last_name',true),
			'shipping_country' => get_user_meta(get_current_user_id(),'shipping_country',true),
			'shipping_address_1' => get_user_meta(get_current_user_id(),'shipping_address_1',true),
			'shipping_address_2' => get_user_meta(get_current_user_id(),'shipping_address_2',true),
			'shipping_postcode' => get_user_meta(get_current_user_id(),'shipping_postcode',true),
			'shipping_state' => get_user_meta(get_current_user_id(),'shipping_state',true),
			'shipping_city' => get_user_meta(get_current_user_id(),'shipping_city',true),
		);
	}
	echo json_encode( $data );
	 die();
}


add_action('wp_ajax_extra_shipping_delete', 'extra_shipping_delete');
add_action('wp_ajax_nopriv_extra_shipping_delete', 'extra_shipping_delete');

function extra_shipping_delete(){
	$dataid = $_GET['data_id'];
	global $wpdb;
	$tablename=$wpdb->prefix.'custom_shipping_address';
	$delete_record = $wpdb->delete( $tablename, array( 'id' => $dataid ) );
	echo json_encode( $delete_record );
    die();
}

add_action('wp_ajax_extra_shipping_edit_data', 'extra_shipping_edit_data');
add_action('wp_ajax_nopriv_extra_shipping_edit_data', 'extra_shipping_edit_data');
function extra_shipping_edit_data(){
	$dataid = $_GET['data_id'];
	global $wpdb;
	$tablename=$wpdb->prefix.'custom_shipping_address';
	$result = $wpdb->get_results ( "SELECT * FROM $tablename WHERE `id` = $dataid ");
	echo json_encode( $result );
    die();
}

add_action('wp_ajax_email_exists_check', 'email_exists_check');
add_action('wp_ajax_nopriv_email_exists_check', 'email_exists_check');

add_action('wp_ajax_get_shipping_data', 'get_shipping_data');
add_action('wp_ajax_nopriv_get_shipping_data', 'get_shipping_data');
function get_shipping_data(){
	$data = array(
		'shipping_first_name' => get_user_meta(get_current_user_id(),'shipping_first_name',true),
		'shipping_last_name' => get_user_meta(get_current_user_id(),'shipping_last_name',true),
		'shipping_company' => get_user_meta(get_current_user_id(),'shipping_company',true),
		'shipping_country' => get_user_meta(get_current_user_id(),'shipping_country',true),
		'shipping_address_1' => get_user_meta(get_current_user_id(),'shipping_address_1',true),
		'shipping_address_2' => get_user_meta(get_current_user_id(),'shipping_address_2',true),
		'shipping_postcode' => get_user_meta(get_current_user_id(),'shipping_postcode',true),
		'shipping_state' => get_user_meta(get_current_user_id(),'shipping_state',true),
		'shipping_city' => get_user_meta(get_current_user_id(),'shipping_city',true),
		
	);
	echo json_encode( $data );
    die();
}
function email_exists_check() {

	//print_r($_GET);
    $email = $_GET['email'];
    $came_from = $_GET['came_from'];
    
    if ( email_exists( $email ) ) {
        $response['result'] = true;
        $_SESSION['lr_email']=$email;
        $_SESSION['came_from']=$came_from;
        
    } else {
        $response['result'] = false;
        $_SESSION['lr_email']=$email;
        $_SESSION['came_from']=$came_from;
    }
    echo json_encode( $response );
    die();
}

add_action('wp_ajax_btn_checkout', 'btn_checkout');
add_action('wp_ajax_nopriv_btn_checkout', 'btn_checkout');
function btn_checkout(){
	if($_POST['btncheck']){
		$_SESSION['custom_bridge']='yes';
		$_SESSION['came_from']='checkout';

	}
}

add_action('wp_ajax_guest_checkout', 'guest_checkout');
add_action('wp_ajax_nopriv_guest_checkout', 'guest_checkout');
function guest_checkout(){
	if($_POST['guest_checkout']){
		$_SESSION['guest_checkout']='yes';
	}
}

// Side cart Fregement
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	?>
	
	<div class="sdr_cart_main xoo-wsc-products">
		<?php
	
		if(WC()->cart->get_cart_contents_count() == 0){
			echo '<div class="isCartEmpty"><h3>My shopping cart</h3><p>Oh, its empty! Lets fill it with joy</p></div>';
		}

		if ( WC()->cart->get_cart_contents_count()) { ?>
	
			<div class="sdr_cart_body">
				<div class="sdr_cart_heading">
					<h2>My shopping cart</h2>
					<span> <?php  echo count( WC()->cart->get_cart() ) ?> products</span>
					<h3 class="added_to_cart">Added to cart</h3>
				</div>
				<div class="sdr_cart_content">
					<?php 
		
					$subtotal = WC()->cart->subtotal;
					$currency_symbol = get_woocommerce_currency_symbol();
					
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product =  wc_get_product( $cart_item['data']->get_id() );
						$quantity = $cart_item['quantity'];	
						$price = $_product->get_regular_price();
						$sale_price = $_product->get_sale_price();
						$image = wp_get_attachment_url( $_product->get_image_id() ); 
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<div class="sdr_cart">
							<div class="sdr_cart_left">
								<div  class="sdr_cart_image">
									<img src="<?php echo $image ?>" alt="product image" />
								</div>
								<div  class="sdr_cart_left_text">
									<h3><a href="<?php echo $product_permalink ?>"><?php echo $_product->get_name() ?></a></h3>
									<p><?php echo $_product->get_short_description() ?></p>
									<p class="pdr-qty">Quantity: <?php echo $quantity ?></p>
								</div>
							</div>	
							<div class="sdr_cart_right">
								<?php 
									if($sale_price) { ?>
									<strong class="offer_price">€ <?php echo $sale_price ?></strong>
									<?php } else { ?>
										<strong class="offer_price">€ <?php echo $price  ?></strong>
									<?php }?>
								<?php if($sale_price) { ?>
								<s class="origin_price"><?php echo $price; ?></s>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="sdr_cart_footer">
				<div class="prc_total">
					<p>Order Subtotal</p>
					<strong>€<?php echo $subtotal ?></strong>
				</div>
				<div class="prc_total_buttin">
					<a href="/produkte"class="btn btn_shoping">CONTINUE SHOPPING</a>
					<?php $checkout_url = wc_get_checkout_url(); ?>
					<a href="/cart-2" class="btn btn_view_cart">View Cart</a>
				</div>
			</div>
		<?php }	?>
	</div>
	
	<?php
	$fragments['.xoo-wsc-products'] = ob_get_clean();
	return $fragments;
}

// unset menu from my-account
add_filter( 'woocommerce_account_menu_items', 'my_account_menu_items' );
function my_account_menu_items( $items ) {
	// print_r($items);
    unset($items['downloads']);
    unset($items['dashboard']);
    return $items;
}

// Rearrage my account navigation
add_filter( 'woocommerce_account_menu_items', 'add_link_my_account' );
 
function add_link_my_account( $items ) {
   $newitems = array(
   		'edit-account'    => __( 'Personal Details', 'woocommerce' ),
   		'orders'          => __( 'Orders', 'woocommerce' ),
   		'edit-address'    => _n( 'Address Book', 'Address', (int) wc_shipping_enabled(), 'woocommerce' ),
   		'cl-communication' => __( 'CL Communication', 'woocommerce' ),		
      	'dashboard'       => __( 'Dashboard', 'woocommerce' ),
      'customer-logout' => __( 'Logout', 'woocommerce' ),
      
   );   
   return $newitems;
}

//Remove required field in My Account Edit form
add_filter('woocommerce_save_account_details_required_fields', 'remove_required_fields');

function remove_required_fields( $required_fields ) {
	// echo '<pre>';
	// print_r($required_fields);
	unset($required_fields['account_first_name']);
	unset($required_fields['account_last_name']);
	unset($required_fields['account_display_name']);
    unset($required_fields['account_email']);

    return $required_fields;
}

// Save the custom field in woocomerce account 
add_action( 'woocommerce_save_account_details', 'save_account_details', 12, 1 );
function save_account_details( $user_id ) {
    // For Gender field
    
    if( isset( $_POST['p_first_name'] ) )
        update_user_meta( $user_id, 'p_first_name', sanitize_text_field( $_POST['p_first_name'] ) );
    
    if( isset( $_POST['p_last_name'] ) )
        update_user_meta( $user_id, 'p_last_name', sanitize_text_field( $_POST['p_last_name'] ) );

    if( isset( $_POST['c_gender'] ) )
        update_user_meta( $user_id, 'c_gender', sanitize_text_field( $_POST['c_gender'] ) );

    // For Date of birth
    if( isset( $_POST['date_birth'] ) )
        update_user_meta( $user_id, 'date_birth', sanitize_text_field( $_POST['date_birth'] ) );

    // For phone
    if( isset( $_POST['billing_phone'] ) )
        update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    
}

// init custom end point "CL-Communication"
add_action('init', function() {
	add_rewrite_endpoint('cl-communication', EP_ROOT | EP_PAGES);
});


add_action('woocommerce_account_cl-communication_endpoint', function() {
	$cl_communication = [];  
	
	wc_get_template('myaccount/cl-communication.php', [
		'cl-communication' => $cl_communication
	]);
});

add_action('wp_head', 'show_more_content');
function show_more_content(){ ?>
	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script>
        jQuery( function( $ ) {
            $(document).ready(function() {
            	$(document).on('click', ".show_less", function() {
            		$(".single-product .sl_product_desc").css({
	                    "height": "100px", 
	                    "overflow": "hidden",
	                });
	                $(this).addClass('show_more').removeClass('show_less').text('show more');

            	});

				// $(".show_more").on( "click", function() {
				$(document).on('click', ".show_more", function() {
                	$('.single-product .sl_product_desc').css('height', 'auto');
                	$('.single-product .sl_product_desc').css('overflow', 'visible');
                	$('.single-product .sl_product_underline ').addClass('show_less').removeClass('show_more').text('show less');

                    // $(".single-product .sl_product_desc").animate({height: 'auto', overflow : 'visible'});
                });
            });
        });
    </script>
<?php }

add_filter('woocommerce_billing_fields','custom_billing_fields');
add_filter( 'woocommerce_shipping_fields' , 'custom_billing_fields', 99 );

function custom_billing_fields( $fields = array() ) {

	unset($fields['billing_company']);
	// unset($fields['billing_phone']);
	unset($fields['shipping_company']);
	unset($fields['shipping_phone']);
	return $fields;
}

function edit_address( $attr ) {
	$load_address = isset( $wp->query_vars['edit-address'] ) ? wc_edit_address_i18n( sanitize_title( $wp->query_vars['edit-address'] ), true ) : 'billing';
	$load_address = $attr['type'];
	$current_user = wp_get_current_user();
	$load_address = sanitize_key( $load_address );
	$country      = get_user_meta( get_current_user_id(), $load_address . '_country', true );

	if ( ! $country ) {
		$country = WC()->countries->get_base_country();
	}

	if ( 'billing' === $load_address ) {
		$allowed_countries = WC()->countries->get_allowed_countries();

		if ( ! array_key_exists( $country, $allowed_countries ) ) {
			$country = current( array_keys( $allowed_countries ) );
		}
	}

	if ( 'shipping' === $load_address ) {
		$allowed_countries = WC()->countries->get_shipping_countries();

		if ( ! array_key_exists( $country, $allowed_countries ) ) {
			$country = current( array_keys( $allowed_countries ) );
		}
	}

	$address = WC()->countries->get_address_fields( $country, $load_address . '_' );

	// Enqueue scripts.
	wp_enqueue_script( 'wc-country-select' );
	wp_enqueue_script( 'wc-address-i18n' );

	// Prepare values.
	foreach ( $address as $key => $field ) {

		$value = get_user_meta( get_current_user_id(), $key, true );

		if ( ! $value ) {
			switch ( $key ) {
				case 'billing_email':
				case 'shipping_email':
				$value = $current_user->user_email;
			break;
			}
		}

		$address[ $key ]['value'] = apply_filters( 'woocommerce_my_account_edit_address_field_value', $value, $key, $load_address );
	}

	wc_get_template(
		'myaccount/form-edit-address.php',
		array(
			'load_address' => $load_address,
			'address'      => apply_filters( 'woocommerce_address_to_edit', $address, $load_address ),
		)
	);

}

add_shortcode('edit_address_shipping', 'edit_address');

add_action('wp_head', 'billing_address');
function billing_address(){
	?>

	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			var queryString = "?action=billing";
			var queryParams = new URLSearchParams(window.location.search)
			var action = queryParams.get('action');
			if(action == 'billing'){
			// alert(action);
			setTimeout(function() {
        			jQuery('.open-acc-sidebar a').trigger('click');
    			},10);
			}
			jQuery('.addr_main_form_content form').submit(function(e){
				e.stopPropagation();
				e.preventDefault();
				var form = $(this);
				var id = form.attr('id');
				var formData = $(this).serialize();
				var billing_first_name = $("#billing_first_name").val();
				var focusSet = false;


				// Biling First name
		        if (!$('#billing_first_name').val()) {
		            if ($("#billing_first_name").parent().next(".validation").length == 0) 
		            {
		                $("#billing_first_name").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter first name</div>");
		            }
		            e.preventDefault();
		            $('#billing_first_name').focus();
		            focusSet = true;
		            return false

		        } else {
		            $("#billing_first_name").parent().next(".validation").remove(); // remove it
		        }

		        // Biling Last name
		        if (!$('#billing_last_name').val()) {
		            if ($("#billing_last_name").parent().next(".validation").length == 0) 
		            {
		                $("#billing_last_name").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter last name</div>");
		            }
		            e.preventDefault();
		            $('#billing_last_name').focus();
		            focusSet = true;
		            return false
		        } else {
		            $("#billing_last_name").parent().next(".validation").remove(); // remove it
		        }

		        // Country 
		        // if (!$('#billing_country').val()) {
		        //     if ($("#billing_country").parent().next(".validation").length == 0) 
		        //     {
		        //         $("#billing_country").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please select country</div>");
		        //     }
		        //     e.preventDefault();
		        //     $('#billing_country').focus();
		        //     focusSet = true;
		        //     return false
		        // } else {
		        //     $("#billing_country").parent().next(".validation").remove(); // remove it
		        // }

		        // Billing Address 1
		        if (!$('#billing_address_1').val()) {
		            if ($("#billing_address_1").parent().next(".validation").length == 0) 
		            {
		                $("#billing_address_1").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter street address</div>");
		            }
		            e.preventDefault();
		            $('#billing_address_1').focus();
		            focusSet = true;
		            return false
		        } else {
		            $("#billing_address_1").parent().next(".validation").remove(); // remove it
		        }

		        // Billing Post code
		        if (!$('#billing_postcode').val()) {
		            if ($("#billing_postcode").parent().next(".validation").length == 0) 
		            {
		                $("#billing_postcode").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter zip code</div>");
		            }
		            e.preventDefault();
		            $('#billing_postcode').focus();
		            focusSet = true;
		            return false
		        } else {
		            $("#billing_postcode").parent().next(".validation").remove(); // remove it
		            var count = $('#billing_postcode').val().length;
		            
		            if(count > 6 ){
		            	$("#billing_postcode").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter a valid postcode / ZIP</div>");
		            	return false;
		            }
		            if(!($.isNumeric($('#billing_postcode').val()) )){
		            	$("#billing_postcode").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter only digit</div>");
		            	return false;
		            }
		        }

		        // Billing city
		        if (!$('#billing_city').val()) {
		            if ($("#billing_city").parent().next(".validation").length == 0) 
		            {
		                $("#billing_city").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter city</div>");
		            }
		            e.preventDefault();
		            $('#billing_city').focus();
		            focusSet = true;
		            return false
		        } else {
		            $("#billing_city").parent().next(".validation").remove(); // remove it
		        }

		        

		        

		        // Billing Email
		        if (!$('#billing_email').val()) {
		            if ($("#billing_email").parent().next(".validation").length == 0) 
		            {
		                $("#billing_email").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter email</div>");
		            }
		            e.preventDefault();
		            $('#billing_email').focus();
		            focusSet = true;
		            return false
		        } else {
		            $("#billing_email").parent().next(".validation").remove(); // remove it
		        }
		        

				$.ajax({
					type: "post",
					// dataType: 'json',
					url: "<?php echo admin_url('admin-ajax.php'); ?>",
					data: formData,
					success: function(data) {
						// console.log(data);
						if(data == 'success'){
							var queryString = "?action=billing";
							var queryParams = new URLSearchParams(window.location.search)
							var action = queryParams.get('action');
							if(action == 'billing'){
							setTimeout(function() {
				        			window.location.href = '/checkout/';
				    			},10);
							}else{
							$(".close_sider").after("<h3 style='color:green;margin-bottom: 20px;'>Address Update Successfully</h3>");
							setTimeout(function(){
							location.reload();

						}, 500);
							}
						}
						// if(data = 'bill_redirect'){
							
						// 	$(".close_sider").after("<h3 style='color:green;margin-bottom: 20px;'>Address Update Successfully</h3>");
						// 	setTimeout(function(){
						// 	window.location = "/checkout/";

						// }, 500);
						// }
						// bill_redirect
						
			      	}
			    });
			    return false;
			});


		});    
	</script>
	<?php
		if(is_user_logged_in()){ ?>
			<script type="text/javascript">
				jQuery(document).ready(function(){

					// $(".custom_billing_address").hide();
				});
			</script>	
		<?php } ?>		

<?php }
add_action('wp_head', 'shipping_address');
function shipping_address(){ ?>

	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			var queryString = "?action=shipping"
			var queryParams = new URLSearchParams(window.location.search)
			var action = queryParams.get('action');
			if(action == 'shipping'){
			// alert(action);
			setTimeout(function() {
        			jQuery('.open-ship-sidebar a').trigger('click');
    			},10);
			}
			jQuery('#wc-stripe-payment-token-new').trigger('click');
			jQuery('.ship_addr_form_content form').submit(function(e){
				e.stopPropagation();
				e.preventDefault();
				var form = $(this);
				var id = form.attr('id');
				var formData = $(this).serialize();
				var billing_first_name = $("#shipping_first_name").val();
				var focusSet = false;

				// Shipping First name
		        if (!$('#shipping_first_name').val()) {
		        		if ($(this).find("#shipping_first_name").parent().next(".validation").length == 0) 
		            {
		                $(this).find("#shipping_first_name").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter first name</div>");
		            }
		            e.preventDefault();
		            $(this).find('#shipping_first_name').focus();
		            focusSet = true;
		            return false;
		        } else {
		            $(this).find("#shipping_first_name").parent().next(".validation").remove(); // remove it
		        }

		        // Shipping Last name
		        if (!$(this).find('#shipping_last_name').val()) {
		            if ($(this).find("#shipping_last_name").parent().next(".validation").length == 0) 
		            {
		                $(this).find("#shipping_last_name").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter last name</div>");
		            }
		            e.preventDefault();
		            $(this).find('#shipping_last_name').focus();
		            focusSet = true;
		            return false;
		        } else {
		            $(this).find("#shipping_last_name").parent().next(".validation").remove(); // remove it
		        }

		        // Country 
		        // if (!$(this).find('#shipping_country').val()) {
		        //     if ($(this).find("#shipping_country").parent().next(".validation").length == 0) 
		        //     {
		        //         $(this).find("#shipping_country").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please select country</div>");
		        //     }
		        //     e.preventDefault();
		        //     $(this).find('#shipping_country').focus();
		        //     focusSet = true;
		        //     return false;
		        // } else {
		        //     $(this).find("#shipping_country").parent().next(".validation").remove(); // remove it
		        // }

		        // Shipping Address 1
		        if (!$('#shipping_address_1').val()) {
		            if ($("#shipping_address_1").parent().next(".validation").length == 0) 
		            {
		                $("#shipping_address_1").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter street address</div>");
		            }
		            e.preventDefault();
		            $('#shipping_address_1').focus();
		            focusSet = true;
		            return false;
		        } else {
		            $("#shipping_address_1").parent().next(".validation").remove(); // remove it
		        }

		        

		        // Shipping Post code
		        if (!$('#shipping_postcode').val()) {
		            if ($("#shipping_postcode").parent().next(".validation").length == 0) 
		            {
		                $("#shipping_postcode").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter zip code</div>");
		            }
		            e.preventDefault();
		            $('#shipping_postcode').focus();
		            focusSet = true;
		            return false;
		        } else {
		            $("#shipping_postcode").parent().next(".validation").remove(); // remove it
		            var count = $('#shipping_postcode').val().length;
		            
		            if(count > 6 ){
		            	$("#shipping_postcode").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter a valid postcode / ZIP</div>");
		            	return false;
		            }
		            if(!$.isNumeric($('#shipping_postcode').val()) ){
		            	$("#shipping_postcode").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter only digit</div>");
		            	return false;
		            }
		        }

		        // Shipping city
		        if (!$(this).find('#shipping_city').val()) {
		            if ($(this).find("#shipping_city").parent().next(".validation").length == 0) 
		            {
		                $(this).find("#shipping_city").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter city</div>");
		            }
		            e.preventDefault();
		            $(this).find('#shipping_city').focus();
		            focusSet = true;
		            return false;
		        } else {
		            $(this).find("#shipping_city").parent().next(".validation").remove(); // remove it
		        }
				// alert('yes');

		       	$.ajax({
					type: "post",
					// dataType: 'json',
					url: "<?php echo admin_url('admin-ajax.php'); ?>",
					data: formData,
					success: function(data) {
						console.log(data);
						if(data == 'success'){
							var queryString = "?action=shipping";
							var queryParams = new URLSearchParams(window.location.search)
							var action = queryParams.get('action');
							
							if(action == 'shipping'){
							setTimeout(function() {
				        			window.location.href = '/checkout/';
				    			},10);
							}else{
							$(".ship_close_sider").after("<h3 style='color:green;margin-bottom: 20px;'>Address Update Successfully</h3>");
							setTimeout(function(){
								location.reload();

							}, 500);
							}
							// return false;
						}
						// if(data == 'ship_redirect'){
						// 	$(".ship_close_sider").after("<h3 style='color:green;margin-bottom: 20px;'>Address Update Successfully</h3>");
						// 	setTimeout(function(){
						// 		window.location = "/checkout/";

						// 	}, 500);

						// }
			      }
			    });
			    return false;
			});    
		});    
	</script>

<?php }

add_action('wp_ajax_edit_address', 'billing_and_shipping_address_post');
add_action('wp_ajax_nopriv_edit_address', 'billing_and_shipping_address_post');
function billing_and_shipping_address_post() {
	// print_r($_POST);
	// exit;
	$billing_email      = $_POST['billing_email'];
   $billing_first_name = $_POST['billing_first_name'];
   $billing_last_name  = $_POST['billing_last_name'];
   $billing_country    = $_POST['billing_country'];
   $billing_address_1  = $_POST['billing_address_1'];
   $billing_address_2  = $_POST['billing_address_2'];
   $billing_city       = $_POST['billing_city'];
   $billing_state      = $_POST['billing_state'];
   $billing_postcode   = $_POST['billing_postcode'];
   $billing_phone   = $_POST['billing_phone'];
   // billing_phone
    
   $edit_id = $_POST['extra_ship_id']; 
	// if(!$edit_id){
	  // print_r($_POST);
	   $shipping_email      = $_POST['shipping_email'];
	   $shipping_first_name = $_POST['shipping_first_name'];
	   $shipping_last_name  = $_POST['shipping_last_name'];
	   $shipping_country    = $_POST['shipping_country'];
	   $shipping_address_1  = $_POST['shipping_address_1'];
	   $shipping_address_2  = $_POST['shipping_address_2'];
	   $shipping_city       = $_POST['shipping_city'];
	   $shipping_state      = $_POST['shipping_state'];
	   $shipping_postcode   = $_POST['shipping_postcode'];
	   $extra_ship_address   = $_POST['extra_ship_address'];

	   
	// }   

   global $wpdb;
	$tablename=$wpdb->prefix.'custom_shipping_address';
   
   if($extra_ship_address == 'extra_ship_address'){
   	$data=array(
      	'firstname' => $_POST['shipping_first_name'], 
        	'lastname' => $_POST['shipping_last_name'],
        	'shipping_address_1' => $_POST['shipping_address_1'], 
        	'shipping_address_2' => $_POST['shipping_address_2'],
        	'shipping_postcode' => $_POST['shipping_postcode'], 
        	'shipping_country' => $_POST['shipping_country'], 
        	'shipping_city' => $_POST['shipping_city'],
        	'shipping_state' => $_POST['shipping_state'],

        	'user_id' => get_current_user_id(),
		);
		$wpdb->insert( $tablename, $data);
     	exit;
   }

   $edit_id = $_POST['extra_ship_id'];
   if($edit_id){
   	$data123=array(
      	'firstname' => $_POST['shipping_first_name'], 
        	'lastname' => $_POST['shipping_last_name'],
        	'shipping_address_1' => $_POST['shipping_address_1'], 
        	'shipping_address_2' => $_POST['shipping_address_2'],
        	'shipping_postcode' => $_POST['shipping_postcode'], 
        	'shipping_country' => $_POST['shipping_country'], 
        	'shipping_city' => $_POST['shipping_city'],
        	'shipping_state' => $_POST['shipping_state'],
        	'user_id' => get_current_user_id(),
		);
   	$wpdb->update( $tablename, $data123, array( 'id' => $edit_id ));
   }
   // exit;
    

    // Billing Details Save
    if(isset($billing_email) && !empty($billing_email)){
		update_user_meta(get_current_user_id(),'billing_email',$billing_email); 
    }
    if(isset($billing_first_name) && !empty($billing_first_name)){
		update_user_meta(get_current_user_id(),'billing_first_name',$billing_first_name); 
    }
    if(isset($billing_last_name) && !empty($billing_last_name)){
		update_user_meta(get_current_user_id(),'billing_last_name',$billing_last_name); 
    }
    if(isset($billing_country) && !empty($billing_country)){
		update_user_meta(get_current_user_id(),'billing_country',$billing_country); 
    }
    if(isset($billing_address_1) && !empty($billing_address_1)){
		update_user_meta(get_current_user_id(),'billing_address_1',$billing_address_1); 
    }
    if(isset($billing_address_2) && !empty($billing_address_2)){
		update_user_meta(get_current_user_id(),'billing_address_2',$billing_address_2); 
    }
    
    if(isset($billing_city) && !empty($billing_city)){
		update_user_meta(get_current_user_id(),'billing_city',$billing_city); 
    }
    if(isset($billing_state) && !empty($billing_state)){
		update_user_meta(get_current_user_id(),'billing_state',$billing_state); 
    }
    if(isset($billing_postcode) && !empty($billing_postcode)){
		update_user_meta(get_current_user_id(),'billing_postcode',$billing_postcode); 
    }
    if(isset($billing_phone) && !empty($billing_phone)){
		update_user_meta(get_current_user_id(),'billing_phone',$billing_postcode); 
    }
    

    // Shipping Detail Save

    if(isset($shipping_email) && !empty($shipping_email)){
		update_user_meta(get_current_user_id(),'shipping_email',$shipping_email); 
    }
    if(isset($shipping_first_name) && !empty($shipping_first_name)){
		update_user_meta(get_current_user_id(),'shipping_first_name',$shipping_first_name); 
    }
    if(isset($shipping_last_name) && !empty($shipping_last_name)){
		update_user_meta(get_current_user_id(),'shipping_last_name',$shipping_last_name); 
    }
    if(isset($shipping_country) && !empty($shipping_country)){
		update_user_meta(get_current_user_id(),'shipping_country',$shipping_country); 
    }
    if(isset($shipping_address_1) && !empty($shipping_address_1)){
		update_user_meta(get_current_user_id(),'shipping_address_1',$shipping_address_1); 
    }
    if(isset($shipping_address_2) && !empty($shipping_address_2)){
		update_user_meta(get_current_user_id(),'shipping_address_2',$shipping_address_2); 
    }
    if(isset($shipping_city) && !empty($shipping_city)){
		update_user_meta(get_current_user_id(),'shipping_city',$shipping_city); 
    }
    if(isset($shipping_state) && !empty($shipping_state)){
		update_user_meta(get_current_user_id(),'shipping_state',$shipping_state); 
    }
    if(isset($shipping_postcode) && !empty($shipping_postcode)){
		update_user_meta(get_current_user_id(),'shipping_postcode',$shipping_postcode); 
    }
    // if($_SESSION['ship_redirect']){
    // 	echo 'ship_redirect';
    // }else{

    // 	echo 'success';
    // }
    // if($_SESSION['bill_redirect']){
    // 	echo 'bill_redirect';
    // }else{

    	echo 'success';
    // }
    
    die();
}

add_shortcode('display_payment_methods','display_payment_methods');  
function display_payment_methods(){
    global $woocommerce;
	
	$available_gatewayz = WC()->payment_gateways->get_available_payment_gateways();

	if ( $available_gatewayz ) { ?>
    	<form id="add_payment_method" method="post">
        	<div id="payment" class="woocommerce-Payment">
            	<ul class="woocommerce-PaymentMethods payment_methods methods">
	                <?php
	                // Chosen Method.
	                if ( count( $available_gatewayz ) ) {
	                    current( $available_gatewayz )->set_current();
	                }

                	foreach ( $available_gatewayz as $gatewayz ) { ?>
	                    <li class="woocommerce-PaymentMethod woocommerce-PaymentMethod--<?php echo esc_attr( $gatewayz->id ); ?> payment_method_<?php echo esc_attr( $gatewayz->id ); ?>">
	                        <input id="payment_method_<?php echo esc_attr( $gatewayz->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gatewayz->id ); ?>" <?php checked( $gatewayz->chosen, true ); ?> />
	                        <label for="payment_method_<?php echo esc_attr( $gatewayz->id ); ?>"><?php echo wp_kses_post( $gatewayz->get_title() ); ?> <?php echo wp_kses_post( $gatewayz->get_icon() ); ?></label>
	                        <?php
	                        if ( $gatewayz->has_fields() || $gatewayz->get_description() ) {
	                            echo '<div class="woocommerce-PaymentBox woocommerce-PaymentBox--' . esc_attr( $gatewayz->id ) . ' payment_box payment_method_' . esc_attr( $gatewayz->id ) . '" style="display: none;">';
	                            $gatewayz->payment_fields();
	                            echo '</div>';
	                        }
	                        ?>
	                    </li>
                    <?php } ?>
            	</ul>
        	</div>
    	</form>        
	<?php }
}	

add_action('template_redirect','custom_bridge');
function custom_bridge(){

	if(is_page('checkout')){
		if(!is_user_logged_in()){
			if(!isset($_SESSION['guest_checkout'])){
				wp_safe_redirect( site_url('checkout-login'));
			}
			
		}
	}	
	

}

add_filter('woocommerce_login_redirect', 'login_redirect');

function login_redirect($redirect_to) {
	if($_SESSION['came_from'] == 'checkout'){
		return wp_safe_redirect( site_url('checkout'));
		exit;
	}else{
		wp_safe_redirect( site_url( 'login' ) );
	}
	
}

add_action( 'init', 'custom_shipping_address_tbl' );

function custom_shipping_address_tbl(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'custom_shipping_address';

	$sql = "CREATE TABLE $table_name (
		id int(9) NOT NULL AUTO_INCREMENT,
		firstname varchar(100) NOT NULL,
		lastname varchar(100) NOT NULL,
		shipping_address_1 text NOT NULL,
		shipping_address_2 text NOT NULL,
		shipping_postcode varchar(100) NOT NULL,
		shipping_country varchar(100) NOT NULL,
		shipping_city varchar(100) NOT NULL,
		shipping_state varchar(100) NOT NULL,
		user_id varchar(100) NOT NULL,
		PRIMARY KEY  (id)
	);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

function woocommerce_checkout_update_user_meta( $order_id  ) {
		if ( ! empty( $_POST['vat_number'] ) ) {
        	update_post_meta( $order_id, 'vat_number', sanitize_text_field( $_POST['vat_number'] ) );
    	}
   
}
add_action( 'woocommerce_checkout_update_order_meta', 'woocommerce_checkout_update_user_meta', 10, 2 );

add_action( 'woocommerce_admin_order_data_after_billing_address', 'show_new_checkout_field_order', 10, 1 );
   
function show_new_checkout_field_order( $order ) {    
   $order_id = $order->get_id();
   if ( get_post_meta( $order_id, 'vat_number', true ) ) echo '<p><strong>vat Number:</strong> ' . get_post_meta( $order_id, 'vat_number', true ) . '</p>';
}
/*Cart shortcode*/
add_shortcode('shortcode_add_to_cart', 'shortcode_add_to_cart_func');
function shortcode_add_to_cart_func() {
	global $product;
	echo do_shortcode('[add_to_cart show_price="false" id="'.$product->get_id().'"]');
}
/**/
add_action('template_redirect', 'is_product_category_page');
function is_product_category_page(){
	if( is_product_category() ) {
		wp_safe_redirect(site_url().'/produkte');
	}
}	


