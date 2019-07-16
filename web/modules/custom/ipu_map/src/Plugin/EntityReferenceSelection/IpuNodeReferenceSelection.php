namespace Drupal\ipu_map\Plugin\EntityReferenceSelection;

use Drupal\node\Plugin\EntityReferenceSelection\NodeSelection;

/**
* Entity reference selection.
*
* @EntityReferenceSelection(
*   id = "ipu_map:node",
*   label = @Translation("IPU node"),
*   group = "ipu",
* )
*/
    class IpuNodeSelection extends NodeSelection {

/**
* {@inheritdoc}
*/
public function getReferenceableEntities($match = NULL, $match_operator = 'CONTAINS', $limit = 0) {
return parent::getReferenceableEntities($match, $match_operator, 25);
}

}