<?php

namespace Drupal\bibcite_ris\Normalizer;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\serialization\Normalizer\NormalizerBase;

/**
 * Normalizes/denormalizes bibliography entity to RIS format.
 */
class RISBibliographyNormalizer extends NormalizerBase {

  /**
   * The format that this Normalizer supports.
   *
   * @var string
   */
  protected $format = 'ris';

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\bibcite_entity\Entity\BibliographyInterface'];

  /**
   * List of scalar fields.
   *
   * @var array
   */
  protected $scalarFields = [
    'bibcite_abst_e' => 'AB',
    'bibcite_year' => 'Y1',
    'bibcite_secondary_title' => 'T2',
    'bibcite_volume' => 'VL',
    'bibcite_issue' => 'IS',
    'bibcite_publisher' => 'PB',
    'bibcite_place_published' => 'CY',
    'bibcite_url' => 'UR',
    'bibcite_notes' => 'N1',
    'bibcite_tertiary_title' => 'T3',
    'bibcite_short_title' => 'J2',
    'bibcite_custom1' => 'U1',
    'bibcite_custom2' => 'U2',
    'bibcite_custom3' => 'U3',
    'bibcite_custom4' => 'U4',
    'bibcite_custom5' => 'U5',
  ];

  /**
   * Mapping between CSL and RIS publication types.
   *
   * @var array
   */
  protected $typesMapping = [
    'bill' => 'BILL',
    'book' => 'BOOK',
    'chapter' => 'CHAP',
    'case' => 'CASE',
    'paper-conference' => 'CONF',
    'motion_picture' => 'MPCT',
    'hearing' => 'HEAR',
    'article-journal' => 'JOUR',
    'article-magazine' => 'MGZN',
    'manuscript' => 'MANSCPT',
    'map' => 'MAP',
    'article-newspaper' => 'NEWS',
    'patent' => 'PAT',
    'personal_communication' => 'PCOMM',
    'report' => 'RPRT',
    'software' => 'COMP',
    'thesis' => 'THES',
    'unpublished' => 'UNPB',
    'webpage' => 'ICOMM',
  ];

  /**
   * {@inheritdoc}
   */
  public function normalize($bibliography, $format = NULL, array $context = array()) {
    $attributes = [];

    $attributes['TY'] = $this->convertType($bibliography->type->target_id);

    if ($authors = $this->extractAuthors($bibliography->author)) {
      $attributes += $authors;
    }

    if ($keywords = $this->extractKeywords($bibliography->keywords)) {
      $attributes['KW'] = $keywords;
    }

    if ($bibliography->bibcite_isbn->value || $bibliography->bibcite_issn->value) {
      $attributes['SN'] = trim($bibliography->bibcite_isbn->value . '/' . $bibliography->bibcite_issn->value, '/');
    }

    foreach ($this->scalarFields as $field_name => $ris_key) {
      if ($bibliography->{$field_name}->value) {
        $attributes[$ris_key] = $bibliography->{$field_name}->value;
      }
    }

    return $attributes;
  }

  /**
   * Convert publication type to BibTex format.
   *
   * @param string $type
   *   CSL publication type.
   *
   * @return string
   *   RIS publication type.
   */
  protected function convertType($type) {
    return isset($this->typesMapping[$type]) ? $this->typesMapping[$type] : 'GEN';
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
   *   Authors in RIS format.
   */
  protected function extractAuthors(FieldItemListInterface $field_item_list) {
    $authors = [];

    foreach ($field_item_list as $key => $field) {
      $authors['A' . ($key + 1)] = $field->entity->getName();
    }

    return $authors;
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
