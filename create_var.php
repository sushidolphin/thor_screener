<?php

function create_var ( $name, $value, $save )
{
	global $settings, $financial_report, $derived, $save_to_database, $derived_fields_prefix;
	
	if 
	( 
		!isset( $financial_report[ $derived_fields_prefix . $name ] ) 
		
		OR 
		
		( 
			
			$settings[ "recalculate_derived_vars" ] == 1			
			
			OR
			
			( 
				is_array( $settings[ "recalculate_derived_vars" ] ) 
				
				AND 
				
				match_name_to_column( $name , $settings[ "recalculate_derived_vars" ] ) != null
			)
											
		)		
		
	) $financial_report[ $derived_fields_prefix . $name ] = $value;
	
	if ( $save == 1 AND ( !isset( $save_to_database ) OR !in_array( $derived_fields_prefix . $name, $save_to_database ) ) ) $save_to_database[] = $derived_fields_prefix . $name;
}


?>
