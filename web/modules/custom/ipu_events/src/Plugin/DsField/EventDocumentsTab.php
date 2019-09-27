<?php

namespace Drupal\ipu_events\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;

/**
 * Plugin for a DS field containing event documents content.
 *
 * @DsField(
 *   id = "event_documents_tab",
 *   title = @Translation("Event Documents Tab"),
 *   entity_type = "node",
 *   provider = "ipu_events",
 *   ui_limit = {"ipu_event|*"}
 * )
 */

class EventDocumentsTab extends DsFieldBase {

  /**
   * {@inheritdoc}
   */
	public function build() {

    /** @var $node Drupal\node\Entity\Node * */
    $have_documents = FALSE;
    $node = $this->entity();

    if ($node->bundle() == 'ipu_event') {
      $all_documents = [];
      $current_language = \Drupal::languageManager()
        ->getCurrentLanguage()
        ->getId();

      if ($node->field_hide_documents_menu->value == 1) {
        $have_documents = FALSE;
        return [];
      }


      // Get the session paragraphs.
      if (!$node->field_ipu_event_sessions->isEmpty()) {
        $sessions = $node->field_ipu_event_sessions->referencedEntities();
        foreach ($sessions as $session) {
          if (!$session->field_ipu_event_document_widget->isEmpty()) {
            $have_documents = TRUE;
          }
        }
      }

      if (!$node->field_ipu_event_section->isEmpty()) {
        $sections = $node->field_ipu_event_section->referencedEntities();
        foreach ($sections as $section) {
          if (!$section->field_ipu_event_document_widget->isEmpty()) {
            $have_documents = TRUE;
          }
        }
      }

      if (!$node->field_event_sub_page->isEmpty()) {
        $subpages = $node->field_event_sub_page->referencedEntities();
        foreach ($subpages as $subpage) {
          if (!$subpage->field_ipu_event_section->isEmpty()) {
            $sections = $node->field_ipu_event_section->referencedEntities();
            foreach ($sections as $section) {
              if (!$section->field_ipu_event_document_widget->isEmpty()) {
                $have_documents = TRUE;
              }
            }
          }
        }
      }

    }
    if ($have_documents) {
      return [
        '#markup' => $this->t('Documents'),
        '#order' => (is_numeric(trim($node->field_documents_order_number->value)) ? trim($node->field_documents_order_number->value) : 0),
        ];
    } else {
      return [];
    }
  }
}
