<?php
/**
*Events archive template.
* Executes when user runs domainname.ext/events
*/
get_header();?>
<div class="wrap row row">
	<div id="container" class="events-container">
		<div id="content" role="main" class="events-content main-event-wrapper">
			<section id="primary" class="content-area event-single-page">
				<main id="main" class="site-main" role="main">

					<?php if (have_posts()): while (have_posts()): the_post();?>
						<article id="post-<?php the_ID();?>" <?php post_class();?>>
							<header class="event-header">
								<!-- <?php the_title(sprintf('<h2 class="event-title"><a rel="nofollow" href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');?> -->
								<?php the_title(sprintf('<h2 class="event-title">', esc_url(get_permalink())), '</h2>');?>
							</header><!-- .event-header -->
							<?php if (has_post_thumbnail()) { ?>
								<figure class="figure-image">
									<?php if (has_post_thumbnail()): the_post_thumbnail('full');endif;?>
								</figure>
								<?php } else { ?>
									<div class="content-wrap-aside"><?php /* translators: %s: Name of current post */the_content();?></div>
								<?php } ?>
							<div class="event-content">

								<div class="section group">

									<div class="event-block">
										<?php $_event_id = get_the_ID();
										echo wpeb_event_details($_event_id, 'details');?>
										<?php echo wpeb_event_location($_event_id); ?>
										<?php echo wpeb_event_cost($_event_id); ?>
										<?php echo wpeb_event_seats($_event_id, 'single'); ?>
									</div>

									<div class="event-block">
										<?php echo wpeb_event_manager($_event_id); ?>
									</div>

								</div>



							</div><!-- .event-content -->

							<?php if (has_post_thumbnail()) { ?>
							<div class="content-wrap"><?php /* translators: %s: Name of current post */the_content();?></div>
							<?php } ?>
							
							<div class="section group">
								<div class="col span_1_of_1">
									<?php if (get_option('wpeb_checkout_page')) {
										if (fnc_get_remaining_seat_status(get_the_ID())) {
											?>
											<?php /* <form id="go_to_checkout" method="get" action="<?php echo get_permalink(get_option('wpeb_checkout_page')); ?>">
											<button type="submit" form="go_to_checkout" class="sign-up"><?php _e('Sign Up', 'wp_event_booking');?></button>
											<input type="hidden" name="event_id" value="<?php echo the_ID(); ?>" />
											<input type="hidden" name="step" value="2" />
											</form> */?>
											<a rel="nofollow" href="<?php echo add_query_arg(array('event_id' => get_the_ID()), get_permalink(get_option('wpeb_checkout_page'))); ?>" class="single-event sign-up"><?php _e('Sign Up', 'wp_event_booking'); ?></a>
											<?php
										} else {
											?>
											<a rel="nofollow" href="javascript:void(0);" class="no-more-sign-up sign-up"><?php _e('No seats left', 'wp_event_booking'); ?></a>
											<?php
										}
									} else {
										_e('Check Out page is missing.', 'wp_event_booking');
									}
									?>
								</div>
							</div>

						</article><!-- #post-## -->
						<?php $buttonColor = esc_attr(get_option('submitButtonColor'));
						$buttonTextColor = esc_attr(get_option('submitButtonTextColor'));?>
						<style type="text/css">
							.single-event.sign-up{
								background: <?php echo $buttonColor; ?>;
								color: <?php echo $buttonTextColor; ?>;
								border: 1px solid <?php echo $buttonColor; ?>;
							}

						</style>
					<?php
					endwhile;
					// Previous/next page navigation.
					the_posts_pagination(array(
						'mid_size' => 2,
						'prev_text' => __('Previous page', 'wp_event_booking'),
						'next_text' => __('Next page', 'wp_event_booking'),
						'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'wp_event_booking') . ' </span>',
					));
					else:
						echo 'No events found.';
					endif;?>

				</main><!-- .site-main -->
			</section><!-- .content-area -->

		</div><!-- #content -->
	</div><!-- #container -->
</div>
<?php get_footer();?>
