<?php

namespace Drupal\sc_pub\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Processor item annotation object.
 *
 * @see \Drupal\sc_pub\Plugin\ScPubProcessorManager
 * @see plugin_api
 *
 * @Annotation
 */
class ScPubProcessor extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
