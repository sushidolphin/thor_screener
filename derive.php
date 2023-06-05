<?php
				
create_var( "price_median", calculate_median( $temp[ "price_data_within_period" ][ "price_median" ] ), 1 );
														
create_var( "PE",

	( get_financial( -1, "eps_TTM" ) > 0 )
	
	? round( get_financial( 0, "price_median" ) / get_financial( -1, "eps_TTM" ) , 2 ) 
	
	: null
	
, 1 );

create_trailing_var ( "eps_TTM", "eps_earnings_per_share", 4, "Q", "sum", 1 );

create_trailing_var ( "net_income_TTM", "net_income", 4, "Q", "sum", 1 );

create_trailing_var ( "net_cash_flow_TTM", "net_cash_flow", 4, "Q", "sum", 1 );

create_trailing_var ( "share_holder_equity_TTM", "share_holder_equity", 4, "Q", "mean", 1 );

create_trailing_var ( "PE_90th_5yrs", "PE", 20, "Q", "90th", 1 );

create_trailing_var ( "PE_10th_5yrs", "PE", 20, "Q", "10th", 1 );

create_var ( "shares_buyback",

	( get_financial( -1, "shares_outstanding" ) > get_financial( 0, "shares_outstanding" ) )

	? 1 : 0,

	1 
);
					
?>
