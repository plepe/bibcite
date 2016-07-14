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

  protected $fieldsMapping = [
    'title' => 'title',
    'author' => 'author',
    'keywords' => 'keywords',
    'bibcite_accessed' => 'accessed',
    'bibcite_container' => 'container',
    'bibcite_event_date' => 'event-date',
    'bibcite_issued' => 'issued',
    'bibcite_original_date' => 'original-date',
    'bibcite_submitted' => 'submitted',
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
  public function normalize($object, $format = NULL, array $context = array()) {
    $attributes = [];

    foreach ($object as $name => $field) {
      if (isset($this->fieldsMapping[$name])) {
        $attributes[$this->fieldsMapping[$name]] = $this->serializer->normalize($field, $format, $context);
      }
    }

    $attributes['type'] = $this->serializer->normalize($object->type, $format, $context);

    return $attributes;
  }

}
