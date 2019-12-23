<?php 

get_header();

?>

<?php
  while(have_posts()) {
      the_post();
      pageBanner();
?>
  
  <div class="container container--narrow page-section">
  
  <div class="post-item">
    <div class="row group">
      <div class="one-third"><?php the_post_thumbnail(); ?></div>
      <div class="two-thirds"><?php the_content(); ?></div>
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