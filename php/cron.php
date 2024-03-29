<?php

/**
 * Summary: php file which implements the wp cron tasks
 */


// Setup WP cron job to run every hour
  if ( ! wp_next_scheduled( 'gmuw_pf_cron_hourly' ) ) {
      wp_schedule_event( time(), 'hourly', 'gmuw_pf_cron_hourly' );
  }

// Cron hook to execute hourly tasks
  add_action( 'gmuw_pf_cron_hourly', 'gmuw_pf_hourly_tasks' );

function gmuw_pf_hourly_tasks(){

}
