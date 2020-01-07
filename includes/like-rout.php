<?php

function universityLikeRouts() {
  register_rest_route( 'university/v1', 'manage-like', array(
    'methods' => 'POST',
    'callback' => 'createLike'
  ));

  register_rest_route( 'university/v1', 'manage-like', array(
    'methods' => 'DELETE',
    'callback' => 'deleteLike'
  ));
}

function createLike($data) {
  if(is_user_logged_in()) {
    $professor = $data['professor_id'];

    $existQuery = new WP_Query(array(
      'author' => get_current_user_id(),
      'post_type' => 'like',
      'meta_query' => array(
        array(
          'key' => 'liked_professor_id',
          'compare' => '=',
          'value' => $professor
        )
      )
    ));

    if($existQuery -> found_posts == 0 AND get_post_type($professor) == 'professor') {
      return wp_insert_post( array(
        'post_type' => 'like',
        'post_status' => 'publish',
        'meta_input' => array(
          'liked_professor_id' => $professor
        )
      ));
    } else {
      die(json_encode(array('errorMessage' => 'Invalid professor ID')));
    }
  } else {
    die(json_encode(array('errorMessage' => 'You must log in')));
  }
}

function deleteLike($data) {
  if(is_user_logged_in()) {
    return wp_delete_post( $data['like_id']);
  } else {
    die(json_encode(array('errorMessage' => 'You must log in')));
  }
}

add_action('rest_api_init', 'universityLikeRouts');