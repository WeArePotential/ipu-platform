<?php

namespace Drupal\ipu_events\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;

/**
 * Plugin for a DS field containing event documents content.
 *
 * @DsField(
 *   id = "event_programme_tab",
 *   title = @Translation("Event Programme Tab"),
 *   entity_type = "node",
 *   provider = "ipu_events",
 *   ui_limit = {"ipu_event|*"}
 * )
 */

class EventProgrammeTab extends DsFieldBase {

  /**
   * {@inheritdoc}
   */
	public function build() {

	  $have_sessions = FALSE;
    $node = $this->entity();

    if ($node->field_hide_programs_menu->value == 1) {
      $have_sessions = FALSE;
      return [];
    }

    if ($node->bundle() == 'ipu_event') {
      $current_language = \Drupal::languageManager()
        ->getCurrentLanguage()
        ->getId();

      if (!$node->field_ipu_event_sessions->isEmpty()) {
        $have_sessions = TRUE;
      }
    }

    if ($have_sessions) {
      return [
        '#markup' => $this->t('Programme'),
        '#order' => (is_numeric(trim($node->field_agenda_order_number->value)) ? trim($node->field_agenda_order_number->value) : 0),
      ];
    } else {
      return [];
    }
	}

}
