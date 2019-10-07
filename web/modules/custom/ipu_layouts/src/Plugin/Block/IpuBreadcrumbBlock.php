<?php

namespace Drupal\ipu_layouts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Component\Render\FormattableMarkup;

/**
 * Provides an IPU Breadcrumb Block.
 *
 * @Block(
 *   id = "ipu_breadcrumb_block",
 *   admin_label = @Translation("IPU Breadcrumb block"),
 *   category = @Translation("IPU"),
 * )
 */
class IpuBreadcrumbBlock extends BlockBase {

  /**
   * Creates a 'IPU Breadcrumb' Block
   * @Block(
   * id = "block_ipu_breadcrumb",
   * admin_label = @Translation("IPU Breadcrumb block"),
   * )
   */
  public function build() {

    $block_manager = \Drupal::service('plugin.manager.block');
    $plugin_block = $block_manager->createInstance('system_breadcrumb_block', []);
    $render1 = $plugin_block->build();
    //$html = \Drupal::service('renderer')->renderRoot($render);

    $plugin_block = $block_manager->createInstance('addtoany_block', []);
    $render2 = $plugin_block->build();
    //$html .= \Drupal::service('renderer')->renderRoot($render);

    //$html = '<div class="peter">PETER'.$html.'</div>';
    //$markup = new FormattableMarkup($html, [] );
    //$build = array(
    //  '#markup' => $markup,
    //);
    $build = [
      'render1' => [
        'block1' => $render1,
        '#prefix' => '<div class="block--breadcrumbs">',
        '#suffix' => '</div>',
        ],
      'render2' => [
        'block2' => $render2,
          '#prefix' => '<div class="block--addtoanybuttons">',
        '#suffix' => '</div>',
      ],
    ];

    return $build;
  }

  public function getCacheContexts() {
    //if you depends on \Drupal::routeMatch()
    //you must set context of this block with 'route' context tag.
    //Every new route this block will rebuild
    return Cache::mergeContexts(parent::getCacheContexts(), array('route'));
  }
}

