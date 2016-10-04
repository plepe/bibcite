<?php

namespace Drupal\bibcite_ris\Normalizer;

use Drupal\bibcite_entity\Normalizer\BibliographyNormalizerBase;

/**
 * Normalizes/denormalizes bibliography entity to RIS format.
 */
class RISBibliographyNormalizer extends BibliographyNormalizerBase {

  /**
   * {@inheritdoc}
   */
  protected $format = 'ris';

  /**
   * {@inheritdoc}
   */
  protected $defaultType = 'GEN';

  /**
   * {@inheritdoc}
   */
  public function normalize($bibliography, $format = NULL, array $context = array()) {
    /** @var \Drupal\bibcite_entity\Entity\BibliographyInterface $bibliography */
    $attributes = [];

    $attributes['TY'] = $this->convertEntityType($bibliography->get('type')->target_id);

    if ($authors = $this->extractAuthors($bibliography->get('author'))) {
      $attributes['AU'] = $authors;
    }

    if ($keywords = $this->extractKeywords($bibliography->get('keywords'))) {
      $attributes['KW'] = $keywords;
    }

    $isbn = $this->extractScalar($bibliography->get('bibcite_isbn'));
    $issn = $this->extractScalar($bibliography->get('bibcite_issn'));
    if ($isbn || $issn) {
      $attributes['SN'] = trim($isbn . '/' . $issn, '/');
    }

    $attributes += $this->extractFields($bibliography);

    return $attributes;
  }

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    if (!empty($data['AU'])) {
      foreach ($data['AU'] as $key => $author_name) {
        // @todo Find a better way to set authors.
        $data['AU'][$key] = $this->prepareAuthor($author_name);
      }
    }

    if (!empty($data['KW'])) {
      foreach ($data['KW'] as $key => $keyword) {
        // @todo Find a better way to set keywords.
        $data['KW'][$key] = $this->prepareKeyword($keyword);
      }
    }

    foreach ($data as $key => $value) {
      if (is_array($value) && count($value) == 1) {
        $data[$key] = reset($value);
      }
    }

    if (!empty($data['TY'])) {
      $data['TY'] = $this->convertFormatType($data['TY']);
    }

    $data = $this->convertKeys($data);

    return parent::denormalize($data, $class, $format, $context);
  }

  /**
   * Convert bibtex keys to Bibcite entity keys and filter non-mapped.
   *
   * @param array $data
   *   Array of decoded values.
   *
   * @return array
   *   Array of decoded values with converted keys.
   */
  protected function convertKeys($data) {
    $converted = [];
    foreach ($data as $key => $field) {
      if (!empty($this->fieldsMapping[$key])) {
        $converted[$this->fieldsMapping[$key]] = $field;
      }
    }

    return $converted;
  }

}
