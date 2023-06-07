<?php

// Quarter report data are being used

/*

create_var ( NAME, VALUE, 1 = save );

create_trailing_var ( NAME or null, FIELD_NAME, number of trailing values, calculation: sum, mean, percentile, SD, median or null for array, 1 = save );

calculate_growth ( FIELD_NAME , compare_with_X_report_ago , 1 = save );

calculate_consistency ( FIELD_NAME , 1 for increase or -1 for decrease, number of trailing values, 1 = save );

*/

				
create_var( "price_median", calculate_median( $temp[ "price_data_within_period" ][ "price_median" ] ), 1 );
														
create_var( "PE",

	( get_financial( -1, "eps_TTM" ) > 0 )
	
	? round( get_financial( 0, "price_median" ) / get_financial( -1, "eps_TTM" ) , 2 ) 
	
	: null
	
, 1 );

create_trailing_var ( "eps_TTM", "eps_earnings_per_share", 4, "sum", 1 );

create_trailing_var ( "net_income_TTM", "net_income", 4, "sum", 1 );

create_trailing_var ( "net_cash_flow_TTM", "net_cash_flow", 4, "sum", 1 );

create_trailing_var ( "share_holder_equity_TTM", "share_holder_equity", 4, "mean", 1 );

create_trailing_var ( null, "PE", 20, "90th", 1 );

create_trailing_var ( null, "PE", 20, "10th", 1 );

create_trailing_var ( null, "PE", 20, "SD", 1 );

create_trailing_var ( null, "roe_return_on_equity", 20, "median", 1 );

create_trailing_var ( null, "roe_return_on_equity", 28, "median", 1 );

create_trailing_var ( null, "net_profit_margin", 20, "median", 1 );

create_trailing_var ( null, "net_profit_margin", 28, "median", 1 );

create_var( "years_to_repay_LT_debt", 

	( get_financial( 0, "net_income_TTM" ) > 0 ) 
	
	? round( get_financial( 0, "long_term_debt" ) / get_financial( 0, "net_income_TTM" ) , 1 ) 
	
	: null
	
, 1 );

calculate_consistency ( "net_income_TTM", 1, 28, 1 );

calculate_growth ( "net_income_TTM", 20, 1 );

create_var ( "shares_buyback",

	( get_financial( -1, "shares_outstanding" ) > get_financial( 0, "shares_outstanding" ) )

	? 1 : 0,

	1 
);
					
?>
