<?php
/**
 * @file IssueHelper class file.
 */
namespace Drupal\ipu_innovation_tracker\Helper;

use Drupal\node\NodeInterface;

/**
 * Class IssueHelper
 *
 * Helper class to modify innovation_tracker_issue content output.
 */
class IssueHelper {

  /**
   * Alter the Hub Updates section of the issue, so that the hub updates are
   * grouped by the hub type (list field) of the innovation hub (taxonomy).
   *
   * @param \Drupal\node\NodeInterface $node
   *   The issue node.
   *
   * @return array
   *   The render array of hubs grouped by the hub types.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function alterHubs(NodeInterface $node) {
    $hub_types = self::getAllHubTypes();
    // Exit if we don't have nothing to work with.
    if (empty($hub_types)) {
      return [];
    }
    $hub_updates = $node->get('field_hub_updates')->referencedEntities();
    // Exit if there are no hub updates.
    if (empty($hub_updates)) {
      return [];
    }
    /** @var \Drupal\Core\Entity\EntityViewBuilderInterface $view_builder */
    $view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
    $build = [];
    foreach($hub_types as $hub_type) {
      // Generate a machine name to use as index in the render array.
      $machine_name = str_replace(' ', '_', strtolower($hub_type));
      // Create a container and header for the grouping.
      $build[$machine_name] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => [
            'innovation-tracker-issue__updates-row',
          ],
        ],
        'title' => [
          '#markup' => t($hub_type),
          '#prefix' => '<div class="innovation-tracker-issue__hub-type">',
          '#suffix' => '</div>',
        ],
        'content' => [
          '#type' => 'container',
          '#attributes' => [
            'class' => [
              'innovation-tracker-issue__updates-content',
            ],
          ],
        ],
      ];

      // Loop through all updates, if the hub update is tagged with a term of
      // this hub type, then add it to the group.
      foreach ($hub_updates as $hub_update) {
        // Get the Innovation hub term  tagging the hub update.
        $terms = $hub_update->get('field_innovation_hub')->referencedEntities();
        // Single value, so reset it and get the first value.
        $term = reset($terms);
        // If the hub type of term matches the current group then add it.
        if ($term->get('field_hub_type')->getString() === $hub_type) {
          // Render the node using highlight view mode.
          $build[$machine_name]['content'][] = $view_builder->view($hub_update, 'highlight');
        }
      }
    }
    return $build;
  }

  /**
   * Generate a link to the hub given a node entity of type hub_update.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node.
   *
   * @return array|mixed[]
   *   The render array of a link to the innovation_hub taxonomy term.
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  public static function getHubLink(NodeInterface $node) {
    /** @var \Drupal\taxonomy\TermInterface $term */
    $term = $node->get('field_innovation_hub')->referencedEntities();
    $term = reset($term);
    $link = $term->toLink($term->label(), 'link', ['attributes' => ['class' => ['innovation-tracker-issue__hub-link']]])->toRenderable();
    $link['#weight'] = 50;
    return $link;
  }

  /**
   * Get all the hub types allowed on the field_hub_type of the taxonomy terms.
   *
   * @return array
   *   All allowed values defined on the list field.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private static function getAllHubTypes() {
    // Load the field config for field_hub_type.
    $config = \Drupal::entityTypeManager()
      ->getStorage('field_storage_config')
      ->load('taxonomy_term.field_hub_type');
    // Return the allowed values.
    return $config->get('settings')['allowed_values'];
  }
}
