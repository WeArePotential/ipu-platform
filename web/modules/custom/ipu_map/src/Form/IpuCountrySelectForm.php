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
    $form['isocode'] = array(
      '#type' => 'select',
      '#options'=> $options,
      '#attributes' => array('class' => array('ipu-country-select')), // Add a class if required
      '#title' => t('Parliaments'),
      '#chosen' => TRUE,
      '#required' => FALSE,
    );
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Go'),
      '#button_type' => 'primary',
    );

    $block_manager = \Drupal::service('plugin.manager.block');
    $config = ['nid'=> 236, 'link_text'=>'Our support for parliaments', 'view_mode'=>'highlight', 'current'=>FALSE];// You can hard code configuration or you load from settings.
    $plugin_block = $block_manager->createInstance('ipu_node_block', $config);
    $render = $plugin_block->build();
    $html = \Drupal::service('renderer')->renderRoot($render);
    $form['#suffix'] = '<div class="parliaments-menu-cta-node">'.$html.'</div>';

    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $language =  \Drupal::languageManager()->getCurrentLanguage()->getId();
    $stubpath = ($language == 'fr' ? '/parlement' : '/parliament');
    $isocode = $form_state->getValue('isocode');
    if ($isocode) {
      if ($isocode != 'XX') {
        $path = $stubpath . '/' . $isocode;
        $form_state->setRedirectUrl(Url::fromUserInput($path));
      }
    }
    //$response = new RedirectResponse($path);
    //$response->send();
    return;
    //drupal_set_message($this->t('@emp_name ,Your application is being submitted!', array('@emp_name' => $form_state->getValue('employee_name'))));
  }
}