<?php 

get_header();

?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg');?>);"></div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">All events</h1>
    <div class="page-banner__intro">
      <p>Events! Events everywhere!</p>
    </div>
  </div>  
</div>

<div class="container container--narrow page-section">

  <?php 
    while(have_posts()) {
      the_post();
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
  ?>

</div>

<?php

get_footer();

?>