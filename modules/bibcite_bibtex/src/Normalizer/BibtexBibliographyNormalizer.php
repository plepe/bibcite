<?php

namespace Drupal\bibcite_bibtex\Normalizer;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\serialization\Normalizer\EntityNormalizer;
use Drupal\serialization\Normalizer\NormalizerBase;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Normalizes/denormalizes bibliography entity to BibTex format.
 */
class BibtexBibliographyNormalizer extends EntityNormalizer {

  /**
   * The format that this Normalizer supports.
   *
   * @var string
   */
  protected $format = 'bibtex';

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
    'bibcite_abst_e' => 'abstract',
    'bibcite_year' => 'year',
    'bibcite_secondary_title' => 'journal',
    'bibcite_volume' => 'volume',
    'bibcite_edition' => 'edition',
    'bibcite_section' => 'chapter',
    'bibcite_number' => 'number',
    'bibcite_pages' => 'pages',
    'bibcite_date' => 'month',
    'bibcite_publisher' => 'publisher',
    'bibcite_place_published' => 'address',
    'bibcite_issn' => 'issn',
    'bibcite_isbn' => 'isbn',
    'bibcite_url' => 'url',
    'bibcite_doi' => 'doi',
    'bibcite_notes' => 'note',
  ];

  /**
   * Mapping between CSL and BibTex publication types.
   *
   * @var array
   */
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
    $attributes['type'] = $this->convertType($bibliography->type->target_id);
    $attributes['reference'] = $bibliography->id();

    if ($keywords = $this->extractKeywords($bibliography->keywords)) {
      $attributes['keywords'] = $keywords;
    }

    if ($authors = $this->extractAuthors($bibliography->author)) {
      $attributes['author'] = $authors;
    }

    foreach ($this->scalarFields as $field_name => $bibtex_key) {
      if ($bibliography->{$field_name}->value) {
        $attributes[$bibtex_key] = $bibliography->{$field_name}->value;
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
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    if (!empty($data['author'])) {
      foreach ($data['author'] as $key => $author_name) {
        // @todo Find a better way to set authors.
        $data['author'][$key] = $this->prepareAuthor($author_name);
      }
    }

    if (!empty($data['keywords'])) {
      $data['keywords'] = explode(',', $data['keywords']);
      foreach ($data['keywords'] as $key => $keyword) {
        // @todo Find a better way to set keywords.
        $data['keywords'][$key] = $this->prepareKeyword($keyword);
      }
    }

    $data = $this->convertKeys($data);
    return parent::denormalize($data, $class, $format, $context);
  }

  /**
   * Convert author name string to Contributor object.
   *
   * @param string $author_name
   *   Raw author name string.
   *
   * @return \Drupal\bibcite_entity\Entity\ContributorInterface
   *   New contributor entity.
   */
  protected function prepareAuthor($author_name) {
    /** @var \Drupal\bibcite\HumanNameParserInterface $name_parser */
    $name_parser = \Drupal::service('bibcite.human_name_parser');
    $contributor_storage = $this->entityManager->getStorage('bibcite_contributor');
    $name_parts = $name_parser->parse($author_name);
    return $contributor_storage->create($name_parts);
  }

  /**
   * Convert keyword string to Keyword object.
   *
   * @param string $keyword
   *   Keyword string.
   *
   * @return \Drupal\bibcite_entity\Entity\KeywordInterface
   *   New keyword entity.
   */
  protected function prepareKeyword($keyword) {
    $storage = $this->entityManager->getStorage('bibcite_keyword');
    return $storage->create(['name' => trim($keyword)]);
  }

  /**
   * Convert bibtex keys to Bibtex keys and filter non-mapped.
   *
   * @param array $data
   *   Array of decoded values.
   *
   * @return array
   *   Array of decoded values with converted keys.
   */
  protected function convertKeys($data) {
    $fields = array_flip($this->scalarFields)
      + [
        'title' => 'title',
        'author' => 'author',
        'keywords' => 'keywords',
      ];
    $types = array_flip($this->typesMapping);

    $converted = [];
    foreach ($data as $key => $field) {
      if (isset($fields[$key])) {
        $converted[$fields[$key]] = $field;
      }
    }

    $converted['type'] = $types[$data['type']];

    return $converted;
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
