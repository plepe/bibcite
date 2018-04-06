<?php

namespace Drupal\bibcite_entity\Normalizer;

use Drupal\serialization\Normalizer\EntityNormalizer;

/**
 * Base normalizer class for bibcite formats.
 */
class KeywordNormalizer extends EntityNormalizer {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\bibcite_entity\Entity\Keyword'];

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    $storage = $this->entityManager->getStorage('bibcite_keyword');
    $label_key = $storage->getEntityType()->getKey('label');

    if (!empty($context['keyword_deduplication'])) {
      $label_key = $storage->getEntityType()->getKey('label');
      $query = $storage->getQuery()
        ->condition($label_key, trim($data))
        ->range(0, 1)
        ->execute();

      if (!empty($entity = $storage->loadMultiple($query))) {
        $entity = reset($entity);
        return $entity;
      }
    }
    $entity = $storage->create([$label_key => trim($data)]);
    return $entity;
  }

}
