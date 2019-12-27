<?php
// callback function for universityRegisterSearch function
function universitySearchResults($request) {
  $mainQuery = new WP_Query(array(
    'post_type' => array('post','page','professor', 'program', 'event'),
    's' => sanitize_text_field($request['name'])
  ));

  $results = array(
    'posts_and_pages' => array(),
    'professors' => array(),
    'programs' => array(),
    'events' => array()
  );
  
  while($mainQuery -> have_posts()) {
    $mainQuery -> the_post();
    if(get_post_type() === 'post' OR get_post_type() === 'page') {
      array_push($results['posts_and_pages'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'author' => (function() {
          if(get_post_type() === 'post') {
            return get_the_author();
          } else {
            return NULL;
          }
        })(),
        'author_url' => (function() {
          if(get_post_type() === 'post') {
            $author_id = get_the_author_meta( 'ID' );
            $author_link = get_author_posts_url( $author_id );
            return $author_link;
          } else {
            return NULL;
          }
        })()
      ));
    }
    if(get_post_type() === 'professor') {
      array_push($results['professors'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink()
      ));
    }
    if(get_post_type() === 'event') {
      array_push($results['events'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink()
      ));
    }
    if(get_post_type() === 'program') {
      array_push($results['programs'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink()
      ));
    }
  }

  return $results;
}

// new search route registration
function universityRegisterSearch() {
  register_rest_route( 'university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE, // GET analog
    'callback' => 'universitySearchResults'
  ));
}

add_action('rest_api_init', 'universityRegisterSearch');