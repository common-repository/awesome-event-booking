<?php
/**
 *Events checkout template.
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

// End the loop.
endwhile;

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
