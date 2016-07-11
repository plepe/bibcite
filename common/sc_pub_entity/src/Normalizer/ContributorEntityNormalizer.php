<?php

namespace Drupal\sc_pub_entity\Normalizer;

/**
 * Normalizes/denormalizes contributor entity to CSL format.
 */
class ContributorEntityNormalizer extends CslNormalizerBase {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\sc_pub_entity\Entity\ContributorInterface'];

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = array()) {
    $attributes = [
      'family' => $object->getLastName(),
      'given' => $object->getFirstName(),
      'suffix' => $object->getSuffix(),
      'literal' => $object->getName(),
    ];

    return $attributes;
  }

}
