<?php

$counter = 0;
$nids = \Drupal::entityQuery('node')
  //->condition('nid', 9741)
  ->condition('type', 'ipu_event')
  ->execute();

print ("Found ".count($nids)." event nodes\n");
$nodes = entity_load_multiple('node', $nids, TRUE);

foreach ($nodes as $node_id => $node) {
  ipu_tweaks_update_computed_date($node);
  $counter++;
  $node->save();
  print("\n - Updated node $node_id \n");
}

print("\n - Updated $counter nodes");
?>