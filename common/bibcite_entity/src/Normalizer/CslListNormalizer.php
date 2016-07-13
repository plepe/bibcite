<?php

namespace Drupal\bibcite_entity\Normalizer;


class CslListNormalizer extends CslNormalizerBase {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = 'Drupal\Core\TypedData\ListInterface';

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = array()) {
    // @todo Move multiple items description to top level module and class
    $multiple_items = [
      'author' => TRUE,
      'keywords' => TRUE,
    ];

    $name = $object->getDataDefinition()->getName();

    if (count($object) === 1 && empty($multiple_items[$name])) {
      $attributes = $this->serializer->normalize($object->first(), $format);
    }
    else {
      $attributes = array();
      foreach ($object as $fieldItem) {
        $attributes[] = $this->serializer->normalize($fieldItem, $format);
      }
    }

    return $attributes;
  }

}
