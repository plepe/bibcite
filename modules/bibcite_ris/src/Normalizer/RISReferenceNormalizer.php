<?php

namespace Drupal\bibcite_ris\Normalizer;

use Drupal\bibcite_entity\Normalizer\ReferenceNormalizerBase;

/**
 * Normalizes/denormalizes reference entity to RIS format.
 */
class RISReferenceNormalizer extends ReferenceNormalizerBase {

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
  public function normalize($reference, $format = NULL, array $context = array()) {
    /** @var \Drupal\bibcite_entity\Entity\ReferenceInterface $reference */
    $attributes = [];

    $attributes['TY'] = $this->convertEntityType($reference->get('type')->target_id);

    if ($authors = $this->extractAuthors($reference->get('author'))) {
      $attributes['AU'] = $authors;
    }

    if ($keywords = $this->extractKeywords($reference->get('keywords'))) {
      $attributes['KW'] = $keywords;
    }

    $isbn = $this->extractScalar($reference->get('bibcite_isbn'));
    $issn = $this->extractScalar($reference->get('bibcite_issn'));
    if ($isbn || $issn) {
      $attributes['SN'] = trim($isbn . '/' . $issn, '/');
    }

    $attributes += $this->extractFields($reference);

    return $attributes;
  }

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    if (!empty($data['AU'])) {
      if (!is_array($data['AU'])) {
        $data['AU'] = [$data['AU']];
      }

      foreach ($data['AU'] as $key => $author_name) {
        // @todo Find a better way to set authors.
        $data['AU'][$key] = $this->prepareAuthor($author_name);
      }
    }

    if (!empty($data['KW'])) {
      if (!is_array($data['KW'])) {
        $data['KW'] = [$data['KW']];
      }

      foreach ($data['KW'] as $key => $keyword) {
        // @todo Find a better way to set keywords.
        $data['KW'][$key] = $this->prepareKeyword($keyword);
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
