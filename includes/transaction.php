<?php

function yabi_create_invoice( $post_id )
{
	$response = 'FAIL';
	
	if( !empty( $post_id ) )
	{
		$order = wc_get_order( $post_id );	
		
		if( !empty( $order ) )
		{	
			$is_virtual = true;	
			$products = array();
					
			$person = get_post_meta( $post_id, 'yabi_order_meta', true );		
				
			$items = $order->get_items();
			
			foreach( $items as $item )
			{
				$data = $item->get_data();
				$total = $item->get_total();			
				$description = $item->get_name();
				$value = round( $item->get_subtotal(), 2 );
				$quantity = $item->get_quantity();
				$id = $item->get_product_id();				
				
				$product = wc_get_product( $id );
				if( !empty( $product ) && !$product->is_virtual() )	
				{
					$is_virtual = false;
				}
				
				if( isset( $data['taxes']['subtotal'] ) )
				{
					$tax = round( current( $data['taxes']['subtotal'] ), 2 );
				}
				else
				{
					$tax = round( $item->get_subtotal_tax(), 2 );
				}
				
				$percent = round( $tax * 100 / $total );
				
				$adjust = round( $total + $item->get_total_tax() );
				
				$theitem = array(
					'id' 			=> $id,
					'description' 	=> $description, 
					'value' 		=> $value, 
					'tax' 			=> $tax, 
					'quantity' 		=> $quantity,
					'percent' 		=> $percent,
					'adjust'		=> $adjust,	
				);
				
				array_push( $products, $theitem );
			}
			
			/* Coupons */
			$numitem = 0;
			$order_items = $order->get_items( 'coupon' );
			foreach( $order_items as $item_id => $item )
			{
				$order_discount_amount = wc_get_order_item_meta( $item_id, 'discount_amount', true );
				$order_discount_tax_amount = wc_get_order_item_meta( $item_id, 'discount_amount_tax', true );
				
				if( isset( $products[ $numitem ] ) )
				{
					$products[ $numitem ][ 'value' ] = floatval( $products[ $numitem ][ 'value' ] ) - floatval( $order_discount_amount );
					$products[ $numitem ][ 'tax' ] = floatval( $products[ $numitem ][ 'tax' ] ) - floatval( $order_discount_tax_amount );
					
					$products[ $numitem ][ 'percent' ] = round( $products[ $numitem ][ 'tax' ] * 100 / $products[ $numitem ][ 'value' ] );
					
					$verified = round( $products[ $numitem ][ 'value' ] * $products[ $numitem ][ 'percent' ] / 100, 2 );
					
					$difference = $products[ $numitem ][ 'tax' ] - $verified;
					
					if( abs( $difference ) > 0.5 )
					{
						$products[ $numitem ][ 'tax' ] = $verified;
						
						$products[ $numitem ][ 'value' ] = $products[ $numitem ][ 'adjust' ] - $products[ $numitem ][ 'tax' ];					
					}
				}			
				
				$numitem++;
			}		
			/***********/
			
			/* Shipping */		
			$total_shipping = $order->get_shipping_total();
			if( 0 < $total_shipping )
			{
				$tax_shipping = $order->get_shipping_tax();
				
				$percent = round( $tax_shipping * 100 / $total_shipping );
				
				$adjust = round( $total_shipping + $tax_shipping );
				
				$theitem = array(
					'id' 			=> '101',
					'description' 	=> 'Transporte', 
					'value' 		=> $total_shipping, 
					'tax' 			=> $tax_shipping, 
					'quantity' 		=> 1,
					'percent' 		=> $percent,
					'adjust'		=> $adjust,	
				);
				
				array_push( $products, $theitem );
			}		
			/************/
			
			$dueDate = date( "Y-m-d" ); 
			$dateTime = date( "Y-m-d" ) .'T'. date("H:i:s") .'Z';
			
			$payment = array(
				'id' => $post_id, 
				'dueDate' => $dueDate, 
				'dateTime' => $dateTime,
			);		
			
			$graphQL = yabi_create_invoice_graph( $person, $products, $payment, $is_virtual );
			
			$response = yabi_send_data( $graphQL );
			
			update_post_meta( $post_id, 'yabi_graphQL', $graphQL );
			update_post_meta( $post_id, 'yabi_response', $response );
			
			if( !empty( $response ) )
			{
				if( isset( $response[ 'errors' ] ) )
				{
					$response = json_encode( array( 'error' => 1, 'message' => $response[ 'errors' ][ 0 ][ 'message' ] ) );
				}
				elseif( isset( $response[ 'data' ][ 'createInvoice' ][ 'document' ][ 'uid' ] ) )
				{			
					$yabi_settings = get_option( 'yabi_settings' );
					$invoice_number = get_option( 'yabi_invoice_number' );
				
					$invoiceConsecutive = $yabi_settings[ 'invoice_name' ] . $invoice_number;
					
					$yabi_invoice_number = intval( $invoice_number ) + 1;
					
					update_option( 'yabi_invoice_number', $yabi_invoice_number, 'no' );
					
					$invoiceid = $response[ 'data' ][ 'createInvoice' ][ 'document' ][ 'uid' ];
					
					$yabi_invoice = array(
						'number' => $invoiceConsecutive,
						'serial' => $invoiceid
					);
					update_post_meta( $post_id, 'yabi_invoice', $yabi_invoice );
					
					$response = json_encode( array( 'error' => 0, 'message' => 'Success!. Invoice generate: '. $invoiceid ) );
				}
				elseif( isset( $response[ 'data' ][ 'createInvoice' ][ 'errors' ] ) )
				{
					$response = json_encode( array( 'error' => 1, 'message' => $response[ 'data' ][ 'createInvoice' ][ 'errors' ][ 0 ][ 'message' ] ) );
				}
				else
				{
					$response = json_encode( array( 'error' => 1, 'message' => 'Unexpected error, try again later or contact support.' ) );
				}
			}
		}
	}
	
	return $response;
}

function yabi_create_invoice_graph( $person, $products, $payment, $is_virtual )
{	
	$yabi_settings = get_option( 'yabi_settings' );
	$invoice_number = get_option( 'yabi_invoice_number' ); 
	
	$businessUnitUuid = $yabi_settings[ 'businessunituuid' ];
	$invoice_name = $yabi_settings[ 'invoice_name' ];
	$payment_type = $yabi_settings[ 'payment_type' ];
	
	$data_products = yabi_create_products( $products );
	$tax_subtotal = yabi_create_tax( $data_products );
	
	$datedue = '';
	if( 'CREDIT' == $payment_type )
	{
		$credit_days = $yabi_settings[ 'credit_days' ];
		
		$newcredit = date( "Y-m-d", strtotime( $payment[ 'dueDate' ] . " +$credit_days days" ) );
		
		$dateTime = $newcredit .'T'. date("H:i:s") .'Z';
		
		$datedue = 'paymentDueDate: "'. $dateTime .'"';	
	}
	
	$address = '';
	$identifier = '';
	$note = '';
	$observations = '';
	$personName = '';
	
	switch( $person[ 'type_person' ] )
	{
		case 'NATURAL_PERSON':
		
			$identifier = $person[ 'identifier' ];
		
			$personName = 'personName: {
				firstName: "'. $person[ 'name' ] .'"
				secondName: "'. $person[ 'second_name' ] .'"
				firstSurname: "'. $person[ 'lastname' ] .'"
				secondSurname: "'. $person[ 'second_last_name' ] .'"
			}';
		
			break;
			
		case 'LEGAL_ENTITY':
		
			$identifier = $person[ 'identifier' ] .'-'.  $person[ 'identifier_digit' ];
		
			$personName = 'personName: {
				corporateName: "'. $person[ 'commercialname' ] .'"
			}';

			break;		
	}
		
	if( !empty( $person[ 'note_name' ] ) && !empty( $person[ 'note_value' ] ) )
	{
		$note = 'note: {
			key: "'. $person[ 'note_name' ] .'"
		  	value: "'. $person[ 'note_value' ] .'"
		}';
	}	
	
	if( !empty( $person[ 'observations' ] ) )
	{
		$observations = 'notes: [{
			key: "Observations"
		  	value: "'. $person[ 'observations' ] .'"
		}]';
	}
	
	if( !empty( $person[ 'city' ] ) && !empty( $person[ 'address' ] ) && !empty( $person[ 'citycode' ] ) )
	{
		$country = 'CO';		
		if( !empty( $person[ 'country' ] ) )
		{
			$country = $person[ 'country' ];
		}
		
		$address = 'physicalLocation: {
			country: '. $country .'
			city: "'. $person[ 'city' ] .'"
			address: "'. $person[ 'address' ] .'"			
			postalCode: "'. $person[ 'citycode' ] .'"
		}';
	}
	
	
	$graphQL = 'mutation{
	createInvoice(
		document: {
			organizationalUnitId: "'. $businessUnitUuid .'"
			id:{
				number: '. $invoice_number .'
				prefix: "'. $invoice_name .'"
			}
			generalInformation: {
				currency: COP
				issueDateTime: "'. $payment[ 'dateTime' ] .'"
				acquirerEmail: "'. $person[ 'email' ] .'"
				operationCode: STD
				subtypeCode: SALES_INVOICE
			}
			documentLines: [
				'. $data_products[ 'graphQL' ] .'
			]
			documentParties: {
				accountingCustomerParty: {
					additionalAccountId: '. $person[ 'type_person' ] .'
					personId: {
						idType: '. $person[ 'type_document' ] .'
						identifier: "'. $identifier .'"
					}
					'. $personName .'
					'. $address .'
					contact: {
						email: "'. $person[ 'email' ] .'"
						telephone: "'. $person[ 'telephone' ] .'"
						'. $note .'
					}
				}
			}
			'. $observations .'
			taxDescription: [
				{
					taxName: CO_01
					taxAmount: "'. $data_products[ 'total_tax' ] .'"
					roundingAmount: "0"
					taxSubtotal: [
						'. $tax_subtotal .'
					]
				}
			]
			documentTotals: {
				lineExtensionAmount: "'. $data_products[ 'total_value' ] .'"
				taxExclusiveAmount: "'. $data_products[ 'total_value' ] .'"
				taxInclusiveAmount: "'. ( $data_products[ 'total_tax' ] + $data_products[ 'total_value' ] ) .'"
				payableAmount: "'. ( $data_products[ 'total_tax' ] + $data_products[ 'total_value' ] ) .'"
			}
			paymentDescription: {
				paymentMeans: {
					'. $datedue .'
					paymentMeanCode: CO_ZZZ
					paymentMeanId: '. $payment_type .'
					paymentTerms: {
						locale: ES_CO
						text: "'. $payment[ 'id' ] .'"
					}
				}
			}
		}
	)
	{	
		document{
			documentUid
			id
			uid
			billingPrefix{
				creationDate
				id
				prefix
				range{
					from
					to
				}
			}

			documentLines{
				description{
					locale
					text
				}
				itemDescription{
					additionalItemProperty{
						key
						values
					}
					brandName
					informationContentProviderParty{
						endpointId{
							agencyId
							id
							schemeId
						}
						identification{
							idType{
								code
								description
							}
							identifier
						}
						name{
							corporateName
							firstName
							firstSurname
							secondName
							secondSurname
						}
						partyTaxScheme{
							compStringId{
								idType{
									code
									description
								}
								identifier
							}
							partyLegalEntity{
								shareholders{
									identification{
										idType{
											code
											description
										}
										identifier
									}
									name{
										corporateName
										firstName
										firstSurname
										secondName
										secondSurname
									}
									participationPercent
									physicalLocation{
										address
										cityCode
										cityName
										countryCode
										countrySubentity
										countrySubentityCode
										description{
											key
											values
										}
										informationUri
										name
										postalCode
									}
								}
							}
							registrationAddress{
								address
								cityCode
								cityName
								countryCode
								countrySubentity
								countrySubentityCode
								description{
									key
									values
								}
								informationUri
								name
								postalCode
							}
							registrationName
							taxLevelCode
							taxScheme{
								code
								description
							}
						}
						physicalLocation{
							address
							cityCode
							cityName
							countryCode
							countrySubentity
							countrySubentityCode
							description{
								key
								values
							}
							informationUri
							name
							postalCode
						}
					}
					manufacturerParty{
						endpointId{
							agencyId
							id
							schemeId
						}
						identification{
							idType{
								code
								description
							}
							identifier
						}
						name{
							corporateName
							firstName
							firstSurname
							secondName
							secondSurname
						}
						partyTaxScheme{
							compStringId{
								idType{
									code
									description
								}
								identifier
							}
							partyLegalEntity{
								shareholders{
									identification{
										idType{
											code
											description
										}
										identifier
									}
									name{
										corporateName
										firstName
										firstSurname
										secondName
										secondSurname
									}
									participationPercent
									physicalLocation{
										address
										cityCode
										cityName
										countryCode
										countrySubentity
										countrySubentityCode
										description{
											key
											values
										}
										informationUri
										name
										postalCode
									}
								}
							}
							registrationAddress{
								address
								cityCode
								cityName
								countryCode
								countrySubentity
								countrySubentityCode
								description{
									key
									values
								}
								informationUri
								name
								postalCode
							}
							registrationName
							taxLevelCode
							taxScheme{
								code
								description
							}
						}
						physicalLocation{
							address
							cityCode
							cityName
							countryCode
							countrySubentity
							countrySubentityCode
							description{
								key
								values
							}
							informationUri
							name
							postalCode
						}
					}
					modelName
					originCountry{
						code
						description
					}
					standardItemId{
						id
						name
						schemeAgencyId
						schemeId
					}
				}
				lineExtensionAmount
				note{
					locale
					text
				}
				price{
					priceAmount
					baseQuantity
				}
				priceModifier{
					amount
					baseAmount
					chargeIndicator
					percentage
					reason{
						text
					}
					reasonCode{
						code
						description
					}
				}
				pricingReference{
					amount
					priceType{
						code
						description
					}
				}
				quantity
				unitCode{
					code
					description
				}
				uuid
			}
			documentModifiers{
				amount
				baseAmount
				chargeIndicator
				percentage
				reason{
					text
				}
				reasonCode{
					code
					description
				}
			}
			documentParties{
				accountingCustomerParty{
					additionalAccountId{
						code
						description
					}
				}
				accountingSupplierParty{
					additionalAccountId{
						code
						description
					}
				}
			}
			documentTotals{
				allowanceTotalAmount
				chargeTotalAmount
				lineExtensionAmount
				prePaidAmount
				taxExclusiveAmount
				taxInclusiveAmount
			}
			documentType
			generalInformation{
				acquirerEmail
				currency{
					code
					description
				}
				environment
				issueDateTime
			}
			id
			insertedAt
			organizationalUnitId
			paymentDescription{
				paymentMeans{
					paymentDueDate
					paymentMeanCode{
						code
						description
					}
					paymentMeanId{
						code
						description
					}
					paymentTerms{
						text
					}
				}
				prepayments{
					id
					instructionId
					paidAmount
					paidDateTime
				}
			}
			referencedDocuments{
				description{
					locale
					text
				}
				issueDate
				referenceId
				referenceType{
					description
					code
				}
			}
			taxDescription{
				taxName{
					code
					description
				}
				roundingAmount
				taxAmount
				taxSubtotal{
					baseUnitMeasureCode{
						code 
						description
					}
					perUnitAmount
					taxAmount
					percent
					
					taxableAmount
				}
			}
			transactions{
				documentId
				insertedAt
				requestedAt
				respondedAt
				transactionChannel
				transactionFrom
				transactionId
				transactionStage
				transactionStatus{
					message
					title
				}
				transactionTo
				transactionType
				updatedAt
			}
			updatedAt
			files{
				graphicalRepresentationHtml{
					data
					fileType
					filename
				}
				attachedDocument{
					data
					fileType
					filename
				}
			}
		}
		errors{
			helpText
			id
			language
			message
			subType
			title
			type
		}
		warnings{
			helpText
			id
			language
			message
			subType
			title
			type
		}
		notifications{
			helpText
			id
			language
			message
			subType
			title
			type
		}
	}
}';

	return $graphQL;
}

function yabi_create_products( $products )
{
	$data = array( 'graphQL' => '', 'total_quantity' => 0, 'total_tax' => 0, 'total_value' => 0, 'total_tax_type' => array(), 'total_value_type' => array() );
	
	$idsequence = 1;
	
	foreach( $products as $item )
	{	
		$data['total_quantity'] = $data['total_quantity'] + $item['quantity'];
		$data['total_tax'] = $data['total_tax'] + $item['tax'];
		$data['total_value'] = $data['total_value'] + $item['value'];
		
		if( !isset( $data['total_tax_type'][$item['percent']] ) )
		{
			$data['total_tax_type'][$item['percent']] = 0;
			$data['total_value_type'][$item['percent']] = 0;
		}
		
		$data['total_tax_type'][$item['percent']] = $data['total_tax_type'][$item['percent']] + $item['tax'];
		$data['total_value_type'][$item['percent']] = $data['total_value_type'][$item['percent']] + $item['value'];
		
		
		$basevalue = round( ( floatval( $item['value'] ) / intval( $item['quantity'] ) ), 2 );
	  
		$data[ 'graphQL' ] .= '{
			description: {
				locale: ES_CO
				text: "'. $item[ 'description' ] .'"
			}
			itemDescription: {
				standardItemId: {
					id: "'. $item[ 'id' ] .'"
					standardId: GTIN
				}
				incomeType: SELF_OWNED
			}
			lineExtensionAmount: "'. $item[ 'value' ] .'"
			lineId: '. $idsequence .'
			price: {
				priceAmount: "'. $basevalue .'"
				baseQuantity: 1
				unitCode: CO_ZZ
				priceType: COMMERCIAL_VALUE
			}
			quantity: "'. $item[ 'quantity' ] .'"
			taxDescription: [
				{
					taxName: CO_01
					taxAmount: "'. $item[ 'tax' ] .'"
					roundingAmount: "0"
					taxSubtotal: [
						{
							taxAmount: "'. $item[ 'tax' ] .'"
							percent: '. $item[ 'percent' ] .'
							taxableAmount: "'. $item[ 'value' ] .'"
						}
					]
				}
			]
			unitCode: CO_ZZ
		}';
		
		$idsequence++;
	}
	
	return $data;
}

function yabi_create_tax( $data_products )
{
	$tax_data = '';
	
	foreach( $data_products[ 'total_tax_type' ] as $percent => $value )
	{
		$tax_data .= '{
			taxAmount: "'. $value .'"
			percent: '. $percent .'			
			taxableAmount: "'. $data_products[ 'total_value_type' ][ $percent ] .'"
		 }';
	}
	
	return $tax_data;
}

function yabi_send_data( $graphQL )
{
	$result = false;
	$yabi_settings = get_option( 'yabi_settings' ); 
	
	$post_data = json_encode( array( 'query' => $graphQL ) );
	
	$headers = array(
		'Content-Type' 	=> 'application/json',
		'Authorization' => 'Bearer ' . $yabi_settings[ 'token' ],
	);
	
	$args = array(
		'body'        => $post_data,
		'timeout'     => '60',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => $headers,
		'cookies'     => array(),
	);
	
	$response = wp_remote_post( $yabi_settings[ 'url_client' ], $args );
	
	if( isset( $response[ 'body' ] ) )
	{
		if( !empty( $response[ 'body' ] ) )
		{
			return json_decode( $response[ 'body' ], true );
		}
		else
		{
			return 0;
		}
	}
	else
	{
		return 0;
	}
}
