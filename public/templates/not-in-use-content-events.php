<section id="primary" class="content-area">
  <main id="main" class="site-main" role="main">

  <?php if (have_posts()): ?>

    <header class="page-header">
      <h1 class=""><?php //<h1 class="page-title">
echo ($region) ? 'Region : ' . get_the_title($region) : 'Events';
?></h1>
    </header><!-- .page-header -->

    <?php
// Start the Loop.
while (have_posts()): the_post();

	?>
			      <article id="post-<?php the_ID();?>" <?php post_class();?>>
			      	<header class="event-header">
			      		<?php
	the_title(sprintf('<h2 class="event-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');
	?>
			      	</header><!-- .event-header -->

			      	<div class="event-content">
			          <?php $_event_id = get_the_ID();
	wpeb_event_details($_event_id);
	?>
			          <?php
	/* translators: %s: Name of current post */
	the_content(sprintf(
		__('Continue reading %s', 'wp_event_booking'),
		the_title('<span class="screen-reader-text">', '</span>', false)
	));
	?>
			      	</div><!-- .event-content -->

			      </article><!-- #post-## -->
			      <?php

	// End the loop.
endwhile;

// Previous/next page navigation.
the_posts_pagination(array(
	'mid_size' => 2,
	'prev_text' => __('Previous page', 'wp_event_booking'),
	'next_text' => __('Next page', 'wp_event_booking'),
	'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'wp_event_booking') . ' </span>',
));

// If no content, include the "No posts found" template.
else:
	echo 'No events found.';

endif;
?>

  </main><!-- .site-main -->
</section><!-- .content-area -->
