<?php 

get_header();

?>

<?php
  while(have_posts()) {
      the_post();
      pageBanner();
?>
  
  <div class="container container--narrow page-section">
  
  <div class="generic-content">
    <div class="row group">
      <div class="one-third"><?php the_post_thumbnail(); ?></div>
      <div class="two-thirds">
        
          <?php
            $likes = new WP_Query(array(
              'post_type' => 'like',
              'meta_query' => array(
                array(
                  'key' => 'liked_professor_id',
                  'compare' => '=',
                  'value' => get_the_ID()
                )
              )
            ));

            $existStatus = 'no';

            if(is_user_logged_in()) {
              $existQuery = new WP_Query(array(
                'author' => get_current_user_id(),
                'post_type' => 'like',
                'meta_query' => array(
                  array(
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => get_the_ID()
                  )
                )
              ));
  
              if($existQuery -> found_posts > 0) {
                $existStatus = 'yes';
              }
            }
            
            ?>
            <span class="like-box" 
                  data-exists="<?php echo $existStatus; ?>" 
                  data-professorid="<?php the_ID(); ?>"
                  data-like="<?php echo $existQuery -> posts[0] -> ID; ?>">
              <i class="fa fa-heart-o" aria-hidden='true'></i>
              <i class="fa fa-heart" aria-hidden='true'></i>
              <span class="like-count"><?php echo $likes -> found_posts; ?></span>
            </span>
        <?php the_content(); ?>
      </div>
    </div>  
  </div>
  <?php 
    $relatedPrograms = get_field('related_programs');
    if($relatedPrograms) { ?>
      <h2>Professor`s programs:</h2>
      <ul class="link-list min-list">
        <?php 
          foreach($relatedPrograms as $program) { ?>
            <li><a href="<?php echo get_the_permalink($program) ?>"><?php echo get_the_title($program); ?></a></li>
          <?php } ?>
      </ul>
    <?php
    }
  ?>
<?php
  }

get_footer();

?>