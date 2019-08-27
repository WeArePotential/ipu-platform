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
	public function build(){
    // @todo Check if there are any documents before returning the tab content.
    return [
      '#markup' => $this->t('Documents'),
    ];
	}

}
