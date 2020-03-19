<?php
/**
 * @file ConfigurationForm class file.
 */

namespace Drupal\ipu_innovation_tracker\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigurationForm
 */
class ConfigurationForm extends ConfigFormBase {

  /**
   * @inheritDoc
   */
  protected function getEditableConfigNames() {
    return [
      'ipu_innovation_tracker.contribute',
    ];
  }

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'ipu_innovation_tracker_configuration_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ipu_innovation_tracker.contribute');

    $form['content'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Content'),
      '#default_value' => $config->get('content.value') ?? '',
      '#format' => $config->get('content.format') ?? filter_default_format(),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $content = $form_state->getValue('content');
    $this->config('ipu_innovation_tracker.contribute')
      ->set('content.value', $content['value'])
      ->set('content.format', $content['format'])
      ->save();

    Cache::invalidateTags(['config:ipu_innovation_tracker.contribute']);

    parent::submitForm($form, $form_state);
  }

}
