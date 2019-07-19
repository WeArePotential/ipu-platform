<?php
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Url;
/**
 * @file
 * Theme and preprocess functions for menus.
 */
/**
 * Implements hook_preprocess_menu().
 *
 * Add a span round link text so it can be styled e.g. before/after
 * Add a system path to items, so we can create classes in Twig (e.g. link-node-96
 *
 */
function ipu_preprocess_menu(&$variables, $hook) {
  // Add a span to the link for menus so we can style with just an icon.
  if ($hook == 'menu') {
    $items = $variables['items'];
    foreach ($items as $key => $item) {
      //print ('Q'.$item['url']->toString);
      $url = $item['url'];
      $system_path = ($url->isRouted() ? $url->getInternalPath() : NULL);
      $variables['items'][$key]['system_path'] = $system_path == '' ? '<front>' : $system_path;
      // If path is current_path, set active to li.
      $variables['items'][$key]['title'] = new FormattableMarkup('<span>@title</span>', [
        '@title' => $variables['items'][$key]['title'],
      ]);
    }
  }
}
