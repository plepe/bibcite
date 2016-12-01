<?php

namespace Drupal\bibcite_entity;


use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;

/**
 * Reference entity view builder.
 */
class ReferenceViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  protected function getBuildDefaults(EntityInterface $entity, $view_mode) {
    $build = parent::getBuildDefaults($entity, $view_mode);

    switch ($view_mode) {
      case 'default':
      case 'full':
        $build['#theme'] = 'bibcite_reference_table';
        break;

      case 'citation':
        $build['#theme'] = 'bibcite_citation';

        // @todo Dependency injection
        $serializer = \Drupal::service('serializer');
        $build['#data'] = $serializer->normalize($entity, 'csl');
        $build['#data']['#entity'] = $entity;
        break;
    }

    return $build;
  }

}
