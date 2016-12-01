<?php

namespace Drupal\bibcite_bibtex\Normalizer;

use Drupal\bibcite_entity\Normalizer\ReferenceNormalizerBase;

/**
 * Normalizes/denormalizes reference entity to BibTex format.
 */
class BibtexReferenceNormalizer extends ReferenceNormalizerBase {

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
  public function normalize($reference, $format = NULL, array $context = array()) {
    /** @var \Drupal\bibcite_entity\Entity\ReferenceInterface $reference */

    $attributes = [];

    $attributes['title'] = $this->extractScalar($reference->get('title'));
    $attributes['type'] = $this->convertEntityType($reference->get('type')->target_id);
    $attributes['reference'] = $reference->id();

    if ($keywords = $this->extractKeywords($reference->get('keywords'))) {
      $attributes['keywords'] = $keywords;
    }

    if ($authors = $this->extractAuthors($reference->get('author'))) {
      $attributes['author'] = $authors;
    }

    $attributes += $this->extractFields($reference);

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
