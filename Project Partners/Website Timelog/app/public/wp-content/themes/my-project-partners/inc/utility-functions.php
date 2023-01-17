<?php
/* UTILITY FUNCTIONS */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


// utility function to get every date from a comma separated list of date periods 
// each period is a string, and follows the format: d.m.Y-d.m.Y
// periods can be a single date without hyphens
// the function facilitates to process the format that Scoro datepicker returns
// returns an array of date objects
function get_each_date_from_periods($dates_str){
    $dates = array();
    $dates_str_arr = explode(", ", $dates_str);
    foreach($dates_str_arr as $period_str){
        $period_arr = explode("-", $period_str);
        $period = array();
        // $start_date = DateTime::createFromFormat('d.m.Y H:i:s', $period_arr[0] . ' 00:00:00');
        $start_date = DateTime::createFromFormat('d-M-Y H:i:s', $period_arr[0] . ' 00:00:00');
        // $end_date = DateTime::createFromFormat('d.m.Y H:i:s', $period_arr[0] . ' 23:59:59');
        $end_date = DateTime::createFromFormat('d-M-Y H:i:s', $period_arr[0] . ' 23:59:59');
        if (count($period_arr) > 1){
            // $end_date = DateTime::createFromFormat('d.m.Y H:i:s', $period_arr[1] . ' 23:59:59');
            $end_date = DateTime::createFromFormat('d-M-Y H:i:s', $period_arr[1] . ' 23:59:59');
        }
        $period = new DatePeriod(
                 $start_date,
                 new DateInterval('P1D'),
                 $end_date
            );
        $dates = array_merge($dates, iterator_to_array($period));
    }
    return $dates;
}

/* Get specific custom field value from Scoro object */
function scoro_get_custom_field($item, $custom_field){
    // foreach($item->custom_fields as $c_field) {
    //     if ($c_field->id === $custom_field) {
    //         return $c_field->value;
    //     }
    // }
}

function creator_get_custom_field($item, $custom_field) {
  foreach($item->Custom_Fields as $c_field) {
    $c_field_arr = explode("=", $c_field);
    if ($c_field_arr[0] === $custom_field) {
        return $c_field_arr[1];
    }
}
}

/* Get current template name for barba */
function pp_get_current_template() {
    global $template;
    $template_name = basename($template, '.php');
    return $template_name === 'single' ? get_post_type() : $template_name;
}

  // Check if a string ends with a specific substring - e.g. '.pdf'.
function pp_ends_with( $haystack, $needle ) {
  $length = strlen( $needle );
  if( !$length ) {
   return true;
  }
  return substr( $haystack, -$length ) === $needle;
}

// Takes the content of a blog posts and finds the first link that ends in '.pdf', then returns its href.
function pp_get_link_to_pdf($content) {
  $dom = new DomDocument();
  // Warnings & Errors are disabled as PHP's DomDocument doesn't support HTML5
  $dom->loadHTML($content, LIBXML_NOWARNING | LIBXML_NOERROR);
  foreach ($dom->getElementsByTagName('a') as $item) {
    if (pp_ends_with($item->getAttribute('href'), '.pdf')) {
      return $item->getAttribute('href');
    }
  }
  return '';
}