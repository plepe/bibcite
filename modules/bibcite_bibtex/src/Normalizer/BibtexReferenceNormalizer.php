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
  protected $contributorKey = 'author';

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
  public function normalize($reference, $format = NULL, array $context = array()) {
    /** @var \Drupal\bibcite_entity\Entity\ReferenceInterface $reference */

    $attributes = [];

    $attributes['title'] = $this->extractScalar($reference->get('title'));
    $attributes['type'] = $this->convertEntityType($reference->bundle());
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

}
