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

}
