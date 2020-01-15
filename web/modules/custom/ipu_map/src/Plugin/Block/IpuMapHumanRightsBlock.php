<?php

namespace Drupal\ipu_map\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Component\Render\FormattableMarkup;

/**
 * Provides an IPU Map of Human Rights Cases Block.
 *
 * @Block(
 *   id = "ipu_map_human_rights_block",
 *   admin_label = @Translation("IPU Human Rights Map block"),
 *   category = @Translation("IPU Map"),
 * )
 */
class IpuMapHumanRightsBlock extends BlockBase {

  /**
   * Creates a 'IPU Map' Block
   * @Block(
   * id = "block_ipu_human_rights_map",
   * admin_label = @Translation("IPU Human Rights Map block"),
   * )
   */
  public function build() {

    $settings = ['data' => ipu_map_get_data_from_view()];

    $markup = '<div id="ipu_map" class="container"><div class="row mapcontainer_un"><div class="map"></div><div class="areaLegend">Legend</div></div></div>';
    $markup = new FormattableMarkup($markup, [] );
    $build = array(
      '#markup' => $markup
    );

    $build['#attached']['library'][] = 'ipu_map/ipu_human_rights_map';
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

