<?php

/**
 * Include and setup custom metaboxes and fields.
 *
 * @category WP Map Markers
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */
add_filter( 'cmb_meta_boxes', 'cmb_wpmm_metaboxes' );

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_wpmm_metaboxes( array $meta_boxes ) {
     $images = wpmm_get_marker_images();
    //var_dump($images); die();

    foreach($images as $image){
        $image_name =  basename($image, '.png');
         $choices[] = array( 'name' => __( $image_name ,'wpmm-map-markers' ), 'value' => $image_name );
    }
    //var_dump($choices); die();
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_wpmm_';

	$meta_boxes[] = array(
		'id' => 'wpmm_metabox',
		'title' => __('Map Markers Options','wpmm-map-markers'),
		'pages' => array( 'wpmm_location', ), // Post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Location name','wpmm-map-markers'),
				'desc' => __('the name of the location','wpmm-map-markers'),
				'id' => $prefix . 'location_name',
				'type' => 'text',
			),
			array(
				'name' => __('Phone','wpmm-map-markers'),
				'desc' => __('phone number','wpmm-map-markers'),
				'id' => 'phone',
				'type' => 'text',
			),
			array(
				'name' => __('Territories','wpmm-map-markers'),
				'desc' => __('territories','wpmm-map-markers'),
				'id' => 'territories',
				'type' => 'text',
			),
			array(
				'name' => __('Display on map','wpmm-map-markers'),
				'desc' => __('check to display this location on the global map','wpmm-map-markers'),
				'id' => $prefix . 'displayonmap',
				'std' => true,
				'type' => 'checkbox',
				
			),
			array(
				'name' => __('Latitude','wpmm-map-markers'),
				'desc' => __('the latitude of the location','wpmm-map-markers'),
				'id' => $prefix . 'latitude',
				'type' => 'text',
			),
			array(
				'name' => __('Longitude','wpmm-map-markers'),
				'desc' => __('the longitude of the location','wpmm-map-markers'),
				'id' => $prefix . 'longitude',
				'type' => 'text',
			),
			array(
				'name' => __('Marker icon','wpmm-map-markers'),
				'desc' => __('select the marker icon','wpmm-map-markers'),
				'id' => $prefix . 'marker_icon',
				'type' => 'radio',
				'options' => $choices,
				'std' => 'blue-marker'
			),
		),
	);

	// Add other metaboxes as needed

	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );

/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( !class_exists( 'cmb_Meta_Box' ) )
		require_once 'metabox/init.php';
}

add_filter( 'cmb_meta_box_url', 'windows_cmb_meta_box_url' );

function windows_cmb_meta_box_url( $url ) {
	return trailingslashit( str_replace( '\\', '/', str_replace( str_replace( '/', '\\', WP_CONTENT_DIR ), WP_CONTENT_URL, $url ) ) );
}

function wpmm_get_marker_images(){
    $directory = WPMM_DIR . '/markers/';
    //get all image files with a .jpg extension.
    $images = glob($directory . "*.png");

    return $images;
}
