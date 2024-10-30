<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'lisa_allowed_data_sources' ) ) {

	function lisa_allowed_data_sources( $data_source ) {
		$allowed_data_sources = (array) apply_filters( 'lisa_data_sources', array(
			'single'	=> __( 'Single', 'lisa' ),
			'query'		=> __( 'Query', 'lisa' )
		) );

		if ( array_key_exists( $data_source, $allowed_data_sources ) ) {
			return $data_source;
		} else {
			return 'single';
		}
	}

}

if ( ! function_exists( 'lisa_allowed_html' ) ) {

	function lisa_allowed_html() {
		$allowed = wp_kses_allowed_html( 'post' );

		// iframe
		$allowed['iframe'] = array(
			'src'             => array(),
			'height'          => array(),
			'width'           => array(),
			'frameborder'     => array(),
			'allowfullscreen' => array(),
		);

		// form fields - input
		$allowed['input'] = array(
			'class' => array(),
			'id'    => array(),
			'name'  => array(),
			'value' => array(),
			'type'  => array(),
		);

		// select
		$allowed['select'] = array(
			'class'  => array(),
			'id'     => array(),
			'name'   => array(),
			'value'  => array(),
			'type'   => array(),
		);

		// select options
		$allowed['option'] = array(
			'selected' => array(),
		);

		// style
		$allowed['style'] = array(
			'types' => array(),
		);

		return $allowed;
	}

}

if ( ! function_exists( 'lisa_kses' ) ) {
	function lisa_kses( $content ) {
		$content = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $content );
		$content = wp_kses( $content, lisa_allowed_html() );
		return trim( $content );
	}
}
