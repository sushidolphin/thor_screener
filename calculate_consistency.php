<?php

function calculate_consistency ( $field_name, $trend, $number_of_elements, $save )
{
	global $freq;

	$var_name = $field_name . "_" . ( $trend == 1 ? "increase" : "decrease" ) . "_consistency_T" . $number_of_elements . $freq;
	
	if ( is_numeric( get_financial( 0, "net_income_TTM" ) ) AND is_numeric( get_financial( -1, "net_income_TTM" ) ) )
	{
		if ( $trend == 1 )
	
			$output = trailing ( $var_name , ( get_financial( 0, "net_income_TTM" ) > get_financial( -1, "net_income_TTM" ) ) ? 1 : 0  , $number_of_elements );
			
		else if ( $trend == -1 )
			
			$output = trailing ( $var_name , ( get_financial( 0, "net_income_TTM" ) < get_financial( -1, "net_income_TTM" ) ) ? 1 : 0  , $number_of_elements );
		
		if ( is_array( $output ) )
	
			return create_var( $var_name , round( array_sum( $output ) / count( $output ) * 100 ), $save );
	}
}

?>
