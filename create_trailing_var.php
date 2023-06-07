<?php

function create_trailing_var ( $name, $trailing_field, $number_of_elements, $calc, $save )
{
	global $freq;
	
	if ( get_financial( 0, $trailing_field ) != null AND $number_of_elements > 1 )
	{
	
		if ( $name == null ) $name = $trailing_field . "_" . $calc . "_T" . $number_of_elements . $freq;

		$trailing_array = trailing ( $name , get_financial( 0, $trailing_field ) , $number_of_elements );
		
		if ( is_array( $trailing_array ) )
		{
			if ( $calc == "sum" ) $value = array_sum( $trailing_array );
			
			else if ( $calc == "mean" ) $value = calculate_mean( $trailing_array );
			
			else if ( $calc == "median" ) $value = calculate_median( $trailing_array );
			
			else if ( $calc == "min" ) $value = return_min( $trailing_array );
			
			else if ( $calc == "max" ) $value = return_max( $trailing_array );
			
			else if ( $calc == "SD" ) $value = standard_dev( $trailing_array );
			
			else if ( strpos( $calc, "th" ) != false ) $value = percentiles( $trailing_array, str_replace( "th", "", $calc ) );
			
			else if ( $calc == null ) return $trailing_array;

			if ( isset( $value ) ) create_var ( $name, $value, $save );
			
			
		}
		else return null;
	
	}
	else return null;
}


?>
