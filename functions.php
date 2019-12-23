<?php

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
  add_theme_support('post-thumbnails');
  //
  add_image_size( 'professor-landscape', 360, 240, true );
  add_image_size( 'professor-page-banner', 1500, 350, true );
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
  
  if(is_post_type_archive('program') AND !is_admin() AND $query -> is_main_query()) {
    $query -> set('orderby', 'title');
    $query -> set('order', 'ASC');
  }
}

add_action('pre_get_posts', 'university_adjust_queries');