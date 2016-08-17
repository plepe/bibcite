<?php

namespace Drupal\bibcite;

use Drupal\bibcite\Plugin\BibCiteProcessorInterface;

/**
 * Defines an interface for Styler service.
 */
interface StylerInterface {

  /**
   * Render CSL data to bibliography citation.
   *
   * @param array $data
   *   Array of values in CSL format.
   * @param string|null $style
   *   Identifier of bibliography style.
   *   Default style will be used if this value is NULL.
   * @param string $lang
   *   Citation language.
   *
   * @return string Rendered bibliography citation.
   *   Rendered bibliography citation.
   */
  public function render(array $data, $style = NULL, $lang = 'en-US');

  /**
   * Set processor plugin.
   *
   * @param \Drupal\bibcite\Plugin\BibCiteProcessorInterface $processor
   *   Processtor plugin object.
   *
   * @return \Drupal\bibcite\StylerInterface
   *   The called Styler object.
   */
  public function setProcessor(BibCiteProcessorInterface $processor);

  /**
   * Load plugin object by identifier.
   *
   * @param string $processor_id
   *   Identifier of processor plugin.
   *
   * @return \Drupal\bibcite\StylerInterface
   *   The called Styler object.
   */
  public function setProcessorById($processor_id);

  /**
   * Get current processor plugin.
   *
   * @return \Drupal\bibcite\Plugin\BibCiteProcessorInterface|null
   *   Current processor plugin.
   */
  public function getProcessor();

  /**
   * Get all available processors plugins.
   *
   * @return array
   *   List of available processor plugins.
   */
  public function getAvailableProcessors();

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
