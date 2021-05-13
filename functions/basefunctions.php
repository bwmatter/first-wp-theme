<?php

if ( ! function_exists( 'wp_body_open' ) ) {
    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support WordPress versions prior to 5.2.0.
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action( 'wp_body_open' );
    }
}

// =========================================================================
// Disable Lazy Load of Images
// =========================================================================
add_filter( 'wp_lazy_loading_enabled', '__return_false' );


// =========================================================================
// DISABLE WPAUTOP + REMOVE STRIPPING OF CHARACTER ENTITIES
// =========================================================================
// DISABLE WPAUTOP
add_filter('the_content', 'wpautop_custom', 9);
function wpautop_custom($content) {
    if (is_page()) { 
        //remove_filter( 'the_content', 'wpautop' );
        //remove_filter( 'the_excerpt', 'wpautop' );
        return $content;
    } else {
        return $content;
    }
}
function allow_nbsp_in_tinymce( $mceInit ) {
	  $mceInit['entities'] = '8239,8209';
	  return $mceInit;
}
add_filter( 'tiny_mce_before_init', 'allow_nbsp_in_tinymce' );


// =========================================================================
// Disable AUTO ADD of NOREFERRER to links and add NOOPENER Instead
// =========================================================================
function external_links_rel_customize($rel_values) {
    return 'noopener';
}
add_filter('external_links_rel_customize', 'external_links_rel_customize',999);


// =========================================================================
// REMOVE JQUERY DEPENDENCIES
// =========================================================================
function cedaro_dequeue_jquery_migrate( $scripts ) {
    if ( ! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
      $jquery_dependencies = $scripts->registered['jquery']->deps;
      $scripts->registered['jquery']->deps = array_diff( $jquery_dependencies, array( 'jquery-migrate' ) );
    }
}
add_action( 'wp_default_scripts', 'cedaro_dequeue_jquery_migrate' );


// =========================================================================
// Async load - Included in WP
// =========================================================================
function add_async_attribute($tag, $handle) {
   $scripts_to_async = array('jquery-migrate'); // Add Enqueued Scripts to this array.
   foreach($scripts_to_async as $async_script) {
      if ($async_script !== $handle) return $tag;
    return str_replace(' src', ' async="async" src', $tag);
   }
   return $tag;
}
add_filter('script_loader_tag', 'add_async_attribute', 10, 2);


// Async load - Custom Added or otherwise not working with add_async_attribute
function custom_async_scripts($url) {
    if ( strpos( $url, '#asyncload') === false )
        return $url;
    else if ( is_admin() )
        return str_replace( '#asyncload', '', $url );
    else
	  return str_replace( '#asyncload', '', $url )."' async='async";
}
add_filter( 'clean_url', 'custom_async_scripts', 11, 1 );


function custom_defer_async_scripts($url)
{
    if ( strpos( $url, '#asyncdeferload') === false )
        return $url;
    else if ( is_admin() )
        return str_replace( '#asyncdeferload', '', $url );
    else
	return str_replace( '#asyncdeferload', '', $url )."' defer async='async";
    }
add_filter( 'clean_url', 'custom_defer_async_scripts', 11, 1 );


// =========================================================================
// CLEAN HEADER
// =========================================================================

function crunchify_remove_version() { return ''; }
add_filter('the_generator', 'crunchify_remove_version');

remove_action('wp_head', 'rsd_link'); // remove really simple discovery link
remove_action('wp_head', 'wp_generator'); // remove wordpress version
remove_action('wp_head', 'feed_links', 2); // remove rss feed links (make sure you add them in yourself if youre using feedblitz or an rss service)
remove_action('wp_head', 'feed_links_extra', 3); // removes all extra rss feed links
remove_action('wp_head', 'index_rel_link'); // remove link to index page
remove_action('wp_head', 'wlwmanifest_link'); // remove wlwmanifest.xml (needed to support windows live writer)
remove_action('wp_head', 'start_post_rel_link', 10, 0); // remove random post link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // remove parent post link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // remove the next and previous post links
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 ); // Remove Shortlink

remove_action('wp_head', 'rest_output_link_wp_head', 10); // Remove api.w.org relation link
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10); // Remove oEmbed
remove_action('template_redirect', 'rest_output_link_header', 11, 0);

add_filter( 'emoji_svg_url', '__return_false' ); // Remove s.w.org prefetch

add_filter('xmlrpc_enabled', '__return_false'); // Disable XMLRPC

/**
 * Disable the emoji's
 */
function disable_emojis() {
 remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
 remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
 remove_action( 'wp_print_styles', 'print_emoji_styles' );
 remove_action( 'admin_print_styles', 'print_emoji_styles' );
 remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
 remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
 remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
 add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
 add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
 //remove_action('wp_head', 'wp_resource_hints', 2);
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
 if ( is_array( $plugins ) ) {
 return array_diff( $plugins, array( 'wpemoji' ) );
 } else {
 return array();
 }
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
 if ( 'dns-prefetch' == $relation_type ) {
 /** This filter is documented in wp-includes/formatting.php */
 $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

$urls = array_diff( $urls, array( $emoji_svg_url ) );
 }

return $urls;
}

// Remove 'hentry' class from post_class()
function remove_hentry_class( $classes ) {
  if ( is_page() || is_category() || is_tag() ) {
      $classes = array_diff( $classes, array( 'hentry' ) );
  }
  return $classes;
}
add_filter( 'post_class','remove_hentry_class' );

// Remove wp-embed script (If Website does not need it)
function my_deregister_scripts(){
  wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );

// Remove WP Block Library CSS, as Gutenberg is disabled.
function wps_deregister_styles() {
    wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );

// =========================================================================
// Page Slug Body Class + DISABLE SRCSET IMGS + Add SVG Support
// =========================================================================
function add_slug_body_class($classes) {
    global $post;
      if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
      }
      return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

function meks_disable_srcset($sources) {
    return false;
}
add_filter( 'wp_calculate_image_srcset', 'meks_disable_srcset' );

function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function remove_default_image_sizes($sizes) {
    unset($sizes['large']); // Added to remove 1024
    //unset( $sizes['thumbnail']); // Uncomment to remove Thumbnails as well.
    unset($sizes['medium']);
    unset($sizes['medium_large']);
    unset($sizes['1536x1536']);
    unset($sizes['2048x2048']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'remove_default_image_sizes');



// =========================================================================
// Set Class for Images added through Content Editor
// =========================================================================
function example_add_img_class( $class ) {
    return 'img-fluid';
}
add_filter( 'get_image_tag_class', 'example_add_img_class' );





// =========================================================================
// b4st Overrides -- To Remove once custom singular theme is made.
// =========================================================================
function remove_navbar_search() {
	// Leave empty
}
add_action( 'navbar_search', 'remove_navbar_search' );

function remove_bottomline() {
	// Leave empty
}
add_action( 'bottomline', 'remove_bottomline' );

function remove_footer_after() {
	// Leave empty
}
add_action( 'footer_after', 'remove_footer_after' );

// Remove Query Strings From Static Resources - Leave Uncommented as it unexpectedly strips needed query strings for things like maps.
function b4st_remove_script_version( $src ) {
   //$parts = explode( '?', $src );
return $src;
}
add_filter( 'script_loader_src', 'b4st_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'b4st_remove_script_version', 15, 1 );

// Disable b4st Pagination
function b4st_pagination() { }



// =========================================================================
// Control CSS and JS Versions included in query strings on front end.
// =========================================================================
function remove_cssjs_ver( $src ) {
if ( current_user_can('manage_options') ) return $src;
    if( strpos( $src, '?ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
//add_filter( 'style_loader_src', 'remove_cssjs_ver', 1000 ); // Uncomment when going to production.
add_filter( 'script_loader_src', 'remove_cssjs_ver', 1000 );


// =========================================================================
// Image Functions
// =========================================================================


/* Automatically set the image Title, Alt-Text, Caption & Description upon upload
--------------------------------------------------------------------------------------*/
add_action( 'add_attachment', 'my_set_image_meta_upon_image_upload' );
function my_set_image_meta_upon_image_upload( $post_ID ) {

	// Check if uploaded file is an image, else do nothing

	if ( wp_attachment_is_image($post_ID) ) {

		$my_image_title = get_post($post_ID)->post_title;

		// Sanitize the title:  remove hyphens, underscores & extra spaces:
		$my_image_title = preg_replace('%\s*[-_\s]+\s*%', ' ',  $my_image_title);

		// Sanitize the title:  capitalize first letter of every word (other letters lower case):
		$my_image_title = ucwords(strtolower($my_image_title));


		// Create an array with the image meta (Title, Caption, Description) to be updated
		// Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
		$my_image_meta = array(
			'ID'	          => $post_ID,			    // Specify the image (ID) to be updated
			'post_title'	  => $my_image_title,		// Set image Title to sanitized title
			'post_excerpt'	=> '',		            // Set image Caption (Excerpt) to sanitized title
			'post_content'	=> '',		            // Set image Description (Content) to sanitized title
		);

		// Set the image Alt-Text
		update_post_meta($post_ID, '_wp_attachment_image_alt', $my_image_alt);

		// Set the image meta (e.g. Title, Excerpt, Content)
		wp_update_post($my_image_meta);

	}
}


/**
 * Produces cleaner filenames for uploads
 *
 * @param  string $filename
 * @return string
 */
function sanitize_file_names_for_uploads( $filename ) {

    $sanitized_filename = remove_accents( $filename ); // Convert to ASCII

    // Standard replacements
    $invalid = array(
        ' '   => '-',
        '%20' => '-',
        '_'   => '-',
    );
    $sanitized_filename = str_replace( array_keys( $invalid ), array_values( $invalid ), $sanitized_filename );
    $sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
    $sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
    $sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
    $sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
    $sanitized_filename = strtolower( $sanitized_filename ); // Lowercase

    return $sanitized_filename;
}

add_filter( 'sanitize_file_name', 'sanitize_file_names_for_uploads', 10, 1 );


// =========================================================================
// CUSTOMIZE WIDGETS ON ADMIN DASHBOARD
// =========================================================================
function disable_dashboard_widgets() {
  remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // Remove "At a Glance"
  remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Remove "Activity" which includes "Recent Comments"
  remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Remove Quick Draft
  remove_meta_box('dashboard_primary', 'dashboard', 'core'); // Remove WordPress Events and News
  remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'side'); // Remove Yoast from Dashboard 
  remove_meta_box('rg_forms_dashboard', 'dashboard', 'side'); // Remove Graivty Forms from Dashboard
}
add_action('admin_menu', 'disable_dashboard_widgets');


function remove_dashboard_widgets() {
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // Remove Dashboard Widget 'Quick Press'
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

// Add System Info, PHP Version, Server Load to Dashboard
function custom_system_info() {
    $load = sys_getloadavg(); 
?>
<h3>System Information</h3>
<p><strong>WP Version</strong>: <?php echo get_bloginfo( 'version' ); ?></p>
<p><strong>PHP Version</strong>: <?php echo phpversion(); ?></p>
<p><strong>Your IP Address</strong>: <?php echo esc_html($_SERVER['REMOTE_ADDR']); ?></p>
<p><strong>Server IP Address</strong>: <?php echo esc_html($_SERVER['SERVER_ADDR']); ?></p>
<p><strong>Server Load</strong>: <?php echo $load[0]; ?> <?php echo $load[1]; ?> <?php echo $load[2]; ?></p>
<?php };
function add_your_dashboard_widget() {
  wp_add_dashboard_widget('custom_system_info', __('System Info'), 'custom_system_info');
}
add_action('wp_dashboard_setup', 'add_your_dashboard_widget' );


// =========================================================================
// Require Authentication for All Requests - https://developer.wordpress.org/rest-api/using-the-rest-api/frequently-asked-questions/#require-authentication-for-all-requests
// =========================================================================
add_filter( 'rest_authentication_errors', function( $result ) {
    if ( ! empty( $result ) ) {
        return $result;
    }
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );
    }
    return $result;
});

// =========================================================================
// Disable Password Incorrect for valid user message
// =========================================================================
function no_wordpress_errors(){
    return 'Login and or Password is incorrect.';
}
add_filter( 'login_errors', 'no_wordpress_errors' );


// =========================================================================
// Make Return Path and FROM emails match
// =========================================================================
class email_return_path {
  	function __construct() {
		  add_action( 'phpmailer_init', array( $this, 'fix' ) );
  	}
	  function fix( $phpmailer ) {
	  	$phpmailer->Sender = $phpmailer->From;
	  }
}
new email_return_path();


// =========================================================================
// HIDE EMAIL FROM SPAM BOTS - Useful for Users that want to add emails into posts.
// =========================================================================

// [email]user@domain.com[/email]
function hide_email_from_spambots_shortcode($atts, $content = null) {
	if ( ! is_email( $content ) ) {
		return;
	}
	return '<a rel="nofollow" class="liame emailLink d-inline-block" href="mailto:' . antispambot( $content ) . '"><span itemprop="email">' . antispambot( $content ) . '</span></a>';
}
add_shortcode('email', 'hide_email_from_spambots_shortcode', 99);

// Delete if no global email address is definied on an ACF Options page.
function wpcodex_hide_email_shortcodealt( $atts , $content = null ) {
	$theemail = get_field('email', 'option');
	return '<a rel="nofollow" class="liame text-underline-none hoverLink" href="'. esc_url('mailto:' . antispambot( $theemail ) . '') .'">' . antispambot( $theemail ) . '</a>';
}
add_shortcode( 'emailacf', 'wpcodex_hide_email_shortcodealt', 99 );


// =========================================================================
// Require Excerpts to be added to 'Posts' for when a website requires it. Helpful for clients.
// =========================================================================

/* Note - This is intended for Classic Editor. Must test if Gutenberg is involved. */

/** https://wpartisan.me/tutorials/wordpress-how-to-move-the-excerpt-meta-box-above-the-editor
 * Removes the regular excerpt box. We're not getting rid
 * of it, we're just moving it above the wysiwyg editor
 *
 * @return null
 */
function oz_remove_normal_excerpt() {
    remove_meta_box( 'postexcerpt' , 'post' , 'normal' );
}
add_action( 'admin_menu' , 'oz_remove_normal_excerpt' );

/**
 * Add the excerpt meta box back in with a custom screen location
 *
 * @param  string $post_type
 * @return null
 */
function oz_add_excerpt_meta_box( $post_type ) {
    if ( in_array( $post_type, array( 'post' ) ) ) {
        add_meta_box(
            'oz_postexcerpt',
            __( 'Excerpt', 'thetab-theme' ),
            'post_excerpt_meta_box',
            $post_type,
            'after_title',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'oz_add_excerpt_meta_box' );

/**
 * You can't actually add meta boxes after the title by default in WP so
 * we're being cheeky. We've registered our own meta box position
 * `after_title` onto which we've regiestered our new meta boxes and
 * are now calling them in the `edit_form_after_title` hook which is run
 * after the post tile box is displayed.
 *
 * @return null
 */
function oz_run_after_title_meta_boxes() {
    global $post, $wp_meta_boxes;
    # Output the `below_title` meta boxes:
    do_meta_boxes( get_current_screen(), 'after_title', $post );
}
add_action( 'edit_form_after_title', 'oz_run_after_title_meta_boxes' );


function mandatory_excerpt($data) {
//change your_post_type to post, page, or your custom post type slug
  if ( 'post' == $data['post_type'] ) {

  $excerpt = $data['post_excerpt'];

  if (empty($excerpt)) { // If excerpt field is empty

      // Check if the data is not drafed and trashed
      if ( ( $data['post_status'] != 'draft' ) && ( $data['post_status'] != 'trash' ) ){

        $data['post_status'] = 'draft';

      add_filter('redirect_post_location', 'excerpt_error_message_redirect', '99');

      }
    }
  }

  return $data;
}

add_filter('wp_insert_post_data', 'mandatory_excerpt');

function excerpt_error_message_redirect($location) {

  $location = str_replace('&message=6', '', $location);

  return add_query_arg('excerpt_required', 1, $location);

}

function excerpt_admin_notice() {

  if (!isset($_GET['excerpt_required'])) return;

  switch (absint($_GET['excerpt_required'])) {

    case 1:

      $message = 'Excerpt field is empty! Excerpt is required to publish your blog post.';

      break;

    default:

      $message = 'Unexpected error';
  }

  echo '<div id="notice" class="error"><p>' . $message . '</p></div>';

}
add_action('admin_notices', 'excerpt_admin_notice');


// =========================================================================
// Removes categories from blog posts = https://developer.wordpress.org/reference/functions/unregister_taxonomy_for_object_type/
// =========================================================================

add_action( 'init', 'remove_cats_from_blog_posts' );
function remove_cats_from_blog_posts() {
    unregister_taxonomy_for_object_type('category','post');
}



// =========================================================================
// Create Custom Dropdown in Admin Toolbar to access 'Edit' links of pages.
// =========================================================================

function admin_dropdown_page_list() {
	global $wp_admin_bar;

	$menu_id = 'AllPages';
	$wp_admin_bar->add_menu(array('id' => $menu_id, 'title' => __('All Pages'), 'href' => 'edit.php?post_type=page'));

  $custom = 'page';  // Change this to your custom post type slug ( So for "http://www.example.com/wp-admin/edit.php?post_type=recipes" you would change this to 'recipes'  )

   // Full List of Paramaters - http://codex.wordpress.org/Template_Tags/get_posts
  $args = array(
    'orderby'          => 'title',     // Order by date , title , modified, etc
    'order'            => 'ASC',       // Show most recently edited on top
    'post_type'        => $custom,     // Post Type Slug
    'numberposts'      => -1,          // Number of Posts to Show (Use -1 to Show All)
    'post_status'      => array('publish', 'pending', 'draft', 'future', 'private', 'inherit'),
  );
   $types = get_posts( $args ); // Get All Pages
   foreach ($types as $post_type) {
   $wp_admin_bar->add_menu( // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
     array(
     'parent' => $menu_id,
     'title' => esc_attr(ucwords($post_type->post_title)),
     'id' => $post_type->ID,
     'href' => get_edit_post_link($post_type->ID),
  )
   );
   }
   wp_reset_postdata();
}
add_action('admin_bar_menu', 'admin_dropdown_page_list', 2000);


// Remove unwanted WordPress TinyMCE buttons from first row of native WYSIWYG Editor
function bydik_remove_tinymce_buttons_first_row( $buttons ) {
  global $my_admin_page;
  $screen = get_current_screen();
  if ( is_admin() && (($screen->id == 'page')) ) {
        $buttons = array('bold','italic','underline','link', 'unlink');
        return $buttons;
    }
}
add_filter( 'mce_buttons', 'bydik_remove_tinymce_buttons_first_row', 2000 );

// Remove unwanted WordPress TinyMCE buttons from first row of native WYSIWYG Editor
function bydik_remove_tinymce_buttons_second_row( $buttons ) {
  global $my_admin_page;
  $screen = get_current_screen();
  if ( is_admin() && ($screen->id == 'page') )  {
        $buttons = array();
        return $buttons;
}
}
add_filter( 'mce_buttons_2', 'bydik_remove_tinymce_buttons_second_row', 2020 );

// Remove unwanted WordPress Add Media button from native WYSIWYG editor
function remove_add_media_button(){
      global $my_admin_page;
      $screen = get_current_screen();
      if ( is_admin() && ($screen->id == 'page')) {
          remove_action( 'media_buttons', 'media_buttons' );
      }
}
add_action('admin_head', 'remove_add_media_button');
