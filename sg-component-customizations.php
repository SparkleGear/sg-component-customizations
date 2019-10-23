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
		$data['hide_subtotal_product'] = 'yes';
		$data['priced_individually']   = 'yes';
	}

	// always do this
	$data['hide_subtotal_cart']    = 'yes';
	$data['hide_subtotal_orders']  = 'yes';

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
				$base_price = WC_CP_Products::get_product_price( $composite, array(
					'price' => $composite->get_price(),
					'calc'  => 'display'
				) );

				if ( ! is_numeric( $base_price ) ) {
					error_log( __FUNCTION__ . ' ERROR: non-numeric base price - this should not happen!' );
					error_log( var_export( $data, true ) );
				} else {

					$option_price = $data['option_price_data']['price'];

					// $old = $data['option_price_html'];

					$min_price = $component_option->min_price;
					$max_price = $component_option->max_price;
					if ( ! is_numeric( $min_price ) ) {
						$min_price = $max_price;
					}

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
	$price = sprintf( _x( 'From %1$s', 'Price range: from-to', 'woocommerce' ), is_numeric( $from ) ? wc_price( $from ) : $from );

	return $price;
}


/**
 * Filters whether we're hiding a virtual item from packing lists and pick lists.
 *
 * @param bool                         $hide_virtual_item Whether we're hiding an item or not
 * @param \WC_Product                  $product Product object
 * @param array|\WC_Order_Item_Product $item Order item
 * @param \WC_Order                    $order Order object
 *
 * @since 3.1.1
 *
 */
function sg_show_virtual_items_packing_list( $hide_virtual_item, $product, $item, $order ) {
	return false;
}

add_filter( 'wc_pip_packing-list_hide_virtual_item', 'sg_show_virtual_items_packing_list', 10, 4 );

//add_filter( 'woocommerce_product_is_taxable', 'sg_is_composite_taxable', 10, 2 );

/**
 * @param boolean              $is_taxable
 * @param WC_Product_Composite $product
 *
 * @return boolean
 */
function sg_is_composite_taxable( $is_taxable, $product ) {

	if ( ! empty( $product ) && is_callable( array( $product, 'is_type' ) ) && $product->is_type( 'composite' ) ) {

		$x = __FUNCTION__ . ' START composite ' . $product->get_title() . ' is ' . ( $is_taxable ? '' : 'not ' ) . 'taxable';
		error_log( $x );

		$ids = $product->get_component_ids();
		foreach ( $ids as $id ) {
			$component = $product->get_component( $id );

			if ( property_exists( $component, 'products' ) ) {
				$products = $component->products;
				if ( is_array( $products ) ) {
					foreach ( $products as $id => &$cp_product ) {
						$component_product = &$cp_product->product;
						$composite_product = &$cp_product->composite;
						if ( $component_product ) {
							$component_is_taxable = ( $component_product->get_tax_status() === 'taxable' ) && wc_tax_enabled();
							// TODO: Make this hack go way, should be configuration driven
							// if any component is not taxable, then make the composite product not taxable
							//					if ( $is_taxable && ! $component_is_taxable ) {
							//						$is_taxable = false;
							//						error_log( __FUNCTION__ . ' setting tax status based on non-taxable component');
							//					}

							$sets_composite_tax_status = (boolean) $component_product->get_meta( 'sg_sets_composite_tax_status', true );
							if ( $sets_composite_tax_status ) {
								error_log( __FUNCTION__ . ' setting tax status based on meta sg_sets_composite_tax_status' );
								$is_taxable = $component_is_taxable;
							}

							// set the tax status of a composite product based on the tax status of the main blank item
							$product_id = $component_product->get_id();
							if ( has_term( 'blank-accessories', 'product_cat', $product_id ) ) {
								error_log( __FUNCTION__ . ' setting tax status based on blank item ACCESSORY' );
								$is_taxable = true;
								$component->products[ $id ]->composite->set_tax_status( 'taxable' );
								$component->products[ $id ]->product->set_tax_status( 'taxable' );
							}

							if ( has_term( 'blank-apparel', 'product_cat', $product_id ) ) {
								error_log( __FUNCTION__ . ' setting tax status based on blank item APPAREL' );
								$is_taxable = false;
								$component->products[ $id ]->composite->set_tax_status( 'none' );
								$component->products[ $id ]->product->set_tax_status( 'none' );
							}

							$m = __FUNCTION__ . ' END component ' . $component_product->get_title() . ' is ' . ( $component_is_taxable ? '' : 'not ' ) . 'taxable';
							error_log( $m );

							$x = __FUNCTION__ . ' END composite ' . $product->get_title() . ' is ' . ( $is_taxable ? '' : 'not ' ) . 'taxable';
							error_log( $x );

//							$cp_product->set_product( $component_product );
						}
					}
				}
			}
		}
	} else {

		// set the tax status of a composite product based on the tax status of the main blank item
		$m = __FUNCTION__ . ' NOT COMPOSITE START product ' . $product->get_title() . ' is ' . ( $is_taxable ? '' : 'not ' ) . 'taxable';
		error_log( $m );

		$product_id = $product->get_id();
		$parent_id  = $product->get_parent_id();
		if ( $parent_id ) {
			$product_id = $parent_id;
		}

		if ( has_term( 'blank-accessories', 'product_cat', $product_id ) ) {
			error_log( __FUNCTION__ . ' setting tax status based on blank item ACCESSORY' );
			$is_taxable = true;
		} else if ( has_term( 'blank-apparel', 'product_cat', $product_id ) ) {
			error_log( __FUNCTION__ . ' setting tax status based on blank item APPAREL' );
			$is_taxable = false;
		}
//		else 	if ( has_term( array( 'blank-apparel','blank-accessories') , 'product_cat', $product_id ) ) {
//			error_log( __FUNCTION__ . ' setting tax status based on blank item');
//			remove_filter( 'woocommerce_product_is_taxable', 'sg_is_composite_taxable', 10 );
//			$is_taxable = $product->get_tax_status();
//			add_filter( 'woocommerce_product_is_taxable', 'sg_is_composite_taxable', 10, 2 );
//		}

		$m = __FUNCTION__ . ' NOT COMPOSITE END product ' . $product->get_title() . ' is ' . ( $is_taxable ? '' : 'not ' ) . 'taxable';
		error_log( $m );

	}

	return $is_taxable;
}
