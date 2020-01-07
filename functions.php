<?php
// import for file
require get_theme_file_path('/includes/search-rout.php');
require get_theme_file_path('/includes/like-rout.php');

// rendering function
function pageBanner($args = NULL) {
  // php logic will live here
    if(!$args['title']) {
      $args['title'] = get_the_title();
    }
    if(!$args['subtitle']) {
      $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if(!$args['background']) {
      if(get_field('page_banner_background_image')['sizes']['professor-page-banner']) {
        $args['background'] = get_field('page_banner_background_image')['sizes']['professor-page-banner'];
      } else {
        $args['background'] = get_template_directory_uri() . '/images/ocean.jpg';
      }
    }
  ?>
  <!-- HTML template -->
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['background']; ?>);">
    </div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>  
  </div>

  <?php
}

// register css
function registerStyles() {
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('main', get_stylesheet_uri());
}

// register js
function registerScripts() {
  wp_enqueue_script('main', get_template_directory_uri() . '/js/scripts-bundled.js', array(), false, true);
  wp_enqueue_script('main-custom', get_template_directory_uri() . '/js/main-custom.js', array(), false, true);

  //  get access to wp variable inside our custom JS file
  wp_localize_script( 'main-custom', 'universityData', array(
    'root_url' => get_site_url(),
    'nonce' => wp_create_nonce( 'wp_rest' )
  ));
}

add_action('wp_enqueue_scripts', 'registerStyles');
add_action('wp_enqueue_scripts', 'registerScripts');

// register menu and some features
function university_features() {
  //  menu registration
  register_nav_menu('footerExploreMenu', 'Footer Explore Menu');
  register_nav_menu('footerLearnMenu', 'Footer Learn Menu');
  //
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  //
  add_image_size( 'professor-landscape', 360, 240, true );
  add_image_size( 'professor-page-banner', 1500, 350, true );
}

add_action('after_setup_theme', 'university_features');

// check if the page have child pages helper-function
function has_children() {
  global $post;
  
  $pages = get_pages('child_of=' . $post->ID);
  
  if (count($pages) > 0):
    return true;
  else:
    return false;
  endif;
}

// set custom settings to invoke before each query (new WP_Query)
function university_adjust_queries($query) {

  $today = date('Ymd');

  if(is_post_type_archive('event') AND !is_admin() AND $query -> is_main_query()) {
    $query -> set('meta_key', 'event_date');
    $query -> set('orderby', 'meta_value_num');
    $query -> set('order', 'ASC');
    $query -> set('meta_query', array(
      array(
        'key' => 'event_date',
        'compare' => '>=',
        'value' => $today,
        'type' => 'numeric'
      )
    ));
  }
  
  if(is_post_type_archive('program') AND !is_admin() AND $query -> is_main_query()) {
    $query -> set('orderby', 'title');
    $query -> set('order', 'ASC');
  }
}

add_action('pre_get_posts', 'university_adjust_queries');

//  Apply google API key for google maps
function universityMapKey($api) {
  $api['key'] = 'google_api_key';
  return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey', );

// add new route for our project
function university_custom_rest() {
  register_rest_field( 'post', 'author_name', array(
    'get_callback' => function() {
      return get_the_author();
    }
  ));
}

add_action('rest_api_init', 'university_custom_rest');
<<<<<<< HEAD

// redirect subscriber accounts from admin to homepage
function redirectSubsToHomepage() {
  $currentUser = wp_get_current_user();
  if(count($currentUser -> roles) == 1 AND $currentUser -> roles[0] == 'subscriber') {
    wp_redirect(site_url('/'));
    exit;
  }
}

add_action( 'admin_init', 'redirectSubsToHomepage');

// hide admin bar if the user is a subscriber
function hideAdminBar() {
  $currentUser = wp_get_current_user();
  if(count($currentUser -> roles) == 1 AND $currentUser -> roles[0] == 'subscriber') {
    show_admin_bar( false );
  }
}

add_action( 'wp_loaded', 'hideAdminBar');

// customize login screen img url
function ourHeaderURL() {
  return esc_url(site_url('/'));
} 

add_filter('login_headerurl', 'ourHeaderURL');

// подключаем наши стили к странице login.php
function customLoginCSS() {
  wp_enqueue_style('font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('main', get_stylesheet_uri());
}

add_action('login_enqueue_scripts', 'customLoginCSS');

// customize login screen main text
function ourHeaderText() {
  return get_bloginfo('title');
}
add_filter( 'login_headertext', 'ourHeaderText' );

// force note posts to be private on server-side
function notePostsFilter($data, $postarr) {
  if($data['post_type'] === 'note') {
    // allow current user to create only 2 note-posts
    if(count_user_posts( get_current_user_id(), 'note') == 2 AND !$postarr['ID']) {
      die(json_encode(array('limitError' => 'You have reached your notes limit')));
    }
    // remove all HTML tags before save in DB
    $data['post_title'] = sanitize_text_field( $data['post_title'] );
    $data['post_content'] = sanitize_textarea_field( $data['post_content'] );
  }

  if($data['post_type'] === 'note' AND $data['post_status'] !== 'trash') {
    $data['post_status'] = 'private';
  }
  return $data;
}

add_filter('wp_insert_post_data', 'notePostsFilter', 10, 2);
=======
>>>>>>> f8121abcf5d3323a42edb96be87c33e018089768
