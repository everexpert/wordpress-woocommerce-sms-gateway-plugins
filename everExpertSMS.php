<?php @error_reporting(E_ERROR | E_WARNING | E_PARSE);
/*
Plugin Name: everexpert SMS plugin
Description: Extension for Woocommerce plugin to send Order SMS Notification.
Version: 1.0
Author: EverExpert
Author URI:http://www.everexpert.com/
*/


if ( ! defined( "ABSPATH" ) ) exit; // Exit if accessed directly
//hook sms_function to send SMS when checkout is completed
add_action( 'woocommerce_thankyou', array('sms_class', 'sms_function'));

add_action( 'woocommerce_order_status_completed', array(sms_class,'sms_function2'), 10, 1);	
class sms_class{
	 /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */

	 
	 
	public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_demo', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_demo', __CLASS__ . '::update_settings' );
		
    }
	
	  /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
	 public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_demo'] = __( 'SMS Settings', 'SMS-Settings' );
        return $settings_tabs;
    }

	/**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }
    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }
    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {
        $settings = array(
            'section_title' => array(
                'name'     => __( 'Section Title', 'SMS-Settings' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_SMS_Settings_section_title'
            ),
            'shop_name' => array(
                'name' => __( 'Shop Name', 'SMS-Settings' ),
                'type' => 'text',
                'desc' => __( 'Name of the Shop to show in sms', 'SMS-Settings' ),
                'id'   => 'shop_name',
                'default'   => get_option( "blogname" ,true)
            ),
            'api_url' => array(
                'name' => __( 'Gateway URL', 'SMS-Settings' ),
                'type' => 'text',
                'desc' => __( 'Please provide your gateway url', 'SMS-Settings' ),
                'id'   => 'wc_SMS_Settings_gateway'
            ),
            'username' => array(
                'name' => __( 'Gateway username', 'SMS-Settings' ),
                'type' => 'text',
                'desc' => __( 'Provide your SMS Gateway username', 'SMS-Settings' ),
                'id'   => 'wc_SMS_Settings_username'
            ),
            'password' => array(
                'name' => __( 'Gateway password', 'SMS-Settings' ),
                'type' => 'text',
                'desc' => __( 'Provide your SMS Gateway password', 'SMS-Settings' ),
                'id'   => 'wc_SMS_Settings_password'
            ),
            'lbl' => array(
                'name' => __( 'asdfaklsdfj'  ),
                'type' => 'title',
                'desc' => __( ''),
                'id'   => 'lbl'
            ),
            'sid' => array(
                'name' => __( 'Stakeholder ID', 'SMS-Settings' ),
                'type' => 'text',
                'desc' => __( 'Provide your SMS Gateway Stakeholder ID', 'SMS-Settings' ),
                'id'   => 'wc_SMS_Settings_sid'
            
			),
			'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_SMS_Settings_section_end'
            ),
            'hints' => array(
                'name' => __( 'You may copy paste from these tockens to use inside your text', 'SMS-Settings' ),
                'type' => 'title',
                'desc' => __( '{SHOP_NAME} , {CURRENCY_NAME} , {ORDER_NUMBER} , {ORDER_DATE} , {ORDER_STATUS} , {ORDER_ITEMS} , {BILLING_FNAME} , {BILLING_LNAME} , {BILLING_EMAIL} , {ORDER_AMOUNT} , {CURRENT_DATE} , {CURRENT_TIME}', 'SMS-Settings' ),
                
            
			),
			
            'customer_message_checkout_switch' => array(
                'name' => __( 'Send SMS on checkout', 'SMS-Settings' ),
                'type' => 'checkbox',
                'desc' => __( 'Enabled', 'SMS-Settings' ),
                'id'   => 'wc_SMS_Settings_checkout_Switch',
				
				'default' => 'yes'
            ),
            'customer_message_checkout' => array(
                'name' => __( 'Message (checkout)', 'SMS-Settings' ),
                'type' => 'textarea',
                'desc' => __( ''
							, 'SMS-Settings' ),
                'id'   => 'wc_SMS_Settings_checkout',
				
                'css'   => 'width: 400px;
							min-height: 100px;',
				'default' => 'Dear {BILLING_FNAME}, thank you for shopping from {SHOP_NAME}. your bill is {ORDER_AMOUNT}'
            ),
			
            'customer_message_processed_switch' => array(
                'name' => __( 'Send SMS after order Complete', 'SMS-Settings' ),
                'type' => 'checkbox',
                'desc' => __( 'Enabled', 'SMS-Settings' ),
                'id'   => 'customer_message_processed_Switch',
				
				'default' => 'yes'
            ),
            'customer_message_processed' => array(
                'name' => __( 'Message (order completed)', 'SMS-Settings' ),
                'type' => 'textarea',
                'desc' => __( ''
						, 'SMS-Settings' ),
                'id'   => 'wc_SMS_Settings_delivered',
                'css'   => 'width: 400px;
							min-height: 100px;',
				'desc_tip' => true,
				'default' => 'Dear {BILLING_FNAME}. your order from {ORDER_DATE} is {ORDER_STATUS}'
            ),
            'section_end2' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_SMS_Settings_section_end2'
            )
            
        );
        return apply_filters( 'wc_SMS_Settings_settings', $settings );
    }

	
	
	/*
	Function to integrate SMS Gateway 
	
	*/	
	

 public static function sms_function($order_id)
 {

	$order = wc_get_order($order_id);
	
	
	$SMS_admin_username=get_option( 'wc_SMS_Settings_username', true );
	$SMS_admin_password=get_option( 'wc_SMS_Settings_password', true );
	$SMS_admin_url=get_option( 'wc_SMS_Settings_gateway', true );
	$SMS_admin_sid=get_option( 'wc_SMS_Settings_sid', true );
	
	//$SMS_send_Processed=get_option( 'customer_message_processed_Switch', true );
	$SMS_send_Checkuot=get_option( 'wc_SMS_Settings_checkout_Switch', true );
	
	//$SMS_admin_delivered=get_option( 'wc_SMS_Settings_delivered', true );
	$SMS_admin_checkout=get_option( 'wc_SMS_Settings_checkout', true );
	$billing_phone=$order->get_billing_phone();
	//$SMS_admin_=;
	
	
	
	if(isset($billing_phone) and strlen($billing_phone)>0 and $SMS_send_Checkuot=="yes"){
	
		//checking if the page was refreshed
		$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

		if($pageWasRefreshed ) {
		   //page was refreshed so we won't send the sms
		} else {
			//first time visit after checkout 
			self::sendText($SMS_admin_username,$SMS_admin_password,$SMS_admin_sid,$SMS_admin_url,self::tokenizeSMS($SMS_admin_checkout,$order),$billing_phone);
	
		}	
	}
	
	
	
	
	
	

	}
	/*
	call this when order status set to completed
	
	*/
 public static function sms_function2($order_id)
 {
	
	$order = wc_get_order($order_id);

	
	$SMS_admin_username=get_option( 'wc_SMS_Settings_username', true );
	$SMS_admin_password=get_option( 'wc_SMS_Settings_password', true );
	$SMS_admin_url=get_option( 'wc_SMS_Settings_gateway', true );
	$SMS_admin_sid=get_option( 'wc_SMS_Settings_sid', true );
	
	$SMS_send_Processed=get_option( 'customer_message_processed_Switch', true );
	
	
	$SMS_admin_delivered=get_option( 'wc_SMS_Settings_delivered', true );

	$billing_phone=$order->get_billing_phone();
	
	
	
	if(isset($billing_phone) and strlen($billing_phone)>0 and $SMS_send_Processed=="yes"){
	self::sendText($SMS_admin_username,$SMS_admin_password,$SMS_admin_sid,$SMS_admin_url,self::tokenizeSMS($SMS_admin_delivered,$order),$billing_phone);
		
	}
	
	}
	
  public static function tokenizeSMS($content, $order){
	  
			if( !$content || !is_object($order))
			return;
	
			
			$order_id = $order->id;
			
			$order_custom_fields = get_post_custom($order_id);
			$current_date_time = current_time( 'timestamp' );
			
			if( preg_match("/{SHOP_NAME}/i", $content) )
			{ 
				$SHOP_NAME = get_option( "shop_name" ,true);
				$content = @str_replace( "{SHOP_NAME}", $SHOP_NAME, $content );
			}
			
			if( preg_match("/{ORDER_NUMBER}/i", $content) )
			{
				$ORDER_NUMBER = isset( $order_id ) ? $order_id : "";
				$content = @str_replace( "{ORDER_NUMBER}", $ORDER_NUMBER, $content );
			}
			
			if( preg_match("/{ORDER_DATE}/i", $content) )
			{
				$order_date_format = get_option( "date_format" );
				$ORDER_DATE = date_i18n($order_date_format, strtotime( $order->order_date ) );
				$content = @str_replace( "{ORDER_DATE}", $ORDER_DATE, $content );
			}
			
			if( preg_match("/{CURRENCY_NAME}/i", $content) )
			{
				$CURRENCY_NAME = $order->currency;
				
				$content = @str_replace( "{CURRENCY_NAME}", $CURRENCY_NAME, $content );
			}
			
			if( preg_match("/{ORDER_STATUS}/i", $content) )
			{
				$ORDER_STATUS = @ucfirst($order->status);
				$content = @str_replace( "{ORDER_STATUS}", $ORDER_STATUS, $content );
			}
			
			if( preg_match("/{ORDER_ITEMS}/i", $content) )
			{
				$order_items = $order->get_items( apply_filters( "woocommerce_admin_order_item_types", array( "line_item" ) ) );
				$ORDER_ITEMS = "";
				if( count($order_items) )
				{
					$item_cntr = 0;
					foreach ( $order_items as $order_item ) {
						if($order_item["type"]=="line_item")
						{
							if($item_cntr==0)
								$ORDER_ITEMS = $order_item["name"];
							else 
								$ORDER_ITEMS .= ", ". $order_item["name"];
							$item_cntr++;
						}
					}
				}
				
				$content = @str_replace( "{ORDER_ITEMS}", $ORDER_ITEMS, $content );
			}
			
			if( preg_match("/{BILLING_FNAME}/i", $content) )
			{
				$BILLING_FNAME = $order_custom_fields["_billing_first_name"][0];
				$content = @str_replace( "{BILLING_FNAME}", $BILLING_FNAME, $content );
			}
			
			if( preg_match("/{BILLING_LNAME}/i", $content) )
			{
				$BILLING_LNAME = $order_custom_fields["_billing_last_name"][0];
				$content = @str_replace( "{BILLING_LNAME}", $BILLING_LNAME, $content );
			}
			
			if( preg_match("/{BILLING_EMAIL}/i", $content) )
			{
				$BILLING_EMAIL = $order_custom_fields["_billing_email"][0];
				$content = @str_replace( "{BILLING_EMAIL}", $BILLING_EMAIL, $content );
			}
			
			if( preg_match("/{ORDER_AMOUNT}/i", $content) )
			{
				$ORDER_AMOUNT = $order_custom_fields["_order_total"][0];
				$content = @str_replace( "{ORDER_AMOUNT}", $ORDER_AMOUNT, $content );
			}
			
			if( preg_match("/{CURRENT_DATE}/i", $content) )
			{
				$wp_date_format = get_option( "date_format" );
				$CURRENT_DATE = date_i18n($wp_date_format, $current_date_time );
				$content = @str_replace( "{CURRENT_DATE}", $CURRENT_DATE, $content );
			}
			
			if( preg_match("/{CURRENT_TIME}/i", $content) )
			{
				$wp_time_format = get_option( "time_format" );
				$CURRENT_TIME = date_i18n($wp_time_format, $current_date_time );
				$content = @str_replace( "{CURRENT_TIME}", $CURRENT_TIME, $content );
			}
			
			return $content;
		
	  
  }
  public static function sendText($user,$pass,$sid,$url,$message,$billing_phone){
	
	
	/*
	sending SMS through curl
 
	 */
		
		
		$param="user=$user&pass=$pass&sms[0][0]=$billing_phone&sms[0][1]=".urlencode($message)."&sms[0][2]=$sid".time()."&sid=$sid";
		$crl = curl_init();
		curl_setopt($crl,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($crl,CURLOPT_SSL_VERIFYHOST,2);
		curl_setopt($crl,CURLOPT_URL,$url);
		curl_setopt($crl,CURLOPT_HEADER,0);
		curl_setopt($crl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($crl,CURLOPT_POST,1);
		curl_setopt($crl,CURLOPT_POSTFIELDS,$param);
		$response = curl_exec($crl);
		curl_close($crl);
			
		 
		//echo $message; 
		

		/*
		saving sms info to database
		*/
	global $wpdb;	
	
	$wpdb->insert( 
		$wpdb->prefix .'SMSinfo', 
		array( 
			'order_id'   => $order->id,
			'gateway_response' => $response
		)
	);


	//$record_id = $wpdb->insert_id;
		
  }
   

 
 /**
database for sms on plugin activation

 **/
 

}
sms_class::init();


/*
custom table for sms info
*/
 function SMS_init() {

	global $wpdb;
	

	$table_name = $wpdb->prefix . 'SMSinfo';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		order_id bigint,
		time timestamp,
		gateway_response varchar(1024)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
 
}
register_activation_hook( __FILE__, 'SMS_init' );