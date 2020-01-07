<?php

function theme_post_types() {
  // Event post type
  register_post_type('event', array(
    // new permissions only for 'note' post type, not for POST
    // using for event-manager role in user roles
    'capability_type' => 'event',
    'map_meta_cap' => true,
    //
    'public' => true,
    'has_archive' => true,
    'menu_icon' => 'dashicons-calendar-alt',
    'rewrite' => array('slug' => 'events'),
    'labels' => array(
        'name' => 'Events',
        'singular_name' => 'Event',
        'add_new_item' => 'Add new Event',
        'edit_item' => 'Edit the Event',
        'new_item' => 'New Event',
        'view_item' => 'View Event',
        'view_items' => 'View Events',
        'all_items' => 'All Events'
    ),
    'supports' => array('title', 'editor', 'author', 'excerpt')
    ));
    // Program post type
    register_post_type('program', array(
      'public' => true,
      'has_archive' => true,
      'menu_icon' => 'dashicons-awards',
      'rewrite' => array('slug' => 'programs'),
      'labels' => array(
          'name' => 'Programs',
          'singular_name' => 'Program',
          'add_new_item' => 'Add new Program',
          'edit_item' => 'Edit the Program',
          'new_item' => 'New Program',
          'view_item' => 'View Program',
          'view_items' => 'View Programs',
          'all_items' => 'All Programs'
      ),
      'supports' => array('title', 'editor')
      )); 
    // Professor post type
    register_post_type('professor', array(
      'public' => true,
      'menu_icon' => 'dashicons-welcome-learn-more',
      'labels' => array(
          'name' => 'Professors',
          'singular_name' => 'Professor',
          'add_new_item' => 'Add new Professor',
          'edit_item' => 'Edit the Professor',
          'new_item' => 'New Professor',
          'view_item' => 'View Professor',
          'view_items' => 'View Professors',
          'all_items' => 'All Professors'
      ),
      'supports' => array('title', 'editor', 'thumbnail')
      )); 
    // Campus post type
    register_post_type('campus', array(
      'public' => true,
      // show this post type in rest api
      'show_in_rest' => true, 
      'menu_icon' => 'dashicons-location-alt',
      'has_archive' => true,
      'rewrite' => array('slug' => 'campuses'),
      'labels' => array(
        'name' => 'Campuses',
        'singular_name' => 'Campus',
        'add_new_item' => 'Add new Campus',
          'edit_item' => 'Edit the Campus',
          'new_item' => 'New Campus',
          'view_item' => 'View Campus',
          'view_items' => 'View Campuses',
          'all_items' => 'All Campuses'
      ),
      'supports' => array('title', 'editor', 'thumbnail')
      )); 
    // Note post type
    register_post_type('note', array(
      // new permissions only for 'note' post type, not for POST
      'capability_type' => 'note',
      'map_meta_cap' => true,
      //
      'show_in_rest' => true,
      'public' => false,
      'show_ui' => true,
      'menu_icon' => 'dashicons-welcome-write-blog',
      'labels' => array(
          'name' => 'Notes',
          'singular_name' => 'Note',
          'add_new_item' => 'Add new Note',
          'edit_item' => 'Edit the Note',
          'new_item' => 'New Note',
          'view_item' => 'View Note',
          'view_items' => 'View Notes',
          'all_items' => 'All Notes'
      ),
      'supports' => array('title', 'editor')
      ));

    // Like post type
    register_post_type('like', array(
      'public' => false,
      'show_ui' => true,
      'menu_icon' => 'dashicons-heart',
      'labels' => array(
          'name' => 'Likes',
          'singular_name' => 'Like',
          'add_new_item' => 'Add new Like',
          'edit_item' => 'Edit the Like',
          'new_item' => 'New Like',
          'view_item' => 'View Like',
          'view_items' => 'View Likes',
          'all_items' => 'All Likes'
      ),
      'supports' => array('title')
      )); 
}

add_action('init', 'theme_post_types');