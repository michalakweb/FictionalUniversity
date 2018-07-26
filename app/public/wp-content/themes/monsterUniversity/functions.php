<?php 

require get_theme_file_path('/incl/search-route.php');

require get_theme_file_path('/incl/like-route.php');

//Add custom field to wp-json

function university_custom_rest() {
	register_rest_field('post', 'authorname', array(
		'get_callback' => function() {return get_author_name();}
	));
	
	register_rest_field('note', 'userNoteCount', array(
		'get_callback' => function() {return count_user_posts(get_current_user_id(), 'note');}
	));
}

add_action('rest_api_init', 'university_custom_rest');

// Add scripts and stylesheets

function startwordpress_scripts() {
	wp_enqueue_style( 'main', get_template_directory_uri() . '/main.css', null, microtime() );
	wp_enqueue_style( 'socialicons', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
	wp_enqueue_script( 'googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyAiD_QIXDaMVZ3CWE_iIq8jjjgnH2hNzLs', null, '1.0', true);
	wp_enqueue_script( 'main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), null, microtime() , true);
	wp_localize_script('main-university-js', 'universityData', array(
		'root_url' => get_site_url(),
		'nonce' => wp_create_nonce('wp_rest')
	));
}

add_action( 'wp_enqueue_scripts', 'startwordpress_scripts' );

//Customize title  and featured images

function customizeTitle() {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_image_size('professorLandscape', 400, 260, true);
	add_image_size('professorPortrait', 480, 650, true);
	add_image_size('pageBanner', 1500, 350, true);
}

add_action( 'after_setup_theme', 'customizeTitle');


//Add registered navigation

function registeredMenu() {
	register_nav_menu('headerMenu', 'Header Menu Location');
	register_nav_menu('footerMenu1', 'Footer Menu Location1');
	register_nav_menu('footerMenu2', 'Footer Menu Location2');
}

add_action( 'after_setup_theme', 'registeredMenu');


//New "program" post type
function program_post_types() {
	register_post_type('program', array(
		'supports' => array(
			'title', 
		),
		'has_archive' => true,
		'public' => true,
		'menu_icon' => 'dashicons-welcome-learn-more',
		'labels' => array(
			'name' => 'Programs',
			'add_new_item' => 'Add New Program',
			'edit_item' => 'Edit Program'
		)
	)); 
}

add_action('init', 'program_post_types');

//New "events" post type
function university_post_types() {
	register_post_type('event', array(
		'capability_type' => 'event',
		'map_meta_cap' => true,
		'supports' => array(
			'title', 'editor', 'excerpt',
		),
		'has_archive' => true,
		'public' => true,
		'menu_icon' => 'dashicons-calendar-alt',
		'labels' => array(
			'name' => 'Events',
			'add_new_item' => 'Add New Event',
			'edit_item' => 'Edit Event'
		)
	)); 
}



add_action('init', 'university_post_types');

//New "Professor" post type
function professor_post_types() {
	register_post_type('professor', array(
		'show_in_rest' => true,
		'supports' => array(
			'title', 'editor', 'excerpt', 'thumbnail'
		),
		'has_archive' => false,
		'public' => true,
		'menu_icon' => 'dashicons-universal-access',
		'labels' => array(
			'name' => 'Professors',
			'add_new_item' => 'Add New Professor',
			'edit_item' => 'Edit Professor'
		)
	)); 
}

add_action('init', 'professor_post_types');

//New "Campus" post type
function campus_post_types() {
	register_post_type('campus', array(
		'supports' => array(
			'title', 'editor', 'excerpt', 'thumbnail'
		),
		'rewrite' => array('slug' => 'campuses'),
		'has_archive' => true,
		'public' => true,
		'menu_icon' => 'dashicons-location-alt',
		'labels' => array(
			'name' => 'Campuses',
			'add_new_item' => 'Add New Campus',
			'edit_item' => 'Edit Campus'
		)
	)); 
}

add_action('init', 'campus_post_types');

function universityMapKey($api) {
	$api['key'] = 'AIzaSyAiD_QIXDaMVZ3CWE_iIq8jjjgnH2hNzLs';
	return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');

//Customizing a default Events query
function filterEventArchives($query) {
	if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
		$today = date('Ymd');
		$query->set('meta_key', 'event_date');
		$query->set('orderby', 'meta_value_num');
		$query->set('order', 'ASC');
		$query->set('meta_query', array(
							array(
								'key' => 'event_date',
								'compare' => '>=',
								'value' => $today,
								'type' => 'numeric'
							)
						));
	}
}

add_action('pre_get_posts', 'filterEventArchives');

//Customizing a default Program query
function filterProgramArchives($query) {
	if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
		$query->set('posts_per_page', '-1');
	}
}

add_action('pre_get_posts', 'filterProgramArchives');

//Page Banner re-usable function

function pageBanner($args = NULL) {
	if(!$args['title']) {
		$args['title'] = get_the_title();
	}
	if(!$args['subtitle']) {
		$args['subtitle'] = get_field('page_banner_subtitle');
	}
	if(!$args['photo']) {
		if(get_field('page_banner_background_image')) {
			$args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
		}
		else {
			$args['photo'] = get_theme_file_uri('/images/ocean.jpg');
		}
	}
	?>
		<div class="page-banner">
			<div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>"></div>
			<div class="page-banner__content container container--narrow">
				<h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
				<div class="page-banner__intro">
					<p><?php echo $args['subtitle']; ?></p>
				</div>
			</div>  
		</div>
	
	
	<?php
}

//Redirect users out of the admin dashboard
function redirectSubsToFrontend() {
	$ourCurrentUser = wp_get_current_user();
	
	if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
		wp_redirect(site_url('/'));
		exit;
	}
}

add_action('admin_init', 'redirectSubsToFrontend');

//Deletes the admin bar for subscribers
function noSubsAdminBar() {
	$ourCurrentUser = wp_get_current_user();

	if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
		show_admin_bar(false);
	}
}

add_action('wp_loaded', 'noSubsAdminBar');

//Custom login screen
function ourHeaderUrl() {
	return esc_url(site_url('/'));
}

add_filter('login_headerurl', 'ourHeaderUrl');


function ourLoginCSS() {
	wp_enqueue_style( 'main', get_template_directory_uri() . '/main.css');
}
add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginTitle() {
	return get_bloginfo('name');
}

add_filter('login_headertitle', 'ourLoginTitle');

//New "Note" post type
function note_post_types() {
	register_post_type('note', array(
		'capability_type' => 'note',
		'map_meta_cap' => true,
		'show_in_rest' => true,
		'supports' => array(
			'title', 'editor',
		),
		'show_ui' => true,
		'has_archive' => false,
		'public' => false,
		'menu_icon' => 'dashicons-welcome-write-blog',
		'labels' => array(
			'name' => 'Notes',
			'add_new_item' => 'Add New Note',
			'edit_item' => 'Edit Note'
		)
	)); 
}

add_action('init', 'note_post_types');

//Force Note posts to be private

function makeNotePrivate($data, $postarr) {
	if($data['post_type'] == 'note') {
		if(count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID']) {
			die("You reached your note limit");
		}
		
		$data['post_content'] = sanitize_textarea_field($data['post_content']);
		$data['post_title'] = sanitize_text_field($data['post_title']);
	}
	
	if($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
		$data['post_status'] = "private";
	}
	
	return $data;
}

add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

//New Like post type
function like_post_types() {
	register_post_type('like', array(
		'supports' => array(
			'title',
		),
		'show_ui' => true,
		'has_archive' => false,
		'public' => false,
		'menu_icon' => 'dashicons-heart',
		'labels' => array(
			'name' => 'Likes',
			'add_new_item' => 'Add New Like',
			'edit_item' => 'Edit Like'
		)
	)); 
}

add_action('init', 'like_post_types');


