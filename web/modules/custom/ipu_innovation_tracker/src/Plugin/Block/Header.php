<?php
/**
 * @file Header class file.
 */

namespace Drupal\ipu_innovation_tracker\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;

/**
 * Class Header
 *
 * Provides a block with a configurable title, text and a navigation block
 * between all the innovation_tracker_issue content types.
 *
 * @Block(
 *   id = "ipu_innovation_tracker_header",
 *   admin_label = @Translation("Innovation Tracker Header"),
 * )
 */
class Header extends BlockBase {

  /**
   * The content type machine name of issues.
   */
  const IPU_ISSUE = 'innovation_tracker_issue';

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
    if (($this->currentNode instanceof NodeInterface) && $this->currentNode->bundle() === self::IPU_ISSUE) {
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
      '#prefix' => '<h1>',
      '#suffix' => '</h1>',
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
    if (($node instanceof NodeInterface) && $node->bundle() === self::IPU_ISSUE) {
      $build['wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => [
            'ipu-innovation-tracker__navigation',
          ]
        ]
      ];

      $build['wrapper']['previous'] = $this->buildNavigationLink($this->getCurrentIssue() - 1, $this->t('Previous'));
      $build['wrapper']['previous']['#attributes']['class'][] = 'ipu-innovation-tracker__previous';
      $build['wrapper']['current'] = $this->buildNavigationSelect();
      $build['wrapper']['next'] = $this->buildNavigationLink($this->getCurrentIssue() + 1, $this->t('Next'));
      $build['wrapper']['next']['#attributes']['class'][] = 'ipu-innovation-tracker__next';
    }
    return $build;
  }

  /**
   * Build the link to next/previous issue.
   *
   * @param int $issue_number
   *   The current issue number.
   *
   * @param string $link_text
   *   The link text.
   *
   * @return array
   *   The render array for a link to the previous issue.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  private function buildNavigationLink($issue_number, $link_text) {
    $link = [];
    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = \Drupal::service('entity_type.manager');
    $query = $entity_type_manager->getStorage('node')->getQuery();
    $result = $query->condition('status', NodeInterface::PUBLISHED)
      ->condition('type', self::IPU_ISSUE)
      ->condition('field_integer_single', $issue_number)
      ->execute();

    if (!empty($result)) {
      $nid = reset($result);
      $node = $entity_type_manager->getStorage('node')->load($nid);
      $link = $node->toLink($link_text)->toRenderable();
    }
    return $link;
  }

  /**
   * Build the select navigation that displays all issues.
   *
   * @return array
   */
  private function buildNavigationSelect() {
    // @todo: Finish this later.
    return ['#markup' => $this->currentNode->label()];
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
    if (($node instanceof NodeInterface) && $node->bundle() === self::IPU_ISSUE) {
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
