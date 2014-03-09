<?php
/**
 * The page template.
 * @package highwind light
 * @since 1.1.1
 */
?>

<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
get_header();
?>

<?php highwind_content_before(); ?>

<section class="content" role="main">

	<?php highwind_content_top(); ?>

	<?php get_template_part( 'content', 'projects' ); ?>

	<?php highwind_content_bottom(); ?>

</section><!-- /.content -->

<?php get_footer(); ?>