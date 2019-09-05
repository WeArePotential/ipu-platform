<?php
/*
 * Get all articles with no news type, and set them to News in brief.
 * All should also be published.
 */
$counter = 0;
$nids = \Drupal::entityQuery('node')
  //->condition('nid', 3395)
  ->condition('type', 'article')
//  ->condition('field_article_type', 'IS EMPTY')
  ->condition('nid', 8000, '>=')
  ->notExists('field_article_type')
    ->execute();

print ("Found ".count($nids)." article nodes\n");

//print_r($nids);
$nodes = entity_load_multiple('node', $nids, TRUE);

foreach ($nodes as $node_id => $node) {
  $node->field_article_type[] = 43;
  $node->setPublished(TRUE);
  $counter++;
  $node->save();
}

print("\n - Updated $counter nodes");
?>