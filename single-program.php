<?php 

get_header();

?>

<?php
  while(have_posts()) {
      the_post();
      pageBanner();
      ?>
  
  <div class="container container--narrow page-section">
  <div class="metabox metabox--position-up metabox--with-home-link">
    <p>
      <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program')?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a>
      <span class="metabox__main"><?php the_title(); ?></span>
    </p>
  </div>
  <div class="post-item">
    <p><?php the_content() ;?></p>
  </div>
  <?php
    $relatedProfessors = new WP_Query(array(
      'post_type' => 'professor',
      'meta_query' => array(
        array(
          'key' => 'related_programs',
          // LIKE аналогично JS some
          'compare' => 'LIKE',
          'value' => '"' . get_the_ID() . '"',
        )
      )
    ));
    if($relatedProfessors -> have_posts()) {
      echo "<ul class='professor-cards'>";
      while($relatedProfessors -> have_posts()) {
        $relatedProfessors -> the_post();?>

        <li class="professor-card__list-item">
          <a class="professor-card" href="<?php the_permalink(); ?>">
            <img class="professor-card__image" src="<?php the_post_thumbnail_url('professor-landscape'); ?>" alt="professor photo">
            <span class="professor-card__name"><?php the_title(); ?></span>
          </a>
        </li>

      <?php
      }
      echo "</ul>";
    }

    wp_reset_postdata();
    //============================
    $today = date('Ymd');
  
    $pageEvents = new WP_Query(array(
      'post_type' => 'event',
      // сортировка по кастом значению
      // в данном случае 'event_date' from event
      'orderby' => 'meta_value_num',
      'meta_key' => 'event_date',
      // DESC - в обратном порядке
      // ASC- в порядке
      'order' => 'ASC',
      // верни только те евенты, даты которых впереди т.е.
      // event_date >= today
      'meta_query' => array(
        array(
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        ),
        array(
          'key' => 'related_program',
          // LIKE аналогично JS some
          'compare' => 'LIKE',
          'value' => '"' . get_the_ID() . '"',
        )
      )
    ));

    echo "<h2 class='headline headline--medium'>Upcoming <u>" . get_the_title() . "</u> events:</h2>";
    while($pageEvents->have_posts()) {
      $pageEvents -> the_post();

      get_template_part('template_parts/event');

    }

    wp_reset_postdata();
    
  }

get_footer();

?>