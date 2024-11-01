<?php
/** 
 *
 * @since             1.0.0
 * @package           yabi_wc
 *
 * @wordpress-plugin
 * Plugin Name:       Integrate Yabi for WooCommerce
 * Plugin URI:        https://mireunion.com/yabi
 * Description:       Create your electronic invoices of purchases made in woocommerce with Yabi
 * Version:           3.0.5
 * Author:            Mex Avila
 * Author URI:        https://datakun.com/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       yabi-wc
 * Domain Path:       /languages
 * Network:			  true
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

global $yabi_thetypes;

$yabi_thetypes = array( 
	'CC' 	=> 'Cédula de ciudadanía',
	'NIT' 	=> 'NIT',
	'TI' 	=> 'Tarjeta de identidad',
	'TE' 	=> 'Tarjeta de extranjería',
	'CE' 	=> 'Cédula de extranjería',
	'P' 	=> 'Pasaporte',
	'RC'	=> 'Registro Civil',
	'DIE' 	=> 'Documento de identificación extranjero',
	'NITE'	=> 'Número de Identificación Tributaria Extranjera',
	'NUIP' 	=> 'Número Único de Identificación Personal'
);

define( 'YABI_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'YABI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

load_plugin_textdomain(
	'yabi-wc', 
	false, 
	basename( dirname( __FILE__ ) ) . '/languages' 
);

require_once( YABI_PLUGIN_PATH . 'includes/ajax.php' );
require_once( YABI_PLUGIN_PATH . 'includes/dian.php' );
require_once( YABI_PLUGIN_PATH . 'includes/transaction.php' );
require_once( YABI_PLUGIN_PATH . 'includes/woo.php' );

function yabi_admin()
{
 	add_submenu_page( 'woocommerce', 'Yabi', 'Yabi', 'manage_options', 'yabi', 'yabi_page' );
}
add_action( 'admin_menu', 'yabi_admin' );

function yabi_page()
{
	$default_tab = null;
	$tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $default_tab;
	$ok = false;

	if( isset( $_POST[ 'invoice_name' ] ) )
	{
		yabi_settings();
		$ok = true;
	}
	
	require_once( YABI_PLUGIN_PATH . 'content/admin.php' );
}

function yabi_product()
{
	global $post;
	
	if( isset( $post->post_status ) && $post->post_status == 'wc-completed' )
	{
		add_meta_box( 'yabi-product', __( 'Yabi - Invoice Data','yabi-wc' ), 'yabi_product_data', 'shop_order', 'normal' );	
	}
}
add_action( 'add_meta_boxes', 'yabi_product' );

function yabi_product_data()
{
	global $post;
	
	$yabi_invoice = get_post_meta( $post->ID, 'yabi_invoice', true );
	
	$typesperson = array(
		'LEGAL_ENTITY' 		=> 'Persona Jurídica',
		'NATURAL_PERSON' 	=> 'Persona Natural'
	);
	$diancodes = yabi_generate_Codes();
	$person = get_post_meta( $post->ID, 'yabi_order_meta', true );
	
	if( empty( $yabi_invoice ) )
	{			
		if( empty( $person ) )
		{
			$order = wc_get_order( $post->ID );
			
			$note_name = '';
			$note_value = '';
			$name = $order->get_billing_first_name();
			$lastname = $order->get_billing_last_name();
			$address_1 = $order->get_billing_address_1();
			$address_2 = $order->get_billing_address_2();
			$address = $address_1 .' '. $address_2;
			$email = $order->get_billing_email();
			$citycode = $order->get_billing_postcode();
			$telephone = $order->get_billing_phone();
			$city = $order->get_billing_city();
			
			$identifier = '';
			$type_document = 'CC';
			$type_person = 'NATURAL_PERSON';
			$commercialname = '';
			
			$person = array(
				'citycode' 			=> $citycode, 
				'address' 			=> $address, 
				'email' 			=> $email, 
				'name' 				=> $name, 
				'lastname' 			=> $lastname, 
				'note_name' 		=> $note_name, 
				'note_value' 		=> $note_value, 
				'identifier' 		=> $identifier,
				'type_document' 	=> $type_document,
				'telephone' 		=> $telephone,
				'type_person' 		=> $type_person,
				'commercialname'	=> $commercialname,
				'city'				=> $city,
			);
		}
		
		require_once( YABI_PLUGIN_PATH . 'content/product.php' );
	}
	elseif( !empty( $person ) )
	{
		require_once( YABI_PLUGIN_PATH . 'content/product-invoice.php' );
	}
	else
	{
		$person = get_post_meta( $post->ID, 'yabi_person', true );
		
		if( !empty( $person ) )
		{
			require_once( YABI_PLUGIN_PATH . 'content/product-invoice-old.php' );
		}
	}
}

function yabi_settings()
{
	$invoice_name = sanitize_text_field( $_POST[ 'invoice_name' ] );
	$invoice_number = sanitize_text_field( $_POST[ 'invoice_number' ] );
	$owner = sanitize_key( $_POST[ 'owner' ] );
	$businessunituuid = sanitize_key( $_POST[ 'businessunituuid' ] );
	$token = sanitize_key( $_POST[ 'token' ] );
	$url_client = esc_url_raw( $_POST[ 'url_client' ] );
	$payment_type = sanitize_text_field( $_POST[ 'payment_type' ] );
	$invoice_type = sanitize_text_field( $_POST[ 'invoice_type' ] );
	$credit_days = sanitize_key( $_POST[ 'credit_days' ] );
	$modified_checkout = sanitize_text_field( $_POST[ 'modified_checkout' ] );
	
	$yabi_settings = array(
		'invoice_name' 			=> $invoice_name,
		'owner' 				=> $owner,
		'businessunituuid' 		=> $businessunituuid,
		'token' 				=> $token,
		'url_client' 			=> $url_client,
		'payment_type' 			=> $payment_type,
		'invoice_type' 			=> $invoice_type,
		'credit_days'			=> $credit_days,
		'modified_checkout'		=> $modified_checkout,
	);
	
	update_option( 'yabi_settings', $yabi_settings, 'yes' );	
	update_option( 'yabi_invoice_number', $invoice_number, 'no' );
}

function yabi_settings_link( $links ) 
{
	$url = esc_url( get_admin_url() . 'admin.php?page=yabi&tab=settings' );	
	$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
	
	array_push(
		$links,
		$settings_link
	);
	
	return $links;
}
add_filter( 'plugin_action_links_yabi/yabi.php', 'yabi_settings_link' );

function yabi_settings_notice()
{
	if( false === get_option( 'yabi_settings' ) )
	{
		$url = esc_url( get_admin_url() . 'admin.php?page=yabi&tab=settings' );	
		$settings_link = " <a href='$url'>" . __( 'Settings' ) . '</a>';
		
		echo '<div class="notice notice-warning is-dismissible"><p>';
		echo __( 'Please enter the Yabi plugin configuration, as you need to enter new data for it to work correctly','yabi-wc' ) . $settings_link;
		echo '</p></div>';
	}
}
add_action( 'admin_notices', 'yabi_settings_notice' );
