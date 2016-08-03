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
    'bibcite_accessed' => 'accessed',
    'bibcite_container' => 'container',
    'bibcite_event_date' => 'event-date',
    'bibcite_issued' => 'issued',
    'bibcite_original_date' => 'original-date',
    'bibcite_submitted' => 'submitted',
  ];

  /**
   * List of scalar fields.
   *
   * @var array
   */
  protected $scalarFields = [
    'bibcite_chapter_number' => 'chapter-number',
    'bibcite_collection_number' => 'collection-number',
    'bibcite_edition' => 'edition',
    'bibcite_issue' => 'issue',
    'bibcite_number' => 'number',
    'bibcite_number_of_pages' => 'number-of-pages',
    'bibcite_number_of_volumes' => 'number-of-volumes',
    'bibcite_volume' => 'volume',
    'bibcite_collection_editor' => 'collection-editor',
    'bibcite_composer' => 'composer',
    'bibcite_container_author' => 'container-author',
    'bibcite_director' => 'director',
    'bibcite_editor' => 'editor',
    'bibcite_editorial_director' => 'editorial-director',
    'bibcite_illustrator' => 'illustrator',
    'bibcite_interviewer' => 'interviewer',
    'bibcite_original_author' => 'original-author',
    'bibcite_recipient' => 'recipient',
    'bibcite_reviewed_author' => 'reviewed-author',
    'bibcite_translator' => 'translator',
    'bibcite_abstract' => 'abstract',
    'bibcite_annote' => 'annote',
    'bibcite_archive' => 'archive',
    'bibcite_archive_location' => 'archive_location',
    'bibcite_archive_place' => 'archive-place',
    'bibcite_authority' => 'authority',
    'bibcite_call_number' => 'call-number',
    'bibcite_citation_label' => 'citation-label',
    'bibcite_citation_number' => 'citation-number',
    'bibcite_collection_title' => 'collection-title',
    'bibcite_container_title' => 'container-title',
    'bibcite_container_title_short' => 'container-title-short',
    'bibcite_dimensions' => 'dimensions',
    'bibcite_doi' => 'DOI',
    'bibcite_event' => 'event',
    'bibcite_event_place' => 'event-place',
    'bibcite_first_reference_note_number' => 'first-reference-note-number',
    'bibcite_genre' => 'genre',
    'bibcite_isbn' => 'ISBN',
    'bibcite_issn' => 'ISSN',
    'bibcite_jurisdiction' => 'jurisdiction',
    'bibcite_locator' => 'locator',
    'bibcite_medium' => 'medium',
    'bibcite_note' => 'note',
    'bibcite_original_publisher' => 'original-publisher',
    'bibcite_original_publisher_place' => 'original-publisher-place',
    'bibcite_original_title' => 'original-title',
    'bibcite_page' => 'page',
    'bibcite_page_first' => 'page-first',
    'bibcite_pmid' => 'PMID',
    'bibcite_pmcid' => 'PMCID',
    'bibcite_publisher' => 'publisher',
    'bibcite_publisher_place' => 'publisher-place',
    'bibcite_references' => 'references',
    'bibcite_reviewed_title' => 'reviewed-title',
    'bibcite_scale' => 'scale',
    'bibcite_section' => 'section',
    'bibcite_source' => 'source',
    'bibcite_status' => 'status',
    'bibcite_title_short' => 'title-short',
    'bibcite_url' => 'URL',
    'bibcite_version' => 'version',
    'bibcite_year_suffix' => 'year-suffix',
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
    $date_pars = date_parse($date_field->value);

    return [
      'date-parts' => [
        [$date_pars['year']],
        [$date_pars['month']],
        [$date_pars['day']],
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
