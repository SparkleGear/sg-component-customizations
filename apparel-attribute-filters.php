<?php
add_filter( 'woocommerce_composite_component_data', 'sg_apparel_component_filter', 10, 3 );

function sg_apparel_component_filter( $data, $i, $composite ) {

	if ( 'Apparel or Accessory' == $data['title'] || 'apparel_that_fits' == $data['query_type'] ) {

		$item_type_attribute_id = wc_attribute_taxonomy_id_by_name( 'sg-item-type' );
		$item_style_attribute_id = wc_attribute_taxonomy_id_by_name( 'sg-item-style' );

		if ( $item_style_attribute_id && $item_type_attribute_id ) {

			$data['show_filters']      = 'yes';
			$data['attribute_filters'] = array( $item_type_attribute_id, $item_style_attribute_id );
		}
	}

	return $data;
}
