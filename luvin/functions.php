<?php
/*
 *  Author: M:Eye Social
 *  URL: http://www.meyesocial.com
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
	Functions
\*------------------------------------*/

require_once(dirname( __FILE__ ) . '/functions/vcomposer.php');

// Navigation
function meye_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

function register_meye_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'meye'), // Main Navigation        
    ));
}

// Filter wp_nav_menu() to add additional links and other output
function new_nav_menu_items($items) 
{
	if (qtranxf_getLanguage() == 'en') {
		$langs = '<li class="menu-item-lang"><a class="navlink" href="/pt/">PT</a> | <b>EN</b></li>';
	}
	else{
		$langs = '<li class="menu-item-lang"><b>PT</b> | <a class="navlink" href="/en/">EN</a></li>';
	}
    
    $items = $items.$langs;
    return $items;
}
add_filter( 'wp_nav_menu_items', 'new_nav_menu_items' );

// scripts (header.php)
function meye_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {    	     

		wp_register_script('bootstrap-scripts', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '1.0.0'); 
        wp_enqueue_script('bootstrap-scripts'); 
		
		wp_register_script('sticky', get_template_directory_uri() . '/js/jquery.sticky.js', array('jquery'), '1.0.0'); 
        wp_enqueue_script('sticky'); 

        wp_register_script('meye-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0.0'); 
        wp_enqueue_script('meye-scripts'); 
		wp_localize_script( 'meye-scripts', 'ajax_posts', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'noposts' => __('No projects found', 'meye'),
		));
		
    }
}

// styles
function meye_styles()
{  
	wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '1.0', 'all');
    wp_enqueue_style('bootstrap'); 		
	
    wp_register_style('generic', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('generic'); 
	
	wp_register_style('luvin', get_template_directory_uri() . '/css/luvin.css', array(), '1.0', 'all');
    wp_enqueue_style('luvin'); 
}


// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists

if (function_exists('register_sidebar'))
{
	
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area', 'meye'),
        'description' => __('General area', 'meye'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));	
   
}


// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function meye_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}



// Custom View Article link to Post
function meye_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'meye') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}


/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'meye_scripts'); // Add Scripts to wp_head
add_action('wp_enqueue_scripts', 'meye_styles'); // Add Theme Stylesheet
add_action('init', 'register_meye_menu'); 
add_action('init', 'meye_pagination'); 

// Add Filters
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

add_theme_support( 'post-thumbnails' );
add_image_size( 'luvinpeople-thumb', 379, 283, true);



// Shortcodes


// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

/*** LUVIN PEOPLE ***/

add_action('init', 'create_post_type_luvinpeople'); 
function create_post_type_luvinpeople()
{
    register_taxonomy_for_object_type('category', 'luvinpeople'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'luvinpeople');
    register_post_type('luvinpeople', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Luvin People', 'meye'), // Rename these to suit
            'singular_name' => __('Luvin People Post', 'meye'),
            'add_new' => __('Add New', 'meye'),
            'add_new_item' => __('Add New Luvin People Post', 'meye'),
            'edit' => __('Edit', 'meye'),
            'edit_item' => __('Edit Luvin People Post', 'meye'),
            'new_item' => __('New Luvin People Post', 'meye'),
            'view' => __('View Luvin People Post', 'meye'),
            'view_item' => __('View Luvin People Post', 'meye'),
            'search_items' => __('Search Luvin People Post', 'meye'),
            'not_found' => __('No Luvin People Posts found', 'meye'),
            'not_found_in_trash' => __('No Luvin People Posts found in Trash', 'meye')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'thumbnail',
			'custom_fields'
        ), 
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}
// Add the Luvin People Meta Boxes
add_action( 'add_meta_boxes', 'add_luvinpeople_metaboxes' );
function add_luvinpeople_metaboxes() {
	add_meta_box('wpt_lp_facebook', 'Facebook username', 'wpt_lp_facebook', 'luvinpeople', 'normal', 'default');
	add_meta_box('wpt_lp_instagram', 'Instagram username', 'wpt_lp_instagram', 'luvinpeople', 'normal', 'default');
}
// The Luvin People Facebook Metabox
function wpt_lp_facebook() {
	global $post;
	echo '<input type="hidden" name="lp_facebook" id="lp_facebook" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$facebook = get_post_meta($post->ID, '_facebook', true);
	echo '<input type="text" name="_facebook" value="' . $facebook  . '" class="widefat translate" />';
}
// The Luvin People Instagram Metabox
function wpt_lp_instagram() {
	global $post;
	echo '<input type="hidden" name="lp_instagram" id="lp_instagram" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$instagram = get_post_meta($post->ID, '_instagram', true);
	echo '<input type="text" name="_instagram" value="' . $instagram  . '" class="widefat translate" />';
}

// Save the Facebook Metabox Data
function wpt_save_lp_facebook_meta($post_id, $post) {
	if ( !wp_verify_nonce( $_POST['lp_facebook'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	$fb_meta['_facebook'] = $_POST['_facebook'];
	foreach ($fb_meta as $key => $value) { 
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}
add_action('save_post', 'wpt_save_lp_facebook_meta', 1, 2); // save the custom fields

// Save the Instagram Metabox Data
function wpt_save_lp_instagram_meta($post_id, $post) {
	if ( !wp_verify_nonce( $_POST['lp_instagram'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	$in_meta['_instagram'] = $_POST['_instagram'];
	foreach ($in_meta as $key => $value) { 
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}
add_action('save_post', 'wpt_save_lp_instagram_meta', 1, 2); // save the custom fields



/*** OUR CLIENTS ***/

add_action('init', 'create_post_type_ourclients'); 
function create_post_type_ourclients()
{
    register_taxonomy_for_object_type('category', 'ourclients'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'ourclients');
    register_post_type('ourclients', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Our Clients', 'meye'), // Rename these to suit
            'singular_name' => __('Client', 'meye'),
            'add_new' => __('Add New', 'meye'),
            'add_new_item' => __('Add New Client', 'meye'),
            'edit' => __('Edit', 'meye'),
            'edit_item' => __('Edit Client', 'meye'),
            'new_item' => __('New Client', 'meye'),
            'view' => __('View Clients', 'meye'),
            'view_item' => __('View Client', 'meye'),
            'search_items' => __('Search Client', 'meye'),
            'not_found' => __('No Clients found', 'meye'),
            'not_found_in_trash' => __('No Clients found in Trash', 'meye')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'thumbnail'
        ), 
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}


/*** OUR PROJECTS ***/

add_action('init', 'create_post_type_ourprojects'); 
function create_post_type_ourprojects()
{
    register_taxonomy_for_object_type('category', 'ourprojects'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'ourprojects');
    register_post_type('ourprojects', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Our Projects', 'meye'), // Rename these to suit
            'singular_name' => __('Projects', 'meye'),
            'add_new' => __('Add New', 'meye'),
            'add_new_item' => __('Add New Project', 'meye'),
            'edit' => __('Edit', 'meye'),
            'edit_item' => __('Edit Project', 'meye'),
            'new_item' => __('New Project', 'meye'),
            'view' => __('View Projects', 'meye'),
            'view_item' => __('View Project', 'meye'),
            'search_items' => __('Search Project', 'meye'),
            'not_found' => __('No Projects found', 'meye'),
            'not_found_in_trash' => __('No Projects found in Trash', 'meye')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'thumbnail',
			'custom_fields'
        ), 
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}
// Add the projects youtube link Meta Boxes
add_action( 'add_meta_boxes', 'add_ourprojects_metaboxes' );
function add_ourprojects_metaboxes() {
	add_meta_box('wpt_op_youtube', 'Youtube link ID', 'wpt_op_youtube', 'ourprojects', 'normal', 'default');
	add_meta_box('wpt_op_desc', 'Project`s description', 'wpt_op_desc', 'ourprojects', 'normal', 'default');		
}
// The Projects Youtube link Metabox
function wpt_op_youtube() {
	global $post;
	echo '<input type="hidden" name="op_youtube" id="op_youtube" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$youtube = get_post_meta($post->ID, '_youtube', true);
	echo 'https://www.youtube.com/watch?v=<input type="text" name="_youtube" value="' . $youtube  . '" class="widefat translate" style="width:120px;" />';
	echo '<p>You must include <u><i>only the video ID</i></u>. Example: https://www.youtube.com/watch?v=<u><b>123456</b></u></p>';
}
// Save the Projects Youtube link Metabox Data
function wpt_save_op_youtube_meta($post_id, $post) {
	if ( !wp_verify_nonce( $_POST['op_youtube'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	$yt_meta['_youtube'] = $_POST['_youtube'];
	foreach ($yt_meta as $key => $value) { 
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}
add_action('save_post', 'wpt_save_op_youtube_meta', 1, 2); // save the custom fields

// The Projects description Metabox
function wpt_op_desc() {
	global $post;
	echo '<input type="hidden" name="op_desc" id="op_desc" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$desc = get_post_meta($post->ID, '_desc', true);
	echo '<textarea name="_desc" class="widefat translate">' . $desc  . '</textarea>';
	echo '<p>Small description for project</p>';
}
// Save the Projects Youtube link Metabox Data
function wpt_save_op_desc_meta($post_id, $post) {
	if ( !wp_verify_nonce( $_POST['op_desc'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	$desc_meta['_desc'] = $_POST['_desc'];
	foreach ($desc_meta as $key => $value) { 
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}
add_action('save_post', 'wpt_save_op_desc_meta', 1, 2); // save the custom fields



/*------------------------------------*\
	LOAD POSTS BY AJAX
\*------------------------------------*/

function more_post_ajax(){

    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 3;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;

    header("Content-Type: text/html");

    $args = array(
        'suppress_filters' => true,
        'post_type' => 'ourprojects',
        'posts_per_page' => $ppp,
        'paged'    => $page,
        //'offset'    => ($ppp-1)*$page,
        'offset'    => (($ppp*$page)-2),
    );

    $loop = new WP_Query($args);
	$counter = 0;
    $out = '<div class="clearfix"></div>';
    while ( $loop->have_posts() ) : $loop->the_post();	
		$pmeta = get_post_meta( get_the_ID() );
		$yt = $pmeta['_youtube'][0];	
		$desc = $pmeta['_desc'][0];
		$counter++;
		if($yt == '' && !has_post_thumbnail()){
			//ALL EMPTY
			continue;
		}
		else{						
				if($yt != ''){
					// IS YOUTUBE VIDEO
					$out .= '
                    <div class="col-sm-4 single" >
                        <div class="video-container">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/'.$yt.'?modestbranding=1&enablejsapi=1&iv_load_policy=3&disablekb=1&wmode=transparent&controls=0&playsinline=0&showinfo=0&rel=0&autoplay=0&loop=0" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <div class="proj-title">
                            '.get_the_title().'
                        </div>
                        <div class="proj-desc">
                            '.$desc.'
                        </div>
                    </div>
					';
				}
				else{
					// IS PHOTO
					$out .= '
                    <div class="col-sm-4 single" >
                        <div class="image-proj" style="background-image:url('.the_post_thumbnail_url('full').');">
                            <img src="'.get_template_directory_uri().'/img/spacer.png" class="vid-spacer" />                            
                        </div>
                        <div class="proj-title">
                            '.get_the_title().'
                        </div>
                        <div class="proj-desc">
                            '.$desc.'
                        </div>
                    </div>
					';
				}			
		}
	endwhile;
    wp_reset_postdata();
    die($counter."|->|".$out);
}

add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax');
add_action('wp_ajax_more_post_ajax', 'more_post_ajax');





/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/



?>
