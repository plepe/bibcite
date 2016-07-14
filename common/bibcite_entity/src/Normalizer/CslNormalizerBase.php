<?php

namespace Drupal\bibcite_entity\Normalizer;


use Drupal\serialization\Normalizer\NormalizerBase;

/**
 * Base class for CSL normalizers.
 */
abstract class CslNormalizerBase extends NormalizerBase {

  /**
   * Checks if the provided format is supported by this normalizer.
   *
   * @param string $format
   *   The format to check.
   *
   * @return bool
   *   TRUE if the format is supported, FALSE otherwise. If no format is
   *   specified this will return FALSE.
   */
  protected function checkFormat($format = NULL) {
    if (!isset($format)) {
      return FALSE;
    }

    // @todo Find a better way to use CSL normalization for export/import formats.
    $formats = \Drupal::moduleHandler()->invokeAll('bibcite_entity_normalization_format');
    $formats = array_merge($formats, ['csl']);

    return in_array($format, $formats);
  }

}
