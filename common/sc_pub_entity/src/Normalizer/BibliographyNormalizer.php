<?php

namespace Drupal\bibcite_entity\Normalizer;

use Drupal\bibcite\CslKeyConverter;
use Drupal\serialization\Normalizer\EntityNormalizer;

/**
 * Normalizes/denormalizes bibliography entity to CSL format.
 */
class BibliographyNormalizer extends CslNormalizerBase {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\bibcite_entity\Entity\BibliographyInterface'];

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = array()) {
    $attributes = [];

    foreach ($object as $name => $field) {
      if ($csl_key = CslKeyConverter::denormalizeKey($name)) {
        $attributes[$csl_key] = $this->serializer->normalize($field, $format, $context);
      }
    }

    return $attributes;
  }

}
