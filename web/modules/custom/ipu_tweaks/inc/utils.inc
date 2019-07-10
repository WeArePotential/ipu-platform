<?php

use \Drupal\datetime;

class IpuEventUtils {

  public static function get_event_session_datetime_text($from, $to, $delimiter = ' - ', $time_only = False) {
    global $language;
    $datetime_text = "";
    $dt_format = ( $time_only ? 'H:i': 'd F Y H:i');
    $start_time = \Drupal::service('date.formatter')->format(
      strtotime($from), 'custom', $dt_format
    );

    $end_time = \Drupal::service('date.formatter')->format(
      strtotime($to), 'custom', $dt_format
    );

    //$start_time = \Drupal::datetime($from);
    $datetime_text = $start_time.$delimiter.$end_time;
    return $datetime_text;

  }

  public static function get_event_date_range_old($from, $to, $granularity) {
    global $language;
    $event_date_range = "";
    $delimiter = ' ';
    $fromDay = $from ["day"] ? $from ["day"] : FALSE;
    $fromMonth = $from["month"] ? format_date(mktime(0, 0, 0, $from ["month"], 10), 'custom', "M", NULL, $language->language) : FALSE;
    $fromYear = $from ["year"] ? $from ["year"] : FALSE;

    $toDay = $to ["day"] ? $to ["day"] : FALSE;
    $toMonth = $to ["month"] ? format_date(mktime(0, 0, 0, $to ["month"], 10), 'custom', "M", NULL, $language->language) : FALSE;
    $toYear = $to ["year"] ? $to ["year"] : FALSE;

    if ($fromYear && $toYear) {
      $fromDayString = $fromDay ? $fromDay . $delimiter : '';
      $toDayString = $toDay ? $toDay . $delimiter : '';

      $fromMonthString = $fromMonth ? $fromMonth . $delimiter : '';
      $toMonthString = $toMonth ? $toMonth . $delimiter : '';

      $from_redundant = $fromDayString . $fromMonthString . $fromYear;
      $to_redundant = $toDayString . $toMonthString . $toYear;

      $from_sep_count = $to_sep_count = 0;

      if ($from_redundant) {
        $from_sep_count = substr_count($from_redundant, $delimiter);
        $from_parts = explode($delimiter, $from_redundant);
      }
      if ($to_redundant) {
        $to_sep_count = substr_count($to_redundant, $delimiter);
        $to_parts = explode($delimiter, $to_redundant);
      }

      if ($from_sep_count === $to_sep_count && $from_sep_count === 2) {//Full dates entered
        if ($from_parts[2] === $to_parts[2]) {//Same year
          if ($from_parts[1] === $to_parts[1]) {//Same month same year
            if ($from_parts[0] !== $to_parts[0]) {
              $from_redundant = $from_parts[0];
            }
          }
          else { //Diff month same year{
            $from_redundant = $from_parts[0] . $delimiter . $from_parts[1];
          }
        }
      }
      else {
        if ($from_sep_count == $to_sep_count && $from_sep_count === 1) {
          $from_redundant = $from_parts[1] == $to_parts[1] && $from_parts[0] != $to_parts[0] ? $from_parts[0] : $from_redundant;
        }
      }
      if ($from_redundant == $to_redundant) {
        $event_date_range = $from_redundant;
      }
      else {
        $event_date_range = $from_redundant . " - " . $to_redundant;
      }
      return $event_date_range;
    }
  }

  public static function get_location($locality, $country) {
    $countries = _addressfield_country_options_list();
    $country = $country ? $countries[$country] : '';
    $formatted_location = $locality . ($locality && $country ? ', ' : "") . $country;
    return $formatted_location;
  }
}
