<?php

namespace Drupal\sc_pub_entity\Normalizer;

/**
 * Normalizes/denormalizes contributor field to CSL format.
 */
class CslEntityReferenceItemNormalizer extends CslNormalizerBase {

  /**
   * The format that this Normalizer supports.
   *
   * @var array
   */
  protected $format = ['csl'];

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem'];

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = array()) {
    return $object->entity->label();
  }

}
