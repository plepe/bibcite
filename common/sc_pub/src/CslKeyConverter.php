<?php

namespace Drupal\sc_pub;


class CslKeyConverter {

  const CSL_KEYS = [
    // Entity fields.
    'title' => 'title',
    'author' => 'author',
    'keywords' => 'keywords',
    // Date fields.
    'accessed' => 'sc_pub_accessed',
    'container' => 'sc_pub_container',
    'event-date' => 'sc_pub_event_date',
    'issued' => 'sc_pub_issued',
    'original-date' => 'sc_pub_original_date',
    'submitted' => 'sc_pub_submitted',
    // Number fields.
    'chapter-number' => 'sc_pub_chapter_number',
    'collection-number' => 'sc_pub_collection_number',
    'edition' => 'sc_pub_edition',
    'issue' => 'sc_pub_issue',
    'number' => 'sc_pub_number',
    'number-of-pages' => 'sc_pub_number_of_pages',
    'number-of-volumes' => 'sc_pub_number_of_volumes',
    'volume' => 'sc_pub_volume',
    // String fields.
    'collection-editor' => 'sc_pub_collection_editor',
    'composer' => 'sc_pub_composer',
    'container-author' => 'sc_pub_container_author',
    'director' => 'sc_pub_director',
    'editor' => 'sc_pub_editor',
    'editorial-director' => 'sc_pub_editorial_director',
    'illustrator' => 'sc_pub_illustrator',
    'interviewer' => 'sc_pub_interviewer',
    'original-author' => 'sc_pub_original_author',
    'recipient' => 'sc_pub_recipient',
    'reviewed-author' => 'sc_pub_reviewed_author',
    'translator' => 'sc_pub_translator',
    'abstract' => 'sc_pub_abstract',
    'annote' => 'sc_pub_annote',
    'archive' => 'sc_pub_archive',
    'archive_location' => 'sc_pub_archive_location',
    'archive-place' => 'sc_pub_archive_place',
    'authority' => 'sc_pub_authority',
    'call-number' => 'sc_pub_call_number',
    'citation-label' => 'sc_pub_citation_label',
    'citation-number' => 'sc_pub_citation_number',
    'collection-title' => 'sc_pub_collection_title',
    'container-title' => 'sc_pub_container_title',
    'container-title-short' => 'sc_pub_container_title_short',
    'dimensions' => 'sc_pub_dimensions',
    'DOI' => 'sc_pub_doi',
    'event' => 'sc_pub_event',
    'event-place' => 'sc_pub_event_place',
    'first-reference-note-number' => 'sc_pub_first_reference_note_number',
    'genre' => 'sc_pub_genre',
    'ISBN' => 'sc_pub_isbn',
    'ISSN' => 'sc_pub_issn',
    'jurisdiction' => 'sc_pub_jurisdiction',
    'locator' => 'sc_pub_locator',
    'medium' => 'sc_pub_medium',
    'note' => 'sc_pub_note',
    'original-publisher' => 'sc_pub_original_publisher',
    'original-publisher-place' => 'sc_pub_original_publisher_place',
    'original-title' => 'sc_pub_original_title',
    'page' => 'sc_pub_page',
    'page-first' => 'sc_pub_page_first',
    'PMID' => 'sc_pub_pmid',
    'PMCID' => 'sc_pub_pmcid',
    'publisher' => 'sc_pub_publisher',
    'publisher-place' => 'sc_pub_publisher_place',
    'references' => 'sc_pub_references',
    'reviewed-title' => 'sc_pub_reviewed_title',
    'scale' => 'sc_pub_scale',
    'section' => 'sc_pub_section',
    'source' => 'sc_pub_source',
    'status' => 'sc_pub_status',
    'title-short' => 'sc_pub_title_short',
    'URL' => 'sc_pub_url',
    'version' => 'sc_pub_version',
    'year-suffix' => 'sc_pub_year_suffix',
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
