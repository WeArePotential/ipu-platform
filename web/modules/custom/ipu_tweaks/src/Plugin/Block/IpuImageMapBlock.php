<?php

namespace Drupal\ipu_tweaks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;



/**
 * Provides a 'Responsive ImageMap' block.
 *
 * @Block(
 *   id = "ipu_imagemap_block",
 *   admin_label = @Translation("IPU ImageMap block"),
 *   category = @Translation("Content")
 * )
 */
class IpuImageMapBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    /*
     * 4 boxes:
     *    Members (from count of countries)
     *    Av. women in parliament
     *    MPs under 45
     *    Human rights cases
     *
     */

    $form = parent::blockForm($form, $form_state);

    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();

    // TO DO: Get from count of countries vocab terms
    $form['imagemap'] = [
      '#title' => t('ImageMap html'),
      '#description' => t('Past HTML for image map, both image and map'),
      '#type' => 'textarea',
      '#default_value' => $config['imagemap'],
      '#size' => 60,
      '#rows' => 8,
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('imagemap', $form_state->getValue('imagemap'));
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    $imagemap = $config['imagemap'];

    $build = [
      '#markup' => check_markup($imagemap, 'full_html'),
      '#attached' =>[
        'library' => ['ipu_tweaks/responsive-imagemaps'],
      ]
    ];
    return $build;
  }


  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account, $return_as_object = FALSE) {
    if (empty($this->node)) {
      return AccessResult::allowed();
    }
    return $this->node->access('view', NULL, TRUE);
  }

}
