<?php
/**
 *Events archive template.
 * Executes when user runs domainname.ext/events
 */
get_header();?>
<div class="wrap">
	<div id="container" class="events-container">
		<div id="content" role="main" class="events-content">
			<section id="primary" class="content-area">
			  <main id="main" class="site-main" role="main">

			  <?php if (have_posts()): ?>

			    <?php
// Start the Loop.
while (have_posts()): the_post();

	?>
				      <article id="post-<?php the_ID();?>" <?php post_class();?>>
				      	<header class="event-header">
				      		<?php the_title(sprintf('<h2 class="event-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');?>
				      	</header><!-- .event-header -->
	<figure><?php if (has_post_thumbnail()):
		the_post_thumbnail(array(640, 400));
	endif;
	?>
	</figure>
				      	<div class="event-content">
									<?php /* translators: %s: Name of current post */the_content();?>
									<div class="section group">
										<div class="col span_1_of_3">
										<?php $_event_id = get_the_ID();
	wpeb_event_details($_event_id, 'details');?>
										</div>
										<div class="col span_1_of_3">
											<?php wpeb_event_location($_event_id);?>
											<?php wpeb_event_cost($_event_id);?>
											<?php wpeb_event_seats($_event_id);?>
										</div>
										<div class="col span_1_of_3">
										<?php wpeb_event_manager($_event_id);?>
										</div>
									</div>
									<div class="section group">
										<div class="col span_1_of_3">
											<?php if (get_option('wpeb_checkout_page')) {?>
											<form id="go_to_checkout" method="get" action="<?php echo get_permalink(get_option('wpeb_checkout_page')); ?>">
												<button type="submit" form="go_to_checkout">Go to Check Out</button>
												<input type="hidden" name="event_id" value="<?php echo the_ID(); ?>" />
												<input type="hidden" name="step" value="2" />
											</form>
										<?php } else {echo 'Check Out page is missing.';}?>
										</div>
									</div>
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

	</div><!-- #content -->
</div><!-- #container -->
</div>
<?php get_footer();?>
