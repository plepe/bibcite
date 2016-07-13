<?php

namespace Drupal\bibcite;

/**
 * Define public interface for the citeproc-php library.
 */
interface CiteprocPhpInterface {

  /**
   * Render bibliography data from CSL values.
   *
   * @param array|\stdClass $values
   *   Array of object of CSL values for rendering.
   * @param string $style
   *   Citation style identifier.
   * @param string $lang
   *   Language code.
   *
   * @return string
   *   Rendered bibliography citation.
   */
  public function render($values, $style, $lang = 'en-US');

  /**
   * Get list of all available citation styles.
   *
   * @return array
   *   List of all available styles.
   */
  public function getStyles();

  /**
   * Get list of all available locales.
   *
   * @return array
   *   List of all available locales.
   */
  public function getLocales();

}
