<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'lisa_shortcode' ) ) :

  function lisa_shortcode( $atts, $content = null, $tag ) {
    $atts = shortcode_atts( array(
      'id' => NULL
  	), $atts, $tag );

    // WP_Query arguments
		$args = array(
			'post_type'				=> array( 'lisa_template' ),
			'post_status'			=> array( 'publish' ),
			'order'						=> 'ASC',
			'orderby'					=> 'menu_order',
			'posts_per_page' 	=> 1,
		);

    if ( empty( $atts['id'] ) ) {
      // ID not specified, autoload it is..

      return sprintf( '<p>%s</p>', __( 'Please select a template to render.', 'lisa' ) );

    } else {
      // An ID was specified, load that template.
      $args['p'] = $atts['id'];
    }

		// The Query
		$query = new WP_Query( $args );

		// The Loop
		if ( $query->have_posts() ) {

			// Setup Timber before we change the loop.
			$context = Timber::get_context();
			$post = new TimberPost();

			while ( $query->have_posts() ) : $query->the_post();
        $source = lisa_allowed_data_sources( get_post_meta( get_the_ID(), '_lisa_data_source', true ) );

        $code = lisa_kses( get_post_meta( get_the_ID(), '_lisa_template_code', true ) );

        $placement = get_post_meta( get_the_ID(), '_lisa_attribute_placement', true );

        if ( $source == 'single' ) {

          $context['post'] = $post;
          $context['content'] = $content;

        } elseif ( $source == 'query' ) {
          $defaults = array(
            'post_type'				=> 'post',
            'posts_per_page'	=> 5,
            'post_status'			=> 'publish'
          );

          $data_query = (array) get_post_meta( get_the_ID(), '_lisa_data_query', true );

          $args = array_merge( $defaults, $data_query );

          $context['posts'] = new Timber\PostQuery( $args );
        }

        $code = Timber::compile_string( $code, $context );

        if ( $placement === 'prepend' ) {
          $content = $code . $content;
        } elseif ( $placement === 'append' ) {
          $content .= $code;
        } elseif ( $placement === 'replace' ) {
          $content = $code;
        }
			endwhile;
		}

		// Restore original Post Data
		wp_reset_postdata();

    return $content;
  }

endif;
