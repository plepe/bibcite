<?php

namespace Drupal\bibcite_entity\Normalizer;

use Drupal\serialization\Normalizer\EntityNormalizer;

/**
 * Base normalizer class for bibcite formats.
 */
class ContributorNormalizer extends EntityNormalizer {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\bibcite_entity\Entity\ContributorInterface'];

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    $storage = $this->entityManager->getStorage('bibcite_contributor');

    if (!empty($context['contributor_deduplication'])) {
      $author_name_parsed = \Drupal::service('bibcite.human_name_parser')
        ->parse($data);
      $query = $storage->getQuery()->range(0, 1);
      foreach ($author_name_parsed as $name_part => $value) {
        if (empty($value)) {
          $query->notExists($name_part);
        }
        else {
          $query->condition($name_part, $value);
        }
      }

      if (!empty($entity = $storage->loadMultiple($query->execute()))) {
        $entity = reset($entity);
        return $entity;
      }
    }
    $entity = $storage->create(['name' => $data]);
    return $entity;
  }

}
