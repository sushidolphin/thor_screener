<?php

function calculate_growth ( $field_name, $reports_ago, $save )
{
	global $freq;

	return create_var ( $field_name . "_annualized_growth_T" . $reports_ago . $freq , annualized_return( calculate_change( get_financial( 0, $field_name ), get_financial( - $reports_ago , $field_name ) ), ( $freq == "Q" ) ? $reports_ago / 4 : $reports_ago ) , $save ); 
}


?>
