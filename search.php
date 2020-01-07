<?php 

get_header();
pageBanner(array(
  'title' => 'Search results',
  'subtitle' => 'You searched for &ldquo;' . esc_html(get_search_query()) . '&rdquo;'
));
?>

<div class="container container--narrow page-section">

  <?php 
    if(!have_posts()) {
      echo '<h2 class="headline headline--small-plus">No results</h2>';
    }
    while(have_posts()) {
      the_post();
      get_template_part('./template_parts/content', get_post_type());
    }

    echo paginate_links();
  
  get_search_form();
  ?>
</div>

<?php

get_footer();

?>