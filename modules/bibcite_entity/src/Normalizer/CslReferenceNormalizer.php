<?php

namespace Drupal\bibcite_entity\Normalizer;

use Drupal\bibcite_entity\Entity\ReferenceInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Normalizes/denormalizes reference entity to CSL format.
 */
class CslReferenceNormalizer extends ReferenceNormalizerBase {

  /**
   * The format that this Normalizer supports.
   *
   * @var string
   */
  protected $format = 'csl';

  /**
   * {@inheritdoc}
   */
  protected $defaultType = '';

  /**
   * List of date fields.
   *
   * @var array
   */
  protected $dateFields = [
    'bibcite_year',
    'bibcite_access_date',
  ];

  /**
   * {@inheritdoc}
   */
  public function normalize($reference, $format = NULL, array $context = array()) {
    /** @var \Drupal\bibcite_entity\Entity\ReferenceInterface $reference */

    $attributes = [];

    $attributes['title'] = $this->extractScalar($reference->get('title'));
    $attributes['type'] = $this->convertEntityType($reference->bundle());

    if ($authors = $this->extractAuthors($reference->get('author'))) {
      $attributes['author'] = $authors;
    }

    if ($keywords = $this->extractKeywords($reference->get('keywords'))) {
      $attributes['keywords'] = $keywords;
    }

    $attributes += $this->extractFields($reference);

    return $attributes;
  }

  /**
   * Extract fields values from reference entity.
   *
   * @param \Drupal\bibcite_entity\Entity\ReferenceInterface $reference
   *   Reference entity object.
   *
   * @return array
   *   Array of entity values.
   */
  protected function extractFields(ReferenceInterface $reference) {
    $attributes = [];

    foreach ($this->fieldsMapping as $csl_field => $entity_field) {
      if ($entity_field && $reference->hasField($entity_field) && ($field = $reference->get($entity_field)) && !$field->isEmpty()) {
        if (in_array($entity_field, $this->dateFields)) {
          $attributes[$csl_field] = $this->extractDate($field);
        }
        else {
          $attributes[$csl_field] = $this->extractScalar($field);
        }
      }
    }

    return $attributes;
  }

  /**
   * Extract authors values from field.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $field_item_list
   *   List of field items.
   *
   * @return array
   *   Authors in CSL format.
   */
  protected function extractAuthors(FieldItemListInterface $field_item_list) {
    $authors = [];

    foreach ($field_item_list as $field) {
      /** @var \Drupal\bibcite_entity\Entity\ContributorInterface $contributor */
      if ($contributor = $field->entity) {
        $authors[] = [
          'category' => $field->category,
          'role' => $field->role,
          'family' => $contributor->getLastName(),
          'given' => $contributor->getFirstName(),
          'suffix' => $contributor->getSuffix(),
          'literal' => $contributor->getName(),
          // @todo Implement another fields.
        ];
      }
    }

    return $authors;
  }

  /**
   * Extract date value to CSL format.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $date_field
   *   Date item list.
   *
   * @return array
   *   Date in CSL format.
   */
  protected function extractDate(FieldItemListInterface $date_field) {
    $value = $this->extractScalar($date_field);

    return [
      'date-parts' => [
        [$value],
      ],
      'literal' => $value,
    ];
  }

}
