<?php
// Shortcode for showing events page.
add_shortcode('wpeb_events_page', 'fnc_wpeb_events_page_callback');
function fnc_wpeb_events_page_callback() {
	$sc_category_slug = $_SESSION['category_slug'] = '';
	$sc_event_region = $_SESSION['event_region'] = '';
	$sc_event_city = $_SESSION['event_city'] = '';
	$sc_order_by = $_SESSION['order_by'] = '';
	$sc_order = $_SESSION['order'] = '';
	/*
		$sc_category_slug = $_SESSION['category_slug'] = $atts['category_slug'];
		$sc_event_region = $_SESSION['event_region'] = $atts['event_region'];
		$sc_event_city = $_SESSION['event_city'] = $atts['event_city'];
		$sc_order_by = $_SESSION['order_by'] = $atts['order_by'];
		$sc_order = $_SESSION['order'] = $atts['order'];
	*/
	$event_region = get_option('show_event_region');
	$event_template = (get_option('event_template')) ? get_option('event_template') : '';
	if ($event_template == 'three') {
		$meta_query[] = array(
			'key' => '_eventStartDate',
			'value' => date('Y-m-d H:i:s', strtotime("now")),
			'compare' => '>',
			'type' => 'DATETIME',
		);
		$args = array(
			'post_type' => array('cpt_events'),
			'meta_query' => $meta_query,
			'meta_key' => '_eventStartDate',
			'orderby' => array('meta_value' => 'ASC'),
			'posts_per_page' => '-1',
		);

		// The Query
		$the_query = new WP_Query($args);
		return fnc_build_events_table_for_template4($the_query);
	} else {
		if ($event_region == 'true') {
			// WP_Query arguments
			$args = array(
				'post_type' => array('location_region'),
				'posts_per_page' => '-1',
				'order' => 'ASC',
				'orderby' => 'title',
			);
			// The Query
			$query = new WP_Query($args);
			// The Loop
			if ($query->have_posts()) {
				echo '<div id="wpeb-events-page">';
				echo '<div class="accordion">';
				while ($query->have_posts()) {
					$query->the_post();
					if (get_events_count_from_region(get_the_ID(), $sc_category_slug, $sc_event_region, $sc_event_city, $sc_order_by, $sc_order)) {
						?>
				<h3 class="region_head" alt="<?php the_ID();?>" data-template="<?php echo $event_template; ?>"><?php the_title();?></h3>
				<div class="region_events region_events_<?php the_ID();?>" ></div>
				<?php }
				}
				echo '</div></div>';
			}
		} else {
			$meta_query[] = array(
				'key' => '_eventStartDate',
				'value' => date('Y-m-d H:i:s', strtotime("now")),
				'compare' => '>',
				'type' => 'DATETIME',
			);
			$args = array(
				'post_type' => array('cpt_events'),
				'meta_query' => $meta_query,
				'meta_key' => '_eventStartDate',
				'orderby' => array('meta_value' => 'ASC'),
				'posts_per_page' => '-1',
			);

			// The Query
			$the_query = new WP_Query($args);
			return fnc_build_events_table($the_query);
		}
	}
}

// Shortcode for showing events.
/*
[event_category slug="shows" event_region=”Northamerica” event_city=”New_York” Orderby=”date” Order=”desc”]
 */
add_shortcode('wpeb_events', 'fnc_wpeb_events_callback');
function fnc_wpeb_events_callback($atts) {
	$output = '';
	$atts = shortcode_atts(array(
		'category_slug' => '',
		'event_region' => '',
		'event_city' => '',
		'order_by' => 'event_date',
		'order' => 'asc',
		'layout' => '',
		'col' => '3',
		'row' => '',
	), $atts, 'wpeb_events');
	$event_template = (get_option('event_template')) ? get_option('event_template') : '';
	$sc_category_slug = $_SESSION['category_slug'] = $atts['category_slug'];
	$sc_event_region = $_SESSION['event_region'] = $atts['event_region'];
	$sc_event_city = $_SESSION['event_city'] = $atts['event_city'];
	$sc_order_by = $_SESSION['order_by'] = $atts['order_by'];
	$sc_order = $_SESSION['order'] = $atts['order'];
	$layout = $atts['layout'];
	$col = $atts['col'];
	$row = $atts['row'];
	$event_region = get_option('show_event_region');
	if ($layout == 'grid') {
		$meta_query[] = array(
			'key' => '_eventStartDate',
			'value' => date('Y-m-d H:i:s', strtotime("now")),
			'compare' => '>',
			'type' => 'DATETIME',
		);
		if (!empty($sc_event_city)) {
			$tmp_loc = get_location_id_from_name($sc_event_city);
			if ($tmp_loc) {
				$meta_query[] = array(
					'key' => '_event_location',
					'value' => $tmp_loc,
					'compare' => '=',
				);
			}
		}
		if (empty($row)) {
			$posts_per_page = '-1';
		} else {
			$posts_per_page = $col * $row;
		}
		$args = array(
			'posts_per_page' => $posts_per_page,
			'post_type' => array('cpt_events'),
			'meta_query' => $meta_query,
			//'meta_key' => '_eventStartDate',
			//'orderby' => array('meta_value' => 'ASC'),
		);

		if ($sc_order_by == 'event_date') {

			$args['meta_key'] = '_eventStartDate';
			$args['orderby'] = array('meta_value' => strtoupper($sc_order));
		} else {
			$args['orderby'] = $sc_order_by;
			$args['order'] = strtoupper($sc_order);
		}

		if (!empty($sc_category_slug)) {
			$args['category_name'] = $sc_category_slug;
		}

		// The Query
		$the_query = new WP_Query($args);
		return fnc_build_events_table_for_template4($the_query, '', $col);
	} else {
		if ($event_region == 'true') {
			// WP_Query arguments
			$args = array(
				'post_type' => array('location_region'),
				'posts_per_page' => '-1',
				'order' => 'ASC',
				'orderby' => 'title',
			);
			if (!empty($sc_event_region)) {
				$tmp_reg = get_region_id_from_name($sc_event_region);
				if ($tmp_reg) {
					$args['post__in'] = array($tmp_reg);
				}
			}
			// The Query
			$query = new WP_Query($args);
			// The Loop
			if ($query->have_posts()) {
				$output .= '<div id="wpeb-events-page" class="wpeb-events-shortcode">';
				$output .= '<div class="accordion">';
				while ($query->have_posts()) {
					$query->the_post();

					$args = array(
						'post_type' => array('event_location'),
						'posts_per_page' => '-1',
						'meta_query' => array(
							array(
								'key' => '_event_region',
								'value' => get_the_ID(),
								'compare' => '=',
								'type' => 'NUMERIC',
							),
						),
					);
					$pre_query = get_posts($args);
					$locations = wp_list_pluck($pre_query, 'ID');
					if (!empty($locations)) {
						$meta_query = array(
							array(
								'key' => '_event_location',
								'value' => $locations,
								'compare' => 'IN',
								'type' => 'NUMERIC',
							), array(
								'key' => '_eventStartDate',
								'value' => date('Y-m-d H:i:s', strtotime("now")),
								'compare' => '>',
								'type' => 'DATETIME',
							),
						);
						$exists_args = array(
							'post_type' => array('cpt_events'),
							'meta_query' => $meta_query,
						);
						if (!empty($sc_category_slug)) {
							$exists_args['category_name'] = $sc_category_slug;
						}
						$exists_query = get_posts($exists_args);
						if ($exists_query) {
							$output .= '<h3 class="region_head" alt="' . get_the_ID() . '" data-template="' . $event_template . '">' . get_the_title() . '</h3>
							<div class="region_events region_events_' . get_the_ID() . '" ></div>';
						}
					}
				}
				$output .= '</div>
			<input type="hidden" name="sc_category_slug" class="sc_category_slug" value="' . $sc_category_slug . '"/>
			<input type="hidden" name="sc_event_region" class="sc_event_region" value="' . $sc_event_region . '"/>
			<input type="hidden" name="sc_event_city" class="sc_event_city" value="' . $sc_event_city . '"/>
			<input type="hidden" name="sc_order_by" class="sc_order_by" value="' . $sc_order_by . '"/>
			<input type="hidden" name="sc_order" class="sc_order" value="' . $sc_order . '"/>
			</div>';
				return $output;
			}
		} else {
			$meta_query[] = array(
				'key' => '_eventStartDate',
				'value' => date('Y-m-d H:i:s', strtotime("now")),
				'compare' => '>',
				'type' => 'DATETIME',
			);
			if (!empty($sc_event_city)) {
				$tmp_loc = get_location_id_from_name($sc_event_city);
				if ($tmp_loc) {
					$meta_query[] = array(
						'key' => '_event_location',
						'value' => $tmp_loc,
						'compare' => '=',
					);
				}
			}
			$args = array(
				'posts_per_page' => '-1',
				'post_type' => array('cpt_events'),
				'meta_query' => $meta_query,
				//'meta_key' => '_eventStartDate',
				//'orderby' => array('meta_value' => 'ASC'),
			);

			if ($sc_order_by == 'event_date') {

				$args['meta_key'] = '_eventStartDate';
				$args['orderby'] = array('meta_value' => strtoupper($sc_order));
			} else {
				$args['orderby'] = $sc_order_by;
				$args['order'] = strtoupper($sc_order);
			}

			if (!empty($sc_category_slug)) {
				$args['category_name'] = $sc_category_slug;
			}

			// The Query
			$the_query = new WP_Query($args);
			return fnc_build_events_table($the_query);
		}
	}

}