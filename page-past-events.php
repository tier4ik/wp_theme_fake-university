<?php 

get_header();
pageBanner(array(
  'background' => get_template_directory_uri() . '/images/sun-bg.jpg'
));
?>


<div class="container container--narrow page-section">

  <?php 
     $today = date('Ymd');
        
     $pastEvents = new WP_Query(array(
       'post_type' => 'event',
       'paged' => get_query_var('paged', 1),
       'posts_per_page' => -1,
       // сортировка по кастом значению
       // в данном случае 'event_date' from event
       'orderby' => 'meta_value_num',
       'meta_key' => 'event_date',
       // DESC - в обратном порядке
       // ASC- в порядке
       'order' => 'DESC',
       // верни только те евенты, даты которых впереди т.е.
       // event_date >= today
       'meta_query' => array(
         array(
           'key' => 'event_date',
           'compare' => '<',
           'value' => $today,
           'type' => 'numeric'
         )
       )
     ));
    while($pastEvents -> have_posts()) {
      $pastEvents -> the_post();

      get_template_part('template_parts/event');

    }

    wp_reset_postdata();
    
    echo paginate_links(array(
      'total' => $pastEvents -> max_num_pages
    ));
  ?>

</div>

<?php

get_footer();

?>