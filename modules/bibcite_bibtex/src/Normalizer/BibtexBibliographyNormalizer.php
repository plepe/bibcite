<?php

namespace Drupal\bibcite_bibtex\Normalizer;

use Drupal\bibcite_entity\Normalizer\BibliographyNormalizerBase;

/**
 * Normalizes/denormalizes bibliography entity to BibTex format.
 */
class BibtexBibliographyNormalizer extends BibliographyNormalizerBase {

  /**
   * {@inheritdoc}
   */
  protected $format = 'bibtex';

  /**
   * {@inheritdoc}
   */
  protected $defaultType = 'unassigned';

  /**
   * {@inheritdoc}
   */
  public function normalize($bibliography, $format = NULL, array $context = array()) {
    /** @var \Drupal\bibcite_entity\Entity\BibliographyInterface $bibliography */

    $attributes = [];

    $attributes['title'] = $this->extractScalar($bibliography->get('title'));
    $attributes['type'] = $this->convertEntityType($bibliography->get('type')->target_id);
    $attributes['reference'] = $bibliography->id();

    if ($keywords = $this->extractKeywords($bibliography->get('keywords'))) {
      $attributes['keywords'] = $keywords;
    }

    if ($authors = $this->extractAuthors($bibliography->get('author'))) {
      $attributes['author'] = $authors;
    }

    $attributes += $this->extractFields($bibliography);

    return $attributes;
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

    if (!empty($data['type'])) {
      $data['type'] = $this->convertFormatType($data['type']);
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
