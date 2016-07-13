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
   * @param array $values
   *   CSL values array.
   * @param string $style
   *   Citation style identifier.
   *
   * @return string
   *   Rendered citation.
   */
  public function render(array $values, $style);

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

  /**
   * Get identifier of default style.
   *
   * @return string
   *   Default style identifier.
   */
  public function getDefaultStyleId();

}
