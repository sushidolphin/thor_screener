<?php

// Quarter report data are being used

/*

	create_var ( NAME, VALUE, 1 = save );

	create_trailing_var ( NAME or null, FIELD_NAME, number of trailing values, calculation: sum, mean, percentile, SD, median or null for array, 1 = save );

	calculate_growth ( FIELD_NAME , compare_with_X_report_ago , 1 = save );

	calculate_consistency ( FIELD_NAME , 1 for increase or -1 for decrease, number of trailing values, 1 = save );

*/





// Median price during reference quarter
				
create_var( "price_median", calculate_median( $temp[ "price_data_within_period" ][ "price_median" ] ), 1 );






// PE = current price / EPS_TTM from previous quarter
														
create_var( "PE",

	( get_financial( -1, "eps_TTM" ) > 0 )
	
	? round( get_financial( 0, "price_median" ) / get_financial( -1, "eps_TTM" ) , 2 ) 
	
	: null
	
, 1 );






// Number of years required to repay long-term debt using current net_income_TTM

create_var( "years_to_repay_LT_debt", 

	( get_financial( 0, "net_income_TTM" ) > 0 ) 
	
	? round( get_financial( 0, "long_term_debt" ) / get_financial( 0, "net_income_TTM" ) , 1 ) 
	
	: null
	
, 1 );




/*

// If less than 4 years, output = 1, else = 0

create_var( "years_to_repay_LT_debt_less_than_4", 

	( get_financial( 0, "years_to_repay_LT_debt" ) < 4 )  
	
	? 1
	
	: 0
	
, 1 );

*/




// If EPS growth has been greater than net income growth in the last 5 years, output = 1, else = 0

create_var ( "EPS_TTM_growth_greater_than_net_income_TTM_growth",

	( get_financial( 0, "eps_TTM_annualized_growth_T20Q" ) > get_financial( 0, "net_income_TTM_annualized_growth_T20Q" ) )

	? 1 : 0,

	1 
);





// TTM VALUES

create_trailing_var ( "eps_TTM", "eps_earnings_per_share", 4, "sum", 1 );

create_trailing_var ( "net_income_TTM", "net_income", 4, "sum", 1 );

create_trailing_var ( "net_income_28Q", "net_income", 28, "sum", 1 );

create_trailing_var ( "net_cash_flow_TTM", "net_cash_flow", 4, "sum", 1 );

create_trailing_var ( "share_holder_equity_TTM", "share_holder_equity", 4, "mean", 1 );

create_trailing_var ( "long_term_debt_TTM", "long_term_debt", 4, "mean", 1 );

create_trailing_var ( "goodwill_and_intangible_assets_TTM", "goodwill_and_intangible_assets", 4, "mean", 1 );

create_trailing_var ( null, "PE", 20, "75th", 1 );

create_trailing_var ( null, "PE", 20, "25th", 1 );

create_trailing_var ( null, "PE", 20, "SD", 1 );





// ROE calculated as net_income from last 12 months / shareholder equity at the beginning of the year

create_var( "ROE_2", 

	( get_financial( -4, "share_holder_equity" ) > 0 ) 
	
	? get_financial( 0, "net_income_TTM" ) / get_financial( -4, "share_holder_equity" ) * 100 
	
	: null 
	
, 1 );






// Invested capital as sum of share holder equity and long-term debt and capital lease obligation, minus goodwill and intangible assets

create_var( "invested_capital_TTM", 

	get_financial( 0, "share_holder_equity_TTM" ) + get_financial( 0, "long_term_debt_TTM" ) - get_financial( 0, "goodwill_and_intangible_assets_TTM" )
	
, 1 );





// ROIC as net_income from last 12 months / invested capital at the beginning of the year

create_var( "ROIC", 

	divide( get_financial( 0, "net_income_TTM" ), get_financial( -4, "invested_capital_TTM" ) ) * 100
	
, 1 );






// If outstanding shares decrease as compared to previous quarter and current PE is above 75th percentile, output = 1, else = 0

create_var ( "shares_buyback_with_high_PE",

	( get_financial( -1, "shares_outstanding" ) > get_financial( 0, "shares_outstanding" ) AND get_financial( 0, "PE" ) >= get_financial( 0, "PE_75th_T20Q" ) )

	? 1 
	
	: 0,

	1 
);






// 5 AND 7 YRS VALUES

foreach ( [ 20, 28 ] as $time_window )
{

	// Median ROE from past X quarters
	
	create_trailing_var ( null, "roe_return_on_equity", $time_window, "median", 1 );
	
	// Median net_profit_margin from past X quarters

	create_trailing_var ( null, "net_profit_margin", $time_window, "median", 1 );
		
	// Median ROE_2 from past X quarters
	
	create_trailing_var ( null, "ROE_2", $time_window, "median", 1 );
	
	// Median ROIC from past X quarters
	
	create_trailing_var ( null, "ROIC", $time_window, "median", 1 );
	
	
	



	// % of times that net_income_TTM increases as compared to previous value. It returns a value from 0 to 100%.

	calculate_consistency ( "net_income_TTM", 1, $time_window, 1 );
	
	// % of times that eps_TTM increases as compared to previous value. It returns a value from 0 to 100%.

	calculate_consistency ( "eps_TTM", 1, $time_window, 1 );
	
	// % of times that roe_return_on_equity increases as compared to previous value. It returns a value from 0 to 100%.
	
	calculate_consistency ( "roe_return_on_equity", 1, $time_window, 1 );
	
	// % of times that ROE_2 increases as compared to previous value. It returns a value from 0 to 100%.
	
	calculate_consistency ( "ROE_2", 1, $time_window, 1 );
	



	
	
	
	
	// Annualized % growth of net_income_TTM over X quarters

	calculate_growth ( "net_income_TTM", $time_window, 1 );
	
	// Annualized % growth of eps_TTM over X quarters

	calculate_growth ( "eps_TTM", $time_window, 1 );

}




/*

// If median ROE of past 7 years is >15%, output = 1, else = 0

create_var( "high_ROE_2", 

	( get_financial( 0, "ROE_2_median_T28Q" ) > 15 ) 
	
	? 1 
	
	: 0
	
, 1 );

*/


/*

// If median ROIC of past 7 years is >15%, output = 1, else = 0

create_var( "high_ROIC", 

	( get_financial( 0, "ROIC_median_T28Q" ) > 15 ) 
	
	? 1 
	
	: 0
	
, 1 );

*/



// Absolute change in net_income_TTM as compared to 7 years ago

create_var( "net_income_TTM_absolute_change_28Q", get_financial( 0, "net_income_TTM" ) - get_financial( -28, "net_income_TTM" ) , 1 );





// Absolute change in invested_capital as compared to 7 years ago

create_var( "invested_capital_TTM_absolute_change_28Q", get_financial( 0, "invested_capital_TTM" ) - get_financial( -28, "invested_capital_TTM" ) , 1 );






// ROIIC as absolute change in net_income_TTM over past 7 years / absolute change in invested_capital over past 7 years

create_var( "ROIIC", 

	divide( get_financial( 0, "net_income_TTM_absolute_change_28Q" ), get_financial( 0, "invested_capital_TTM_absolute_change_28Q" ) ) * 100
	
, 1 );




// Reinvestment rate

create_var( "reinvestment_rate", 

	divide( get_financial( 0, "invested_capital_TTM_absolute_change_28Q" ), get_financial( 0, "net_income_28Q" ) ) * 100
	
, 1 );





// Value compounding rate

create_var( "value_compounding_rate", 

	divide( get_financial( 0, "reinvestment_rate" ), get_financial( 0, "ROIIC" ) )
	
, 1 );



/*

// If value compounding rate > 15, output = 1, else = 0

create_var( "high_value_compounding_rate", 

	( get_financial( 0, "value_compounding_rate" ) > 15 ) 
	
	? 1
	
	: 0
	
, 1 );

*/



					
?>
