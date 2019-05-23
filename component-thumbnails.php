<?php
/**
 * Created by PhpStorm.
 * User: jeff
 * Date: 3/16/2019
 * Time: 7:22 PM
 */


//$data[] = apply_filters( 'woocommerce_composite_component_option_data', array(
//	'option_id'             => strval( $product_id ),
//	'option_title'          => $title,
//	'option_price_html'     => $price_html,
//	'option_thumbnail_html' => $thumbnail_html,
//	'option_details_html'   => $details_html,
//	'option_price_data'     => $component_option->get_price_data(),
//	'is_selected'           => $is_selected,
//	'is_in_view'            => false === $is_selected || $is_selected_option_in_view
//), $component_option );

add_filter( 'woocommerce_composite_component_option_data', 'sg_replace_component_thumbnail', 10, 2 );

function sg_replace_component_thumbnail( $component_data, $component_option ) {

	$image_size = 'woocommerce_thumbnail';

	$design_product_id = $component_option->get_composite_id();

	$component_id = $component_data['option_id'];

	$new_thumbnail_id = apply_filters('sg_get_product_image', false, $design_product_id, $component_id );
	if ( ! empty( $new_thumbnail_id ) ) {
		$html = wp_get_attachment_image( $new_thumbnail_id, $image_size );
		$component_data['option_thumbnail_html'] = $html;
	}

	return $component_data;
}