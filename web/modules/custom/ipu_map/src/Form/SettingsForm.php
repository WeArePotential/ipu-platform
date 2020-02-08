<?php

namespace Drupal\ipu_map\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Unicode;
use Drupal\taxonomy\Entity\Vocabulary;

/**
 * Provide settings for Stage File Proxy.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ipu_map_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'ipu_map.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $field_type = NULL) {
    $config = $this->config('ipu_map.settings');

    $items = [];
    $vocabularies = \Drupal\taxonomy\Entity\Vocabulary::loadMultiple();
    foreach ($vocabularies as $voc) {
      $items[$voc->get('vid')] = $voc->get('name');
    }
    $items[''] = t('-- Please choose a vocabulary --');
    asort($items);
    $form['vocabulary_id'] = [
      '#type' => 'select',
      '#title' => t('Vocabulary'),
      '#options' => $items,
      '#default_value' => $config->get('vocabulary_id'),
    ];

    $ipu_map_parliaments_page = $config->get('ipu_map_parliaments_page');
    if (empty($ipu_map_origin_dir)) {
      $ipu_map_origin_dir = \Drupal::service('site.path') . '/files';
    }
    $form['ipu_map_parliaments_page'] = [
      '#type' => 'textfield',
      '#title' => $this->t('The path of the parliaments page.'),
      '#default_value' => $ipu_map_parliaments_page,
      '#required' => TRUE,
      ];

    $form['ipu_map_data_provenance'] = array(
      '#type' => 'text_format',
      '#title' => 'Provenance',
      '#format' => $config->get('ipu_map_data_provenance.format', 'full_html'),
      '#default_value' => $config->get('ipu_map_data_provenance.value', ''),
      '#allowed_formats' => [
        $config->get('ipu_map_data_provenance.format') => 'full_html',
      ],
    );

    $form['data_points'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Data points'),
    ];
    $form['data_points']['gender'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Gender'),
    ];
    $form['data_points']['gender']['ipu_map_average_percent_women'] = [
      '#type' => 'number',
      '#min' => 0,
      '#max' => 100,
      '#size' => 6,
      '#step' => 'any',
      '#title' => $this->t('Global average women in parliament (%).'),
      '#default_value' => $config->get('ipu_map_average_percent_women', 100),
      '#required' => TRUE,
    ];
    $form['data_points']['gender']['ipu_map_gender_link'] = [
      '#title' => $this->t('Link'),
      '#type' => 'linkit',
      '#autocomplete_route_name' => 'linkit.autocomplete',
      '#autocomplete_route_parameters' => [ 'linkit_profile_id' => 'editor'],
      '#default_value' => $config->get('ipu_map_gender_link', 100),
    ];

    $form['data_points']['youth'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Youth'),
    ];

    $form['data_points']['youth']['ipu_map_average_percent_under45'] = [
      '#type' => 'number',
      '#min' => 0,
      '#max' => 100,
      '#size' => 6,
      '#step' => 'any',
      '#title' => $this->t('Global average MPs under 35 (%).'),
      '#default_value' => $config->get('ipu_map_average_percent_under45', 0),
      '#required' => TRUE,
    ];
    $form['data_points']['youth']['ipu_map_youth_link'] = [
      '#title' => $this->t('Link'),
      '#type' => 'linkit',
      '#autocomplete_route_name' => 'linkit.autocomplete',
      '#autocomplete_route_parameters' => [ 'linkit_profile_id' => 'editor'],
      '#default_value' => $config->get('ipu_map_youth_link', 100),
    ];

    $form['data_points']['hr'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Human rights'),
    ];

    $form['data_points']['hr']['ipu_map_hr_data'] = [
      '#type' => 'markup',
      '#markup' => 'This data point is set by editing individual countries',
    ];

    $form['data_points']['hr']['ipu_map_hr_link'] = [
      '#title' => $this->t('Link'),
      '#type' => 'linkit',
      '#autocomplete_route_name' => 'linkit.autocomplete',
      '#autocomplete_route_parameters' => ['id' => '0', 'linkit_profile_id' => 'editor'],
      '#default_value' => $config->get('ipu_map_hr_link', 100),
    ];
    /*$ipu_map_origin_dir = $config->get('origin_dir');
    if (!$ipu_map_origin_dir) {
      $ipu_map_origin_dir = $config->get('file_public_path');
      if (empty($ipu_map_origin_dir)) {
        $ipu_map_origin_dir = \Drupal::service('site.path') . '/files';
      }
    }
    $form['origin_dir'] = [
      '#type' => 'textfield',
      '#title' => $this->t('The origin directory.'),
      '#default_value' => $ipu_map_origin_dir,
      '#description' => $this->t('If this is set then Stage File Proxy will use a different path for the remote files. This is useful for multisite installations where the sites directory contains different names for each url. If this is not set, it defaults to the same path as the local site.'),
      '#required' => FALSE,
    ];


    $form['hotlink'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hotlink.'),
      '#default_value' => $config->get('hotlink'),
      '#description' => $this->t("If this is true then Stage File Proxy will not transfer the remote file to the local machine, it will just serve a 301 to the remote file and let the origin webserver handle it."),
      '#required' => FALSE,
    ];
    */
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $vocabulary_id = $form_state->getValue('vocabulary_id');
    // \Drupal::messenger()->addStatus($vocabulary_id);
    if (empty($vocabulary_id) || $vocabulary_id == '') {
       $form_state->setErrorByName('vocabulary_id', 'You must select a vocabulary.');
    }

    /*$origin = $form_state->getValue('origin');

    if (!empty($origin) && filter_var($origin, FILTER_VALIDATE_URL) === FALSE) {
      $form_state->setErrorByName('origin', 'Origin needs to be a valid URL.');
    }
    */
    $ipu_map_parliaments_page = $form_state->getValue('ipu_map_parliaments_page');
    if (!empty($ipu_map_parliaments_page) && Unicode::substr($ipu_map_parliaments_page, -1) === '/') {
      $form_state->setErrorByName('ipu_map_parliaments_page', 'Parliaments page URL cannot end in slash.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('ipu_map.settings');
    $values = $form_state->getValues();
    $keys = [
      'vocabulary_id',
      'ipu_map_parliaments_page',
      'ipu_map_average_percent_under45',
      'ipu_map_average_percent_women',
      'ipu_map_youth_link',
      'ipu_map_hr_link',
      'ipu_map_hr_text',
      'ipu_map_gender_link',
    ];
    foreach ($keys as $key) {
      $config->set($key, $form_state->getValue($key));
    }
    $config->set('ipu_map_data_provenance.value', $values['ipu_map_data_provenance']['value']);
    $config->set('ipu_map_data_provenance.format', $values['ipu_map_data_provenance']['format']);

    $config->save();
  }

}
