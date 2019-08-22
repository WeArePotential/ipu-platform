<?php
use \Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;

$db_nodes = [];


$counter = 0;
$nids = \Drupal::entityQuery('node')
  //->condition('nid', 264)
  ->condition('nid', 9279)
  ->condition('type', 'publication')
  ->execute();

print ("Found ".count($nids)." publication nodes\n");
$nodes = entity_load_multiple('node', $nids, TRUE);

foreach ($nodes as $node_id => $node) {
    //$entity = Media::load($hero_image[0]['target_id']);
  if (empty($node->field_publication_document)) {
    $existing = NULL;
  } else {
    $existing = $node->get('field_publication_document')->getValue();
    print("\nId: ".$node_id.': '.print_r($existing, true));
    //$node_fr = $node->getTranslation('fr');
    //$existing_fr = $node_fr->get('field_media_image')->getValue();
    //print("\nId: ".$node_id.': '.print_r($existing_fr, true));
  }
//  if (empty($existing)) {
//    if (!empty($d8_nodes[$node_id])) {
//      $file = $d8_nodes[$node_id]['field_media_image'];
//      $node->field_media_image->setValue([
//          'target_id' => $file['fid'],
//      ]);
//        $node->save();
//      $counter++;
//    }
//  } else {
//    print("\n - File already exists for $node_id \n");
//  }
}

print("\n - Updated $counter nodes");
?>