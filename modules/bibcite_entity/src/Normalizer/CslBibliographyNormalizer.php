<?php

namespace Drupal\bibcite_entity\Normalizer;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\serialization\Normalizer\NormalizerBase;

/**
 * Normalizes/denormalizes bibliography entity to CSL format.
 */
class CslBibliographyNormalizer extends NormalizerBase {

  /**
   * The format that this Normalizer supports.
   *
   * @var string
   */
  protected $format = 'csl';

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\bibcite_entity\Entity\BibliographyInterface'];

  /**
   * List of date fields.
   *
   * @var array
   */
  protected $dateFields = [
    'bibcite_year' => 'issued',
    'bibcite_access_date' => 'accessed',
  ];

  /**
   * List of scalar fields.
   *
   * @var array
   */
  protected $scalarFields = [
    'bibcite_abst_e' => 'abstract',
    'bibcite_secondary_title' => 'container-title',
    'bibcite_volume' => 'volume',
    'bibcite_edition' => 'edition',
    'bibcite_section' => 'section',
    'bibcite_issue' => 'issue',
    'bibcite_number_of_volumes' => 'number-of-volumes',
    'bibcite_number' => 'number',
    'bibcite_pages' => 'page',
    'bibcite_date' => 'original-date',
    'bibcite_publisher' => 'publisher',
    'bibcite_place_published' => 'publisher-place',
    'bibcite_issn' => 'ISSN',
    'bibcite_isbn' => 'ISBN',
    'bibcite_call_number' => 'call-number',
    'bibcite_citekey' => 'citation-label',
    'bibcite_url' => 'URL',
    'bibcite_doi' => 'DOI',
    'bibcite_notes' => 'note',
    'bibcite_alternate_title' => 'original-title',
  ];

  /**
   * {@inheritdoc}
   */
  public function normalize($bibliography, $format = NULL, array $context = array()) {
    $attributes = [];

    $attributes['title'] = $bibliography->title->value;
    $attributes['type'] = $bibliography->type->value;

    if ($authors = $this->extractAuthors($bibliography->author)) {
      $attributes['author'] = $authors;
    }

    if ($keywords = $this->extractKeywords($bibliography->keywords)) {
      $attributes['keywords'] = $keywords;
    }

    foreach ($this->dateFields as $field_name => $csl_key) {
      if ($bibliography->{$field_name}->value) {
        $attributes[$csl_key] = $this->extractDate($bibliography->{$field_name});
      }
    }

    foreach ($this->scalarFields as $field_name => $csl_key) {
      if ($bibliography->{$field_name}->value) {
        $attributes[$csl_key] = $this->extractScalar($bibliography->{$field_name});
      }
    }

    return $attributes;
  }

  /**
   * Extract keywords labels from field.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $field_item_list
   *   List of field items.
   *
   * @return array
   *   Keywords labels.
   */
  protected function extractKeywords(FieldItemListInterface $field_item_list) {
    $keywords = [];

    foreach ($field_item_list as $field) {
      if ($keyword = $field->entity) {
        $keywords[] = $field->entity->label();
      }
    }

    return $keywords;
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
    return [
      'date-parts' => [
        [$date_field->value],
      ],
      'literal' => $date_field->value,
    ];
  }

  /**
   * Extract scalar value to CSL format.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $scalar_field
   *   Number item list.
   *
   * @return mixed
   *   Scalar in CSL format.
   */
  protected function extractScalar(FieldItemListInterface $scalar_field) {
    return $scalar_field->value;
  }

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
