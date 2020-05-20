<?php
/**
 * @file Header class file.
 */

namespace Drupal\ipu_tweaks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;
use Drupal\Core\Url;

/**
 * Class ImpactReportFooter
 *
 * Provides a block with a configurable title, text and a navigation block
 * for the report content type.
 *
 * @Block(
 *   id = "ipu_impact_report_footer",
 *   admin_label = @Translation("Impact Report Footer"),
 * )
 */
class ImpactReportFooter extends BlockBase {

  /**
   * The content type machine name of reports and sections.
   */
  const IPU_IMPACT_REPORT_SECTION = 'report_section';
  const IPU_IMPACT_REPORT = 'report';

  /**
   * The storage for node.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  private $storage;

  /**
   * The current node.
   *
   * @var \Drupal\node\NodeInterface
   */
  private $currentNode;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->storage = \Drupal::service('entity_type.manager')->getStorage('node');
    $this->currentNode = \Drupal::routeMatch()->getParameter('node');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    if (($this->currentNode instanceof NodeInterface) && ($this->currentNode->bundle() === self::IPU_IMPACT_REPORT_SECTION || $this->currentNode->bundle() === self::IPU_IMPACT_REPORT)) {
      $build['header'] = $this->buildHeader();
      $build['content'] = $this->buildContent();
    }
    return $build;
  }

  /**
   * Build the renderable array for the header element.
   */
  private function buildHeader() {
    return [
      '#markup' => $this->t($this->configuration['page_title']) ?? '',
    ];
  }

  /**
   * Build the renderable array for the content element.
   */
  private function buildContent() {
    return [
      '#type' => 'container',
      'content' => [
        '#markup' => check_markup($this->configuration['content']['value'] ?? '', $this->configuration['content']['format']),
      ],
      'navigation' => $this->buildNavigation(),
    ];
  }

  /**
   * Build the renderable array for the navigation element.
   */
  private function buildNavigation() {
    $node = \Drupal::routeMatch()->getParameter('node');
    $build = [];

    if (($node instanceof NodeInterface) && ($node->bundle() === self::IPU_IMPACT_REPORT_SECTION || $node->bundle() === self::IPU_IMPACT_REPORT)) {
      $build['wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => [
            'navigation',
          ],
        ],
      ];

      $links = [];
      $publication_link = [];
      $language = $node->language()->getId();

      // Get the main report page
      $query = \Drupal::entityQuery('node')
        ->condition('type', self::IPU_IMPACT_REPORT)
        ->condition('status', \Drupal\node\NodeInterface::PUBLISHED)
//        ->condition('langcode', $language)
        ->sort('nid');
      $results = $query->execute();
      if (count($results) == 0) {
        return ['#markup' => 'Report not found'];
      }
      $count = 0;
      $last_link = [];
      foreach($results as $nid) {
        $report_page = entity_load('node', $nid);
        if ($report_page->hasTranslation($language)) {
          $report_page = $report_page->getTranslation($language);
        }
        $link = $report_page->toLink($report_page->label(), 'canonical')
          ->toRenderable();
//        $link['#suffix'] = '</div>';
        if ((int)$count++ < count($results)-1) {
//          $link['#prefix'] = '<div class="term-icon term-icon-logo-' . $language . '">';
//          $link['#attributes'] = ['class' => 'nav-link'];
          // Get the link to the PDF of report
          $publication_link = $report_page->field_publication_link;
          $links[] = $link;
        } else {
//          $link['#prefix'] = '<div class="term-icon term-icon-report-next">';
          $last_link = $link;
        }
      }

      // Now get the pages
      $query = \Drupal::entityQuery('node')
        ->condition('type', [self::IPU_IMPACT_REPORT_SECTION], 'IN')
        ->condition('status', \Drupal\node\NodeInterface::PUBLISHED)
//        ->condition('langcode', $language)
        ->sort('title');

      //->condition('field_hub_updates', $node->id());
      $results = $query->execute();
      if (count($results) == 0) {
        return ['#markup' => ''];
      }

      foreach($results as $nid) {
        $section_page = entity_load('node', $nid);
        if ($section_page->hasTranslation($language)) {
          $section_page = $section_page->getTranslation($language);
        }
        $link_title = $section_page->label();
        $theme_id = 0;
        if (!empty($section_page->field_theme)) {
          foreach ($section_page->field_theme as $theme) {
            if ($theme->entity) {
              $theme->entity = $theme->entity->getTranslation($language);
              $link_text = $theme->entity->label();
              $theme_id = $theme->entity->id();
            }
          }
          $link = $section_page->toLink($link_text, 'canonical')
            ->toRenderable();
          //$link['#prefix'] = '<div class="term-icon term-icon-' . $theme_id . '">';
          //$link['#suffix'] = '</div>';
          $link['#attributes'] = ['class' => ['nav-link']];
          if ($nid == $node->id()) {
            $link['#attributes']['class'][] = 'active';
          }

          $link['#weight'] = -2;
          $links[] = $link;
        }
      }
      $links[] = $last_link;

      $links1 = array_slice($links, 0, 4);
      $list1 = [
        '#theme' => 'item_list',
        '#list_type' => 'ul',
        //'#title' => '',
        '#items' => $links1,
      ];
      $links2 = array_slice($links, 5, 4);
      $list2 = [
        '#theme' => 'item_list',
        '#list_type' => 'ul',
//        '#title' => '',
        '#items' => $links2,
      ];

      $col4 = [];
      if (!empty($publication_link)) {
        $col4 = ['link'=> [ 'uri' => Url::fromUri($publication_link->uri)->toString(), 'title' => $publication_link->title]];
      }
      return [
        '#theme' => 'ipu-tweaks-report-footer',
        '#col1' => '', //['#markup'=>'<div class="logo"><a href=""><img alt="" src=""/>Logo</a></div>'],
        '#title' => '',
        '#language' => $language,
        '#col2' => $list1, //\Drupal::service('renderer')->render($list1),
        '#col3' => $list2,
        '#col4' => $col4,
        '#attributes' => ['class' => ''],
//        '#prefix' => '<div>',
//        '#suffix' => '</div>',
//        '#wrapper_attributes' => ['class' => 'sticky-top navbar navbar-default', 'id' => 'main-nav'],
      ];
    }

    return $build;
  }


  /**
   * Build the select navigation that displays all issues.
   *
   * @return array
   */
  private function buildNavigationSelect() {
    /* @todo: Turn this into a dropdown
    $nids = [];
    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager */
    /*
     $entity_type_manager = \Drupal::service('entity_type.manager');
    $query = $entity_type_manager->getStorage('node')->getQuery();
    $result = $query
      ->condition('status', NodeInterface::PUBLISHED)
      ->condition('type', self::IPU_ISSUE)
      ->order()
      //->condition('field_integer_single', $issue_number)
      ->execute();

    if (!empty($result)) {
      foreach($result as $nid=>$node) {
        $nids[$nid] = $node->title;
      }
      //$nid = reset($result);
      //$node = $entity_type_manager->getStorage('node')->load($nid);
      //$link = $node->toLink($link_text)->toRenderable();
    }
    return $link;
  */
    return ['#markup' => '<div class="ipu-innovation-tracker__current">'.$this->currentNode->label(). '</div>'];
  }

  /**
   * Returns the issue number of the current node.
   *
   * @return string
   *   The issue number.
   */
  private function getCurrentIssue() {
    return $this->currentNode->get('field_integer_single')->getString();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['page_title'] = [
      '#title' => t('Title'),
      '#description' => t('Enter text to show as a title for all issues. For example: IPU Innovation Tracker'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['page_title'] ?? '',
    ];

    $form['content'] = [
      '#title' => t('Content'),
      '#description' => t('Enter text to display as content'),
      '#type' => 'text_format',
      '#default_value' => $this->configuration['content']['value'] ?? '',
      '#format' => $this->configuration['content']['format'] ?? filter_default_format(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('page_title', $form_state->getValue('page_title'));
    $this->setConfigurationValue('content', $form_state->getValue('content'));
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    // Invalidate the block's cache when the node is updated.
    $node = \Drupal::routeMatch()->getParameter('node');
    if (($node instanceof NodeInterface) && $node->bundle() === self::IPU_IMPACT_REPORT_SECTION) {
      return Cache::mergeTags(parent::getCacheTags(), ['node_list']);
    }
    else {
      return parent::getCacheTags();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    // Cache separately for each route.
    return Cache::mergeContexts(parent::getCacheContexts(), ['url.path']);
  }
}
