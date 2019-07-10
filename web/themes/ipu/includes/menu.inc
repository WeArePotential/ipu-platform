<?php
use Drupal\Component\Render\FormattableMarkup;

/**
 * @file
 * Theme and preprocess functions for menus.
 */
/**
 * Implements hook_preprocess_menu().
 */
function ipu_preprocess_menu(&$variables, $hook) {
  // Add a span to the link for menus so we can style with just an icon.
  if ($hook == 'menu') {
    $items = $variables['items'];
    foreach ($items as $key => $item) {
      // If path is current_path, set active to li.
      $variables['items'][$key]['title'] = new FormattableMarkup('<span>@title</span>', [
        '@title' => $variables['items'][$key]['title'],
      ]);
    }
  }
}
