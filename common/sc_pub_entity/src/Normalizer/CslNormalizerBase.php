<?php

namespace Drupal\sc_pub_entity\Normalizer;


use Drupal\serialization\Normalizer\NormalizerBase;

/**
 * Base class for CSL normalizers.
 */
abstract class CslNormalizerBase extends NormalizerBase {

  /**
   * The format that this Normalizer supports.
   *
   * @var array
   */
  protected $format = ['csl'];

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
    if (!isset($format) || !isset($this->format)) {
      return FALSE;
    }

    return in_array($format, (array) $this->format);
  }

}
