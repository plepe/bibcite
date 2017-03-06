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
  protected $contributorKey = 'AU';

  /**
   * {@inheritdoc}
   */
  protected $keywordKey = 'KW';

  /**
   * {@inheritdoc}
   */
  protected $typeKey = 'TY';

  /**
   * {@inheritdoc}
   */
  public function normalize($reference, $format = NULL, array $context = array()) {
    /** @var \Drupal\bibcite_entity\Entity\ReferenceInterface $reference */
    $attributes = [];

    $attributes['TY'] = $this->convertEntityType($reference->bundle());

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

}
