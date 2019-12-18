<?php

namespace Drupal\ckeditor_removeformat\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "removeformat" plugin.
 *
 * @CKEditorPlugin(
 *   id = "removeformat",
 *   label = @Translation("Remove Formatting")
 * )
 */
class RemoveFormatPlugin extends CKEditorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    return [
      'removeformat' => [
        'label' => $this->t('Remove formatting'),
        'image' => base_path() . 'libraries/removeformat/icons/removeformat.png',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return 'libraries/removeformat/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    return [];
  }

}
