<?php

// =========================================================================
// ACF Options Page
// =========================================================================
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'General Info',
		'menu_title' 	=> 'General Info',
		'menu_slug' 	=> 'general-info',
		'capability' 	=> 'edit_posts',
		'icon_url'      => 'dashicons-editor-ol',
		'redirect' 	=> false,
		'autoload' => true,
		'updated_message' => __("Fields have been updated. Please clear cache by hovering over Delete Cache above and clicking Delete Cache.", 'acf'),
	));

}

function my_acf_init() {
    //acf_update_setting('google_api_key', '');
}
add_action('acf/init', 'my_acf_init');


add_filter('acf/fields/post_object/query', 'my_acf_fields_post_object_query', 10, 3);
function my_acf_fields_post_object_query( $args, $field, $post_id ) {

    $args['post_status'] = 'publish';



    return $args;
}





add_filter( 'acf/fields/wysiwyg/toolbars' , 'my_toolbars'  );
function my_toolbars( $toolbars )
{
	// Uncomment to view format of $toolbars
/*
	echo '<pre>';
		print_r($toolbars);
	echo '</pre>';
	die;
*/

	// Add a new toolbar called "Very Simple"
	// - this toolbar has only 1 row of buttons
	$toolbars['Very Simple'] = array();
	$toolbars['Very Simple'][1] = array('bold' , 'italic' , 'underline', 'link' );

	// Edit the "Full" toolbar and remove 'code'
	// - delet from array code from http://stackoverflow.com/questions/7225070/php-array-delete-by-value-not-key
	//if( ($key = array_search('code' , $toolbars['Full' ][2])) !== false )
	{
	    //unset( $toolbars['Full' ][2][$key] );
	}

	// remove the 'Basic' toolbar completely
	//unset( $toolbars['Basic' ] );

	// return $toolbars - IMPORTANT!
	return $toolbars;
}


function acf_custom_styling() {
?>
<style>
#imageCategory { width: 100% !important; min-height: auto !important; }

/*
 * ACF "repeat-horizontal" class, display repeaters in horizontal columns
 */

@media (min-width: 768px) {
#galleryACF .acf-repeater tbody {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
}
#galleryACF .acf-repeater tr.acf-row:not(.acf-clone) {
    width: 100%;
    flex-grow: 0;
    flex-shrink: 0;
    flex-basis: 24.8%; /* 21% because 25% gives CSS bugs when content pushes width and not 20 because we want the max to be 4 */
    border-bottom: 2px solid #aaa;
}

#galleryACF.galleryACF--thirds .acf-repeater tr.acf-row:not(.acf-clone) {
	flex-basis: 32.75%;

}


#galleryACF .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields {
    width: 100% !important; /* important is necessary because it gets overwritten on drag&drop  */
}
#galleryACF .acf-repeater .acf-row-handle,
#galleryACF .acf-repeater .acf-fields{
    border-width: 0px 0px 0px 1px;
}
#galleryACF .acf-repeater .acf-row-handle.order{
    min-width: 10px; /* to stop breaking layout if we keep the max rows bellow 10 */
}
#galleryACF .acf-repeater .acf-row:last-child .acf-row-handle{
    border-width: 0px;
}
#galleryACF .acf-repeater .acf-row-handle .acf-icon{
    position: relative;
    margin: 10px 0;
}
#galleryACF .acf-repeater .acf-row:hover .acf-row-handle .acf-icon{
    display: none; /* remove standard annoying hover */
}
#galleryACF .acf-repeater .acf-row-handle.remove:hover .acf-icon{
    display: block; /* re-apply hover on set block */
}
#galleryACF .acf-repeater .acf-fields .acf-field-image, #galleryACF .acf-repeater .acf-fields .acf-field-file, #galleryACF .acf-repeater .acf-fields .acf-field-url { min-height: !important; }
#galleryACF .acf-repeater .acf-fields .acf-field-image .acf-label label, #galleryACF .acf-repeater .acf-fields .acf-field-file .acf-label label, #galleryACF .acf-repeater .acf-fields .acf-field-url .acf-label label { display: inline-block !important; margin: 0 5px 0 0 !important; }
#galleryACF .acf-repeater .acf-fields .acf-field-image p.description, #galleryACF .acf-repeater .acf-fields .acf-field-file p.description, #galleryACF .acf-repeater .acf-fields .acf-field-url p.description { display: inline-block !important; }
}

@media (min-width: 768px) {
.halfField { width: calc(50% - 12px); display: inline-block; float: left; clear: none; }
.quarterField { width: calc(25% - 12px); display: inline-block;float: left; clear: none; }
.threequarterField { width: calc(75% - 12px); display: inline-block;float: left; clear: none; }
.twoThirdsField { width: calc(66% - 12px); display: inline-block;float: left; clear: none;}
.oneThirdsField { width: calc(33% - 12px); display: inline-block;float: left; clear: none;}
}
@media (min-width: 11768px) {
#acf-group_60393befaa4b8 .select2-container .select2-selection__rendered {     display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: flex-start;
    align-content: stretch;
    align-items: flex-start; }
	#acf-group_60393befaa4b8 .select2-container .select2-selection__rendered li.select2-selection__choice {     order: 0;
    flex: 0 1 33%;
    align-self: auto;     max-width: 300px; }
	#acf-group_60393befaa4b8 .select2-container .select2-selection__rendered .select2-selection__clear { position: absolute; right: 0; }
}
</style>
<?php
}
add_action('acf/input/admin_footer', 'acf_custom_styling');


