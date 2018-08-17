<?php
	$message_template		= array();
	$message_quickreplies	= array();
	
	if( $dialogflow_object->get_intentName() == "Booking.Bike.Insurance" )
	{
		$message_text[0]		= "Insurance covers the motorbike you have rented with us against theft and all damages to the motorbike with an excess of $95.";
		$message_text[1]		= "Our terms and conditions apply.";
	}
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Difference-Delivery-Pickup"  )
	{
		$message_text[0]		= "Delivery time is what time we will deliver the bike at the start of the rental period.";
		$message_text[1]		= "Pick up time is what time we will pick up the bike at the end of your rental.";
	}
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Cancel-Policy" )
	{
		$message_text[0]		= "If you add the cancellation insurance to your order, we can cancel and refund your order costs, minus the cancellation insurance charge.";
		$message_text[1]		= "If you do not take the cancellation insurance, we can cancel your order but we can not refund the charges.";
	}
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Cancel.Booking" )
	{
		$message_text[0]		= "According to our terms and conditions this is not allowed, however if you added our Cancellation Insurance to your order, we can refund the full amount, minus the charges for the Cancellation Insurance.";
		$message_text[1]		= "If you have not taken the Cancellation Insurance package prior, or within 24 hours after the booking was made, and want to cancel, the cost for the Cancellation Insurance will be charged 200%.";
	}
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Price.High" )
	{
		$message_text[0]		= "We provide you the best possible quality and highly reliable services. We offers you: \n * Accurate motorbike delivery and pickup services \n * 24/7/365 road side assistance team \n * Brand new and safe motorbikes and scooters that are vigorously checked on 19 points before delivery.";
	}
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Contact"  )
	{
		$message_text[0]		= "Our email :\ninfo@balibikerental.com.\nContact number :\n+62 855 7467 9030";
	}
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Enquiry.Price" )
	{
		//starting price for bike
		$event_param_object 			= new event_param( $BRAND_ID , $SENDER_ID , $EVENT_ID ) ;
		$submit_dialogflow_result		= json_decode( $submit_dialogflow_result , 1 );
		
		if ( $submit_dialogflow_result["result"]["parameters"]["bike"] == 1 )
		{
			$message_text[0]	= "The price for Honda Vario 125cc starts from $8.95 USD per day and insurance is $3.95 USD per day and deposit is $95 USD per bike.";
		}
		if ( $submit_dialogflow_result["result"]["parameters"]["bike"] == 2 )
		{
			$message_text[0]	= "The price for Yamaha R15 150cc starts from $18.95 USD per day and insurance is $9.90 USD per day and deposit is $95 USD per bike.";
		}
		if ( $submit_dialogflow_result["result"]["parameters"]["bike"] == 5 )
		{
			$message_text[0]	= "The price for Kawasaki KLX 150cc starts from $17.95 USD per day and insurance is $9.90 USD per day and deposit is $95 USD per bike.";
		}
		if ( $submit_dialogflow_result["result"]["parameters"]["bike"] == 13 )
		{
			$message_text[0]	= "The price for Honda Scoopy 110cc starts from $8.95 USD per day and insurance is $3.95 USD per day and deposit is $95 USD per bike.";
		}
	}
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Welcome"  )
	{
		$message_text[0]		= "We provide bike booking for :\n1. Honda Vario \n2. Honda Scoopy \n3. Yamaha R15 \n4. Kawasaki KLX";
	}
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Enquiry" )
	{
		$event_param_object 			= new event_param( $BRAND_ID , $SENDER_ID , $EVENT_ID ) ;
		$submit_dialogflow_result		= json_decode( $submit_dialogflow_result , 1 );
		
		if(count( $submit_dialogflow_result["result"]["contexts"] ) > 0 )
		{
			$event_param_lastindex = 0 ;
		
			for( $i = 0 ; $i < count( $submit_dialogflow_result["result"]["contexts"] ) ; $i++ )
			{ 
				if( $submit_dialogflow_result["result"]["contexts"][$i]["name"] == "bookingbikeenquiry-followup-2" )
				{
					$event_param_lastindex = $i ; // count( $submit_dialogflow_result["result"]["contexts"] ) - 2 ;
					break;
				}  
			}
			$dialogflow_param = $submit_dialogflow_result["result"]["contexts"][ $event_param_lastindex ]["parameters"];
				
			if( isset( $dialogflow_param["delivery_date"] ) )
			{ 
				$array_delivery =  array_merge(
								$event_param_object -> get_parameter("delivery"),
								array(
										"date" => $event_param_object -> get_formatedParameter( $dialogflow_param["delivery_date"] , "date" ) ,
										"time" => $event_param_object -> get_formatedParameter( $dialogflow_param["delivery_time"] , "time" ),
										"location_id" => null,
										"location_name" => null
									)
							);
				$event_param_object -> set_parameter( "delivery" , $array_delivery);
			}  
			if( isset( $dialogflow_param["pickup_date"] ) )
			{
				$array_pickup =  array_merge(
							$event_param_object -> get_parameter("pickup"),
							array(
									"date" => $event_param_object -> get_formatedParameter( $dialogflow_param["pickup_date"] , "date" ) ,
									"time" => $event_param_object -> get_formatedParameter( $dialogflow_param["pickup_time"] , "time" ),
									"location_id" => null,
									"location_name" => null
								)
						);
				$event_param_object -> set_parameter( "pickup" , $array_pickup);
			}	
			$event_param_object-> insert_record();
		}
		$message_text[0] = $dialogflow_object->get_speech();
	}
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Enquiry.Location" )
	{
		// how to set param
		$event_param_object 			= new event_param( $BRAND_ID , $SENDER_ID , $EVENT_ID ) ;
		$submit_dialogflow_result		= json_decode( $submit_dialogflow_result , 1 );
	
		if(count( $submit_dialogflow_result["result"]["contexts"] ) > 0 )
		{
			$event_param_lastindex = 0 ;
			
			// to prevent the change of order of the item.
			for( $i = 0 ; $i < count( $submit_dialogflow_result["result"]["contexts"] ) ; $i++ )
			{ 
				if( $submit_dialogflow_result["result"]["contexts"][$i]["name"] == "bookingbikeenquirylocation-followup" )
				{
					$event_param_lastindex = $i ; // count( $submit_dialogflow_result["result"]["contexts"] ) - 2 ;
					break;
				}  
			}
			$dialogflow_param = $submit_dialogflow_result["result"]["contexts"][ $event_param_lastindex ]["parameters"];
			
			$array_location = 
				array(
						0 => "unknown", 				1 => "Kuta",		2 => "Airport",
						3 => "International Airport",	4 => "Jimbaran",	5 => "Seminyak Office",
						6 => "Nusa Dua Office" ,		7 => "Sanur",		8 => "Denpasar",
						9 => "Tanah Lot",				10 => "Canggu", 	11 => "Tabanan",
						12 => "Ubud",					13 => "Gianyar",	14 => "Klungkung",
						15 => "Padangbai",				16 => "Candidasa",	17 => "Amed",
						18 => "Culik",					19 => "Medewi", 	20 => "Seraya",
						21 => "Pekutatan",				22 => "Bedugul", 	23 => "Gilimanuk",
						24 => "Singaraja",				25 => "Negara",		26 => "Lovina",
						27 => "Legian" ,				28 => "Nusa Dua",	29 => "Seminyak",
						30 => "Kerobokan",				31 => "Hard Rock Hotel Bali"
					);
					
			$temp_delivery_location = 0;
			if( is_array($dialogflow_param["delivery_location"]) )
			{   
				if( count( $dialogflow_param["delivery_location"] ) > 0)
				{
					
					$temp_delivery_location = $dialogflow_param["delivery_location"][0];
				} 
			}
			elseif( $dialogflow_param["delivery_location"] != null )
			{ 
				$temp_delivery_location = $dialogflow_param["delivery_location"];
			}
			
			$temp_pickup_location = 0;
			if( is_array( $dialogflow_param["pickup_location"] ) )
			{ 
				if( count( $dialogflow_param["pickup_location"] ) > 0)
				{ 
					$temp_pickup_location = $dialogflow_param["pickup_location"][0];
				} 
			}
			else if( $dialogflow_param["pickup_location"] != null )
			{ 
				$temp_pickup_location = $dialogflow_param["pickup_location"];
			} 
			
			if( isset( $dialogflow_param["delivery_location"] ) )
			{ 
				$array_delivery =  array_merge(
								$event_param_object -> get_parameter("delivery"),
								array(
										"date" => $event_param_object-> get_parameter("delivery")["date"] ,
										"time" => $event_param_object-> get_parameter("delivery")["time"] ,
										"location_id" => $temp_delivery_location ,
										"location_name" => ( $temp_delivery_location > 0 && $temp_delivery_location < 33 ? $array_location[ $temp_delivery_location ] : $array_location[ 0 ] )
									)
							);
				$event_param_object -> set_parameter( "delivery" , $array_delivery);
			}  
			if( isset( $dialogflow_param["pickup_location"] ) )
			{
				$array_pickup =  array_merge(
							$event_param_object -> get_parameter("pickup"),
							array(
									"date" => $event_param_object-> get_parameter("pickup")["date"] ,
									"time" => $event_param_object-> get_parameter("pickup")["time"] ,
									"location_id" => $temp_pickup_location ,
									"location_name" => ( $temp_pickup_location > 0 && $temp_pickup_location < 33 ? $array_location[ $temp_pickup_location ] : $array_location[ 0 ] )
								)
						);
				$event_param_object -> set_parameter( "pickup" , $array_pickup);
			}	
			$event_param_object-> insert_record();
		} 
		
		$balibikerental_object 				= new balibikerental();
		$tour_operator_id					= null;
		$tour_operator_client_pay_to		= null;
		$event_delivery						= $event_param_object-> get_parameter("delivery");
		if( isset($event_delivery["date"]) )
		{
			$pick_up_date 			= $event_delivery["date"];
			$pick_up_time			= $event_delivery["time"];
			$timestamp_start		= strtotime($pick_up_date . " " . $pick_up_time );
		} 
		$event_pickup					= $event_param_object-> get_parameter("pickup");
		if( isset($event_delivery["date"]) )
		{
			$return_date 			= $event_pickup["date"];  
			$return_time 			= $event_pickup["time"];  
			$timestamp_end			= strtotime($return_date . " " . $return_time ); 
		} 
		$day_diff = 0;
		if( isset($timestamp_start ) && isset($timestamp_end ) )
		{ 
			$day_diff			=	floor( ( $timestamp_end - $timestamp_start ) / 86400 ); // gives you elapsed days
		} 
		if( $day_diff < 2)
		{ 
			$message_text[0]			= "Sorry, minimum booking must be 2 days or greater. \nFor June-July peak season, minimum booking must be 3 days or greater. Please provide your new date.";
		}
		else
		{
			$walk_in_customer					= null;
			$brand 								= "1";
			$pick_up_location 					= "2";							//integer
			$return_location					= "2";							//integers
			$pick_up_location_custom 			= null;
			$return_location_custom 			= null;
			
	/*		// if the location of pickup or delivery is not in array (2 => "Airport",5 => "Seminyak Office",	6 => "Nusa Dua Office" )
			// later if retrieabvle fee of delivery from API, then we have to change this part.
			if
			( 
				$event_param_object-> get_parameter("delivery")["location_id"] != null && in_array( $event_param_object-> get_parameter("delivery")["location_id"] , array( 2,5,6 ) ) &&
				$event_param_object-> get_parameter("pickup")["location_id"] != null && in_array( $event_param_object-> get_parameter("pickup")["location_id"] , array( 2,5,6 ) ) 
			)
			{ 
				$pick_up_location 					= $event_param_object-> get_parameter("delivery")["location_id"];
				$return_location					= $event_param_object-> get_parameter("pickup")["location_id"];
				$pick_up_location_custom 			= null;
				$return_location_custom 			= null;
			}
			else
			{
				$pick_up_location 					= null;
				$return_location					= null;
				$pick_up_location_custom 			= $event_param_object-> get_parameter("delivery")["location_id"];
				$return_location_custom 			= $event_param_object-> get_parameter("pickup")["location_id"]; 
			}
	*/
			list( $curl_result , $curl_header , $curl_error , $curl_url ) = $balibikerental_object -> curl_post_bike_reservations_dates( $tour_operator_id , $tour_operator_client_pay_to , $pick_up_date , $return_date , $pick_up_time , $return_time , $walk_in_customer , $brand , $pick_up_location , $return_location , $pick_up_location_custom , $return_location_custom ); 
			if( $curl_header !== false )
			{
				if( in_array( $curl_header["http_code"] , array( "200" ) ) )
				{
					$result_curl_get_bike_availability	= json_decode( $curl_result , 1 );
					$motor_name = array();
					$motor_price = array();
					$motor_price_daily = array();
					$motor_string	= "";
					for
					(
						$j = 0 , $i = 0 ;
						$i <  ( count( $result_curl_get_bike_availability[ "applicable_classes" ] ) > 10 ? 10 : count( $result_curl_get_bike_availability[ "applicable_classes" ] ) ) ;
						$i++
					)
					{
						if( isset( $result_curl_get_bike_availability[ "applicable_classes" ][$i] ) )
						{ 
							$motor_string 	.= ( $i != 0 ? " \n * " : "" );
							$motor_name[ $j ]	 = $result_curl_get_bike_availability[ "applicable_classes" ][ $i ][ "class" ][ "name" ];
							$motor_price_daily[ $j ] = $result_curl_get_bike_availability[ "applicable_classes" ][ $i ][ "rack_rate_details" ][0][ "base_daily_price" ][ "amount_for_display" ];
							$motor_string 	.= $motor_name[ $j ] . " ( " . $motor_price_daily[ $j ] . " per day )" ;
							
							$count_message_quickreplies = count( $message_quickreplies ) ; 
							$message_quickreplies[ $count_message_quickreplies ]["type"]	= "text";
							$message_quickreplies[ $count_message_quickreplies ]["index"]	= 1; 
							$message_quickreplies[ $count_message_quickreplies ]["data"]["title"] = $motor_name[ $j ] . " ( " . $motor_price_daily[ $j ] . " per day )" ;   
							$j++ ;
						}
					}
				}
				else if( in_array( $curl_header["http_code"] , array( "404" , "500" ) ) )
				{
					$message_text[0]	= "Sorry, there is some problem on our side."; 
				}
			}
			if( $PLATFORM != "facebook")
			{
				if( $curl_result == null )
				{
					$message_text[0]	= "Sorry, there is some problem on our side."; 
				}
				else if ( $curl_result != null )
				{
					$message_text[0]	= "Great. These are the bike that available on your date : \n \n * " . $motor_string;
					$message_text[1]	= "Feel free to choose your bike.";
				}
			}
			else
			{ 
				if( $curl_result == null )
				{
					$message_text[0]	= "Sorry, there is some problem on our side."; 
				}
				else if ( $curl_result != null )
				{
					$message_text[0]	= "Great. These are the bike that available on your date";
					$message_text[1]	= "Feel free to choose your bike.";
				}
			}
		}
	}8
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Enquiry.Location.Bike" )
	{
		// how to set param
		$event_param_object 			= new event_param( $BRAND_ID , $SENDER_ID , $EVENT_ID ) ;
		$submit_dialogflow_result		= json_decode( $submit_dialogflow_result , 1 );
		
		$balibikerental_object 				= new balibikerental();
		$tour_operator_id					= null;
		$tour_operator_client_pay_to		= null;
		$pick_up_date 						= $event_param_object-> get_parameter("delivery")["date"];
		$return_date						= $event_param_object-> get_parameter("pickup")["date"];
		$pick_up_time 						= $event_param_object-> get_parameter("delivery")["time"];
		$return_time 						= $event_param_object-> get_parameter("pickup")["time"];
		$walk_in_customer					= null;
		$brand 								= "1";
		$pick_up_location 					= "2";							//integer
		$return_location					= "2";							//integers
		$pick_up_location_custom 			= null;
		$return_location_custom 			= null;
/*		// if the location of pickup or delivery is not in array (2 => "Airport",5 => "Seminyak Office",	6 => "Nusa Dua Office" )
		// later if retrieabvle fee of delivery from API, then we have to change this part.
		if
		( 
			$event_param_object-> get_parameter("delivery")["location_id"] != null && in_array( $event_param_object-> get_parameter("delivery")["location_id"] , array( 2,5,6 ) ) &&
			$event_param_object-> get_parameter("pickup")["location_id"] != null && in_array( $event_param_object-> get_parameter("pickup")["location_id"] , array( 2,5,6 ) )  
		)
		{ 
			$pick_up_location 					= $event_param_object-> get_parameter("delivery")["location_id"];
			$return_location					= $event_param_object-> get_parameter("pickup")["location_id"];
			$pick_up_location_custom 			= null;
			$return_location_custom 			= null;
		}
		else
		{
			$pick_up_location 					= null;
			$return_location					= null;
			$pick_up_location_custom 			= $event_param_object-> get_parameter("delivery")["location_id"];
			$return_location_custom 			= $event_param_object-> get_parameter("pickup")["location_id"]; 
		}
*/
		$vehicle_class_id 					= $submit_dialogflow_result["result"]["parameters"]["Bike"];
		list( $curl_result , $curl_header , $curl_error , $curl_url ) = $balibikerental_object -> curl_get_bike_reservations_additional_charges( $tour_operator_id , $tour_operator_client_pay_to , $pick_up_date , $return_date , $pick_up_time , $return_time , $walk_in_customer , $brand , $pick_up_location , $return_location , $pick_up_location_custom , $return_location_custom , $vehicle_class_id ); 
		
		if( $curl_header !== false )
		{
			if( in_array( $curl_header["http_code"] , array( "200" ) ) )
			{
				$result_curl_get_bike_accessories = json_decode( $curl_result , 1 );
				$currency			= $result_curl_get_bike_accessories[ "price" ][ "rack_rate_details" ][0][ "base_daily_price" ][ "currency_icon" ];
				$motor_price_daily	= $result_curl_get_bike_accessories[ "price" ][ "rack_rate_details" ][0][ "base_daily_price" ][ "amount" ];
				$motor_price		= $result_curl_get_bike_accessories[ "price" ][ "base_price" ][ "amount" ];
				
				$array_bike =  array_merge(
								$event_param_object -> get_parameter("bike"),
								array(
										"id" => $vehicle_class_id,
										"name" => $result_curl_get_bike_accessories[ "price" ]["class"]["name"],
										"unit" => 1,
										"unit_price" => $motor_price_daily, 			//from API BBR
										"total_price" => $motor_price,				// from API BBR
										"currency" => $currency
									)
							);
				$event_param_object -> set_parameter( "bike" , $array_bike);			
				$event_param_object-> insert_record();
			
				//process the output of the result
				$accessories_name = array();
				$accessories_price = array();
				$accessories_string	= "";
				for
				(
					$j = 0 , $i = 0 ;
					$i <  ( count( $result_curl_get_bike_accessories[ "additional_charges" ] ) > 16 ? 16 : count( $result_curl_get_bike_accessories[ "additional_charges" ] ) ) ;
					$i++
				)
				{
					if( isset( $result_curl_get_bike_accessories[ "additional_charges" ][$i] ) )
					{
						$accessories_string 	.= ( $i != 0 ? " \n * " : "" );
						$accessories_name[ $j ]	 = $result_curl_get_bike_accessories[ "additional_charges" ][$i][ "name" ];
						$accessories_price[ $j ] = $result_curl_get_bike_accessories[ "additional_charges" ][$i][ "applicable_price" ][ "amount_for_display" ];
						$accessories_string 	.= $accessories_name[ $j ] . " (" . $accessories_price[ $j ] . ")" ;
						$j++ ;
					}
				}
				$message_text[0]	= "Sure. Want to add any accessories?\n  " . $accessories_string;
				$message_text[1]	= "The first-aid kit and helmets is provided by us.";
			}
			else if( in_array( $curl_header["http_code"] , array( "404" , "500" ) ) )
			{
				$message_text[0]	= "Sorry, there is some problem on our side.";
			}
		}
	}
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Enquiry.Location.Bike.Accessories" )
	{
		// how to set param
		$event_param_object 				= new event_param( $BRAND_ID , $SENDER_ID , $EVENT_ID ) ;
		$submit_dialogflow_result			= json_decode( $submit_dialogflow_result , 1 ); 
		
		$balibikerental_object 				= new balibikerental();
		$tour_operator_id					= null;
		$tour_operator_client_pay_to		= null;
		$pick_up_date 						= $event_param_object-> get_parameter("delivery")["date"];
		$return_date						= $event_param_object-> get_parameter("pickup")["date"];
		$pick_up_time 						= $event_param_object-> get_parameter("delivery")["time"];
		$return_time 						= $event_param_object-> get_parameter("pickup")["time"];
		$walk_in_customer					= null;
		$brand 								= "1";
		$pick_up_location 					= 2;							//integer
		$return_location					= 2;							//integers
		$pick_up_location_custom 			= null;
		$return_location_custom 			= null;
/*		// if the location of pickup or delivery is not in array (2 => "Airport",5 => "Seminyak Office",	6 => "Nusa Dua Office" )
		// later if retrieabvle fee of delivery from API, then we have to change this part.
		if
		( 
			$event_param_object-> get_parameter("delivery")["location_id"] != null && in_array( $event_param_object-> get_parameter("delivery")["location_id"] , array( 2,5,6 ) ) &&
			$event_param_object-> get_parameter("pickup")["location_id"] != null && in_array( $event_param_object-> get_parameter("pickup")["location_id"] , array( 2,5,6 ) ) 
		)
		{ 
			$pick_up_location 					= $event_param_object-> get_parameter("delivery")["location_id"];
			$return_location					= $event_param_object-> get_parameter("pickup")["location_id"];
			$pick_up_location_custom 			= null;
			$return_location_custom 			= null;
		}
		else
		{
			$pick_up_location 					= null;
			$return_location					= null;
			$pick_up_location_custom 			= $event_param_object-> get_parameter("delivery")["location_id"];
			$return_location_custom 			= $event_param_object-> get_parameter("pickup")["location_id"]; 
		}
*/
		$vehicle_class_id 					= $event_param_object-> get_parameter("bike")["id"];
		$additional_charges 				= $submit_dialogflow_result["result"]["parameters"]["add_on"];       //integer (id)
		$id_quantity						= $submit_dialogflow_result["result"]["parameters"]["quantity"];
		list( $curl_result , $curl_header , $curl_error , $curl_url ) = $balibikerental_object -> curl_post_bike_reservations_additional_charges( $tour_operator_id , $tour_operator_client_pay_to , $pick_up_date , $return_date , $pick_up_time , $return_time , $walk_in_customer , $brand , $pick_up_location , $return_location , $pick_up_location_custom , $return_location_custom , $vehicle_class_id , $additional_charges , $id_quantity );
		
		if( $curl_header !== false )
		{
			if( in_array( $curl_header["http_code"] , array( "200" ) ) )
			{
				$result_curl_get_bike_total_price = json_decode( $curl_result , 1 );
				$currency			= $result_curl_get_bike_total_price["price"][ "selected_insurances" ][0][ "price" ][ "currency_icon" ];
				$total_days			= $result_curl_get_bike_total_price[ "price" ][ "total_days" ];
			//	$grand_total 		= $result_curl_get_bike_total_price[ "price" ][ "total_price" ][ "amount_for_display" ];
			//	$deposit			= $result_curl_get_bike_total_price[ "price" ][ "security_deposit" ][ "amount_for_display" ];   //deposit
				
				$array_add_on_price = 
					array(
							 "0"	=> 0.00 ,
							 "5"	=> 3.95 ,		"8" 	=> 0.50,		"9" 	=> 3.50,		"10" => 9.95,			
							 "12"	=> 1.50, 		"13"	=> 0.50,		"16"	=> 12.95,		"17" => 4.95,
							 "18"	=> 0.00 ,		"19"	=> 0.00,		"20"	=> 6.95,		"23" => 0.75,	
							 "33"	=> 1.50  
						);  
				$array_add_on = 
					array(
							 "0"	=> "Custom" ,
							 "5"	=> "Motorbike Insurance",				"8" 	=> "Cancellation Insurance",	"9" 	=> "Luggage",				"10" => "BioWear Eco Poncho",			
							 "12"	=> "Hard case for Motorbike", 			"13"	=> "Side Mount Surfrack",		"16"	=> "Nivea Sunscreen",		"17" => "OFF! Mosquito Repellent",
							 "18"	=> "Sanitized Helmet" ,					"19"	=> "First Aid Kit",				"20"	=> "Pocket WiFi 4g",		"23" => "Premium PowerBank 9000 mAh",	
							 "33"	=> "International Travel Power adaptor"  
						); 
				$temp_add_on_keys	= return_array_keys( $array_add_on , true ); 
				$add_on_array 		= array();
				$temp_data 			= $event_param_object -> get_parameter("add_on");  
				for( $i = 0 ; $i < count( $temp_data ) ; $i++ )
				{ 
					if( $temp_data  != "" && is_array( $temp_data ) )
					{ 
						$add_on_array[ count( $add_on_array ) ] = $temp_data[$i] ;
					}
				} 
				for( $i = 0 ; $i < count( $submit_dialogflow_result["result"]["parameters"]["add_on"] ) ; $i++ )
				{
					$temp_add_on_id = $submit_dialogflow_result["result"]["parameters"]["add_on"][ $i ];  
					$add_new_array = true;
					//check current list
					for( $j = 0 ; $j < count( $add_on_array ) ; $j++ )
					{
						// if current id exist previously
						if( $add_on_array[$j]["id"] ==  $temp_add_on_id )
						{ 
							// do not re-add this to new list
							$add_new_array = false; 
							// update only the unit as the unit is the only thing change.
							$add_on_array[ $j ] =  
								array(
									"id" 			=> $temp_add_on_id,
									"name" 			=> $add_on_array[$j]["name"],
									"unit" 			=> $submit_dialogflow_result["result"]["parameters"]["quantity"][$i],
									"unit_price" 	=> $add_on_array[$j]["unit_price"],
									"currency" 		=> $currency 
								);
						}
					}
					// add to the list if there is not exist
					if( $add_new_array == true )
					{
						// add to the list
						$add_on_array[ count( $add_on_array ) ] =  
							array(
								"id" 			=> $temp_add_on_id,
								"name" 			=> ( in_array( $temp_add_on_id , $temp_add_on_keys ) ? $array_add_on[ $temp_add_on_id ] : $array_add_on[ 0 ] ),
								"unit" 			=> $submit_dialogflow_result["result"]["parameters"]["quantity"][$i],
								"unit_price" 	=> ( in_array( $temp_add_on_id , $temp_add_on_keys ) ? $array_add_on_price[ $temp_add_on_id ] : $array_add_on_price[ 0 ] ),
								"currency" 		=> $currency 
							);
					}
				}
				$event_param_object 			-> set_parameter( "add_on" , $add_on_array);
				$event_param_object				-> set_parameter( "total_days" , $total_days ) ;
				$event_param_object				-> insert_record();
				$current_bike_selected 			= $event_param_object-> get_parameter("bike")["name"];
				$current_delivery_date 			= $event_param_object-> get_parameter("delivery")["date"];
				$current_pickup_date	 		= $event_param_object-> get_parameter("pickup")["date"];
				$current_delivery_time	 		= $event_param_object-> get_parameter("delivery")["time"];
				$current_pickup_time 			= $event_param_object-> get_parameter("pickup")["time"];
				$current_delivery_location_name = $event_param_object-> get_parameter("delivery")["location_name"];
				$current_pickup_location_name 	= $event_param_object-> get_parameter("pickup")["location_name"];
		
				$message_text[0]	= "Alright. Your booking summary : \nBike : " . $current_bike_selected . "\nDelivery date : " . $current_delivery_date . " " . $current_delivery_time . "\nPickup date : " . $current_pickup_date . " " . $current_pickup_time . "\nDelivery location : " . $current_delivery_location_name . "\nPickup location : " . $current_pickup_location_name . "\nTotal days : " . $total_days  ;
				$message_text[1]	= "We accept payment via Visa, Mastercard, American Express, Discover, Diners Club, JCB and UnionPay. Do you want to proceed with payment?";
			}
			elseif ( in_array( $curl_header["http_code"] , array( "404" , "500" ) ) ) 
			{
				$message_text[0]	= "Sorry, there is some problem on our side. Please try again later.";
			}
		}
	}
	else if( $dialogflow_object->get_intentName() == "Booking.Bike.Enquiry.Location.Bike.No.Accessories" )
	{
	
		$event_param_object 				= new event_param( $BRAND_ID , $SENDER_ID , $EVENT_ID ) ;
		$balibikerental_object 				= new balibikerental();
		$tour_operator_id					= null;
		$tour_operator_client_pay_to		= null;
		$pick_up_date 						= $event_param_object-> get_parameter("delivery")["date"];
		$return_date						= $event_param_object-> get_parameter("pickup")["date"];
		$pick_up_time 						= $event_param_object-> get_parameter("delivery")["time"];
		$return_time 						= $event_param_object-> get_parameter("pickup")["time"];
		$walk_in_customer					= null;
		$brand 								= "1";
		$pick_up_location 					= "2";						//later if retrieabvle fee of delivery from API, then we have to change this part.
		$return_location					= "2";						//later if retrieabvle fee of delivery from API, then we have to change this part.
		$pick_up_location_custom 			= null;
		$return_location_custom 			= null;
/*		// if the location of pickup or delivery is not in array (2 => "Airport",5 => "Seminyak Office",	6 => "Nusa Dua Office" )
		// later if retrieabvle fee of delivery from API, then we have to change this part.
		if
		( 
			$event_param_object-> get_parameter( "delivery_location" ) != null && in_array( $event_param_object-> get_parameter( "delivery_location" ) , array( 2,5,6 ) ) &&
			$event_param_object-> get_parameter( "pickup_location" ) != null && in_array( $event_param_object-> get_parameter( "pickup_location" ) , array( 2,5,6 ) ) 
		)
		{ 
			$pick_up_location 					= $event_param_object-> get_parameter( "delivery_location" );
			$return_location					= $event_param_object-> get_parameter( "pickup_location" );
			$pick_up_location_custom 			= null;
			$return_location_custom 			= null;
		}
		else
		{
			$pick_up_location 					= null;
			$return_location					= null;
			$pick_up_location_custom 			= $event_param_object-> get_parameter( "delivery_location" );
			$return_location_custom 			= $event_param_object-> get_parameter( "pickup_location" ); 
		}
*/
		$vehicle_class_id 					= $event_param_object-> get_parameter("bike")["id"];
		$additional_charges 				= null;       
		$id_quantity						= null;
		list( $curl_result , $curl_header , $curl_error , $curl_url ) = $balibikerental_object -> curl_post_bike_reservations_additional_charges( $tour_operator_id , $tour_operator_client_pay_to , $pick_up_date , $return_date , $pick_up_time , $return_time , $walk_in_customer , $brand , $pick_up_location , $return_location , $pick_up_location_custom , $return_location_custom , $vehicle_class_id , $additional_charges , $id_quantity );
		
		if( $curl_header !== false )
		{
			if( in_array( $curl_header["http_code"] , array( "200" ) ) )
			{
				$result_curl_get_bike_total_price = json_decode( $curl_result , 1 );
				$total_days							= $result_curl_get_bike_total_price[ "price" ][ "total_days" ];
			//	$grand_total 						= $result_curl_get_bike_total_price[ "price" ][ "total_price" ][ "amount_for_display" ];
			//	$deposit							= $result_curl_get_bike_total_price[ "price" ][ "security_deposit" ][ "amount_for_display" ];   //deposit
		
				$event_param_object				-> set_parameter( "total_days" , $total_days ) ;
				$event_param_object				-> insert_record();
				$current_bike_selected 			= $event_param_object-> get_parameter("bike")["name"];
				$current_delivery_date 			= $event_param_object-> get_parameter("delivery")["date"];
				$current_pickup_date	 		= $event_param_object-> get_parameter("pickup")["date"];
				$current_delivery_time	 		= $event_param_object-> get_parameter("delivery")["time"];
				$current_pickup_time 			= $event_param_object-> get_parameter("pickup")["time"];
				$current_delivery_location_name = $event_param_object-> get_parameter("delivery")["location_name"];
				$current_pickup_location_name 	= $event_param_object-> get_parameter("pickup")["location_name"];
				
				$message_text[0]	= "Alright. Your booking summary : \nBike : " . $current_bike_selected . "\nDelivery date : " . $current_delivery_date . " " . $current_delivery_time . "\nPickup date : " . $current_pickup_date . " " . $current_pickup_time . "\nDelivery location : " . $current_delivery_location_name . "\nPickup location : " . $current_pickup_location_name . "\nTotal days : " . $total_days ;
				$message_text[1]	= "We accept payment via Visa, Mastercard, American Express, Discover, Diners Club, JCB and UnionPay. Do you want to proceed with payment?";
			}
			else if( in_array( $curl_header["http_code"] , array( "404" , "500" ) ) )
			{
				$message_text[0]	= "Sorry, there is some problem on our side. Please try again later.";
			}
		}
	} 
	else if( in_array( $dialogflow_object->get_intentName() , array( "Booking.Bike.Enquiry.Location.Bike.Accessories.Proceed" , "Booking.Bike.Enquiry.Location.Bike.No.Accessories.Proceed" ) ) )
	{  
		// retrieve all data from user_param
		$event_param_object	= new event_param( $BRAND_ID , $SENDER_ID , $EVENT_ID ) ;
		$event_param		= $event_param_object -> get_parameter(); 
		  
		// generate transaction id
		$transaction_object	= new transaction( $BRAND_ID , $SENDER_ID , null , null) ; 
		$transId = $transaction_object -> get_transId();
		
		if( $transId != null )
		{  
			/* 
				{
					total_days:8,
					delivery:
					{
						"date":"2018-05-01",
						"time":"14:00",
						"location_id":["12"],
						"location_name":"Ubud"
					},
					pickup:
					{
						"date":"2018-05-01",
						"time":"14:00",
						"location_id":["12"],
						"location_name":"Ubud"
					}, 
					bile : 
					{
						id:"5",
						name:"Kawasaki KLX 150cc",
						unit : 1,
						unit_price : 100,
						total_price : 800
					} 
					,"add_on":
					[
						{
							"id" => $submit_dialogflow_result["result"]["parameters"]["add_on"],
							"name" => "",
							"unit" => $submit_dialogflow_result["result"]["parameters"]["quantity"],
							"unit_price" => "",
							"total_price" => "" 
						},
						{
							"id" => $submit_dialogflow_result["result"]["parameters"]["add_on"],
							"name" => "",
							"unit" => $submit_dialogflow_result["result"]["parameters"]["quantity"],
							"unit_price" => "",
							"total_price" => "" 
						}
					]    
				}
			*/
			
			// move all user_param to transaction detail      
			//( $transId , $type , $itemId , $itemValue , $unit , $unitPrice , $tax , $totalAmount )
			//( $new_detail = false , $transId , $type , $itemId , $itemValue , $unit , $unitPrice , $tax , $totalAmount )
			$new_detail = true;
			$transaction_detail_object[0]	= new transaction_detail( $new_detail , $transId , "10001" , null , $event_param["total_days"] , 0 , 0 , 0 , 0 ); 				//"total_days" ,
			$transaction_detail_object[1]	= new transaction_detail( $new_detail , $transId , "10011" , null , $event_param["delivery"]["date"]  , 0 , 0 , 0 , 0 );			//"delivery_date" ,
			$transaction_detail_object[2]	= new transaction_detail( $new_detail , $transId , "10012" , null , $event_param["delivery"]["time"]  , 0 , 0 , 0 , 0 );			//"delivery_time" ,
			$transaction_detail_object[3]	= new transaction_detail( $new_detail , $transId , "10013" , null , $event_param["delivery"]["location_id"]  , 0 , 0 , 0 , 0 );	//"delivery_location_id" ,
			$transaction_detail_object[4]	= new transaction_detail( $new_detail , $transId , "10014" , null , $event_param["delivery"]["location_name"]  , 0 , 0 , 0 , 0 ); //"delivery_location_name" , 
			$transaction_detail_object[5]	= new transaction_detail( $new_detail , $transId , "10021" , null , $event_param["pickup"]["date"]  , 0 , 0 , 0 , 0 );			//"pickup_date" ,
			$transaction_detail_object[6]	= new transaction_detail( $new_detail , $transId , "10022" , null , $event_param["pickup"]["time"]  , 0 , 0 , 0 , 0 );			//"pickup_time" ,
			$transaction_detail_object[7]	= new transaction_detail( $new_detail , $transId , "10023" , null , $event_param["pickup"]["location_id"]  , 0 , 0 , 0 , 0 ); 	// "pickup_location_id"
			$transaction_detail_object[8]	= new transaction_detail( $new_detail , $transId , "10024" , null , $event_param["pickup"]["location_name"]  , 0 , 0 , 0 , 0 );	//"pickup_location_name"  
			$transaction_detail_object[9]	= new transaction_detail( $new_detail , $transId , "20001" ,
																		$event_param["bike"]["id"] ,
																		$event_param["bike"]["name"] ,
																		$event_param["bike"]["unit"] ,
																		$event_param["bike"]["unit_price"] ,
																		0 ,
																		$event_param["bike"]["total_price"] );  // "bike"
			for( $i = 0 ; $i < count( $event_param["add_on"] ) ; $i++ )
			{
				$transaction_detail_object[ count( $transaction_detail_object ) ]	= new transaction_detail(
																							$new_detail , 
																							$transId ,
																							"30001" ,
																							$event_param["add_on"][$i]["id"] ,
																							$event_param["add_on"][$i]["name"] ,
																							$event_param["add_on"][$i]["unit"] ,
																							$event_param["add_on"][$i]["unit_price"]  ,
																							0,
																							$event_param["add_on"][$i]["unit"] * $event_param["add_on"][$i]["unit_price"] * $event_param["total_days"] 
																						);
			}
			$message_text[0]			= $dialogflow_object->get_speech();
			// then proceed with payment
			$message_text[1]			= "Click the link to submit payment" ;
			$message_text[2]			= "https://omni.silverstreet.com/index.php?page=stripe&type=check_out&transid=" . rawurlencode($transId) ;
			$message_template[0]["index"]			= 1;
			$message_template[0]["ignore"]			= 2;
			$message_template[0]["type"]			= "url";
			$message_template[0]["data"]["title"]	= "Pay";
			$message_template[0]["data"]["url"]		= "https://omni.silverstreet.com/index.php?page=stripe&type=check_out&transid=" . rawurlencode($transId);
			
			//$message_text[2] = "https://omni.silverstreet.com/index.php?page=stripe&type=check_out&transid=" . rawurlencode($transId);
		}
		else
		{
			write_log( "error" );
			$message_text[0] = "transaction id is empty, please retry";
		}
	}
	else
	{
		$message_text[0] = $dialogflow_object->get_speech(); 
	}
	switch($PLATFORM)
	{
		case "facebook":	$facebookmessage_object = new facebook_message($BRAND_ID, $PLATFORM) ;		$facebookmessage_object -> submit_text( $SENDER_ID , $message_text , $dialogflow_object -> get_id() , $message_template , $message_quickreplies );			break;
		case "line": 		$linemessage_object		= new line_message( $BRAND_ID , $PLATFORM );		$linemessage_object		-> submit_text( $SENDER_ID , $message_text , $dialogflow_object -> get_id() , true , $LINE_MESSAGEBODY );	break;
		case "telegram": 	$telegrammessage_object	= new telegram_message( $BRAND_ID , $PLATFORM );	$telegrammessage_object	-> submit_text( $SENDER_ID , $message_text , $dialogflow_object -> get_id() , true , $EVENT_ID );			break;
		case "rcs": 		$rcs_event_object		= new rcs_event( $BRAND_ID ) ;						$rcs_event_object		-> submit_text( $SENDER_ID , $message_text , $dialogflow_object -> get_id() , true );						break;
	}

?>