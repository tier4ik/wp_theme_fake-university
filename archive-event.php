<?php 

get_header();
pageBanner(array(
  'title' => 'All events',
  'subtitle' => 'Events! Events everywhere!'
))
?>
<div class="container container--narrow page-section">

  <?php 
    while(have_posts()) {
      the_post();

      get_template_part('template_parts/event');

    }
    echo paginate_links();
  ?>

    <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events'); ?>">Look at our archive</a></p>

</div>

<?php

get_footer();

?>