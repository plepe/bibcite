<?php

namespace Drupal\bibcite_entity\Normalizer;

/**
 * Normalizes/denormalizes contributor entity to CSL format.
 */
class CslContributorEntityNormalizer extends CslNormalizerBase {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\bibcite_entity\Entity\ContributorInterface'];

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = array()) {
    $attributes = [
      'family' => $object->getLastName(),
      'given' => $object->getFirstName(),
      'suffix' => $object->getSuffix(),
      'literal' => $object->getName(),
      // @todo Implement another fields.
    ];

    return $attributes;
  }

}
