<?php

namespace Drupal\ipu_map\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Component\Render\FormattableMarkup;

/**
 * Provides an IPU Map of Signatories Block.
 *
 * @Block(
 *   id = "ipu_map_signatory_block",
 *   admin_label = @Translation("IPU Signatory Map block"),
 *   category = @Translation("IPU Map"),
 * )
 */
class IpuMapSignatoryBlock extends BlockBase {

  /**
   * Creates a 'IPU Map' Block
   * @Block(
   * id = "block_ipu_signatory_map",
   * admin_label = @Translation("IPU Signatory Map block"),
   * )
   */
  public function build() {

    $settings = ['data' => ipu_map_get_data_from_view()];

    $markup = '<div id="ipu_map" class="container"><div class="row mapcontainer_un"><div class="map"></div></div></div>';
    $markup = new FormattableMarkup($markup, [] );
    $build = array(
      '#markup' => $markup
    );

    $build['#attached']['library'][] = 'ipu_map/ipu_signatory_map';
    $build['#attached']['drupalSettings']['ipu_map'] = $settings;
    return $build;
  }

  public function defaultConfiguration() {
    $default_config = \Drupal::config('ipu_map.settings');
    return [
      'ipu_map_vid' => $default_config->get('ipu_map.vocabulary_id'),
    ];
  }
}

