<?php

namespace Drupal\ipu_map\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an IPU Map Block.
 *
 * @Block(
 *   id = "ipu_map_block",
 *   admin_label = @Translation("IPU Map block"),
 *   category = @Translation("IPU Map"),
 * )
 */
class IpuMapBlock extends BlockBase {

  /**
   * Creates a 'IPU Map' Block
   * @Block(
   * id = "block_ipu_map",
   * admin_label = @Translation("IPU Map block"),
   * )
   */
  public function build() {

    $settings = ['foo' => 'bar'];
    $markup = '<div id="ipu_map" class="container">'.$this->t('IPU Map demo').'<div class="row mapcontainer_un"><div class="col-12 map">MAP</div></div></div>';
    $build = array(
      '#markup' => $markup
    );

    $build['#attached']['library'][] = 'ipu_map/ipu_map';
    $build['#attached']['drupalSettings']['ipu_map']['ipu_map'] = $settings;
    return $build;
  }

  public function defaultConfiguration() {
    $default_config = \Drupal::config('ipu_map.settings');
    return [
      'ipu_map_vid' => $default_config->get('ipu_map.vocabulary_id'),
    ];
  }
}

