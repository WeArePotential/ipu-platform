<?php
/**
 * @file
 * Contains \Drupal\ipu_map\Plugin\Block\IpuCountrySelectBlock.
 */
namespace Drupal\ipu_map\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;
/**
 * Provides a 'country select' block.
 *
 * @Block(
 *   id = "ipu_country_select_block",
 *   admin_label = @Translation("Parliament select block"),
 *   category = @Translation("IPU Map")
 * )
 */
class IpuCountrySelectBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\ipu_map\Form\IpuCountrySelectForm');
    $renderArray['form'] = $form;
    return $renderArray;
  }
}