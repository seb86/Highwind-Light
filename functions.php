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
	add_filter( 'projects_show_page_title', 'remove_project_title' ); // Removes the projects title.
}
add_action('after_setup_theme', 'removeParentFunctions');

function remove_project_title(){
	return '';
}

function add_archive_featured_image( ){
	if( !is_single() && !is_page() ){
		add_action( 'highwind_content_entry_top', 'highwind_featured_image', 20 ); // Adds the featured image in to the_content if not on a post or page.
	}
}
add_action( 'highwind_content_entry_top', 'add_archive_featured_image' );

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
			$header_title_color = get_post_meta( $post->ID, 'highwind-header-title-color', true );
			$header_title_hide = get_post_meta( $post->ID, 'highwind-header-hide-title', true );
			if ( !empty( $header_title_hide ) ){ $hide_title = 'text-indent: -9999px;'; }else{ $hide_title = 'text-indent: 0px;'; }
			if( is_404() ) {
				$title = __( '404 not found', 'highwind_light' );
			}
			else if ( is_projects_archive() ) {
				$title = __( 'Projects', 'highwind_light' );
			}
			else{ $title = get_the_title(); }
		?>
			<a href="<?php echo esc_url( get_permalink( $post->ID) ); ?>" title="<?php echo esc_attr( $title ); ?>" rel="home" class="site-intro">
				<?php do_action( 'highwind_site_title_link' ); ?>
				<h1 class="site-title" style="color:<?php echo $header_title_color; ?>;<?php echo $hide_title; ?>"><?php echo esc_attr( $title ); ?></h1>
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

/**
 * Adds a meta box to the post editing screen
 */
function highwind_header_color_picker_meta() {
	add_meta_box( 'highwind_meta', __( 'Highwind Header', 'highwind_light' ), 'highwind_header_color_picker_meta_callback', 'page' );
	add_meta_box( 'highwind_meta', __( 'Highwind Header', 'highwind_light' ), 'highwind_header_color_picker_meta_callback', 'post' );
	add_meta_box( 'highwind_meta', __( 'Highwind Header', 'highwind_light' ), 'highwind_header_color_picker_meta_callback', 'project' );
}
add_action( 'add_meta_boxes', 'highwind_header_color_picker_meta' );

/**
 * Outputs the content of the meta box
 */
function highwind_header_color_picker_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'highwind_nonce' );
	$header_color = get_post_meta( $post->ID, 'highwind-header-color', true );
	$header_title_color = get_post_meta( $post->ID, 'highwind-header-title-color', true );
	$header_title_hide = get_post_meta( $post->ID, 'highwind-header-hide-title', true );
	?>

	<p>
		<div class="highwind-row-content">
			<label for="highwind-header-bg-color" class="highwind-row-title"><?php _e( 'Background Color', 'highwind_light' )?></label>
			<input name="highwind-header-color" type="text" value="<?php if ( isset ( $header_color ) ) echo $header_color; ?>" class="highwind-header-bg-color" />
		</div>
	</p>

	<p>
		<div class="highwind-row-content">
			<label for="highwind-header-title-color" class="highwind-row-title"><?php _e( 'Title Color', 'highwind_light' )?></label>
			<input name="highwind-header-title-color" type="text" value="<?php if ( isset ( $header_title_color ) ) echo $header_title_color; ?>" class="highwind-header-title-color" />
		</div>
	</p>

	<p>
		<div class="highwind-row-content">
			<label for="highwind-header-hide-title" class="highwind-row-title"><?php _e( 'Hide Title', 'highwind_light' )?>
			<input name="highwind-header-hide-title" type="checkbox" value="1"<?php checked($header_title_hide); ?> class="highwind-header-hide-title" />
			</label>
		</div>
	</p>

	<?php
}

/**
 * Saves the custom meta input
 */
function highwind_header_color_picker_meta_save( $post_id ) {
 	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'highwind_nonce' ] ) && wp_verify_nonce( $_POST[ 'highwind_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}

	// Checks for input and saves if needed
	if( isset( $_POST[ 'highwind-header-color' ] ) ) {
		update_post_meta( $post_id, 'highwind-header-color', $_POST[ 'highwind-header-color' ] );
	}

	if( isset( $_POST[ 'highwind-header-title-color' ] ) ) {
		update_post_meta( $post_id, 'highwind-header-title-color', $_POST[ 'highwind-header-title-color' ] );
	}

	if( isset( $_POST[ 'highwind-header-hide-title' ] ) ) {
		update_post_meta( $post_id, 'highwind-header-hide-title', $_POST[ 'highwind-header-hide-title' ] );
	}

}
add_action( 'save_post', 'highwind_header_color_picker_meta_save' );

/**
 * Adds the meta box stylesheet when appropriate
 */
function highwind_header_color_picker_admin_styles(){
	global $typenow;

	if( is_admin() ) {
		if( $typenow == 'page' || $typenow == 'post' || $typenow == 'project' ) {
			wp_enqueue_style( 'highwind_meta_box_styles', get_stylesheet_directory_uri() . '/highwind-meta-box-styles.css' );
		}
	}
}
add_action( 'admin_print_styles', 'highwind_header_color_picker_admin_styles' );

/**
 * Loads the color picker javascript
 */
function enqueue_highwind_header_color_picker() {
	global $typenow;

	if( is_admin() ) {
		if( $typenow == 'page' || $typenow == 'post' || $typenow == 'project' ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'highwind-meta-box-color-js', get_stylesheet_directory_uri() . '/highwind-meta-box-color.js', array( 'wp-color-picker' ) );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'enqueue_highwind_header_color_picker' );

?>