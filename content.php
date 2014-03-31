<?php
/**
 * The template for displaying posts.
 * @package highwind_light
 * @since 1.0.0
 */
?>

<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<header class="post-header">

	<?php highwind_content_header_top(); ?>

	<?php if ( !is_single() ) : ?>
		<h1 class="post-title" data-text="<?php the_title_attribute(); ?>"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'highwind_light' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	<?php endif; ?>

	<?php highwind_content_header_bottom(); ?>

</header><!-- /.post-header -->

<section class="article-content">

	<?php

		highwind_content_entry_top();

		the_content( __( 'Continue Reading...', 'highwind_light' ) );

		wp_link_pages();

		highwind_content_entry_bottom();

	?>

</section><!-- /.article-content -->