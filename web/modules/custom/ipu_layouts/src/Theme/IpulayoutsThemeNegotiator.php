<?php
namespace Drupal\ipu_layouts\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

/**
 * Switch theme for report content types.
 *  Based on https://speedandfunction.com/switching-themes-programmatically-drupal-8/
 */

/**
 * Class IpulayoutsThemeNegotiator.
 *
 * @package Drupal\ipu_layouts
 */
class IpulayoutsThemeNegotiator implements ThemeNegotiatorInterface {

  /**
   * Constructor.
   */
  public function __construct() {

  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    $node = $route_match->getParameter('node');
    // Only if we're viewing a node
    if ($route_match->getRouteName() == 'entity.node.canonical') {
      // And the node is "report" or "report section"
      if (!empty($node) && $node->bundle() == 'report' || !empty($node) && $node->bundle() == 'report_section') {
        return TRUE;
      }
    }
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    return 'ipu_report';
  }

}
