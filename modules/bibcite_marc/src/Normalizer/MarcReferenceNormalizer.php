<?php

namespace Drupal\bibcite_marc\Normalizer;

use Drupal\bibcite_entity\Normalizer\ReferenceNormalizerBase;

/**
 * Normalizes/denormalizes reference entity to Marc format.
 */
class MarcReferenceNormalizer extends ReferenceNormalizerBase {

  /**
   * {@inheritdoc}
   */
  protected $format = 'marc';

  /**
   * {@inheritdoc}
   */
  protected $defaultType = 'misc';

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

    $attributes['title'] = $this->extractScalar($reference->get('title'));
    $attributes[$this->typeKey] = $this->convertEntityType($reference->bundle(), $format);
    $attributes['reference'] = $reference->id();

    if ($keywords = $this->extractKeywords($reference->get('keywords'))) {
      $attributes[$this->keywordKey] = $keywords;
    }

    if ($authors = $this->extractAuthors($reference->get('author'))) {
      $attributes[$this->contributorKey] = $authors;
    }

    $attributes += $this->extractFields($reference, $format);

    return $attributes;
  }

}
