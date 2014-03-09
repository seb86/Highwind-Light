<?php
// Localisation
load_theme_textdomain( 'highwind_light', get_template_directory() . '/languages' );

// Add excerpt support to pages.
add_action('init', 'admin_page_support');
function admin_page_support() {
	add_post_type_support( 'page', 'excerpt' );
}

// Removes default Highwind functions and features.
function removeParentFunctions() {
	add_filter( 'highwind_header_gravatar', '__return_false' ); // This removes the Gravatar from the header. Delete this line if you still want to use it.
	remove_action( 'highwind_content_entry_top', 'highwind_featured_image', 20 ); // Removes the featured image from the_content.
	remove_action( 'highwind_footer', 'highwind_credit', 20 ); // Removes parent credit footer.
}
add_action('after_setup_theme', 'removeParentFunctions');

/**
 * Site title
 * Displays the site title and description if on the Homepage,
 * else it displays the page title and excerpt.
 * Hooked into highwind_header()
 * @since 1.0.0
 */
if ( ! function_exists( 'highwind_site_title' ) ) {
	function highwind_site_title() {
		global $wp_query, $post;
		if( is_home() ) {
		?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="site-intro">
				<?php
					do_action( 'highwind_site_title_link' );
					if ( apply_filters( 'highwind_header_gravatar', true ) ) {
						echo get_avatar( apply_filters( 'highwind_header_gravatar_email', $email = esc_attr( get_option( 'admin_email' ) ) ), 256, '', esc_attr( get_bloginfo( 'name' ) ) );
					}
					?>
				<h1 class="site-title"><?php esc_attr( bloginfo( 'name' ) ); ?></h1>
				<h2 class="site-description"><?php esc_attr( bloginfo( 'description' ) ); ?></h2>
			</a>
		<?php
		}
		else{
		?>
			<a href="<?php echo esc_url( get_permalink( $post->id) ); ?>" title="<?php esc_attr( the_title() ); ?>" rel="home" class="site-intro">
				<?php do_action( 'highwind_site_title_link' ); ?>
				<h1 class="site-title"><?php esc_attr( the_title() ); ?></h1>
				<h2 class="site-description"><?php esc_attr( the_excerpt() ); ?></h2>
			</a>
		<?php
		}
	}
}

/**
 * Credit
 * Hooked into highwind_footer
 * @since 1.0.0
 */
add_action( 'highwind_footer', 'highwind_light_credit', 20 );
if ( ! function_exists( 'highwind_light_credit' ) ) {
	function highwind_light_credit() {
	?>
	<p>
		<?php _e( 'Powered by', 'highwind_light' ); ?> <a href="http://wordpress.org" title="WordPress.org">WordPress</a> &amp; <a href="http://www.sebastiendumont.com/highwind-light/" title="<?php _e( 'Highwind Light - Child Theme', 'highwind_light' ); ?>">Highwind Light</a>.
	</p>
	<?php
	}
}

?>