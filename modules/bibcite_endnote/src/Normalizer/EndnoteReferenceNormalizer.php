<?php

namespace Drupal\bibcite_endnote\Normalizer;

use Drupal\bibcite_entity\Normalizer\ReferenceNormalizerBase;

/**
 * Normalizes/denormalizes reference entity to Endnote format.
 */
class EndnoteReferenceNormalizer extends ReferenceNormalizerBase {

  /**
   * {@inheritdoc}
   */
  protected $format = ['endnote7', 'endnote8', 'tagged'];

  /**
   * {@inheritdoc}
   */
  protected $defaultType = 'Generic';

  /**
   * {@inheritdoc}
   */
  protected $contributorKey = 'authors';

  /**
   * {@inheritdoc}
   */
  protected $keywordKey = 'keywords';

  /**
   * {@inheritdoc}
   */
  protected $typeKey = 'type';

  /**
   * {@inheritdoc}
   */
  public function normalize($reference, $format = NULL, array $context = []) {
    /** @var \Drupal\bibcite_entity\Entity\ReferenceInterface $reference */
    $attributes = [];

    $attributes['type'] = $this->convertEntityType($reference->bundle(), $format);

    if ($authors = $this->extractAuthors($reference->get('author'))) {
      $attributes['authors'] = $authors;
    }

    if ($keywords = $this->extractKeywords($reference->get('keywords'))) {
      $attributes['keywords'] = $keywords;
    }

    $attributes['title'] = $this->extractScalar($reference->get('title'));

    $attributes += $this->extractFields($reference, $format);

    return $attributes;
  }

}
