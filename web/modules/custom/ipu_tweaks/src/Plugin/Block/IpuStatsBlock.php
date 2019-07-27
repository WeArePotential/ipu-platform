<?php

namespace Drupal\ipu_tweaks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;



/**
 * Provides a 'NodeBlock' block.
 *
 * @Block(
 *   id = "ipu_stats_block",
 *   admin_label = @Translation("IPU statistics block"),
 *   category = @Translation("Content")
 * )
 */
class IpuStatsBlock extends BlockBase implements ContainerFactoryPluginInterface {

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

    /*$form['current'] = [
      '#title' => t('Use current node?'),
      '#description' => 'Will display the currently displayed node in the
      specified view mode. Useful for showing certain fields from the current
      node in a block. Uncheck to pick a specific node.',
      '#type' => 'checkbox',
      '#default_value' => (isset($config['current']) ? $config['current'] : TRUE),
    ];*/

    // TO DO: Get from count of countries vocab terms
    $form['members'] = [
      '#title' => t('Members'),
      '#type' => 'fieldset',
    ];
    $form['members']['count'] = [
      '#title' => t('IPU member parliaments'),
      '#type' => 'textfield',
      '#default_value' => (isset($config['members']['count']) ? $config['members']['count'] : '0'),
      '#size' => 3,
      '#maxlen' => 3,
      '#required' => TRUE,
    ];


    $form['women'] = [
      '#title' => t('Women in parliament'),
      '#type' => 'fieldset',
    ];
    $form['women']['title'] = [
      '#title' => t('Title'),
      '#type' => 'textfield',
      '#default_value' => t((isset($config['women']['title']) ? $config['women']['title'] : 'Women in parliament')),
      '#size' => 20,
      '#maxlen' => 20,
      '#required' => TRUE,
    ];
    $form['women']['percent'] = [
      '#title' => t('Average number of women in parliament (%)'),
      '#description' => t('Enter a numeric value without the % symbol'),
      '#type' => 'textfield',
      '#default_value' => (isset($config['women']['percent']) ? $config['women']['percent']: '0.00'),
      '#size' => 4,
      '#maxlen' => 5,
      '#required' => TRUE,
    ];
    $form['women']['description'] = [
      '#title' => t('Description'),
      '#type' => 'textfield',
      '#default_value' => t((isset($config['women']['description']) ? $config['women']['description'] : 'global share of women MPs')),
      '#size' => 50,
      '#maxlen' => 128,
      '#required' => TRUE,
    ];
    $form['women']['link_text'] = [
      '#title' => t('Link text'),
      '#type' => 'textfield',
      '#default_value' => t(isset($config['women']['link_text']) ? $config['women']['link_text'] : 'IPU on Gender Equality'),
      '#size' => 30,
      '#maxlen' => 20,
      '#required' => TRUE,
    ];
    $form['women']['nid'] = [
      '#title' => t('Link'),
      '#description' => t('The content to link to'),
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => array(
        'target_bundles' => array('page', 'basic_page', 'landing_page', 'section_page'),
      ),
      '#default_value' => ((isset($config['women']['nid']) && !empty($config['women']['nid'])) ? Node::load($config['women']['nid']) : NULL),
    ];

    $form['under45'] = [
      '#title' => t('Youth participation'),
      '#type' => 'fieldset',
    ];
    $form['under45']['title'] = [
      '#title' => t('Title'),
      '#type' => 'textfield',
      '#default_value' => t(isset($config['under45']['title']) ? $config['under45']['title'] : 'Youth participation'),
      '#size' => 20,
      '#maxlen' => 20,
      '#required' => TRUE,
    ];
    $form['under45']['percent'] = [
      '#title' => t('Global % members under 45'),
      '#type' => 'textfield',
      '#description' => t('Enter a numeric value without the % symbol'),
      '#default_value' => (isset($config['under45']['percent']) ? $config['under45']['percent'] : '0.00'),
      '#size' => 3,
      '#maxlen' => 3,
      '#required' => TRUE,
    ];
    $form['under45']['description'] = [
      '#title' => t('Description'),
      '#type' => 'textfield',
      '#default_value' => t(isset($config['under45']['description']) ? $config['under45']['description'] : 'of the world\'s MPs are under 45'),
      '#size' => 50,
      '#maxlen' => 128,
      '#required' => TRUE,
    ];
    $form['under45']['link_text'] = [
      '#title' => t('Link text'),
      '#type' => 'textfield',
      '#default_value' => t(isset($config['under45']['link_text']) ? $config['under45']['link_text'] : 'IPU on youth empowerment'),
      '#size' => 30,
      '#maxlen' => 20,
      '#required' => TRUE,
    ];
    $form['under45']['nid'] = [
      '#title' => t('Link'),
      '#description' => t('The content to link to'),
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => array(
        'target_bundles' => array('page', 'basic_page', 'landing_page', 'section_page'),
      ),
      '#default_value' => ((isset($config['under45']['nid']) && !empty($config['under45']['nid'])) ? Node::load($config['under45']['nid']) : NULL),
    ];

    // TO DO: Get from count of human righta field in country vocab
    $form['humanrights'] = [
      '#title' => t('MPs\'s human rights'),
      '#type' => 'fieldset',
    ];
    $form['humanrights']['title'] = [
      '#title' => t('Title'),
      '#type' => 'textfield',
      '#default_value' => t(isset($config['humanrights']['title']) ? $config['humanrights']['title'] : 'MPs\'s human rights'),
      '#size' => 20,
      '#maxlen' => 20,
      '#required' => TRUE,
    ];
    $form['humanrights']['count'] = [
      '#title' => t('Number of human rights cases'),
      '#type' => 'textfield',
      '#default_value' => (isset($config['humanrights']['count']) ? $config['humanrights']['count'] : '0'),
      '#size' => 3,
      '#maxlen' => 3,
      '#required' => TRUE,
    ];
    $form['humanrights']['description'] = [
      '#title' => t('Description'),
      '#type' => 'textfield',
      '#default_value' => t(isset($config['humanrights']['description']) ? $config['humanrights']['description'] : 'IPU-supported human rights cases'),
      '#size' => 50,
      '#maxlen' => 128,
      '#required' => TRUE,
    ];
    $form['humanrights']['link_text'] = [
      '#title' => t('Link text'),
      '#type' => 'textfield',
      '#default_value' => t(isset($config['humanrights']['link_text']) ? $config['humanrights']['link_text'] : 'Report an MP in danger'),
      '#size' => 30,
      '#maxlen' => 20,
      '#required' => TRUE,
    ];
    $form['humanrights']['nid'] = [
      '#title' => t('Link'),
      '#description' => t('The content to link to'),
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => array(
        'target_bundles' => array('page', 'basic_page', 'landing_page', 'section_page'),
      ),
      '#default_value' => ((isset($config['humanrights']['nid']) && !empty($config['humanrights']['nid'])) ? Node::load($config['humanrights']['nid']) : NULL),
    ];


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('women', $form_state->getValue('women'));
    $this->setConfigurationValue('under45', $form_state->getValue('under45'));
    $this->setConfigurationValue('humanrights', $form_state->getValue('humanrights'));
    $this->setConfigurationValue('members', $form_state->getValue('members'));
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $stats= [];
    $config = $this->getConfiguration();

    $stats['members'] = $config['members'];
    $ipu_map_config = \Drupal::config('ipu_map.settings');
    $stats['members']['link'] = \Drupal::service('path.alias_manager')->getAliasByPath($ipu_map_config->get('ipu_map_parliaments_page'));

    $stats['women'] = $config['women'];
    $stats['women']['link'] = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$config['women']['nid']);

    $stats['under45'] = $config['under45'];
    $stats['under45']['link'] = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$config['under45']['nid']);

    $stats['humanrights'] = $config['humanrights'];
    $stats['humanrights']['link'] = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$config['humanrights']['nid']);

    $build = [
      '#theme'      => 'ipu-tweaks-data-points',
      '#stats'    => $stats,
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
