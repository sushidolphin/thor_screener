<?php

// Quarter report data are being used

/*

Column fields starting with underscore _ have been dowloaded from data provider.
Column fields starting with x_ have been derived with proprietary functions.

row_id
object_id
fiscal_period
period_ending_on
valid_from
valid_until
_revenue
_cost_of_goods_sold
_gross_profit
_research_and_development_expenses
_sga_expenses
_operating_expenses
_operating_income
_total_nonoperating_income_expense
_income_taxes
_income_after_taxes
_income_from_continuous_operations
_net_income
_ebitda
_ebit
_basic_shares_outstanding
_shares_outstanding
_basic_eps
_eps_earnings_per_share
_cash_on_hand
_receivables
_inventory
_other_current_assets
_total_current_assets
_property_plant_and_equipment
_longterm_investments
_other_longterm_assets
_total_longterm_assets
_total_assets
_total_current_liabilities
_long_term_debt
_other_noncurrent_liabilities
_total_long_term_liabilities
_total_liabilities
_common_stock_net
_retained_earnings_accumulated_deficit
_comprehensive_income
_share_holder_equity
_total_liabilities_and_share_holders_equity
_net_income_loss
_total_depreciation_and_amortization_cash_flow
_other_noncash_items
_total_noncash_items
_change_in_accounts_receivable
_change_in_inventories
_change_in_accounts_payable
_change_in_assets_liabilities
_total_change_in_assets_liabilities
_cash_flow_from_operating_activities
_net_change_in_property_plant_and_equipment
_net_change_in_intangible_assets
_net_acquisitions_divestitures
_net_change_in_shortterm_investments
_net_change_in_longterm_investments
_net_change_in_investments_total
_investing_activities_other
_cash_flow_from_investing_activities
_net_longterm_debt
_net_current_debt
_debt_issuance_retirement_net_total
_net_common_equity_issued_repurchased
_net_total_equity_issued_repurchased
_total_common_and_preferred_stock_dividends_paid
_financial_activities_other
_cash_flow_from_financial_activities
_net_cash_flow
_stockbased_compensation
_common_stock_dividends_paid
_current_ratio
_longterm_debt_capital
_debt_equity_ratio
_gross_margin
_operating_margin
_ebit_margin
_net_profit_margin
_asset_turnover
_inventory_turnover_ratio
_receiveable_turnover
_days_sales_in_receivables
_roe_return_on_equity
_return_on_tangible_equity
_roa_return_on_assets
_roi_return_on_investment
_book_value_per_share
_operating_cash_flow_per_share
_free_cash_flow_per_share
_goodwill_and_intangible_assets
x_PE
x_price_median
x_shares_buyback
x_eps_TTM
x_net_income_TTM
x_net_cash_flow_TTM
x_share_holder_equity_TTM
_pretax_income
_pretax_profit_margin
x_years_to_repay_LT_debt
x_net_income_TTM_annualized_growth_T20Q
x_net_income_TTM_increase_consistency_T28Q
x_net_profit_margin_median_T20Q
x_net_profit_margin_median_T28Q
x_roe_return_on_equity_median_T20Q
x_PE_90th_T20Q
x_PE_10th_T20Q
x_PE_SD_T20Q
x_roe_return_on_equity_median_T28Q

*/

// Quarter report data are being used

/*

	create_var ( NAME, VALUE, 1 = save );

	create_trailing_var ( NAME or null, FIELD_NAME, number of trailing values, calculation: sum, mean, percentile, SD, median or null for array, 1 = save );

	calculate_growth ( FIELD_NAME , compare_with_X_report_ago , 1 = save );

	calculate_consistency ( FIELD_NAME , 1 for increase or -1 for decrease, number of trailing values, 1 = save );

*/





// CURRENT VALUES
				
create_var( "price_median", calculate_median( $temp[ "price_data_within_period" ][ "price_median" ] ), 1 );
														
create_var( "PE",

	( get_financial( -1, "eps_TTM" ) > 0 )
	
	? round( get_financial( 0, "price_median" ) / get_financial( -1, "eps_TTM" ) , 2 ) 
	
	: null
	
, 1 );

create_var( "years_to_repay_LT_debt", 

	( get_financial( 0, "net_income_TTM" ) > 0 ) 
	
	? round( get_financial( 0, "long_term_debt" ) / get_financial( 0, "net_income_TTM" ) , 1 ) 
	
	: null
	
, 1 );

create_var ( "EPS_TTM_growth_greater_than_net_income_TTM_growth",

	( get_financial( 0, "eps_TTM_annualized_growth_T20Q" ) > get_financial( 0, "net_income_TTM_annualized_growth_T20Q" ) )

	? 1 : 0,

	1 
);





// TTM VALUES

create_trailing_var ( "eps_TTM", "eps_earnings_per_share", 4, "sum", 1 );

create_trailing_var ( "net_income_TTM", "net_income", 4, "sum", 1 );

create_trailing_var ( "net_cash_flow_TTM", "net_cash_flow", 4, "sum", 1 );

create_trailing_var ( "share_holder_equity_TTM", "share_holder_equity", 4, "mean", 1 );

create_trailing_var ( null, "PE", 20, "75th", 1 );

create_trailing_var ( null, "PE", 20, "25th", 1 );

create_trailing_var ( null, "PE", 20, "SD", 1 );





create_var ( "shares_buyback_with_high_PE",

	( get_financial( -1, "shares_outstanding" ) > get_financial( 0, "shares_outstanding" ) AND get_financial( 0, "PE" ) >= get_financial( 0, "PE_75th_T20Q" ) )

	? 1 
	
	: 0,

	1 
);





// 5 AND 7 YRS VALUES

foreach ( [ 20, 28 ] as $time_window )
{

	create_trailing_var ( null, "roe_return_on_equity", $time_window, "median", 1 );

	create_trailing_var ( null, "net_profit_margin", $time_window, "median", 1 );


	calculate_consistency ( "net_income_TTM", 1, $time_window, 1 );

	calculate_consistency ( "eps_TTM", 1, $time_window, 1 );


	calculate_growth ( "net_income_TTM", $time_window, 1 );

	calculate_growth ( "eps_TTM", $time_window, 1 );

}

					
?>
