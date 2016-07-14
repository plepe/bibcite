<?php

namespace Drupal\bibcite_entity\Normalizer;

/**
 * Normalizes/denormalizes bibliography entity to CSL format.
 */
class CslBibliographyNormalizer extends CslNormalizerBase {

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

    // Find a better way to convert entity fields to csl fields.
    /** @var \Drupal\bibcite\CslDataProviderInterface $csl_data_provider */
    $csl_data_provider = \Drupal::service('bibcite.csl_data_provider');
    $fields = $csl_data_provider->getFields();
    $fields_mapping = array_map(function($field) {
      return $field['schema_field'];
    }, $fields);
    $fields_mapping = array_flip($fields_mapping);

    foreach ($object as $name => $field) {
      if (isset($fields_mapping[$name])) {
        $attributes[$fields_mapping[$name]] = $this->serializer->normalize($field, $format, $context);
      }
    }

    $attributes['type'] = $this->serializer->normalize($object->type, $format, $context);

    return $attributes;
  }

}
