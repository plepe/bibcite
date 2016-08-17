<?php

namespace Drupal\bibcite\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Processor plugins.
 */
interface BibCiteProcessorInterface extends PluginInspectionInterface {

  /**
   * Render citation string from CSL values array.
   *
   * @param array $data
   *   CSL values array.
   * @param string $style
   *   Citation style identifier.
   * @param string $lang
   *   Citation language.
   *
   * @return string Rendered citation.
   *   Rendered citation.
   */
  public function render(array $data, $style, $lang = 'en_US');

  /**
   * Get plugin description markup.
   *
   * @return mixed
   *   Description markup.
   */
  public function getDescription();

  /**
   * Get plugin label markup.
   *
   * @return mixed
   *   Label markup.
   */
  public function getPluginLabel();

  /**
   * Get list of available bibliography styles.
   *
   * @return array
   *   Bibliography styles list.
   */
  public function getAvailableStyles();

}
