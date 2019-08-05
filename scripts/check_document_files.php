<?php

$counter = 0;
$nids = \Drupal::entityQuery('node')
  //->condition('nid', 9741)
  ->condition('type', 'document')
  ->execute();

print ("Found " . count($nids) . " document nodes\n");
$nodes = entity_load_multiple('node', $nids, TRUE);

foreach ($nodes as $node_id => $node) {
  if ($counter < 10) {
    if (is_object($node->field_document_file)) {
      print("\n - Prccess node $node_id \n");
      print "\nHave file: ";
      print $node->field_document_file->value;
      //$node->field_document_file->entity->getFileUri();
      if ($node->hasTranslation('fr')) {
        $node_fr = $node->getTranslation('fr');
        print 'Translation: ' . $node->field_document_file->value;
        //$node->field_document_file->entity->getFileUri();
      }

      foreach ($node->field_document_file as $k => $file) {
        //$file = \Drupal\file\Entity\File::load($fid);

        print($counter . ': ' . $k . ': ' . print_r($file->value, TRUE));
      }
    }
    else {
      print 'Not found';
    }
  }
  $counter++;
  //print("\n - Updated node $node_id \n");
}

print("\n - Updated $counter nodes");
?>