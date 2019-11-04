<?php
/**
 * @file
 * Contains \Drupal\ipu_map\Form\IpuCountrySelectForm.
 */
namespace Drupal\ipu_map\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\core\Render\RendererInterface;

class IpuCountrySelectForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ipu_map_country_select_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $all_parliaments = t('All parliaments')->render();
    $options = ['XX'=>$all_parliaments];
    $country_terms = ipu_map_get_countries();

    // We have already set the parliaments page in config.
    $form['#action'] = \Drupal::config('ipu_map.settings')->get('ipu_map_parliaments_page');

    $i = 0;
    $language =  \Drupal::languageManager()->getCurrentLanguage()->getId();

    foreach ($country_terms as $country_term) {
      //if ($i++ <2) print_r($country_term->get('field_iso_code')->getValue());
      if ($country_term->hasField('field_iso_code')) {
        if (!$country_term->get('field_iso_code')->isEmpty()) {
          $iso_code = $country_term->get('field_iso_code')
            ->getValue()[0]['value'];
          $options[$iso_code] = $country_term->getName();
        } else {
          // TODO: Decide what to do with countries which have no ISO code
          // $options[$i++] = $country_term->getName();
        }
      }
    }
    $form['isocode'] = [
      '#type' => 'select',
      '#options' => $options,
      '#attributes' => [
        'class' => ['ipu-country-select'],
      ],
      '#title' => t('Parliaments'),
      '#chosen' => TRUE,
      '#required' => FALSE,
    ];

    $stubpath = ($language == 'fr' ? '/fr/parlement' : '/parliament');
    $form['#action'] = $stubpath;

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Go'),
      '#button_type' => 'primary',
    ];

    $block_manager = \Drupal::service('plugin.manager.block');
    $config = [
      'nid' => 236,
      'link_text' => 'Our support for parliaments',
      'view_mode' => 'highlight',
      'current' => FALSE,
    ];
    // You can hard code configuration or you load from settings.
    $plugin_block = $block_manager->createInstance('ipu_node_block', $config);
    $render = $plugin_block->build();
    $html = \Drupal::service('renderer')->renderRoot($render);
    $form['#suffix'] = '<div class="parliaments-menu-cta-node">' . $html . '</div>';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // We use the action to build the url as the submitForm doesn't recognise
    // the language of the calling page.
    $isocode = $form_state->getValue('isocode');
    if ($isocode) {
      if ($isocode != 'XX') {
        $stubpath = $form['#action'];
        $path = $stubpath . '/' . $isocode;
        $form_state->setRedirectUrl(Url::fromUserInput($path));
      }
    }
  }

}