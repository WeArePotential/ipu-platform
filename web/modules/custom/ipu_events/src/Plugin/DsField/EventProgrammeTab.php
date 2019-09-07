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
    // @todo Check if there are any sessions before returning the tab content.
    $have_sessions = FALSE;
    $node = $this->entity();

    if ($node->bundle() == 'ipu_event') {
      $current_language = \Drupal::languageManager()
        ->getCurrentLanguage()
        ->getId();

      if (!$node->field_ipu_event_sessions->isEmpty()) {
        $have_sessions = TRUE;
      }
    }

    if ($have_sessions) {
      return ['#markup' => $this->t('Programme'),];
    } else {
      return [];
    }
	}

}
