<?php

namespace Drupal\bibcite_entity\Normalizer;

/**
 * Normalizes/denormalizes contributor field to CSL format.
 */
class ContributorFieldNormalizer extends CslNormalizerBase {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\bibcite_entity\Plugin\Field\FieldType\ContributorFieldInterface'];

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = array()) {
    $attributes = [
      'category' => $object->category,
      'role' => $object->role,
    ] + $this->serializer->normalize($object->entity, $format, $context);

    return $attributes;
  }

}
