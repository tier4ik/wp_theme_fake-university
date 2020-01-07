<?php get_header();

while(have_posts()) {
  the_post();
  pageBanner();
?>
  <div class="container container--narrow page-section">

  <!-- check if the current page has a parent page -->

    <?php 
      // get_the_ID() - get ID of the current page
      // wp_get_post_parent_id() - get curent page`s parent page ID 
      $parent = wp_get_post_parent_id( get_the_ID() );
      if($parent) {
      ?>
        <div class="metabox metabox--position-up metabox--with-home-link">
          <p><a class="metabox__blog-home-link" href="<?php echo get_permalink( $parent );?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title( $parent );?></a> <span class="metabox__main"><?php the_title();?></span></p>
        </div>
    
    <?php
      }
    ?>

    <?php 
      if(has_children()) { ?>

      <div class="page-links">
        <h2 class="page-links__title">
          <a href="<?php echo get_permalink( $parent );?>"><?php echo get_the_title( $parent );?></a>
        </h2>
        <ul class="min-list">
          <?php wp_list_pages( array('child_of' => get_the_ID(), 'title_li' => NULL));?>
        </ul>
      </div>

    <?php } ?>

    <div class="generic-content">
      <?php get_search_form(); ?>
    </div>

  </div>

</div>
<?php
}
get_footer();?>



