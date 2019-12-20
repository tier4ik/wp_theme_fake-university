<?php
function registerStyles() {
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('main', get_stylesheet_uri());
}

function registerScripts() {
  wp_enqueue_script('main', get_template_directory_uri() . '/js/scripts-bundled.js', array(), false, true);
  wp_enqueue_script('main-custom', get_template_directory_uri() . '/js/main-custom.js', array(), false, true);
}

add_action('wp_enqueue_scripts', 'registerStyles');
add_action('wp_enqueue_scripts', 'registerScripts');

function university_features() {
  //  menu registration
  register_nav_menu('footerExploreMenu', 'Footer Explore Menu');
  register_nav_menu('footerLearnMenu', 'Footer Learn Menu');
  //
  add_theme_support('title-tag');
}

add_action('after_setup_theme', 'university_features');

//  Check if the page have child pages
function has_children() {
  global $post;
  
  $pages = get_pages('child_of=' . $post->ID);
  
  if (count($pages) > 0):
    return true;
  else:
    return false;
  endif;
}
//
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
}

add_action('pre_get_posts', 'university_adjust_queries');