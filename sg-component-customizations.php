<?php

include_once 'component-thumbnails.php';
include_once 'apparel-attribute-filters.php';

add_filter( 'woocommerce_composite_component_data', 'sg_woocommerce_composite_component_data', 10, 3 );

function sg_woocommerce_composite_component_data( $data, $i, $composite ) {

	if ( 'Apparel or Accessory' == $data['title'] || 'apparel_that_fits' == $data['query_type'] ) {
		$data['title']            = 'Apparel or Accessory';
		$data['query_type']       = 'apparel_that_fits';
		$data['selection_mode']   = 'thumbnails';
		$data['pagination_style'] = 'load-more';
		$data['display_prices']   = 'absolute';
		$data['show_orderby']     = 'yes';
	}

	// always do this
	$data['hide_subtotal_cart']    = 'yes';
	$data['hide_subtotal_orders']  = 'yes';
	$data['hide_subtotal_product'] = 'yes';
	$data['priced_individually']   = 'yes';

	return $data;
}

add_filter( 'woocommerce_composite_component_default_orderby', 'sg_cp_default_orderby', 10, 3 );
function sg_cp_default_orderby( $orderby, $id, $composite ) {
	$orderby = 'price';

	return $orderby;
}

add_filter( 'woocommerce_composite_component_option_data', 'sg_cp_add_base_price', 10, 2 );

function sg_cp_add_base_price( $data, $component_option ) {

	$component = $component_option->get_component();
	$composite = $component->get_composite();

	if ( false === $component->hide_component_option_prices() && 'absolute' === $component->get_price_display_format() ) {
		$options_style = $component->get_options_style();

		if ( $options_style == 'thumbnails' ) {

			if ( ! empty( $data['option_price_data']['price'] ) ) {
				$base_price   = WC_CP_Products::get_product_price( $composite, array(
					'price' => $composite->get_price(),
					'calc'  => 'display'
				) );
				$option_price = $data['option_price_data']['price'];

				$old = $data['option_price_html'];

				$min_price = $component_option->min_price;
				$max_price = $component_option->max_price;
				if ( ! is_numeric( $min_price ) ) {
					error_log( __FUNCTION__ . ' non numeric min price? ' . var_export( $data, true ) );
				}

				if ( ! is_numeric( $max_price ) ) {
					error_log( __FUNCTION__ . ' non numeric max price?' . var_export( $data, true ) );
					$max_price = $min_price;
				}

				if ( $min_price == $max_price ) {
					$total_price               = $base_price + $option_price;
					$data['option_price_html'] = wc_price( $total_price );
				} else {
					$min_price                 += $base_price;
					$max_price                 += $base_price;
					$data['option_price_html'] = wc_price( $min_price ) . ' - ' . wc_price( $max_price );
				}
			}
		}
	}

	return $data;
}

add_filter( 'woocommerce_composite_component_option_data', 'sg_find_mockup_thumbnail', 50, 2 );

function sg_find_mockup_thumbnail( $data, $component_option ) {

	$component = $component_option->get_component();
	$composite = $component->get_composite();

	$options_style = $component->get_options_style();

	if ( $options_style == 'thumbnails' ) {

		if ( ! empty( $data['option_price_data']['price'] ) ) {
//			$base_price                = WC_CP_Products::get_product_price( $composite, array(
//				'price' => $composite->get_price(),
//				'calc'  => 'display'
//			) );
//			$option_price              = $data['option_price_data']['price'];
//			$total_price               = $base_price + $option_price;
//			$data['option_price_html'] = wc_price( $total_price );
		}

	}

	return $data;
}

add_filter( 'woocommerce_composite_component_option_data', 'sg_find_portfolio_thumbnail', 55, 2 );

function sg_find_portfolio_thumbnail( $data, $component_option ) {

	$component = $component_option->get_component();
	$composite = $component->get_composite();

	$options_style = $component->get_options_style();

	if ( $options_style == 'thumbnails' ) {

		if ( ! empty( $data['option_price_data']['price'] ) ) {
//			$base_price                = WC_CP_Products::get_product_price( $composite, array(
//				'price' => $composite->get_price(),
//				'calc'  => 'display'
//			) );
//			$option_price              = $data['option_price_data']['price'];
//			$total_price               = $base_price + $option_price;
//			$data['option_price_html'] = wc_price( $total_price );
		}

	}


	return $data;
}


/*
From support ...
We took a look and this what we found out:
Component subtotals are hidden in cart/order templates by this function: https://cl.ly/961a4d1cad6f and not by this: https://cl.ly/1616c41df5a0.
This function: https://cl.ly/1616c41df5a0 prevents component totals from being aggregated next to the parent Composite item, as mentioned in our documentation: https://cl.ly/ab08730f384e. Could you please try removing it?

*/
//add_filter( 'woocommerce_add_composited_order_item_subtotals', 'sg_hide_order_item_subtotals', 10, 3 );
/**
 * @param boolean $show
 * @param         $parent_item
 * @param         $order
 *
 * @return bool
 */
function sg_hide_order_item_subtotals( $show, $parent_item, $order ) {
	$show = false;

	return $show;
}

add_filter( 'woocommerce_format_price_range', 'sg_format_price_range', 99, 3 );
function sg_format_price_range( $price, $from, $to ) {
	/* translators: 1: price from 2: price to */
	$price = sprintf( _x( 'From %1$s', 'Price range: from-to', 'woocommerce' ), is_numeric( $from ) ? wc_price( $from ) : $from);
	return $price;
}


/**
 * Filters whether we're hiding a virtual item from packing lists and pick lists.
 *
 * @since 3.1.1
 *
 * @param bool $hide_virtual_item Whether we're hiding an item or not
 * @param \WC_Product $product Product object
 * @param array|\WC_Order_Item_Product $item Order item
 * @param \WC_Order $order Order object
 */
function sg_show_virtual_items_packing_list( $hide_virtual_item, $product, $item, $order ) {
	return false;
}

add_filter( 'wc_pip_packing-list_hide_virtual_item', 'sg_show_virtual_items_packing_list', 10, 4 );