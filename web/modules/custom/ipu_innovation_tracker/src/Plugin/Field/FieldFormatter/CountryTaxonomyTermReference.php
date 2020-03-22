<?php

/**
 * @file
 * Contains \Drupal\taxonomy_formatter\Plugin\Field\FieldFormatter\TaxonomyTermReferenceCsv.
 */

namespace Drupal\ipu_innovation_tracker\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * @FieldFormatter(
 *  id = "country_taxonomy_term_reference",
 *  label = @Translation("Parliament link"),
 *  field_types = {
 *   "entity_reference"
 *  }
 * )
 */
class CountryTaxonomyTermReference extends EntityReferenceFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'links_option' => TRUE,
      'separator_option' => ', ',
      'element_option' => '- None -',
      'wrapper_option' => '- None -',
      'element_class' => '',
      'wrapper_class' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = array();
    $element['isocode_option'] = array(
      '#type'           => 'checkbox',
      '#title'          => t('Link to parliament with ISO code'),
      '#description'    => t('When checked terms will link to the Parliament page using the ISO code'),
      '#default_value'  => $this->getSetting('isocode_option'),
    );
    $element['separator_option'] = array(
      '#type'           => 'textfield',
      '#title'          => t('Separator'),
      '#description'    => t('The separator to use, including leading and trailing spaces'),
      '#default_value'  => $this->getSetting('separator_option'),
    );
    $element['element_option'] = array(
      '#type'           => 'select',
      '#title'          => t('Element'),
      '#description'    => t('The HTML element to wrap each tag in'),
      '#default_value'  => $this->getSetting('element_option'),
      '#options'        => array(
        '- None -'  => '- None -',
        'span'      => 'span',
        'h1'        => 'h1',
        'h2'        => 'h2',
        'h3'        => 'h3',
        'h4'        => 'h4',
        'h5'        => 'h5',
        'strong'    => 'h6',
        'em'        => 'h7',
      ),
    );
    $element['element_class'] = array(
      '#type'           => 'textfield',
      '#title'          => t('Element Class'),
      '#description'    => t('The class assigned to the element'),
      '#default_value'  => $this->getSetting('element_class'),
    );
    $element['wrapper_option'] = array(
      '#type'           => 'select',
      '#title'          => t('Wrapper'),
      '#description'    => t('The HTML element to wrap the entire collection in'),
      '#default_value'  => $this->getSetting('wrapper_option'),
      '#options'        => array(
        '- None -'  => '- None -',
        'div'       => 'div',
        'span'      => 'span',
        'h1'        => 'h1',
        'h2'        => 'h2',
        'h3'        => 'h3',
        'h4'        => 'h4',
        'h5'        => 'h5',
        'p'         => 'p',
        'strong'    => 'strong',
        'em'        => 'em',
      ),
    );
    $element['wrapper_class'] = array(
      '#type'           => 'textfield',
      '#title'          => t('Wrapper Class'),
      '#description'    => t('The class assigned to the wrapper'),
      '#default_value'  => $this->getSetting('wrapper_class'),
    );
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();
    $summary[] = t('The Terms will be displayed separated by "@separator"', array('@separator' => $this->getSetting('separator_option')));
    if ($this->getSetting('isocode_option')) {
      $summary[]= $this->t('<br>The terms will link to the Parliament pages');
    }
    if ($this->getSetting('element_option')!="- None -") {
      $summary[]= t('<br>Elements will be wrapped in a "@element" tag',  array('@element' => $this->getSetting('element_option')));
      if (!empty($settings['element_class'])) {
        $summary[]= t(' with the class of @elemclass', array('@elemclass' => $this->getSetting('element_class')));
      }
    }
    if ($this->getSetting('wrapper_option')!="- None -") {
      $summary[]= t('<br>The entire list will be wrapped in a "@wrapper" tag', array('@wrapper' => $this->getSetting('wrapper_option')));
      if (!empty($settings['wrapper_class'])) {
        $summary[]= t(' with the class of @wrapclass', array('@wrapclass' => $this->getSetting('wrapper_class')));
      }
    }
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $element = array();
    $separator = SafeMarkup::checkPlain($this->getSetting('separator_option'));
    if ($this->getSetting('element_option')!='- None -') {
      $elementwrap[0] = '<' . $this->getSetting('element_option') . ' class="' . SafeMarkup::checkPlain($this->getSetting('element_class')) . '">';
      $elementwrap[1] = '</' . $this->getSetting('element_option') . '>';
    }
    else {
      $elementwrap[0] = '';
      $elementwrap[1] = '';
    }
    if ($this->getSetting('wrapper_option')!='- None -') {
      $wrapper[0] = '<' . $this->getSetting('wrapper_option') . ' class="' . \Drupal\Component\Utility\SafeMarkup::checkPlain($this->getSetting('wrapper_class')) . '">';
      $wrapper[1] = '</' . $this->getSetting('wrapper_option') . '>';
    }
    else {
      $wrapper[0] = '';
      $wrapper[1] = '';
    }
    $formatted = '';
    //$isocode = 'QQQQQW'.print_r($items[0], true);

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {

      if (1 || $this->getSetting('isocode_option')) {
        //$isocode = 'QQQQQW<pre>'.print_r(json_decode(json_encode($entity->toArray())), true). '</pre>';
        if ($entity->field_iso_code_for_parliament->value != '') {
          $isocode = $entity->field_iso_code_for_parliament->value;
        } else {
          $isocode = $entity->field_iso_code->value;
        }
        $url = Url::fromUserInput(($langcode == 'fr'? '/fr/parlement/' : '/parliament/') . $isocode. '/'. SafeMarkup::checkPlain($entity->label()) );
        $internal_link = Link::fromTextAndUrl(SafeMarkup::checkPlain($entity->label()), $url)->toString();
        $formatted .= $elementwrap[0] . $internal_link . $elementwrap[1] . $separator;
      }
      else {
        $formatted .= $elementwrap[0] . SafeMarkup::checkPlain($entity->label()) . $elementwrap[1] . $separator;
      }
    }
    $length = strlen($separator);
    $formatted = substr($formatted, 0 , -($length));
    $formatted = $wrapper[0] . $formatted . $wrapper[1];
    $element[0]['#markup'] = $formatted;
    return $element;
  }

}
