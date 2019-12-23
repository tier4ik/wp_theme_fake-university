<?php 

get_header();

?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg');?>);"></div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">Past Events</h1>
    <div class="page-banner__intro">
      <p>These events have already passed.</p>
    </div>
  </div>  
</div>

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
      // Load field value.
      $date_string = get_field('event_date');

      // Create DateTime object from value (formats must match).
      $date = DateTime::createFromFormat('d/m/Y', $date_string);
  ?>
    
    <div class="event-summary">
      <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
        <span class="event-summary__month"><?php echo $date->format('M'); ?></span>
        <span class="event-summary__day"><?php echo $date->format('d'); ?></span>
      </a>
      <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><span><?php echo $date->format('Y'); ?></span>: <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
        <p><?php 
          if(has_excerpt()) {
            echo get_the_excerpt();
          }else{
            echo wp_trim_words( get_the_content(), 18);
          }
        ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
      </div>
    </div>

  <?php
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