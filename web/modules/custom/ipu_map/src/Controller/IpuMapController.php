<?php

namespace Drupal\ipu_map\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Lock\NullLockBackend;
use Drupal\views\Views;
use Drupal\Core\Render\Markup;
use Drupal\Core\Link;
use Drupal\Core\Url;


/**
 * Defines HelloController class.
 */
class IpuMapController extends ControllerBase {

  private $title = '';
  private $member = false;
  private $current_language_id = 'en';

  public function __construct() {
    $this->current_language_id =  \Drupal::languageManager()->getCurrentLanguage(\Drupal\Core\Language\LanguageInterface::TYPE_CONTENT)->getId();
  }
  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content() {
    /* We can obtain the countries information here and pass as js settings.
    * Or we can obtain via ajax once the map is loaded.
    * To take advantage of Drupal caching, we'll load here, although this means
    * there's a lot of data to pass through settings.
    */
    $data = ipu_map_get_data_from_view();
    $settings = ['data' => $data];
    $markup = '<div id="ipu_map" class="container">' . $this->t('IPU Map demo') . '<div class="row mapcontainer_un"><div class="col-12 map">MAP</div></div></div>';

    $content = [
      '#type' => 'markup',
      '#markup' => $markup,
    ];

    $content['#attached']['library'][] = 'ipu_map/ipu_map';
    $content['#attached']['drupalSettings']['ipu_map'] = $settings;
    return $content;
  }

  public function countryContent($iso_code) {
    $country = $this->getCountryTerm($iso_code);
    if ($country == Null) {
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
    }
    // TODO: Remove styling and add to css. This will mean we can remove the use of #children
    $markup = '';
    $flag = ipu_map_get_flag($iso_code);
    $title = $country->title;
    //$flag_markup = Markup::create($flag);
    $flag_markup = \Drupal::service('renderer')->render($flag);

    if (!empty($country->field_iso_code_for_parliament->value)) {
      $parliament_iso_code = $country->get('field_iso_code_for_parliament')->value;
      $flag = ipu_map_get_flag($parliament_iso_code);
      $parent = $this->getCountryTerm($parliament_iso_code);
      $title = $parent->title;
    }
    else {
      $parliament_iso_code = $iso_code;
    }

    // Build arrays of data from parline and the taxonomy term fields
    $data = ipu_map_get_parline_data($parliament_iso_code, $this->getDescription(), $this->getMembershipStatus(), $this->getPrinciplesSignatoryStatus(),$this->getHumanRightsCases(), $this->current_language_id);

    $hub_link = ipu_map_get_hub_link($country->get('tid')->value);
    $data['country_stats']['#hub_links'] = $hub_link == '' ? null : ['#markup' => $hub_link];

    // Get any HR documents. If we have any, then we want to update the link
    // 285 is the term id of the HR Decisions Document type
    list($hr_documents_block, $hr_documents_count) = ipu_map_get_hr_documents([$country->get('tid')->value, 285]);
    if ($hr_documents_count > 0) {
      $url = Url::fromRoute('entity.node.canonical', ['node' => 205], [
        'attributes' => [
          'class' => [
            'btn-outline-primary',
            'btn-sm',
            'btn',
          ],
        ],
      ]);
      $link = Link::fromTextAndUrl(t('Find out more about the Committee on the Human Rights of Parliamentarians'), $url);
      $hr_documents_block['#suffix'] = $link->toString();
    }

    // If a document is attached to the country, we override
    // the hr_link and hr_text.
    $hr_documents = [];
    foreach ($country->field_hr_cases_document->getValue() as $document) {
      $hr_documents[] = $document['target_id'];
    };

    //drupal_set_message(print_r($this->getHumanRightsCases(), true));
    if (count($hr_documents) > 0 && $this->getHumanRightsCases() > 0)  {
      $document = \Drupal::entityTypeManager()->getStorage('node')->load($hr_documents[0]);
      if ($this->current_language_id != 'en' && $document->hasTranslation($this->current_language_id)) {
        $document = $document->getTranslation($this->current_language_id);
      }
      // Will either be field_external_link or
      // field_document_file
      // i.e. $document->field_external_link->getValue()[0]['uri']);
      // or $document->field_document_link
      if ($document->field_document_file !== NULL) {
        $fid = $document->field_document_file->getValue()[0]['target_id'];
        if ($file = \Drupal\file\Entity\File::load($fid)) {
          $uri = $file->getFileUri();
          $url = \Drupal\Core\Url::fromUri(file_create_url($uri))->toString();
          $data['data']['#ipu_map_hr_link'] = $url;
          $data['data']['#ipu_map_hr_text'] = ['#markup'=>t('Read the cases')];
        }
      }
    } else {
      $data['data']['#ipu_map_hr_text'] = ['#markup'=>t('Report an MP in danger')];
    }

    $regions = [];
    foreach($country->field_geographic_region->getValue() as $term) {
      $regions[] = $term['target_id'];
    };

    // Get a view with news and stories
    $news_block = ipu_map_get_country_news($country->get('tid')->value, $regions);

    // Get a view with innovation traker stories
    $innovation_tracker_stories_block = ipu_map_get_innovation_tracker_stories($country->get('tid')->value);

    $case_studies_block = ipu_map_get_country_case_studies($country->get('tid')->value);
    // Rendering of the block is done in the theming, so no need to run $markup .= \Drupal::service('renderer')->render($content);

    $events_block = ipu_map_get_events($country->get('tid')->value);

    $callout = ipu_map_get_callout();

    $geogroups = [];
    foreach ($country->field_geopolitical_group->getValue() as $term) {
      $geogroups[] = $term['target_id'];
    };
    $geogroups_block = ipu_map_get_geogroups(implode(', ', $geogroups));

    $region_block = ipu_map_get_countries_region(implode(', ', $regions));

    $page = [
      '#theme' => 'ipu-map-country',
      '#content' => [
        'title' => $this->title,
        'flag' => $flag,
        'parline_data' => $data['data'],
        'country_stats' => $data['country_stats'],
        'news_and_stories' => $news_block,
        'innovation_tracker_stories' => $innovation_tracker_stories_block,
        'case_studies' => $case_studies_block,
        'events' => $events_block,
        'hr_documents' => $hr_documents_block,
        'callout' => $callout,
        'geogroups' => $geogroups_block,
        'countries_region' => $region_block,
      ],
    ];

    return $page;
  }

  public function getMembershipStatus() {
    return $this->member;
  }
  public function getPrinciplesSignatoryStatus() {
    return $this->principlessignatory;
  }
  public function getHumanRightsCases() {
    return $this->humanrightscases;
  }
  public function getDescription() {
    return $this->description;
  }
  public function getCountryTitle( $iso_code) {
    // This will set the breadcrumb and html page title. The content title is set
    // in the #title of the content
    $country = $this->getCountryTerm($iso_code);
    if ($country->field_iso_code_for_parliament->value != '') {
      $country = $this->getCountryTerm($country->field_iso_code_for_parliament->value);
    }
    return $this->title;
  }

  private function getCountryTerm( $iso_code) {
    $country = Null;

    $query = \Drupal::entityQuery('taxonomy_term');
    $tids = $query
      ->condition('field_iso_code.value', $iso_code, '=')
      //->condition($this->current_language_id)
      ->condition('status', 1)  // Once we have our conditions specified we use the execute() method to run the query
      ->execute();
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadMultiple($tids);
    foreach ($terms as $term) {
      if($term->hasTranslation($this->current_language_id)) {
        $country = \Drupal::service('entity.repository')
          ->getTranslationFromContext($term, $this->current_language_id);
      } else {
        $country = $term; //return title of term
      }
    }
    if ($country) {
      $this->member = $country->field_ipu_member->value;
      $this->principlessignatory = $country->field_principles_signatory->value;
      $this->humanrightscases = $country->field_human_rights_cases->value;
      $this->description = $country->description->value;
      $this->title = $country->name->value;
      return $country;
    } else {
      $this->title = 'No country found for '.$iso_code;
      return Null;
    }
  }
}
