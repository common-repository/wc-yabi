<?php

add_action( 'wp_ajax_yabi_generate_invoice', 'yabi_generate_invoice' );
add_action( 'wp_ajax_yabi_save_data', 'yabi_save_data' );


function yabi_generate_invoice()
{
	$response = 'FAIL';
	
	if( isset( $_POST[ 'post_id' ] ) )
	{
		$post_id = sanitize_key( $_POST[ 'post_id' ] );
		
		$response = yabi_create_invoice( $post_id );
	}
	
	echo $response;
	
	wp_die();
}

function yabi_save_data()
{
	if( isset( $_POST[ 'name' ] ) && isset( $_POST[ 'identifier' ] ) )
	{
		$post_id = sanitize_key( $_POST[ 'post_id' ] );
		$address = sanitize_text_field( $_POST[ 'address' ] );
		$email = sanitize_email( $_POST[ 'email' ] );
		$name = sanitize_text_field( $_POST[ 'name' ] );
		$lastname = sanitize_text_field( $_POST[ 'lastname' ] );
		$note_name = sanitize_text_field( $_POST[ 'note_name' ] );
		$note_value = sanitize_text_field( $_POST[ 'note_value' ] );
		$identifier = sanitize_text_field( $_POST[ 'identifier' ] );
		$observations = sanitize_text_field( $_POST[ 'observations' ] );
		$type_document = sanitize_text_field( $_POST[ 'type_document' ] );
		$telephone = sanitize_text_field( $_POST[ 'telephone' ] );
		$type_person = sanitize_text_field( $_POST[ 'type_person' ] );
		$commercialname = sanitize_text_field( $_POST[ 'commercialname' ] );
		$second_name = sanitize_text_field( $_POST[ 'second_name' ] );
		$second_last_name = sanitize_text_field( $_POST[ 'second_last_name' ] );
		$identifier_digit = sanitize_text_field( $_POST[ 'identifier_digit' ] );
		$city = sanitize_text_field( $_POST[ 'city' ] );
		$citycode = sanitize_text_field( $_POST[ 'citycode' ] );
		
		$person = array(
			'type_person' 		=> $type_person,			
			'address' 			=> $address, 
			'commercialname' 	=> $commercialname,
			'email' 			=> $email, 
			'identifier' 		=> $identifier,
			'lastname' 			=> $lastname, 
			'name' 				=> $name,
			'note_name'			=> $note_name,	
			'note_value' 		=> $note_value, 
			'observations' 		=> $observations,
			'telephone' 		=> $telephone,
			'type_document' 	=> $type_document,
			'second_name' 		=> $second_name,
			'second_last_name' 	=> $second_last_name,
			'identifier_digit' 	=> $identifier_digit,	
			'citycode'			=> $citycode,
			'city'				=> $city,		
		);
		
		update_post_meta( $post_id, 'yabi_order_meta', $person );
		
		echo 'GOOD';
	}	
	else
	{
		echo 'FAIL';
	}
	
	wp_die();
}