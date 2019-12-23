<?php 

get_header();
pageBanner(array(
  'subtitle' => 'Keep up with our latest news !'
));
?>

<?php
  while(have_posts()) {
      the_post();
?>
  <div class="container container--narrow page-section">
  <div class="metabox metabox--position-up metabox--with-home-link">
    <p>
      <a class="metabox__blog-home-link" href="<?php echo site_url('/blog') ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to Blog</a>
      <span class="metabox__main"><?php the_author_posts_link(); ?></span>
    </p>
  </div>
  <div class="post-item">
    <p><?php the_content() ;?></p>
  </div>
<?php
  }

get_footer();

?>