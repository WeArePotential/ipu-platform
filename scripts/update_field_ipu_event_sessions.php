<?php
ini_set("memory_limit",-1);
use Drupal\paragraphs\Entity\Paragraph;  // Don't forget to use a little Class...

function getTermOrAdd($session_title, $vid = 'event_session_type') {
  $session_title = trim($session_title);
  $terms = \Drupal::entityTypeManager()
    ->getStorage('taxonomy_term')
    ->loadByProperties([
      'name' => $session_title,
      'vid' => $vid,
      ]);
  //print("\nGot term? ". print_r($term, true));
  foreach ($terms as $tid=>$term) {
    return $tid;
  }

  $new_term = \Drupal\taxonomy\Entity\Term::create([
    'vid' => $vid,
    'name' => $session_title,
  ]);
  $new_term->enforceIsNew();
  $new_term->save();  // create new term id and return it;
  return $new_term->id();
}

$tally = 0;

// Get all paragraphs of type ipu_event_sessions
// and build an array of $pid and field_original_fc_id
$fc_to_para_mapping = array();
$pids = \Drupal::entityQuery('paragraph')
  //->condition('id', 19261)
  ->condition('type', 'ipu_event_sessions')
  ->execute();
$paragraphs = entity_load_multiple('paragraph', $pids, TRUE);

foreach ($paragraphs as $pid=>$paragraph) {
  $paras = $paragraph->toArray();
  $pid = $paras['id'][0]['value'];
  print("\nPID: ". $pid);

  // Get existing session types
  $session_types = [];
  print("\nSession title: " . $paragraph->field_fc_sessions_session_title->value);
  print("\nSession type term id was: " . $paragraph->field_event_session_types->target_id);

  $st_id = $paragraph->field_fc_session_types->target_id;
  if ($st_id != NULL) {
    print("\nSession type target_id: " . $st_id);
    $st = entity_load('paragraph', $st_id, TRUE);
    $st_title = $st->field_fc_session_type_title->value;
    print("\nSession type title: " . $st_title);
    if ($st_title != '' ) {
      $session_types[] = $st_title;
      $tid = getTermOrAdd($st_title);
      print("\nUpdate session type to : " . $tid);
      $paragraph->set('field_event_session_types', $tid);
      $paragraph->save();
    }
  }

  //$field_ipu_event_session_type_title = $paras['field_ipu_event_session_type_title'][0]['value'];
  //print("\nTitle: " . $field_ipu_event_session_type_title);


//  foreach($paras as $key=>$field_original_fc_ids) {
//    if ($key == "field_original_fc_id") {
//      //print "$y:\n". print_r($field_original_fc_ids, true)."\n";
//      foreach ($field_original_fc_ids as $target_fc_id) {
//        $fc_to_para_mapping[$target_fc_id['value']] = $pid;
//      }
//    }
//  }
//}

/*// Get all paragraphs of type ipu_event_document_widget
$pids = \Drupal::entityQuery('paragraph')
  ->condition('type', 'ipu_event_document_widget')
  ->execute();

print ("Found ".count($pids)." paragraphs\n");
foreach ($pids as $pid) {
  //print ("$key:\n");
  //print_r($pid, true);
}
//$pids = array(15373);
$paragraphs = entity_load_multiple('paragraph', $pids, TRUE);
foreach ($paragraphs as $pid => $paragraph) {
  print ("Process $pid:\n");
  //
  //debug($paragraph->field_fc_original_reference);

  $paras = $paragraph->toArray();
  $update = FALSE;
  foreach ($paras as $y => $fc_original_references) {
    if ($y == "field_fc_original_reference") {
      print "$y:\n" . print_r($fc_original_references, TRUE) . "\n";
      foreach ($fc_original_references as $target_fc_id) {
        // Find para with this fc id
        $new_pid = $fc_to_para_mapping[$target_fc_id['value']];
        print "New ref: " . print_r($new_pid, TRUE) . "\n";
        $match = FALSE;
        foreach ($paras['field_ipu_event_document'] as $ref) {
          if ($ref['target_id'] == $new_pid) {
            $match = TRUE;
          }
        }
        if ($match == FALSE) {
          $p = Paragraph::load($new_pid);
          $paragraph->field_ipu_event_document[] = $p;
          $update = TRUE;
        } else {
          print "Ref exists\n";
        }
      }
    }
  }
*/

//  if ($update) {
//    $tally++;
//    //$paragraph->save();
//  }

} // end for each

print("\n - Updsted $tally paragraphs");

