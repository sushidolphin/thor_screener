<?php

function create_trailing_var ( $name, $trailing_field, $number_of_elements, $financial_report_type, $calc, $save )
{
	
	if ( get_financial( 0, "fiscal_period" ) == $financial_report_type )
	{

		$trailing_array = trailing ( $name , get_financial( 0, $trailing_field ) , $number_of_elements );
		
		if ( is_array( $trailing_array ) )
		{
			if ( $calc == "sum" ) $value = array_sum( $trailing_array );
			
			else if ( $calc == "mean" ) $value = calculate_mean( $trailing_array );
			
			else if ( $calc == "median" ) $value = calculate_median( $trailing_array );
			
			else if ( $calc == "min" ) $value = return_min( $trailing_array );
			
			else if ( $calc == "max" ) $value = return_max( $trailing_array );
			
			else if ( strpos( $calc, "th" ) != false ) $value = percentiles( $trailing_array, str_replace( "th", "", $calc ) );

			if ( isset( $value ) ) create_var ( $name, $value, $save );
			
			
		}
	
	}
}


?>
