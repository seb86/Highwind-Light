<?php
/**
 * The template for displaying pages.
 * @package highwind_light
 * @since 1.0.0
 */
?>

<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<header class="page-header">

	<?php highwind_content_header_top(); ?>

	<?php highwind_content_header_bottom(); ?>

</header><!-- /.page-header -->

<section class="article-content">

	<?php

		highwind_content_entry_top();

		the_content();

		wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'highwind_light' ) . '</span>', 'after' => '</div>' ) );

		highwind_content_entry_bottom();

	?>

</section><!-- /.article-content -->