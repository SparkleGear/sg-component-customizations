<?php


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

				$data['option_price_html'] = wc_price( $total_price );
				$min_price                 = $component_option->min_price;
				$max_price                 = $component_option->max_price;
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