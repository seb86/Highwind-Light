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
			// Prevents any errors when on viewing a 404 page.
			if( !is_404() ) {
				if ( is_projects_archive() ) {
					$header_title_color = '#ffffff';
					$header_title_hide = '';
				}
				else{
					$header_title_color = get_post_meta( $post->ID, 'highwind-header-title-color', true );
					$header_title_hide = get_post_meta( $post->ID, 'highwind-header-hide-title', true );
				}
			}
			else{
				$header_title_color = '';
				$header_title_hide = '';
			}

			if ( !empty( $header_title_hide ) ){ $hide_title = 'text-indent: -9999px;'; }else{ $hide_title = 'text-indent: 0px;'; }

			// Titles
			if( is_404() ) {
				$title = __( '404 not found', 'highwind_light' );
			}
			else if ( is_projects_archive() ) {
				$title = __( 'Projects', 'highwind_light' );
			}
			else if ( is_archive() ) {
				$title = __( 'Archive', 'highwind_light' );
			}
			else if ( is_category() ) {
				$title = __( 'Category', 'highwind_light' );
			}
			else if ( is_tag() ) {
				$title = __( 'Tag', 'highwind_light' );
			}
			else{ $title = get_the_title(); }

			// Page links
			if( !is_404() ) {
				if ( is_projects_archive() ) {
					$page_link = get_post_type_archive_link( 'projects' );
				}
				else{
					$page_link = get_permalink( $post->ID );
				}
			}
			else{
				$page_link = home_url( '/' );
			}
		?>
			<a href="<?php echo esc_url( $page_link ); ?>" title="<?php echo esc_attr( $title ); ?>" rel="home" class="site-intro">
				<?php do_action( 'highwind_site_title_link' ); ?>
				<h1 class="site-title" style="color:<?php echo $header_title_color; ?>;<?php echo $hide_title; ?>"><?php echo esc_attr( $title ); ?></h1>
				<h2 class="site-description"><?php esc_attr( $post->post_excerpt ); ?></h2>
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
	$header_bg_position_x = get_post_meta( $post->ID, 'highwind-header-bg-position-x', true );
	$header_bg_position_y = get_post_meta( $post->ID, 'highwind-header-bg-position-y', true );
	$header_bg_repeat = get_post_meta( $post->ID, 'highwind-header-bg-repeat', true );
	$header_title_color = get_post_meta( $post->ID, 'highwind-header-title-color', true );
	$header_title_hide = get_post_meta( $post->ID, 'highwind-header-hide-title', true );

	if( empty( $header_bg_position_x ) ) { $header_bg_position_x = 'center'; }
	if( empty( $header_bg_position_y ) ) { $header_bg_position_y = 'center'; }
	if( empty( $header_bg_repeat ) ) { $header_bg_repeat = 'no-repeat'; }
	?>

	<div class="highwind-row-content">
		<label for="highwind-header-bg-color" class="highwind-row-title"><?php _e( 'Background Color', 'highwind_light' )?></label>
		<input name="highwind-header-color" type="text" value="<?php if ( isset ( $header_color ) ) echo $header_color; ?>" class="highwind-header-bg-color" />
	</div>

	<div class="highwind-row-content">
		<label for="highwind-header-bg-position" class="highwind-row-title"><?php _e( 'Background Position (X)', 'highwind_light' )?></label>
		<label for="highwind-header-bg-position-x" class="highwind-row-option">
		<input name="highwind-header-bg-position-x" type="radio" value="left"<?php checked($header_bg_position_x, 'left'); ?> class="highwind-header-bg-position" />
		<?php _e( 'Left', 'highwind_light' )?>
		</label>
		<label for="highwind-header-bg-position-x" class="highwind-row-option">
		<input name="highwind-header-bg-position-x" type="radio" value="center"<?php checked($header_bg_position_x, 'center'); ?> class="highwind-header-bg-position" />
		<?php _e( 'Center', 'highwind_light' )?>
		</label>
		<label for="highwind-header-bg-position-x" class="highwind-row-option">
		<input name="highwind-header-bg-position-x" type="radio" value="right"<?php checked($header_bg_position_x, 'right'); ?> class="highwind-header-bg-position" />
		<?php _e( 'Right', 'highwind_light' )?>
		</label><br>
		<label for="highwind-header-bg-position" class="highwind-row-title"><?php _e( 'Background Position (Y)', 'highwind_light' )?></label>
		<label for="highwind-header-bg-position-y" class="highwind-row-option">
		<input name="highwind-header-bg-position-y" type="radio" value="top"<?php checked($header_bg_position_y, 'top'); ?> class="highwind-header-bg-position" />
		<?php _e( 'Top', 'highwind_light' )?>
		</label>
		<label for="highwind-header-bg-position-y" class="highwind-row-option">
		<input name="highwind-header-bg-position-y" type="radio" value="center"<?php checked($header_bg_position_y, 'center'); ?> class="highwind-header-bg-position" />
		<?php _e( 'Center', 'highwind_light' )?>
		</label>
		<label for="highwind-header-bg-position-y" class="highwind-row-option">
		<input name="highwind-header-bg-position-y" type="radio" value="right"<?php checked($header_bg_position_y, 'bottom'); ?> class="highwind-header-bg-position" />
		<?php _e( 'Bottom', 'highwind_light' )?>
		</label>
	</div>

	<div class="highwind-row-content">
		<label for="highwind-header-bg-repeat" class="highwind-row-title"><?php _e( 'Background Repeat', 'highwind_light' )?></label>
		<label for="highwind-header-bg-repeat" class="highwind-row-option">
		<input name="highwind-header-bg-repeat" type="radio" value="no-repeat"<?php checked($header_bg_repeat, 'no-repeat'); ?> class="highwind-header-bg-repeat" />
		<?php _e( 'No-Repeat', 'highwind_light' )?>
		</label>
		<label for="highwind-header-bg-repeat" class="highwind-row-option">
		<input name="highwind-header-bg-repeat" type="radio" value="repeat"<?php checked($header_bg_repeat, 'repeat'); ?> class="highwind-header-bg-repeat" />
		<?php _e( 'Repeat', 'highwind_light' )?>
		</label>
		<label for="highwind-header-bg-repeat" class="highwind-row-option">
		<input name="highwind-header-bg-repeat" type="radio" value="repeat-x"<?php checked($header_bg_repeat, 'right-x'); ?> class="highwind-header-bg-repeat" />
		<?php _e( 'Right-X', 'highwind_light' )?>
		</label>
		<label for="highwind-header-bg-repeat" class="highwind-row-option">
		<input name="highwind-header-bg-repeat" type="radio" value="repeat-y"<?php checked($header_bg_repeat, 'right-y'); ?> class="highwind-header-bg-repeat" />
		<?php _e( 'Right-Y', 'highwind_light' )?>
		</label>
	</div>

	<div class="highwind-row-content">
		<label for="highwind-header-title-color" class="highwind-row-title"><?php _e( 'Title Color', 'highwind_light' )?></label>
		<input name="highwind-header-title-color" type="text" value="<?php if ( isset ( $header_title_color ) ) echo $header_title_color; ?>" class="highwind-header-title-color" />
	</div>

	<div class="highwind-row-content">
		<label for="highwind-header-hide-title" class="highwind-row-title"><?php _e( 'Hide Title', 'highwind_light' )?>
		<input name="highwind-header-hide-title" type="checkbox" value="1"<?php checked($header_title_hide); ?> class="highwind-header-hide-title" />
		</label>
	</div>

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

	if( isset( $_POST[ 'highwind-header-bg-position-x' ] ) ) {
		update_post_meta( $post_id, 'highwind-header-bg-position-x', $_POST[ 'highwind-header-bg-position-x' ] );
	}
	if( isset( $_POST[ 'highwind-header-bg-position-y' ] ) ) {
		update_post_meta( $post_id, 'highwind-header-bg-position-y', $_POST[ 'highwind-header-bg-position-y' ] );
	}
	if( isset( $_POST[ 'highwind-header-bg-repeat' ] ) ) {
		update_post_meta( $post_id, 'highwind-header-bg-repeat', $_POST[ 'highwind-header-bg-repeat' ] );
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

/** 
 * Adds the ability to add a featured image 
 * to categories and taxonomies.
 *
 * @since 1.1.2
 */

// create custom field for taxonomies
add_action( 'admin_init', 'highwind_light_taxonomies_add_form_fields', 999 );
add_action( 'admin_init', 'highwind_light_taxonomies_save_form_fields', 999 );

// ajax action
add_action( 'wp_ajax_highwind-light-remove-image', 'highwind_light_ajax_set_post_thumbnail' );

function highwind_light_taxonomies_add_form_fields(){
	add_action( 'category_add_form_fields', 'highwind_light_taxonomies_add_meta', 10, 2 );
	add_action( 'category_edit_form_fields', 'highwind_light_taxonomies_edit_meta', 10, 2 );
	add_action( 'post_tag_add_form_fields', 'highwind_light_taxonomies_add_meta', 10, 2 );
	add_action( 'post_tag_edit_form_fields', 'highwind_light_taxonomies_edit_meta', 10, 2 );
}

//edit term page
function highwind_light_taxonomies_edit_meta( $term ) {
	global $wpdb;

	// put the term ID into a variable
	$term_id = $term->term_id;
	$post = get_default_post_to_edit( 'post', true );
	$post_ID = $post->ID;
?>
	<tr class="form-field">
		<th><?php _e('Set Featured Image', 'highwind_light'); ?></th>
		<td>
			<div id="postimagediv" class="postbox" style="width:95%;" >
				<div class="inside">
					<?php wp_enqueue_media( array('post' => $post_ID) ); ?>
					<?php
					$thumbnail_id = get_option( '_highwind_light_taxonomy_term_'.$term_id.'_thumbnail_id_', 0 );
					echo _wp_post_thumbnail_html( $thumbnail_id, $post_ID );
					?>
				</div>
				<input type="hidden" name="highwind_light_taxonomies_edit_post_ID" id="highwind_light_taxonomies_edit_post_ID_id" value="<?php echo $post_ID; ?>" />
				<input type="hidden" name="highwind_light_taxonomies_edit_term_ID" id="highwind_light_taxonomies_edit_term_ID_id" value="<?php echo $term_id; ?>" />
			</div>
		</td>
	</tr>
<?php
}

function highwind_light_taxonomies_save_form_fields(){
	add_action('edited_category', 'highwind_light_taxonomies_save_meta', 10, 2 );
	add_action('edited_post_tag', 'highwind_light_taxonomies_save_meta', 10, 2 );
}

function highwind_light_taxonomies_save_meta( $term_id ) {
	if ( isset( $_POST['highwind_light_taxonomies_create_post_ID'] ) ) {
		$default_post_ID = $_POST['highwind_light_taxonomies_create_post_ID'];
	}
	else if ( isset( $_POST['highwind_light_taxonomies_edit_post_ID'] ) ) {
		$default_post_ID = $_POST['highwind_light_taxonomies_edit_post_ID'];
	}
	$thumbnail_id = get_post_meta( $default_post_ID, '_thumbnail_id', true );
	if( $thumbnail_id ){
		update_option( '_highwind_light_taxonomy_term_'.$term_id.'_thumbnail_id_', $thumbnail_id );
	}
}

function highwind_light_ajax_set_post_thumbnail() {
	global $current_user;

	if ( $current_user->ID < 0 ){
		wp_die( 'ERROR:You are not allowed to do the operation.' );
	}
	$post_ID = intval( $_POST['post_id'] );
	if ( $post_ID < 1 ){
		wp_die( "ERROR:Invalid post ID.".$post_ID );
	}
	delete_post_thumbnail( $post_ID );

	$thumbnail_id = intval( $_POST['thumbnail_id'] );
	if ( $thumbnail_id == '-1' ){
		//delete option which used to saving thumbnail id
		if( $_POST['term_id'] > 0 ){
			delete_option( '_highwind_light_taxonomy_term_'.$_POST['term_id'].'_thumbnail_id_' );
		}
		$return = _wp_post_thumbnail_html( null, $post_ID );
		wp_die( $return );

	}
	wp_die( "ERROR" );
}

/**
 * Adds additional theme options to the theme customizer.
 * 
 * @since 1.1.2
 */
function highwind_light_theme_customizer( $wp_customize ) {
	// Add default settings for breadcrumbs.
	$wp_customize->add_setting( 'highwind_light_breadcrumbs', array(
		'type' 				=> 'option',
		'default' 			=> apply_filters('highwind_light_breadcrumbs', 'yes' ),
		'sanitize_callback' => 'sanitize_key',
	) );

	// Add Breadcrumbs section
	$wp_customize->add_section( 'highwind_light_breadcrumbs_section' , array(
		'title' 		=> __( 'Breadcrumbs', 'highwind_light' ),
		'priority' 		=> 30,
		'description' 	=> __('Choose if you want to show the breadcrumbs under the header.', 'highwind_light'),
	) );

	// Add controller for breadcrumbs and display options.
	$breadcrumbs = array(
						'yes' => array(
							'value' => 'yes',
							'label' => __( 'Yes', 'highwind_light' ),
						),
						'no' => array(
							'value' => 'no',
							'label' => __( 'No', 'highwind_light' ),
						),
					);
	$choices = array();
	foreach ( $breadcrumbs as $breadcrumb ) {
		$choices[$breadcrumb['value']] = $breadcrumb['label'];
	}
	$wp_customize->add_control( 'highwind_light_breadcrumbs', array(
		'label' 	=> __( 'Enable Breadcrumbs?', 'highwind_light' ),
		'section' 	=> 'highwind_light_breadcrumbs_section',
		'type' 		=> 'radio',
		'settings' 	=> 'highwind_light_breadcrumbs',
		'choices' 	=> $choices,
	) );
}
add_action('customize_register', 'highwind_light_theme_customizer');

?>