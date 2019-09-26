<?php

namespace Drupal\ipu_events\Element;

use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a event_document render element.
 *
 * New render element types are defined as plugins. They live in the
 * Drupal\{module_name}\Element namespace and implement
 * \Drupal\Core\Render\Element\ElementInterface. They are annotated with either
 * \Drupal\Core\Render\Annotation\RenderElement or
 * \Drupal\Core\Render\Annotation\FormElement. And extend either the
 * \Drupal\Core\Render\Element\RenderElement, or
 * \Drupal\Core\Render\Element\FormElement base classes.
 *
 * In the annotation below we define the string "marquee" as the ID for this
 * plugin. That will also be the value used for the '#type' property in a render
 * array. For example:
 *
 * @code
 * $build['awesome'] = [
 *   '#type' => 'marquee',
 *   '#content' => 'Whoa cools, a marquee!',
 * ];
 * @endcode
 *
 * View an example of this custom element in use in
 * \Drupal\render_example\Controller\RenderExampleController::arrays().
 *
 * @see plugin_api
 * @see ipu_events_theme()
 *
 * @RenderElement("event_documents")
 */
class EventDocuments extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return [
      '#theme' => 'ipu_events_event_documents',
      '#title' => '',
      '#documents' => '',
      '#session_documents' => '',
      //'#attributes' => [
      //  'direction' => 'left',
      //  'loop' => -1,
      //  'scrollamount' => 'random',
      //],
    ];
  }

}
