<?php

namespace Drupal\ipu_events\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;
use Drupal\core\Render;
/**
 * Plugin for a DS field containing event documents content.
 *
 * @DsField(
 *   id = "event_documents_content",
 *   title = @Translation("Event Documents Content"),
 *   entity_type = "node",
 *   provider = "ipu_events",
 *   ui_limit = {"ipu_event|*"}
 * )
 */

class EventDocumentsContent extends DsFieldBase {

  /**
   * {@inheritdoc}
   */
	public function build(){
    /** @var $node Drupal\node\Entity\Node **/
    $node = $this->entity();

    if ($node->bundle() == 'ipu_event') {
      $all_documents = [];
      $current_language = \Drupal::languageManager()->getCurrentLanguage()->getId();
  
      // Get the session paragraphs.
      if (!$node->field_ipu_event_sessions->isEmpty()) {
        $sessions = $node->field_ipu_event_sessions->referencedEntities();
  
        // Get the document widget paragraphs.
        foreach ($sessions as $session) {
          if (!$session->field_ipu_event_document_widget->isEmpty()) {
            $document_widgets = $session->field_ipu_event_document_widget->referencedEntities();
    
            // Get the document paragraphs.
            foreach ($document_widgets as $document_widget) {
              if (!$document_widget->field_ipu_event_document->isEmpty()) {
                $documents = $document_widget->field_ipu_event_document->referencedEntities();
  
                // We're grouping by session type taxonomy terms from the session paragraph.
                if (!$session->field_event_session_types->isEmpty()) {
                  $session_types = $session->field_event_session_types->referencedEntities();
                  /** @var \Drupal\taxonomy\Entity\Term $term **/
                  foreach ($session_types as $term) {
                    $all_documents[$term->id()]['name'] = $term->getName();
                    $all_documents[$term->id()]['sessions'][$session->id()]['title'] = $session->field_fc_sessions_session_title->value;
                    $all_documents[$term->id()]['sessions'][$session->id()]['documents'] = $documents;
                  }
                }
              }
            }
          }
        }
      }
  
      // Create the render array of the documents grouped by the session type.
      $grouped_documents_render = [];
      foreach ($all_documents as $document_group) {
        $title = $document_group['name'];
        $documents_per_group = [];
  
        foreach ($document_group['sessions'] as $document_group_session) {
          foreach ($document_group_session['documents'] as $document_group_session_document) {
            // Get rid of the documents that aren't in the current language. I tried setting access on the paragraphs with
            // hook_ENTITY_TYPE_access() but that seems to be ignored when manually sending a render array of paragraphs.
            $document_lanagage = $document_group_session_document->get('field_publication_language')->value;
            if ($current_language == $document_lanagage) {
              $view_builder = \Drupal::entityTypeManager()->getViewBuilder($document_group_session_document->getEntityTypeId());
              $documents_per_group[] = $view_builder->view($document_group_session_document, 'full');
            }
          }
        }
  
        $grouped_documents_render[] = [
          '#theme' => 'item_list',
          '#title' => $title,
          '#list_type' => 'ul',
          '#items' => $documents_per_group,
        ];
      }

      // Other documents
      $other_documents = [];
      if (!$node->field_ipu_event_section->isEmpty()) {
        $sections = $node->field_ipu_event_section->referencedEntities();
        foreach ($sections as $section) {
          if (!$section->field_ipu_event_document_widget->isEmpty()) {
            $document_widgets = $section->field_ipu_event_document_widget->referencedEntities();
            if ($section->field_ie_fc_title->value != '') {
              $other_documents[] = '<h2>' . $section->field_ie_fc_title->value . '</h2>';
            }
            foreach ($document_widgets as $document_widget) {
              if (!$document_widget->field_ipu_event_document->isEmpty()) {
                $documents = $document_widget->field_ipu_event_document->referencedEntities();
                foreach ($documents as $document) {
                  $document_lanagage = $document->get('field_publication_language')->value;
                  if ($current_language == $document_lanagage) {
                    $view_builder = \Drupal::entityTypeManager()->getViewBuilder($document->getEntityTypeId());
                    $document_view = $view_builder->view($document, 'full');
                    $other_documents[] = render($document_view);
                  }
                  // $other_documents[] = $document->id(). '-'. $document_lanagage;
                }
              }
            }
          }
        }
      }


      return [
        '#type' => 'event_documents',
        '#content' => $grouped_documents_render,
        '#suffix' => (count($other_documents) > 0 ? implode('', $other_documents) : ''),
      ];
    }
		
    return;
	}
}
