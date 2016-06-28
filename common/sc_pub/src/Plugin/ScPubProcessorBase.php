<?php

namespace Drupal\sc_pub\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Base class for Processor plugins.
 */
abstract class ScPubProcessorBase extends PluginBase implements ScPubProcessorInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getPluginLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return '';
  }

}
