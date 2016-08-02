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
   * List of date fields.
   *
   * @var array
   */
  protected $dateFields = [
    'bibcite_accessed' => 'accessed',
  ];

  /**
   * List of scalar fields.
   *
   * @var array
   */
  protected $scalarFields = [
    'bibcite_number' => 'number',
    'bibcite_number_of_pages' => 'pages',
    'bibcite_volume' => 'volume',
    'bibcite_annote' => 'annote',
    'bibcite_event_place' => 'address',
    'bibcite_note' => 'note',
    'bibcite_status' => 'status',
  ];

  /**
   * Mapping between CSL and RIS publication types.
   *
   * @var array
   */
  protected $typesMapping = [
    'ABST' => 'Abstract',
    'ADVS' => 'Audiovisual material',
    'AGGR' => 'Aggregated Database',
    'ANCIENT' => 'Ancient Text',
    'ART' => 'Art Work',
    'BILL' => 'Bill',
    'BLOG' => 'Blog',
    'BOOK' => 'Whole book',
    'CASE' => 'Case',
    'CHAP' => 'Book chapter',
    'CHART' => 'Chart',
    'CLSWK' => 'Classical Work',
    'COMP' => 'Computer program',
    'CONF' => 'Conference proceeding',
    'CPAPER' => 'Conference paper',
    'CTLG' => 'Catalog',
    'DATA' => 'Data file',
    'DBASE' => 'Online Database',
    'DICT' => 'Dictionary',
    'EBOOK' => 'Electronic Book',
    'ECHAP' => 'Electronic Book Section',
    'EDBOOK' => 'Edited Book',
    'EJOUR' => 'Electronic Article',
    'ELEC' => 'Web Page',
    'ENCYC' => 'Encyclopedia',
    'EQUA' => 'Equation',
    'FIGURE' => 'Figure',
    'GEN' => 'Generic',
    'GOVDOC' => 'Government Document',
    'GRANT' => 'Grant',
    'HEAR' => 'Hearing',
    'ICOMM' => 'Internet Communication',
    'INPR' => 'In Press',
    'JFULL' => 'Journal (full)',
    'JOUR' => 'Journal',
    'LEGAL' => 'Legal Rule or Regulation',
    'MANSCPT' => 'Manuscript',
    'MAP' => 'Map',
    'MGZN' => 'Magazine article',
    'MPCT' => 'Motion picture',
    'MULTI' => 'Online Multimedia',
    'MUSIC' => 'Music score',
    'NEWS' => 'Newspaper',
    'PAMP' => 'Pamphlet',
    'PAT' => 'Patent',
    'PCOMM' => 'Personal communication',
    'RPRT' => 'Report',
    'SER' => 'Serial publication',
    'SLIDE' => 'Slide',
    'SOUND' => 'Sound recording',
    'STAND' => 'Standard',
    'STAT' => 'Statute',
    'THES' => 'Thesis/Dissertation',
    'UNPB' => 'Unpublished work',
    'VIDEO' => 'Video recording',
  ];

  /**
   * {@inheritdoc}
   */
  public function normalize($bibliography, $format = NULL, array $context = array()) {
    $attributes = [];

    $attributes['TY'] = $this->convertType($bibliography->type->value);

    if ($authors = $this->extractAuthors($bibliography->author)) {
      $attributes += $authors;
    }

    $attributes['AB'] = $bibliography->bibcite_abstract->value;

    $attributes['AV'] = $bibliography->bibcite_archive_location->value;

    $attributes['CN'] = $bibliography->bibcite_call_number->value;

    $attributes['CY'] = $bibliography->bibcite_publisher_place->value;

    $attributes['DA'] = $bibliography->bibcite_original_date->value;

    $attributes['DO'] = $bibliography->bibcite_doi->value;

    $attributes['ED'] = $bibliography->bibcite_editor->value;

    $attributes['ET'] = $bibliography->bibcite_edition->value;

    $attributes['IS'] = $bibliography->bibcite_issue->value;

    if ($keywords = $this->extractKeywords($bibliography->keywords)) {
      $attributes['KW'] = $keywords;
    }

    $attributes['M1'] = $bibliography->bibcite_number->value;

    $attributes['NV'] = $bibliography->bibcite_number_of_volumes->value;

    $attributes['PB'] = $bibliography->bibcite_publisher->value;

    $attributes['PP'] = $bibliography->bibcite_publisher_place->value;

    $attributes['PY'] = $bibliography->bibcite_publisher_place->value;

    $attributes['SE'] = $bibliography->bibcite_section->value;

    $attributes['SE'] = $bibliography->bibcite_section->value;

    $attributes['SN'] = trim($bibliography->bibcite_isbn->value . '/' . $bibliography->bibcite_issn->value, '/');

    $attributes['ST'] = $bibliography->bibcite_title_short->value;

    $attributes['TI'] = $bibliography->title;

    $attributes['UR'] = $bibliography->bibcite_url->value;

    $attributes['VL'] = $bibliography->bibcite_volume->value;

    $attributes['Y1'] = $bibliography->bibcite_original_date->value;

    $attributes['Y2'] = $bibliography->bibcite_accessed->value;

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
