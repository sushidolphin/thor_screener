<?php

/*

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
x_PE_90th_5yrs
_pretax_income
_pretax_profit_margin
x_PE_10th_5yrs

*/
				
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
