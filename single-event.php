<?php 

get_header();

?>

<?php
  while(have_posts()) {
      the_post();
      pageBanner(array(
        'subtitle' => 'Take a look at our events.'
      ));
?>
  <div class="container container--narrow page-section">
  <div class="metabox metabox--position-up metabox--with-home-link">
    <p>
      <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event')?>"><i class="fa fa-home" aria-hidden="true"></i> Back to Events</a>
      <span class="metabox__main"><?php the_title(); ?></span>
    </p>
  </div>
  <div class="post-item">
    <p><?php the_content() ;?></p>
  </div>
  <h2>Related programs:</h2>
  <ul class="link-list min-list">
    <?php 
      $relatedProgram = get_field('related_program');
      foreach($relatedProgram as $program) { ?>
        <li><a href="<?php echo get_the_permalink($program) ?>"><?php echo get_the_title($program); ?></a></li>
      <?php } ?>
  </ul>
<?php
  }

get_footer();

?>