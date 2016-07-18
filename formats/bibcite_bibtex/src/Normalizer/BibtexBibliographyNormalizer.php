<?php

namespace Drupal\bibcite_bibtex\Normalizer;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\serialization\Normalizer\NormalizerBase;

/**
 * Normalizes/denormalizes bibliography entity to BibTex format.
 */
class BibtexBibliographyNormalizer extends NormalizerBase {

  /**
   * {@inheritdoc}
   */
  protected $format = 'bibtex';

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\bibcite_entity\Entity\BibliographyInterface'];

  protected $dateFields = [
    'bibcite_accessed' => 'accessed',
  ];

  protected $scalarFields = [
    'bibcite_number' => 'number',
    'bibcite_number_of_pages' => 'pages',
    'bibcite_volume' => 'volume',
    'bibcite_annote' => 'annote',
    'bibcite_event_place' => 'address',
    'bibcite_note' => 'note',
    'bibcite_status' => 'status',
  ];

  protected $typesMapping = [
    'journal-article' => 'article',
    'book' => 'book',
    'pamphlet' => 'booklet',
    'chapter' => 'inbook',
    'paper-conference' => 'conference',
    'thesis' => 'phdthesis',
    'report' => 'techreport',
    'patent' => 'patent',
    'webpage' => 'electronic',
    'article' => 'other',
    'legislation' => 'standard',
    'manuscript' => 'unpublished',
  ];

  /**
   * {@inheritdoc}
   */
  public function normalize($bibliography, $format = NULL, array $context = array()) {
    $attributes = [];

    $attributes['title'] = $bibliography->title->value;
    $attributes['type'] = $this->convertType($bibliography->type->value);
    $attributes['reference'] = $bibliography->id();

    if ($keywords = $this->extractKeywords($bibliography->keywords)) {
      $attributes['keywords'] = $keywords;
    }

    if ($authors = $this->extractAuthors($bibliography->author)) {
      $attributes['author'] = $authors;
    }

    foreach ($this->dateFields as $field_name => $bibtex_key) {
      if ($bibliography->{$field_name}->value) {
        $attributes[$bibtex_key] = $this->extractDate($bibliography->{$field_name});
      }
    }

    foreach ($this->scalarFields as $field_name => $bibtex_key) {
      if ($bibliography->{$field_name}->value) {
        $attributes[$bibtex_key] = $this->extractScalar($bibliography->{$field_name});
      }
    }

    return $attributes;
  }

  /**
   * Extract date to BibTex format.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $date_field
   *   Date item list.
   *
   * @return string
   *   Date in BibTex format.
   */
  protected function extractDate(FieldItemListInterface $date_field) {
    return $date_field->value;
  }

  /**
   * Convert publication type to BibTex format.
   *
   * @param string $type
   *   CSL publication type.
   *
   * @return string
   *   BibTex publication type.
   */
  protected function convertType($type) {
    return isset($this->typesMapping[$type]) ? $this->typesMapping[$type] : 'unassigned';
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
      $keywords[] = $field->entity->label();
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
   *   Authors in BibTex format.
   */
  protected function extractAuthors(FieldItemListInterface $field_item_list) {
    $authors = [];

    foreach ($field_item_list as $field) {
      $authors[] = $field->entity->getName();
    }

    return $authors;
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
