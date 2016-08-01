<?php

namespace Drupal\bibcite_entity;


use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;

/**
 * Bibliography entity view builder.
 */
class BibliographyViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  protected function getBuildDefaults(EntityInterface $entity, $view_mode) {
    $build = parent::getBuildDefaults($entity, $view_mode);

    switch ($view_mode) {
      case 'full':
        $build['#theme'] = 'bibliography_table';
        break;

      case 'citation':
        $build['#theme'] = 'bibliography';

        // @todo Dependency injection
        $serializer = \Drupal::service('serializer');
        $build['#data'] = $serializer->normalize($entity, 'csl');
        $build['#data']['#entity'] = $entity;
        break;
    }

    return $build;
  }

}
