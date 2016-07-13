<?php

namespace Drupal\bibcite;


class CslKeyConverter {

  const CSL_KEYS = [
    // Entity fields.
    'title' => 'title',
    'author' => 'author',
    'keywords' => 'keywords',
    // Date fields.
    'accessed' => 'bibcite_accessed',
    'container' => 'bibcite_container',
    'event-date' => 'bibcite_event_date',
    'issued' => 'bibcite_issued',
    'original-date' => 'bibcite_original_date',
    'submitted' => 'bibcite_submitted',
    // Number fields.
    'chapter-number' => 'bibcite_chapter_number',
    'collection-number' => 'bibcite_collection_number',
    'edition' => 'bibcite_edition',
    'issue' => 'bibcite_issue',
    'number' => 'bibcite_number',
    'number-of-pages' => 'bibcite_number_of_pages',
    'number-of-volumes' => 'bibcite_number_of_volumes',
    'volume' => 'bibcite_volume',
    // String fields.
    'collection-editor' => 'bibcite_collection_editor',
    'composer' => 'bibcite_composer',
    'container-author' => 'bibcite_container_author',
    'director' => 'bibcite_director',
    'editor' => 'bibcite_editor',
    'editorial-director' => 'bibcite_editorial_director',
    'illustrator' => 'bibcite_illustrator',
    'interviewer' => 'bibcite_interviewer',
    'original-author' => 'bibcite_original_author',
    'recipient' => 'bibcite_recipient',
    'reviewed-author' => 'bibcite_reviewed_author',
    'translator' => 'bibcite_translator',
    'abstract' => 'bibcite_abstract',
    'annote' => 'bibcite_annote',
    'archive' => 'bibcite_archive',
    'archive_location' => 'bibcite_archive_location',
    'archive-place' => 'bibcite_archive_place',
    'authority' => 'bibcite_authority',
    'call-number' => 'bibcite_call_number',
    'citation-label' => 'bibcite_citation_label',
    'citation-number' => 'bibcite_citation_number',
    'collection-title' => 'bibcite_collection_title',
    'container-title' => 'bibcite_container_title',
    'container-title-short' => 'bibcite_container_title_short',
    'dimensions' => 'bibcite_dimensions',
    'DOI' => 'bibcite_doi',
    'event' => 'bibcite_event',
    'event-place' => 'bibcite_event_place',
    'first-reference-note-number' => 'bibcite_first_reference_note_number',
    'genre' => 'bibcite_genre',
    'ISBN' => 'bibcite_isbn',
    'ISSN' => 'bibcite_issn',
    'jurisdiction' => 'bibcite_jurisdiction',
    'locator' => 'bibcite_locator',
    'medium' => 'bibcite_medium',
    'note' => 'bibcite_note',
    'original-publisher' => 'bibcite_original_publisher',
    'original-publisher-place' => 'bibcite_original_publisher_place',
    'original-title' => 'bibcite_original_title',
    'page' => 'bibcite_page',
    'page-first' => 'bibcite_page_first',
    'PMID' => 'bibcite_pmid',
    'PMCID' => 'bibcite_pmcid',
    'publisher' => 'bibcite_publisher',
    'publisher-place' => 'bibcite_publisher_place',
    'references' => 'bibcite_references',
    'reviewed-title' => 'bibcite_reviewed_title',
    'scale' => 'bibcite_scale',
    'section' => 'bibcite_section',
    'source' => 'bibcite_source',
    'status' => 'bibcite_status',
    'title-short' => 'bibcite_title_short',
    'URL' => 'bibcite_url',
    'version' => 'bibcite_version',
    'year-suffix' => 'bibcite_year_suffix',
  ];

  public static function normalizeKey($key) {
    $keys_mapping = static::CSL_KEYS;
    return isset($keys_mapping[$key]) ? $keys_mapping[$key] : NULL;
  }

  public static function denormalizeKey($key) {
    $keys_mapping = static::CSL_KEYS;
    $keys_mapping = array_flip($keys_mapping);
    return isset($keys_mapping[$key]) ? $keys_mapping[$key] : NULL;
  }

}
