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
      // We only show current lannguage, except when not EN or FR
      $ignore_language = ($current_language == 'fr' ? 'en' : 'fr');

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

                if ($session->field_event_session_types->isEmpty()) {
                  $all_documents[0]['name'] = '';
                  $all_documents[0]['weight'] = 9999;
                  $all_documents[0]['sessions'][$session->id()]['title'] = $session->field_fc_sessions_session_title->value;
                  $all_documents[0]['sessions'][$session->id()]['documents'] = $documents;
                } else {
                  $session_types = $session->field_event_session_types->referencedEntities();
                  /** @var \Drupal\taxonomy\Entity\Term $term **/
                  foreach ($session_types as $term) {
                    if ($term->isTranslatable()) {} {
                      if ($term->hasTranslation($current_language)) {
                        $term = $term->getTranslation($current_language);
                      }
                    }
                    $all_documents[$term->id()]['name'] = $term->getName();
                    $all_documents[$term->id()]['weight'] = $term->getWeight();
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
      // Sort into weight ascending order - https://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
      $weighted = array_column($all_documents, 'weight');
      array_multisort($weighted, SORT_ASC, $all_documents);

      foreach ($all_documents as $document_group) {
        $title = $document_group['name'];
        $documents_per_group = [];

        foreach ($document_group['sessions'] as $document_group_session) {
          foreach ($document_group_session['documents'] as $document_group_session_document) {
            // Get rid of the documents that aren't in the current language. I tried setting access on the paragraphs with
            // hook_ENTITY_TYPE_access() but that seems to be ignored when manually sending a render array of paragraphs.
            $document_language = $document_group_session_document->get('field_publication_language')->value;
            if (($document_language != $ignore_language && $document_language != NULL) || $document_group_session_document->get('field_persistent')->value == TRUE) {
              $view_builder = \Drupal::entityTypeManager()->getViewBuilder($document_group_session_document->getEntityTypeId());
              $documents_per_group[] = $view_builder->view($document_group_session_document, 'full', $current_language);
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
      $title = '';
      $description = '';
      $other_documents = [];
      if (!$node->field_ipu_event_section->isEmpty()) {
        $sections = $node->field_ipu_event_section->referencedEntities();
        foreach ($sections as $section) {
          if ($section->getTranslation($current_language)->field_ie_fc_title->value != '') {
            $title = $section->getTranslation($current_language)->field_ie_fc_title->value;
          }
          if ($section->getTranslation($current_language)->field_ie_fc_description->value != '') {
            $description = $section->getTranslation($current_language)->field_ie_fc_description->value;
          }
          if (!$section->field_ipu_event_document_widget->isEmpty()) {
            $document_widgets = $section->field_ipu_event_document_widget->referencedEntities();
            foreach ($document_widgets as $document_widget) {
              if (!$document_widget->field_ipu_event_document->isEmpty()) {
                $documents = $document_widget->field_ipu_event_document->referencedEntities();
                foreach ($documents as $document) {
                  $document_language = $document->get('field_publication_language')->value;
                  if (($document_language !== $ignore_language && $document_language != NULL) || $document->get('field_persistent')->value == TRUE) {
                    $view_builder = \Drupal::entityTypeManager()->getViewBuilder($document->getEntityTypeId() );
                    $document_view = $view_builder->view($document, 'full', $current_language);
                    //$other_documents[] = render($document_view);
                    $other_documents[] = $document_view;
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
        '#title' => $title == '' ? $this->t('Documents') : $title,
        '#description' => $description,
        '#documents' => $other_documents, //(count($other_documents) > 0 ? implode('', $other_documents) : ''),
        '#session_documents' => $grouped_documents_render,
      ];
    }

    return [];
	}
}
