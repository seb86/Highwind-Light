<?php
/**
 * The header template.
 * @package highwind_light
 * @since 1.0.0
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?><?php highwind_html_before(); ?><!doctype html><!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>

	<?php highwind_head_top(); ?>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php wp_title( '/', true, 'right' ); ?></title>

	<!--  Mobile viewport optimized: j.mp/bplateviewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php highwind_head_bottom(); ?>

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php highwind_body_top(); ?>

<div class="outer-wrap" id="top">

	<div class="inner-wrap">

	<?php highwind_header_before(); ?>

	<?php
	if( is_home() || is_front_page() ) {
		$header_image = header_image();
		$header_color = '';
	}
	else{
		if ( is_projects_archive () ) {
			$post_id = projects_get_page_id('projects');
		}
		else{
			$post_id 			= get_the_ID(); // Get the Post ID
		}
		$post_thumbnail_id 		= get_post_thumbnail_id( $post_id ); // Get the Attachment ID from post
		$post_image 			= wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
		$header_image 			= apply_filters( 'highwind_header_featured_image_size', $post_image[0] );
		$header_color 			= get_post_meta( $post_id, 'highwind-header-color', true );
		$header_bg_position_x 	= get_post_meta( $post_id, 'highwind-header-bg-position-x', true );
		$header_bg_position_y 	= get_post_meta( $post_id, 'highwind-header-bg-position-y', true );
		$header_bg_repeat 		= get_post_meta( $post_id, 'highwind-header-bg-repeat', true );

		// If no setting has been found, set default.
		if( empty( $header_bg_position_x ) ) { $header_bg_position_x = 'center'; }
		if( empty( $header_bg_position_y ) ) { $header_bg_position_y = 'center'; }
		if( empty( $header_bg_repeat ) ) { $header_bg_repeat = 'no-repeat'; }
	}
	?>
	<header class="header content-wrapper" role="banner" style="background-color:<?php echo $header_color; ?>; background-image:url(<?php echo $header_image; ?>); background-position-x:<?php echo $header_bg_position_x; ?>; background-position-y:<?php echo $header_bg_position_y; ?>; background-repeat:<?php echo $header_bg_repeat; ?>;">

		<?php highwind_header(); ?>

	</header>

	<div class="content-wrapper">

	<?php highwind_header_after(); ?>

	<?php
	/**
	 * WordPress SEO by Yoast - Breadcrumbs
	 *
	 * @since 1.1.2
	 */
	if ( get_theme_mod( 'highwind_light_breadcrumbs' ) == 'yes' ) {
		if ( function_exists('yoast_breadcrumb') ) {
			yoast_breadcrumb('<p id="breadcrumbs">','</p>');
		}
	}
	?>