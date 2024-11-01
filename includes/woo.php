<?php 


function yabi_admin_billing_fields( $address ) 
{	
    $bc = $address[ 'city' ];
	
	$address[ 'city' ] = yabi_generate_values( $bc );

    return $address;
}

function yabi_checkout_fields( $checkout )
{
	global $yabi_thetypes;
	
	$field = array(
		'type' 			=> 'select',
		'required' 		=> true,
		'class' 		=> array(
			'form-row-wide',
		),
		'label' 		=> __( 'Select the type of person', 'yabi-wc' ),
		'options'		=> array(
			'NATURAL_PERSON'	=> __( 'Natural Person', 'yabi-wc' ),
			'LEGAL_ENTITY'		=> __( 'Legal Entity', 'yabi-wc' ),
		),
	);
	
	woocommerce_form_field( 'type_person', $field, $checkout->get_value( 'type_person' ) );
	
	$field = array(
		'type' 			=> 'text',
		'required' 		=> false,
		'class' 		=> array(
			'form-row-last',
		),
		'label' 		=> __( 'Second Name', 'yabi-wc' ),
	);
	
	woocommerce_form_field( 'second_name', $field, $checkout->get_value( 'second_name' ) );
	
	$field = array(
		'type' 			=> 'text',
		'required' 		=> false,
		'class' 		=> array(
			'form-row-last',
		),
		'label' 		=> __( 'Second Last Name', 'yabi-wc' ),
	);
	
	woocommerce_form_field( 'second_last_name', $field, $checkout->get_value( 'second_last_name' ) );
	
	$field = array(
		'type' 			=> 'select',
		'required' 		=> true,
		'class' 		=> array(
			'form-row-wide',
		),
		'label' 		=> __( 'Type of document', 'yabi-wc' ),
		'options'		=> $yabi_thetypes,
	);
	
	woocommerce_form_field( 'type_document', $field, $checkout->get_value( 'type_document' ) );
	
	$field = array(
		'type' 			=> 'number',
		'required' 		=> true,
		'class' 		=> array(
			'form-row-first',
		),
		'label' 		=> __( 'Identifier','yabi-wc' ),
	);
	
	woocommerce_form_field( 'identifier', $field, $checkout->get_value( 'identifier' ) );
	
	$field = array(
		'type' 			=> 'number',
		'required' 		=> false,
		'class' 		=> array(
			'form-row-last',
		),
		'label' 		=> __( 'Identifier digit', 'yabi-wc' ),
	);
	
	woocommerce_form_field( 'identifier_digit', $field, $checkout->get_value( 'identifier_digit' ) );
}

/** Validate field */
function yabi_checkout_fields_process()
{
	if( empty( $_POST[ 'type_person' ] ) )
	{
		wc_add_notice( __( 'Type of person is a required Field!', 'yabi-wc' ) , 'error' );
	}
	
	if( empty( $_POST[ 'type_document' ] ) )
	{
		wc_add_notice( __( 'Type of document is a required Field!', 'yabi-wc' ) , 'error' );
	}
	
	if( empty( $_POST[ 'identifier' ] ) )
	{
		wc_add_notice( __( 'Identifier is a required Field!', 'yabi-wc' ) , 'error' );
	}
	else
	{
		$identifier = sanitize_text_field( $_POST[ 'identifier' ] );
		 
		if ( !is_numeric( $identifier ) || $identifier <= 0 || strlen( $identifier ) < 5 ) 
		{
            wc_add_notice( __( 'Identifier field must contain a positive number of at least 5 digits.', 'yabi-wc' ), 'error' );
    	}
	}
}

/** Update the value field */
function yabi_checkout_fields_update_order_meta( $order_id )
{
	$yabi_order_meta = array(
		'type_person' 		=> '',
		'second_name' 		=> '',
		'second_last_name' 	=> '',
		'type_document' 	=> '',
		'identifier' 		=> '',
		'identifier_digit' 	=> '',
	);
	
	if( !empty( $_POST[ 'type_person' ] ) ) 
	{
		$yabi_order_meta[ 'type_person' ] = sanitize_text_field( $_POST[ 'type_person' ] );
	}
	
	if( !empty( $_POST[ 'second_name' ] ) ) 
	{
		$yabi_order_meta[ 'second_name' ] = sanitize_text_field( $_POST[ 'second_name' ] );
	}
	
	if( !empty( $_POST[ 'second_last_name' ] ) ) 
	{
		$yabi_order_meta[ 'second_last_name' ] = sanitize_text_field( $_POST[ 'second_last_name' ] );
	}
	
	if( !empty( $_POST[ 'type_document' ] ) ) 
	{
		$yabi_order_meta[ 'type_document' ] = sanitize_text_field( $_POST[ 'type_document' ] );
	}
	
	if( !empty( $_POST[ 'identifier' ] ) ) 
	{
		$yabi_order_meta[ 'identifier' ] = intval( sanitize_text_field( $_POST[ 'identifier' ] ) );
	}
	
	if( !empty( $_POST[ 'identifier_digit' ] ) ) 
	{
		$yabi_order_meta[ 'identifier_digit' ] = intval( sanitize_text_field( $_POST[ 'identifier_digit' ] ) );
	}
	
	$order = wc_get_order( $order_id );
			
	$note_name = '';
	$note_value = '';
	$name = $order->get_billing_first_name();
	$lastname = $order->get_billing_last_name();
	$address_1 = $order->get_billing_address_1();
	$address_2 = $order->get_billing_address_2();
	$address = trim( $address_1 .' '. $address_2 );
	$email = $order->get_billing_email();
	$citycode = $order->get_billing_postcode();
	$telephone = $order->get_billing_phone();
	$commercialname = $order->get_billing_company();
	$country = $order->get_billing_country();
	$city = $order->get_billing_city();
	
	$yabi_order_meta[ 'country' ] = $country;
	$yabi_order_meta[ 'address' ] = $address;
	$yabi_order_meta[ 'citycode' ] = $citycode;
	$yabi_order_meta[ 'commercialname' ] = $commercialname;
	$yabi_order_meta[ 'email' ] = $email;
	$yabi_order_meta[ 'name' ] = $name;
	$yabi_order_meta[ 'lastname' ] = $lastname;
	$yabi_order_meta[ 'note_name' ] = $note_name;
	$yabi_order_meta[ 'note_value' ] = $note_value;
	$yabi_order_meta[ 'telephone' ] = $telephone;
	$yabi_order_meta[ 'city' ] = $city;
	
	update_post_meta( $order_id, 'yabi_order_meta', $yabi_order_meta );
}

function yabi_city_dropdown_field( $fields ) 
{
	$city_args = wp_parse_args( array(
		'type' => 'select',
		'options' => yabi_generate_Codes(),
		'input_class' => array(
			'country_select',
			'wc-enhanced-select',
		)
	), $fields[ 'billing' ][ 'billing_city' ] );

	$fields[ 'billing' ][ 'billing_city' ] = $city_args;
	
	wc_enqueue_js( "
	jQuery( ':input.wc-enhanced-select' ).filter( ':not(.enhanced)' ).each( function() {
		var select2_args = { minimumResultsForSearch: 5 };
		jQuery( this ).select2( select2_args ).addClass( 'enhanced' );
	});" );
	
	$fields[ 'billing' ][ 'billing_country' ][ 'required' ] = false;
	$fields[ 'billing' ][ 'billing_city' ][ 'required' ] = false;
	$fields[ 'billing' ][ 'billing_state' ][ 'required' ] = false;
	$fields[ 'billing' ][ 'billing_address_1' ][ 'required' ] = false;
	$fields[ 'billing' ][ 'billing_postcode' ][ 'required' ] = false;
	
	return $fields;
}

function yabi_enqueue()
{
	wp_enqueue_style( 'checkout', YABI_PLUGIN_URL .'/scripts/checkout.min.css' );
	wp_enqueue_script( 'checkout', YABI_PLUGIN_URL .'/scripts/checkout.min.js' );
}

function yabi_payment_complete( $order_id, $old_status, $new_status )
{
	$yabi_settings = get_option( 'yabi_settings' );
	$yabi_invoice = get_post_meta( $order_id, 'yabi_invoice', true );
	
	if( 'Automatic' == $yabi_settings[ 'invoice_type' ] && empty( $yabi_invoice ) )
	{
		switch( $new_status )
		{
			case 'completed':
			
				yabi_create_invoice( $order_id );
			
				break;
				
			case 'processing':
			
				yabi_create_invoice( $order_id );
			
				break;
		}
	}
}

$yabi_settings = get_option( 'yabi_settings' );

if( isset( $yabi_settings[ 'modified_checkout' ] ) && 'Yes' === $yabi_settings[ 'modified_checkout' ] ):

	add_filter( 'woocommerce_checkout_fields', 'yabi_city_dropdown_field', 999999, 1 );
	add_filter( 'woocommerce_order_formatted_billing_address', 'yabi_admin_billing_fields' );
	add_action( 'woocommerce_before_checkout_billing_form', 'yabi_checkout_fields' );
	add_action( 'woocommerce_checkout_init', 'yabi_enqueue' );
	add_action( 'woocommerce_checkout_process', 'yabi_checkout_fields_process' );	
	add_action( 'woocommerce_checkout_update_order_meta', 'yabi_checkout_fields_update_order_meta', 10 );
	add_action( 'woocommerce_order_status_changed', 'yabi_payment_complete', 11, 3 );

endif;
