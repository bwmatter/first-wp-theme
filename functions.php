<?php

// =========================================================================
// INCLUDE FUNCTION FILES
// =========================================================================

require get_stylesheet_directory() . '/functions/basefunctions.php'; // Custom Set of Cleanup Functions, Optimizations etc.
require get_stylesheet_directory() . '/functions/gf.php'; // Specific Gravity Forms Functions, Filters, Hooks, Tweaks etc
require get_stylesheet_directory() . '/functions/acf.php'; // Specific ACF Functions, Filters, Tweaks, Styling etc

function b4st_setup() {
    add_theme_support( 'editor-styles' );
    add_editor_style('theme/css/editor.css');

    // Gutenberg Blocks
    //add_theme_support( 'wp-block-styles' );
    //add_theme_support( 'align-wide' );

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    update_option('thumbnail_size_w', 285);
    update_option('small_size_w', 0); // Remove WP Default Small Size Thumbnail by making it 0
    update_option('medium_size_w', 0); // Remove WP Default Medium Size Thumbnail by making it 0
    update_option('large_size_w', 0); // Remove WP Default Large Size Thumbnail by making it 0
    update_option('medium_large_w', 0); // Remove WP Default Large Size Thumbnail by making it 0.


    if ( ! isset($content_width) ) {
      $content_width = 1100; // This can be ignored.
    }

    //add_theme_support('automatic-feed-links'); // Leave Uncommented to remove Header Feed resources.
}


// =========================================================================
// ENQUEUE SCRIPTS AND STYLE SHEETS
// =========================================================================


function b4st_enqueues() {
    global $post;

    $my_css_ver = date("ymd-Gis", filemtime( get_stylesheet_directory( __FILE__ ) . '/css/styles.css' )); // add Ver string to css file.

    wp_dequeue_style('b4st-css'); // dequeue master b4st ** To remove when final custom theme without parent is created.

    wp_register_style( 'custom-styles', get_stylesheet_directory_uri() . '/css/styles.css', array(), $my_css_ver, null);
    wp_enqueue_style('custom-styles');

    wp_enqueue_script('jquery');

    wp_enqueue_script( 'jquery-migrate', includes_url('js/jquery/jquery-migrate.min.js'), array(), null, true );

}
add_action('wp_enqueue_scripts', 'b4st_enqueues', 100);


// =========================================================================
// ADD PREFETCH RESOURCES FOR ASSETS NOT ENQUEUED WITH wp_enqueue i.e. GTM, Typekit etc.
// =========================================================================

function change_to_preconnect_resource_hints( $hints, $relation_type ) {

    if ( 'dns-prefetch' === $relation_type ) {
        //$hints[] = '//use.typekit.net'; // add Typekit Script to Prefetch
    }
  
    return $hints;
} 
add_filter( 'wp_resource_hints', 'change_to_preconnect_resource_hints', 10, 2 );


// =========================================================================
// Use WebFontLoader to Load Local Fonts as well as Google Fonts, Typekit etc - https://github.com/typekit/webfontloader#readme
// =========================================================================
function fonts() {
    $stylesheet_directory = get_stylesheet_directory_uri();
?>
<script>
    WebFontConfig = {
		  //typekit: { id: '' }, // uncomment and add TypeKit ID if client is using. Delete if Client is not using
      classes: false, timeout: 2000,
      custom: { // Uncomment and add local Fonts here.
      //families: [''], 
      //urls : ['']
      },
      active: function(){
        sessionStorage.fonts = true;
      }
  };
  (function(d) {
      var wf = d.createElement('script'), s = d.scripts[0];
      wf.src = '<?php echo esc_url(get_stylesheet_directory_uri() . '/js/webfontloader.js'); ?>';
      wf.async = true;
      s.parentNode.insertBefore(wf, s);
   })(document);
  </script>
<?php
}
add_action( 'wp_footer', 'fonts', 99 );



