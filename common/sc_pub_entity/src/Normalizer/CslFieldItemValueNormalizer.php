<?php

namespace Drupal\bibcite_entity\Normalizer;

/**
 * Normalizes/denormalizes field item to CSL format.
 */
class CslFieldItemValueNormalizer extends CslNormalizerBase {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = [
    'Drupal\Core\Field\Plugin\Field\FieldType\StringItem',
    'Drupal\Core\Field\Plugin\Field\FieldType\IntegerItem',
  ];

  /**
   * {@inheritdoc}
   */
  public function normalize($field_item, $format = NULL, array $context = array()) {
    return $field_item->value;
  }

}
