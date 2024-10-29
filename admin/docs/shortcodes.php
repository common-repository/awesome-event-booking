<h3><?php _e('Shortcodes', 'wp_event_booking');?></h3>
<div>
  <p><code>[wpeb_events_page]</code> <?php _e('For listing all events in WordPress page. It would not allow any arguments.', 'wp_event_booking');?></p>
  <hr />
  <p><code>[wpeb_events]</code> <?php _e('For listing event in WordPress pages with cusomize option', 'wp_event_booking');?></p>
  <p><?php _e('Possible arguments', 'wp_event_booking');?></p>
  <p><code>category_slug</code> - <?php _e('Slug of event category', 'wp_event_booking');?></p>
  <p><code>event_region</code> - <?php _e('Name of event region', 'wp_event_booking');?></p>
  <p><code>event_city</code> - <?php _e('Name of event city', 'wp_event_booking');?></p>
  <p><code>order_by</code> (<?php _e('Possible arguments', 'wp_event_booking');?> - <code>event_date/date/title/</code>)</p>
  <p><code>order</code> (<?php _e('Possible arguments', 'wp_event_booking');?> - <code>asc/desc</code>)</p>
  <p><code>layout</code> (<?php _e('Possible arguments', 'wp_event_booking');?> - <code>grid/list</code>). <?php _e('Default is list. If you left this blank, it will show list view.', 'wp_event_booking');?></p>
  <p><code>row</code> (<?php _e('Possible arguments', 'wp_event_booking');?> - <code>1 or 2 or 3 ... </code>). <?php _e('Number of rows needed for grid layout.', 'wp_event_booking');?></p>
  <p><code>col</code> (<?php _e('Possible arguments', 'wp_event_booking');?> - <code>1 to 12</code>). <?php _e('Number of columns that needed for grid layout', 'wp_event_booking');?></p>
  <p><?php _e('Example', 'wp_event_booking');?> <code>[wpeb_events category_slug="your-category-slug-goes-here" order_by="date" order="asc" layout="grid" row="3" col="4"]</code></p>
  <hr />
  <?php do_action('wpeb_after_shortcode_doc');?>
</div>
