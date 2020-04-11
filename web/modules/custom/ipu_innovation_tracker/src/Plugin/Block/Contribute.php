<?php
/**
 * @file Contribute class file.
 */

namespace Drupal\ipu_innovation_tracker\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a block to display information on how to contribute to the
 * innovation tracker issues.
 *
 * @Block(
 *   id = "ipu_innovation_tracker_contribute",
 *   admin_label = @Translation("Innovation Tracker Contributions"),
 * )
 */
class Contribute extends BlockBase {

  /**
   * @inheritDoc
   */
  public function build() {
    $config = \Drupal::service('config.factory')->get('ipu_innovation_tracker.contribute');

    return [
      '#theme' => 'ipu_contribute_block',
      '#content' => [
        '#markup' => check_markup($this->t($config->get('content.value')), $config->get('content.format')),
      ],
    ];
  }

}
